<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Tests\Support\TestSite;
use PHPUnit\Framework\TestCase;

final class SmokeTest extends TestCase
{
    public function testRepositoryLayoutIsDetected(): void
    {
        self::assertFileExists(TestSite::legacyRoot() . '/include/common.php');
        self::assertFileExists(TestSite::legacyRoot() . '/libraries/Icms/aliases.php');
    }
}
