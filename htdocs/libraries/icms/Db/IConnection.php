<?php
declare(strict_types=1);

namespace Icms\Db;

/**
 * Interface for database adapters.
 *
 * All the methods in this class are PDO methods, with the exception of escape, which is a legacy method
 *
 * @since 1.4
 */
interface IConnection
{

}

\class_alias(IConnection::class, 'icms_db_IConnection');
