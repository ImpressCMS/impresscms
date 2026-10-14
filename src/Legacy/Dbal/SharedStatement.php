<?php

declare(strict_types=1);

namespace App\Legacy\Dbal;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;

/**
 * Prepared statement on the shared PDO; runs in ERRMODE_EXCEPTION (see ErrorMode).
 */
final class SharedStatement extends AbstractStatementMiddleware
{
    public function __construct(Statement $wrappedStatement, private readonly \PDO $pdo)
    {
        parent::__construct($wrappedStatement);
    }

    public function bindValue(int|string $param, mixed $value, ParameterType $type): void
    {
        ErrorMode::throwing($this->pdo, fn () => parent::bindValue($param, $value, $type));
    }

    public function execute(): Result
    {
        return ErrorMode::throwing($this->pdo, fn () => parent::execute());
    }
}
