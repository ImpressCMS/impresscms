<?php

declare(strict_types=1);

namespace App\Tests\Http\Characterization;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\BrowserKit\Response;

/**
 * Pins the observable behaviour of ImpressCMS for a fixed URL corpus.
 *
 * The snapshots in __snapshots__/ were recorded with `htdocs/` as document
 * root before the host existed. They must match at routing level 2 (legacy
 * scripts executed directly) and level 3 (every request through the host),
 * and on every supported web server (ICMS_TEST_BASE_URL).
 *
 * Each case starts from a freshly restored database, because pages such as
 * the home page reflect earlier requests (online users, last login).
 *
 * Re-record with ICMS_UPDATE_SNAPSHOTS=1.
 */
final class CharacterizationTest extends HttpTestCase
{
    private const SNAPSHOT_DIR = __DIR__ . '/__snapshots__';

    protected function setUp(): void
    {
        TestSite::restoreSnapshot();
        parent::setUp();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function corpus(): array
    {
        $cases = [
            'asset-css',
            'asset-js',
            'asset-image',
            'home',
            'user-login-form',
            'login-post',
            'admin-anonymous',
            'not-found-path',
            'redirect-then-exit',
            'two-cookies',
            'ipf-admin-list-webmaster',
            'site-closed-anonymous',
            'site-closed-webmaster',
            'login-post-custom-session',
        ];

        return array_combine($cases, array_map(static fn (string $case): array => [$case], $cases));
    }

    #[DataProvider('corpus')]
    public function testResponseMatchesSnapshot(string $case): void
    {
        [$request, $response, $hashBody] = $this->perform($case);

        $normalizer = new SnapshotNormalizer(static::$server->baseUri());
        $actual = $normalizer->transcript($request, $response, $hashBody);

        $file = self::SNAPSHOT_DIR . '/' . $case . '.txt';
        if (getenv('ICMS_UPDATE_SNAPSHOTS') === '1') {
            @mkdir(self::SNAPSHOT_DIR, 0777, true);
            file_put_contents($file, $actual);
        }

        self::assertFileExists($file, 'No snapshot recorded for ' . $case . '; run with ICMS_UPDATE_SNAPSHOTS=1.');
        self::assertSame(
            str_replace("\r\n", "\n", (string) file_get_contents($file)),
            $actual,
            "Response for {$case} differs from its snapshot." . self::serverLog(),
        );
    }

    /**
     * @return array{string, Response, bool} request line, response, whether to hash the body
     */
    private function perform(string $case): array
    {
        return match ($case) {
            'asset-css' => ['GET /themes/iTheme/style.css', $this->request('GET', '/themes/iTheme/style.css'), true],
            'asset-js' => ['GET /include/xoops.js', $this->request('GET', '/include/xoops.js'), true],
            'asset-image' => ['GET /images/arrow.gif', $this->request('GET', '/images/arrow.gif'), true],
            'home' => ['GET /', $this->request('GET', '/'), false],
            'user-login-form' => ['GET /user.php', $this->request('GET', '/user.php'), false],
            'login-post' => ['POST /user.php (webmaster)', $this->loginAsWebmaster(), false],
            'admin-anonymous' => ['GET /admin.php', $this->request('GET', '/admin.php'), false],
            'not-found-path' => ['GET /does-not-exist', $this->request('GET', '/does-not-exist'), false],
            'redirect-then-exit' => [
                'GET /modules/system/admin.php?fct=userrank (anonymous)',
                $this->request('GET', '/modules/system/admin.php?fct=userrank'),
                false,
            ],
            'two-cookies' => ['POST /user.php (webmaster, remember me)', $this->loginWithRememberMe(), false],
            'ipf-admin-list-webmaster' => [
                'GET /modules/system/admin.php?fct=userrank (webmaster)',
                $this->loggedInAsWebmaster()->request('GET', '/modules/system/admin.php?fct=userrank'),
                false,
            ],
            'site-closed-anonymous' => ['GET / (site closed, anonymous)', $this->closeSite()->request('GET', '/'), false],
            // logged in before closing: the closed site only accepts its own login form
            'site-closed-webmaster' => [
                'GET / (site closed, webmaster)',
                $this->loggedInAsWebmaster()->closeSite()->request('GET', '/'),
                false,
            ],
            'login-post-custom-session' => [
                'POST /user.php (webmaster, session name MYSITE)',
                $this->withCustomSessionName()->loginAsWebmaster(),
                false,
            ],
        };
    }

    private function loginWithRememberMe(): Response
    {
        $this->request('GET', '/user.php');

        return $this->request('POST', '/user.php', [
            'op' => 'login',
            'uname' => TestSite::WEBMASTER_UNAME,
            'pass' => TestSite::WEBMASTER_PASS,
            'rememberme' => 'On',
        ], ['HTTP_REFERER' => static::url('/user.php')]);
    }

    private function closeSite(): self
    {
        TestSite::setConfig('closesite', '1');

        return $this;
    }

    private function withCustomSessionName(): self
    {
        TestSite::setConfig('use_mysession', '1');
        TestSite::setConfig('session_name', 'MYSITE');

        return $this;
    }

    private function loggedInAsWebmaster(): self
    {
        $this->loginAsWebmaster();

        return $this;
    }
}
