<?php

declare(strict_types=1);

namespace App\Tests\Support;

use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Bridge mechanics against tests/Fixtures/legacy-root instead of ImpressCMS:
 * small scripts that report their environment, redirect, set cookies, throw
 * or leave output buffers open, served by the host through a php -S router.
 * Runs once per CI run: at routing level 3 (the default) and without an
 * external server (ICMS_TEST_BASE_URL).
 */
abstract class LegacyFixtureTestCase extends TestCase
{
    protected static ?ServerProcess $server = null;

    protected HttpBrowser $browser;

    public static function fixtureRoot(): string
    {
        return TestSite::repoRoot() . '/tests/Fixtures/legacy-root';
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (!self::runs()) {
            return;
        }

        static::$server = ServerProcess::forLegacyRoot(self::fixtureRoot(), ['APP_DEBUG' => static::debug() ? '1' : '0']);
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
        if (static::$server === null) {
            self::markTestSkipped('Bridge fixture tests run at routing level 3 without ICMS_TEST_BASE_URL.');
        }
        $this->browser = new HttpBrowser(HttpClient::create(['timeout' => 30]), null, new CookieJar());
        $this->browser->followRedirects(false);
    }

    protected static function debug(): bool
    {
        return false;
    }

    private static function runs(): bool
    {
        return ServerProcess::routing() === 3 && ServerProcess::externalBaseUri() === null;
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, string> $server
     */
    protected function request(string $method, string $path, array $parameters = [], array $server = []): Response
    {
        $this->browser->request($method, static::$server->baseUri() . $path, $parameters, [], $server);

        return $this->browser->getInternalResponse();
    }

    /**
     * Send a request target exactly as given; HTTP clients normalise paths such
     * as /%2e%2e/ before sending them.
     *
     * @return array{int, string} status code and body
     */
    protected function rawGet(string $target): array
    {
        $uri = parse_url(static::$server->baseUri());
        $socket = fsockopen((string) $uri['host'], (int) $uri['port'], $errno, $errstr, 5);
        self::assertNotFalse($socket, $errstr);
        fwrite($socket, "GET {$target} HTTP/1.1\r\nHost: {$uri['host']}:{$uri['port']}\r\nConnection: close\r\n\r\n");
        $raw = (string) stream_get_contents($socket);
        fclose($socket);

        [$head, $body] = array_pad(explode("\r\n\r\n", $raw, 2), 2, '');
        preg_match('#^HTTP/\S+ (\d{3})#', $head, $status);

        return [(int) ($status[1] ?? 0), $body];
    }

    protected static function serverLog(): string
    {
        return "\n--- php -S log ---\n" . (static::$server?->log() ?? '');
    }

    protected static function applicationLog(): string
    {
        return (string) @file_get_contents(TestSite::repoRoot() . '/var/log/test.log');
    }
}
