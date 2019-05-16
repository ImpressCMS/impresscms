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
 * Legacy MySQL protected query method
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package	Database
 * @subpackage	Legacy
 * @version	SVN: $Id: Proxy.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * {@link query()} method for security reasons.
 *
 * @category	ICMS
 * @package     Database
 * @subpackage  Legacy
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
class icms_db_legacy_mysql_Proxy extends icms_db_legacy_mysql_Database {

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
	public function query($sql, $limit = 0, $start = 0) {
		// Hack by marcan to track query count
		global $smartfactory_query_count_activated, $smartfactory_query_count;
		if (isset($smartfactory_query_count_activated) && $smartfactory_query_count_activated) {
			$smartfactory_query_count++;
		}
		// End of Hack by marcan to track query count
		$sql = ltrim($sql);
		if (!$this->allowWebChanges && strtolower(substr($sql, 0, 6)) != 'select')  {
			trigger_error(_CORE_DB_NOTALLOWEDINGET, E_USER_WARNING);
			return FALSE;
		}

		return $this->queryF($sql, $limit, $start);
	}
}
