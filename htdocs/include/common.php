<?php
// $Id: common.php 12400 2014-01-25 18:46:03Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //

/**
 * All common information used in the core goes from here.
 * Be careful while editing this file!
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package core
 * @version $Id: common.php 12400 2014-01-25 18:46:03Z skenow $
 */

/**
 * make sure mainfile is included, for security and functionality
 */
defined("XOOPS_MAINFILE_INCLUDED") or die();

// -- Include common functions and constants file
require_once ICMS_ROOT_PATH . "/include/constants.php";

// Load Composer autoloader - prefer trust path location for security.
// After installation, the vendor directory lives in ICMS_TRUST_PATH (outside
// the web root).  Fall back to ICMS_ROOT_PATH for pre-install or legacy setups.
$_icms_autoload_from_trustpath = false;
if (file_exists(ICMS_TRUST_PATH . "/vendor/autoload.php")) {
	$_icms_autoload = ICMS_TRUST_PATH . "/vendor/autoload.php";
	$_icms_autoload_from_trustpath = true;
} else {
	$_icms_autoload = ICMS_ROOT_PATH . "/vendor/autoload.php";
}
require_once $_icms_autoload;
unset($_icms_autoload);

// When vendor lives in the trust path the Composer-generated autoloader files
// compute $baseDir as dirname(dirname(__DIR__)) relative to trustpath/vendor/,
// which resolves to trustpath/ instead of the web root.  Every icms_* class
// lookup therefore targets trustpath/libraries/ – a directory that does not
// exist because libraries/ always stays in ICMS_ROOT_PATH.
//
// Register a prepended SPL autoloader (runs before Composer's now-broken one)
// that maps all three categories of ImpressCMS-native classes to the correct
// ICMS_ROOT_PATH/libraries location:
//
//   "icms"     (classmap entry)  →  libraries/icms.php
//   "icms_*"   (PSR-0 style)     →  libraries/<underscore/separated/path>.php
//   "Icms\*"   (PSR-4 style)     →  libraries/icms/<Namespace/Path>.php
if ($_icms_autoload_from_trustpath) {
	$_icms_root_lib = ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . "libraries";
	spl_autoload_register(
		static function (string $class) use ($_icms_root_lib): void {
			// Classmap: bare "icms" abstract base class → libraries/icms.php
			if ($class === "icms") {
				$file = $_icms_root_lib . DIRECTORY_SEPARATOR . "icms.php";
				if (is_file($file)) {
					require_once $file;
				}
				return;
			}
			// PSR-0: icms_core_DataFilter → libraries/icms/core/DataFilter.php
			if (strncmp($class, "icms_", 5) === 0) {
				$file =
					$_icms_root_lib .
					DIRECTORY_SEPARATOR .
					str_replace("_", DIRECTORY_SEPARATOR, $class) .
					".php";
				if (is_file($file)) {
					require_once $file;
				}
				return;
			}
			// PSR-4: Icms\Core\DataFilter → libraries/icms/Core/DataFilter.php
			if (strncmp($class, "Icms\\", 5) === 0) {
				$file =
					$_icms_root_lib .
					DIRECTORY_SEPARATOR .
					"icms" .
					DIRECTORY_SEPARATOR .
					str_replace("\\", DIRECTORY_SEPARATOR, substr($class, 5)) .
					".php";
				if (is_file($file)) {
					require_once $file;
				}
			}
		},
		true, // throw  (required SPL signature argument)
		true, // prepend – run BEFORE Composer's broken path resolution
	);
	unset($_icms_root_lib);
}
unset($_icms_autoload_from_trustpath);

include_once ICMS_INCLUDE_PATH . "/functions.php";
include_once ICMS_INCLUDE_PATH . "/debug_functions.php";
include_once ICMS_INCLUDE_PATH . "/version.php";

if (!isset($xoopsOption)) {
	$xoopsOption = [];
}

// load core language file before the initialization of the boot sequence
icms_loadLanguageFile("core", "theme");

// -- Initialize kernel and launch bootstrap
require_once ICMS_LIBRARIES_PATH . "/icms.php";
icms::setup();
icms::boot();

// -- Easiest ML by Gijoe (no longer needed here)

// Disable gzip compression if PHP is run under CLI mode or if multi-language is enabled
// To be refactored
if (
	empty($_SERVER["SERVER_NAME"]) ||
	substr(PHP_SAPI, 0, 3) == "cli" ||
	$GLOBALS["icmsConfigMultilang"]
) {
	$icmsConfig["gzip_compression"] = 0;
}

if (
	$icmsConfig["gzip_compression"] == 1 &&
	extension_loaded("zlib") &&
	!ini_get("zlib.output_compression")
) {
	ini_set("zlib.output_compression", true);
	if (ini_get("zlib.output_compression_level") < 0) {
		ini_set("zlib.output_compression_level", 6);
	}
	if (!zlib_get_coding_type()) {
		ini_set("zlib.output_compression", false);
		ob_start("ob_gzhandler");
	}
}

/*
 * This address the strict compliance for PHP 5.3/5.4, but the rest of our timezone handling
 * can be improved beyond this. ~skenow
 */
date_default_timezone_set(
	timezone_name_from_abbr("", $icmsConfig["default_TZ"] * 3600, 0),
);

// -- Include site-wide lang file
icms_loadLanguageFile("core", "global");
icms_loadLanguageFile("core", "core");
icms_loadLanguageFile("system", "common");
@define("_GLOBAL_LEFT", @_ADM_USE_RTL == 1 ? "right" : "left");
@define("_GLOBAL_RIGHT", @_ADM_USE_RTL == 1 ? "left" : "right");

// -- Include page-specific lang file
if (
	isset($xoopsOption["pagetype"]) &&
	false === strpos($xoopsOption["pagetype"], ".")
) {
	icms_loadLanguageFile("core", $xoopsOption["pagetype"]);
}

defined("XOOPS_USE_MULTIBYTES") or define("XOOPS_USE_MULTIBYTES", 0);

if (
	!empty($_POST["xoops_theme_select"]) &&
	in_array($_POST["xoops_theme_select"], $icmsConfig["theme_set_allowed"])
) {
	$icmsConfig["theme_set"] = $_POST["xoops_theme_select"];
	$_SESSION["xoopsUserTheme"] = $_POST["xoops_theme_select"];
} elseif (
	!empty($_POST["theme_select"]) &&
	in_array($_POST["theme_select"], $icmsConfig["theme_set_allowed"])
) {
	$icmsConfig["theme_set"] = $_POST["theme_select"];
	$_SESSION["xoopsUserTheme"] = $_POST["theme_select"];
} elseif (
	!empty($_SESSION["xoopsUserTheme"]) &&
	in_array($_SESSION["xoopsUserTheme"], $icmsConfig["theme_set_allowed"])
) {
	$icmsConfig["theme_set"] = $_SESSION["xoopsUserTheme"];
}

if ($icmsConfig["closesite"] == 1) {
	include ICMS_INCLUDE_PATH . "/site-closed.php";
}

icms::launchModule();

if ($icmsConfigPersona["multi_login"]) {
	if (is_object(icms::$user)) {
		$online_handler = icms::handler("icms_core_Online");
		$online_handler->write(
			icms::$user->getVar("uid"),
			icms::$user->getVar("uname"),
			time(),
			0,
			$_SERVER["REMOTE_ADDR"],
		);
	}
}

// -- finalize boot process
icms::$preload->triggerEvent("finishCoreBoot");
