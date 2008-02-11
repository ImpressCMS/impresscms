<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
/**
 * @package     database
 * @subpackage  mysqli
 * @version $Id $
 * @since ImpressCMS 1.0
 *
 * @author	    Gustavo Pilla <nekro@impresscms.org>
 * @copyright	copyright (c) 2008, ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

/**
 * base class
 */
include_once XOOPS_ROOT_PATH."/class/database/database.php";

/**
 * Connection to a MySQL database
 *
 * @abstract
 *
 * @package     database
 * @subpackage  mysqli
 * @since ImpressCMS 1.0
 *
 * @author      Gustavo Pilla  <nekro@impresscms.org>
 * @copyright   copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabase extends XoopsDatabase
{
	/**
	 * Database connection
	 * @var resource
	 */
	var $conn;

	/**
	 * connect to the database
	 *
     * @param bool $selectdb select the database now?
     * @return bool successful?
	 */
	function connect($selectdb = true)
	{
		if ( !extension_loaded( 'mysql' ) ) {
			trigger_error( 'notrace:mysql extension not loaded', E_USER_ERROR );
			return false;
		}
		if (XOOPS_DB_PCONNECT == 1) {
			//$this->conn = @mysql_pconnect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
			$this->conn = new mysqli( XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS );
		} else {
			//$this->conn = @mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
			$this->conn = new mysqli( XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS );
		}

		if (mysqli_connect_errno()) {
			$this->logger->addQuery('', $this->error(), $this->errno());
			return false;
		}
		if($selectdb != false){
			if (!$this->conn->select_db(XOOPS_DB_NAME)) {

				$this->logger->addQuery('', $this->error(), $this->errno());
				return false;
			}
		}
		return true;
	}

	/**
	 * generate an ID for a new row
     *
     * This is for compatibility only. Will always return 0, because MySQL supports
     * autoincrement for primary keys.
     *
     * @param string $sequence name of the sequence from which to get the next ID
     * @return int always 0, because mysql has support for autoincrement
	 */
	function genId($sequence)
	{
		return 0; // will use auto_increment
	}

	/**
	 * Get a result row as an enumerated array
	 *
     * @param resource $result
     * @return array
	 */
	function fetchRow($result)
	{
		//return @mysql_fetch_row($result);
		return @$result->fetch_row();
	}

	/**
	 * Fetch a result row as an associative array
	 *
     * @return array
	 */
	function fetchArray($result)
    {
        //return @mysql_fetch_assoc( $result );
        return @$result->fetch_assoc();
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    function fetchBoth($result)
    {
        //return @mysql_fetch_array( $result, MYSQL_BOTH );
        return @$result->fetch_array( MYSQLI_BOTH );
    }

	/**
	 * Get the ID generated from the previous INSERT operation
	 *
     * @return int
	 */
	function getInsertId()
	{
		//return mysql_insert_id($this->conn);
		return $this->conn->insert_id;
	}

	/**
	 * Get number of rows in result
	 *
     * @param resource query result
     * @return int
	 */
	function getRowsNum($result)
	{
		//return @mysql_num_rows($result);
		return $result->num_rows;
	}

	/**
	 * Get number of affected rows
	 *
     * @return int
	 */
	function getAffectedRows()
	{
		//return mysql_affected_rows($this->conn);
		return $this->conn->affected_rows;
	}

	/**
	 * Close MySQL connection
	 *
	 */
	function close()
	{
		//mysql_close($this->conn);
		$this->conn->close();
	}

	/**
	 * will free all memory associated with the result identifier result.
	 *
     * @param resource query result
     * @return bool TRUE on success or FALSE on failure.
	 */
	function freeRecordSet($result)
	{
		return mysqli_free_result($result);
	}

	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
     * @return bool Returns the error text from the last MySQL function, or '' (the empty string) if no error occurred.
	 */
	function error()
	{
		return @$this->conn->error;
	}

	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 *
     * @return int Returns the error number from the last MySQL function, or 0 (zero) if no error occurred.
	 */
	function errno()
	{
		return @$this->conn->errno;
	}

    /**
     * Returns escaped string text with single quotes around it to be safely stored in database
     *
     * @param string $str unescaped string text
     * @return string escaped string text with single quotes around
     */
    function quoteString($str)
    {
         $str = "'".str_replace('\\"', '"', addslashes($str))."'";
         return $str;
    }

    /**
     * perform a query on the database
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if successful
     * or TRUE if successful and no result
     */
    function queryF($sql, $limit=0, $start=0)
	{
		if ( !empty($limit) ) {
			if (empty($start)) {
				$start = 0;
			}
			$sql = $sql. ' LIMIT '.(int)$start.', '.(int)$limit;
		}
		$result = $this->conn->query($sql);
		if ( $result ) {
			$this->logger->addQuery($sql);
			return $result;
        } else {
			$this->logger->addQuery($sql, $this->error(), $this->errno());
			return false;
        }
    }

	/**
	 * perform a query
     *
     * This method is empty and does nothing! It should therefore only be
     * used if nothing is exactly what you want done! ;-)
	 *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     *
     * @abstract
	 */
	function query($sql, $limit=0, $start=0)
	{

    }

    /**
	 * perform queries from SQL dump file in a batch
	 *
     * @param string $file file path to an SQL dump file
     *
     * @return bool FALSE if failed reading SQL file or TRUE if the file has been read and queries executed
	 */
	function queryFromFile($file){
        if (false !== ($fp = fopen($file, 'r'))) {
			include_once XOOPS_ROOT_PATH.'/class/database/drivers/'.XOOPS_DB_TYPE.'/sqlutility.php';
            $sql_queries = trim(fread($fp, filesize($file)));
            SqlUtility::splitMySqlFile($pieces, $sql_queries);
            foreach ($pieces as $query) {
                // [0] contains the prefixed query
                // [4] contains unprefixed table name
                $prefixed_query = SqlUtility::prefixQuery(trim($query), $this->prefix());
                if ($prefixed_query != false) {
                    $this->query($prefixed_query[0]);
                }
            }
            return true;
        }
        return false;
    }

    /**
	 * Get field name
	 *
     * @param resource $result query result
     * @param int numerical field index
     * @return string
	 */
	function getFieldName($result, $offset)
	{
		//return mysql_field_name($result, $offset);
		$field = $result->fetch_field();
		return $field->name;
	}

	/**
	 * Get field type
	 *
     * @param resource $result query result
     * @param int $offset numerical field index
     * @return string
	 */
    function getFieldType($result, $offset)
	{
		$field = $result->fetch_field();
		return $field->type;
	}

	/**
	 * Get number of fields in result
	 *
     * @param resource $result query result
     * @return int
	 */
	function getFieldsNum($result)
	{
		return $result->field_count;
	}
}

