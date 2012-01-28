<?php
/**
 *
 *
 * @category	icms
 * @package		Administration
 * @subpackage	System
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @version		$Id$
 */

include_once "../../../include/cp_header.php";
include_once ICMS_MODULES_PATH . "/" . basename(dirname(dirname(__FILE__))) . "/include/common.php";
if (!defined("CPANEL_ADMIN_URL")) define("CPANEL_ADMIN_URL", CPANEL_URL . "admin/");