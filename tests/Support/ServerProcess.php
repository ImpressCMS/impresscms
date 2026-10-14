<?php

declare(strict_types=1);

namespace App\Tests\Support;

use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * The web server for the Http suite.
 *
 * Normally a `php -S` server on a free local port that serves the web root
 * `htdocs/`, with the test site's mainfile (ICMS_MAINFILE) in its environment;
 * its index.php hands requests to the host in the test site's trust path. The
 * routing level comes from ICMS_TEST_ROUTING:
 *
 * - `3` (default): every request goes to index.php (php -S router), as with
 *   the level 3 rewrite rules of docs/server/;
 * - `2`: existing files are served or executed directly and unknown paths go
 *   to index.php (php -S without router), as with a fallback rule.
 *
 * With ICMS_TEST_BASE_URL the suite tests an already running server instead
 * (Apache, nginx or FrankenPHP with the configuration from docs/server/).
 */
final class ServerProcess
{
    private ?Process $process = null;
    private int $port = 0;

    /**
     * @param array<string, string> $env
     */
    public function __construct(
        private readonly string $documentRoot,
        private readonly ?string $router = null,
        private readonly array $env = [],
        private readonly ?string $externalBaseUri = null,
    ) {
    }

    /**
     * The test site: the server at ICMS_TEST_BASE_URL, or `php -S` on htdocs/
     * at the routing level of ICMS_TEST_ROUTING.
     *
     * @param array<string, string> $env
     */
    public static function forSite(array $env = []): self
    {
        $root = TestSite::repoRoot();
        if (null !== $external = self::externalBaseUri()) {
            return new self($root . '/htdocs', null, [], $external);
        }

        return new self(
            $root . '/htdocs',
            self::routing() === 3 ? $root . '/htdocs/index.php' : null,
            $env + ['APP_ENV' => 'test', 'APP_DEBUG' => '0'],
        );
    }

    /**
     * The host with another web root (the bridge fixtures), every request
     * routed to it.
     *
     * @param array<string, string> $env for example APP_DEBUG
     */
    public static function forLegacyRoot(string $legacyRoot, array $env = []): self
    {
        return new self(
            $legacyRoot,
            TestSite::repoRoot() . '/tests/Support/legacy-root-router.php',
            ['ICMS_LEGACY_ROOT' => $legacyRoot] + $env + ['APP_ENV' => 'test', 'APP_DEBUG' => '0'],
        );
    }

    /** The routing level of the test server: 2 or 3 (ICMS_TEST_ROUTING). */
    public static function routing(): int
    {
        $level = getenv('ICMS_TEST_ROUTING') ?: '3';
        if (!in_array($level, ['2', '3'], true)) {
            throw new RuntimeException("ICMS_TEST_ROUTING must be 2 or 3, got '{$level}'.");
        }

        return (int) $level;
    }

    /** The external server under test (ICMS_TEST_BASE_URL), if any. */
    public static function externalBaseUri(): ?string
    {
        $url = getenv('ICMS_TEST_BASE_URL');

        return is_string($url) && $url !== '' ? rtrim($url, '/') : null;
    }

    public function start(float $timeout = 15.0): void
    {
        if ($this->externalBaseUri !== null || $this->process?->isRunning()) {
            return;
        }

        $this->port = self::freePort();
        // Production-like error handling: PHP errors go to the server log
        // (stderr), not into pages, independent of the local php.ini.
        // php -S only exposes its environment (APP_ENV, APP_DEBUG, …) to
        // Symfony through $_ENV, which php.ini-production's "GPCS" leaves empty.
        $command = [
            PHP_BINARY,
            '-d', 'variables_order=EGPCS',
            '-d', 'display_errors=0',
            '-d', 'log_errors=1',
            '-d', 'error_reporting=' . E_ALL,
            '-S', '127.0.0.1:' . $this->port,
            '-t', $this->documentRoot,
        ];
        if ($this->router !== null) {
            $command[] = $this->router;
        }

        $this->process = new Process(
            $command,
            $this->documentRoot,
            ['ICMS_MAINFILE' => TestSite::mainfile()] + $this->env,
            null,
            null,
        );
        $this->process->start();

        $deadline = microtime(true) + $timeout;
        while (microtime(true) < $deadline) {
            if (!$this->process->isRunning()) {
                throw new RuntimeException("php -S exited early:\n" . $this->process->getErrorOutput() . $this->process->getOutput());
            }
            $socket = @fsockopen('127.0.0.1', $this->port, $errno, $errstr, 0.2);
            if ($socket !== false) {
                fclose($socket);

                return;
            }
            usleep(50_000);
        }

        $this->stop();
        throw new RuntimeException("php -S did not accept connections on port {$this->port} within {$timeout}s.");
    }

    public function stop(): void
    {
        if ($this->process !== null && $this->process->isRunning()) {
            $this->process->stop(2);
        }
        $this->process = null;
    }

    public function baseUri(): string
    {
        return $this->externalBaseUri ?? 'http://127.0.0.1:' . $this->port;
    }

    public function isExternal(): bool
    {
        return $this->externalBaseUri !== null;
    }

    public function documentRoot(): string
    {
        return $this->documentRoot;
    }

    /**
     * Everything the server wrote so far (access log, PHP errors).
     */
    public function log(): string
    {
        return $this->process === null ? '' : $this->process->getErrorOutput() . $this->process->getOutput();
    }

    public function __destruct()
    {
        $this->stop();
    }

    private static function freePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        if ($socket === false) {
            throw new RuntimeException("Cannot allocate a free port: {$errstr}");
        }
        $name = (string) stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($name, strrpos($name, ':') + 1);
    }
}
