<?php

/**
 * MySQLi Database Driver file.
 *
 * @package database
 * @subpackage mysqli
 * @version $Id: database.php 19118 2010-03-27 17:46:23Z skenow $
 * @since ImpressCMS 1.0
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

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
class icms_db_icms_mysqli_Safe extends icms_db_icms_mysqli_Database {

	/**
	 * perform a query on the database
	 *
	 * @param string $sql a valid MySQL query
	 * @param int $limit number of records to return
	 * @param int $start offset of first record to return
	 * @return resource query result or FALSE if successful
	 * or TRUE if successful and no result
	 */
	function query($sql, $limit = 0, $start = 0) {
		return $this->queryF($sql, $limit, $start);
	}
}