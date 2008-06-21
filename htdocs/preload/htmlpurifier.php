<?php
/**
* Initiating HTMLPurifier Library
*
* This file is responsible for initiating the HTML Purifier Library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package	libraries
* @since	1.1
* @author	marcan <marcan@impresscms.org>
* @version	$Id: htmlpurifier.php 2135 2008-05-11 12:11:23Z m0nty_ $
*/

/**
 * Define these constants to specify weight. Only for demonstration purposes for now
 */
/*define(ICMSPRELOADHTMLPURIFIER_STARTCOREBOOT, 2);
define(ICMSPRELOADHTMLPURIFIER_FINISHCOREBOOT, 10);
*/
class IcmsPreloadHTMLPurifier extends IcmsPreloadItem
{
	function eventStartCoreBoot() {
		$filename = ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.standalone.php';
		if (file_exists($filename)) {
			require $filename;
		}
	}
	
}
?>