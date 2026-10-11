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

// -- Legacy class name bridge ---------------------------------------------
// libraries/Autoloader.php (composer "files") maps icms_* names through
// libraries/Icms/aliases.php; make sure it is registered even when the
// composer autoloader was generated without it.
require_once ICMS_LIBRARIES_PATH . '/Autoloader.php';

unset($icmsTestsHtdocs, $icmsTestsRepoRoot, $icmsTestsAutoloader);
