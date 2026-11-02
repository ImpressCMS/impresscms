<?php

declare(strict_types=1);

namespace App\Legacy;

use Closure;
use Icms\Kernel\Bootstrap;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * The ImpressCMS runtime for Laravel-owned requests (design L6).
 *
 * Boots legacy lazily, in headless mode, up to the stage a request needs:
 * nothing for routes that do not ask for it, so `GET /up` never touches
 * ImpressCMS or the database. Legacy state is process-wide, so the stages
 * that already ran (also those of a legacy page in the same process) count.
 */
final class LegacyRuntime
{
    /** icms:: service => stage that creates it */
    private const SERVICE_STAGES = [
        'preload' => 'services',
        'security' => 'services',
        'logger' => 'services',
        'db' => 'services',
        'xoopsDB' => 'services',
        'config' => 'services',
        'session' => 'services',
        'user' => 'services',
        'module' => 'module',
    ];

    private ?Bootstrap $bootstrap = null;

    /** @var array<string, list<Closure(): void>> */
    private array $beforeStage = [];

    public function __construct(
        private readonly string $mainfile,
        private readonly Container $container,
    ) {
    }

    /**
     * Run the headless bootstrap up to and including $stage.
     *
     * @throws \Icms\Kernel\SiteClosedException
     * @throws \Icms\Kernel\NotInstalledException
     */
    public function bootTo(string $stage): void
    {
        if (Bootstrap::isCompleted($stage)) {
            return;
        }

        if ($this->bootstrap === null) {
            $this->describeRequestInSuperglobals();
            $this->bootstrap = new Bootstrap(Bootstrap::MODE_HEADLESS, $this->mainfile);
        }

        foreach (Bootstrap::STAGES as $name) {
            if (! Bootstrap::isCompleted($name)) {
                foreach ($this->beforeStage[$name] ?? [] as $hook) {
                    $hook();
                }

                $this->runStage($name);
            }

            if ($name === $stage) {
                return;
            }
        }
    }

    public function isBooted(string $stage): bool
    {
        return Bootstrap::isCompleted($stage);
    }

    /**
     * Register a callback that runs right before a stage, if that stage has
     * not run yet (for example the database connection provider before `services`).
     *
     * @param Closure(): void $hook
     */
    public function beforeStage(string $stage, Closure $hook): void
    {
        if (! in_array($stage, Bootstrap::STAGES, true)) {
            throw new InvalidArgumentException("Unknown bootstrap stage \"{$stage}\".");
        }

        $this->beforeStage[$stage][] = $hook;
    }

    /**
     * An icms:: service, booting the stage that creates it. Returns the very
     * instance that legacy code sees as icms::$<name>.
     */
    public function service(string $name): mixed
    {
        $stage = self::SERVICE_STAGES[$name] ?? throw new InvalidArgumentException("Unknown ImpressCMS service \"{$name}\".");
        $this->bootTo($stage);

        return \icms::${$name};
    }

    /**
     * Laravel's error handler turns every warning into an exception; legacy
     * boot code has warnings, and installs its own handler (Icms\Core\Logger)
     * only during `services`. Until then PHP handles them, as without the
     * host. A stage that installs a handler keeps it, as legacy expects.
     */
    private function runStage(string $name): void
    {
        $phpHandling = static fn (): bool => false;
        set_error_handler($phpHandling);

        try {
            $this->bootstrap?->run($name);
        } finally {
            if (self::currentErrorHandler() === $phpHandling) {
                restore_error_handler();
            }
        }
    }

    private static function currentErrorHandler(): ?callable
    {
        $handler = set_error_handler(null);
        restore_error_handler();

        return $handler;
    }

    /**
     * Legacy code reads the superglobals. In a real request they describe the
     * request already; Laravel's test client only builds a Request object.
     */
    private function describeRequestInSuperglobals(): void
    {
        if (! $this->container->bound('request')) {
            return;
        }

        $request = $this->container->make('request');
        if (! $request instanceof Request) {
            return;
        }

        if (($_SERVER['REQUEST_URI'] ?? null) === $request->server->get('REQUEST_URI')) {
            return;
        }

        $request->overrideGlobals();
    }
}
