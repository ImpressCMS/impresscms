<?php
/**
*
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
defined("XOOPS_MAINFILE_INCLUDED") or die();

@set_magic_quotes_runtime(0);

if (!defined('ICMS_ROOT_PATH')) {
	define( 'ICMS_ROOT_PATH', XOOPS_ROOT_PATH );
}
if (!defined('ICMS_TRUST_PATH')) {
	define( 'ICMS_TRUST_PATH', XOOPS_TRUST_PATH );
}
if (!defined('ICMS_URL')) {
	define( 'ICMS_URL', XOOPS_URL );
}
if (!defined('ICMS_GROUP_ADMIN')) {
	define('ICMS_GROUP_ADMIN', XOOPS_GROUP_ADMIN);
}
if (!defined('ICMS_GROUP_USERS')) {
	define('ICMS_GROUP_USERS', XOOPS_GROUP_USERS);
}
if (!defined('ICMS_GROUP_ANONYMOUS')) {
	define('ICMS_GROUP_ANONYMOUS', XOOPS_GROUP_ANONYMOUS);
}

/**
 * Creating ICMS specific constants
 */
define('ICMS_PRELOAD_PATH', XOOPS_ROOT_PATH . '/preloads');
define('ICMS_PURIFIER_CACHE', ICMS_TRUST_PATH.'/cache/htmlpurifier');

// ################# Creation of the IcmsPreloadHandler ##############
include_once ICMS_ROOT_PATH . '/kernel/icmspreloadhandler.php';

global $icmsPreloadHandler;
$icmsPreloadHandler = IcmsPreloadHandler::getInstance();

// ################# Creation of the ImpressCMS Libraries ##############
/**
 * @todo The definition of the library path needs to be in mainfile
 */
// ImpressCMS Third Party Libraries folder
define( 'ICMS_LIBRARIES_PATH', XOOPS_ROOT_PATH . '/libraries' );
define( 'ICMS_LIBRARIES_URL', XOOPS_URL . '/libraries' );
// ImpressCMS Third Party Library for PDF generator
define( 'ICMS_PDF_LIB_PATH', ICMS_ROOT_PATH . '/libraries/tcpdf' );
define( 'ICMS_PDF_LIB_URL', ICMS_URL . '/libraries/tcpdf' );
// ImpressCMS Modules path & url
define( 'ICMS_MODULES_PATH', ICMS_ROOT_PATH . '/modules' );
define( 'ICMS_MODULES_URL', ICMS_URL . '/modules' );

// ################# Preload Trigger startCoreBoot ##############
$icmsPreloadHandler->triggerEvent('startCoreBoot');

// ################# Creation of the ImpressCMS Kernel object ##############
include_once(ICMS_ROOT_PATH . '/kernel/icmskernel.php');

global $impresscms, $xoops;
$impresscms =& new IcmsKernel();
$xoops =& $impresscms;
// ################# Creation of the ImpressCMS Kernel object ##############

// Instantiate security object
require_once XOOPS_ROOT_PATH."/class/xoopssecurity.php";
global $xoopsSecurity;
$xoopsSecurity = new XoopsSecurity();
//Check super globals
$xoopsSecurity->checkSuperglobals();

// ############## Activate error handler / logger class ##############
global $xoopsLogger, $xoopsErrorHandler;

include_once XOOPS_ROOT_PATH . '/class/logger.php';
$xoopsLogger =& XoopsLogger::instance();
$xoopsErrorHandler =& $xoopsLogger;
$xoopsLogger->startTime('ICMS');
$xoopsLogger->startTime( 'ICMS Boot' );

define("XOOPS_SIDEBLOCK_LEFT",1);
define("XOOPS_SIDEBLOCK_RIGHT",2);
define("XOOPS_SIDEBLOCK_BOTH",-2);
define("XOOPS_CENTERBLOCK_LEFT",3);
define("XOOPS_CENTERBLOCK_RIGHT",5);
define("XOOPS_CENTERBLOCK_CENTER",4);
define("XOOPS_CENTERBLOCK_ALL",-6);
define("XOOPS_CENTERBLOCK_BOTTOMLEFT",6);
define("XOOPS_CENTERBLOCK_BOTTOMRIGHT",8);
define("XOOPS_CENTERBLOCK_BOTTOM",7);

