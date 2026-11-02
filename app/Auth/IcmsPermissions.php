<?php

declare(strict_types=1);

namespace App\Auth;

use App\Legacy\LegacyRuntime;

/**
 * ImpressCMS groups and group permissions as Laravel abilities (design L11),
 * through Gate::before():
 *
 *     ->middleware('can:webmaster')                         // ICMS_GROUP_ADMIN
 *     ->middleware('can:icms_group:2')                      // membership of group 2
 *     ->middleware('can:icms_perm:module_admin')            // right on the current module
 *     Gate::allows('icms_perm:news_submit', 7)              // item right in the current module
 *
 * Without a subject a right is checked for the current module itself (as
 * module_read/module_admin are, in the system module); with an integer
 * subject for that item of the current module. Anonymous visitors are checked
 * with the anonymous group. icms_member_groupperm lets webmasters pass.
 * Other abilities are left to Laravel (null).
 */
final class IcmsPermissions
{
    public const WEBMASTER = 'webmaster';
    public const GROUP_PREFIX = 'icms_group:';
    public const PERMISSION_PREFIX = 'icms_perm:';

    /** the system module, which holds the module-level rights */
    private const SYSTEM_MODULE_ID = 1;

    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    /**
     * @param array<array-key, mixed> $arguments
     */
    public function check(?IcmsUser $user, string $ability, array $arguments = []): ?bool
    {
        if ($ability === self::WEBMASTER) {
            return $user?->isWebmaster() ?? false;
        }

        if (str_starts_with($ability, self::GROUP_PREFIX)) {
            $group = (int) substr($ability, strlen(self::GROUP_PREFIX));

            return $user?->inGroup($group) ?? $group === (int) ICMS_GROUP_ANONYMOUS;
        }

        if (str_starts_with($ability, self::PERMISSION_PREFIX)) {
            return $this->hasPermission($user, substr($ability, strlen(self::PERMISSION_PREFIX)), $arguments[0] ?? null);
        }

        return null;
    }

    private function hasPermission(?IcmsUser $user, string $permission, mixed $subject): bool
    {
        if ($subject !== null && ! is_numeric($subject)) {
            return false;
        }

        $this->runtime->bootTo('module');

        $groups = $user?->getGroups() ?? [(int) ICMS_GROUP_ANONYMOUS];
        $module = \icms::$module;
        $moduleId = is_object($module) ? (int) $module->getVar('mid') : self::SYSTEM_MODULE_ID;

        [$itemId, $permissionModule] = $subject === null
            ? [$moduleId, self::SYSTEM_MODULE_ID]
            : [(int) $subject, $moduleId];

        return (bool) \icms::handler('icms_member_groupperm')->checkRight($permission, $itemId, $groups, $permissionModule);
    }
}
