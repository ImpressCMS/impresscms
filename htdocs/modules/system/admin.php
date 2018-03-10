<?php
// $Id: admin.php 12313 2013-09-15 21:14:35Z skenow $
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
 * The beginning of the admin interface for ImpressCMS
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	System
 */

define('ICMS_IN_ADMIN', 1);

include_once "../../mainfile.php";
include_once dirname(__FILE__) . '/include/common.php';

$fct = (isset($_GET['fct']))
	? trim(filter_input(INPUT_GET, 'fct'))
	: ((isset($_POST['fct']))
		? trim(filter_input(INPUT_POST, 'fct'))
		: '');

if (isset($fct) && $fct == 'users') {
	icms_loadLanguageFile('core', 'user');
	// hook for profile module
	if (icms_get_module_status('profile')) {
		$op = isset($op) ? $op : '';
		$uid = isset($uid) ? $uid : 0;
		if ($op == 'modifyUser' && $uid != 0) {
			header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php?op=edit&id=" . $uid);
		} else {
			header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php");
		}
	}
}

include ICMS_INCLUDE_PATH . '/cp_functions.php';
icms_loadLanguageFile('system', 'admin');
icms_loadLanguageFile('core', 'moduleabout');

// Check if function call does exist (security)
/** @todo we don't need to scan the directory on every page load. Set a var on install or update
 *  that can be checked instead
 */
$admin_dir = ICMS_MODULES_PATH . '/system/admin';
if (!file_exists($admin_dir . '/' . $fct)) {
    if ((strlen($fct) > 5) && (substr($fct, -5) == 'admin')) {
        $fct = substr($fct, 0, -5);
    } else {
        $fct .= 'admin';
    }
    if (!file_exists($admin_dir . '/' . $fct)) {
        redirect_header(ICMS_URL . '/', 3, _INVALID_ADMIN_FUNCTION);
    } else {
        $_GET['fct'] = $_POST['fct'] = $_REQUEST['fct'] = $fct;
    }
}

$admintest = 0;

if (is_object(icms::$user)) {
	$icmsModule = icms::handler('icms_module')->getByDirname('system');
	if (!icms::$user->isAdmin($icmsModule->getVar('mid'))) {
		redirect_header(ICMS_URL . '/', 3, _NOPERM);
	}
	$admintest = 1;
} else {redirect_header(ICMS_URL . '/', 3, _NOPERM);}

// include system category definitions
include_once ICMS_MODULES_PATH . '/system/constants.php';
$error = FALSE;
if ($admintest != 0) {
	if (icms_getModuleInfo('system')->getDBVersion() < ICMS_SYSTEM_DBVERSION) {
		icms_core_Message::warning(_CO_ICMS_UPDATE_NEEDED, "", TRUE);
	}
	if (isset($fct) && $fct != '') {
		if (file_exists(ICMS_MODULES_PATH . '/system/admin/' . $fct . '/icms_version.php')) {
			icms_loadLanguageFile('system', $fct, TRUE);
			include ICMS_MODULES_PATH . '/system/admin/' . $fct . '/icms_version.php';
			$sysperm_handler = icms::handler('icms_member_groupperm');
			$category = !empty($modversion['category']) ? (int) $modversion['category'] : 0;
			unset($modversion);
			if ($category > 0) {
				$groups =& icms::$user->getGroups();
				if (in_array(ICMS_GROUP_ADMIN, $groups)
					|| FALSE !== $sysperm_handler->checkRight('system_admin', $category, $groups, $icmsModule->getVar('mid'))
				) {
					if (file_exists(ICMS_MODULES_PATH . "/system/admin/" . $fct . ".php")) {
						include_once ICMS_MODULES_PATH . "/system/admin/" . $fct . ".php";
					}
				} else {$error = TRUE;}
			} else {$error = TRUE;}
		} else {$error = TRUE;}
	} else {$error = TRUE;}
}
if ($error) {
	header("Location:" . ICMS_URL . "/admin.php");
}
