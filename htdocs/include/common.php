<?php
// $Id: comment_reply.php 1130 2007-10-24 10:16:06Z dugris $
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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //
defined("XOOPS_MAINFILE_INCLUDED") or die();

set_magic_quotes_runtime(0);

/**
 * Creating ICMS specific constants
 */
define('ICMS_ROOT_PATH', XOOPS_ROOT_PATH);
define('ICMS_URL', XOOPS_URL);
define('ICMS_TRUST_PATH', XOOPS_TRUST_PATH);
define('ICMS_PRELOAD_PATH', XOOPS_ROOT_PATH . '/preload');

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
$xoopsLogger->startTime();
$xoopsLogger->startTime( 'XOOPS Boot' );

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

// TODO: Change the name of the constant.
define("XOOPS_EDITOR_PATH", XOOPS_ROOT_PATH."/editors");
define("XOOPS_EDITOR_URL", XOOPS_URL."/editors");
		
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

// ############## Login a user with a valid session ##############
$xoopsUser = '';
$xoopsUserIsAdmin = false;
$member_handler =& xoops_gethandler('member');
global $sess_handler;
$sess_handler =& xoops_gethandler('session');
if ($xoopsConfig['use_ssl'] && isset($_POST[$xoopsConfig['sslpost_name']]) && $_POST[$xoopsConfig['sslpost_name']] != '') {
	session_id($_POST[$xoopsConfig['sslpost_name']]);
} elseif ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
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
		session_regenerate_id(true);
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

if (file_exists('./xoops_version.php')) {
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
// ################# Preload Trigger finishCoreBoot ##############
$icmsPreloadHandler->triggerEvent('finishCoreBoot');

$xoopsLogger->stopTime( 'XOOPS Boot' );
$xoopsLogger->startTime( 'Module init' );
?>