define("XOOPS_BLOCK_INVISIBLE",0);
define("XOOPS_BLOCK_VISIBLE",1);
define("XOOPS_MATCH_START",0);
define("XOOPS_MATCH_END",1);
define("XOOPS_MATCH_EQUAL",2);
define("XOOPS_MATCH_CONTAIN",3);

define("ICMS_INCLUDE_PATH", ICMS_ROOT_PATH."/include");
define("ICMS_INCLUDE_URL", ICMS_ROOT_PATH."/include");
define("ICMS_UPLOAD_PATH", ICMS_ROOT_PATH."/uploads");
define("ICMS_UPLOAD_URL", ICMS_URL."/uploads");
define("ICMS_THEME_PATH", ICMS_ROOT_PATH."/themes");
define("ICMS_THEME_URL", ICMS_URL."/themes");
define("ICMS_COMPILE_PATH", ICMS_ROOT_PATH."/templates_c");
define("ICMS_CACHE_PATH", ICMS_ROOT_PATH."/cache");
define("ICMS_IMAGES_URL", ICMS_URL."/images");
define("ICMS_EDITOR_PATH", ICMS_ROOT_PATH."/editors");
define("ICMS_EDITOR_URL", ICMS_URL."/editors");
/**
 * @todo make this $icms_images_setname as an option in preferences...
 */
$icms_images_setname = 'crystal';
define("ICMS_IMAGES_SET_URL", ICMS_IMAGES_URL."/" . $icms_images_setname);

/**#@+
 * Deprectaed: for backward compatibility
 */
define("XOOPS_INCLUDE_PATH", ICMS_INCLUDE_PATH);
define("XOOPS_INCLUDE_URL", ICMS_INCLUDE_URL);
define("XOOPS_UPLOAD_PATH", ICMS_UPLOAD_PATH);
define("XOOPS_UPLOAD_URL", ICMS_UPLOAD_URL);
define("XOOPS_THEME_PATH", ICMS_THEME_PATH);
define("XOOPS_THEME_URL", ICMS_THEME_URL);
define("XOOPS_COMPILE_PATH", ICMS_COMPILE_PATH);
define("XOOPS_CACHE_PATH", ICMS_CACHE_PATH);
define("XOOPS_EDITOR_PATH", ICMS_EDITOR_PATH);
define("XOOPS_EDITOR_URL", ICMS_EDITOR_URL);


define("SMARTY_DIR", ICMS_LIBRARIES_PATH."/smarty/");

if (!defined('XOOPS_XMLRPC')) {
	define('XOOPS_DB_CHKREF', 1);
} else {
	define('XOOPS_DB_CHKREF', 0);
}

// ############## Include common functions file ##############
include_once XOOPS_ROOT_PATH.'/include/functions.php';

// #################### Connect to DB ##################
require_once XOOPS_ROOT_PATH.'/class/database/databasefactory.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$xoopsSecurity->checkReferer(XOOPS_DB_CHKREF)) {
	define('XOOPS_DB_PROXY', 1);
}
$xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();

// ################# Include required files ##############
require_once XOOPS_ROOT_PATH.'/kernel/object.php';
require_once XOOPS_ROOT_PATH.'/class/criteria.php';

// #################### Include text sanitizer ##################
include_once XOOPS_ROOT_PATH."/class/module.textsanitizer.php";

// #################### Include html purifier ##################
include_once XOOPS_ROOT_PATH."/class/icms.htmlpurifier.php";

// #################### Including debuging functions ##################
include_once(XOOPS_ROOT_PATH . "/include/debug_functions.php");

// ################# Load Config Settings ##############
$config_handler =& xoops_gethandler('config');
$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);

// #################### Easiest ML by Gijoe #################

