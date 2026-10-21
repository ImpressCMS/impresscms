<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Maps the variables of an IPF entity to the fields of IpfEntityType and back
 * (design D7).
 *
 * Fields are read with getVar($key, 'n'): the raw value, so that lengths are
 * validated on what is stored ('e' escapes HTML for the legacy form elements).
 * Only submitted fields are written, with setVar(), as
 * Icms\Ipf\Controller::postDataToObject() does for the keys present in $_POST:
 * a field the browser did not send (an empty multi-select) keeps its value.
 * XOBJ_DTYPE_SIMPLE_ARRAY variables are lists here, set as lists (which
 * Icms\Core\Entity::cleanVars() joins with '|') and read from the raw value.
 */
final class IpfEntityDataMapper implements DataMapperInterface
{
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        if (!is_object($viewData)) {
            return;
        }
        foreach ($forms as $form) {
            $key = $form->getName();
            if ($form->getConfig()->getMapped() && isset($viewData->vars[$key])) {
                $form->setData(self::read($viewData, $key));
            }
        }
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        if (!is_object($viewData)) {
            return;
        }
        /** @var FormInterface $form */
        foreach ($forms as $form) {
            $key = $form->getName();
            if (!$form->getConfig()->getMapped() || !$form->isSubmitted() || $form->isDisabled() || !isset($viewData->vars[$key])) {
                continue;
            }
            self::write($viewData, $key, $form->getData());
        }
    }

    public static function read(object $entity, string $key): mixed
    {
        $type = (int) $entity->vars[$key]['data_type'];
        // getVar() explodes a stored string, and fails on the list setVar() needs
        $value = $type === XOBJ_DTYPE_SIMPLE_ARRAY ? $entity->vars[$key]['value'] : $entity->getVar($key, 'n');

        return match ($type) {
            XOBJ_DTYPE_SIMPLE_ARRAY => self::toList($value),
            XOBJ_DTYPE_INT => $value === null || $value === '' ? null : (int) $value,
            default => is_scalar($value) ? (string) $value : $value,
        };
    }

    public static function write(object $entity, string $key, mixed $value): void
    {
        $value = match ((int) $entity->vars[$key]['data_type']) {
            XOBJ_DTYPE_SIMPLE_ARRAY => self::toList($value),
            XOBJ_DTYPE_INT => $value === null || $value === '' ? 0 : (int) $value,
            default => $value ?? '',
        };
        $entity->setVar($key, $value);
    }

    /**
     * @return list<string>
     */
    private static function toList(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_map('strval', $value));
        }
        if ($value === null || $value === '') {
            return [];
        }

        return explode('|', (string) $value);
    }
}
