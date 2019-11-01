<?php

/**
 * Database connection
 *
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package    ICMS\Database
 */
class icms_db_Connection extends \Aura\Sql\ExtendedPdo implements icms_db_IConnection, icms_db_legacy_IDatabase
{
	/**
	 * Database prefix
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Last row count
	 *
	 * @var int
	 */
	protected $lastRowCount = 0;

	/**
	 *  Safely escape the string, but strips the outer quotes
	 *
	 * This is a legacy method and not part of PDO and must be declared in any class that implements this interface
	 *
	 * @see icms_db_IConnection::escape()
	 * @param    string $string
	 * @return    string
	 */
	public function escape($string)
	{
		return substr($this->quote($string), 1, -1);
	}

	/**
	 * Executes an SQL statement and returns a result set as an SQL statement object
	 * @see PDO::query()
	 * @param string $statement
	 * @param array $fetch
	 * @return icms_db_Statement|mixed|PDOStatement
	 */
	public function query($statement, ...$fetch)
	{
		if (func_num_args() === 3) {
			return $this->queryF($statement, $fetch[0], $fetch[1]);
		}

		return call_user_func_array(['parent', 'query'], func_get_args());
	}

	/**
	 * @inheritDoc
	 */
	public function queryF($sql, $limit = 0, $start = 0)
	{
		if (!empty($limit)) {
			$sql = $sql . ' LIMIT ' . ((int)$start) . ', ' . ((int)$limit);
		}
		$result = $this->perform($sql);
		if ($result) {
			$this->lastRowCount = $result->rowCount();
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $sql, 'errorno' => null, 'error' => null));
		} else {
			$this->lastRowCount = null;
			$errorinfo = $this->errorInfo();
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $sql, 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
		}
		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function setLogger($logger)
	{
		trigger_error('setLogger method does nothing is deprecated.', E_USER_DEPRECATED);
	}

	/**
	 * @inheritDoc
	 */
	public function setPrefix($value)
	{
		$this->prefix = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function prefix($tablename = '')
	{
		return $tablename ? $this->prefix . '_' . $tablename : $this->prefix;
	}

	/**
	 * @inheritDoc
	 */
	public function genId($sequence)
	{
		trigger_error('genId will be removed', E_USER_DEPRECATED);

		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRow($result)
	{
		return ($result instanceof PDOStatement) ? $result->fetch(self::FETCH_NUM) : false;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchArray($result)
	{
		return ($result instanceof PDOStatement) ? $result->fetchAll(self::FETCH_ASSOC) : false;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchBoth($result)
	{
		return ($result instanceof PDOStatement) ? $result->fetchAll(self::FETCH_BOTH) : false;
	}

	/**
	 * @inheritDoc
	 */
	public function getInsertId()
	{
		trigger_error('Use lastInsertId instead of getInsertId.', E_USER_DEPRECATED);

		return parent::lastInsertId();
	}

	/**
	 * @inheritDoc
	 */
	public function getRowsNum($result)
	{
		trigger_error('getRowsNum will be removed.', E_USER_DEPRECATED);

		return $result ? $result->rowCount() : 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getAffectedRows()
	{
		trigger_error('getAffectedRows will be removed.', E_USER_DEPRECATED);

		return $this->lastRowCount;
	}

	/**
	 * @inheritDoc
	 */
	public function close()
	{
		trigger_error('close method is replaced with disconnect.', E_USER_DEPRECATED);

		return parent::disconnect();
	}

	/**
	 * @inheritDoc
	 */
	public function freeRecordSet($result)
	{
		trigger_error('freeRecordSet method will be removed.', E_USER_DEPRECATED);

		return ($result instanceof \PDOStatement) && $result->closeCursor();
	}

	/**
	 * @inheritDoc
	 */
	public function error()
	{
		$error = parent::errorInfo();
		if (!$error) {
			return null;
		}
		return $error[1];
	}

	/**
	 * @inheritDoc
	 */
	public function errno()
	{
		return parent::errorCode();
	}

	/**
	 * @inheritDoc
	 */
	public function quoteString($str)
	{
		trigger_error('Use quote instead.', E_USER_DEPRECATED);

		return parent::quote($str);
	}

	/**
	 * @inheritDoc
	 */
	public function getFieldName($result, $offset)
	{
		return $result ? $result->getColumnMeta($offset)['name'] : false;
	}

	/**
	 * @inheritDoc
	 */
	public function getFieldType($result, $offset)
	{
		return $result ? $result->getColumnMeta($offset)['mysql:decl_type'] : false;
	}

	/**
	 * @inheritDoc
	 */
	public function getFieldsNum($result)
	{
		return $result ? $result->columnCount() : false;
	}
}
