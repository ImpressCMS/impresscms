<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * Xoops header
 */
include_once 'header.php';
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
	$imblogging_tribes_handler = icms_getModuleHandler('tribes');
	$tribesObj = $imblogging_tribes_handler->get($com_itemid);
	if ($tribesObj && !$tribesObj->isNew()) {
		$com_replytext = 'test...';
		$bodytext = $tribesObj->getPostLead();
		if ($bodytext != '') {
			$com_replytext .= '<br /><br />'.$bodytext.'';
		}
		$com_replytitle = $tribesObj->getVar('title');
		include_once ICMS_ROOT_PATH .'/include/comment_new.php';
	}
}
?>