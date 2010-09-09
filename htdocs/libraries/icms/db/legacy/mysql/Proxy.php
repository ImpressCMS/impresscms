<?php
// $Id: database.php 19118 2010-03-27 17:46:23Z skenow $
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * {@link query()} method for security reasons.
 *
 * @package     database
 * @subpackage  mysql
 * @since XOOPS
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
class icms_db_legacy_mysql_Proxy extends icms_db_legacy_mysql_Database
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
		// Hack by marcan to track query count
		global $smartfactory_query_count_activated, $smartfactory_query_count;
		if (isset($smartfactory_query_count_activated) && $smartfactory_query_count_activated) {
			$smartfactory_query_count++;
		}
		// End of Hack by marcan to track query count
		$sql = ltrim($sql);
		if ( !$this->allowWebChanges && strtolower( substr($sql, 0, 6) ) != 'select' )  {
			trigger_error( _CORE_DB_NOTALLOWEDINGET, E_USER_WARNING );
			return false;
		}

		return $this->queryF($sql, $limit, $start);
	}
}