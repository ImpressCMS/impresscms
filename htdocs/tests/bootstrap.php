<?php
/**
 * PHPUnit bootstrap for ImpressCMS library tests.
 *
 * This file intentionally does NOT boot the full CMS (no DB, no session, no
 * preloaders). It only loads the Composer autoloader and the minimal
 * environment (tests/environment.php) required so that classes under
 * htdocs/libraries/ can be loaded by tests in isolation.
 *
 * @package icms\tests
 */

declare(strict_types=1);

// -- Composer autoloader --------------------------------------------------
$icmsTestsAutoloader = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_file($icmsTestsAutoloader)) {
    fwrite(
        STDERR,
        "Composer autoloader not found at {$icmsTestsAutoloader}. Run `composer install` in htdocs/.\n"
    );
    exit(1);
}
require_once $icmsTestsAutoloader;
unset($icmsTestsAutoloader);

require_once __DIR__ . '/environment.php';
