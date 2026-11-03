<?php

declare(strict_types=1);

namespace App\Ipf\Form;

/**
 * One field of an IpfForm: an entity variable or a handler permission
 * (design L31). Laravel has no form component, so the field set the legacy
 * IPF form shows is described here, with the validator rules and messages
 * that ControlMap derives from the variable definition.
 */
final class IpfField
{
    /**
     * @param list<mixed> $rules validator rules for the submitted value
     * @param array<string, string> $messages rule => message, naming the caption
     * @param array<int|string, string>|null $options value => label of a select
     * @param list<mixed> $itemRules rules for each selected value of a multiple select
     */
    public function __construct(
        public readonly string $name,
        public readonly string $caption,
        public readonly ?string $description = null,
        public readonly bool $hidden = false,
        public readonly bool $mapped = true,
        public readonly bool $readonly = false,
        public readonly bool $permission = false,
        public readonly bool $multiple = false,
        public readonly ?array $options = null,
        public readonly array $rules = [],
        public readonly array $itemRules = [],
        public readonly array $messages = [],
        public readonly mixed $value = null,
    ) {
    }

    public static function hidden(string $name, bool $mapped, mixed $value): self
    {
        return new self(name: $name, caption: $name, hidden: true, mapped: $mapped, value: $value);
    }
}
