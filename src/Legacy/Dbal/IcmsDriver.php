<?php

declare(strict_types=1);

namespace App\Legacy\Dbal;

use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDO\Connection as PdoConnection;
use Doctrine\DBAL\Driver\PDO\Exception;
use Icms\Db\Factory;

/**
 * DBAL driver for the ImpressCMS database (design D7).
 *
 * Opens the connection as ImpressCMS' own PDO class (Icms\Db\Mysql\Connection,
 * which legacy code needs for escape() and its query events), so that DBAL
 * owns the one physical connection and Icms\Db\Factory can take it over
 * unchanged through its connection provider.
 */
final class IcmsDriver extends AbstractMySQLDriver
{
    public function connect(#[\SensitiveParameter] array $params): Connection
    {
        $dsn = 'mysql:';
        foreach (['host' => 'host', 'port' => 'port', 'dbname' => 'dbname', 'unix_socket' => 'unix_socket', 'charset' => 'charset'] as $param => $key) {
            if (isset($params[$param]) && $params[$param] !== '') {
                $dsn .= $key . '=' . $params[$param] . ';';
            }
        }

        $options = ($params['driverOptions'] ?? []) + [\PDO::ATTR_PERSISTENT => !empty($params['persistent'])];

        $class = Factory::connectionClass('mysql');
        try {
            /** @var \PDO $pdo */
            $pdo = new $class($dsn, (string) ($params['user'] ?? ''), (string) ($params['password'] ?? ''), $options);
        } catch (\PDOException $exception) {
            throw Exception::new($exception);
        }
        // the legacy layer's error mode; DBAL calls switch it (SharedConnection)
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

        return new SharedConnection(new PdoConnection($pdo), $pdo);
    }
}
