<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Support\IsolatedLegacyTestCase;
use App\Tests\Support\TestSite;
use Icms\Db\Factory;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class SmokeTest extends IsolatedLegacyTestCase
{
    public function testLegacyDatabaseLayerReachesTheTestSite(): void
    {
        self::defineLegacyConstants();

        $db = Factory::instance();
        $result = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('users'));
        [$count] = $db->fetchRow($result);

        self::assertSame(TestSite::dbConfig()['name'], XOOPS_DB_NAME);
        self::assertSame(2, (int) $count, 'webmaster and member');
    }
}
