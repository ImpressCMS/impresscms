<?php

$db =& icms_db_Factory::instance() ;

// beggining of Output
icms_cp_header();

// title
$moduleName = $xoopsModule->getVar('name');
echo "<div class='CPbigTitle' style='background-image: url(../images/iconbig_icms.png)'><a href='#'>" . $moduleName . "</a></div>\n";

// Menu
include dirname(__FILE__).'/mymenu.php' ;

	// for RTL users
	@define( '_GLOBAL_LEFT' , @_ADM_USE_RTL == 1 ? 'right' : 'left' ) ;
	@define( '_GLOBAL_RIGHT' , @_ADM_USE_RTL == 1 ? 'left' : 'right' ) ;

// open ADVISORY
echo "<div class='protectorAdvisoryWrapper'>\n";

// calculate the relative path between XOOPS_ROOT_PATH and XOOPS_TRUST_PATH
$root_paths = explode( '/' , XOOPS_ROOT_PATH ) ;
$trust_paths = explode( '/' , XOOPS_TRUST_PATH ) ;
foreach( $root_paths as $i => $rpath ) {
	if( $rpath != $trust_paths[ $i ] ) break ;
}
$relative_path = str_repeat( '../' , count( $root_paths ) - $i ) . implode( '/' , array_slice( $trust_paths , $i ) ) ;

// the path of XOOPS_TRUST_PATH accessible check
function url_exists($url) {
  $h = get_headers($url);
  $status = array();
  preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
  return ($status[1] == 200);
}
$test = url_exists(XOOPS_URL . "/" . htmlspecialchars($relative_path) . "/modules/protector/public_check.png");
if($test) {
	// Accessible URL - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>ICMS_TRUST_PATH (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= "<img style='padding: 2px; border: 3px solid #F2DEDE; margin: 0 0 10px;' src='" . XOOPS_URL . "/" . htmlspecialchars($relative_path) . "/modules/protector/public_check.png' alt='' /><br />\n";
			$safeCheck .= _AM_ADV_TRUSTPATHPUBLIC . "\n";
			$safeCheck .= "<h3><a href='" . XOOPS_URL . '/' . htmlspecialchars($relative_path) . "/modules/protector/public_check.php'>" . _AM_ADV_TRUSTPATHPUBLICLINK . "</a></h3>\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// Inaccessible - Kickass.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>ICMS_TRUST_PATH - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;


// register_globals
$test = ini_get( "register_globals" ) ;
if($test) {
	// On - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>PHP: register_globals (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_REGISTERGLOBALS . "<br />" . XOOPS_ROOT_PATH . "/.htaccess<br />\n";
			$safeCheck .= "<h3>php_flag &nbsp; register_globals &nbsp; off</em></h3>\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// Off - Kickass.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>PHP: register_globals - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;


// allow_url_fopen
$test = ini_get( "allow_url_fopen" ) ;
if($test) {
	// On - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>PHP: allow_url_fopen (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_ALLOWURLFOPEN . "\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// Off - Kickass.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>PHP: allow_url_fopen - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;



// session.use_trans_sid
$test = ini_get( "session.use_trans_sid" ) ;
if($test) {
	// On - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>Server: session.use_trans_sid (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_USETRANSSID . "\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// Off - Kickass.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>Server: session.use_trans_sid - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;


// XOOPS_DB_PREFIX
$test = strtolower( XOOPS_DB_PREFIX ) != 'xoops' && strtolower( XOOPS_DB_PREFIX ) != 'icms';
if(!$test) {
	// The DB Prefix is way too easily guessed - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>XOOPS_DB_PREFIX = " . XOOPS_DB_PREFIX . " (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_DBPREFIX . "\n";
			$safeCheck .= "<h3><a href='index.php?page=prefix_manager'>"._AM_ADV_LINK_TO_PREFIXMAN."</a></h3>\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// We can only hope that this prefix is bueno - PASS.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>XOOPS_DB_PREFIX = " . XOOPS_DB_PREFIX . " - <strong>Secure!</strong> :)</span>\n";
		$safeCheck .= "<br /><a href='index.php?page=prefix_manager'>" . _AM_ADV_LINK_TO_PREFIXMAN . "</a>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;


// patch to mainfile.php
$test = defined( 'PROTECTOR_PRECHECK_INCLUDED' ) && defined( 'PROTECTOR_POSTCHECK_INCLUDED' ) ? true : false;
if(!$test) {
	// Mainfile not patched - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";	
		
		$test2 = defined( 'PROTECTOR_PRECHECK_INCLUDED' ) ? true : false;
		$test3 = defined( 'PROTECTOR_POSTCHECK_INCLUDED' ) ? true : false;
		if(!$test2) {
			// Missing PreCheck
			$safeCheck .= "<h3>Mainfile.php: Missing PreCheck (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_MAINUNPATCHED . "\n";
		} 
		if(!$test3) {
			// Missing PostCheck
			$safeCheck .= "<h3>Mainfile.php: Missing PostCheck (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_MAINUNPATCHED . "\n";
		}
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// PreCheck and PostCheck detected - PASS.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>Mainfile.php: PreCheck/PostCheck Detected - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;


// patch to databasefactory.php
$db =& icms_db_Factory::instance() ;
$test = strtolower( get_class( $db ) ) != 'protectormysqldatabase';
if($test) {
	// DBFactory not patched - UNSAFE!
	$safeCheck = "<div class='coreMessage errorMsg'>\n";
		$safeCheck .= "<p>\n";
			$safeCheck .= "<h3>databasefactory.php: Not Patched (" ._AM_ADV_NOTSECURE . ")</h3>\n";
			$safeCheck .= _AM_ADV_DBFACTORYUNPATCHED. "\n";
		$safeCheck .= "</p>\n";
	$safeCheck .= "</div>\n";
} else {
	// We can only hope that this prefix is bueno - PASS.
	$safeCheck = "<div class='coreMessage confirmMsg'>\n";
		$safeCheck .= "<span>" . _AM_ADV_DBFACTORYPATCHED . " - <strong>Secure!</strong> :)</span>\n";
	$safeCheck .= "</div>\n";	
}
echo $safeCheck;




// open table for PROTECTION CHECK
$uri_contami = XOOPS_URL."/index.php?xoopsConfig%5Bnocommon%5D=1" ;
$uri_isocom = XOOPS_URL."/index.php?cid=".urlencode(",password /*") ;
$check = "<div class='coreMessage'>\n";
	$check .= "<p>\n";
		$check .= "<h3>" . _AM_ADV_SUBTITLECHECK . "</h3>\n";
		$check .= "<span>" . _AM_ADV_CHECKCONTAMI . ": <a href='" . $uri_contami . "' target='_blank'>" . $uri_contami . "</a></span><br />\n";
		$check .= "<span>" . _AM_ADV_CHECKISOCOM . ": <a href='" . $uri_isocom . "' target='_blank'>" . $uri_isocom . "</a></span>\n";
	$check .= "</p>\n";	
$check .= "</div>\n";
echo $check;

echo "</div>\n";
icms_cp_footer();
?>