// Disable gzip compression if PHP is run under CLI mode
// To be refactored
if (empty($_SERVER['SERVER_NAME']) || substr(PHP_SAPI, 0, 3) == 'cli') {
	$xoopsConfig['gzip_compression'] = 0;
}
if ( $xoopsConfig['gzip_compression'] == 1 && extension_loaded( 'zlib' ) && !ini_get( 'zlib.output_compression' ) ) {
	if ( @ini_get( 'zlib.output_compression_level' ) < 0 ) {
    	ini_set( 'zlib.output_compression_level', 6 );
	}
 	ob_start( 'ob_gzhandler' );
}

// #################### Error reporting settings ##################
if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 ) {
	error_reporting(E_ALL);
    $xoopsLogger->enableRendering();
    $xoopsLogger->usePopup = ( $xoopsConfig['debug_mode'] == 2 );
} else {
	error_reporting(0);
    $xoopsLogger->activated = false;
}
$xoopsSecurity->checkBadips();

// ################# Include version info file ##############
include_once XOOPS_ROOT_PATH."/include/version.php";

// for older versions...will be DEPRECATED!
$xoopsConfig['xoops_url'] = XOOPS_URL;
$xoopsConfig['root_path'] = XOOPS_ROOT_PATH."/";

/**#@+
 * Host abstraction layer
 */
if ( !isset($_SERVER['PATH_TRANSLATED']) && isset($_SERVER['SCRIPT_FILENAME']) ) {
	$_SERVER['PATH_TRANSLATED'] =& $_SERVER['SCRIPT_FILENAME'];     // For Apache CGI
} elseif ( isset($_SERVER['PATH_TRANSLATED']) && !isset($_SERVER['SCRIPT_FILENAME']) ) {
	$_SERVER['SCRIPT_FILENAME'] =& $_SERVER['PATH_TRANSLATED'];     // For IIS/2K now I think :-(
}

if ( empty( $_SERVER[ 'REQUEST_URI' ] ) ) {         // Not defined by IIS
	// Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
    if ( !( $_SERVER[ 'REQUEST_URI' ] = @$_SERVER['PHP_SELF'] ) ) {
    	$_SERVER[ 'REQUEST_URI' ] = $_SERVER['SCRIPT_NAME'];
	}
    if ( isset( $_SERVER[ 'QUERY_STRING' ] ) ) {
    	$_SERVER[ 'REQUEST_URI' ] .= '?' . $_SERVER[ 'QUERY_STRING' ];
	}
}
$xoopsRequestUri = $_SERVER[ 'REQUEST_URI' ];       // Deprecated (use the corrected $_SERVER variable now)
/**#@-*/
    // Include openid common functions if needed
    if (defined('ICMS_INCLUDE_OPENID')) {
    	require_once ICMS_LIBRARIES_PATH . "/phpopenid/occommon.php";
    }

// ############## Login a user with a valid session ##############
$xoopsUser = '';
$xoopsUserIsAdmin = false;
$member_handler =& xoops_gethandler('member');
global $sess_handler;
$sess_handler =& xoops_gethandler('session');
if ($xoopsConfig['use_ssl'] && isset($_POST[$xoopsConfig['sslpost_name']]) && $_POST[$xoopsConfig['sslpost_name']] != '') {
	session_id($_POST[$xoopsConfig['sslpost_name']]);
} elseif ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '' && $xoopsConfig['session_expire'] > 0) {
	if (isset($_COOKIE[$xoopsConfig['session_name']])) {
    	session_id($_COOKIE[$xoopsConfig['session_name']]);
	}
    if (function_exists('session_cache_expire')) {
    	session_cache_expire($xoopsConfig['session_expire']);
	}
    @ini_set('session.gc_maxlifetime', $xoopsConfig['session_expire'] * 60);
}

session_set_save_handler(array(&$sess_handler, 'open'), array(&$sess_handler, 'close'), array(&$sess_handler, 'read'), array(&$sess_handler, 'write'), array(&$sess_handler, 'destroy'), array(&$sess_handler, 'gc'));

