<?php
/**
 * Extended User Profile
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	LICENSE.txt
 * @license	GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package	modules
 * @since	1.2
 * @author	Jan Pedersen
 * @author	Marcello Brandao <marcello.brandao@gmail.com>
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version	$Id$
 */

/**
 * Edit a Friendship
 *
 * @param object $friendshipObj ProfileFriendship object to be edited
*/
function editfriendship($friendshipObj, $uid=false, $hideForm=false) {
	global $profile_friendship_handler, $xoTheme, $icmsTpl, $icmsUser;

	$icmsTpl->assign('hideForm', $hideForm);
	$friend = ($icmsUser->uid()==$friendshipObj->getVar('friend2_uid'))?$friendshipObj->getVar('friend1_uid'):$friendshipObj->getVar('friend2_uid');
	if (!$friendshipObj->isNew()){
		if ($friendshipObj->userCanEditAndDelete()) {
			$friendshipObj->hideFieldFromForm(array('creation_time', 'friend2_uid', 'friend1_uid'));
			$sform = $friendshipObj->getSecureForm(_MD_PROFILE_FRIENDSHIP_EDIT, 'addfriendship');
			$sform->assign($icmsTpl, 'profile_friendshipform');
		}
	} else {
		if (!$profile_friendship_handler->userCanSubmit() || !$uid) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$friendshipObj->setVar('friend1_uid', $icmsUser->uid());
		$friendshipObj->setVar('friend2_uid', $uid);
		$friendshipObj->setVar('creation_time', time());
		$friendshipObj->hideFieldFromForm(array('creation_time', 'friend2_uid', 'friend1_uid', 'situation'));
		$sform = $friendshipObj->getSecureForm(_MD_PROFILE_FRIENDSHIP_ADD, 'addfriendship');
		$sform->assign($icmsTpl, 'profile_friendshipform');
	}
}

$profile_template = 'profile_index.html';
$profile_current_page = basename(__FILE__);
include_once 'header.php';

if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/userinfo.php?uid='.$uid);
	exit();
}

// log visitor
$profile_visitors_handler = icms_getModuleHandler('visitors');
$profile_visitors_handler->logVisitor($uid);

// Use a naming convention that indicates the source of the content of the variable
$clean_op = '';
if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

// Again, use a naming convention that indicates the source of the content of the variable
$clean_friendship_id = isset($_GET['friendship_id']) ? intval($_GET['friendship_id']) : 0 ;
$profile_friendship_handler = icms_getModuleHandler('friendship');
$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('addfriendship', '');

// Only proceed if the supplied operation is a valid operation
if (in_array($clean_op,$valid_op,true) && is_object($icmsUser)){
	switch ($clean_op) {
		case "addfriendship":
			if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
			}
			include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController($profile_friendship_handler);
			$controller->storeFromDefaultForm(_MD_PROFILE_FRIENDSHIP_CREATED, _MD_PROFILE_FRIENDSHIP_MODIFIED);
			break;
		default:
			if($icmsUser->uid() != $uid) {
				if (!getFriendship($icmsUser->uid(), $uid)) {
					$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
					editfriendship($friendshipObj, $uid, true);
				} else {
					$clean_friendship_id = $profile_friendship_handler->getFriendshipIdPerUser($icmsUser->uid(), $uid);
					$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
					editfriendship($friendshipObj, $uid, true);
				}
			} else {
				$clean_friendship_ids = $profile_friendship_handler->getFriendshipIdsWaiting($uid);
				foreach($clean_friendship_ids as $clean_friendship_id){
					$friendshipObj = $profile_friendship_handler->get($clean_friendship_id);
					editfriendship($friendshipObj, false, true);
				}
			}
		break;
	}
}

