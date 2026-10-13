<?php

declare(strict_types=1);

namespace App\Tests\Support;

/**
 * Base class for Integration and Functional tests that boot legacy
 * ImpressCMS in-process.
 *
 * Legacy defines constants (mainfile.php) and keeps static state in `icms::`
 * that cannot be reset, so every test must run in its own process. PHPUnit
 * attributes are not inherited: each concrete class must declare
 * #[RunTestsInSeparateProcesses]. This base class fails tests that do not.
 *
 * Tests run like the host: the root autoloader is the only one
 * (ICMS_HOST_AUTOLOADED) and the test site's mainfile is named by
 * ICMS_MAINFILE. $_SERVER gets the entries a web server would provide.
 */
abstract class IsolatedLegacyTestCase extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Isolation::inChildProcess()) {
            self::fail(static::class . ' boots legacy ImpressCMS and must declare #[RunTestsInSeparateProcesses].');
        }
        putenv('ICMS_MAINFILE=' . TestSite::mainfile());

        // tests/bootstrap.php loaded the root autoloader, exactly as the host does.
        if (!defined('ICMS_HOST_AUTOLOADED')) {
            define('ICMS_HOST_AUTOLOADED', true);
        }

        $_SERVER = array_replace($_SERVER, [
            'HTTP_HOST' => '127.0.0.1',
            'SERVER_NAME' => '127.0.0.1',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php',
            'QUERY_STRING' => '',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_METHOD' => 'GET',
            'HTTP_USER_AGENT' => 'ImpressCMS test suite',
        ]);
    }

    /**
     * Define the test site's constants (mainfile.php with `nocommon`).
     *
     * A full boot must not include common.php from inside a method, because
     * its top-level variables are expected to be globals; tests boot further
     * through Icms\Kernel\Bootstrap, which binds them explicitly.
     */
    protected static function defineLegacyConstants(): void
    {
        // mainfile.php reads $xoopsOption from the including scope.
        $xoopsOption = ['nocommon' => true];
        require_once TestSite::mainfile();
    }
}
