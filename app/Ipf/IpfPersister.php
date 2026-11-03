<?php

declare(strict_types=1);

namespace App\Ipf;

use App\Ipf\Form\IpfForm;
use LogicException;

/**
 * Stores and deletes IPF entities bound by an IpfForm, as Icms\Ipf\Controller
 * does, without its redirects (design L32):
 *
 * - save(): the handler's insert(), which runs its events (beforeSave,
 *   beforeInsert/beforeUpdate, afterSave, …), then each permission of the
 *   handler with the groups the form bound, replacing the previous ones
 * - delete(): the handler's delete(), which also removes the permissions
 *
 * The upload branch of storeFromDefaultForm() has no counterpart: forms with
 * upload controls are not built (ControlMap).
 */
final class IpfPersister
{
    /**
     * @return list<string> errors; empty when the entity and its permissions were stored
     */
    public function save(IpfForm $form): array
    {
        if (! $form->isSubmitted()) {
            throw new LogicException('Only a submitted IPF form can be saved.');
        }

        if ($form->fails()) {
            return $form->errors();
        }

        $entity = $form->entity();
        $handler = $entity->handler;
        if (! $handler->insert($entity)) {
            return array_values(array_map('strval', $entity->getErrors())) ?: ['The handler did not store the item.'];
        }

        $permissions = $handler->getPermissions();
        if (! $permissions) {
            return [];
        }

        $store = new \Icms\Ipf\Permission\Handler($handler);
        foreach ($permissions as $permission) {
            $name = (string) $permission['perm_name'];
            // as in legacy, a selection the browser did not send (none) removes every group
            $store->saveItem_Permissions($form->permissionGroups($name), $entity->id(), $name);
        }

        return [];
    }

    /**
     * @return list<string> errors; empty when the entity was deleted
     */
    public function delete(object $entity): array
    {
        if (! $entity->handler->delete($entity)) {
            return array_values(array_map('strval', $entity->getErrors())) ?: ['The handler did not delete the item.'];
        }

        return [];
    }
}
