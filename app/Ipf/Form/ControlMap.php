<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Illuminate\Validation\Rule;

/**
 * Maps an IPF variable to a form field with Laravel validator rules
 * (design L31), following the choices Icms\Ipf\Form\Base::createElements()
 * makes for the legacy form:
 *
 * | IPF                                    | rules                         |
 * |----------------------------------------|-------------------------------|
 * | XOBJ_DTYPE_TXTBOX, control `text`      | string, max:<maxlength>       |
 * | XOBJ_DTYPE_TXTAREA, control `textarea` | string                        |
 * | XOBJ_DTYPE_INT                         | integer                       |
 * | control `select`, `selectmulti`        | in:<options> (each, multiple) |
 * | required variable                      | required, otherwise nullable  |
 *
 * Every field is `sometimes`: as Icms\Ipf\Controller::postDataToObject(),
 * only the submitted keys are validated and written. Messages name the field
 * by its caption, in the words of the legacy validation (_XOBJ_ERR_REQUIRED,
 * _XOBJ_ERR_SHORTERTHAN). Any other control or data type raises
 * UnsupportedIpfControlException.
 */
final class ControlMap
{
    /**
     * The field for a variable shown on the form.
     *
     * @param array<string, mixed> $var the variable definition (Entity::$vars[$key])
     * @param array<string, mixed>|null $control the variable's control, if any
     */
    public static function field(object $entity, string $key, array $var, ?array $control): IpfField
    {
        $caption = self::caption($key, $var);
        $field = self::fieldFor($entity, $key, $var, $control, $caption);

        $presence = $var['required'] ? 'required' : 'nullable';
        $messages = $field['messages'];
        if ($var['required']) {
            $messages['required'] = sprintf(_XOBJ_ERR_REQUIRED, $caption);
        }

        return new IpfField(
            name: $key,
            caption: $caption,
            description: ((string) ($var['form_dsc'] ?? '')) ?: null,
            readonly: ! empty($var['readonly']),
            multiple: $field['multiple'] ?? false,
            options: $field['options'] ?? null,
            rules: ['sometimes', $presence, ...$field['rules']],
            itemRules: $field['itemRules'] ?? [],
            messages: $messages,
            value: IpfEntityValues::read($entity, $key),
        );
    }

    /**
     * The group selection for a permission of the entity's handler, with the
     * groups that hold it now (for a new entity: the module's default).
     *
     * @param array<string, mixed> $permission an entry of the handler's getPermissions()
     * @param array<int|string, string> $groups group id => name
     */
    public static function permissionField(object $entity, array $permission, array $groups): IpfField
    {
        $name = (string) $permission['perm_name'];
        $caption = self::caption($name, ['form_caption' => $permission['caption']]);
        $selected = $entity->isNew()
            ? ($entity->handler->getModuleConfig()["def_perm_{$name}"] ?? false)
            : $entity->getGroupPerm($name);
        $invalid = self::invalidChoice($caption);

        return new IpfField(
            name: $name,
            caption: $caption,
            description: ((string) ($permission['description'] ?? '')) ?: null,
            mapped: false,
            permission: true,
            multiple: true,
            options: $groups,
            rules: ['sometimes', 'nullable', 'array'],
            itemRules: [Rule::in(array_map('strval', array_keys($groups)))],
            messages: ['array' => $invalid, '*.in' => $invalid],
            value: array_values(array_map('intval', (array) ($selected ?: []))),
        );
    }

    /**
     * The options of a select control, in the order the legacy select list
     * shows them (Icms\Ipf\Form\Elements\Select).
     *
     * @param array<string, mixed> $control
     * @return array<int|string, string> value => label
     */
    public static function options(object $entity, array $control): array
    {
        if (isset($control['options'])) {
            return (array) $control['options'];
        }

        if (isset($control['object'])) {
            $options = method_exists($control['object'], $control['method']) ? $control['object']->{$control['method']}() : [];

            return is_array($options) ? $options : [];
        }

        $handler = $entity->handler;
        if (isset($control['itemHandler'])) {
            $handler = isset($control['module'])
                ? icms_getModuleHandler($control['itemHandler'], $control['module'])
                : \icms::handler($control['itemHandler']);
        }

        if (! isset($control['method']) || ! method_exists($handler, $control['method'])) {
            return [];
        }

        $options = call_user_func_array([$handler, $control['method']], $control['params'] ?? []);

        return is_array($options) ? $options : [];
    }

