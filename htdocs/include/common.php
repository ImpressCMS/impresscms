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

require_once ICMS_ROOT_PATH . "/include/constants.php";

// ################################## Instantiate security object ##################################
require_once ICMS_ROOT_PATH . "/class/xoopssecurity.php";
global $xoopsSecurity;
$xoopsSecurity = $icmsSecurity = new IcmsSecurity();
$icmsSecurity->checkSuperglobals();

// #################################### Initialize db constant #####################################
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$icmsSecurity->checkReferer(XOOPS_DB_CHKREF)) {
	define('XOOPS_DB_PROXY', 1);
}

// ############################ Initialize kernel and launch bootstrap #############################
require_once ICMS_ROOT_PATH . "/libraries/icms.php";
icms::setup();
icms::boot();

// ##################### Creation of old global variables for backward compat ######################
global $icmsPreloadHandler;
$icmsPreloadHandler = icms::$preload;

global $xoopsLogger, $xoopsErrorHandler;
$xoopsLogger = $xoopsErrorHandler = icms::$logger;

$xoopsDB = icms::$db;

// ################## Creation of the non-static ImpressCMS Kernel object for BC ###################
global $xoops, $impresscms;
$xoops = $impresscms = new icms_core_Kernel();

// ################################# Include common functions file #################################
include_once ICMS_ROOT_PATH . "/include/functions.php";

// ############################## register module class repositories ###############################
icms_Autoloader::registerModules();

// ################################# Including debuging functions ##################################
include_once ICMS_ROOT_PATH . "/include/debug_functions.php";

// ##################################### Load Config Settings ######################################
$config_handler = icms::handler('icms_config');
$configs = $config_handler->getConfigsByCat(
	array(
		ICMS_CONF, ICMS_CONF_USER, ICMS_CONF_METAFOOTER, ICMS_CONF_MAILER,
		ICMS_CONF_AUTH, ICMS_CONF_MULILANGUAGE, ICMS_CONF_PERSONA, ICMS_CONF_PLUGINS,
		ICMS_CONF_CAPTCHA, ICMS_CONF_SEARCH
	)
);
$xoopsConfig = $icmsConfig = $configs[ICMS_CONF];
$icmsConfigUser       = $configs[ICMS_CONF_USER];
$icmsConfigMetaFooter = $configs[ICMS_CONF_METAFOOTER];
$icmsConfigMailer     = $configs[ICMS_CONF_MAILER];
$icmsConfigAuth       = $configs[ICMS_CONF_AUTH];
$icmsConfigMultilang  = $configs[ICMS_CONF_MULILANGUAGE];
$icmsConfigPersona    = $configs[ICMS_CONF_PERSONA];
$icmsConfigPlugins    = $configs[ICMS_CONF_PLUGINS];
$icmsConfigCaptcha    = $configs[ICMS_CONF_CAPTCHA];
$icmsConfigSearch     = $configs[ICMS_CONF_SEARCH];
unset($configs);

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

// ################################### Error reporting settings ####################################
if (!isset($xoopsOption['nodebug']) || !$xoopsOption['nodebug']) {
	if ($icmsConfig['debug_mode'] == 1 || $icmsConfig['debug_mode'] == 2) {
		error_reporting(E_ALL);
		icms::$logger->enableRendering();
		icms::$logger->usePopup = ( $icmsConfig['debug_mode'] == 2 );
	} else {
		error_reporting(0);
		icms::$logger->activated = false;
	}
}

$icmsSecurity->checkBadips();

// ################################### Include version info file ###################################
include_once ICMS_ROOT_PATH."/include/version.php";

// for older versions...will be DEPRECATED!
$icmsConfig['xoops_url'] = ICMS_URL;
$icmsConfig['root_path'] = ICMS_ROOT_PATH . "/";

/**#@+
 * Host abstraction layer
 */
