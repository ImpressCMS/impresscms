<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

require_once("../../../include/cp_header.php");

//include_once ICMS_ROOT_PATH.'/modules/smartobject/include/common.php';
define("SMARTOBJECT_ROOT_PATH", ICMS_ROOT_PATH.'/modules/smartobject/');
include_once(SMARTOBJECT_ROOT_PATH.'include/functions.php');

/**
 * Include the common language constants for the SmartObject Framework
 */
 if (!defined('SMARTOBJECT_COMMON_CONSTANTS')) {
	$common_file = SMARTOBJECT_ROOT_PATH . "language/" . $xoopsConfig['language'] . "/common.php";
	if (!file_exists($common_file)) {

		$common_file = SMARTOBJECT_ROOT_PATH . "language/english/common.php";
	}
	include_once($common_file);
	define('SMARTOBJECT_COMMON_CONSTANTS', true);
}
$admin_file = SMARTOBJECT_ROOT_PATH . "language/" . $xoopsConfig['language'] . "/admin.php";
if (!file_exists($admin_file)) {

    $admin_file = SMARTOBJECT_ROOT_PATH . "language/english/admin.php";
}
include_once($admin_file);

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(ICMS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}
?>
