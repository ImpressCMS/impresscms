<?php

declare(strict_types=1);

namespace App\Legacy\Dbal;

/**
 * DBAL and the ImpressCMS database layer share one PDO but expect different
 * error modes: legacy code checks return values (ERRMODE_SILENT, set by
 * Icms\Db\Factory), DBAL catches exceptions. DBAL calls switch the
 * connection to ERRMODE_EXCEPTION for their duration only.
 */
final class ErrorMode
{
    /**
     * @template T
     * @param \Closure(): T $call
     * @return T
     */
    public static function throwing(\PDO $pdo, \Closure $call): mixed
    {
        $previous = $pdo->getAttribute(\PDO::ATTR_ERRMODE);
        if ($previous === \PDO::ERRMODE_EXCEPTION) {
            return $call();
        }

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            return $call();
        } finally {
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, $previous);
        }
    }
}
