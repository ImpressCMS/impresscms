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

include_once 'header.php';
global $icmsUser;
/*$friendship_level = isset($_POST['level'])?intval($_POST['level']):0;
$friend2_uid = isset($_POST['friend2_uid'])?intval($_POST['friend2_uid']):0;
$profile_friendship_handler = icms_getModuleHandler('friendship');
$uid = is_object($icmsUser)?intval($icmsUser->getVar('uid')):0;
if($friend2_uid > 0 && $uid > 0 && $uid != $friend2_uid){
	addFriendshipRequest($uid, $friend2_uid);
}

if($friendship_level != 0 && $uid > 0){
	respontFriendshipRequest($uid, $friendship_level);
}
*/
include_once 'footer.php';
?>