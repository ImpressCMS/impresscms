<?php

declare(strict_types=1);

namespace App\Providers;

use App\Database\IcmsMySqlConnector;
use App\Database\SharedMySqlConnection;
use App\Legacy\HandlerLocator;
use App\Legacy\LegacyEventForwarder;
use App\Legacy\LegacyRuntime;
use Icms\Db\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;
use LogicException;
use PDO;

/**
 * The ImpressCMS runtime in the Laravel container (design L6–L9): the headless
 * bootstrap, the shared database connection, the icms:: services, the
 * handlers and the event forwarding.
 */
final class IcmsServiceProvider extends ServiceProvider
{
    /** container id => icms:: property */
    private const SHARED_SERVICES = [
        'icms.db' => 'db',
        'icms.db.legacy' => 'xoopsDB',
        'icms.config' => 'config',
        'icms.security' => 'security',
        'icms.logger' => 'logger',
        'icms.session' => 'session',
        'icms.preload' => 'preload',
    ];

    /** legacy reassigns these during a request (login, module launch) */
    private const CURRENT_SERVICES = [
        'icms.user' => 'user',
        'icms.module' => 'module',
    ];

    /** autowiring: class => container id */
    private const CLASS_ALIASES = [
        \Icms\Db\IConnection::class => 'icms.db',
        \Icms\Db\Legacy\PdoDatabase::class => 'icms.db.legacy',
        \Icms\Config\Handler::class => 'icms.config',
        \Icms\Core\Security::class => 'icms.security',
        \Icms\Core\Logger::class => 'icms.logger',
        \Icms\Core\Session::class => 'icms.session',
        \Icms\Preload\Handler::class => 'icms.preload',
    ];

    public function register(): void
    {
        $this->app->singleton(LegacyEventForwarder::class);

        $this->app->singleton(LegacyRuntime::class, static function (Application $app): LegacyRuntime {
            $runtime = new LegacyRuntime(config('icms.legacy.root') . '/mainfile.php', $app);
            $runtime->beforeStage('constants', static fn () => $app->make(LegacyEventForwarder::class)->install());
            $runtime->beforeStage('services', static fn () => self::shareConnection($app));

            return $runtime;
        });

        foreach (self::SHARED_SERVICES as $id => $property) {
            $this->app->singleton($id, static fn (Application $app): mixed => $app->make(LegacyRuntime::class)->service($property));
        }

        foreach (self::CURRENT_SERVICES as $id => $property) {
            $this->app->bind($id, static fn (Application $app): mixed => $app->make(LegacyRuntime::class)->service($property));
        }

        foreach (self::CLASS_ALIASES as $class => $id) {
            $this->app->alias($id, $class);
        }

        $this->app->singleton('icms.handlers', static fn (Application $app): HandlerLocator => new HandlerLocator(
            $app->make(LegacyRuntime::class),
            config('icms.legacy.root') . '/libraries/Icms/aliases.php',
        ));
        $this->app->alias('icms.handlers', HandlerLocator::class);

        $this->registerDatabase();
    }

    /**
     * The `icms` connection: Laravel's MySQL driver with ImpressCMS' PDO class
     * and the settings of mainfile.php, read when the connection is opened.
     */
    private function registerDatabase(): void
    {
        $this->app->bind('db.connector.mysql', IcmsMySqlConnector::class);

        Connection::resolverFor('mysql', static fn ($pdo, string $database, string $prefix, array $config): Connection => new SharedMySqlConnection($pdo, $database, $prefix, $config));

        $this->app->afterResolving('db', static function (DatabaseManager $db, Application $app): void {
            $db->extend('icms', static function (array $config, string $name) use ($app): Connection {
                $app->make(LegacyRuntime::class)->bootTo('constants');

                return $app->make('db.factory')->make(self::legacyDatabaseSettings() + $config, $name);
            });
        });
    }

    /**
     * @return array<string, mixed>
     */
    private static function legacyDatabaseSettings(): array
    {
        $settings = [
            'host' => XOOPS_DB_HOST,
            'database' => XOOPS_DB_NAME,
            'username' => XOOPS_DB_USER,
            'password' => XOOPS_DB_PASS,
            'prefix' => XOOPS_DB_PREFIX . '_',
            'dsn_charset' => XOOPS_DB_CHARSET ?: 'utf8mb4',
            'options' => [PDO::ATTR_PERSISTENT => (int) XOOPS_DB_PCONNECT === 1] + (array) config('database.connections.icms.options'),
        ];

        if (defined('ICMS_DB_PORT')) {
            $settings['port'] = ICMS_DB_PORT;
        }

        return $settings;
    }

    /**
     * Icms\Db\Factory takes its PDO from Laravel, so icms::$db, icms::$xoopsDB
     * and every handler use Laravel's physical connection and transaction state.
     */
    private static function shareConnection(Application $app): void
    {
        try {
            Factory::setConnectionProvider(static fn (): PDO => $app->make('db')->connection('icms')->getPdo());
        } catch (LogicException) {
            // legacy code in this process already opened its own connection
        }
    }
}