// passing language constants to smarty
icms_makeSmarty(array(
	'lang_aim'               => _US_AIM,
	'lang_basicInfo'         => _US_BASICINFO,
	'lang_contactinfo'       => _MD_PROFILE_CONTACTINFO,
	'lang_delete'            => _MD_PROFILE_DELETE,
	'lang_detailsinfo'       => _MD_PROFILE_USERDETAILS,
	'lang_editdesc'          => _MD_PROFILE_EDITDESC,
	'lang_editprofile'       => _MD_PROFILE_EDITPROFILE,
	'lang_email'             => _US_EMAIL,
	'lang_extrainfo'         => _US_EXTRAINFO,
	'lang_icq'               => _US_ICQ,
	'lang_interest'          => _US_INTEREST,
	'lang_lastlogin'         => _US_LASTLOGIN,
	'lang_location'          => _US_LOCATION,
	'lang_membersince'       => _US_MEMBERSINCE,
	'lang_more'              => _US_MOREABOUT,
	'lang_msnm'              => _US_MSNM,
	'lang_myinfo'            => _US_MYINFO,
	'lang_noavatar'          => _MD_PROFILE_NOAVATARYET,
	'lang_notregistered'     => _US_NOTREGISTERED,
	'lang_occupation'        => _US_OCCUPATION,
	'lang_posts'             => _US_POSTS,
	'lang_privmsg'           => _US_PM,
	'lang_rank'              => _US_RANK,
	'lang_realname'          => _US_REALNAME,
	'lang_selectavatar'      => _MD_PROFILE_SELECTAVATAR,
	'lang_signature'         => _US_SIGNATURE,
	'lang_statistics'        => _US_STATISTICS,
	'lang_uname'             => _US_NICKNAME,
	'lang_usercontributions' => _MD_PROFILE_USERCONTRIBUTIONS,
	'lang_visitors'          => _MD_PROFILE_VISITORS,
	'lang_website'           => _US_WEBSITE,
	'lang_yim'               => _US_YIM));

// passing user information to smarty
$icmsTpl->assign('uid_owner',$uid);
$icmsTpl->assign('section_name', _MD_PROFILE_PROFILE);
$icmsTpl->assign('user_uname', $thisUser->getVar('uname'));
$icmsTpl->assign('user_realname', $thisUser->getVar('name'));
$icmsTpl->assign('user_websiteurl', ($thisUser->getVar('url', 'E') != '') ? $myts->makeClickable(formatURL($thisUser->getVar('url', 'E'))) : '');
$icmsTpl->assign('user_icq', $thisUser->getVar('user_icq'));
$icmsTpl->assign('user_aim', $thisUser->getVar('user_aim'));
$icmsTpl->assign('user_yim', $thisUser->getVar('user_yim'));
$icmsTpl->assign('user_msnm', $thisUser->getVar('user_msnm'));
$icmsTpl->assign('user_location', $thisUser->getVar('user_from'));
$icmsTpl->assign('user_occupation', $thisUser->getVar('user_occ'));
$icmsTpl->assign('user_interest', $thisUser->getVar('user_intrest'));
$icmsTpl->assign('user_extrainfo', trim($thisUser->getVar('bio')) ? $myts->displayTarea($thisUser->getVar('bio', 'N'),0,1,1) : '');
$icmsTpl->assign('user_joindate', formatTimestamp($thisUser->getVar('user_regdate'), 's'));
$icmsTpl->assign('user_posts', $thisUser->getVar('posts'));
$icmsTpl->assign('user_signature', trim($thisUser->getVar('user_sig')) ? $myts->displayTarea($thisUser->getVar('user_sig', 'N'), 1, 1, 1) : '');
$icmsTpl->assign('user_email', ($thisUser->getVar('user_viewemail') == 1) ? $thisUser->getVar('email', 'E') : '');
$icmsTpl->assign('user_lastlogin', ($thisUser->getVar("last_login") != 0) ? formatTimestamp($thisUser->getVar("last_login"), "m") : '');
$userrank = $thisUser->rank();
$icmsTpl->assign('user_ranktitle', $userrank['title']);
if ($userrank['image']) {
	$icmsTpl->assign('user_rankimage', '<img src="'.ICMS_UPLOAD_URL.'/'.$userrank['image'].'" alt="" />');
}
if ($thisUser->getVar('user_avatar') && $thisUser->getVar('user_avatar') != 'blank.gif' && $thisUser->getVar('user_avatar') != ''){
	$icmsTpl->assign('user_avatar', ICMS_UPLOAD_URL.'/'.$thisUser->getVar('user_avatar'));
} elseif ($icmsConfigUser['avatar_allow_gravatar'] == 1) {
	$icmsTpl->assign('user_avatar', $thisUser->gravatar('G', $icmsConfigUser['avatar_width']));
	$icmsTpl->assign('gravatar', true);
}
$allow_avatar_upload = ($isOwner && is_object($icmsUser) && $icmsConfigUser['avatar_allow_upload'] == 1 && $icmsUser->getVar('posts') >= $icmsConfigUser['avatar_minposts']);
$icmsTpl->assign('allow_avatar_upload', $allow_avatar_upload);

// visitors
$visitors = $profile_visitors_handler->getVisitors(0, 5, $uid);
$rtn = array();
$i = 0;
foreach($visitors as $visitor) {
	$visitorUser =& $member_handler->getUser($visitor['uid_visitor']);
	$rtn[$i]['uid'] = $visitor['uid_visitor'];
	$rtn[$i]['uname'] = $visitorUser->getVar('uname');
	$rtn[$i]['time'] = $visitor['visit_time'];
	$i++;
}
$icmsTpl->assign('visitors', $rtn);
unset($visitors);

