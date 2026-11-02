<?php

declare(strict_types=1);

namespace App\Database;

use Icms\Db\Factory;
use Illuminate\Database\Connectors\MySqlConnector;
use PDO;

/**
 * Laravel's MySQL connector, bound as `db.connector.mysql`, that opens the
 * connection as ImpressCMS' own PDO class (Icms\Db\Mysql\Connection, which
 * legacy code needs for escape() and its query events). Laravel then owns
 * the one physical connection and Icms\Db\Factory takes it over unchanged
 * through its connection provider (design L7).
 *
 * The client charset goes into the DSN, as ImpressCMS puts it there: PDO
 * escapes with the charset of the handshake, which `SET NAMES` does not change.
 */
final class IcmsMySqlConnector extends MySqlConnector
{
    /**
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array<int, mixed> $options
     */
    protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options): PDO
    {
        $class = Factory::connectionClass('mysql');

        return new $class($dsn, $username, $password, $options);
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function getHostDsn(array $config): string
    {
        $dsn = parent::getHostDsn($config);

        return isset($config['dsn_charset']) ? "{$dsn};charset={$config['dsn_charset']}" : $dsn;
    }
}
