<?php

/**
 * Legacy interface for database classes
 *
 * This PDO connection must support the legacy database methods to
 * facilitate the complete migration to PDO and the new methods it brings.
 * It does not (and should not) introduce any new methods
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category    ICMS
 * @package	    Database
 */

namespace Icms\Db\Legacy;

use Exception;
use Icms\Db\IConnection;
use Icms\Db\IUtility;
use Icms\Db\Legacy\Mysql\Utility;
use PDO;

/**
 * Create and interact with a database connection using PDO
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @category ICMS
 * @package Database
 * @subpackage PDO
 */
class PdoDatabase extends Database
{

	/**
	 * The PDO connection that performs operations behind the scenes
	 *
	 * @var \Icms\Db\IConnection
	 */
	protected $pdo;

	/**
	 * Legacy database connection var - to be replaced by $pdo
	 *
	 * @var resource
	 */
	public $conn;

	/**
	 * Row count of the most recent statement
	 *
	 * @var int
	 */
	protected int $rowCount = 0;

	public function __construct($connection, bool $allowWebChanges = false)
	{
		parent::__construct(null, $allowWebChanges);
		$this->pdo = $connection;
		$this->conn = $this->pdo; // only for legacy support
	}

	public function connect(bool $selectdb = TRUE): bool
	{
		return TRUE;
	}

	public function close(): bool {
		$this->pdo = NULL;
		return TRUE;
	}

	public function quoteString($str): string
	{
		return $this->pdo->quote($str);
	}

	public function quote($string): string
	{
		return $this->pdo->quote($string);
	}

	public function escape($string) {
		return $this->pdo->escape($string);
	}

	public function error(): string
	{
		$error = $this->pdo->errorInfo();
		return $error [2];
	}

	public function errno(): int {
		$error = $this->pdo->errorInfo ();
		return $error [1];
	}

	public function genId(string $sequence): int
	{
		return 0; // will use auto_increment
	}

	public function query(string $sql, int $limit = 0, int $start = 0) {
		if (! $this->allowWebChanges && stripos(trim($sql), 'select') !== 0) {
			trigger_error(_CORE_DB_NOTALLOWEDINGET, E_USER_WARNING);
			return FALSE;
		}
		return $this->queryF($sql, $limit, $start);
	}

	/**
	 * perform a query on the database
	 *
	 * Legacy compatibility contract:
	 * - return a statement object for result-set queries
	 * - return TRUE for successful non-result-set queries
	 * - return FALSE on failure
	 *
	 * @param string $sql a valid MySQL query
	 * @param int $limit number of records to return
	 * @param int $start offset of first record to return
	 * @return mixed
	 */
	public function queryF(string $sql, int $limit = 0, int $start = 0) {
		$result = FALSE;
		/* Use Protector's db layer protection against possible SQLi
		 * This needs to be done for legacy queries, since PDO only offers
		 * SQLi protection when you use bindParam and bindValue, and then
		 * use prepare() and execute() on the statement
		 */
		if (FALSE === Utility::checkSQL($sql)) {
			return $result;
		}

		if (!empty($limit)) {
			$start = !empty($start) ? (int) $start . ',' : '';
			$sql .= ' LIMIT ' . $start . (int) $limit;
		}
		try {
			$result = $this->pdo->query($sql);
			if ($result) {
				$this->rowCount = $result->rowCount();
				if ($result->columnCount() === 0) {
					return TRUE;
				}
			} else {
				$this->rowCount = 0;
			}
		} catch (Exception $e) {
			$this->rowCount = 0;
		}
		return $result;
	}

	public function getInsertId() {
		return $this->pdo->lastInsertId();
	}

	public function getAffectedRows(): int
	{
		return $this->rowCount;
	}

	public function getFieldName($result, $offset): string
	{
		if ($result) {
			$column = $result->getColumnMeta($offset);
			return $column['name'];
		} else {
			return FALSE;
		}
	}

	public function getFieldType($result, int $offset): string
	{
		if ($result) {
			$column = $result->getColumnMeta($offset);
			return $column['mysql:decl_type'];
		} else {
			return FALSE;
		}
	}

	public function getFieldsNum($result): int
	{
		if ($result) {
			return $result->columnCount();
		} else {
			return FALSE;
		}
	}

	public function fetchRow($result)
	{
		if ($result) {
			return $result->fetch(PDO::FETCH_NUM);
		} else {
			return FALSE;
		}
	}

	public function fetchArray($result) {
		if ($result) {
			return $result->fetch(PDO::FETCH_ASSOC);
		} else {
			return FALSE;
		}
	}

	public function fetchBoth($result) {
		if ($result) {
			return $result->fetch(PDO::FETCH_BOTH);
		} else {
			return FALSE;
		}
	}

	public function getRowsNum($result) {
		if ($result) {
			return $result->rowCount();
		} else {
			return 0;
		}
	}

	public function freeRecordSet($result): bool
	{
		if ($result) {
			$result->closeCursor();
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// Inseridas por Claudia fevereiro/2012, ImpressCMS.org

	/**
	 * Executa uma sql e retorna o nro.
	 * de linhas afetadas por update ou delete
	 * Executes a sql and returns the number of rows affected by update or delete
	 *
	 * This is not a legacy method and should only be implemented in the new PDO class
	 *
	 * @param string $sql
	 * @return int - nro. de linhas afetadas ou false // number of rows affected, or FALSE
	 *@todo this can be removed without breaking legacy db functionality
	 *
	 */
	public function exec(string $sql): ?int
	{

		/*
		 $row = $this->pdo->exec($sql);
		if ($row === FALSE) {
		$errorinfo = $this->pdo->errorInfo();
		icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $sql, 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
		return FALSE;
		} else {
		icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $sql));
		return $row;
		}
		*/
	}

	/**
	 * perform queries from SQL dump file in a batch
	 *
	 * @param string $file
	 *        	file path to an SQL dump file
	 *
	 * @return bool FALSE if failed reading SQL file or TRUE if the file has been read and queries executed
	 */
	public function queryFromFile(string $file): bool
	{
		if (FALSE !== ($fp = fopen($file, 'r'))) {

			$sql_queries = trim(fread($fp, filesize($file)));
			$pieces = [];
			Utility::splitSqlFile($pieces, $sql_queries);
			foreach ($pieces as $query) {
				// [0] contains the prefixed query
				// [4] contains unprefixed table name
				$prefixed_query = Utility::prefixQuery(trim($query), $this->prefix());
				if ($prefixed_query) {
					$this->query($prefixed_query[0]);
				}
			}
			return TRUE;
		}
		return FALSE;
	}

	function getConnection(): IConnection
	{
		return $this->pdo;
	}

	/**
	 * Retrieve the MySQL server version information
	 *
	 * @param IConnection|null $connection
	 *        	MySQL database connection link
	 * @return string
	 */
	public function getServerVersion(IConnection $connection = NULL): string {
		if (NULL === $connection)
			$connection = $this->pdo;
		return $connection->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
}
\class_alias(PdoDatabase::class, 'icms_db_legacy_PdoDatabase');