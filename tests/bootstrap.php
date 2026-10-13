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
