<?php

declare(strict_types=1);

namespace App\Database;

use Closure;
use Illuminate\Database\LostConnectionException;
use Illuminate\Database\MySqlConnection;
use PDO;

/**
 * The Laravel connection over the PDO that ImpressCMS shares (design L7).
 *
 * Legacy code checks return values (ERRMODE_SILENT, which Icms\Db\Factory
 * sets); Laravel relies on exceptions. Every Laravel query therefore runs in
 * ERRMODE_EXCEPTION and restores the legacy mode afterwards.
 *
 * A lost connection is not reopened: legacy would keep the dead PDO and the
 * two layers would stop sharing one connection and transaction state.
 */
final class SharedMySqlConnection extends MySqlConnection
{
    /**
     * @param string $query
     * @param array<array-key, mixed> $bindings
     */
    protected function run($query, $bindings, Closure $callback): mixed
    {
        $this->reconnectIfMissingConnection();

        $pdo = $this->getPdo();
        $previous = $pdo->getAttribute(PDO::ATTR_ERRMODE);
        if ($previous === PDO::ERRMODE_EXCEPTION) {
            return parent::run($query, $bindings, $callback);
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            return parent::run($query, $bindings, $callback);
        } finally {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, $previous);
        }
    }

    public function reconnect(): never
    {
        throw new LostConnectionException('The database connection shared with ImpressCMS was lost and is not reopened.');
    }
}
