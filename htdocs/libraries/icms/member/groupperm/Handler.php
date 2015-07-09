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
 * Manage group permissions
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 *
 * @author		Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nube.com.ar>
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupPermission
 * @version		SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Group permission handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of group permission class objects.
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupPermission
 * @see			icms_member_groupperm_Object
 * @author		Kazumi Ono  <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_member_groupperm_Handler extends icms_ipf_Handler {
    
        public function __construct(&$db) {
            parent::__construct($db, 'member_groupperm', 'gperm_id', 'gperm_itemid', 'gperm_name', 'icms', 'group_permission', array('gperm_id', array('gperm_name', 'gperm_itemid', 'gperm_modid')));
        }	

	/**
	 * Delete all module specific permissions assigned for a group
	 *
	 * @param	int  $gperm_groupid ID of a group
	 * @param	int  $gperm_modid ID of a module
	 *
	 * @return	bool TRUE on success
	 */
	public function deleteByGroup($gperm_groupid, $gperm_modid = null) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_groupid', (int) ($gperm_groupid)));
		if (isset($gperm_modid)) {
			$criteria->add(new icms_db_criteria_Item('gperm_modid', (int) $gperm_modid));
		}
		return $this->deleteAll($criteria);
	}

	/**
	 * Delete all module specific permissions
	 *
	 * @param	int  $gperm_modid ID of a module
	 * @param	string  $gperm_name Name of a module permission
	 * @param	int  $gperm_itemid ID of a module item
	 *
	 * @return	bool TRUE on success
	 */
	public function deleteByModule($gperm_modid, $gperm_name = null, $gperm_itemid = null) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_modid', (int) $gperm_modid));
		if (isset($gperm_name)) {
			$criteria->add(new icms_db_criteria_Item('gperm_name', $gperm_name));
			if (isset($gperm_itemid)) {
				$criteria->add(new icms_db_criteria_Item('gperm_itemid', (int) $gperm_itemid));
			}
		}
		return $this->deleteAll($criteria);
	}
	/**#@-*/

	/**
	 * Check permission
	 *
	 * @param	string    $gperm_name       Name of permission
	 * @param	int       $gperm_itemid     ID of an item
	 * @param	int/array $gperm_groupid    A group ID or an array of group IDs
	 * @param	int       $gperm_modid      ID of a module
	 * @param	bool	  $webmasterAlwaysTrue	If true, then Webmasters will always return true, if false, a real check will be made
	 *
	 * @return	bool    TRUE if permission is enabled
	 */
	public function checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1, $webmasterAlwaysTrue=true) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_modid', $gperm_modid));
		$criteria->add(new icms_db_criteria_Item('gperm_name', $gperm_name));
		$gperm_itemid = (int) $gperm_itemid;
		if ($gperm_itemid > 0) {
			$criteria->add(new icms_db_criteria_Item('gperm_itemid', $gperm_itemid));
		}
		if (is_array($gperm_groupid)) {
			if ($webmasterAlwaysTrue && in_array(ICMS_GROUP_ADMIN, $gperm_groupid)) {
				return true;
			}
			$criteria2 = new icms_db_criteria_Compo();
			foreach ($gperm_groupid as $gid) {
				$criteria2->add(new icms_db_criteria_Item('gperm_groupid', $gid), 'OR');
			}
			$criteria->add($criteria2);
		} else {
			if ($webmasterAlwaysTrue && ICMS_GROUP_ADMIN == $gperm_groupid) {
				return true;
			}
			$criteria->add(new icms_db_criteria_Item('gperm_groupid', $gperm_groupid));
		}
		if ($this->getCount($criteria) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Add a permission
	 *
	 * @param	string  $gperm_name       Name of permission
	 * @param	int     $gperm_itemid     ID of an item
	 * @param	int     $gperm_groupid    ID of a group
	 * @param	int     $gperm_modid      ID of a module
	 *
	 * @return	bool    TRUE if success
	 */
	public function addRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1) {
		$perm =& $this->create();
		$perm->setVar('gperm_name', $gperm_name);
		$perm->setVar('gperm_groupid', $gperm_groupid);
		$perm->setVar('gperm_itemid', $gperm_itemid);
		$perm->setVar('gperm_modid', $gperm_modid);
		return $this->insert($perm);
	}

	/**
	 * Get all item IDs that a group is assigned a specific permission
	 *
	 * @param	string    $gperm_name       Name of permission
	 * @param	int/array $gperm_groupid    A group ID or an array of group IDs
	 * @param	int       $gperm_modid      ID of a module
	 *
	 * @return  array   array of item IDs
	 */
	public function getItemIds($gperm_name, $gperm_groupid, $gperm_modid = 1) {
		$ret = array();
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_name', $gperm_name));
		$criteria->add(new icms_db_criteria_Item('gperm_modid', (int) $gperm_modid));
		if (is_array($gperm_groupid)) {
			$criteria2 = new icms_db_criteria_Compo();
			foreach ($gperm_groupid as $gid) {
				$criteria2->add(new icms_db_criteria_Item('gperm_groupid', $gid), 'OR');
			}
			$criteria->add($criteria2);
		} else {
			$criteria->add(new icms_db_criteria_Item('gperm_groupid', (int) $gperm_groupid));
		}
		$perms = $this->getObjects($criteria, true);
		foreach ( array_keys($perms) as $i) {
			$ret[] = $perms[$i]->getVar('gperm_itemid');
		}
		return array_unique($ret);
	}

	/**
	 * Get all group IDs assigned a specific permission for a particular item
	 *
	 * @param	string  $gperm_name       Name of permission
	 * @param	int     $gperm_itemid     ID of an item
	 * @param	int     $gperm_modid      ID of a module
	 *
	 * @return  array   array of group IDs
	 */
	public function getGroupIds($gperm_name, $gperm_itemid, $gperm_modid = 1) {
                $criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_name', $gperm_name));
                $criteria->add(new icms_db_criteria_Item('gperm_itemid', (int) $gperm_itemid));
                $criteria->add(new icms_db_criteria_Item('gperm_modid', (int) $gperm_modid));
                $perms = $this->getObjects($criteria, true);
		foreach (array_keys($perms) as $i) {
			$ret[] = $perms[$i]->getVar('gperm_groupid');
		}
		return $ret;
	}
}
