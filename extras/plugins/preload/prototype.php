<?php
/**
 * Initiating Protptype Library
 *
 * This file is responsible for initiating the Prototype library
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		libraries
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: prototype.php 1742 2008-04-20 14:46:20Z malanciault $
 */

/**
 * Define these constants to specify weight. Only for demonstration purposes for now
 */
/*define(ICMSPRELOADPROTECTOR_STARTCOREBOOT, 2);
 define(ICMSPRELOADPROTECTOR_FINISHCOREBOOT, 10);
 */
class IcmsPreloadPrototype extends IcmsPreloadItem
{
	function eventStartOutputInit() {
		global $xoTheme;
		$xoTheme->addScript(ICMS_LIBRARIES_URL . '/prototype/prototype.js');
	}

	function eventAdminHeader() {
		$ret  = '<script type="text/javascript" src="'.ICMS_LIBRARIES_URL.'/prototype/prototype.js"></script>';

		echo $ret;
	}
}
?>