/**
 *	Safe Connection to a MySQL database.
 *
 * @package database
 * @subpackage mysqli
 * @since ImpressCMS 1.0
 *
 * @author Gustavo Alejandro Pilla <nekro@impresscms.org>
 * @copyright copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabaseSafe extends XoopsMySQLiDatabase
{

    /**
     * perform a query on the database
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if successful
     * or TRUE if successful and no result
     */
	function query($sql, $limit=0, $start=0)
	{
		return $this->queryF($sql, $limit, $start);
	}
}

/**
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * {@link query()} method for security reasons.
 *
 * @package database
 * @subpackage mysqli
 * @since ImpressCMS 1.0
 *
 * @author Gustavo Alejandro Pilla <nekro@impresscms.org>
 * @copyright copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabaseProxy extends XoopsMySQLiDatabase
{

    /**
     * perform a query on the database
     *
     * this method allows only SELECT queries for safety.
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if unsuccessful
     */
	function query($sql, $limit=0, $start=0)
	{
	    $sql = ltrim($sql);
		if (strtolower(substr($sql, 0, 6)) == 'select') {
		//if (preg_match("/^SELECT.*/i", $sql)) {
			return $this->queryF($sql, $limit, $start);
		}
		// TODO: Hardcoded language
		$this->logger->addQuery($sql, 'Database update not allowed during processing of a GET request', 0);
		return false;
	}
}
?>