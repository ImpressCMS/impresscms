<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\LegacyBridge\LegacyExecutionResponse;
use App\Tests\Support\TestSite;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\Group;

/**
 * Which application the kernel picks. Legacy-owned requests come back as the
 * marker for public/index.php; no legacy code runs in these tests.
 */
#[Group('spec:request-ownership')]
final class RequestOwnershipTest extends TestCase
{
    /**
     * @scenario Registered route matches
     */
    public function testRegisteredRouteIsHandledByLaravel(): void
    {
        $response = $this->get('/up');

        $response->assertOk();
        self::assertSame('text/plain; charset=UTF-8', $response->headers->get('Content-Type'));
        self::assertSame('ok', $response->getContent());
    }

    /**
     * @scenario Registered route takes precedence over a legacy file
     * @scenario Query-specific takeover of a legacy admin page
     */
    public function testRouteClaimsOneVariantOfALegacyScript(): void
    {
        self::assertFileExists(TestSite::legacyRoot() . '/modules/system/admin.php');

        $response = $this->get('/modules/system/admin.php?fct=users');

        self::assertNotInstanceOf(LegacyExecutionResponse::class, $response->baseResponse);
        self::assertSame('host: system users', $response->getContent());
    }

    /**
     * @scenario Other variants of the same script stay legacy
     * @scenario Legacy page request
     */
    public function testOtherVariantsOfTheScriptStayLegacy(): void
    {
        $response = $this->get('/modules/system/admin.php?fct=preferences')->baseResponse;

        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/modules/system/admin.php', $response->resolution->scriptName);
    }

    /**
     * @scenario Unmatched path under a reserved prefix
     */
    public function testReservedPrefixIsNeverLegacy(): void
    {
        $response = $this->get('/admin-next/unknown');

        self::assertNotInstanceOf(LegacyExecutionResponse::class, $response->baseResponse);
        $response->assertNotFound();
    }

    /**
     * @scenario POST to a GET-only route
     */
    public function testWrongMethodOnALaravelRouteIs405(): void
    {
        $response = $this->post('/up');

        $response->assertMethodNotAllowed();
        self::assertStringContainsString('GET', (string) $response->headers->get('Allow'));
    }

    public function testUnknownPathFallsBackToTheLegacyFrontController(): void
    {
        $response = $this->get('/does-not-exist')->baseResponse;

        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/index.php', $response->resolution->scriptName);
    }

    public function testRootPathIsLegacy(): void
    {
        $response = $this->get('/')->baseResponse;

        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/index.php', $response->resolution->scriptName);
    }
}
