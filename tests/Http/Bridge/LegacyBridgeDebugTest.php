<?php

declare(strict_types=1);

namespace App\Tests\Http\Bridge;

use App\Tests\Support\LegacyFixtureTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('spec:legacy-request-bridge')]
final class LegacyBridgeDebugTest extends LegacyFixtureTestCase
{
    protected static function debug(): bool
    {
        return true;
    }

    /**
     * @scenario Fatal error in debug mode
     */
    public function testFailureShowsDetailsInDebugMode(): void
    {
        $id = bin2hex(random_bytes(6));
        $response = $this->request('GET', '/throws.php?id=' . $id);

        self::assertSame(500, $response->getStatusCode(), self::serverLog());
        $body = $response->getContent();
        self::assertStringContainsString('RuntimeException: legacy failure ' . $id, $body);
        self::assertMatchesRegularExpression('#Location: .*throws\.php:4#', $body);
        self::assertStringContainsString('Stack trace:', $body);
        self::assertStringContainsString('partial output before the failure', $body, 'captured output');
        self::assertStringContainsString('legacy failure ' . $id, self::applicationLog());
    }
}
