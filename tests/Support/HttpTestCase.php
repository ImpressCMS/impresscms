<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Base class for the Http suite: a real `php -S` server per test class, the
 * database restored per class, and a BrowserKit browser with a cookie jar.
 *
 * Redirects are not followed automatically, so tests see status codes and
 * Location headers exactly as the server sent them.
 */
abstract class HttpTestCase extends DatabaseTestCase
{
    protected static ?ServerProcess $server = null;

    protected HttpBrowser $browser;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$server = static::createServer();
        static::$server->start();
    }

    public static function tearDownAfterClass(): void
    {
        static::$server?->stop();
        static::$server = null;

        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->browser = new HttpBrowser(HttpClient::create(['timeout' => 30]), null, new CookieJar());
        $this->browser->followRedirects(false);
    }

    /**
     * Override to serve a different document root or environment.
     */
    protected static function createServer(): ServerProcess
    {
        return ServerProcess::forConfiguredDocumentRoot();
    }

    protected static function url(string $path): string
    {
        return static::$server->baseUri() . '/' . ltrim($path, '/');
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, string> $server
     */
    protected function request(string $method, string $path, array $parameters = [], array $server = []): Response
    {
        $this->browser->request($method, static::url($path), $parameters, [], $server);

        return $this->browser->getInternalResponse();
    }

    /**
     * Log in through the legacy login form and return the response to the POST.
     */
    protected function loginAs(string $uname, string $pass): Response
    {
        $this->request('GET', '/user.php');

        return $this->request('POST', '/user.php', [
            'op' => 'login',
            'uname' => $uname,
            'pass' => $pass,
        ], ['HTTP_REFERER' => static::url('/user.php')]);
    }

    protected function loginAsWebmaster(): Response
    {
        return $this->loginAs(TestSite::WEBMASTER_UNAME, TestSite::WEBMASTER_PASS);
    }

    protected function loginAsMember(): Response
    {
        return $this->loginAs(TestSite::MEMBER_UNAME, TestSite::MEMBER_PASS);
    }

    /**
     * Server log, appended to assertion messages to make failures diagnosable.
     */
    protected static function serverLog(): string
    {
        return "\n--- php -S log ---\n" . (static::$server?->log() ?? '');
    }
}
