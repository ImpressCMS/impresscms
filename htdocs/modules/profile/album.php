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

$profile_template = 'profile_album.html';
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

$controler = new ProfileControlerPhotos($xoopsDB,$xoopsUser);
$nbSections = $controler->getNumbersSections();

/**
* This variable define the beggining of the navigation must b
* setted here so all calls to database will take this into account
*/
$start = (isset($_GET['start']))? intval($_GET['start']) : 0;

/**
* Filter for search pictures in database
*/
if($controler->isOwner==1) {
  $criteria_uid = new criteria('uid_owner', $controler->uidOwner);
} else {
	$criteria_private = new criteria('private',0);
	$criteria_uid2 = new criteria('uid_owner', intval($controler->uidOwner));
	$criteria_uid = new criteriaCompo($criteria_uid2);
	$criteria_uid->add($criteria_private);
}
$criteria_uid->setLimit($xoopsModuleConfig['picturesperpage']);
$criteria_uid->setStart($start);
if($xoopsModuleConfig['images_order']==1) {
	$criteria_uid->setOrder('DESC');
	$criteria_uid->setSort('cod_img');
}
/**
* Fetch pictures from factory
*/
$pictures_object_array = $controler->album_factory->getObjects($criteria_uid);
$criteria_uid->setLimit('');
$criteria_uid->setStart(0);

/**
* If there is no pictures in the album show in template lang_nopicyet
*/
if($nbSections['nbPhotos']==0) {
	$nopicturesyet = _MD_PROFILE_NOTHINGYET;
	$xoopsTpl->assign('lang_nopicyet',$nopicturesyet);
} else {
	/**
	* Lets populate an array with the dati from the pictures
	*/
	$i = 0;
	foreach($pictures_object_array as $picture)
	{
		$pictures_array[$i]['url'] = $picture->getVar('url','s');
		$pictures_array[$i]['desc'] = $picture->getVar('title','s');
		$pictures_array[$i]['cod_img'] = $picture->getVar('cod_img','s');
		$pictures_array[$i]['private'] = $picture->getVar('private','s');
		$xoopsTpl->assign('pics_array', $pictures_array);
		$i++;
	}
}

/**
* Show the form if it is the owner and he can still upload pictures
*/
$maxfilebytes = '';
if(!empty($xoopsUser)) {
	if(($controler->isOwner==1) && $xoopsModuleConfig['nb_pict']>$nbSections['nbPhotos']) 	{
		$maxfilebytes = $xoopsModuleConfig['maxfilesize'];
		$xoopsTpl->assign('maxfilebytes',$maxfilebytes);
		$xoopsTpl->assign('showForm','1');
	}
}

/**
* Let's get the user name of the owner of the album
*/
$owner = new XoopsUser($controler->uidOwner);
$identifier = $owner->getVar('uname');
$avatar = $owner->getVar('user_avatar');

/**
* Criando a barra de navegao caso tenha muitos amigos
*/
$barra_navegacao = new XoopsPageNav($nbSections['nbPhotos'],$xoopsModuleConfig['picturesperpage'],$start,'start','uid='.intval($controler->uidOwner));
$navegacao = $barra_navegacao->renderImageNav(2);

$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/lightbox/css/lightbox.css');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/prototype/prototype.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/scriptaculous/src/scriptaculous.js?load=effects,builder');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/lightbox.js');
/**
* Assigning smarty variables
*/
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

//numbers
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
$xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
$xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']);

//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYPHOTOS);
$xoopsTpl->assign('section_name',_MD_PROFILE_PHOTOS);

//page atributes
$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar('name'), $controler->nameOwner));
$xoopsTpl->assign('isanonym',$controler->isAnonym);

//form
$xoopsTpl->assign('lang_formtitle',_MD_PROFILE_SUBMIT_PIC_TITLE);
$xoopsTpl->assign('lang_selectphoto',_MD_PROFILE_SELECT_PHOTO);
$xoopsTpl->assign('lang_caption',_MD_PROFILE_CAPTION);
$xoopsTpl->assign('lang_uploadpicture',_MD_PROFILE_UPLOADPICTURE);
$xoopsTpl->assign('lang_youcanupload',sprintf(_MD_PROFILE_YOUCANUPLOAD,$maxfilebytes/1024));

//$xoopsTpl->assign('path_profile_uploads',$xoopsModuleConfig['link_path_upload']);
$xoopsTpl->assign('lang_max_nb_pict', sprintf(_MD_PROFILE_YOUCANHAVE,$xoopsModuleConfig['nb_pict']));
$xoopsTpl->assign('lang_delete',_MD_PROFILE_DELETE );
$xoopsTpl->assign('lang_editdesc',_MD_PROFILE_EDITDESC );
$xoopsTpl->assign('lang_nb_pict', sprintf(_MD_PROFILE_YOUHAVE,$nbSections['nbPhotos']));

$xoopsTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());
$xoopsTpl->assign('navegacao',$navegacao);
$xoopsTpl->assign('lang_avatarchange',_MD_PROFILE_AVATARCHANGE);
$xoopsTpl->assign('avatar_url',$avatar);

$xoopsTpl->assign('lang_setprivate',_MD_PROFILE_PRIVATIZE);
$xoopsTpl->assign('lang_unsetprivate',_MD_PROFILE_UNPRIVATIZE);

include ICMS_ROOT_PATH.'/include/comment_view.php';

include 'footer.php';
?>