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
        $_SERVER['ICMS_MAINFILE'] = TestSite::mainfile();
    }

    /**
     * Define the test site's constants (mainfile.php with `nocommon`).
     *
     * A full boot must not include common.php from inside a method, because
     * its top-level variables are expected to be globals; tests boot further
     * through Icms\Kernel\Bootstrap, which binds them explicitly.
     *
     * @param array<string, mixed> $server extra $_SERVER entries
     */
    protected static function defineLegacyConstants(array $server = []): void
    {
        $_SERVER = array_replace([
            'HTTP_HOST' => '127.0.0.1',
            'SERVER_NAME' => '127.0.0.1',
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/index.php',
            'QUERY_STRING' => '',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_METHOD' => 'GET',
        ], $_SERVER, $server);

        // mainfile.php reads $xoopsOption from the including scope.
        $xoopsOption = ['nocommon' => true];
        require_once TestSite::mainfile();
    }
}
