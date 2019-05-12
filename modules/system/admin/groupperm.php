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
 * Update permissions for a member group
 *
 * Target for the group permissions form to handle the POST data. Only used
 * by some modules - not the core.
 *
 * @todo		Full refactoring: this should really be a callback method, not a separate target.
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 */
define('ICMS_IN_ADMIN', 1);

/** necessary files, since this is a direct POST target */
require_once dirname(__DIR__) . '/include/common.php';

icms_loadLanguageFile('system', 'groups', true);

/* set filter types, if not strings */
// there should be no GET variables - this page is for POST, only!

$filter_post = array(
	'modid' => 'int',
	'uid' => 'int',
	'perms' => 'int',
);

/* set default values for variables */
$modid = 0;
$fct = $op = "";

/* filter the user input */
if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

// we don't want system module permissions to be changed here
if ($modid <= 1 || !is_object(icms::$user) || !icms::$user->isAdmin($modid) || !empty($_GET)) {
	redirect_header(ICMS_URL . '/index.php', 1, _NOPERM);
	exit();
}

$module_handler = icms::handler('icms_module');
$module = & $module_handler->get($modid);

if (!is_object($module) || !$module->getVar('isactive')) {
	redirect_header(ICMS_URL . '/admin.php', 1, _MODULENOEXIST);
	exit();
}

$msg = array();

$member_handler = icms::handler('icms_member');
$group_list = & $member_handler->getGroupList();
if (is_array($perms) && !empty($perms)) {
	$gperm_handler = icms::handler('icms_member_groupperm');
	foreach ($perms as $perm_name => $perm_data) {
		if (false != $gperm_handler->deleteByModule($modid, $perm_name)) {
			$msg[] = sprintf(_MD_AM_PERMRESETOK, $perm_name, $module->getVar('name'));
			foreach ($perm_data['groups'] as $group_id => $item_ids) {
				foreach ($item_ids as $item_id => $selected) {
					if ($selected == 1) {
						// make sure that all parent ids are selected as well
						if ($perm_data['parents'][$item_id] != '') {
							$parent_ids = explode(':', $perm_data['parents'][$item_id]);

							foreach ($parent_ids as $pid) {
								if ($pid != 0 && !in_array($pid, array_keys($item_ids))) {
									// one of the parent items were not selected, so skip this item
									$msg[] = sprintf(_MD_AM_PERMADDNG, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>') . ' (' . _MD_AM_PERMADDNGP . ')';
									continue 2;
								}
							}
						}

						$gperm = & $gperm_handler->create();
						$gperm->setVar('gperm_groupid', $group_id);
						$gperm->setVar('gperm_name', $perm_name);
						$gperm->setVar('gperm_modid', $modid);
						$gperm->setVar('gperm_itemid', $item_id);

						if (!$gperm_handler->insert($gperm)) {
							$msg[] = sprintf(_MD_AM_PERMADDNG, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
						} else {
							$msg[] = sprintf(_MD_AM_PERMADDOK, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
						}

						unset($gperm);
					}
				}
			}
		} else {
			$msg[] = sprintf(_MD_AM_PERMRESETNG, $module->getVar('name') . '(' . $perm_name . ')');
		}
	}
}

$backlink = xoops_getenv("HTTP_REFERER");
if ($module->getVar('hasadmin')) {
	$adminindex = ($redirect_url != "")?$redirect_url:$module->getInfo('adminindex');
	if ($adminindex) {
		$backlink = ICMS_MODULES_URL . '/' . $module->getVar('dirname') . '/' . $adminindex;
	}
}
$backlink = $backlink?$backlink:ICMS_URL . '/admin.php';

redirect_header($backlink, 2, implode("<br />", $msg));
