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

// -- Custom icms autoloader -----------------------------------------------
// Mirror the routing implemented in htdocs/include/common.php so tests can
// load legacy class names (icms_*) transparently through the PSR-4 namespace.
$icmsRootLib = ICMS_LIBRARIES_PATH;
$icmsRenameMap = [
    'icms_core_Object' => 'Icms\\Core\\Entity',
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
            }
        }
    },
    true,
    true,
);

unset($icmsTestsHtdocs, $icmsTestsRepoRoot, $icmsTestsAutoloader, $icmsRootLib, $icmsRenameMap);
