<?php
// $Id: groups.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Administration of usergroups, functionfile
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Groups
 */

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

/**
 * Diplay groups and options/permissions
 */
function displayGroups() {
	global $icmsAdminTpl;
	icms_cp_header();
	$member_handler = icms::handler('icms_member');
	$groups =& $member_handler->getGroups();
	$count = count($groups);
	$gperm_handler = icms::handler('icms_member_groupperm');
	$ugroups  = (is_object(icms::$user)) ? icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
	for ($i = 0; $i < $count; $i++) {
		$id = $groups[$i]->getVar('groupid');
		if ($gperm_handler->checkRight('group_manager', $id, $ugroups)) {
			if (ICMS_GROUP_ADMIN == $id || ICMS_GROUP_USERS == $id || ICMS_GROUP_ANONYMOUS == $id) {
				$grouparray[$i]['permissions'] = false;
			} else {
				$grouparray[$i]['permissions'] = true;
			}
		}
		$grouparray[$i]['name'] =  $groups[$i]->getVar('name');
		$grouparray[$i]['description'] =  $groups[$i]->getVar('description');
		$grouparray[$i]['id'] = (int) $id;
		$icmsAdminTpl->assign("grouparray", $grouparray);
	}
	$name_value = "";
	$desc_value = "";
	$s_cat_value = '';
	$a_mod_value = array();
	$r_mod_value = array();
	$ed_mod_value = array();
	$group_manager_value = array();
	$debug_mod_value = array();
	$r_block_value = array();
	$op_value = "add";
	$submit_value = _AM_CREATENEWADG;
	$g_id_value = "";
	$type_value = "";
	$form_title = _AM_CREATENEWADG;
	$icmsAdminTpl->assign("grouprights", "1");
	$icmsAdminTpl->assign("displaygroups", "1");
	$icmsAdminTpl->display("db:admin/groups/system_adm_groups.html");
	include ICMS_MODULES_PATH . "/system/admin/groups/groupform.php";
	icms_cp_footer();
}

/**
 * Modify settings for a group
 * @param int $g_id	Unique group ID
 */
function modifyGroup($g_id) {
	global $icmsAdminTpl;
	$userstart = $memstart = 0;
	if (!empty($_POST['userstart'])) {
		$userstart = (int) $_POST['userstart'];
	} elseif (!empty($_GET['userstart'])) {
		$userstart = (int) $_GET['userstart'];
	}
	if (!empty($_POST['memstart'])) {
		$memstart = (int) $_POST['memstart'];
	} elseif (!empty($_GET['memstart'])) {
		$memstart = (int) $_GET['memstart'];
	}
	icms_cp_header();
	echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/groups/images/groups_big.png)"><a href="admin.php?fct=groups">'. _AM_GROUPSMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'. _AM_MODIFYADG . '</div><br />';

	$member_handler = icms::handler('icms_member');
	$thisgroup =& $member_handler->getGroup($g_id);
	$name_value = $thisgroup->getVar("name", "E");
	$desc_value = $thisgroup->getVar("description", "E");

	$moduleperm_handler = icms::handler('icms_member_groupperm');
	$a_mod_value = $moduleperm_handler->getItemIds('module_admin', $thisgroup->getVar('groupid'));
	$r_mod_value = $moduleperm_handler->getItemIds('module_read', $thisgroup->getVar('groupid'));
	$ed_mod_value = $moduleperm_handler->getItemIds('use_wysiwygeditor', $thisgroup->getVar('groupid'));
	$debug_mod_value = $moduleperm_handler->getItemIds('enable_debug', $thisgroup->getVar('groupid'));
	$group_manager_value = $moduleperm_handler->getItemIds('group_manager', $thisgroup->getVar('groupid'));

	$gperm_handler = icms::handler('icms_member_groupperm');
	$r_block_value = $gperm_handler->getItemIds('block_read', $g_id);
	$op_value = "update";
	$submit_value = _AM_UPDATEADG;
	$g_id_value = $thisgroup->getVar("groupid");
	$type_value = $thisgroup->getVar("group_type", "E");
	$form_title = _AM_MODIFYADG;
	if (ICMS_GROUP_ADMIN == $g_id) {
		$s_cat_disable = TRUE;
	}

	$s_cat_value = $gperm_handler->getItemIds('system_admin', $g_id);

	include ICMS_MODULES_PATH . "/system/admin/groups/groupform.php";
	$usercount = $member_handler->getUserCount(new icms_db_criteria_Item('level', 0, '>'));
	$membercount = $member_handler->getUserCountByGroup($g_id);
	if ($usercount < 200 && $membercount < 200) {
		$icmsAdminTpl->assign("usersless200", "1");
		$icmsAdminTpl->assign("groupid", $thisgroup->getVar("groupid"));
		// do the old way only when counts are small
		$mlist = array();
		$members =& $member_handler->getUsersByGroup($g_id, FALSE);
		if (count($members) > 0) {
			$member_criteria = new icms_db_criteria_Item('uid', "(" . implode(',', $members) . ")", "IN");
			$member_criteria->setSort('uname');
			$mlist = $member_handler->getUserList($member_criteria);
		}
		$criteria = new icms_db_criteria_Item('level', 0, '>');
		$criteria->setSort('uname');
		$userslist = $member_handler->getUserList($criteria);
		$users = array_diff($userslist, $mlist);
				  foreach ($users as $u_id => $u_name) {
					$usersarray[$u_id]['name'] =  $u_name;
					$usersarray[$u_id]['id'] = (int) $u_id;
					$icmsAdminTpl->assign("usersarray", $usersarray);
				  }
				foreach ($mlist as $m_id => $m_name) {
					$multiple[$m_id]['name'] =  $m_name;
					$multiple[$m_id]['id'] = (int) $m_id ;
					$icmsAdminTpl->assign("multiple", $multiple);
				}
		} else {
			$members =& $member_handler->getUsersByGroup($g_id, FALSE, 200, $memstart);
			$mlist = array();
			if (count($members) > 0) {
				$member_criteria = new icms_db_criteria_Item('uid', "(" . implode(',', $members) . ")", "IN");
				$member_criteria->setSort('uname');
				$mlist = $member_handler->getUserList($member_criteria);
			}
			$nav = new icms_view_PageNav($membercount, 200, $memstart, "memstart", "fct=groups&amp;op=modify&amp;g_id=" . (int) $g_id);
			foreach ($mlist as $m_id => $m_name) {
				$multiple[$m_id]['name'] =  $m_name;
				$multiple[$m_id]['id'] = (int) $m_id ;
				$icmsAdminTpl->assign("multiple", $multiple);
			}
			$icmsAdminTpl->assign("groupid", $thisgroup->getVar("groupid"));
			$icmsAdminTpl->assign("g_id", (int) $g_id );
			$icmsAdminTpl->assign("memstart",  $memstart );
			$icmsAdminTpl->assign("nav", $nav->renderNav(4));
		}
	$icmsAdminTpl->assign("security", icms::$security->getTokenHTML());
	$icmsAdminTpl->assign("modifygroups", "1");
	$icmsAdminTpl->assign("groupform", "1");
	$icmsAdminTpl->display('db:admin/groups/system_adm_groups.html');
	icms_cp_footer();
}