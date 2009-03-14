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

$profile_template = 'profile_video.html';
include_once 'header.php';

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($xoopsUser)){
		$uid = $xoopsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}
$modname = basename( dirname( __FILE__ ) );

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/userinfo.php?uid='.$uid);
	exit();
}

$controler = new ProfileVideoControler($xoopsDB,$xoopsUser);
$nbSections = $controler->getNumbersSections();

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;

/**
* Criteria for Videos
*/
$criteriaUidVideo  = new criteria('uid_owner',$controler->uidOwner);
$criteriaUidVideo->setStart($start);
$criteriaUidVideo->setLimit($xoopsModuleConfig['videosperpage']);

/**
* Get all videos of this user and assign them to template
*/

$videos = $controler->getVideos($criteriaUidVideo);
$videos_array = $controler->assignVideoContent($nbSections['nbVideos'],$videos);

if(is_array($videos_array)) {
  $xoopsTpl->assign('videos', $videos_array);
} else {
  $xoopsTpl->assign('lang_novideoyet',_MD_PROFILE_NOVIDEOSYET);
}

$pageNav = $controler->VideosNavBar($nbSections['nbVideos'], $xoopsModuleConfig['videosperpage'],$start,2);

//permissions
$xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
$xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));

//Owner data
$xoopsTpl->assign('uid_owner',$controler->uidOwner);
$xoopsTpl->assign('owner_uname',$controler->nameOwner);
$xoopsTpl->assign('isOwner',$controler->isOwner);
$xoopsTpl->assign('isanonym',$controler->isAnonym);

//numbers
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
$xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
$xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']);

//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYVIDEOS);
$xoopsTpl->assign('section_name',_MD_PROFILE_VIDEOS);

//page atributes
$xoopsTpl->assign('xoops_pagetitle',  sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar('name'), $controler->nameOwner));

//form actions
$xoopsTpl->assign('lang_delete',_MD_PROFILE_DELETE );
$xoopsTpl->assign('lang_editdesc',_MD_PROFILE_EDITDESC );
$xoopsTpl->assign('lang_makemain',_MD_PROFILE_MAKEMAIN);

//FORM SUBMIT
$xoopsTpl->assign('lang_addvideos',_MD_PROFILE_ADDFAVORITEVIDEOS);
$xoopsTpl->assign('lang_youtubecodeLabel',_MD_PROFILE_YOUTUBECODE);
$xoopsTpl->assign('lang_captionLabel',_MD_PROFILE_CAPTION);
$xoopsTpl->assign('lang_submitValue',_MD_PROFILE_ADDVIDEO);


$xoopsTpl->assign('width',$xoopsModuleConfig['width_tube']);
$xoopsTpl->assign('height',$xoopsModuleConfig['height_tube']);
$xoopsTpl->assign('lang_videohelp',_MD_PROFILE_ADDVIDEOSHELP);

//Videos NAvBAr
$xoopsTpl->assign('pageNav',$pageNav);

include 'footer.php';
?>