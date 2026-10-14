<?php
/**
 * Installer common include file
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package installer
 * @since 2.0.14
 * @author Skalpa Keo <skalpa@xoops.org>
 * @version $Id: common.inc.php 12389 2014-01-17 16:58:21Z skenow $
 */

/**
 * If non-empty, only this user can access this installer
 */
define("INSTALL_USER", "");
define("INSTALL_PASSWORD", "");

// options for mainfile.php
$xoopsOption["nocommon"] = true;
define("XOOPS_INSTALL", 1);

/*
 * set the default timezone for date/time functions - for strict PHP 5.3/5.4
 * suppress errors, because we don't care
 * if it's not set, it will be set to UTC, which we would have defaulted, anyway
 */
date_default_timezone_set(@date_default_timezone_get());

/*
 * Start the session early so that subsequent pages can determine where the
 * Composer vendor directory lives after page_movevendor.php has moved it
 * from the web root into the trust path.  The session must be started before
 * any output is sent – all includes below are definition-only files, so this
 * is safe.
 */
session_start();
if (!@is_array($_SESSION["settings"])) {
	$_SESSION["settings"] = [];
}

include_once "../include/version.php";
// including a few functions - core
include_once "../include/functions.php";
// installer common functions
require_once "include/functions.php";
include_once "./class/IcmsInstallWizard.php";

/*
 * Load the Composer autoloader, from the first location that has one (see
 * include/host.php): the trust path (once the host files are installed), the
 * staging directory trust_dist/ shipped in the package, or the web root.
 *
 * The trust path comes from the session (page_pathsettings.php) or, once it is
 * written, from mainfile.php; this covers page_end.php, where the session is
 * often already cleared or expired.
 */
require_once "../include/host.php";
$_icms_trust_path = !empty($_SESSION["settings"]["TRUST_PATH"])
	? $_SESSION["settings"]["TRUST_PATH"]
	: icms_host_trust_path(dirname(__DIR__));
$_icms_vendor_autoload = icms_host_vendor_autoload(dirname(__DIR__), $_icms_trust_path);

if ($_icms_vendor_autoload === null) {
	header("Content-Type: text/plain; charset=utf-8");
	echo "ImpressCMS Installer – Autoloader not found\n\n";
	echo "The Composer autoloader (vendor/autoload.php) could not be found. ";
	echo "Upload the complete ImpressCMS package, including the trust_dist/ directory, and retry.\n";
	exit(1);
}

require_once $_icms_vendor_autoload;
// The Icms\ library, its legacy icms_* names and the icms class, from the web root.
require_once dirname(__DIR__) . "/libraries/Autoloader.php";
unset($_icms_trust_path, $_icms_vendor_autoload);

error_reporting(E_ALL);

$pageHasHelp = false;
$pageHasForm = false;

$wizard = new IcmsInstallWizard();
if (!$wizard->xoInit()) {
	exit();
}
// NOTE: session_start() has been moved to the top of this file (before the
// autoloader require) so that the trust-path detection above can read the
// session.  Do NOT call session_start() again here.
