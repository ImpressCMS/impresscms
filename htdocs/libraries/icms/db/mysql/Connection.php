<?php

/**
 *
 * @copyright The ImpressCMS Project - http://www.impresscms.org/
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since 2.0
 * @category ICMS
 * @package Database
 * @subpackage MySQL
 */

/**
 *
 * @copyright The ImpressCMS Project - http://www.impresscms.org/
 *
 * @category ICMS
 * @package Database
 * @subpackage MySQL
 */
class icms_db_mysql_Connection extends PDO implements icms_db_IConnection {

	/**
	 * Safely escape the string, but strips the outer quotes
	 *
	 * @see icms_db_IConnection::escape()
	 * @param string $string
	 * @return string
	 */
	public function escape($string) {
		return substr($this->quote($string), 1, -1);
	}

	/**
	 *
	 * @see PDO::query()
	 * @return
	 */
	// public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = []) {
             //PDO::query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false
	public function query($query, $fetchMode = PDO::ATTR_DEFAULT_FETCH_MODE, ...$fetch_mode_args) {
		$mode = $fetchMode;
		$args = func_get_args();
		$sql = $args[0];
		// the use of icms_db_IConnection is correct - without it, the query count in debug is not correct
		$result = call_user_func_array(array(parent::class, 'query'), $args);

		// trigger events for the debug console - see plugins/preloads/debug_mode.php
		if ($result) {
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => NULL, 'error' => NULL));
		} else {
			$errorinfo = $this->errorInfo();
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
			// durable record of the failure without the SQL, see icms_core_EventLog
			icms_core_EventLog::logDatabaseFailure('db.query', $errorinfo);
		}

		return $result;
	}

	/**
	 * Executes an SQL statement and returns the number of affected rows, logging a failure
	 *
	 * @see PDO::exec()
	 * @param string $statement
	 * @return int|false
	 */
	#[\ReturnTypeWillChange]
	public function exec($statement) {
		$result = parent::exec($statement);
		if ($result === false) {
			icms_core_EventLog::logDatabaseFailure('db.exec', $this->errorInfo());
		}

		return $result;
	}

	/**
	 * Prepares a statement, logging a failure
	 *
	 * Failures of PDOStatement::execute() are not reported here.
	 *
	 * @see PDO::prepare()
	 * @param string $query
	 * @param array $options
	 * @return PDOStatement|false
	 */
	#[\ReturnTypeWillChange]
	public function prepare($query, $options = array()) {
		$result = parent::prepare($query, $options);
		if ($result === false) {
			icms_core_EventLog::logDatabaseFailure('db.prepare', $this->errorInfo());
		}

		return $result;
	}
}
