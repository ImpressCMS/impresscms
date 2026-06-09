<?php
/**
 * Site-specific configuration file for multisite installations
 *
 * This file should be placed in: ICMS_TRUSTPATH/sites/{domain}/mainfile.php
 * where {domain} is the sanitized hostname (e.g., example.com, subdomain.example.com)
 *
 * Copy this template and configure it for each site in your multisite installation.
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @since		ImpressCMS 2.0
 * @version		$Id$
 */

if (!defined("XOOPS_MAINFILE_INCLUDED")) {
	define("XOOPS_MAINFILE_INCLUDED", 1);

	// ==========================================
	// PHYSICAL PATHS
	// ==========================================
	
	// XOOPS Physical Path
	// Physical path to your main XOOPS directory WITHOUT trailing slash
	// Example: define('XOOPS_ROOT_PATH', '/var/www/impresscms/htdocs');
	// This should typically point to the shared htdocs directory
	define('XOOPS_ROOT_PATH', '');

	// XOOPS Security Physical Path (Trust Path)
	// Physical path to your security XOOPS directory WITHOUT trailing slash.
	// Ideally outside your server WEB folder for enhanced security
	// Example: define('XOOPS_TRUST_PATH', '/var/www/impresscms-trust');
	define('XOOPS_TRUST_PATH', '');

	// ==========================================
	// SITE-SPECIFIC URL
	// ==========================================
	
	// XOOPS Virtual Path (URL)
	// Virtual path to your main XOOPS directory WITHOUT trailing slash
	// This should be the full URL for THIS specific site
	// Example: define('XOOPS_URL', 'https://example.com');
	// Example: define('XOOPS_URL', 'https://subdomain.example.com');
	define('XOOPS_URL', 'http://');

	// ==========================================
	// PATH SECURITY CHECK
	// ==========================================
	
	define('XOOPS_CHECK_PATH', 0);
	// Protect against external scripts execution if safe mode is not enabled
	if (XOOPS_CHECK_PATH && !@ini_get('safe_mode')) {
		if (function_exists('debug_backtrace')) {
			$xoopsScriptPath = debug_backtrace();
			if (!count($xoopsScriptPath)) {
				die("ImpressCMS path check: this file cannot be requested directly");
			}
			$xoopsScriptPath = $xoopsScriptPath[0]['file'];
		} else {
			$xoopsScriptPath = isset($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
		}
		if (DIRECTORY_SEPARATOR != '/') {
			// IIS6 may double the \ chars
			$xoopsScriptPath = str_replace(strpos($xoopsScriptPath, '\\\\', 2) ? '\\\\' : DIRECTORY_SEPARATOR, '/', $xoopsScriptPath);
		}
		if (strcasecmp(substr($xoopsScriptPath, 0, strlen(XOOPS_ROOT_PATH)), str_replace(DIRECTORY_SEPARATOR, '/', XOOPS_ROOT_PATH))) {
			exit("ImpressCMS path check: Script is not inside XOOPS_ROOT_PATH and cannot run.");
		}
	}

	// ==========================================
	// DATABASE CONFIGURATION
	// ==========================================
	
	// Database Type
	// Choose the database to be used (typically 'mysql')
	define('XOOPS_DB_TYPE', 'mysql');
 
	// Database Charset
	// Set the database charset if applicable
	if (defined('XOOPS_DB_CHARSET')) {
		die();
	}
	define('XOOPS_DB_CHARSET', 'utf8');

	// Table Prefix
	// This prefix will be added to all tables for this site
	// Each site can use a different prefix to share one database, or use separate databases
	// Example: 'icms_site1_', 'icms_site2_', or just 'icms'
	define('XOOPS_DB_PREFIX', 'icms');

	// Database Hostname
	// Hostname of the database server. If you are unsure, 'localhost' works in most cases.
	// You can use a different database server for each site if needed
	define('XOOPS_DB_HOST', 'localhost');

	// Database Username
	// Your database user account on the host
	// Each site can have its own database credentials
	define('XOOPS_DB_USER', '');

	// Database Password
	// Password for your database user account
	define('XOOPS_DB_PASS', '');

	// Database Name
	// The name of database on the host
	// Each site can use a separate database, or share one with different prefixes
	define('XOOPS_DB_NAME', '');

	// ==========================================
	// SECURITY
	// ==========================================
	
	// Password Salt Key
	// This salt will be appended to passwords in the icms_encryptPass() function.
	// Do NOT change this once your site is Live, doing so will invalidate everyone's password.
	// IMPORTANT: Use a DIFFERENT salt for each site in a multisite installation!
	define('XOOPS_DB_SALT', '');
	
	// Use persistent connection? (Yes=1 No=0)
	// Default is 'No' (0). 
	define('XOOPS_DB_PCONNECT', 0);

	// ==========================================
	// SITE-SPECIFIC DIRECTORIES (OPTIONAL)
	// ==========================================
	
	// Optional: Override default cache/upload/templates_c directories
	// By default, these use the shared directories in XOOPS_ROOT_PATH
	// Uncomment and configure these if you want site-specific data directories:
	
	// Site-specific cache directory
	// define('ICMS_CACHE_PATH', XOOPS_TRUST_PATH . '/sites/{domain}/cache');
	
	// Site-specific compiled templates directory
	// define('ICMS_COMPILE_PATH', XOOPS_TRUST_PATH . '/sites/{domain}/templates_c');
	
	// Site-specific uploads directory (path and URL)
	// define('ICMS_UPLOAD_PATH', XOOPS_TRUST_PATH . '/sites/{domain}/uploads');
	// define('ICMS_UPLOAD_URL', XOOPS_URL . '/uploads/{domain}');

	// ==========================================
	// OPTIONAL FEATURES
	// ==========================================
	
	// (optional) Physical path to script that logs database queries.
	// Example: define('ICMS_LOGGING_HOOK', XOOPS_ROOT_PATH . '/modules/foobar/logging_hook.php');
	define('ICMS_LOGGING_HOOK', '');

	// ==========================================
	// GROUP DEFINITIONS
	// ==========================================
	
	define("XOOPS_GROUP_ADMIN", "1");
	define("XOOPS_GROUP_USERS", "2");
	define("XOOPS_GROUP_ANONYMOUS", "3");

	// ==========================================
	// SECURITY: PROTECT SUPERGLOBALS
	// ==========================================
	
	foreach (array('GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS', '_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS', '_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES', 'xoopsDB', 'xoopsUser', 'xoopsUserId', 'xoopsUserGroups', 'xoopsUserIsAdmin', 'icmsConfig', 'xoopsOption', 'xoopsModule', 'xoopsModuleConfig', 'xoopsRequestUri') as $bad_global) {
		if (isset($_REQUEST[$bad_global])) {
			header('Location: ' . XOOPS_URL . '/');
			exit();
		}
	}

	// ==========================================
	// ICMS COMPATIBILITY DEFINES
	// ==========================================
	
	define('ICMS_GROUP_ADMIN', XOOPS_GROUP_ADMIN);
	define('ICMS_GROUP_USERS', XOOPS_GROUP_USERS);
	define('ICMS_GROUP_ANONYMOUS', XOOPS_GROUP_ANONYMOUS);
	define('ICMS_URL', XOOPS_URL);
	define('ICMS_TRUST_PATH', XOOPS_TRUST_PATH);
	define('ICMS_ROOT_PATH', XOOPS_ROOT_PATH);
	
	// ==========================================
	// LOAD COMMON BOOTSTRAP
	// ==========================================
	
	if (!isset($xoopsOption['nocommon']) && XOOPS_ROOT_PATH != '') {
		include XOOPS_ROOT_PATH . "/include/common.php";
	}
}
