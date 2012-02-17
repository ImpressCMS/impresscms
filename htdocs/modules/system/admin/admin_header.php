<?php
/**
 *
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	System
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @version		$Id$
 */

if (!is_object(icms::$user) || !is_object(icms::$module) || !icms::$user->isAdmin(icms::$module->getVar('mid'))) {
	exit("Access Denied");
}

/* Normally, you would include cp_functions, cp_header.
 * Since this module loads all the object files as includes instead of directly, 
 * only include the things in cp_header not already included by admin.php - which is nothing!
 */

$module_dir = basename(dirname(dirname(__FILE__)));
include_once ICMS_MODULES_PATH . "/" . $module_dir . "/include/common.php";
if (!defined("CPANEL_ADMIN_URL")) define("CPANEL_ADMIN_URL", CPANEL_URL . "admin/");

icms_loadLanguageFile($module_dir, 'common');

if ($fct !== "") {
	$icms_admin_handler = icms_getModuleHandler($fct, $module_dir);
	icms_loadLanguageFile($module_dir, $fct, TRUE);
}

$filter_post = array(
    'uid' => 'int',
);

$filter_get = array(
    'uid' => 'int',
);

$op = "";

if (!empty($_GET)) {
    $clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
    extract($clean_GET);
}
if (!empty($_POST)) {
    $clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
    extract($clean_POST);
}
