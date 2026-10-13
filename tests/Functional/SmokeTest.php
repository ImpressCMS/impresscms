<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\DatabaseTestCase;
use App\Tests\Support\TestSite;

final class SmokeTest extends DatabaseTestCase
{
    public function testSnapshotRestoreResetsTheDatabase(): void
    {
        $pdo = TestSite::connection();
        $pdo->exec('DELETE FROM ' . TestSite::table('users') . " WHERE uname = 'member'");

        TestSite::restoreSnapshot();

        $count = (int) $pdo->query('SELECT COUNT(*) FROM ' . TestSite::table('users'))->fetchColumn();
        self::assertSame(2, $count);
    }
}
