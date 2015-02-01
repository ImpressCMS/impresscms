<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * DataBase Base class file for MySQL driver
 *
 * @deprecated	PHP is removing support for the mysql driver functions. Switch to PDO
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	Legacy
 * @version		SVN: $Id: Database.php 12434 2014-04-12 23:22:23Z skenow $
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * connection to a mysql database
 *
 * @category	ICMS
 * @package     Database
 * @subpackage	Legacy
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
abstract class icms_db_legacy_mysql_Database extends icms_db_legacy_Database {
	/**
	 * Database connection
	 * @var resource
	 */
	public $conn;

	/**
	 * connect to the database
	 *
	 * @param bool $selectdb select the database now?
	 * @return bool successful?
	 */
	public function connect($selectdb = true) {
		defined('_CORE_MYSQL_DEPRECATED') || define('_CORE_MYSQL_DEPRECATED', 'The mysql extension is being deprecated as of PHP 5.5.0 (<a href="http://php.net/mysql_connect">PHP MySQL Extenstion</a>). Switch to PDO, instead');
		icms_core_Debug::setDeprecated("PDO", _CORE_MYSQL_DEPRECATED);
		static $db_charset_set;

		$this->allowWebChanges = ($_SERVER['REQUEST_METHOD'] != 'GET');

		if (!extension_loaded('mysql')) {
			trigger_error(_CORE_DB_NOTRACE, E_USER_ERROR);
			return false;
		}

		if (XOOPS_DB_PCONNECT == 1) {
			$this->conn = @ mysql_pconnect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
		} else {
			$this->conn = @ mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
		}

		if (!$this->conn) {
			$this->logger->addQuery('', $this->error(), $this->errno());
			return false;
		}
		if ($selectdb != false) {
			if (!mysql_select_db(XOOPS_DB_NAME)) {
				$this->logger->addQuery('', $this->error(), $this->errno());
				return false;
			}
		}

		if (!isset ($db_charset_set) && defined('XOOPS_DB_CHARSET') && XOOPS_DB_CHARSET && XOOPS_DB_CHARSET !== 'ucs2') {
			$this->queryF("SET NAMES '" . XOOPS_DB_CHARSET . "'");
		}
		$db_charset_set = 1;

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
	public function genId($sequence) {
		return 0; // will use auto_increment
	}

	/**
	 * Get a result row as an enumerated array
	 *
	 * @param resource $result
	 * @return array the fetched rows
	 */
	public function fetchRow($result) {
		return @ mysql_fetch_row($result);
	}

	/**
	 * Fetch a result row as an associative array
	 *
	 * @return array the fetched associative array
	 */
	public function fetchArray($result) {
		return @ mysql_fetch_assoc($result);
	}

	/**
	 * Fetch a result row as an associative array and numerical array
	 *
	 * @return array the associative and numerical array
	 */
	public function fetchBoth($result) {
		return @ mysql_fetch_array($result, MYSQL_BOTH);
	}

	/**
	 * Get the ID generated from the previous INSERT operation
	 *
	 * @return int
	 */
	public function getInsertId() {
		return mysql_insert_id($this->conn);
	}

	/**
	 * Get number of rows in result
	 *
	 * @param resource query result
	 * @return int the number of rows in the resultset
	 */
	public function getRowsNum($result) {
		return @ mysql_num_rows($result);
	}

	/**
	 * Get number of affected rows
	 *
	 * @return int number of affected rows
	 */
	public function getAffectedRows() {
		return mysql_affected_rows($this->conn);
	}

	/**
	 * Closes MySQL connection
	 *
	 */
	public function close() {
		mysql_close($this->conn);
	}

	/**
	 * will free all memory associated with the result identifier result.
	 *
	 * @param resource query result
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function freeRecordSet($result) {
		return mysql_free_result($result);
	}

	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
	 * @return string Returns the error text from the last MySQL function, or '' (the empty string) if no error occurred.
	 */
	public function error() {
		return @ mysql_error();
	}

	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 *
	 * @return int Returns the error number from the last MySQL function, or 0 (zero) if no error occurred.
	 */
	public function errno() {
		return @ mysql_errno();
	}

	/**
	 * Returns escaped string text with single quotes around it to be safely stored in database
	 *
	 * @param string $str unescaped string text
	 * @return string escaped string text with single quotes around
	 */
	public function quoteString($str) {
		return $this->quote($str);
		$str = "'" . str_replace('\\"', '"', addslashes($str)) . "'";
		return $str;
	}

	/**
	 * Quotes a string for use in a query using mysql_real_escape_string.
	 *
	 * @param string $str unescaped string text
	 * @return string escaped string text using mysql_real_escape_string
	 */
	public function quote($string) {
		return "'" . mysql_real_escape_string($string, $this->conn) . "'";
	}
	public function escape($string) {
		return mysql_real_escape_string($string, $this->conn);
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
	public function queryF($sql, $limit = 0, $start = 0) {
		if (!empty ($limit)) {
			if (empty ($start)) {
				$start = 0;
			}
			$sql = $sql . ' LIMIT ' . (int) $start . ', ' . (int) $limit;
		}
		$result = mysql_query($sql, $this->conn);
		if ($result) {
			$this->logger->addQuery($sql);
			return $result;
		} else {
			$this->logger->addQuery($sql, $this->error(), $this->errno());
			return false;
		}
	}

	/**
	 * perform queries from SQL dump file in a batch
	 *
	 * @param string $file file path to an SQL dump file
	 *
	 * @return bool FALSE if failed reading SQL file or TRUE if the file has been read and queries executed
	 */
	public function queryFromFile($file) {
		if (false !== ($fp = fopen($file, 'r'))) {

			$sql_queries = trim(fread($fp, filesize($file)));
			icms_db_legacy_mysql_Utility::splitSqlFile($pieces, $sql_queries);
			foreach ($pieces as $query) {
				// [0] contains the prefixed query
				// [4] contains unprefixed table name
				$prefixed_query = icms_db_legacy_mysql_Utility::prefixQuery(trim($query), $this->prefix());
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
	 * @return string the fieldname
	 */
	public function getFieldName($result, $offset) {
		return mysql_field_name($result, $offset);
	}

	/**
	 * Get field type
	 *
	 * @param resource $result query result
	 * @param int $offset numerical field index
	 * @return string the fieldtype
	 */
	public function getFieldType($result, $offset) {
		return mysql_field_type($result, $offset);
	}

	/**
	 * Get number of fields in result
	 *
	 * @param resource $result query result
	 * @return int number of fields in the resultset
	 */
	public function getFieldsNum($result) {
		return mysql_num_fields($result);
	}
	
	/**
	 * Retrieve the MySQL server version information
	 *
	 * @param obj $connecton	A MySQL database connection link
	 * @return string
	 */
	public function getServerVersion($connection = NULL) {
		if (NULL === $connection) $connection = $this->conn;
		return mysql_get_server_info($connection);
	}
}
