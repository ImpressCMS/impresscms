<?php
/**
 * Installer common include file
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright    The XOOPS project http://www.xoops.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package        installer
 * @since        Xoops 2.0.14
 * @author		Skalpa Keo <skalpa@xoops.org>
 */

/**
 * If non-empty, only this user can access this installer
 */
define('INSTALL_USER', '');
define('INSTALL_PASSWORD', '');

// options for mainfile.php
$xoopsOption['nocommon'] = true;
define('XOOPS_INSTALL', 1);

/* set the default timezone for date/time functions - for strict PHP 5.3/5.4
 * suppress errors, because we don't care
 * if it's not set, it will be set to UTC, which we would have defaulted, anyway
 */
date_default_timezone_set(@date_default_timezone_get());

/* we need this so we can use icms_core_Logger during the install to trap errors */
if (!defined('INSTALLER_INCLUDE_MAIN')) {
	if (!defined('ICMS_ROOT_PATH')) {
		define('ICMS_ROOT_PATH', dirname(dirname(__DIR__)));
	}
} else {
	require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "mainfile.php";
}
define('ICMS_PUBLIC_PATH', dirname(__DIR__));

require_once ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

include_once ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'version.php';
// including a few functions - relying more on the core, now
include_once ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
// installer common functions
require_once 'include/functions.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'xoopsinstallwizard.php';

$errorHandler = icms_core_Logger::instance();
error_reporting(E_ALL);

try {
	$env = new \Dotenv\Dotenv(ICMS_ROOT_PATH);
	$env->load();
} catch (Exception $ex) {
	// Reading .env file failed but that is not out business
}


$pageHasHelp = false;
$pageHasForm = false;

$wizard = new XoopsInstallWizard();
if (!$wizard->xoInit()) {
	exit();
}
session_start();

if (!@is_array($_SESSION['settings'])) {
	$_SESSION['settings'] = array();
}
