<?php
/**
 * Common file of the module included on all pages of the module
 *
 * @copyright	ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		2.0
 * @category	icms
 * @package		Administration
 * @subpackage	System
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

if (!defined("CPANEL_DIRNAME")) define("CPANEL_DIRNAME", $modversion["dirname"] = basename(dirname(dirname(__FILE__))));
if (!defined("CPANEL_URL")) define("CPANEL_URL", ICMS_MODULES_URL . "/" . CPANEL_DIRNAME . "/");
if (!defined("CPANEL_ROOT_PATH")) define("CPANEL_ROOT_PATH", ICMS_MODULES_PATH . "/" . CPANEL_DIRNAME . "/");
if (!defined("CPANEL_IMAGES_URL")) define("CPANEL_IMAGES_URL", CPANEL_URL . "images/");
if (!defined("CPANEL_ADMIN_URL")) define("CPANEL_ADMIN_URL", CPANEL_URL . "admin/");

// Include the common language file of the module
icms_loadLanguageFile(CPANEL_DIRNAME, "common");