<?php
/**
 * All common information used in the core goes from here.
 * Be careful while editing this file!
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 */

/** make sure mainfile is included, for security and functionality */
defined("XOOPS_MAINFILE_INCLUDED") or die();

@set_magic_quotes_runtime(0);

// ########################## Include common functions and constants file ##########################
require_once ICMS_ROOT_PATH . "/include/constants.php";
include_once ICMS_ROOT_PATH . "/include/functions.php";
include_once ICMS_ROOT_PATH . "/include/debug_functions.php";
include_once ICMS_ROOT_PATH . "/include/version.php";

if (!isset($xoopsOption)) {
	$xoopsOption = array();
}
if (!defined("XOOPS_USE_MULTIBYTES")) {
	define("XOOPS_USE_MULTIBYTES", 0);
}

// ############################ Initialize kernel and launch bootstrap #############################
require_once ICMS_ROOT_PATH . "/libraries/icms.php";
icms::setup();
icms::boot();

// ############################## register module class repositories ###############################
icms_Autoloader::registerModules();

// ###################################### Easiest ML by Gijoe ######################################

// Disable gzip compression if PHP is run under CLI mode
// To be refactored
if (empty($_SERVER['SERVER_NAME']) || substr(PHP_SAPI, 0, 3) == 'cli') {
	$icmsConfig['gzip_compression'] = 0;
}
if ($icmsConfig['gzip_compression'] == 1
	&& extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
	if (@ini_get('zlib.output_compression_level') < 0) {
		ini_set('zlib.output_compression_level', 6);
	}
	ob_start('ob_gzhandler');
}

// Include openid common functions if needed
if (defined('ICMS_INCLUDE_OPENID')) {
	require_once ICMS_LIBRARIES_PATH . "/phpopenid/occommon.php";
}

// ################################## Include site-wide lang file ##################################
icms_loadLanguageFile('core', 'global');
icms_loadLanguageFile('core', 'theme');
icms_loadLanguageFile('core', 'core');
icms_loadLanguageFile('system', 'common');
@define('_GLOBAL_LEFT', @_ADM_USE_RTL == 1 ? 'right' : 'left');
@define('_GLOBAL_RIGHT', @_ADM_USE_RTL == 1 ? 'left' : 'right');

// ################################ Include page-specific lang file ################################
if (isset($xoopsOption['pagetype']) && false === strpos($xoopsOption['pagetype'], '.')) {
	icms_loadLanguageFile('core', $xoopsOption['pagetype']);
}

if (!empty($_POST['xoops_theme_select']) && in_array($_POST['xoops_theme_select'], $icmsConfig['theme_set_allowed'])) {
	$icmsConfig['theme_set'] = $_POST['xoops_theme_select'];
	$_SESSION['xoopsUserTheme'] = $_POST['xoops_theme_select'];
} elseif (!empty($_POST['theme_select']) && in_array($_POST['theme_select'], $icmsConfig['theme_set_allowed'])) {
	$icmsConfig['theme_set'] = $_POST['theme_select'];
	$_SESSION['xoopsUserTheme'] = $_POST['theme_select'];
} elseif (!empty($_SESSION['xoopsUserTheme'])
		&& in_array($_SESSION['xoopsUserTheme'], $icmsConfig['theme_set_allowed'])) {
	$icmsConfig['theme_set'] = $_SESSION['xoopsUserTheme'];
}

if ($icmsConfig['closesite'] == 1) {
	include ICMS_ROOT_PATH . '/include/site-closed.php';
}

icms::launchModule();

if ($icmsConfigPersona['multi_login']) {
	if (is_object($icmsUser)) {
		$online_handler = icms::handler('icms_core_Online');
		$online_handler->write($icmsUser->getVar('uid'), $icmsUser->getVar('uname'),
							   time(), 0, $_SERVER['REMOTE_ADDR']);
	}
}

// ##################################### finalize boot process #####################################
icms::$preload->triggerEvent('finishCoreBoot');
