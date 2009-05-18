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

$profile_template = 'profile_audio.html';
include_once 'header.php';

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($icmsUser)){
		$uid = $icmsUser->getVar('uid');
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

$controler = new ProfileAudioControler($xoopsDB,$icmsUser);
$nbSections = $controler->getNumbersSections();

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;

/**
* Criteria for Audio
*/
$criteriaUidAudio = new criteria('uid_owner',$controler->uidOwner);
$criteriaUidAudio->setStart($start);
$criteriaUidAudio->setLimit($icmsModuleConfig['audiosperpage']);

/**
* Get all audios of this user and assign them to template
*/
$audios = $controler->getAudio($criteriaUidAudio);
$audios_array = $controler->assignAudioContent($nbSections['nbAudio'],$audios);

if(is_array($audios_array)) {
	$xoopsTpl->assign('audios', $audios_array);
	$audio_list = '';
	foreach($audios_array as $audio_item) {
	  $audio_list .= '../../uploads/'.$modname.'/mp3/'.$audio_item['url'].' | ';
	}
	//$audio_list = substr($audio_list,-2);
	$xoopsTpl->assign('audio_list',$audio_list);
} else {
  $xoopsTpl->assign('lang_noaudioyet',_MD_PROFILE_NOAUDIOYET);
}

$pageNav = $controler->AudiosNavBar($nbSections['nbAudio'], $icmsModuleConfig['audiosperpage'],$start,2);

//meta language names
$xoopsTpl->assign('lang_meta',_MD_PROFILE_META);
$xoopsTpl->assign('lang_title',_MD_PROFILE_META_TITLE);
$xoopsTpl->assign('lang_album',_MD_PROFILE_META_ALBUM);
$xoopsTpl->assign('lang_artist',_MD_PROFILE_META_ARTIST);
$xoopsTpl->assign('lang_year',_MD_PROFILE_META_YEAR);

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
$xoopsTpl->assign('lang_mysection',_MI_PROFILE_MYAUDIOS);
$xoopsTpl->assign('section_name',_MD_PROFILE_AUDIOS);
//page atributes
$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_PROFILE_PAGETITLE,$icmsModule->getVar('name'), $controler->nameOwner));

//form actions
$xoopsTpl->assign('lang_delete',_MD_PROFILE_DELETE );
$xoopsTpl->assign('lang_editdesc',_MD_PROFILE_EDITDESC );
$xoopsTpl->assign('lang_makemain',_MD_PROFILE_MAKEMAIN);

//FORM SUBMIT
$xoopsTpl->assign('lang_selectaudio',_MD_PROFILE_SELECTAUDIO);
$xoopsTpl->assign('lang_authorLabel',_MD_PROFILE_AUTHORAUDIO);
$xoopsTpl->assign('lang_titleLabel',_MD_PROFILE_TITLEAUDIO);
$xoopsTpl->assign('lang_submitValue',_MD_PROFILE_SUBMITAUDIO);
$xoopsTpl->assign('lang_addaudios',_MD_PROFILE_ADDAUDIO);

$xoopsTpl->assign('width',$icmsModuleConfig['width_tube']);
$xoopsTpl->assign('height',$icmsModuleConfig['height_tube']);
$xoopsTpl->assign('player_from_list',_MD_PROFILE_PLAYER);

$xoopsTpl->assign('lang_audiohelp',sprintf(_MD_PROFILE_ADDAUDIOHELP,($icmsModuleConfig['maxfilesize'])/1024));
$xoopsTpl->assign('max_youcanupload',$icmsModuleConfig['maxfilesize']);
//Videos NAvBAr
$xoopsTpl->assign('pageNav',$pageNav);

include 'footer.php';
?>