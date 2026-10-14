<?php

declare(strict_types=1);

namespace App\Legacy;

use Doctrine\DBAL\Connection;
use Icms\Db\Factory;
use Icms\Kernel\Bootstrap;
use Symfony\Component\DependencyInjection\Attribute\AutowireServiceClosure;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The ImpressCMS runtime for Symfony-owned requests (design D6).
 *
 * Boots legacy lazily, in headless mode, up to the stage a request needs:
 * nothing for routes that do not ask for it, so `GET /up` never touches
 * ImpressCMS or the database. Legacy state is process-wide, so every
 * LegacyRuntime shares the stages that already ran (also those of a legacy
 * page in the same process).
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

    /** @var array<string, list<\Closure(): void>> */
    private array $beforeStage = [];

    /**
     * @param (\Closure(): Connection)|null $connection the DBAL connection that owns
     *                                                  the database link (design D7)
     */
    public function __construct(
        private readonly string $mainfile,
        private readonly RequestStack $requestStack,
        #[AutowireServiceClosure('doctrine.dbal.default_connection')]
        private readonly ?\Closure $connection = null,
        private readonly ?LegacyEventForwarder $events = null,
    ) {
        if ($connection !== null) {
            $this->beforeStage('services', $this->shareConnection(...));
        }
    }

    /**
     * Icms\Db\Factory takes its PDO from DBAL, so icms::$db, icms::$xoopsDB and
     * every handler use DBAL's physical connection and transaction state.
     */
    private function shareConnection(): void
    {
        $connection = $this->connection;
        try {
            Factory::setConnectionProvider(static function () use ($connection): \PDO {
                $pdo = $connection()->getNativeConnection();
                assert($pdo instanceof \PDO);

                return $pdo;
            });
        } catch (\LogicException) {
            // legacy code in this process already opened its own connection
        }
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
            // Legacy code reads the superglobals; make them describe the current
            // request (in a real request they already do; KernelBrowser does not
            // set them).
            $this->requestStack->getMainRequest()?->overrideGlobals();
            $this->events?->install();
            $this->bootstrap = new Bootstrap(Bootstrap::MODE_HEADLESS, $this->mainfile);
        }

        foreach (Bootstrap::STAGES as $name) {
            if (!Bootstrap::isCompleted($name)) {
                foreach ($this->beforeStage[$name] ?? [] as $hook) {
                    $hook();
                }
                $this->bootstrap->run($name);
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
     * Register a callback that runs right before a stage, if that stage has not
     * run yet (for example the database connection provider before `services`).
     *
     * @param \Closure(): void $hook
     */
    public function beforeStage(string $stage, \Closure $hook): void
    {
        if (!in_array($stage, Bootstrap::STAGES, true)) {
            throw new \InvalidArgumentException(sprintf('Unknown bootstrap stage "%s".', $stage));
        }
        $this->beforeStage[$stage][] = $hook;
    }

    /**
     * An icms:: service, booting the stage that creates it. Returns the very
     * instance that legacy code sees as icms::$<name>.
     */
    public function service(string $name): mixed
    {
        $stage = self::SERVICE_STAGES[$name] ?? throw new \InvalidArgumentException(sprintf('Unknown ImpressCMS service "%s".', $name));
        $this->bootTo($stage);

        return \icms::${$name};
    }

    /**
     * @return list<string>
     */
    public static function serviceNames(): array
    {
        return array_keys(self::SERVICE_STAGES);
    }
}
