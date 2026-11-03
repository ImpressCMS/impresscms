<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use LogicException;
use Symfony\Component\HttpFoundation\Request;

/**
 * A form for an IPF entity, built from its metadata (design L31): its
 * fields, the submitted values, Laravel's validation of them and, when they
 * are valid, the entity with those values applied.
 *
 * Values are read from the top level of the request body, under the names the
 * legacy IPF form posts (`extension`, `dirname[]`, the permission names).
 * Other keys of that form (`op`, `fct`, `icms_page_before_form`, the token)
 * are ignored. Only submitted fields are validated and written, as
 * Icms\Ipf\Controller::postDataToObject() does for the keys in $_POST; a
 * permission the browser did not send selects no group.
 */
final class IpfForm
{
    /** @var array<string, mixed>|null field name => submitted value */
    private ?array $submitted = null;

    private ?Validator $validation = null;

    /**
     * @param array<string, IpfField> $fields
     */
    public function __construct(
        private readonly object $entity,
        private readonly array $fields,
        private readonly ValidationFactory $validator,
    ) {
    }

    public function entity(): object
    {
        return $this->entity;
    }

    /** @return array<string, IpfField> */
    public function fields(): array
    {
        return $this->fields;
    }

    public function field(string $name): IpfField
    {
        return $this->fields[$name] ?? throw new LogicException("The form has no field \"{$name}\".");
    }

    public function has(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    public function submit(Request $request): self
    {
        $input = $request->request->all();
        $this->submitted = array_intersect_key($input, $this->fields);
        $this->validation = $this->validator->make($this->submitted, ...$this->validationArguments());

        if ($this->validation->fails()) {
            return $this;
        }

        foreach ($this->submitted as $name => $value) {
            $field = $this->fields[$name];
            if (! $field->mapped || $field->readonly) {
                continue;
            }

            IpfEntityValues::write($this->entity, $name, $value);
        }

        return $this;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted !== null;
    }

    public function fails(): bool
    {
        if ($this->validation === null) {
            throw new LogicException('The form has not been submitted.');
        }

        return $this->validation->fails();
    }

    /**
     * The validation errors, each naming its field's caption.
     *
     * @return list<string>
     */
    public function errors(): array
    {
        if ($this->validation === null) {
            return [];
        }

        return array_values(array_unique($this->validation->errors()->all()));
    }

    /**
     * The groups selected for a permission field; none when it was not sent.
     *
     * @return list<int>
     */
    public function permissionGroups(string $name): array
    {
        $value = $this->submitted[$name] ?? [];

        return array_values(array_map('intval', is_array($value) ? $value : [$value]));
    }

    /**
     * @return array{
     *     0: array<string, list<mixed>>,
     *     1: array<string, string>,
     *     2: array<string, string>
     * } rules, messages and attribute names for the validator
     */
    private function validationArguments(): array
    {
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($this->fields as $name => $field) {
            if ($field->hidden) {
                continue;
            }

            $rules[$name] = $field->rules;
            $attributes[$name] = $field->caption;
            if ($field->itemRules !== []) {
                $rules["{$name}.*"] = $field->itemRules;
                $attributes["{$name}.*"] = $field->caption;
            }

            // `required` => `extension.required`, `*.in` => `dirname.*.in`
            foreach ($field->messages as $rule => $message) {
                $messages["{$name}.{$rule}"] = $message;
            }
        }

        return [$rules, $messages, $attributes];
    }
}
