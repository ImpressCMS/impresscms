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
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	System
 * @version		SVN: $Id: admin.php 12313 2013-09-15 21:14:35Z skenow $
 */
define('ICMS_IN_ADMIN', 1);

include_once '../../include/functions.php';
if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);
if (!empty($_GET)) foreach ($_GET as $k => $v) ${$k} = StopXSS($v);
$fct = (isset($_GET['fct']))
	? trim(filter_input(INPUT_GET, 'fct'))
	: ((isset($_POST['fct']))
		? trim(filter_input(INPUT_POST, 'fct'))
		: '');

if (isset($fct) && $fct == 'users') {$xoopsOption['pagetype'] = 'user';}
include '../../mainfile.php';
include ICMS_ROOT_PATH . '/include/cp_functions.php';
icms_loadLanguageFile('system', 'admin');
icms_loadLanguageFile('core', 'moduleabout');

// hook for profile module
if (isset($fct) && $fct == 'users' && icms_get_module_status('profile')) {
	$op = isset($_GET['op']) ? filter_input(INPUT_GET, 'op') : '';
	$uid = isset($_GET['uid']) ? filter_input(INPUT_GET, 'uid') : 0;
	if ($op == 'modifyUser' && $uid != 0) {
		header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php?op=edit&id=" . $uid);
	} else {
		header("Location:" . ICMS_MODULES_URL . "/profile/admin/user.php");
	}
}

// Check if function call does exist (security)
$admin_dir = ICMS_ROOT_PATH . '/modules/system/admin';
$dirlist = icms_core_Filesystem::getDirList($admin_dir);
if ($fct && !in_array($fct, $dirlist)) {redirect_header(ICMS_URL . '/', 3, _INVALID_ADMIN_FUNCTION);}
$admintest = 0;

if (is_object(icms::$user)) {
	$icmsModule = icms::handler('icms_module')->getByDirname('system');
	if (!icms::$user->isAdmin($icmsModule->getVar('mid'))) {
		redirect_header(ICMS_URL . '/', 3, _NOPERM);
	}
	$admintest = 1;
} else {redirect_header(ICMS_URL . '/', 3, _NOPERM);}

// include system category definitions
include_once ICMS_ROOT_PATH . '/modules/system/constants.php';
$error = FALSE;
if ($admintest != 0) {
	if (isset($fct) && $fct != '') {
		if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/icms_version.php')) {
			$icms_version = 'icms_version';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/xoops_version.php')) {
			$icms_version = 'xoops_version';
		}
		if (isset($icms_version) && $icms_version !== '') {
			icms_loadLanguageFile('system', $fct, TRUE);
			include ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/' . $icms_version . '.php';
			$sysperm_handler = icms::handler('icms_member_groupperm');
			$category = !empty($modversion['category']) ? (int) $modversion['category'] : 0;
			unset($modversion);
			if ($category > 0) {
				$groups =& icms::$user->getGroups();
				if (in_array(XOOPS_GROUP_ADMIN, $groups)
					|| FALSE !== $sysperm_handler->checkRight('system_admin', $category, $groups, $icmsModule->getVar('mid')))
					{
					if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/main.php')) {
						include_once ICMS_ROOT_PATH . '/modules/system/admin/' . $fct . '/main.php';
					} else {$error = TRUE;}
				} else {$error = TRUE;}
			} elseif ($fct == 'version') {
				if (file_exists(ICMS_ROOT_PATH . '/modules/system/admin/version/main.php')) {
					include_once ICMS_ROOT_PATH . '/modules/system/admin/version/main.php';
				} else {$error = TRUE;}
			} else {$error = TRUE;}
		} else {$error = TRUE;}
	} else {$error = TRUE;}
}
if ($error) {
	header("Location:" . ICMS_URL . "/admin.php");
}
