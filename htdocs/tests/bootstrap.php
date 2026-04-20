<?php
/**
 * PHPUnit bootstrap for ImpressCMS library tests.
 *
 * This file intentionally does NOT boot the full CMS (no DB, no session, no
 * preloaders). It only defines the minimum constants and autoloading plumbing
 * required so that classes under htdocs/libraries/icms/ can be loaded by tests
 * in isolation.
 *
 * @package icms\tests
 */

declare(strict_types=1);

// -- Paths ----------------------------------------------------------------
$icmsTestsHtdocs = dirname(__DIR__);
$icmsTestsRepoRoot = dirname($icmsTestsHtdocs);

// -- Core ImpressCMS constants needed by bare class files -----------------
// Several legacy files contain `defined("ICMS_ROOT_PATH") or die(...)` guards
// at the very top. We satisfy those without booting the CMS.
if (!defined('ICMS_ROOT_PATH')) {
    define('ICMS_ROOT_PATH', $icmsTestsHtdocs);
}
if (!defined('ICMS_TRUST_PATH')) {
    define('ICMS_TRUST_PATH', $icmsTestsRepoRoot . '/trustpath');
}
if (!defined('ICMS_LIBRARIES_PATH')) {
    define('ICMS_LIBRARIES_PATH', ICMS_ROOT_PATH . '/libraries');
}
if (!defined('ICMS_INCLUDE_PATH')) {
    define('ICMS_INCLUDE_PATH', ICMS_ROOT_PATH . '/include');
}
if (!defined('ICMS_CACHE_PATH')) {
    define('ICMS_CACHE_PATH', $icmsTestsRepoRoot . '/var/cache');
}
if (!defined('ICMS_URL')) {
    define('ICMS_URL', 'http://localhost');
}
if (!defined('ICMS_THEME_PATH')) {
    define('ICMS_THEME_PATH', ICMS_ROOT_PATH . '/themes');
}
if (!defined('ICMS_THEME_URL')) {
    define('ICMS_THEME_URL', ICMS_URL . '/themes');
}
if (!defined('XOOPS_MAINFILE_INCLUDED')) {
    define('XOOPS_MAINFILE_INCLUDED', true);
}

// -- Composer autoloader --------------------------------------------------
$icmsTestsAutoloader = $icmsTestsHtdocs . '/vendor/autoload.php';
if (!is_file($icmsTestsAutoloader)) {
    fwrite(
        STDERR,
        "Composer autoloader not found at {$icmsTestsAutoloader}. Run `composer install` in htdocs/.\n"
    );
    exit(1);
}
require_once $icmsTestsAutoloader;

// -- Minimal procedural helpers -------------------------------------------
// Some legacy class files call icms_loadLanguageFile() at the top level of
// the file. In the test environment we do not ship localized strings, so
// provide a no-op shim if the real helper hasn't been pulled in.
if (!function_exists('icms_loadLanguageFile')) {
    function icms_loadLanguageFile(string $module, string $file, bool $admin = false): void
    {
        // No-op stub for tests that load classes in isolation.
    }
}

// -- Custom icms autoloader -----------------------------------------------
// Mirror the routing implemented in htdocs/include/common.php so tests can
// load legacy class names (icms_*) transparently through the PSR-4 namespace.
$icmsRootLib = ICMS_LIBRARIES_PATH;
$icmsRenameMap = [
    'icms_core_Object'          => 'Icms\\Core\\Entity',
    'icms_ipf_Object'           => 'Icms\\Ipf\\Entity',
    'icms_ipf_category_Object'  => 'Icms\\Ipf\\Category\\Entity',
    'icms_ipf_seo_Object'       => 'Icms\\Ipf\\Seo\\Entity',
];
spl_autoload_register(
    static function (string $class) use ($icmsRootLib, $icmsRenameMap): void {
        if ($class === 'icms') {
            $file = $icmsRootLib . DIRECTORY_SEPARATOR . 'icms.php';
            if (is_file($file)) {
                require_once $file;
            }
            return;
        }
        if (isset($icmsRenameMap[$class])) {
            $target = $icmsRenameMap[$class];
            if (class_exists($target, true) || interface_exists($target, true) || trait_exists($target, true)) {
                if (!class_exists($class, false) && !interface_exists($class, false) && !trait_exists($class, false)) {
                    class_alias($target, $class);
                }
            }
            return;
        }
        if (strncmp($class, 'icms_', 5) === 0) {
            $parts = array_map('ucfirst', explode('_', substr($class, 5)));
            $psr4 = 'Icms\\' . implode('\\', $parts);
            if ($psr4 !== $class && (class_exists($psr4, true) || interface_exists($psr4, true) || trait_exists($psr4, true))) {
                if (!class_exists($class, false)
                    && !interface_exists($class, false)
                    && !trait_exists($class, false)
                ) {
                    class_alias($psr4, $class);
                }
                return;
            }
            // Guard against redeclare: the PSR-4 autoload above may have
            // loaded a file that happened to declare this legacy class
            // directly (classes not yet moved into the Icms\ namespace).
            if (class_exists($class, false) || interface_exists($class, false) || trait_exists($class, false)) {
                return;
            }
            $legacy = $icmsRootLib . DIRECTORY_SEPARATOR
                . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            if (is_file($legacy)) {
                require_once $legacy;
                return;
            }
        }
        if (strncmp($class, 'Icms\\', 5) === 0) {
            $file = $icmsRootLib . DIRECTORY_SEPARATOR . 'icms'
                . DIRECTORY_SEPARATOR
                . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 5))
                . '.php';
            if (is_file($file)) {
                require_once $file;
                // If the target file has not been refactored yet and only
                // declares the legacy flat class name, alias the modern
                // namespaced name to it so the autoload chain terminates
                // and composer's PSR-4 loader does not re-include the same
                // file via include() and trigger a redeclare.
                if (!class_exists($class, false)
                    && !interface_exists($class, false)
                    && !trait_exists($class, false)
                ) {
                    $legacyName = 'icms_' . strtolower(str_replace('\\', '_', substr($class, 5)));
                    if (class_exists($legacyName, false)
                        || interface_exists($legacyName, false)
                        || trait_exists($legacyName, false)
                    ) {
                        class_alias($legacyName, $class);
                    }
                }
            }
        }
    },
    true,
    true,
);

unset($icmsTestsHtdocs, $icmsTestsRepoRoot, $icmsTestsAutoloader, $icmsRootLib, $icmsRenameMap);
