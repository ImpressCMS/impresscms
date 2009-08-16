<?php
/**
 * Extended User Profile
 *
 *
 * @copyright	   The ImpressCMS Project http://www.impresscms.org/
 * @license		 LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package		 modules
 * @since		   1.2
 * @author		  Jan Pedersen
 * @author		  The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		 $Id$
 */

/**
* Check a user's uname, email, password and password verification
*
* @param object $user {@link XoopsUser} to check
*
* @return string
*/
function userCheck($user)
{
	global $icmsConfigUser;
	$stop = '';
	if (!checkEmail($user->getVar('email'))) {
		$stop .= _PROFILE_MA_INVALIDMAIL;
	}
	foreach ($icmsConfigUser['bad_emails'] as $be) {
		if (!empty($be) && preg_match("/".$be."/i", $user->getVar('email'))) {
			$stop .= _PROFILE_MA_INVALIDMAIL;
			break;
		}
	}
	if (strrpos($user->getVar('email'),' ') > 0) {
		$stop .= _PROFILE_MA_EMAILNOSPACES.'<br />';
	}
	switch ($icmsConfigUser['uname_test_level']) {
		case 0:
		// strict
		$restriction = '/[^a-zA-Z0-9\_\-]/';
		break;
		case 1:
		// medium
		$restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
		break;
		case 2:
		// loose
		$restriction = '/[\000-\040]/';
		break;
	}
	if (strlen($user->getVar('uname')) > $icmsConfigUser['maxuname']) {
		$stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOLONG, $icmsConfigUser['maxuname'])."<br />";
	}
	if (strlen($user->getVar('uname')) < $icmsConfigUser['minuname']) {
		$stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOSHORT, $icmsConfigUser['minuname'])."<br />";
	}
	foreach ($icmsConfigUser['bad_unames'] as $bu) {
		if(empty($bu) ||$user->isAdmin()) continue;
		if (preg_match("/".$bu."/i", $user->getVar('uname'))) {
			$stop .= _PROFILE_MA_DISPLAYNAMERESERVED."<br />";
			break;
		}
	}

	if (strlen($user->getVar('login_name')) > $icmsConfigUser['maxuname']) {
		$stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOLONG, $icmsConfigUser['maxuname'])."<br />";
	}
	if (strlen($user->getVar('login_name')) < $icmsConfigUser['minuname']) {
		$stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOSHORT, $icmsConfigUser['minuname'])."<br />";
	}
	foreach ($icmsConfigUser['bad_unames'] as $bu) {
		if(empty($bu) ||$user->isAdmin()) continue;
		if (preg_match("/".$bu."/i", $user->getVar('login_name'))) {
			$stop .= _PROFILE_MA_DISPLAYNAMERESERVED."<br />";
			break;
		}
	}

	$member_handler =& xoops_gethandler('member');
	$display_criteria = new Criteria('uname', $user->getVar('uname'));
	if ($user->getVar('uid') > 0) {
		//existing user, so let's keep the user's own row out of this
		$display_criteria = new CriteriaCompo($display_criteria);

		$useriddisplay_criteria = new Criteria('uid', $user->getVar('uid'), '!=');

		$display_criteria->add($useriddisplay_criteria);
	}
	$display_count = $member_handler->getUserCount($display_criteria);
	unset($display_criteria);
	if ($display_count > 0) {
		$stop .= _PROFILE_MA_DISPLAYNAMETAKEN."<br />";
	}
	/*$displays_criteria = new Criteria('login_name', $user->getVar('login_name'));
	if ($user->getVar('uid') > 0) {
		//existing user, so let's keep the user's own row out of this
		$displays_criteria = new CriteriaCompo($displays_criteria);
	}
	$displays_count = $member_handler->getUserCount($displays_criteria);
	unset($displays_criteria);
	if ($displays_count > 0) {
		$stop .= _PROFILE_MA_LOGINNAMETAKEN."<br />";
	}*/
	if ( $user->getVar('login_name')) {
		$count_loginname = new Criteria('login_name', $user->getVar('login_name'));
		if ($user->getVar('uid') > 0) {
			//existing user, so let's keep the user's own row out of this
			$count_loginname = new CriteriaCompo($count_loginname);
			$count_loginname->add(new Criteria('uid', $user->getVar('uid'), '!='));
		}
		$count = $member_handler->getUserCount($count_loginname);
		unset($count_loginname);
		if ( $count > 0 ) {
			$stop .= _PROFILE_MA_LOGINNAMETAKEN."<br />";
		}
	}

	if ( $user->getVar('email')) {
		$count_criteria = new Criteria('email', $user->getVar('email'));
		if ($user->getVar('uid') > 0) {
			//existing user, so let's keep the user's own row out of this
			$count_criteria = new CriteriaCompo($count_criteria);
			$count_criteria->add(new Criteria('uid', $user->getVar('uid'), '!='));
		}
		$count = $member_handler->getUserCount($count_criteria);
		unset($count_criteria);
		if ( $count > 0 ) {
			$stop .= _PROFILE_MA_EMAILTAKEN."<br />";
		}
	}

	return $stop;
}