session_start();
/*
$sess_handler->securityLevel = 3;
$sess_handler->check_ip_blocks = 2;
$sess_handler->salt_key = XOOPS_DB_SALT;
$sess_handler->enableRegenerateId = true;
$sess_handler->icms_sessionOpen(); */

// Remove expired session for xoopsUserId
if ( $xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '' && !isset($_COOKIE[$xoopsConfig['session_name']]) && !empty($_SESSION['xoopsUserId']) ) {
	unset( $_SESSION['xoopsUserId'] );
}

// autologin hack GIJ
if(empty($_SESSION['xoopsUserId']) && isset($_COOKIE['autologin_uname']) && isset($_COOKIE['autologin_pass'])) {

		// autologin V2 GIJ
		if( ! empty( $_POST ) ) {
			$_SESSION['AUTOLOGIN_POST'] = $_POST ;
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'] ;
			redirect_header( XOOPS_URL . '/session_confirm.php' , 0 , '&nbsp;' ) ;
		} else if( ! empty( $_SERVER['QUERY_STRING'] ) && substr( $_SERVER['SCRIPT_NAME'] , -19 ) != 'session_confirm.php') {
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'] ;
			redirect_header( XOOPS_URL . '/session_confirm.php' , 0 , '&nbsp;' ) ;
		}
		// end of autologin V2

		// redirect to XOOPS_URL/ when query string exists (anti-CSRF) V1 code
		/* if( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			redirect_header( XOOPS_URL . '/' , 0 , 'Now, logging in automatically' ) ;
			exit ;
		}*/

		$myts =& MyTextSanitizer::getInstance();
		$uname = $myts->stripSlashesGPC($_COOKIE['autologin_uname']);
		$pass = $myts->stripSlashesGPC($_COOKIE['autologin_pass']);
		if( empty( $uname ) || is_numeric( $pass ) ) $user = false ;
		else {
			// V3
			$uname4sql = addslashes( $uname ) ;
			$criteria = new CriteriaCompo(new Criteria('uname', $uname4sql ));
			$user_handler =& xoops_gethandler('user');
			$users =& $user_handler->getObjects($criteria, false);
			if( empty( $users ) || count( $users ) != 1 ) $user = false ;
			else {
				// V3.1 begin
				$user = $users[0] ;
				$old_limit = time() - ( defined('XOOPS_AUTOLOGIN_LIFETIME') ? XOOPS_AUTOLOGIN_LIFETIME : 604800 ) ; // 1 week default
				list( $old_Ynj , $old_encpass ) = explode( ':' , $pass ) ;
				if( strtotime( $old_Ynj ) < $old_limit || md5( $user->getVar('pass') . XOOPS_DB_PASS . XOOPS_DB_PREFIX . $old_Ynj ) != $old_encpass ) $user = false ;
				// V3.1 end
			}
			unset( $users ) ;
		}
		$xoops_cookie_path = defined('XOOPS_COOKIE_PATH') ? XOOPS_COOKIE_PATH : preg_replace( '?http://[^/]+(/.*)$?' , "$1" , XOOPS_URL ) ;
		if( $xoops_cookie_path == XOOPS_URL ) $xoops_cookie_path = '/' ;
		if (false != $user && $user->getVar('level') > 0) {
			// update time of last login
			$user->setVar('last_login', time());
			if (!$member_handler->insertUser($user, true)) {
			}
			//$_SESSION = array();
			$_SESSION['xoopsUserId'] = $user->getVar('uid');
			$_SESSION['xoopsUserGroups'] = $user->getGroups();
			// begin newly added in 2004-11-30
			$user_theme = $user->getVar('theme');
			$user_language = $user->language();
			if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
				$_SESSION['xoopsUserTheme'] = $user_theme;
			}
			$_SESSION['xoopsUserLanguage'] = $user_language;

			// end newly added in 2004-11-30
			// update autologin cookies
			$expire = time() + ( defined('XOOPS_AUTOLOGIN_LIFETIME') ? XOOPS_AUTOLOGIN_LIFETIME : 604800 ) ; // 1 week default
			setcookie('autologin_uname', $uname, $expire, $xoops_cookie_path, '', 0);
			// V3.1
			$Ynj = date( 'Y-n-j' ) ;
			setcookie('autologin_pass', $Ynj . ':' . md5( $user->getVar('pass') . XOOPS_DB_PASS . XOOPS_DB_PREFIX . $Ynj ) , $expire, $xoops_cookie_path, '', 0);
		} else {
			setcookie('autologin_uname', '', time() - 3600, $xoops_cookie_path, '', 0);
			setcookie('autologin_pass', '', time() - 3600, $xoops_cookie_path, '', 0);
		}
	}
	// end of autologin hack GIJ

