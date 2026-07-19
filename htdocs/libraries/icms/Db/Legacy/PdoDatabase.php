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

/**
 * Create and interact with a database connection using PDO
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @category ICMS
 * @package Database
 * @subpackage PDO
 */
class icms_db_legacy_PdoDatabase extends icms_db_legacy_Database implements icms_db_legacy_IDatabase {

	/**
	 * The PDO connection that performs operations behind the scenes
	 *
	 * @var icms_db_IConnection
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
	protected $rowCount = 0;

	public function __construct($connection, $allowWebChanges = FALSE) {
		parent::__construct($connection, $allowWebChanges);
		$this->pdo = $connection;
		$this->conn = & $this->pdo; // only for legacy support
	}

	public function connect($selectdb = TRUE) {
		return TRUE;
	}

	public function close() {
		$this->pdo = NULL;
		return TRUE;
	}

	public function quoteString($string) {
		return $this->pdo->quote($string);
	}

	public function quote($string) {
		return $this->pdo->quote($string);
	}

	public function escape($string) {
		return $this->pdo->escape($string);
	}

	public function error() {
		$error = $this->pdo->errorInfo();
		return $error [2];
	}

	public function errno() {
		$error = $this->pdo->errorInfo ();
		return $error [1];
	}

	public function genId($sequence) {
		return 0; // will use auto_increment
	}

	public function query($sql, $limit = 0, $start = 0) {
		if (! $this->allowWebChanges && strtolower(substr(trim($sql), 0, 6)) != 'select') {
			trigger_error(_CORE_DB_NOTALLOWEDINGET, E_USER_WARNING);
			return FALSE;
		}
		return $this->queryF($sql, $limit, $start);
	}

	public function queryF($sql, $limit = 0, $start = 0) {
		$result = FALSE;
		/* Use Protector's db layer protection against possible SQLi
		 * This needs to be done for legacy queries, since PDO only offers
		 * SQLi protection when you use bindParam and bindValue, and then
		 * use prepare() and execute() on the statement
		 */
		if (FALSE === icms_db_legacy_mysql_Utility::checkSQL($sql)) {
			return $result;
		}
		
		if (!empty($limit)) {
			$start = !empty($start) ? (int) $start . ',' : '';
			$sql .= ' LIMIT ' . $start . (int) $limit;
		}
		try {
			$result = $this->pdo->query($sql);
			if ($result) { // added by claudia, ImpressCMS.org
				$this->rowCount = $result->rowCount();
			} else { // added by claudia, ImpressCMS.org
				$this->rowCount = FALSE; // added by claudia, ImpressCMS.org
			} // added by claudia, ImpressCMS.org
		} catch (Exception $e) {
		}
		return $result;
	}

	public function getInsertId() {
		return $this->pdo->lastInsertId();
	}

	public function getAffectedRows() {
		return $this->rowCount;
	}

	public function getFieldName($result, $offset) {
		if ($result) {
			$column = $result->getColumnMeta($offset);
			return $column['name'];
		} else {
			return FALSE;
		}
	}

	public function getFieldType($result, $offset) {
		if ($result) {
			$column = $result->getColumnMeta($offset);
			return $column['mysql:decl_type'];
		} else {
			return FALSE;
		}
	}

	public function getFieldsNum($result) {
		if ($result) {
			return $result->columnCount();
		} else {
			return FALSE;
		}
	}

	public function fetchRow($result) {
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
			return FALSE;
		}
	}

	public function freeRecordSet($result) {
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
	 * @todo this can be removed without breaking legacy db functionality
	 *
	 * @param string $sql
	 * @return int - nro. de linhas afetadas ou false // number of rows affected, or FALSE
	 */
	function exec($sql) {
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
		*/	}
	
	/**
	 * Fetch a result row as an object
	 *
	 * This is not a legacy method and should only be implemented in the new PDO class
	 *
	 * @todo this can be removed without breaking legacy db functionality
	 *
	 * @param resource $result
	 * @param string $class
	 *        	O nome de classe para instanciar, definir as propriedades e retornar. Se n�o for especificado, um objeto stdClass � retornado.
	 *        	The name of the class to instantiate, set the properties and return. If none is specified, a stdClass object returned.
	 * @param array $params
	 *        	Um array opcional de par�metros para passar para o construtor do objeto class_name .
	 *        	An optional array of parameters to pass to the constructor for class_name objects.
	 * @return object Inserida por Claudia // added by Claudia (ImpressCMS)
	 */
	function fetchObject($result, $class = 'stdClass', $params = array()) {
		/*
		 if ($result) {
		return $result->fetchObject($class, $params);
		} else {
		return FALSE;
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
	public function queryFromFile($file) {
		if (FALSE !== ($fp = fopen($file, 'r'))) {
			
			$sql_queries = trim(fread($fp, filesize($file)));
			icms_db_legacy_mysql_Utility::splitSqlFile($pieces, $sql_queries);
			foreach ($pieces as $query) {
				// [0] contains the prefixed query
				// [4] contains unprefixed table name
				$prefixed_query = icms_db_mysql_Utility::prefixQuery(trim($query), $this->prefix());
				if ($prefixed_query != FALSE) {
					$this->query($prefixed_query[0]);
				}
			}
			return TRUE;
		}
		return FALSE;
	}

	function getConnection() {
		return $this->pdo;
	}
	
	/**
	 * Retrieve the MySQL server version information
	 *
	 * @param obj $connection
	 *        	MySQL database connection link
	 * @return mixed
	 */
	public function getServerVersion($connection = NULL) {
		if (NULL === $connection)
			$connection = $this->pdo;
		return $connection->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
}
