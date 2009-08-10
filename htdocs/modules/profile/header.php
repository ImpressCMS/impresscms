<?php
/**
 * Extended User Profile
 *
 * @copyright	   The ImpressCMS Project http://www.impresscms.org/
 * @license		 LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package		 modules
 * @since		   1.3
 * @author		  Marcello Brandao <marcello.brandao@gmail.com>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		 $Id: $
 */

include_once "../../mainfile.php";
$dirname = basename( dirname( __FILE__ ) );
$uid = isset($_GET['uid'])?intval($_GET['uid']):(!empty($icmsUser)?intval($icmsUser->getVar('uid')):0);
$isOwner = $isFriend = false ;
$isAnonym = true;
$owner_uname = !empty($icmsUser)?trim($icmsUser->getVar('uname')):_GUESTS;
if($uid > 0) {
	$isAnonym = empty($icmsUser)?true:false;
	$isOwner = (!empty($icmsUser) && $icmsUser->getVar('uid') == $uid)?true:false;
}
$xoopsOption['template_main'] = ($isAnonym == false && $uid > 0 && !empty($profile_template))?$profile_template:'profile_noindex.html';
include_once ICMS_ROOT_PATH."/header.php";
include_once ICMS_ROOT_PATH.'/modules/'.$dirname.'/include/common.php';
icms_loadLanguageFile('core', 'user');
global $icmsModuleConfig;
$myts =& MyTextSanitizer::getInstance();
$module_name = $icmsModule->getVar('name');
$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$dirname.'/assets/css/profile'.(@_ADM_USE_RTL == 1 ? '_rtl':'').'.css');
if(ereg('msie', strtolower($_SERVER['HTTP_USER_AGENT']))) {$xoTheme->addStylesheet(ICMS_URL.'/modules/'.$dirname.'/assets/css/tabs-ie.css');}
$xoTheme->addScript(ICMS_URL.'/modules/'.$dirname.'/assets/js/profile.js');
icms_makeSmarty(array(
	'module_name',$module_name,
	'xoops_pagetitle',  sprintf(_MD_PROFILE_PAGETITLE,$module_name, $owner_uname ),
	'profile_image','<img src="'.ICMS_URL.'/modules/'.$dirname.'/assets/images/profile-start.gif" alt="'.$module_name.'"/>',
	'profile_content',_MI_PROFILE_MODULEDESC,
	'module_is_socialmode', $icmsModuleConfig['profile_social']));

if($icmsModuleConfig['profile_social']){
	$profile_configs_handler = icms_getModuleHandler('configs');
	$permissions = array();
	
	$items = array('audio', 'pictures', 'friendship', 'scraps', 'videos', 'tribes', 'profile_contact', 'profile_stats', 'profile_general', 'profile_usercontributions');
	foreach($items as $item){
		$permissions = array_merge($permissions, array($item => getAllowedItems($item, $uid)));
	}
	foreach($permissions as $permission => $value){
		$xoopsTpl->assign('allow_'.$permission, $value);
	}
	$nbSections = $profile_configs_handler->geteachSectioncounts($uid);
	foreach($nbSections as $nbSection => $value){
		$xoopsTpl->assign('nb_'.$nbSection, $value);
	}
	icms_makeSmarty(array(
		'lang_mysection' => _MD_PROFILE_MYPROFILE,
		'lang_home' => _MD_PROFILE_HOME,
		'lang_exprofile' => _MD_EXTENDED_PROFILE,
		'lang_photos' => _MD_PROFILE_PHOTOS,
		'lang_friends' => _MD_PROFILE_FRIENDS,
		'lang_audio' => _MD_PROFILE_AUDIOS,
		'lang_videos' => _MD_PROFILE_VIDEOS,
		'lang_scrapbook' => _MD_PROFILE_SCRAPBOOK,
		'lang_profile' => _MD_PROFILE_PROFILE,
		'lang_tribes' => _MD_PROFILE_TRIBES,
		'isOwner' => $isOwner));
}
if ($isAnonym == true && $uid == 0) {
	include_once(ICMS_ROOT_PATH.'/modules/'.$dirname.'/footer.php');
	exit();
}
//Token
$icmsTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());

?>