if (!empty($_SESSION['xoopsUserId'])) {
	$xoopsUser =& $member_handler->getUser($_SESSION['xoopsUserId']);
    if (!is_object($xoopsUser)) {
    	$xoopsUser = '';
		// Regenrate a new session id and destroy old session
		$sess_handler->icms_sessionRegenerateId(true);
        $_SESSION = array();
	} else {
    	if ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
        	setcookie($xoopsConfig['session_name'], session_id(), time()+(60*$xoopsConfig['session_expire']), '/',  '', 0);
		}
        $xoopsUser->setGroups($_SESSION['xoopsUserGroups']);
        $xoopsUserIsAdmin = $xoopsUser->isAdmin();
        if(!isset($_SESSION['xoopsUserLanguage']) ){
        	$_SESSION['xoopsUserLanguage'] = $xoopsUser->language();
		}
	}
}

$im_multilanguageConfig =& $config_handler->getConfigsByCat(IM_CONF_MULILANGUAGE);
if ($im_multilanguageConfig['ml_enable']) {
require XOOPS_ROOT_PATH.'/include/im_multilanguage.php' ;
$easiestml_langs = explode( ',' , $im_multilanguageConfig['ml_tags'] ) ;
include_once(XOOPS_ROOT_PATH . '/class/xoopslists.php');

$easiestml_langpaths = XoopsLists::getLangList();
$langs = array_combine($easiestml_langs,explode( ',' , $im_multilanguageConfig['ml_names'] ));

if( $im_multilanguageConfig['ml_autoselect_enabled']  && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE'] != "" ){
	$autolang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
	if (in_array($autolang,$easiestml_langs)){
    	$xoopsConfig['language'] = $langs[$autolang];
	}
}
if (isset( $_GET['lang'] ) && isset($_COOKIE['lang'])){
	if (in_array($_GET['lang'],$easiestml_langs)){
    	$xoopsConfig['language'] = $langs[$_GET['lang']];
    	if(isset( $_SESSION['xoopsUserLanguage'] )){
    		$_SESSION['xoopsUserLanguage'] = $langs[$_GET['lang']];
		}
	}
}elseif(isset($_COOKIE['lang']) && isset( $_SESSION['xoopsUserLanguage'] )){
	if($_COOKIE['lang'] != $_SESSION['xoopsUserLanguage'] ){
    	if( in_array( $_SESSION['xoopsUserLanguage'] , $langs ) )
    		$xoopsConfig['language'] = $_SESSION['xoopsUserLanguage'];
	}else{
    	if (in_array($_COOKIE['lang'],$easiestml_langs))
    		$xoopsConfig['language'] = $langs[$_COOKIE['lang']];
    	}
    }elseif(isset($_COOKIE['lang'])){
    	if (in_array($_COOKIE['lang'],$easiestml_langs)){
    		$xoopsConfig['language'] = $langs[$_COOKIE['lang']];
    		if(isset( $_SESSION['xoopsUserLanguage'] )){
    			$_SESSION['xoopsUserLanguage'] = $langs[$_GET['lang']];
    		}
    	}
    }elseif(isset($_GET['lang'])){
    	if (in_array($_GET['lang'],$easiestml_langs)){
    		$xoopsConfig['language'] = $langs[$_GET['lang']];
    	}
    }
}

