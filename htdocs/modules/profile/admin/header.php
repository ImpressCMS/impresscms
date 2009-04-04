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

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(ICMS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}
?>
