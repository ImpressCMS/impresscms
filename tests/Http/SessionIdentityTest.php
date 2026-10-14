<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\BrowserKit\Response;

/**
 * One session and one identity across legacy pages and Symfony routes
 * (designs D10, D11), through the Symfony host.
 */
#[Group('spec:session-identity')]
final class SessionIdentityTest extends HttpTestCase
{
    protected function setUp(): void
    {
        TestSite::restoreSnapshot();
        parent::setUp();
    }

    /**
     * @return array<string, mixed>
     */
    private function identity(): array
    {
        $response = $this->request('GET', '/_test/identity');
        self::assertSame(200, $response->getStatusCode(), $response->getContent() . self::serverLog());

        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return list<string> names of the cookies a response sets
     */
    private static function cookieNames(Response $response): array
    {
        return array_map(
            static fn (string $header): string => (string) strstr($header, '=', true),
            (array) $response->getHeader('Set-Cookie', false),
        );
    }

    /**
     * @scenario Value written by legacy, read by new application
     */
    public function testSessionValueWrittenByLegacyIsReadBySymfony(): void
    {
        $this->loginAsWebmaster();

        self::assertSame(1, $this->identity()['session_user_id'], 'xoopsUserId, written by the legacy login');
    }

    /**
     * @scenario Custom session name
     */
    public function testCustomSessionNameIsTheOnlySessionCookie(): void
    {
        TestSite::setConfig('use_mysession', '1');
        TestSite::setConfig('session_name', 'MYSITE');

        $login = $this->loginAsWebmaster();
        self::assertContains('MYSITE', self::cookieNames($login));

        $response = $this->request('GET', '/_test/identity');
        $identity = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('MYSITE', $identity['session_name']);
        self::assertSame(1, $identity['session_user_id']);
        foreach (self::cookieNames($response) as $name) {
            self::assertSame('MYSITE', $name, 'no second session cookie');
        }

        $jar = array_map(static fn ($cookie): string => $cookie->getName(), $this->browser->getCookieJar()->all());
        self::assertNotContains('ICMSSESSION', $jar);
        self::assertNotContains('PHPSESSID', $jar);
    }

    /**
     * @scenario Logged-in user
     */
    public function testSymfonySeesTheLegacyUser(): void
    {
        $this->loginAsMember();

        $identity = $this->identity();
        self::assertTrue($identity['authenticated']);
        self::assertSame(2, $identity['id']);
        self::assertSame(TestSite::MEMBER_UNAME, $identity['uname']);
        self::assertSame($identity['legacy_user_id'], $identity['id']);
        self::assertEqualsCanonicalizing($identity['legacy_groups'], $identity['groups']);
        self::assertSame([(int) 2], $identity['groups']);
    }

    /**
     * @scenario Anonymous visitor
     */
    public function testVisitorWithoutSessionIsAnonymous(): void
    {
        $identity = $this->identity();

        self::assertFalse($identity['authenticated']);
        self::assertSame([3], $identity['groups'], 'the anonymous group only');
    }

    /**
     * @scenario Logout
     */
    public function testLegacyLogoutAppliesToSymfonyRoutes(): void
    {
        $this->loginAsWebmaster();
        self::assertTrue($this->identity()['authenticated']);

        $this->request('GET', '/user.php?op=logout');

        self::assertFalse($this->identity()['authenticated']);
    }

    /**
     * @scenario Admin route as anonymous visitor
     */
    public function testWebmasterRouteRedirectsAnonymousVisitorsToTheLogin(): void
    {
        $response = $this->request('GET', '/whoami');

        self::assertSame(302, $response->getStatusCode(), self::serverLog());
        self::assertStringEndsWith('/user.php?xoops_redirect=%2Fwhoami', (string) $response->getHeader('Location'));
    }

    /**
     * @scenario Admin route as non-admin
     */
    public function testWebmasterRouteIsForbiddenForMembers(): void
    {
        $this->loginAsMember();

        self::assertSame(403, $this->request('GET', '/whoami')->getStatusCode());
    }

    /**
     * @scenario Admin route as webmaster
     */
    public function testWebmasterRouteRunsForWebmasters(): void
    {
        $this->loginAsWebmaster();
        $response = $this->request('GET', '/whoami');

        self::assertSame(200, $response->getStatusCode(), $response->getContent() . self::serverLog());
        $whoami = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(1, $whoami['id']);
        self::assertSame(TestSite::WEBMASTER_UNAME, $whoami['uname']);
        self::assertContains('ROLE_WEBMASTER', $whoami['roles']);
        self::assertContains('ROLE_GROUP_1', $whoami['roles']);
    }

    public function testModulePermissionsComeFromTheGroupPermissions(): void
    {
        self::assertSame(200, $this->request('GET', '/_test/module-read')->getStatusCode(), 'anonymous may read the system module');
        self::assertSame(302, $this->request('GET', '/_test/module-admin')->getStatusCode());

        $this->loginAsMember();
        self::assertSame(200, $this->request('GET', '/_test/members-only')->getStatusCode());
        self::assertSame(403, $this->request('GET', '/_test/module-admin')->getStatusCode());
    }

    public function testModuleAdminPermissionForWebmasters(): void
    {
        $this->loginAsWebmaster();

        self::assertSame(200, $this->request('GET', '/_test/module-admin')->getStatusCode());
    }

    /**
     * @scenario Legacy token accepted by new route
     */
    public function testLegacyTokenIsAcceptedOnceBySymfony(): void
    {
        $this->loginAsWebmaster();
        $form = $this->request('GET', '/modules/system/admin.php?fct=smilies');
        self::assertSame(200, $form->getStatusCode(), self::serverLog());
        self::assertSame(1, preg_match('/name=["\']XOOPS_TOKEN_REQUEST["\'][^>]*value=["\']([0-9a-f]{32})["\']/', $form->getContent(), $match), 'the legacy form carries a token');

        $first = $this->request('POST', '/_test/token', ['XOOPS_TOKEN_REQUEST' => $match[1]]);
        self::assertSame(200, $first->getStatusCode(), $first->getContent() . self::serverLog());
        self::assertSame('token accepted', $first->getContent());

        $replay = $this->request('POST', '/_test/token', ['XOOPS_TOKEN_REQUEST' => $match[1]]);
        self::assertSame(403, $replay->getStatusCode());
    }

    public function testMissingTokenIsRejected(): void
    {
        $this->loginAsWebmaster();

        self::assertSame(403, $this->request('POST', '/_test/token')->getStatusCode());
    }
}
