<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;

/**
 * Request ownership end to end through public/index.php (the Symfony host).
 */
#[Group('spec:request-ownership')]
final class RequestOwnershipTest extends HttpTestCase
{
    protected function setUp(): void
    {
        if (ServerProcess::configuredDocumentRoot() !== 'public') {
            self::markTestSkipped('Request ownership needs the Symfony host (ICMS_TEST_DOCROOT=public).');
        }
        parent::setUp();
    }

    /**
     * @scenario Request reaches the front controller
     */
    public function testEveryRequestGoesThroughTheFrontController(): void
    {
        $symfony = $this->request('GET', '/up');
        self::assertSame('ok', $symfony->getContent());

        $legacy = $this->request('GET', '/');
        self::assertSame(200, $legacy->getStatusCode(), self::serverLog());
        self::assertStringContainsString(TestSite::SITE_NAME, $legacy->getContent());
    }

    /**
     * @scenario Registered route takes precedence over a legacy file
     * @scenario Query-specific takeover of a legacy admin page
     */
    public function testClaimedVariantIsNotExecutedByLegacy(): void
    {
        // legacy would redirect an anonymous visitor to the login page
        $response = $this->request('GET', '/modules/system/admin.php?fct=users');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame('symfony: system users', $response->getContent());
    }

    /**
     * @scenario Other variants of the same script stay legacy
     * @scenario Legacy page request
     */
    public function testOtherVariantsAreExecutedByLegacy(): void
    {
        $response = $this->request('GET', '/modules/system/admin.php?fct=preferences');

        self::assertSame(302, $response->getStatusCode(), self::serverLog());
        self::assertStringEndsWith('/user.php', (string) $response->getHeader('Location'));
    }

    /**
     * @scenario POST to a GET-only route
     */
    public function testWrongMethodIsRejectedBeforeLegacy(): void
    {
        $response = $this->request('POST', '/up');

        self::assertSame(405, $response->getStatusCode());
        self::assertStringContainsString('GET', (string) $response->getHeader('Allow'));
    }
}
