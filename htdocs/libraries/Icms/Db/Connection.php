<?php
namespace Icms\Db;

/**
 * Database connection
 *
 * @category ICMS
 * @package Database
 *
 * @copyright The ImpressCMS Project <http://www.impresscms.org>
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version SVN: $Id$
 */

/**
 * Database connection
 *
 * @category ICMS
 * @package Database
 */
class Connection extends \PDO implements IConnection
{
	/**
	 * Safely escape the string, but strips the outer quotes
	 *
	 * This is a legacy method and not part of PDO and must be declared in any class that implements this interface
	 *
	 * @see \IConnection::escape()
	 * @param  string $string
	 * @return string
	 */
	public function escape(string $string): string
	{
		return substr($this->quote($string), 1, -1);
	}

	/**
	 * Executes an SQL statement and returns a result set as an SQL statement object
	 *
	 * @see \PDO::query()
	 * @param string $query
	 * @param mixed|null $fetchMode
	 * @param mixed $fetchModeArgs
	 * @return \PDOStatement|false
	 */
	public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): \PDOStatement|false
	{
		if ($fetchMode === null) {
			$result = parent::query($query);
		} else {
			$result = parent::query($query, $fetchMode, ...$fetchModeArgs);
		}

		// trigger events for the debug console - see plugins/preloads/debug_mode.php
		if ($result) {
			\Icms\Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $query, 'errorno' => null, 'error' => null));
		} else {
			$errorinfo = $this->errorInfo();
			\Icms\Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $query, 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
			// durable record of the failure without the SQL, see \Icms\Core\EventLog
			\Icms\Core\EventLog::logDatabaseFailure('db.query', $errorinfo);
		}

		return $result;
	}

	/**
	 * Executes an SQL statement and returns the number of affected rows, logging a failure
	 *
	 * @see \PDO::exec()
	 * @param string $statement
	 * @return int|false
	 */
	public function exec(string $statement): int|false
	{
		$result = parent::exec($statement);
		if ($result === false) {
			\Icms\Core\EventLog::logDatabaseFailure('db.exec', $this->errorInfo());
		}

		return $result;
	}

	/**
	 * Prepares a statement, logging a failure
	 *
	 * Failures of PDOStatement::execute() are not reported here.
	 *
	 * @see \PDO::prepare()
	 * @param string $query
	 * @param array $options
	 * @return \PDOStatement|false
	 */
	public function prepare(string $query, array $options = []): \PDOStatement|false
	{
		$result = parent::prepare($query, $options);
		if ($result === false) {
			\Icms\Core\EventLog::logDatabaseFailure('db.prepare', $this->errorInfo());
		}

		return $result;
	}
}
