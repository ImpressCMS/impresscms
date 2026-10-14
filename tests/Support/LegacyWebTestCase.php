<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Base class for Functional tests of Symfony routes that boot legacy
 * ImpressCMS through App\Legacy\LegacyRuntime.
 *
 * Like IsolatedLegacyTestCase: one process per test (each concrete class
 * declares #[RunTestsInSeparateProcesses]), the test site database restored
 * per class, the root autoloader as the only one, the test site's mainfile
 * named by ICMS_MAINFILE.
 */
abstract class LegacyWebTestCase extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!TestSite::isInstalled()) {
            self::fail('The test site is not installed. Run `php bin/test-db` first.');
        }
        if (!Isolation::inChildProcess()) {
            TestSite::restoreSnapshot();
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!Isolation::inChildProcess()) {
            self::fail(static::class . ' boots legacy ImpressCMS and must declare #[RunTestsInSeparateProcesses].');
        }
        putenv('ICMS_MAINFILE=' . TestSite::mainfile());
        if (!defined('ICMS_HOST_AUTOLOADED')) {
            define('ICMS_HOST_AUTOLOADED', true);
        }
    }
}
