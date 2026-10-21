<?php

declare(strict_types=1);

namespace App\Legacy\Dbal;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;

/**
 * DBAL driver connection over the PDO that ImpressCMS shares; every call runs
 * in ERRMODE_EXCEPTION (see ErrorMode).
 */
final class SharedConnection extends AbstractConnectionMiddleware
{
    public function __construct(Connection $wrappedConnection, private readonly \PDO $pdo)
    {
        parent::__construct($wrappedConnection);
    }

    public function prepare(string $sql): Statement
    {
        return new SharedStatement(ErrorMode::throwing($this->pdo, fn () => parent::prepare($sql)), $this->pdo);
    }

    public function query(string $sql): Result
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::query($sql));
    }

    public function quote(string $value): string
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::quote($value));
    }

    public function exec(string $sql): int|string
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::exec($sql));
    }

    public function lastInsertId(): int|string
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::lastInsertId());
    }

    public function beginTransaction(): void
    {
        ErrorMode::throwing($this->pdo, fn () => parent::beginTransaction());
    }

    public function commit(): void
    {
        ErrorMode::throwing($this->pdo, fn () => parent::commit());
    }

    public function rollBack(): void
    {
        ErrorMode::throwing($this->pdo, fn () => parent::rollBack());
    }

    public function getServerVersion(): string
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::getServerVersion());
    }
}
