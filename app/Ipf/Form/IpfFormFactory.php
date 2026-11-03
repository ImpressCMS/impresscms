<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use InvalidArgumentException;

/**
 * Forms for IPF entities (design L31): one field per variable, with the
 * visibility rules of Icms\Ipf\Form\Base::createElements(), plus one group
 * selection per permission of the entity's handler (createPermissionControls()).
 *
 * - the key field and variables not shown on the form are hidden values; the
 *   key is not written, the entity is loaded by it
 * - other variables get the field ControlMap chooses, labelled with
 *   `form_caption` and described with `form_dsc`
 * - each permission is a multiple selection of all groups, named after the
 *   permission, not written to the entity (App\Ipf\IpfPersister stores it)
 */
final class IpfFormFactory
{
    public function __construct(private readonly ValidationFactory $validator)
    {
    }

    public function for(object $entity): IpfForm
    {
        if (! isset($entity->vars, $entity->handler)) {
            throw new InvalidArgumentException('An IPF form needs an IPF entity.');
        }

        return new IpfForm($entity, [...self::fields($entity), ...self::permissionFields($entity)], $this->validator);
    }

    /**
     * The entity's fields, in the order of its variables.
     *
     * @return array<string, IpfField>
     */
    private static function fields(object $entity): array
    {
        $keyName = $entity->handler->keyName;

        $fields = [];
        foreach ($entity->vars as $key => $var) {
            $key = (string) $key;
            if ($key === $keyName || ! $var['displayOnForm']) {
                $fields[$key] = IpfField::hidden($key, $key !== $keyName, IpfEntityValues::read($entity, $key));

                continue;
            }

            // XOBJ_DTYPE_ARRAY without a control has no element in the legacy form either
            if ((int) $var['data_type'] === XOBJ_DTYPE_ARRAY && ! isset($entity->controls[$key])) {
                continue;
            }

            $fields[$key] = ControlMap::field($entity, $key, $var, $entity->controls[$key] ?? null);
        }

        return $fields;
    }

    /**
     * @return array<string, IpfField>
     */
    private static function permissionFields(object $entity): array
    {
        $permissions = $entity->handler->getPermissions();
        if (! $permissions) {
            return [];
        }

        $groups = \icms::handler('icms_member')->getGroupList();
        asort($groups);

        $fields = [];
        foreach ($permissions as $permission) {
            $fields[(string) $permission['perm_name']] = ControlMap::permissionField($entity, $permission, $groups);
        }

        return $fields;
    }
}
