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

$profile_template = 'profile_configs.html';
include_once("header.php");
$modname = basename( dirname( __FILE__ ) );


$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($xoopsUser)){
		$uid = $xoopsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/userinfo.php?uid='.$uid);
	exit();
}

/**
* Factories of tribes  
*/
$configs_factory = icms_getmodulehandler('configs', $modname, 'profile' );
$controler = new ProfileControlerConfigs($xoopsDB,$xoopsUser);  
$nbSections = $controler->getNumbersSections();

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
$xoopsTpl->assign('isfriend',$controler->isFriend);

//numbers
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
$xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
$xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']); 


if (!empty($_POST['button'])) {
  if (!($GLOBALS['xoopsSecurity']->check())){
	redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
  }
  $criteria = new Criteria('config_uid',$xoopsUser->getVar("uid"));
  if ($configs_factory->getCount($criteria)>0){
    $configs = $configs_factory->getObjects($criteria);
    $config = $configs[0];
    $config->unsetNew();
  } else {
    $config = $configs_factory->create();
  }

  $config->setVar('config_uid',$xoopsUser->getVar("uid"));
  if (isset($_POST['pic']))  $config->setVar('pictures',$_POST['pic']);
  if (isset($_POST['aud'])) $config->setVar('audio',$_POST['aud']);
  if (isset($_POST['vid'])) $config->setVar('videos',$_POST['vid']);
  if (isset($_POST['tribes'])) $config->setVar('tribes',$_POST['tribes']);
  if (isset($_POST['scraps'])) $config->setVar('scraps',$_POST['scraps']);
  if (isset($_POST['friends'])) $config->setVar('friends',$_POST['friends']);
  if (isset($_POST['profileContact'])) $config->setVar('profile_contact',$_POST['profileContact']);
  if (isset($_POST['gen'])) $config->setVar('profile_general',$_POST['gen']);
  if (isset($_POST['stat'])) $config->setVar('profile_stats',$_POST['stat']);
  if (!$configs_factory->insert($config)) {

  }
  redirect_header("configs.php?uid=".$xoopsUser->getVar("uid"),3,_MD_PROFILE_CONFIGSSAVE);
  exit();
}

$criteria = new Criteria('config_uid',$uid_owner);
if($configs_factory->getCount($criteria)>0) {
	$configs = $configs_factory->getObjects($criteria);
	$config = $configs[0];
	
	$pic = $config->getVar('pictures');
	$aud = $config->getVar('audio');
	$vid = $config->getVar('videos');
	$tri = $config->getVar('tribes');
	$scr = $config->getVar('scraps');
	$fri = $config->getVar('friends');
	$pcon = $config->getVar('profile_contact');
	$pgen = $config->getVar('profile_general');
	$psta = $config->getVar('profile_stats');
	
	$xoopsTpl->assign('pic',$pic);
	$xoopsTpl->assign('aud',$aud);
	$xoopsTpl->assign('vid',$vid);
	$xoopsTpl->assign('tri',$tri);
	$xoopsTpl->assign('scr',$scr);
	$xoopsTpl->assign('fri',$fri);
	$xoopsTpl->assign('pcon',$pcon);
	$xoopsTpl->assign('pgen',$pgen);
	$xoopsTpl->assign('psta',$psta);
}

//Owner data
$xoopsTpl->assign('uid_owner',$controler->uidOwner);
$xoopsTpl->assign('owner_uname',$controler->nameOwner);
$xoopsTpl->assign('isOwner',$controler->isOwner);
$xoopsTpl->assign('isanonym',$controler->isAnonym);
$xoopsTpl->assign('isfriend',$controler->isFriend);

//form
$xoopsTpl->assign('lang_whocan',_MD_PROFILE_WHOCAN);
$xoopsTpl->assign('lang_configtitle',_MD_PROFILE_CONFIGSTITLE);
$xoopsTpl->assign('lang_configprofilestats',_MD_PROFILE_CONFIGSPROFILESTATS);
$xoopsTpl->assign('lang_configprofilegeneral',_MD_PROFILE_CONFIGSPROFILEGENERAL);
$xoopsTpl->assign('lang_configprofilecontact',_MD_PROFILE_CONFIGSPROFILECONTACT);
$xoopsTpl->assign('lang_configfriends',_MD_PROFILE_CONFIGSFRIENDS);
$xoopsTpl->assign('lang_configscraps',_MD_PROFILE_CONFIGSSCRAPS);
$xoopsTpl->assign('lang_configsendscraps',_MD_PROFILE_CONFIGSSCRAPSSEND);
$xoopsTpl->assign('lang_configtribes',_MD_PROFILE_CONFIGSTRIBES);
$xoopsTpl->assign('lang_configaudio',_MD_PROFILE_CONFIGSAUDIOS); 
$xoopsTpl->assign('lang_configvideos',_MD_PROFILE_CONFIGSVIDEOS);
$xoopsTpl->assign('lang_configpictures',_MD_PROFILE_CONFIGSPICTURES);
$xoopsTpl->assign('lang_only_me',_MD_PROFILE_CONFIGSONLYME);
$xoopsTpl->assign('lang_only_friends',_MD_PROFILE_CONFIGSONLYEFRIENDS);
$xoopsTpl->assign('lang_only_users',_MD_PROFILE_CONFIGSONLYEUSERS);
$xoopsTpl->assign('lang_everyone',_MD_PROFILE_CONFIGSEVERYONE);

$xoopsTpl->assign('lang_cancel',_MD_PROFILE_CANCEL);

//scraps
//$xoopsTpl->assign('scraps',$scraps);
$xoopsTpl->assign('lang_answerscrap',_MD_PROFILE_ANSWERSCRAP);

//Owner data
$xoopsTpl->assign('uid_owner',$controler->uidOwner);
$xoopsTpl->assign('owner_uname',$controler->nameOwner);
$xoopsTpl->assign('isOwner',$controler->isOwner);
$xoopsTpl->assign('isanonym',$controler->isAnonym);


//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_CONFIGSTITLE);
$xoopsTpl->assign('section_name',_MD_PROFILE_CONFIGSTITLE);

include 'footer.php';
?>