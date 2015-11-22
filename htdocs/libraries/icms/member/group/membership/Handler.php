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
 * Manage memberships
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	Kazumi Ono (aka onokazo)
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Group membership handler class. (Singleton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of group membership class objects.
 *
 * @author      Kazumi Ono <onokazu@xoops.org> 
 * @package	ICMS\Member\Group\Membership
 */
class icms_member_group_membership_Handler extends icms_ipf_Handler {
    
        public function __construct(&$db) {
            parent::__construct($db, 'member_group_membership', 'linkid', 'groupid', 'uid', 'icms', 'groups_users_link', 'linkid');
        }	    

	/**
	 * retrieve groups for a user
	 *
	 * @param int $uid ID of the user
	 * @param bool $asobject should the groups be returned as {@link icms_member_group_Object}
	 * objects? FALSE returns associative array.
	 * @return array array of groups the user belongs to
	 */
	public function getGroupsByUser($uid) {
		$ret = array();
		$sql = "SELECT groupid FROM " . icms::$xoopsDB->prefix('groups_users_link')
			. " WHERE uid='" . (int) $uid . "'";
		$result = icms::$xoopsDB->query($sql);
		if (!$result) {
			return $ret;
		}
		while ($myrow = icms::$xoopsDB->fetchArray($result)) {
			$ret[] = $myrow['groupid'];
		}
		return $ret;
	}

	/**
	 * retrieve users belonging to a group
	 *
	 * @param int $groupid ID of the group
	 * @param bool $asobject return users as {@link icms_user_Object} objects?
	 * FALSE will return arrays
	 * @param int $limit number of entries to return
	 * @param int $start offset of first entry to return
	 * @return array array of users belonging to the group
	 */
	public function getUsersByGroup($groupid, $limit=0, $start=0) {
		$ret = array();
		$sql = "SELECT uid FROM " . icms::$xoopsDB->prefix('groups_users_link')
			. " WHERE groupid='" . (int) $groupid . "'";
		$result = icms::$xoopsDB->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = icms::$xoopsDB->fetchArray($result)) {
			$ret[] = $myrow['uid'];
		}
		return $ret;
	}
}

