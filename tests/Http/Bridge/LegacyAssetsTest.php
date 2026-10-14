<?php

declare(strict_types=1);

namespace App\Tests\Http\Bridge;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;

/**
 * Static files of the real ImpressCMS tree served through the host.
 */
#[Group('spec:legacy-request-bridge')]
final class LegacyAssetsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        // With rewrite rules the web server serves static files itself; only the
        // php -S router sends them to the host.
        if (ServerProcess::routing() !== 3 || ServerProcess::externalBaseUri() !== null) {
            self::markTestSkipped('Assets reach the host only through the php -S router (ICMS_TEST_ROUTING=3).');
        }
        parent::setUp();
    }

    /**
     * @scenario Stylesheet request
     */
    public function testStylesheetIsServedAsIs(): void
    {
        $response = $this->request('GET', '/themes/iTheme/style.css');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame('text/css; charset=UTF-8', $response->getHeader('Content-Type'));
        self::assertSame(file_get_contents(TestSite::legacyRoot() . '/themes/iTheme/style.css'), $response->getContent());
    }

    /**
     * @scenario Conditional request for an unchanged asset
     */
    public function testUnchangedAssetAnswers304(): void
    {
        $etag = $this->request('GET', '/themes/iTheme/style.css')->getHeader('ETag');
        self::assertNotEmpty($etag);

        $response = $this->request('GET', '/themes/iTheme/style.css', [], ['HTTP_IF_NONE_MATCH' => (string) $etag]);

        self::assertSame(304, $response->getStatusCode());
        self::assertSame('', $response->getContent());
    }

    public function testMissingAssetIs404(): void
    {
        self::assertSame(404, $this->request('GET', '/themes/iTheme/missing.css')->getStatusCode());
    }
}
