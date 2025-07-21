<?php
/**
 * ImpressCMS Version Checker
 *
 * This page checks if the ImpressCMS install runs the latest released version
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package System
 * @subpackage Version
 * @since 1.0
 * @author malanciault <marcan@impresscms.org)
 * @version SVN: $Id: main.php 12403 2014-01-26 21:35:08Z skenow $
 */

if (!is_object(icms::$user) || !is_object(icms::$module) || !icms::$user->isAdmin()) {
	exit("Access Denied");
}

/*
 * If an mid is defined in the GET params then this file is called by clicking on a module Info button in
 * System Admin > Modules, so we need to display the module information pop up
 *
 * @todo this has nothing to do in the version checker system module, but it is there as a
 * reminiscence of XOOPS. It needs to be moved elsewhere in 1.1
 */
if (isset($_GET['mid'])) {
	include_once ICMS_MODULES_PATH . '/system/admin/version/module_info.php';
	exit();
}


/**
 * Now here is the version checker :-)
 */
global $icmsAdminTpl, $xoTheme;
$icmsVersionChecker = icms_core_Versioncheckergithub::getInstance();
icms_cp_header();

if ($icmsVersionChecker->check()) {


	$icmsAdminTpl->assign('latest', $icmsVersionChecker->getLatest());
	$icmsAdminTpl->assign('installed', $icmsVersionChecker->getInstalled());
	$icmsAdminTpl->assign('update_available', $icmsVersionChecker->hasUpdate());
//	if (ICMS_VERSION_STATUS == 10 && $icmsVersionChecker->latest['status'] < 10) {
//		// I'm running a final release so make sure to notify the user that the update is not a final
//		$icmsAdminTpl->assign('not_a_final_comment', TRUE);
//	}
} else {

	$checkerErrors = $icmsVersionChecker->getErrors(TRUE);
	if ($checkerErrors) {
		$icmsAdminTpl->assign('errors', $checkerErrors);
	}
}

/* retrieve the PHP and OS information*/
$sysinfo = array();
$sysinfo['php']['version'] = PHP_VERSION;
$sysinfo['mysql']['version'] = icms::$xoopsDB->getServerVersion();
$sysinfo['php']['api'] = PHP_SAPI;
$sysinfo['os']['version'] = PHP_OS;
$sysinfo['php']['register_globals'] = ini_get('register_globals') ? _CO_ICMS_ON : _CO_ICMS_OFF;
$sysinfo['php']['allow_url_fopen'] = ini_get('allow_url_fopen') ? _CO_ICMS_ON : _CO_ICMS_OFF;
$sysinfo['php']['fsockopen'] = function_exists('fsockopen') ? _CO_ICMS_ON : _CO_ICMS_OFF;
$sysinfo['php']['allow_call_time_pass_reference'] = ini_get('allow_call_time_pass_reference') ? _CO_ICMS_ON : _CO_ICMS_OFF;
$sysinfo['php']['file_uploads'] = ini_get('file_uploads') ? _CO_ICMS_ON : _CO_ICMS_OFF;
$sysinfo['php']['post_max_size'] = icms_conv_nr2local(ini_get('post_max_size'));
$sysinfo['php']['max_input_time'] = icms_conv_nr2local(ini_get('max_input_time'));
$sysinfo['php']['output_buffering'] = icms_conv_nr2local(ini_get('output_buffering'));
$sysinfo['php']['max_execution_time'] = icms_conv_nr2local(ini_get('max_execution_time'));
$sysinfo['php']['memory_limit'] = icms_conv_nr2local(ini_get('memory_limit'));
$sysinfo['php']['upload_max_filesize'] = icms_conv_nr2local(ini_get('upload_max_filesize'));
$icmsAdminTpl->assign('sysinfo', $sysinfo);
$icmsAdminTpl->display('db:system_adm_version.html');
icms_cp_footer();
