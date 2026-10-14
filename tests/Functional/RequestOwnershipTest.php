<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\LegacyBridge\LegacyExecutionResponse;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Which application the kernel picks. Legacy-owned requests come back as the
 * marker for public/index.php; no legacy code runs in these tests.
 */
#[Group('spec:request-ownership')]
final class RequestOwnershipTest extends WebTestCase
{
    /**
     * @scenario Registered route matches
     */
    public function testRegisteredRouteIsHandledBySymfony(): void
    {
        $client = static::createClient();
        $client->request('GET', '/up');

        $response = $client->getResponse();
        self::assertSame(200, $response->getStatusCode());
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

        $client = static::createClient();
        $client->request('GET', '/modules/system/admin.php?fct=users');

        $response = $client->getResponse();
        self::assertNotInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('symfony: system users', $response->getContent());
    }

    /**
     * @scenario Other variants of the same script stay legacy
     * @scenario Legacy page request
     */
    public function testOtherVariantsOfTheScriptStayLegacy(): void
    {
        $client = static::createClient();
        $client->request('GET', '/modules/system/admin.php?fct=preferences');

        $response = $client->getResponse();
        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/modules/system/admin.php', $response->resolution->scriptName);
    }

    /**
     * @scenario Unmatched path under a reserved prefix
     */
    public function testReservedPrefixIsNeverLegacy(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin-next/unknown');

        $response = $client->getResponse();
        self::assertNotInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame(404, $response->getStatusCode());
    }

    /**
     * @scenario POST to a GET-only route
     */
    public function testWrongMethodOnASymfonyRouteIs405(): void
    {
        $client = static::createClient();
        $client->request('POST', '/up');

        $response = $client->getResponse();
        self::assertSame(405, $response->getStatusCode());
        self::assertStringContainsString('GET', (string) $response->headers->get('Allow'));
    }

    public function testUnknownPathFallsBackToTheLegacyFrontController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/does-not-exist');

        $response = $client->getResponse();
        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/index.php', $response->resolution->scriptName);
    }
}