/**
* Check password - used when changing password
*
* @param string $uname username of the user changing password
* @param string $oldpass old password
* @param string $newpass new password
* @param string $vpass verification of new password (must be the same as $newpass)
*
* @return string
**/
function checkPassword($uname, $oldpass, $newpass, $vpass) {
	global $icmsConfigUser;
	$stop = "";
	$uname = trim($uname);
	if ($oldpass == "") {
		$stop .= _PROFILE_MA_ENTERPWD;
	}
	else {
		//check if $oldpass is correct
		$member_handler =& xoops_gethandler('member');
		if (!$member_handler->loginUser(addslashes($uname), addslashes($oldpass))) {
			$stop .= _PROFILE_MA_WRONGPASSWORD;
		}
	}
	if ( $newpass == '' || !$vpass || $vpass == '' ) {
		$stop .= _PROFILE_MA_ENTERPWD.'<br />';
	}
	if ( ($newpass != $vpass) ) {
		$stop .= _PROFILE_MA_PASSNOTSAME.'<br />';
	} elseif ( ($newpass != '') && (strlen($newpass) < $icmsConfigUser['minpass']) ) {
		$stop .= sprintf(_PROFILE_MA_PWDTOOSHORT,$icmsConfigUser['minpass'])."<br />";
	}
	return $stop;
}

function getAllowedItems($item, $uid){
	global $xoopsDB, $icmsUser, $profile_isAdmin, $icmsModuleConfig, $profile_current_page;
	$array = array();
	$count = 0;

	// all registrated users (administrators included) have to set their profile settings first
	if (is_object($icmsUser) && $profile_current_page != 'configs.php' && $icmsUser->getVar('uid') == $uid) {
		$sql = sprintf('SELECT COUNT(*) FROM %s WHERE config_uid = %u', $xoopsDB->prefix('profile_configs'), intval($uid));
		$result = $xoopsDB->query($sql);
		list($count) = $xoopsDB->fetchRow($result);
		if ( $count <= 0 ) {
			redirect_header(PROFILE_URL.'configs.php', 3, _PROFILE_MA_MAKE_CONFIG_FIRST);
			exit();
		}
	}

	if ($profile_isAdmin) {
		return true;
	}

	if ($icmsModuleConfig['profile_social'] == false){
		redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		exit();
	}
	
	$profile_configs_handler = icms_getModuleHandler('configs');
	$configs_id = $profile_configs_handler->getConfigIdPerUser($uid);
	$clean_configs_id = !empty($configs_id[0]['configs_id'])?$configs_id[0]['configs_id']:0;
	$configsObj = $profile_configs_handler->get($clean_configs_id);
	$accessability = $profile_configs_handler->userCanAccessSection($configsObj, $item, $uid);
	return $accessability;
}

function getFriendship($uid1, $uid2){
	global $xoopsDB;
	$count = 0;
	$sql = sprintf('SELECT COUNT(*) FROM %s WHERE ((friend1_uid = %u AND friend2_uid = %u) OR (friend2_uid = %u AND friend1_uid = %u))', $xoopsDB->prefix('profile_friendship'), intval($uid1), intval($uid2), intval($uid1), intval($uid2));
	$result = $xoopsDB->query($sql);
	list($count) = $xoopsDB->fetchRow($result);
	if ( $count > 0 ) {
		return true;
	}
	return false;
}

function addFriendshipRequest($uid, $friend2_uid){
	$friendship_id = $profile_friendship_handler->getFriendshipIdPerUser($uid, $friend2_uid);
	$clean_friendship_id = !empty($friendship_id[0]['friendship_id'])?$friendship_id[0]['friendship_id']:0;
	if($GLOBALS['xoopsSecurity']->check() && $clean_friendship_id == 0){
	$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		$friendshipObj->setVar('friend2_uid', $friend2_uid);
		$profile_friendship_handler->insert($friendshipObj, false, true, $debug=true);
	}
}

function respontFriendshipRequest($uid, $friendship_level){
	$friendship_id = $profile_friendship_handler->getFriendshipIdPerUser($uid);
	$clean_friendship_id = !empty($friendship_id[0]['friendship_id'])?$friendship_id[0]['friendship_id']:0;
	if($GLOBALS['xoopsSecurity']->check() && $clean_friendship_id > 0){
	$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
		$friendshipObj->setVar('situation', $friendship_level);
		$profile_friendship_handler->insert($friendshipObj, false, true, $debug=true);
	}
}

?>