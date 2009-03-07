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

$profile_template = 'profile_edittribe.html';
include_once 'header.php';
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

$controler = new ProfileControlerTribes($xoopsDB,$xoopsUser);
$nbSections = $controler->getNumbersSections();

$tribe_id = intval($_POST['tribe_id']);
$marker = (!empty($_POST['marker'])) ? intval($_POST['marker']) : 0;
$criteria= new criteria('tribe_id',$tribe_id);
$tribes = $controler->tribes_factory->getObjects($criteria);
$tribe = $tribes[0];

$uid = $xoopsUser->getVar('uid');

if($marker==1 && $tribe->getVar('owner_uid')==$uid) {
	$title = trim(htmlspecialchars($_POST['title']));
	$desc = $_POST['desc'];
	$img = $_POST['img'];
	$updateImg = ($_POST['flag_oldimg']==1)?0:1;
	
	$path_upload = ICMS_ROOT_PATH.'/uploads';
	$maxfilebytes = $xoopsModuleConfig['maxfilesize'];
	$maxfileheight = $xoopsModuleConfig['max_original_height'];
	$maxfilewidth = $xoopsModuleConfig['max_original_width'];
	$controler->tribes_factory->receiveTribe($title,$desc,$img,$path_upload,$maxfilebytes,$maxfilewidth,$maxfileheight,$updateImg,$tribe);
	redirect_header('tribes.php?uid='.$uid,3,_MD_PROFILE_TRIBEEDITED);
} else {
	/**
	* Render a form with the info of the user  
	*/
	$tribe_members = $controler->reltribeusers_factory->getUsersFromTribe($tribe_id,0,50);
	$xoopsTpl->assign('tribe_members', $tribe_members);
	$maxfilebytes = $xoopsModuleConfig['maxfilesize'];
	$xoopsTpl->assign('lang_savetribe',_MD_PROFILE_UPLOADTRIBE);
	$xoopsTpl->assign('maxfilesize',$maxfilebytes);
	$xoopsTpl->assign('tribe_title', $tribe->getVar('tribe_title'));
	$xoopsTpl->assign('tribe_desc', $tribe->getVar('tribe_desc'));
	$xoopsTpl->assign('tribe_img', $tribe->getVar('tribe_img'));
	$xoopsTpl->assign('tribe_id', $tribe->getVar('tribe_id'));
	
	//permissions
    $xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
    $xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
    $xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
    $xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
    $xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
    $xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));
    
	$xoopsTpl->assign('lang_membersoftribe', _MD_PROFILE_MEMBERSDOFTRIBE);
	$xoopsTpl->assign('lang_edittribe', _MD_PROFILE_EDIT_TRIBE);
	$xoopsTpl->assign('lang_tribeimage', _MD_PROFILE_TRIBE_IMAGE);
	$xoopsTpl->assign('lang_keepimage', _MD_PROFILE_MAINTAINOLDIMAGE);
	$xoopsTpl->assign('lang_youcanupload',sprintf(_MD_PROFILE_YOUCANUPLOAD,$maxfilebytes/1024));
	$xoopsTpl->assign('lang_titletribe', _MD_PROFILE_TRIBE_TITLE);
	$xoopsTpl->assign('lang_desctribe', _MD_PROFILE_TRIBE_DESC);
	
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
	$xoopsTpl->assign('lang_mysection',_MD_PROFILE_TRIBES.' :: '._MD_PROFILE_EDIT_TRIBE);
	$xoopsTpl->assign('section_name',_MD_PROFILE_TRIBES.' > '._MD_PROFILE_EDIT_TRIBE);
	
	//page atributes
	$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar('name'), $controler->nameOwner));
	
	//$xoopsTpl->assign('path_profile_uploads',$xoopsModuleConfig['link_path_upload']);
	$xoopsTpl->assign('lang_owner',_MD_PROFILE_TRIBEOWNER);
	
}

include 'footer.php';
?>