<?php
// $Id: admin.php 12313 2013-09-15 21:14:35Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu) //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project //
// ------------------------------------------------------------------------- //
/**
 * The beginning of the admin interface for ImpressCMS
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @license LICENSE.txt
 * @package Administration
 * @subpackage System
 */
define('ICMS_IN_ADMIN', 1);

include '../../mainfile.php';
include ICMS_ROOT_PATH . '/include/cp_functions.php';

icms_loadLanguageFile('system', 'admin');
icms_loadLanguageFile('core', 'moduleabout');

/* default values */
$fct = $op = '';
$uid = 0;

/*
 * possible input variables
 * since this is a gateway to all the submodules, we'll have to defer some checks to them
 */
$filter_get = array('fct' => 'str', 'op' => 'str', 'uid' => 'int');

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_post var is not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, true);
	if (!empty($clean_GET)) {
		extract($clean_GET);
	}
}

/*
 * This is where it gets difficult - each submodule has its own post vars.
 * Each submodule needs to filter appropriately. This page has no post actions. Sort of.
 * users, findusers, groups, mailusers, modulesadmin are exceptions to this,
 * and all the IPF submodules that use quick search
 */

/* cannot defer everything - be strict about it, though */

$filter_post = array('fct' => 'str');

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, true);
	if (!empty($clean_POST)) {
		extract($clean_POST);
	}
}

if ($fct == 'users') {
	icms_loadLanguageFile('core', 'user');
}

// hook for profile module
if (isset($fct) && $fct == 'users' && icms_get_module_status('profile')) {
	if ($op == 'modifyUser' && $uid != 0) {
		header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php?op=edit&id=" . $uid);
	} else {
		header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php");
	}
}

// Check if function call does exist (security)
$admin_dir = ICMS_ROOT_PATH . '/modules/system/admin';
$dirlist = icms_core_Filesystem::getDirList($admin_dir);
if ($fct && !in_array($fct, $dirlist)) {
	redirect_header(ICMS_URL . '/', 3, _INVALID_ADMIN_FUNCTION);
}

$admintest = 0;

if (is_object(icms::$user)) {
	$icmsModule = icms::handler('icms_module')->getByDirname('system');
	if (!icms::$user->isAdmin($icmsModule->getVar('mid'))) {
		redirect_header(ICMS_URL . '/', 3, _NOPERM);
	}
	$admintest = 1;
} else {
	redirect_header(ICMS_URL . '/', 3, _NOPERM);
}

// include system category definitions
include_once ICMS_ROOT_PATH . '/modules/system/constants.php';
$error = false;
if ($admintest != 0) {
	if (isset($fct) && $fct != '') {
		if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/icms_version.php')) {
			$icms_version = 'icms_version';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/xoops_version.php')) {
			$icms_version = 'xoops_version';
		}
		if (isset($icms_version) && $icms_version !== '') {
			icms_loadLanguageFile('system', $fct, true);
			include ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/' . $icms_version . '.php';
			$sysperm_handler = icms::handler('icms_member_groupperm');
			$category = !empty($modversion['category']) ? (int) $modversion['category'] : 0;
			unset($modversion);
			if ($category > 0) {
				$groups = &icms::$user->getGroups();
				if (in_array(ICMS_GROUP_ADMIN, $groups) || false !== $sysperm_handler->checkRight('system_admin', $category, $groups, $icmsModule->getVar('mid'))) {
					if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/main.php')) {
						include_once ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/main.php';
					} else {
						$error = true;
					}
				} else {
					$error = true;
				}
			} elseif ($fct == 'version') {
				if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/version/main.php')) {
					include_once ICMS_ROOT_PATH . '/modules/system/admin/version/main.php';
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}
}
if ($error) {
	header("Location:" . ICMS_URL . "/admin.php");
}
