<?php
/**
* Installer common include file
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.0.14
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/

/**
 * If non-empty, only this user can access this installer
 */
define( 'INSTALL_USER', '' );
define( 'INSTALL_PASSWORD', '' );

// options for mainfile.php
$xoopsOption['nocommon'] = true;
define('XOOPS_INSTALL', 1);

@include_once '../mainfile.php';

error_reporting( E_ALL );

class XoopsInstallWizard {

	var $pages = array();
	var $titles = array();
	var $currentPage = 0;
	var $lastpage;
	var $secondlastpage;
	var $language = 'english';
	
	function xoInit() {
		if ( !$this->checkAccess() ) {
			return false;
		}
		if ( @empty( $_SERVER['REQUEST_URI'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
		}
		// Load the main language file
		$this->initLanguage( !@empty( $_COOKIE['xo_install_lang'] ) ? $_COOKIE['xo_install_lang'] : 'english' );
		// Setup pages
		$this->pages		= array(
			'langselect', 'start', 'modcheck',
			'pathsettings', 'dbsettings', 'configsave',
			'tablescreate', 'siteinit',
			'tablesfill', 'modulesinstall', 'end'
		);
		$this->lastpage = end($this->pages);
		$this->secondlastpage = $this->pages[count($this->pages) - 2];
		$this->pagesNames	= array(
			LANGUAGE_SELECTION, INTRODUCTION, CONFIGURATION_CHECK,
			PATHS_SETTINGS, DATABASE_CONFIG, CONFIG_SAVE,
			TABLES_CREATION, INITIAL_SETTINGS, 
			DATA_INSERTION, MODULES_INSTALL, WELCOME
		);
		$this->pagesTitles	= array(
			LANGUAGE_SELECTION_TITLE, INTRODUCTION_TITLE, CONFIGURATION_CHECK_TITLE,
			PATHS_SETTINGS_TITLE, DATABASE_CONFIG_TITLE, CONFIG_SAVE_TITLE,
			TABLES_CREATION_TITLE, INITIAL_SETTINGS_TITLE,
			DATA_INSERTION_TITLE, MODULES_INSTALL_TITLE, WELCOME_TITLE
		);
		
		$this->setPage(0);
		// Prevent client caching
		header( "Cache-Control: no-store, no-cache, must-revalidate", false );
		header( "Pragma: no-cache" );
		return true;
	}
	
	function checkAccess() {
		if ( INSTALL_USER != '' && INSTALL_PASSWORD != '' ) {
		    if (!isset($_SERVER['PHP_AUTH_USER']) ) {
		        header('WWW-Authenticate: Basic realm="XOOPS Installer"');
		        header('HTTP/1.0 401 Unauthorized');
		        echo 'You can not access this XOOPS installer.';
		        return false;
		    }
	        if( INSTALL_USER != '' && $_SERVER['PHP_AUTH_USER'] != INSTALL_USER) {
	            header('HTTP/1.0 401 Unauthorized');
	            echo 'You can not access this XOOPS installer.';
	            return false;
	        }
	        if( INSTALL_PASSWD != $_SERVER['PHP_AUTH_PW'] ){
	            header('HTTP/1.0 401 Unauthorized');
	            echo 'You can not access this XOOPS installer.';
	            return false;
		    }
		}
		return true;
	}
	
	function loadLangFile( $file ) {
		if ( file_exists( "./language/$this->language/$file.php" ) ) {
			include_once "./language/$this->language/$file.php";
		} else {
			include_once "./language/english/$file.php";
		}
	}
	
	
	function initLanguage( $language ) {
		//echo $language;
		if ( !file_exists( "./language/$language/install.php" ) ) {
			$language = 'english';
		}
    	$this->language = $language;
		$this->loadLangFile( 'install' );
	}

	function setPage( $page ) {
		if ( (int)$page && $page >= 0 && $page < count($this->pages) ) {
			$this->currentPageName = $this->pages[ $page ];
			$this->currentPage = $page;
		} elseif ( false !== ( $index = array_search( $page, $this->pages ) ) ) {
			$this->currentPageName = $page;
			$this->currentPage = $index;
		} else {
			return false;
		}
		return $this->currentPage;
	}
	
	function baseLocation() {
		$proto	= ( @$_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
		$host	= $_SERVER['HTTP_HOST'];
		$base	= substr( $_SERVER['PHP_SELF'], 0, strrpos( $_SERVER['PHP_SELF'], '/' ) );
		return "$proto://$host$base";
	}

	function pageURI( $page ) {	
		if ( !(int)$page{0} ) {
			if ( $page{0} == '+' ) {
				$page = $this->currentPage + substr( $page, 1 );
			} elseif ( $page{0} == '-' ) {
				$page = $this->currentPage - substr( $page, 1 );
			} else {
				$page = (int)array_search( $page, $this->pages );
			}
		}
		$page = $this->pages[$page ];
		return $this->baseLocation() . "/page_$page.php";
	}

	function redirectToPage( $page, $status = 303, $message = 'See other' ) {
		$location = $this->pageURI( $page );
		$proto = !@empty($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header( "$proto $status $message" );
		//header( "Status: $status $message" );
		header( "Location: $location" );
	}
	

}

if ( ini_get( 'magic_quotes_gpc' ) ) {
	@array_walk( $_GET, 'stripslashes' );
	@array_walk( $_POST, 'stripslashes' );
	@array_walk( $_REQUEST, 'stripslashes' );
}


$pageHasHelp = false;
$pageHasForm = false;

   
$wizard =& new XoopsInstallWizard();
if ( !$wizard->xoInit() ) {
	exit();
}
session_start();

if ( !@is_array( $_SESSION['settings'] ) ) {
	$_SESSION['settings'] = array();
}


?>