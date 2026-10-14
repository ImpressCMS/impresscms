<?php

declare(strict_types=1);

namespace App\Tests\Http\Bridge;

use App\Tests\Support\LegacyFixtureTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('spec:legacy-request-bridge')]
final class LegacyBridgeTest extends LegacyFixtureTestCase
{
    /**
     * @scenario Script sees its own script name
     */
    public function testScriptSeesTheEnvironmentOfADirectRequest(): void
    {
        $response = $this->request('GET', '/modules/system/admin.php?fct=users', [], ['HTTP_X_ICMS_TEST_TAKEOVER' => 'off']);

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        $env = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('/modules/system/admin.php', $env['SCRIPT_NAME']);
        self::assertSame('/modules/system/admin.php', $env['PHP_SELF']);
        self::assertSame('/modules/system/admin.php?fct=users', $env['REQUEST_URI']);
        self::assertSame('fct=users', $env['QUERY_STRING']);
        self::assertSame('/modules/system/admin.php', $env['SCRIPT_FILENAME']);
        self::assertSame('/', $env['DOCUMENT_ROOT']);
        self::assertSame('/modules/system', $env['cwd']);
        self::assertSame('users', $env['fct']);
    }

    public function testPostedFieldsReachTheScript(): void
    {
        $response = $this->request('POST', '/modules/system/admin.php?fct=users', ['op' => 'save'], ['HTTP_X_ICMS_TEST_TAKEOVER' => 'off']);

        $env = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(['op' => 'save'], $env['post']);
        self::assertSame('users', $env['request']['fct']);
        self::assertSame('save', $env['request']['op']);
    }

    /**
     * @scenario Global variables keep working
     */
    public function testTopLevelVariablesAreGlobals(): void
    {
        $response = $this->request('GET', '/globals/page.php');

        self::assertSame('global: set at the top level of page.php', $response->getContent(), self::serverLog());
    }

    /**
     * @scenario Unknown path
     */
    public function testUnknownPathRunsTheLegacyFrontController(): void
    {
        $response = $this->request('GET', '/does-not-exist');

        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertTrue($body['front_controller']);
        self::assertSame('/does-not-exist', $body['uri']);
    }

    /**
     * @scenario Encoded traversal attempt
     */
    public function testEncodedTraversalIsRefused(): void
    {
        foreach (['/%2e%2e/%2e%2e/etc/passwd', '/..%2f..%2fetc/passwd', '/modules/%2e%2e/%2e%2e/%2e%2e/composer.json', '/%2e%2e%5c%2e%2e%5ccomposer.json'] as $target) {
            [$status, $body] = $this->rawGet($target);

            self::assertSame(404, $status, $target . self::serverLog());
            self::assertStringNotContainsString('root:', $body);
            self::assertStringNotContainsString('"require"', $body, 'no file outside the legacy root is read');
        }
    }

    /**
     * @scenario Compiled template directory
     */
    public function testCompiledTemplatesAreNotExecuted(): void
    {
        $response = $this->request('GET', '/templates_c/compiled.php');

        self::assertSame(404, $response->getStatusCode());
        self::assertStringNotContainsString('compiled template executed', $response->getContent());
    }

    public function testNonPublicFilesAndUploadedScriptsAreRefused(): void
    {
        self::assertSame(404, $this->request('GET', '/templates_c/missing.php')->getStatusCode(), 'also when the file does not exist');
        self::assertSame(404, $this->request('GET', '/cache/data.txt')->getStatusCode());

        $upload = $this->request('GET', '/uploads/evil.php');
        self::assertSame(404, $upload->getStatusCode());
        self::assertStringNotContainsString('upload executed', $upload->getContent());

        self::assertSame(200, $this->request('GET', '/uploads/picture.txt')->getStatusCode(), 'uploaded files are still served');
    }

    /**
     * @scenario Redirect followed by exit
     */
    public function testRedirectFollowedByExit(): void
    {
        $response = $this->request('GET', '/redirect-exit.php');

        self::assertSame(302, $response->getStatusCode(), self::serverLog());
        self::assertSame('/user.php', $response->getHeader('Location'));
        self::assertStringNotContainsString('not reached', $response->getContent());
    }

    /**
     * @scenario Multiple cookies
     */
    public function testEveryCookieKeepsItsAttributes(): void
    {
        $response = $this->request('GET', '/two-cookies.php');

        $cookies = (array) $response->getHeader('Set-Cookie', false);
        self::assertCount(2, $cookies, self::serverLog());
        self::assertSame('first=one; path=/; HttpOnly', $cookies[0]);
        self::assertStringStartsWith('second=two; expires=', $cookies[1]);
        self::assertStringContainsString('; Max-Age=', $cookies[1]);
        self::assertStringContainsString('; path=/sub', $cookies[1]);
        self::assertStringContainsString('; SameSite=Lax', $cookies[1]);
        self::assertSame('two cookies', $response->getContent());
    }

    /**
     * @scenario Output buffering level
     */
    public function testUnclosedBuffersAreCollected(): void
    {
        self::assertSame('outer inner innermost', $this->request('GET', '/nested-buffers.php')->getContent(), self::serverLog());
        self::assertSame('buffered then exit', $this->request('GET', '/exit-with-buffers.php')->getContent(), self::serverLog());
    }

    /**
     * @scenario Fatal error in production
     */
    public function testFailureDisclosesNothingOutsideDebugMode(): void
    {
        $id = bin2hex(random_bytes(6));
        $response = $this->request('GET', '/throws.php?id=' . $id);

        self::assertSame(500, $response->getStatusCode(), self::serverLog());
        $body = $response->getContent();
        self::assertStringNotContainsString('legacy failure', $body);
        self::assertStringNotContainsString('partial output', $body);
        self::assertStringNotContainsString('throws.php', $body);
        self::assertStringNotContainsString(str_replace('\\', '/', self::fixtureRoot()), str_replace('\\', '/', $body));
        self::assertStringContainsString('legacy failure ' . $id, self::applicationLog(), 'the details are logged');
    }
}
