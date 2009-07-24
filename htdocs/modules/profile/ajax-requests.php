<?php
/**
 * File supposed to work for ajax requests.
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.3
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

include_once '../../mainfile.php';

$friendship_level = isset($_POST['level'])?intval($_POST['level']):0;
$friend2_uid = isset($_POST['friend2_uid'])?intval($_POST['friend2_uid']):0;
$profile_friendship_handler = icms_getModuleHandler('friendship');
$uid = intval($icmsUser->getVar('uid'));
if($friend2_uid != 0 && $uid != $friend2_uid && is_object($icmsUser)){
	$friendship_id = $profile_friendship_handler->getFriendshipIdPerUser($uid, $friend2_uid);
	$clean_friendship_id = !empty($friendship_id[0]['friendship_id'])?$friendship_id[0]['friendship_id']:0;
	if($clean_friendship_id == 0){
	$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		$friendshipObj->setVar('friend2_uid', $friend2_uid);
		$profile_friendship_handler->insert($friendshipObj, false, true, $debug=true);
	}
}

if($friendship_level != 0 && is_object($icmsUser)){
	$friendship_id = $profile_friendship_handler->getFriendshipIdPerUser($uid);
	$clean_friendship_id = !empty($friendship_id[0]['friendship_id'])?$friendship_id[0]['friendship_id']:0;
	if($clean_friendship_id > 0){
	$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		$friendshipObj->setVar('situation', $friendship_level);
		$profile_friendship_handler->insert($friendshipObj, false, true, $debug=true);
	}
}





















?>