<?php
/**
* Friendships page
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

$profile_template = 'profile_friendship.html';
include_once 'header.php';

$profile_friendship_handler = icms_getModuleHandler('friendship');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : $real_uid ;
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('');

$isAllowed = getAllowedItems('friendship', $clean_uid);
if (!$isAllowed) {
	redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
}
$xoopsTpl->assign('uid_owner',$uid);

/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	default:
		if($clean_uid > 0){
			$friendshipsArray = $profile_friendship_handler->getFriendship(false, false, $clean_uid);
			$icmsTpl->assign('profile_allfriendships', $friendshipsArray);
		}elseif($real_uid > 0){
			$friendshipsArray = $profile_friendship_handler->getFriendship(false, false, $real_uid);
			$icmsTpl->assign('profile_allfriendships', $friendshipsArray);
		}else{
			redirect_header(PROFILE_URL);
		}
		break;
	}
}
$icmsTpl->assign('profile_module_home', icms_getModuleName(true, true));

include_once 'footer.php';
?>