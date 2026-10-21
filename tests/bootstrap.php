<?php

declare(strict_types=1);

/*
 * Root test bootstrap. Loads the single root autoloader only: the Library
 * suite gets its ImpressCMS constants from LibraryEnvironmentExtension, and
 * the Integration/Functional suites boot legacy for real in child processes,
 * so nothing ImpressCMS-specific may be defined here.
 */

$autoloader = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_file($autoloader)) {
    fwrite(STDERR, "Composer autoloader not found at {$autoloader}. Run `composer install` in the repository root.\n");
    exit(1);
}
require_once $autoloader;
unset($autoloader);

// The Icms library comes from the web root, as in host.php: the trust path's
// composer.json cannot name a web root, which differs per site.
require_once dirname(__DIR__) . '/htdocs/libraries/Autoloader.php';

// The Http suite runs the host with APP_DEBUG=0, whose container is never
// rebuilt when code or configuration change: start every run from a clean
// test cache (only once, in the main PHPUnit process).
if (!App\Tests\Support\Isolation::inChildProcess()) {
    (new Symfony\Component\Filesystem\Filesystem())->remove(dirname(__DIR__) . '/var/cache/test');
}
