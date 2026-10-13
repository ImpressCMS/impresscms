<?php

declare(strict_types=1);

namespace App\Tests\Support;

use PHPUnit\Framework\TestCase;

/**
 * Base class for tests that use the scripted test site database.
 *
 * Restores the snapshot taken by `bin/test-db` once per test class. With
 * process isolation, PHPUnit also runs setUpBeforeClass() in every child
 * process; the restore only happens in the parent, so tests of one class
 * share the class's starting state as documented in design D13.
 */
abstract class DatabaseTestCase extends TestCase
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
}
