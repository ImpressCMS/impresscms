<?php

declare(strict_types=1);

namespace App\Tests\Support;

use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * A `php -S` server on a free local port for the Http suite.
 *
 * Serves either `htdocs/` (direct deployment) or `public/` (Symfony host)
 * with the test site's mainfile (ICMS_MAINFILE) in its environment.
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
    ) {
    }

    /**
     * The server for the document root selected by ICMS_TEST_DOCROOT
     * (`htdocs`, the default, or `public`).
     *
     * @param array<string, string> $env
     */
    public static function forConfiguredDocumentRoot(array $env = []): self
    {
        $docroot = self::configuredDocumentRoot();
        $root = TestSite::repoRoot();

        return $docroot === 'public'
            ? new self($root . '/public', $root . '/public/index.php', $env + ['APP_ENV' => 'test', 'APP_DEBUG' => '0'])
            : new self($root . '/htdocs', null, $env);
    }

    /**
     * The Symfony host (public/) in the test environment.
     *
     * @param array<string, string> $env for example ICMS_LEGACY_ROOT or APP_DEBUG
     */
    public static function forHost(array $env = []): self
    {
        $root = TestSite::repoRoot();

        return new self($root . '/public', $root . '/public/index.php', $env + ['APP_ENV' => 'test', 'APP_DEBUG' => '0']);
    }

    public static function configuredDocumentRoot(): string
    {
        $docroot = getenv('ICMS_TEST_DOCROOT') ?: 'htdocs';
        if (!in_array($docroot, ['htdocs', 'public'], true)) {
            throw new RuntimeException("ICMS_TEST_DOCROOT must be 'htdocs' or 'public', got '{$docroot}'.");
        }

        return $docroot;
    }

    public function start(float $timeout = 15.0): void
    {
        if ($this->process?->isRunning()) {
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
        return 'http://127.0.0.1:' . $this->port;
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