    /**
     * The caption the legacy form shows for a variable, as plain text.
     *
     * @param array<string, mixed> $var
     */
    public static function caption(string $key, array $var): string
    {
        $caption = trim(html_entity_decode(strip_tags((string) ($var['form_caption'] ?? '')), ENT_QUOTES, 'UTF-8'));

        return $caption !== '' ? $caption : $key;
    }

    /**
     * @param array<string, mixed> $var
     * @param array<string, mixed>|null $control
     * @return array{
     *     rules: list<mixed>,
     *     messages: array<string, string>,
     *     multiple?: bool,
     *     options?: array<int|string, string>,
     *     itemRules?: list<mixed>
     * }
     */
    private static function fieldFor(object $entity, string $key, array $var, ?array $control, string $caption): array
    {
        $dataType = (int) $var['data_type'];

        if ($control === null) {
            return match ($dataType) {
                XOBJ_DTYPE_TXTBOX => self::text($var, $caption),
                XOBJ_DTYPE_TXTAREA => self::textarea($caption),
                XOBJ_DTYPE_INT => self::integer($caption),
                default => throw new UnsupportedIpfControlException($key, self::dataTypeName($dataType)),
            };
        }

        $name = (string) (($control['name'] ?? '') ?: 'select');

        return match ($name) {
            // the legacy form gives integers a text control
            'text' => $dataType === XOBJ_DTYPE_INT ? self::integer($caption) : self::text($var, $caption),
            'textarea' => self::textarea($caption),
            'select' => self::choice($entity, $control, $caption, false),
            'selectmulti' => self::choice($entity, $control, $caption, true),
            default => throw new UnsupportedIpfControlException($key, $name),
        };
    }

    /**
     * @param array<string, mixed> $var
     * @return array{
     *     rules: list<mixed>,
     *     messages: array<string, string>
     * }
     */
    private static function text(array $var, string $caption): array
    {
        $rules = ['string'];
        $messages = ['string' => "{$caption}: please enter text."];

        $maxlength = (int) ($var['maxlength'] ?? 0);
        if ($maxlength > 0) {
            $rules[] = "max:{$maxlength}";
            $messages['max'] = sprintf(_XOBJ_ERR_SHORTERTHAN, $caption, $maxlength);
        }

        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * @return array{
     *     rules: list<mixed>,
     *     messages: array<string, string>
     * }
     */
    private static function textarea(string $caption): array
    {
        return ['rules' => ['string'], 'messages' => ['string' => "{$caption}: please enter text."]];
    }

    /**
     * @return array{
     *     rules: list<mixed>,
     *     messages: array<string, string>
     * }
     */
    private static function integer(string $caption): array
    {
        return ['rules' => ['integer'], 'messages' => ['integer' => "{$caption}: please enter a whole number."]];
    }

    /**
     * @param array<string, mixed> $control
     * @return array{
     *     rules: list<mixed>,
     *     messages: array<string, string>,
     *     multiple: bool,
     *     options: array<int|string, string>,
     *     itemRules: list<mixed>
     * }
     */
    private static function choice(object $entity, array $control, string $caption, bool $multiple): array
    {
        $options = self::options($entity, $control);
        $in = Rule::in(array_map('strval', array_keys($options)));
        $invalid = self::invalidChoice($caption);

        if (! $multiple) {
            return ['rules' => [$in], 'messages' => ['in' => $invalid], 'multiple' => false, 'options' => $options, 'itemRules' => []];
        }

        return [
            'rules' => ['array'],
            'messages' => ['array' => $invalid, '*.in' => $invalid],
            'multiple' => true,
            'options' => $options,
            'itemRules' => [$in],
        ];
    }

    private static function invalidChoice(string $caption): string
    {
        return "{$caption}: the selected choice is invalid.";
    }

    private static function dataTypeName(int $type): string
    {
        foreach (get_defined_constants() as $name => $value) {
            if (str_starts_with((string) $name, 'XOBJ_DTYPE_') && $value === $type) {
                return $name;
            }
        }

        return "data type {$type}";
    }
}
