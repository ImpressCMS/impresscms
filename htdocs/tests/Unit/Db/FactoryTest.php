<?php
/**
 * Icms\Db\Factory with and without a connection provider.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Db;

use Icms\Db\Factory;
use Icms\Db\IConnection;
use Icms\Db\Legacy\PdoDatabase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

final class FactoryTest extends TestCase
{
    protected function setUp(): void
    {
        self::resetFactory();
    }

    protected function tearDown(): void
    {
        self::resetFactory();
    }

    public function testProviderSuppliesTheConnectionOnce(): void
    {
        $calls = 0;
        $connection = self::connection();
        Factory::setConnectionProvider(static function () use (&$calls, $connection): \PDO {
            $calls++;
            return $connection;
        });

        self::assertSame($connection, Factory::pdoInstance());
        self::assertSame($connection, Factory::pdoInstance());
        self::assertSame(1, $calls);
    }

    public function testProvidedConnectionGetsTheLegacyErrorMode(): void
    {
        $connection = self::connection();
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        Factory::setConnectionProvider(static fn (): \PDO => $connection);

        Factory::pdoInstance();

        self::assertSame(\PDO::ERRMODE_SILENT, $connection->getAttribute(\PDO::ATTR_ERRMODE));
    }

    public function testProviderMustReturnAnIConnection(): void
    {
        Factory::setConnectionProvider(static fn (): \PDO => new \PDO('sqlite::memory:'));

        $this->expectException(\RuntimeException::class);
        Factory::pdoInstance();
    }

    public function testProviderCannotBeChangedOnceConnected(): void
    {
        $connection = self::connection();
        Factory::setConnectionProvider(static fn (): \PDO => $connection);
        Factory::pdoInstance();

        $this->expectException(\LogicException::class);
        Factory::setConnectionProvider(null);
    }

    public function testConnectionClassFallsBackToTheGenericConnection(): void
    {
        self::assertSame('\Icms\Db\Mysql\Connection', Factory::connectionClass('mysql'));
        self::assertSame('\Icms\Db\Connection', Factory::connectionClass('sqlite'));
    }

    // Separate processes get the library environment only from the htdocs/ bootstrap.
    #[RunInSeparateProcess]
    public function testLegacyDatabaseSharesTheProvidedConnection(): void
    {
        require_once dirname(__DIR__, 2) . '/environment.php';
        define('XOOPS_DB_TYPE', 'mysql');
        define('XOOPS_DB_PREFIX', 'icms');
        $connection = self::connection();
        Factory::setConnectionProvider(static fn (): \PDO => $connection);

        $db = Factory::instance();

        self::assertInstanceOf(PdoDatabase::class, $db);
        self::assertSame($connection, $db->conn);
        self::assertSame('icms_users', $db->prefix('users'));
    }

    #[RunInSeparateProcess]
    public function testWithoutProviderANonPdoDatabaseTypeHasNoPdoConnection(): void
    {
        define('XOOPS_DB_TYPE', 'mysql');

        self::assertNull(Factory::pdoInstance());
    }

    private static function connection(): \PDO
    {
        return new class ('sqlite::memory:') extends \PDO implements IConnection {
            public function escape(string $string): string
            {
                return substr($this->quote($string), 1, -1);
            }
        };
    }

    private static function resetFactory(): void
    {
        $factory = new \ReflectionClass(Factory::class);
        $factory->setStaticPropertyValue('pdoInstance', null);
        $factory->setStaticPropertyValue('xoopsInstance', false);
        $factory->setStaticPropertyValue('connectionProvider', null);
    }
}