// #################### Include site-wide lang file ##################
if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/global.php") ) {
	include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/global.php";
} else {
	include_once XOOPS_ROOT_PATH."/language/english/global.php";
}
if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/theme.php") ) {
	include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/theme.php";
} else {
	include_once XOOPS_ROOT_PATH."/language/english/theme.php";
}
if(!defined('_GLOBAL_LEFT')){
define('_GLOBAL_LEFT', (( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'right':'left'));} // type here right in rtl languages
if(!defined('_GLOBAL_RIGHT')){
define('_GLOBAL_RIGHT', (( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'left':'right'));} // type here left in rtl languages
// ################ Include page-specific lang file ################
if (isset($xoopsOption['pagetype']) && false === strpos($xoopsOption['pagetype'], '.')) {
	if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/".$xoopsOption['pagetype'].".php") ) {
    	include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/".$xoopsOption['pagetype'].".php";
	} else {
    	include_once XOOPS_ROOT_PATH."/language/english/".$xoopsOption['pagetype'].".php";
	}
}

$xoopsOption = array();

if ( !defined("XOOPS_USE_MULTIBYTES") ) {
	define("XOOPS_USE_MULTIBYTES",0);
}

if (!empty($_POST['xoops_theme_select']) && in_array($_POST['xoops_theme_select'], $xoopsConfig['theme_set_allowed'])) {
	$xoopsConfig['theme_set'] = $_POST['xoops_theme_select'];
    $_SESSION['xoopsUserTheme'] = $_POST['xoops_theme_select'];
} elseif (!empty($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $xoopsConfig['theme_set_allowed'])) {
	$xoopsConfig['theme_set'] = $_SESSION['xoopsUserTheme'];
}

if ($xoopsConfig['closesite'] == 1) {
	include XOOPS_ROOT_PATH . "/include/site-closed.php";
}

if (file_exists('./xoops_version.php') || file_exists('./icms_version.php')) {
	$url_arr = explode( '/', strstr( $_SERVER['PHP_SELF'],'/modules/') );
    $module_handler =& xoops_gethandler('module');
    $xoopsModule =& $module_handler->getByDirname($url_arr[2]);
    unset($url_arr);
    if (!$xoopsModule || !$xoopsModule->getVar('isactive')) {
    	include_once XOOPS_ROOT_PATH."/header.php";
        echo "<h4>"._MODULENOEXIST."</h4>";
        include_once XOOPS_ROOT_PATH."/footer.php";
        exit();
	}
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if ($xoopsUser) {
    	if (!$moduleperm_handler->checkRight('module_read', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
        	redirect_header(XOOPS_URL."/user.php",1,_NOPERM, false);
		}
        $xoopsUserIsAdmin = $xoopsUser->isAdmin($xoopsModule->getVar('mid'));
	} else {
    	if (!$moduleperm_handler->checkRight('module_read', $xoopsModule->getVar('mid'), XOOPS_GROUP_ANONYMOUS)) {
        	redirect_header(XOOPS_URL."/user.php",1,_NOPERM);
		}
	}
    if ( file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/".$xoopsConfig['language']."/main.php") ) {
    	include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/".$xoopsConfig['language']."/main.php";
	} else {
    	if ( file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/english/main.php") ) {
        	include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/english/main.php";
		}
	}
    if ($xoopsModule->getVar('hasconfig') == 1 || $xoopsModule->getVar('hascomments') == 1 || $xoopsModule->getVar( 'hasnotification' ) == 1) {
    	$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
	}
} elseif($xoopsUser) {
	$xoopsUserIsAdmin = $xoopsUser->isAdmin(1);
}
    $config_handler =& xoops_gethandler('config');
	$xoopsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
	if ($xoopsConfigPersona['multi_login']){
		if( is_object( $xoopsUser ) ) {
			$online_handler =& xoops_gethandler('online');
			$online_handler->write($xoopsUser->uid(), $xoopsUser->uname(),
			time(),0,$HTTP_SERVER_VARS['REMOTE_ADDR']);
		}
	}
// ################# Preload Trigger finishCoreBoot ##############
$icmsPreloadHandler->triggerEvent('finishCoreBoot');

$xoopsLogger->stopTime( 'ICMS Boot' );
$xoopsLogger->startTime( 'Module init' );
?>
