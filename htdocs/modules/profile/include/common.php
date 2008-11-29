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

if (!defined("ICMS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("SMARTPROFILE_DIRNAME") ){
	define("SMARTPROFILE_DIRNAME", basename(  dirname(  dirname( __FILE__ ) ) ));
}

if( !defined("SMARTPROFILE_URL") ){
	define("SMARTPROFILE_URL", ICMS_URL.'/modules/'.SMARTPROFILE_DIRNAME.'/');
}
if( !defined("SMARTPROFILE_ROOT_PATH") ){
	define("SMARTPROFILE_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'.SMARTPROFILE_DIRNAME.'/');
}

if( !defined("SMARTPROFILE_IMAGES_URL") ){
	define("SMARTPROFILE_IMAGES_URL", SMARTPROFILE_URL.'/images/');
}
include_once(ICMS_ROOT_PATH.'/modules/smartobject/include/functions.php');
$profile_isAdmin = smart_userIsAdmin();
?>