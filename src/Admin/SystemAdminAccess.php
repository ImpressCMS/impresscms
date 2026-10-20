<?php

declare(strict_types=1);

namespace App\Admin;

use App\Legacy\LegacyRuntime;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * The access rules of the system module's admin functions
 * (modules/system/admin.php, design D5), for a set of groups:
 *
 * 1. the groups administer the system module (`module_admin`), which is what
 *    the module launch checks in the admin, and
 * 2. they are webmasters, or hold `system_admin` for the function's category,
 *    read from admin/<fct>/icms_version.php or xoops_version.php.
 *
 * Needs the legacy services (the `siteState` stage), not the module launch,
 * so the context can check before ImpressCMS launches the module itself.
 */
final class SystemAdminAccess
{
    /** @var array<string, int> fct => category */
    private array $categories = [];

    public function __construct(
        private readonly LegacyRuntime $runtime,
        #[Autowire('%icms.legacy.root%')]
        private readonly string $legacyRoot,
    ) {
    }

    /**
     * The groups of the current ImpressCMS user; null for anonymous visitors.
     *
     * @return list<int>|null
     */
    public function currentGroups(): ?array
    {
        $this->runtime->bootTo('siteState');
        $user = $this->runtime->service('user');

        return is_object($user) ? array_values(array_map('intval', (array) $user->getGroups())) : null;
    }

    /**
     * Rule 1: administrators of the system module.
     *
     * @param list<int>|null $groups null for anonymous visitors
     */
    public function administersSystemModule(?array $groups): bool
    {
        if ($groups === null) {
            return false;
        }
        $this->runtime->bootTo('siteState');

        return (bool) \icms::handler('icms_member_groupperm')->checkRight('module_admin', $this->systemModuleId(), $groups);
    }

    /**
     * Rule 2: webmasters, or holders of `system_admin` for the function's
     * category. Functions without a category (other than `version`) are
     * denied, as in admin.php.
     *
     * @param list<int> $groups
     */
    public function mayUseFunction(string $fct, array $groups): bool
    {
        $category = $this->category($fct);
        if ($category === 0) {
            return $fct === 'version';
        }

        return in_array((int) ICMS_GROUP_ADMIN, $groups, true)
            || false !== \icms::handler('icms_member_groupperm')->checkRight('system_admin', $category, $groups, $this->systemModuleId());
    }

    /**
     * Both rules.
     *
     * @param list<int>|null $groups
     */
    public function isGranted(string $fct, ?array $groups): bool
    {
        return $groups !== null && $this->administersSystemModule($groups) && $this->mayUseFunction($fct, $groups);
    }

    public function functionExists(string $fct): bool
    {
        return preg_match('/^[a-z0-9_]+$/i', $fct) === 1 && is_dir($this->functionDir($fct));
    }

    /**
     * The `category` of the function's version file, 0 if it has none.
     */
    public function category(string $fct): int
    {
        if (isset($this->categories[$fct])) {
            return $this->categories[$fct];
        }
        if (!$this->functionExists($fct)) {
            return $this->categories[$fct] = 0;
        }

        $dir = $this->functionDir($fct);
        $file = is_file($dir . '/icms_version.php') ? $dir . '/icms_version.php' : $dir . '/xoops_version.php';
        if (!is_file($file)) {
            return $this->categories[$fct] = 0;
        }

        // the version file uses the category constants and the function's language
        $this->runtime->bootTo('siteState');
        include_once $this->legacyRoot . '/modules/system/constants.php';
        icms_loadLanguageFile('system', 'admin');
        icms_loadLanguageFile('system', $fct, true);

        // in a scope of its own, as admin.php includes it and then unsets $modversion
        $category = (static function (string $file): int {
            include $file;
            $modversion = get_defined_vars()['modversion'] ?? [];

            return is_array($modversion) && !empty($modversion['category']) ? (int) $modversion['category'] : 0;
        })($file);

        return $this->categories[$fct] = $category;
    }

    public function systemModuleId(): int
    {
        $this->runtime->bootTo('siteState');
        $system = \icms::handler('icms_module')->getByDirname('system');

        return is_object($system) ? (int) $system->getVar('mid') : 1;
    }

    private function functionDir(string $fct): string
    {
        return $this->legacyRoot . '/modules/system/admin/' . $fct;
    }
}
