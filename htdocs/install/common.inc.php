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
 * Load the Composer autoloader.
 *
 * The vendor directory starts in the web root (htdocs/vendor) but may have
 * been moved to the trust path by page_movevendor.php.  Rather than relying
 * on a session flag (which can be lost on session expiry or direct page
 * access), we probe both locations in priority order and use whichever one
 * actually exists on disk.
 *
 * Trust path resolution order (first non-empty value wins):
 *   1. $_SESSION['settings']['TRUST_PATH']  – set by page_pathsettings.php.
 *   2. XOOPS_TRUST_PATH extracted from ../mainfile.php via regex – available
 *      from page_configsave.php onward; this covers page_end.php where the
 *      session is often already cleared or expired.
 *
 * Autoloader probe order:
 *   1. <trustpath>/vendor/autoload.php  – used after the vendor move.
 *   2. <webroot>/vendor/autoload.php    – used before the move.
 *
 * If neither location has an autoload.php we emit a clear error instead of
 * letting require_once throw an opaque fatal.
 */
$_icms_vendor_autoload = null;
$_icms_vendor_from_trustpath = false;

// ── Step 1: resolve the trust path ───────────────────────────────────────────
$_icms_trust_path = !empty($_SESSION["settings"]["TRUST_PATH"])
	? $_SESSION["settings"]["TRUST_PATH"]
	: null;

if ($_icms_trust_path === null) {
	// Session is empty (expired, direct page access, or end-of-install cleanup).
	// Try to read XOOPS_TRUST_PATH from the already-written mainfile.php.
	$_icms_mainfile = __DIR__ . "/../mainfile.php";
	if (file_exists($_icms_mainfile)) {
		$_icms_mf_content = @file_get_contents($_icms_mainfile);
		if (
			$_icms_mf_content !== false &&
			preg_match(
				"/define\s*\(\s*['\"]XOOPS_TRUST_PATH['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/",
				$_icms_mf_content,
				$_icms_mf_match,
			)
		) {
			$_icms_trust_path = $_icms_mf_match[1];
		}
		unset($_icms_mf_content, $_icms_mf_match);
	}
	unset($_icms_mainfile);
}

// ── Step 2: probe trust-path vendor first ────────────────────────────────────
if (!empty($_icms_trust_path)) {
	$_icms_candidate =
		rtrim(str_replace("\\", "/", $_icms_trust_path), "/") .
		"/vendor/autoload.php";

	if (file_exists($_icms_candidate)) {
		$_icms_vendor_autoload = $_icms_candidate;
		$_icms_vendor_from_trustpath = true;
	}
	unset($_icms_candidate);
}

// ── Step 3: fall back to web-root vendor ─────────────────────────────────────
if ($_icms_vendor_autoload === null) {
	$_icms_candidate = __DIR__ . "/../vendor/autoload.php";
	if (file_exists($_icms_candidate)) {
		$_icms_vendor_autoload = $_icms_candidate;
	}
	unset($_icms_candidate);
}

// ── Step 4: nothing found – emit a human-readable error ──────────────────────
if ($_icms_vendor_autoload === null) {
	$__locations = [__DIR__ . "/../vendor/autoload.php"];
	if (!empty($_icms_trust_path)) {
		array_unshift(
			$__locations,
			rtrim(str_replace("\\", "/", $_icms_trust_path), "/") .
				"/vendor/autoload.php",
		);
	}
	header("Content-Type: text/plain; charset=utf-8");
	echo "ImpressCMS Installer – Autoloader not found\n\n";
	echo "The Composer autoloader (vendor/autoload.php) could not be found ";
	echo "in any of the expected locations:\n";
	foreach ($__locations as $__loc) {
		echo "  • " . $__loc . "\n";
	}
	echo "\nIf you moved the vendor directory manually, make sure it exists ";
	echo "at one of the paths listed above.\n";
	unset($__locations);
	exit(1);
}

unset($_icms_trust_path);

require_once $_icms_vendor_autoload;
unset($_icms_vendor_autoload);

/*
 * After the vendor directory is moved to the trust path, the Composer-generated
 * autoloader files (vendor/composer/autoload_psr0.php, autoload_psr4.php,
 * autoload_classmap.php, autoload_static.php) contain paths derived from
 * dirname(dirname(__DIR__)) which now resolves to trustpath/ instead of htdocs/.
 * This means every lookup for icms_* / Icms\ classes targets trustpath/libraries,
 * which does not exist – the libraries folder always stays in htdocs/libraries.
 *
 * We register a prepended SPL autoloader (runs BEFORE Composer's now-broken one)
 * that maps icms_* (PSR-0 underscore convention), Icms\ (PSR-4 namespace
 * convention), and the bare "icms" classmap entry to the correct absolute path
 * of htdocs/libraries.
 *
 * The trigger is $_icms_vendor_from_trustpath (set above) rather than the
 * VENDOR_MOVED session flag so this works even when the session flag is absent.
 */
if ($_icms_vendor_from_trustpath) {
	$__icms_fallback_lib = realpath(__DIR__ . "/../libraries");
	if ($__icms_fallback_lib !== false) {
		spl_autoload_register(
			static function (string $class) use ($__icms_fallback_lib): void {
				// Classmap: the bare "icms" abstract base class (libraries/icms.php)
				if ($class === "icms") {
					$file =
						$__icms_fallback_lib . DIRECTORY_SEPARATOR . "icms.php";
					if (is_file($file)) {
						require_once $file;
					}
					return;
				}

				// PSR-0: icms_core_Password  →  libraries/icms/core/Password.php
				if (strncmp($class, "icms_", 5) === 0) {
					$file =
						$__icms_fallback_lib .
						DIRECTORY_SEPARATOR .
						str_replace("_", DIRECTORY_SEPARATOR, $class) .
						".php";
					if (is_file($file)) {
						require_once $file;
					}
					return;
				}

				// PSR-4: Icms\Core\Password  →  libraries/icms/Core/Password.php
				if (strncmp($class, "Icms\\", 5) === 0) {
					$file =
						$__icms_fallback_lib .
						DIRECTORY_SEPARATOR .
						"icms" .
						DIRECTORY_SEPARATOR .
						str_replace(
							"\\",
							DIRECTORY_SEPARATOR,
							substr($class, 5),
						) .
						".php";
					if (is_file($file)) {
						require_once $file;
					}
				}
			},
			true, // throw  (required SPL signature argument)
			true, // prepend – run BEFORE Composer's broken autoloader
		);
	}
	unset($__icms_fallback_lib);
}
unset($_icms_vendor_from_trustpath);

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
