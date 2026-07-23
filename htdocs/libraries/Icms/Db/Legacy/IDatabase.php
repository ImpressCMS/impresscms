<?php
/**
 * Standard interface for database classes
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category    ICMS
 * @package	    Database
 */

/**
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @category    ICMS
 * @package	    Database
 */
declare(strict_types=1);
namespace Icms\Db\Legacy;

interface IDatabase {
	/**
	 * assign a {@link icms_core_Logger} object to the database
	 *
	 * @see icms_core_Logger
	 * @param object $logger reference to a {@link icms_core_Logger} object
	 */
	public function setLogger($logger);
	/**
	 * set the prefix for tables in the database
	 *
	 * @param string $value table prefix
	 */
	public function setPrefix(string $value);
	/**
	 * attach the prefix.'_' to a given tablename.
	 *
	 * if tablename is empty, only prefix will be returned
	 *
	 * @param string $tablename tablename
	 * @return string prefixed tablename, just prefix if tablename is empty
	 */
	public function prefix(string $tablename=''): string;
	/**
	 * connect to the database
	 *
	 * @param bool $selectdb select the database now?
	 * @return bool successful?
	 */
	public function connect(bool $selectdb = true): bool;
	/**
	 * generate an ID for a new row
	 *
	 * This is for compatibility only. Will always return 0, because MySQL supports
	 * autoincrement for primary keys.
	 *
	 * @param string $sequence name of the sequence from which to get the next ID
	 * @return int always 0, because mysql has support for autoincrement
	 */
	public function genId(string $sequence): int;
	/**
	 * Get a result row as an enumerated array
	 *
	 * @param resource $result
	 * @return array the fetched rows, false when no row is available
	 */
	public function fetchRow($result);
	/**
	 * Fetch a result row as an associative array
	 *
	 * @param resource $result
	 * @return array the fetched associative array, false when no row is available
	 */
	public function fetchArray($result);
	/**
	 * Fetch a result row as an associative array and numerical array
	 *
	 * @param resource $result
	 * @return array the associative and numerical array, false when no row is available
	 */
	public function fetchBoth($result);
	/**
	 * Get the ID generated from the previous INSERT operation
	 *
	 * @return int
	 */
	public function getInsertId();
	/**
	 * Get number of rows in result
	 *
	 * @param resource query result
	 * @return int the number of rows in the resultset
	 */
	public function getRowsNum($result);
	/**
	 * Get number of affected rows
	 *
	 * @return int number of affected rows
	 */
	public function getAffectedRows(): int;
	/**
	 * Closes MySQL connection
	 *
	 */
	public function close();
	/**
	 * will free all memory associated with the result identifier result.
	 *
	 * @param resource query result
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function freeRecordSet($result): bool;
	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
	 * @return string Returns the error text from the last MySQL function, or '' (the empty string) if no error occurred.
	 */
	public function error(): string;
	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 *
	 * @return int Returns the error number from the last MySQL function, or 0 (zero) if no error occurred.
	 */
	public function errno(): int;
	/**
	 * Returns escaped string text with single quotes around it to be safely stored in database
	 *
	 * @param string $str unescaped string text
	 * @return string escaped string text with single quotes around
	 */
	public function quoteString($str): string;
	/**
	 * Quotes a string for use in a query using mysql_real_escape_string.
	 *
	 * @param string $str unescaped string text
	 * @return string escaped string text using mysql_real_escape_string
	 */
	public function quote($string): string;
	/**
	 * perform a query on the database
	 *
	 * @param string $sql a valid MySQL query
	 * @param int $limit number of records to return
	 * @param int $start offset of first record to return
	 * @return mixed query result object/resource for result-set queries, TRUE if successful and no result-set, or FALSE if unsuccessful
	 */
	public function queryF(string $sql, int $limit = 0, int $start = 0);
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
	 */
	public function query(string $sql, int $limit = 0, int $start = 0);
	/**
	 * Get field name
	 *
	 * @param resource $result query result
	 * @param int numerical field index
	 * @return string the fieldname
	 */
	public function getFieldName($result, $offset): string;
	/**
	 * Get field type
	 *
	 * @param resource $result query result
	 * @param int $offset numerical field index
	 * @return string the fieldtype
	 */
	public function getFieldType($result, int $offset): string;
	/**
	 * Get number of fields in result
	 *
	 * @param resource $result query result
	 * @return int number of fields in the resultset
	 */
	public function getFieldsNum($result): int;

}
\class_alias(IDatabase::class, 'icms_db_legacy_IDatabase');