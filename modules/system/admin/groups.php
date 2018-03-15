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
 * Administration of usergroups, main file
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Groups
 */

/* set filter types, if not strings */
$filter_get = array(
	'g_id' => 'int',
);

$filter_post = array();

/* set default values for variables, $op and $fct are handled in the header */
$g_id = 0;

/** common header for the admin functions */
include "admin_header.php";
$groups_handler = $icms_admin_handler;

$gperm_handler = icms::handler('icms_member_groupperm');

if ((isset($_GET['g_id']) && !$gperm_handler->checkRight('group_manager', (int) $_GET['g_id'], icms::$user->getGroups()))) {
	exit("Access Denied");
}

/** functions specific to group management */
include_once ICMS_MODULES_PATH . "/system/admin/groups/groups.php";
$member_handler = icms::handler('icms_member');

// from finduser section
if (!empty($memberslist_id) && is_array($memberslist_id)) {
	$op = "addUser";
	$uids =& $memberslist_id;
}

switch ($op) {
	case "modify":
		modifyGroup($g_id);
		break;

	case "update":
		if (!icms::$security->check()) {
			redirect_header("admin.php?fct=groups", 3, implode('<br />', icms::$security->getErrors()));
		}
		$system_catids = empty($system_catids) ? array() : $system_catids;
		$admin_mids = empty($admin_mids) ? array() : $admin_mids;
		$read_mids = empty($read_mids) ? array() : $read_mids;
		$useeditor_mids = empty($useeditor_mids) ? array() : $useeditor_mids;
		$enabledebug_mids = empty($enabledebug_mids) ? array() : $enabledebug_mids;
		$read_bids = empty($read_bids) ? array() : $read_bids;
		$group =& $member_handler->getGroup($g_id);
		$group->setVar('name', $name);
		$group->setVar('description', $desc);

		// if this group is not one of the default groups
		if (!in_array($group->getVar('groupid'), array(ICMS_GROUP_ADMIN, ICMS_GROUP_USERS, ICMS_GROUP_ANONYMOUS))) {
			if (count($system_catids) > 0) {
				$group->setVar('group_type', 'Admin');
			} else {
				$group->setVar('group_type', '');
			}
		}

		if (!$member_handler->insertGroup($group)) {
			redirect_header("admin.php?fct=groups", 3, $group->getHtmlErrors());
		} else {
			$groupid = $group->getVar('groupid');
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_groupid', $groupid));
			$criteria->add(new icms_db_criteria_Item('gperm_modid', 1));
			$criteria2 = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_name', 'system_admin'));
			$criteria2->add(new icms_db_criteria_Item('gperm_name', 'module_admin'), 'OR');
			$criteria2->add(new icms_db_criteria_Item('gperm_name', 'module_read'), 'OR');
			if ($g_id != ICMS_GROUP_ANONYMOUS) {
				$criteria2->add(new icms_db_criteria_Item('gperm_name', 'use_wysiwygeditor'), 'OR');
			}
			$criteria2->add(new icms_db_criteria_Item('gperm_name', 'enable_debug'), 'OR');
			$criteria2->add(new icms_db_criteria_Item('gperm_name', 'block_read'), 'OR');
			$criteria2->add(new icms_db_criteria_Item('gperm_name', 'group_manager'), 'OR');
			$criteria->add($criteria2);
			$gperm_handler->deleteAll($criteria);
			if (count($system_catids) > 0) {
				array_push($admin_mids, 1);
				foreach ($system_catids as $s_cid) {
					$sysperm =& $gperm_handler->create();
					$sysperm->setVar('gperm_groupid', $groupid);
					$sysperm->setVar('gperm_itemid', $s_cid);
					$sysperm->setVar('gperm_name', 'system_admin');
					$sysperm->setVar('gperm_modid', 1);
					$gperm_handler->insert($sysperm);
				}
			}

			foreach ($admin_mids as $a_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $a_mid);
				$modperm->setVar('gperm_name', 'module_admin');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}

			array_push($read_mids, 1);
			foreach ($read_mids as $r_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $r_mid);
				$modperm->setVar('gperm_name', 'module_read');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}

			if ($g_id != ICMS_GROUP_ANONYMOUS) {
				foreach ($useeditor_mids as $ed_mid) {
					$modperm =& $gperm_handler->create();
					$modperm->setVar('gperm_groupid', $groupid);
					$modperm->setVar('gperm_itemid', $ed_mid);
					$modperm->setVar('gperm_name', 'use_wysiwygeditor');
					$modperm->setVar('gperm_modid', 1);
					$gperm_handler->insert($modperm);
				}
			}

			foreach ($enabledebug_mids as $ed_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $ed_mid);
				$modperm->setVar('gperm_name', 'enable_debug');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}

			$groupmanager_gids = empty($groupmanager_gids) ? array() : $groupmanager_gids;
			foreach ($groupmanager_gids as $gm_gid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $gm_gid);
				$modperm->setVar('gperm_name', 'group_manager');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			foreach ($read_bids as $r_bid) {
				$blockperm =& $gperm_handler->create();
				$blockperm->setVar('gperm_groupid', $groupid);
				$blockperm->setVar('gperm_itemid', $r_bid);
				$blockperm->setVar('gperm_name', 'block_read');
				$blockperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($blockperm);
			}
			redirect_header("admin.php?fct=groups", 1, _ICMS_DBUPDATED);
		}
		break;

	case "add":
		if (!icms::$security->check()) {
			redirect_header("admin.php?fct=groups", 3, implode('<br />', icms::$security->getErrors()));
		}
		if (!$name) {
			redirect_header("admin.php?fct=groups", 3, _AM_UNEED2ENTER);
			exit();
		}

		$system_catids = empty($system_catids) ? array() : $system_catids;
		$admin_mids = empty($admin_mids) ? array() : $admin_mids;
		$read_mids = empty($read_mids) ? array() : $read_mids;
		$useeditor_mids = empty($useeditor_mids) ? array() : $useeditor_mids;
		$enabledebug_mids = empty($enabledebug_mids) ? array() : $enabledebug_mids;
		$groupmanager_gids = empty($groupmanager_gids) ? array() : $groupmanager_gids;
		$read_bids = empty($read_bids) ? array() : $read_bids;
		$group =& $member_handler->createGroup();
		$group->setVar("name", $name);
		$group->setVar("description", $desc);
		if (count($system_catids) > 0) {
			$group->setVar("group_type", 'Admin');
		}
		if (!$member_handler->insertGroup($group)) {
			redirect_header("admin.php?fct=groups", 3, $group->getHtmlErrors());
		} else {
			$groupid = $group->getVar('groupid');
			if (count($system_catids) > 0) {
				array_push($admin_mids, 1);
				foreach ($system_catids as $s_cid) {
					$sysperm =& $gperm_handler->create();
					$sysperm->setVar('gperm_groupid', $groupid);
					$sysperm->setVar('gperm_itemid', $s_cid);
					$sysperm->setVar('gperm_name', 'system_admin');
					$sysperm->setVar('gperm_modid', 1);
					$gperm_handler->insert($sysperm);
				}
			}
			foreach ($admin_mids as $a_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $a_mid);
				$modperm->setVar('gperm_name', 'module_admin');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			array_push($read_mids, 1);
			foreach ($read_mids as $r_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $r_mid);
				$modperm->setVar('gperm_name', 'module_read');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			foreach ($useeditor_mids as $ed_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $ed_mid);
				$modperm->setVar('gperm_name', 'use_wysiwygeditor');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			foreach ($enabledebug_mids as $ed_mid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $ed_mid);
				$modperm->setVar('gperm_name', 'enable_debug');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			foreach ($groupmanager_gids as $gm_gid) {
				$modperm =& $gperm_handler->create();
				$modperm->setVar('gperm_groupid', $groupid);
				$modperm->setVar('gperm_itemid', $gm_gid);
				$modperm->setVar('gperm_name', 'group_manager');
				$modperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($modperm);
			}
			foreach ($read_bids as $r_bid) {
				$blockperm =& $gperm_handler->create();
				$blockperm->setVar('gperm_groupid', $groupid);
				$blockperm->setVar('gperm_itemid', $r_bid);
				$blockperm->setVar('gperm_name', 'block_read');
				$blockperm->setVar('gperm_modid', 1);
				$gperm_handler->insert($blockperm);
			}
			redirect_header("admin.php?fct=groups", 1, _ICMS_DBUPDATED);
		}
		break;

	case "del":
		icms_cp_header();
		icms_core_Message::confirm(array('fct' => 'groups', 'op' => 'delConf', 'g_id' => $g_id), 'admin.php', _AM_AREUSUREDEL);
		icms_cp_footer();
		break;

	case "delConf":
		if (!icms::$security->check()) {
			redirect_header("admin.php?fct=groups", 3, implode('<br />', icms::$security->getErrors()));
		}
		if ((int) ($g_id) > 0 && !in_array($g_id, array(ICMS_GROUP_ADMIN, ICMS_GROUP_USERS, ICMS_GROUP_ANONYMOUS))) {
			$group =& $member_handler->getGroup($g_id);
			$member_handler->deleteGroup($group);
			$gperm_handler = icms::handler('icms_member_groupperm');
			$gperm_handler->deleteByGroup($g_id);
		}
		redirect_header("admin.php?fct=groups", 1, _ICMS_DBUPDATED);
		break;

	case "addUser":
		if (!icms::$security->check()) {
			redirect_header("admin.php?fct=groups", 3, implode('<br />', icms::$security->getErrors()));
		}
		$size = count($uids);
		for ($i = 0; $i < $size; $i++) {
			$member_handler->addUserToGroup($groupid, $uids[$i]);
		}
		redirect_header("admin.php?fct=groups&amp;op=modify&amp;g_id=" . $groupid . "", 0, _ICMS_DBUPDATED);
		break;

	case "delUser":
		if (!icms::$security->check()) {
			redirect_header("admin.php?fct=groups", 3, implode('<br />', icms::$security->getErrors()));
		}
		if ((int) $groupid > 0) {
			$memstart = isset($memstart) ? (int) $memstart : 0;
			if ($groupid == ICMS_GROUP_ADMIN) {
				if ($member_handler->getUserCountByGroup($groupid) > count($uids)) {
					$member_handler->removeUsersFromGroup($groupid, $uids);
				}
			} else {
				$member_handler->removeUsersFromGroup($groupid, $uids);
			}
			redirect_header('admin.php?fct=groups&amp;op=modify&amp;g_id=' . $groupid . '&amp;memstart=' . $memstart, 0, _ICMS_DBUPDATED);
		}
		break;

	case "display":
	default:
		displayGroups();
		break;
}