// getting user contributions
$gperm_handler = & xoops_gethandler('groupperm');
$groups = is_object($icmsUser) ? $icmsUser->getGroups() : ICMS_GROUP_ANONYMOUS;
$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo(new Criteria('hassearch', 1));
$criteria->add(new Criteria('isactive', 1));
$mids = array_keys($module_handler->getList($criteria));

foreach ($mids as $mid) {
  if ( $gperm_handler->checkRight('module_read', $mid, $groups)) {
	$module =& $module_handler->get($mid);
	$user_uid =$thisUser->getVar('uid');
	$results = $module->search('', '', 5, 0, $user_uid);
	$count = count($results);
	if (is_array($results) && $count > 0) {
		for ($i = 0; $i < $count; $i++) {
			if (isset($results[$i]['image']) && $results[$i]['image'] != '') {
				$results[$i]['image'] = 'modules/'.$module->getVar('dirname').'/'.$results[$i]['image'];
			} else {
				$results[$i]['image'] = 'images/icons/posticon2.gif';
			}
			
			if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
				$results[$i]['link'] = "modules/".$module->getVar('dirname')."/".$results[$i]['link'];
			}

			$results[$i]['title'] = $myts->displayTarea($results[$i]['title']);
			$results[$i]['time'] = $results[$i]['time'] ? formatTimestamp($results[$i]['time']) : '';
		}
		if ($count == 5) {
			$showall_link = '<a href="'.ICMS_URL.'/search.php?action=showallbyuser&amp;mid='.$mid.'&amp;uid='.$thisUser->getVar('uid').'">'._US_SHOWALL.'</a>';
		} else {
			$showall_link = '';
		}
		$icmsTpl->append('modules', array('name' => $module->getVar('name'), 'results' => $results, 'showall_link' => $showall_link));
	}
	unset($module);
  }
}

// getting social content
// pictures
$profile_pictures_handler = icms_getModuleHandler('pictures');
$pictures = $profile_pictures_handler->getPictures(0, 3, $uid);
$rtn = array();
$i = 0;
foreach($pictures as $picture) {
	$rtn[$i++]['content'] = $picture['picture_content'];
}
$icmsTpl->assign('pictures', $rtn);
unset($pictures);

// audio
$profile_audio_handler = icms_getModuleHandler('audio');
$audios = $profile_audio_handler->getAudios(0, 1, $uid);
$rtn = array();
foreach($audios as $audio) {
	$rtn['content'] = $audio['audio_content'];
}
$icmsTpl->assign('audio', $rtn);
unset($audios);

// friends
$friends = $profile_friendship_handler->getFriendship(0, 3, $uid);
$rtn = array();
$i = 0;
foreach($friends as $friend) {
	$rtn[$i]['user_avatar'] = $friend['friendship_avatar'];
	$rtn[$i]['uname'] = $friend['friendship_content'];
	$i++;
}
$icmsTpl->assign('friends', $rtn);
unset($friends);

// video
$profile_videos_handler = icms_getModuleHandler('videos');
$videos = $profile_videos_handler->getVideos(0, 1, $uid);
$rtn = array();
foreach($videos as $video) {
	$rtn['content'] = $video['video_content'];
}
$icmsTpl->assign('video', $rtn);
unset($videos);

// tribes
// get tribes where the user is the owner
$profile_tribes_handler = icms_getModuleHandler('tribes');
$tribes = $profile_tribes_handler->getTribes(0, 0, $uid, false, true);
$rtn = array();
$i = 0;
foreach($tribes as $tribe) {
	$rtn[$i]['title'] = $tribe['title'];
	$rtn[$i]['itemLink'] = $tribe['itemLink'];
	$i++;
}
unset($tribes);
// get tribes where the user is a member
$tribes = $profile_tribes_handler->getMembershipTribes($uid);
foreach($tribes as $tribe) {
	$rtn[$i]['title'] = $tribe['title'];
	$rtn[$i]['itemLink'] = $tribe['itemLink'];
	$i++;
}
// finally sort the array
usort($rtn, 'sortList');
$icmsTpl->assign('tribes', $rtn);
unset($tribes);

// Comments
include ICMS_ROOT_PATH.'/include/comment_view.php';
// Closing the page
include("footer.php");

function sortList($a, $b) {
	$a = strtolower($a['title']);
	$b = strtolower($b['title']);
	return ($a == $b) ? 0 : ($a < $b) ? -1 : +1;
}
?>