<?php

declare(strict_types=1);

namespace App\Tests\Http\Admin;

use App\Testing\HostMarker;
use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;

/**
 * Admin pages that Laravel serves produce their headers through the legacy admin
 * header and footer, and send each one once (design L29). The page is the
 * mimetype administration, which the takeover serves.
 */
#[Group('spec:admin-page-integration')]
final class AdminPageRenderingTest extends HttpTestCase
{
    protected function setUp(): void
    {
        if (ServerProcess::routing() !== 3) {
            self::markTestSkipped('The mimetype takeover needs routing level 3 (ICMS_TEST_ROUTING=3).');
        }
        parent::setUp();
    }

    /**
     * @scenario Headers set by the admin header
     */
    public function testAdminHeadersAreSentOnce(): void
    {
        $this->loginAsWebmaster();
        $response = $this->request('GET', '/modules/system/admin.php?fct=mimetype');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame('system.mimetype', $response->getHeader(HostMarker::ROUTE_HEADER));

        $headers = array_change_key_case($response->getHeaders(), CASE_LOWER);
        // what icms_cp_header() and icms_cp_footer() leave, by header() replace semantics
        self::assertSame(['SAMEORIGIN'], $headers['x-frame-options'] ?? null);
        self::assertSame(['private, no-cache'], $headers['cache-control'] ?? null);
        self::assertSame(['no-cache'], $headers['pragma'] ?? null);
        self::assertCount(1, $headers['content-type'] ?? []);
        self::assertCount(1, $headers['expires'] ?? []);
        self::assertStringContainsString(TestSite::SITE_NAME, $response->getContent());
    }
}