if (!isset($_SERVER['PATH_TRANSLATED']) && isset($_SERVER['SCRIPT_FILENAME'])) {
	$_SERVER['PATH_TRANSLATED'] =& $_SERVER['SCRIPT_FILENAME'];	 // For Apache CGI
} elseif (isset($_SERVER['PATH_TRANSLATED']) && !isset($_SERVER['SCRIPT_FILENAME'])) {
	$_SERVER['SCRIPT_FILENAME'] =& $_SERVER['PATH_TRANSLATED'];	 // For IIS/2K now I think :-(
}

if (empty($_SERVER[ 'REQUEST_URI' ])) {
	// Not defined by IIS
	// Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
	if (!( $_SERVER[ 'REQUEST_URI' ] = @$_SERVER['PHP_SELF'] )) {
		$_SERVER[ 'REQUEST_URI' ] = $_SERVER['SCRIPT_NAME'];
	}
	if (isset($_SERVER[ 'QUERY_STRING' ])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

$xoopsRequestUri = $_SERVER['REQUEST_URI'];
/**#@-*/
// Include openid common functions if needed
if (defined('ICMS_INCLUDE_OPENID')) {
	require_once ICMS_LIBRARIES_PATH . "/phpopenid/occommon.php";
}

// ################################# Validate & Start User Session #################################
$xoopsUser = $icmsUser = '';
$xoopsUserIsAdmin = $icmsUserIsAdmin = false;
$member_handler = icms::handler('icms_member');
global $sess_handler;
$sess_handler = icms::handler('icms_core_Session');

session_set_save_handler(array(&$sess_handler, 'open'), array(&$sess_handler, 'close'),
						 array(&$sess_handler, 'read'), array(&$sess_handler, 'write'),
						 array(&$sess_handler, 'destroy'), array(&$sess_handler, 'gc'));

$sslpost_name = isset($_POST[$icmsConfig['sslpost_name']]) ? $_POST[$icmsConfig['sslpost_name']] : "";
$sess_handler->sessionStart($sslpost_name);

// Autologin if correct cookie present.
if (empty($_SESSION['xoopsUserId']) && isset($_COOKIE['autologin_uname']) && isset($_COOKIE['autologin_pass'])) {
	$sess_handler->sessionAutologin($_COOKIE['autologin_uname'], $_COOKIE['autologin_pass'], $_POST);
}

if (!empty($_SESSION['xoopsUserId'])) {
	$xoopsUser = $icmsUser =& $member_handler->getUser($_SESSION['xoopsUserId']);
	if (!is_object($icmsUser)) {
		$xoopsUser = $icmsUser = '';
		// Regenrate a new session id and destroy old session
		$sess_handler->icms_sessionRegenerateId(true);
		$_SESSION = array();
	} else {
		if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '') {
			// we need to secure cookie when using SSL
			$secure = substr(ICMS_URL, 0, 5) == 'https' ? 1 : 0;
			setcookie($icmsConfig['session_name'], session_id(),
				time()+(60*$icmsConfig['session_expire']), '/', '', $secure, 0);
		}
		$icmsUser->setGroups($_SESSION['xoopsUserGroups']);
		$xoopsUserIsAdmin = $icmsUserIsAdmin = $icmsUser->isAdmin();
		if (!isset($_SESSION['UserLanguage'])) {
			$_SESSION['UserLanguage'] = $icmsUser->getVar('language');
		}
	}
}
$UserGroups = is_object($icmsUser) ? $icmsUser->getGroups() : array(ICMS_GROUP_ANONYMOUS);
if ($icmsConfigMultilang['ml_enable']) {
	require ICMS_ROOT_PATH . '/include/im_multilanguage.php' ;
	$easiestml_langs = explode(',', $icmsConfigMultilang['ml_tags']);

	$easiestml_langpaths = IcmsLists::getLangList();
	$langs = array_combine($easiestml_langs, explode(',', $icmsConfigMultilang['ml_names']));

	if ($icmsConfigMultilang['ml_autoselect_enabled']
		&& isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
		&& $_SERVER['HTTP_ACCEPT_LANGUAGE'] != "") {
		$autolang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		if (in_array($autolang, $easiestml_langs)) {
			$icmsConfig['language'] = $langs[$autolang];
		}
	}

	if (isset($_GET['lang']) && isset($_COOKIE['lang'])) {
		if (in_array($_GET['lang'], $easiestml_langs)) {
			$icmsConfig['language'] = $langs[$_GET['lang']];
			if (isset($_SESSION['UserLanguage'])) {
				$_SESSION['UserLanguage'] = $langs[$_GET['lang']];
			}
		}
	} elseif (isset($_COOKIE['lang']) && isset($_SESSION['UserLanguage'])) {
		if ($_COOKIE['lang'] != $_SESSION['UserLanguage']) {
			if (in_array($_SESSION['UserLanguage'], $langs)) {
				$icmsConfig['language'] = $_SESSION['UserLanguage'];
			}
		} else {
			if (in_array($_COOKIE['lang'], $easiestml_langs)) {
				$icmsConfig['language'] = $langs[$_COOKIE['lang']];
			}
		}
	} elseif (isset($_COOKIE['lang'])) {
		if (in_array($_COOKIE['lang'], $easiestml_langs)) {
			$icmsConfig['language'] = $langs[$_COOKIE['lang']];
			if (isset( $_SESSION['UserLanguage'] )) {
				$_SESSION['UserLanguage'] = $langs[$_GET['lang']];
			}
		}
	} elseif (isset($_GET['lang'])) {
		if (in_array($_GET['lang'], $easiestml_langs)) {
			$icmsConfig['language'] = $langs[$_GET['lang']];
		}
	}
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

if (!isset($xoopsOption)) {
	$xoopsOption = array();
}

if (!defined("XOOPS_USE_MULTIBYTES")) {
	define("XOOPS_USE_MULTIBYTES", 0);
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

if (file_exists('./xoops_version.php') || file_exists('./icms_version.php')) {
	$url_arr = explode('/', strstr($_SERVER['PHP_SELF'], '/modules/'));
	$module_handler = icms::handler('icms_module');
	$icmsModule =& $module_handler->getByDirname($url_arr[2]);
	$xoopsModule =& $module_handler->getByDirname($url_arr[2]);
	unset($url_arr);
	if (!$icmsModule || !$icmsModule->getVar('isactive')) {
		include_once ICMS_ROOT_PATH . '/header.php';
		echo "<h4>" . _MODULENOEXIST . "</h4>";
		include_once ICMS_ROOT_PATH . '/footer.php';
		exit();
	}
	$moduleperm_handler = icms::handler('icms_member_groupperm');
	if ($icmsUser) {
		if (!$moduleperm_handler->checkRight('module_read', $icmsModule->getVar('mid'), $icmsUser->getGroups())) {
			redirect_header(ICMS_URL . "/user.php", 3, _NOPERM, false);
		}
		$xoopsUserIsAdmin = $icmsUserIsAdmin = $icmsUser->isAdmin($icmsModule->getVar('mid'));
	} else {
		if (!$moduleperm_handler->checkRight('module_read', $icmsModule->getVar('mid'), ICMS_GROUP_ANONYMOUS)) {
			redirect_header(ICMS_URL . "/user.php", 3, _NOPERM);
		}
	}
	icms_loadLanguageFile($icmsModule->getVar('dirname'), 'main');
	if ($icmsModule->getVar('hasconfig') == 1
		|| $icmsModule->getVar('hascomments') == 1
		|| $icmsModule->getVar('hasnotification') == 1) {
		$icmsModuleConfig =& $config_handler->getConfigsByCat(0, $icmsModule->getVar('mid'));
		$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $icmsModule->getVar('mid'));
	}
} elseif ($icmsUser) {
	$xoopsUserIsAdmin = $icmsUserIsAdmin = $icmsUser->isAdmin(1);
}

if ($icmsConfigPersona['multi_login']) {
	if (is_object($icmsUser)) {
		$online_handler = icms::handler('icms_core_Online');
		$online_handler->write($icmsUser->getVar('uid'), $icmsUser->getVar('uname'),
							   time(), 0, $_SERVER['REMOTE_ADDR']);
	}
}

// ##################################### finalize boot process #####################################
icms::$preload->triggerEvent('finishCoreBoot');
icms::$logger->stopTime('ICMS Boot');
icms::$logger->startTime('Module init');