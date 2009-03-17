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

include_once "../../mainfile.php";

$modname = basename( dirname( __FILE__ ) );
include_once ICMS_ROOT_PATH.'/modules/'.$modname.'/include/common.php';
include_once ICMS_ROOT_PATH.'/modules/'.$modname.'/class/controler.php';
icms_loadLanguageFile('core', 'user');
  $mod_handler =& xoops_gethandler('module');
  $mod_profile  =& $mod_handler->getByDirname($modname);
  $conf_handler =& xoops_gethandler('config');
  $moduleConfig   =& $conf_handler->getConfigsByCat(0, $mod_profile->getVar('mid'));
$album_factory 			= icms_getmodulehandler('images', $modname, 'profile' );
$visitors_factory 		= icms_getmodulehandler('visitors', $modname, 'profile' );
$videos_factory 			= icms_getmodulehandler('video', $modname, 'profile' );
$friendpetition_factory 		= icms_getmodulehandler('friendpetition', $modname, 'profile' );
$friendships_factory 		= icms_getmodulehandler('friendship', $modname, 'profile' );
$scraps_factory 		= icms_getmodulehandler('scraps', $modname, 'profile' );

$uid_owner=0;
$isOwner=0;
$isanonym =1;
$isfriend =0;

/**
* If anonym and uid not set then redirect to admins profile
* Else redirects to own profile
*/
if(empty($xoopsUser)) {
	$isanonym = 1;
	if(isset($_GET['uid'])) {
	  $uid_owner = intval($_GET['uid']);
	} else {
	  $uid_owner=0;
	}
	$isOwner = 0;
} else {
    $isanonym = 0;
	if( !empty($_GET['uid'])) {
	  $uid_owner = intval($_GET['uid']);
	  $isOwner = ($xoopsUser->getVar('uid')==$uid_owner) ? 1:0;
	} else {
	  $uid_owner = intval($xoopsUser->getVar('uid'));
	  $isOwner = 1;
	}
	
}
$myts =& MyTextSanitizer::getInstance();
if ($isanonym == 1 && $uid_owner==0) {
  $xoopsOption['template_main'] = 'profile_noindex.html';
  include_once(ICMS_ROOT_PATH."/header.php");
  $xoopsTpl->assign('module_name',$xoopsModule->getVar('name'));
  $xoopsTpl->assign('xoops_pagetitle',  sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar("name"), _GUESTS));
  $xoopsTpl->assign('profile_image','<img src="'.ICMS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/images/profile-start.gif" alt="'.$xoopsModule->getVar('name').'"/>');
  $xoopsTpl->assign('profile_content',_MI_PROFILE_MODULEDESC);
/**
* Adding to the module js and css of the lightbox and new ones
*/
$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/css/profile'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
if(ereg('msie', strtolower($_SERVER['HTTP_USER_AGENT']))) {$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/css/tabs-ie.css');}
$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/lightbox/css/lightbox.css');
$xoTheme->addScript(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/js/profile.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/prototype.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/scriptaculous.js?load=effects,builder');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/lightbox.js');
  $xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYPROFILE);

    if($moduleConfig['profile_social']==1){
  $xoopsTpl->assign('module_is_socialmode', true);
    }

  $xoopsTpl->assign('lang_home',_MD_PROFILE_HOME);
  $xoopsTpl->assign('lang_exprofile',_MD_EXTENDED_PROFILE);
  $xoopsTpl->assign('lang_photos',_MD_PROFILE_PHOTOS);
  $xoopsTpl->assign('lang_friends',_MD_PROFILE_FRIENDS);
  $xoopsTpl->assign('lang_audio',_MD_PROFILE_AUDIOS);
  $xoopsTpl->assign('lang_videos',_MD_PROFILE_VIDEOS);
  $xoopsTpl->assign('lang_scrapbook',_MD_PROFILE_SCRAPBOOK);
  $xoopsTpl->assign('lang_profile',_MD_PROFILE_PROFILE);
  $xoopsTpl->assign('lang_tribes',_MD_PROFILE_TRIBES);
  $xoopsTpl->assign('lang_configs',_MD_PROFILE_CONFIGSTITLE);
  $controler = new ProfileControlerIndex($xoopsDB,array());
  $nbSections = $controler->getNumbersSections();
  //permissions
  $xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
  $xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
  $xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
  $xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
  $xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
  $xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));
  $xoopsTpl->assign('uid_owner',0);
  $xoopsTpl->assign('owner_uname',_GUESTS);
  $xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
  $xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
  $xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
  $xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
  $xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
  $xoopsTpl->assign('nb_audio',$nbSections['nbAudio']); 
  include_once(ICMS_ROOT_PATH."/footer.php");
  exit();
}

if (!empty($profile_template)) $xoopsOption['template_main'] = $profile_template;
include_once(ICMS_ROOT_PATH."/header.php");
/**
* Adding to the module js and css of the lightbox and new ones
*/
$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/css/profile'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
if(ereg('msie', strtolower($_SERVER['HTTP_USER_AGENT']))) {$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/css/tabs-ie.css');}
$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/lightbox/css/lightbox.css');
$xoTheme->addScript(ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/js/profile.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/prototype.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/scriptaculous.js?load=effects,builder');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/lightbox/js/lightbox.js');

//navbar
$xoopsTpl->assign('module_name',$xoopsModule->getVar('name'));
    if($moduleConfig['profile_social']==1){
  $xoopsTpl->assign('module_is_socialmode', true);
    }

$xoopsTpl->assign('lang_home',_MD_PROFILE_HOME);
$xoopsTpl->assign('lang_exprofile', _MD_EXTENDED_PROFILE);
$xoopsTpl->assign('lang_photos',_MD_PROFILE_PHOTOS);
$xoopsTpl->assign('lang_friends',_MD_PROFILE_FRIENDS);
$xoopsTpl->assign('lang_audio',_MD_PROFILE_AUDIOS);
$xoopsTpl->assign('lang_videos',_MD_PROFILE_VIDEOS);
$xoopsTpl->assign('lang_scrapbook',_MD_PROFILE_SCRAPBOOK);
$xoopsTpl->assign('lang_profile',_MD_PROFILE_PROFILE);
$xoopsTpl->assign('lang_tribes',_MD_PROFILE_TRIBES);
$xoopsTpl->assign('lang_configs',_MD_PROFILE_CONFIGSTITLE);

//xoopsToken
$xoopsTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());


?>
