<?php
/**
* ImpressCMS Version Checker
*
* This page checks if the ImpressCMS install runs the latest released version
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		malanciault <marcan@impresscms.org)
* @version		$Id: error.php 429 2008-01-02 22:21:41Z malanciault $
*/

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin() ) {
    exit("Access Denied");
}

require_once XOOPS_ROOT_PATH.'/class/icmsversionchecker.php';
$icmsVersionChecker = IcmsVersionChecker::getInstance();

if ($icmsVersionChecker->check()) {
	$icmsAdminTpl->assign('update_available', true);
	$icmsAdminTpl->assign('latest_changelog', $icmsVersionChecker->latest_changelog);
}
$icmsAdminTpl->assign('latest_version', $icmsVersionChecker->latest_version_name);
$icmsAdminTpl->assign('your_version', $icmsVersionChecker->installed_version_name);
$icmsAdminTpl->assign('latest_url', $icmsVersionChecker->latest_url);	

xoops_cp_header();
$icmsAdminTpl->display(XOOPS_ROOT_PATH.'/modules/system/templates/admin/system_adm_version.html');
xoops_cp_footer();
?>