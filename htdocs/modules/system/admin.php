<?php
/**
 * The beginning of the admin interface for ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	System
 * @version		SVN: $Id$
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
