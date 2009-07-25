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

/**
 * Edit a Friendship
 *
 * @param object $friendshipObj ProfileFriendship object to be edited
*/
function editfriendship($friendshipObj, $hideForm=false)
{
	global $xoTheme, $icmsTpl, $icmsUser;

	$profile_friendship_handler = icms_getModuleHandler('friendship');
	$icmsTpl->assign('hideForm', $hideForm);
	$friend = ($icmsUser->uid()==$friendshipObj->getVar('friend1_uid'))?$friendshipObj->getVar('friend1_uid'):$friendshipObj->getVar('friend2_uid');
	if (!$friendshipObj->isNew()){
		if (!$friendshipObj->userCanEditAndDelete()) {
			redirect_header($friendshipObj->getItemLink(true), 3, _NOPERM);
		}
		$friendshipObj->hideFieldFromForm(array('creation_time', 'friend2_uid', 'friend1_uid'));
		$sform = $friendshipObj->getSecureForm(_MD_PROFILE_FRIENDSHIP_EDIT, 'addfriendship');
		$sform->assign($icmsTpl, 'profile_friendshipform');
		$icmsTpl->assign('profile_category_path',  icms_getLinkedUnameFromId($friend) . ' > ' . _EDIT);
	} else {
		if (!$profile_friendship_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$friendshipObj->setVar('friend1_uid', $icmsUser->uid());
		$friendshipObj->setVar('friend2_uid', $uid);
		$friendshipObj->setVar('creation_time', time());
		$friendshipObj->hideFieldFromForm(array('creation_time', 'friend2_uid', 'friend1_uid', 'situation'));
		$sform = $friendshipObj->getSecureForm(_MD_PROFILE_FRIENDSHIP_ADD, 'addfriendship');
		$sform->assign($icmsTpl, 'profile_friendshipform');
		$icmsTpl->assign('profile_category_path', _ADD_FRIEND);
	}

	$xoTheme->addStylesheet(ICMS_URL . '/modules/profile/module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
}


$profile_template = 'profile_friendship.html';
include_once 'header.php';

$profile_friendship_handler = icms_getModuleHandler('friendship');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser;
$clean_friendship_id = isset($_GET['friendship_id']) ? intval($_GET['friendship_id']) : 0 ;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : $real_uid ;
$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addfriendship','del','');

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
	case "mod":
		$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		if ($clean_friendship_id > 0 && $friendshipObj->isNew()) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editfriendship($friendshipObj);
		break;

	case "addfriendship":
        if (!$xoopsSecurity->check()) {
        	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
        }
         include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_friendship_handler);
		$controller->storeFromDefaultForm(_MD_PROFILE_FRIENDSHIP_CREATED, _MD_PROFILE_FRIENDSHIP_MODIFIED);
		break;

	case "del":
		$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		if (!$friendshipObj->userCanEditAndDelete()) {
			redirect_header($friendshipObj->getItemLink(true), 3, _NOPERM);
		}
		if (isset($_POST['confirm'])) {
		    if (!$xoopsSecurity->check()) {
		    	redirect_header($impresscms->urls['previouspage'], 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
		    }
		}
  	    include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_friendship_handler);
		$controller->handleObjectDeletionFromUserSide();
		$icmsTpl->assign('profile_category_path', $friendshipObj->getVar('title') . ' > ' . _DELETE);

		break;

	default:
		if($real_uid){
			$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
			editfriendship($friendshipObj, true);
		}
		if($clean_friendship_id > 0){
			$profile_friendship_handler->updateCounter($clean_friendship_id);
			$icmsTpl->assign('profile_single_friendship', $friendshipObj->toArray());
		}elseif($clean_uid > 0){
			$friendshipsArray = $profile_friendship_handler->getFriendships(false, false, $clean_uid);
			$icmsTpl->assign('profile_allfriendships', $friendshipsArray);
		}elseif($real_uid > 0){
			$friendshipsArray = $profile_friendship_handler->getFriendships(false, false, $real_uid);
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