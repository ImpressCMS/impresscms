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

include '../../mainfile.php';
/*
 * Hack by felix for ampersand
 */
/*if ($xoopsUser) {
    header('location: userinfo.php?uid='.$xoopsUser->getVar('uid'));
}
else {
    header('location: register.php');
}*/
include 'header.php';
$xoopsOption['template_main'] = 'profile_userlist.html';
include(ICMS_ROOT_PATH.'/header.php');
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$user_handler =& icms_getmodulehandler( 'user', basename( dirname( __FILE__ ) ), 'profile' );

$criteria = new CriteriaCompo();
if(!(is_object($xoopsUser) && $xoopsUser->isAdmin())){
	if(is_object($xoopsUser)){
		$groups = $xoopsUser->getGroups();
	}else{
		$groups = array(3);
	}
	$allowed_groups = array();
	foreach($groups as $groupid){
		if(isset($xoopsModuleConfig['view_group_'.$groupid]) && $xoopsModuleConfig['view_group_'.$groupid][0] != ''){
			$allowed_groups = array_merge($allowed_groups, $xoopsModuleConfig['view_group_'.$groupid]);
		}
	}
	$allowed_groups = array_unique($allowed_groups);
	$criteria->add(new Criteria('groupid', '('.implode(', ', $allowed_groups).')', 'IN'));
}
$real_total_items = intval($user_handler->getCount($criteria));

$criteria->setStart($start);
$criteria->setLimit($xoopsModuleConfig['perpage']);
$usersObj =& $user_handler->getObjects($criteria, true);

$uArray = array();
	$wh = '';
if($xoopsModuleConfig['index_avatar'] && $xoopsModuleConfig['index_avatar_height'] && $xoopsModuleConfig['index_avatar_width']){
	$wh = "width='".$xoopsModuleConfig['index_avatar_width']."' height=".$xoopsModuleConfig['index_avatar_height']."'";
}
foreach($usersObj as $uid => $userObj){
	if($xoopsModuleConfig['index_avatar'] && $userObj->user_avatar() != ''){

		$avatar = "<img ".$wh." src='".ICMS_URL."/uploads/".$userObj->user_avatar()."'/>";
		$uArray[$uid]['avatar'] = $avatar;
		unset($avatar);
	}
	if($xoopsModuleConfig['index_real_name'] == 'real'){
		$uArray[$uid]['uname'] = $userObj->getVar('name') != '' ? $userObj->getVar('name') : $userObj->uname();
	}elseif($xoopsModuleConfig['index_real_name'] == 'both'){
		$uArray[$uid]['uname'] = $userObj->getVar('name') != '' ? $userObj->getVar('name').' ('.$userObj->uname().')' : $userObj->uname();
	}else{
		$uArray[$uid]['uname'] = $userObj->uname();
	}
}
$xoopsTpl->assign('u_array', $uArray);
$xoopsTpl->assign('avatar', $xoopsModuleConfig['index_avatar']);
$xoopsTpl->assign('avatar_height', $xoopsModuleConfig['index_avatar_height']);
$xoopsTpl->assign('avatar_width', $xoopsModuleConfig['index_avatar_width']);
include_once ICMS_ROOT_PATH.'/class/pagenav.php';
$pagenav = new XoopsPageNav($real_total_items, $xoopsModuleConfig['perpage'], $start, 'start', '');
$xoopsTpl->assign('navbar', '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>');
include(ICMS_ROOT_PATH.'/footer.php');
?>