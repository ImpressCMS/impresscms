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
class icms_db_icms_mysqli_Proxy extends icms_db_icms_mysqli_Database {

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
	function query($sql, $limit = 0, $start = 0) {
		// Hack by marcan to track query count
		global $smartfactory_query_count_activated, $smartfactory_query_count;
		if (isset ($smartfactory_query_count_activated) && $smartfactory_query_count_activated) {
			$smartfactory_query_count++;
		}
		// End of Hack by marcan to track query count
		$sql = ltrim($sql);
		if (!$this->allowWebChanges && strtolower(substr($sql, 0, 6)) != 'select') {
			trigger_error(_CORE_DB_NOTALLOWEDINGET, E_USER_WARNING);
			return false;
		}

		return $this->queryF($sql, $limit, $start);
	}
}