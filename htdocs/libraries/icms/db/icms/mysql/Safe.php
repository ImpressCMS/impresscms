<?php
// $Id: database.php 19118 2010-03-27 17:46:23Z skenow $
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Safe Connection to a MySQL database.
 *
 * @package     database
 * @subpackage  mysql
 * @since XOOPS
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
class icms_db_icms_mysql_Safe extends icms_db_icms_mysql_Database {

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