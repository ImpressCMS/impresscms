<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;

/**
 * Legacy login with a custom session name and the database session handler.
 * The bridge must leave session start-up to ImpressCMS (both document roots).
 */
#[Group('spec:legacy-request-bridge')]
final class LegacySessionTest extends HttpTestCase
{
    protected function setUp(): void
    {
        TestSite::restoreSnapshot();
        TestSite::setConfig('use_mysession', '1');
        TestSite::setConfig('session_name', 'MYSITE');
        parent::setUp();
    }

    /**
     * @scenario POST to a legacy login
     */
    public function testLoginUsesTheConfiguredSessionAndTheDatabaseHandler(): void
    {
        $login = $this->loginAsWebmaster();

        $cookies = array_map(
            static fn (string $header): string => strstr($header, '=', true) ?: '',
            (array) $login->getHeader('Set-Cookie', false),
        );
        self::assertContains('MYSITE', $cookies, self::serverLog());
        self::assertNotContains('ICMSSESSION', $cookies);
        self::assertNotContains('PHPSESSID', $cookies);

        $sessionId = (string) $this->browser->getCookieJar()->get('MYSITE')?->getValue();
        self::assertNotSame('', $sessionId);
        $stored = TestSite::connection()->prepare('SELECT sess_data FROM ' . TestSite::table('session') . ' WHERE sess_id = ?');
        $stored->execute([$sessionId]);
        self::assertStringContainsString('xoopsUserId', (string) $stored->fetchColumn(), 'stored by the database session handler');

        $admin = $this->request('GET', '/admin.php');
        self::assertSame(200, $admin->getStatusCode(), self::serverLog());
        self::assertStringContainsString('Administration of ' . TestSite::SITE_NAME, $admin->getContent());
    }
}
