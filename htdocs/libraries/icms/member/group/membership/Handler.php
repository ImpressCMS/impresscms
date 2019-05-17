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
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupMembership
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Group membership handler class. (Singleton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of group membership class objects.
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupMembership
 */
class icms_member_group_membership_Handler extends icms_core_ObjectHandler {
	/**
	 * create a new membership
	 *
	 * @param bool $isNew should the new object be set to "new"?
	 * @return object icms_member_group_membership_Object {@link icms_member_group_membership_Object}
	 * @see icms_core_ObjectHandler#create()
	 */
	public function &create($isNew = true) 	{
		$mship = new icms_member_group_membership_Object();
		if ($isNew) {
			$mship->setNew();
		}
		return $mship;
	}

	/**
	 * retrieve a membership
	 *
	 * @param int $id ID of the membership to get
	 * @return mixed reference to the object if successful, else FALSE
	 * @see icms_core_ObjectHandler#get($int_id)
	 */
	public function &get($id) {
		$id = (int) $id;
		$mship = false;
		if ($id > 0) {
			$sql = "SELECT * FROM " . icms::$xoopsDB->prefix('groups_users_link')
				. " WHERE linkid='" . $id . "'";
			if (!$result = icms::$xoopsDB->query($sql)) {
				return $mship;
			}
			$numrows = icms::$xoopsDB->getRowsNum($result);
			if ($numrows == 1) {
				$mship = new icms_member_group_membership_Object();
				$mship->assignVars(icms::$xoopsDB->fetchArray($result));
			}
		}
		return $mship;
	}

	/**
	 * inserts a membership in the database
	 *
	 * @param object $mship reference to the membership object
	 * @return bool TRUE if already in DB or successful, FALSE if failed
	 * @see icms_core_ObjectHandler#insert($object)
	 */
	public function insert(&$mship) {
		/* As of PHP5.3.0, is_a()is no longer deprecated and there is no need to replace it */
		if (!is_a($mship, 'icms_member_group_membership_Object')) {
			return false;
		}
		if (!$mship->isDirty()) {
			return true;
		}
		if (!$mship->cleanVars()) {
			return false;
		}
		foreach ( $mship->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if ($mship->isNew()) {
			$linkid = icms::$xoopsDB->genId('groups_users_link_linkid_seq');
			$sql = sprintf(
				"INSERT INTO %s (linkid, groupid, uid) VALUES ('%u', '%u', '%u')",
				icms::$xoopsDB->prefix('groups_users_link'),
				(int) $linkid,
				(int) $groupid,
				(int) $uid
			);
		} else {
			$sql = sprintf(
				"UPDATE %s SET groupid = '%u', uid = '%u' WHERE linkid = '%u'",
				icms::$xoopsDB->prefix('groups_users_link'),
				(int) $groupid,
				(int) $uid,
				(int) $linkid
			);
		}
		if (!$result = icms::$xoopsDB->query($sql)) {
			return false;
		}
		if (empty($linkid)) {
			$linkid = icms::$xoopsDB->getInsertId();
		}
		$mship->assignVar('linkid', $linkid);
		return true;
	}

	/**
	 * delete a membership from the database
	 *
	 * @param object $mship reference to the membership object
	 * @return bool FALSE if failed
	 * @see icms_core_ObjectHandler#delete($object)
	 */
	public function delete(&$mship) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no reason to replace it */
		if (!is_a($mship, 'icms_member_group_membership_Object')) {
			return false;
		}

		$sql = sprintf(
			"DELETE FROM %s WHERE linkid = '%u'",
			icms::$xoopsDB->prefix('groups_users_link'),
			(int) $groupm->getVar('linkid')
		);
		if (!$result = icms::$xoopsDB->query($sql)) {
			return false;
		}
		return true;
	}

	/**
	 * retrieve memberships from the database
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} conditions to meet
	 * @param bool $id_as_key should the ID be used as the array's key?
	 * @return array array of references
	 */
	public function getObjects($criteria = null, $id_as_key = false) {
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM " . icms::$xoopsDB->prefix('groups_users_link');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = icms::$xoopsDB->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = icms::$xoopsDB->fetchArray($result)) {
			$mship = new icms_member_group_membership_Object();
			$mship->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $mship;
			} else {
				$ret[$myrow['linkid']] =& $mship;
			}
			unset($mship);
		}
		return $ret;
	}

	/**
	 * count how many memberships meet the conditions
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} conditions to meet
	 * @return int
	 */
	public function getCount($criteria = null) {
		$sql = "SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix('groups_users_link');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
		}
		$result = icms::$xoopsDB->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = icms::$xoopsDB->fetchRow($result);
		return $count;
	}

	/**
	 * delete all memberships meeting the conditions
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} with conditions to meet
	 * @return bool
	 */
	public function deleteAll($criteria = null) {
		$sql = "DELETE FROM " . icms::$xoopsDB->prefix('groups_users_link');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
		}
		if (!$result = icms::$xoopsDB->query($sql)) {
			return false;
		}
		return true;
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

