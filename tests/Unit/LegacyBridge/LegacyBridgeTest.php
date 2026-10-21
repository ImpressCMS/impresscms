<?php

declare(strict_types=1);

namespace App\Tests\Unit\LegacyBridge;

use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyRequestHydrator;
use App\LegacyBridge\LegacyResponse;
use App\LegacyBridge\LegacyScriptResolver;
use App\Tests\Support\LegacyFixtureTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The bridge's prepare/complete cycle in-process, with the fixture scripts:
 * host state before and after a script run.
 */
final class LegacyBridgeTest extends TestCase
{
    private function bridge(): LegacyBridge
    {
        $resolver = new LegacyScriptResolver(LegacyFixtureTestCase::fixtureRoot(), 'index.php', ['css', 'txt'], ['templates_c', 'cache'], ['uploads']);

        return new LegacyBridge($resolver, new LegacyRequestHydrator(), ['css' => 'text/css; charset=UTF-8'], false, new NullLogger());
    }

    private function runScript(string $uri, ?\Closure $assertDuringRun = null): Response
    {
        $bridge = $this->bridge();
        $request = Request::create($uri);
        $finished = [];
        $execution = $bridge->prepare($request, $bridge->resolve($request), static function (Response $response) use (&$finished): void {
            $finished[] = $response;
        });

        (static function (string $script) use ($assertDuringRun): void {
            require $script;
            if ($assertDuringRun !== null) {
                $assertDuringRun();
            }
        })($execution->script());

        $response = $bridge->complete($execution);
        self::assertSame([], $finished, 'the finisher is only used on the exit() path');

        return $response;
    }

    /**
     * @scenario Output buffering level
     */
    #[Group('spec:legacy-request-bridge')]
    public function testOutputBufferLevelIsRestored(): void
    {
        $level = ob_get_level();

        $response = $this->runScript('/nested-buffers.php');

        self::assertSame($level, ob_get_level());
        self::assertSame('outer inner innermost', $response->getContent());
    }

    public function testBuffersClosedBeyondTheScriptsOwnAreReopened(): void
    {
        ob_start();
        $level = ob_get_level();
        $bridge = $this->bridge();
        $request = Request::create('/style.css');
        $execution = $bridge->prepare($request, $bridge->resolve($request), static fn () => null);

        while (ob_get_level() > $level - 1) {
            ob_end_clean();
        }
        $bridge->complete($execution);

        self::assertSame($level, ob_get_level());
        ob_end_clean();
    }

    public function testSuperglobalsAndWorkingDirectoryAreRestored(): void
    {
        $cwd = getcwd();
        $get = $_GET;
        $server = $_SERVER;

        $response = $this->runScript('/modules/system/admin.php?fct=users', function (): void {
            self::assertSame(['fct' => 'users'], $_GET);
            self::assertSame('/modules/system/admin.php', $_SERVER['SCRIPT_NAME']);
        });

        self::assertSame($cwd, getcwd());
        self::assertSame($get, $_GET);
        self::assertSame($server, $_SERVER);
        self::assertInstanceOf(LegacyResponse::class, $response);
        self::assertSame('/modules/system', json_decode((string) $response->getContent(), true)['cwd']);
    }

    #[RunInSeparateProcess]
    public function testASessionTheScriptStartedIsLeftToTheScript(): void
    {
        $bridge = $this->bridge();
        $request = Request::create('/style.css');
        $execution = $bridge->prepare($request, $bridge->resolve($request), static fn () => null);

        session_start();
        $_SESSION['written'] = 'by legacy';
        $bridge->complete($execution);

        self::assertSame(PHP_SESSION_ACTIVE, session_status());
        self::assertSame(['written' => 'by legacy'], $_SESSION, 'written when the script shuts down');
        session_abort();
    }

    #[RunInSeparateProcess]
    public function testAHostSessionIsPutBack(): void
    {
        session_start();
        $_SESSION = ['host' => 'value'];
        $bridge = $this->bridge();
        $request = Request::create('/style.css');
        $execution = $bridge->prepare($request, $bridge->resolve($request), static fn () => null);

        $_SESSION['changed'] = 'by legacy';
        $bridge->complete($execution);

        self::assertSame(['host' => 'value'], $_SESSION);
        session_abort();
    }

    public function testExceptionHandlerInstalledByTheScriptRendersTheFailure(): void
    {
        $bridge = $this->bridge();
        $request = Request::create('/throws.php');
        $execution = $bridge->prepare($request, $bridge->resolve($request), static fn () => null);

        set_exception_handler(static function (\Throwable $e): void {
            echo 'legacy handler: ' . $e->getMessage();
        });
        try {
            echo 'before ';
            $response = $bridge->fail($execution, new \RuntimeException('boom'));
        } finally {
            restore_exception_handler();
        }

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('before legacy handler: boom', $response->getContent());
    }

    public function testWithoutHandlerTheBridgeRendersAGeneric500(): void
    {
        $bridge = $this->bridge();
        $request = Request::create('/throws.php');
        $execution = $bridge->prepare($request, $bridge->resolve($request), static fn () => null);

        echo 'partial';
        $response = $bridge->fail($execution, new \RuntimeException('secret detail'));

        self::assertSame(500, $response->getStatusCode());
        self::assertStringNotContainsString('secret detail', (string) $response->getContent());
        self::assertStringNotContainsString('partial', (string) $response->getContent());
    }
}
