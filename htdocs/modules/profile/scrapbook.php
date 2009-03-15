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

if ( !empty($_POST['mainscrap']) ) {
  // sendet formular
  if ( trim($_POST['mainscrap'])=='insert' ) {  //submit
     $profile_template = 'profile_scrapbook.html';
     include_once("header.php");
	 $controler = new ProfileControlerScraps($xoopsDB,$xoopsUser);
     $nbSections = $controler->getNumbersSections();
     if (!($GLOBALS['xoopsSecurity']->check())){
	   redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
	   exit();
     }
     $scrapbook_uid 	= intval($_POST['uid']);
     $scrap_text    	= $myts->displayTarea($_POST['text'],0,1,1,1,1);
     $mainform	   		= (!empty($_POST['mainform'])) ? 1 : 0;
     $scrap = $scraps_factory->create();
     $scrap->setVar('scrap_text',$scrap_text);
     $scrap->setVar('scrap_from',$xoopsUser->getVar('uid'));
     $scrap->setVar('scrap_to',$scrapbook_uid);
     $scraps_factory->insert($scrap);
     $extra_tags['X_OWNER_NAME'] =  $xoopsUser->getUnameFromId($scrapbook_uid);
     $extra_tags['X_OWNER_UID'] = $scrapbook_uid;
     $notification_handler =& xoops_gethandler('notification');
     $notification_handler->triggerEvent ("scrap", $xoopsUser->getVar('uid'), "new_scrap",$extra_tags);
     if ($mainform==1) {
	   redirect_header("scrapbook.php?uid=".$scrapbook_uid,1,_MD_PROFILE_SCRAP_SENT);
     } else {
	   redirect_header("scrapbook.php?uid=".$xoopsUser->getVar('uid'),1,_MD_PROFILE_SCRAP_SENT);
     }
	 exit();
  } elseif ( trim($_POST['mainscrap'])=='reply' ) {  //answer
    exit();
  } elseif ( trim($_POST['mainscrap'])=='delete' ) {  //delete    
    $scrap_id = intval($_POST['scrap_id']);
    $confirm = isset($_POST['confirm'])? intval($_POST['confirm']):'';
    if($confirm != 1) {
	  include_once "header.php";
	  xoops_confirm(array('scrap_id'=> $scrap_id,'confirm'=>1,'mainscrap'=>'delete'), 'scrapbook.php', _MD_PROFILE_ASKCONFIRMSCRAPDELETION, _MD_PROFILE_CONFIRMSCRAPDELETION);
	  include ICMS_ROOT_PATH.'/footer.php';
	  exit();
    } else {
	  $profile_template = 'profile_scrapbook.html';
	  include_once("header.php");

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

	  $controler = new ProfileControlerScraps($xoopsDB,$xoopsUser);
      $nbSections = $controler->getNumbersSections();
	  /**
	  * Creating the factory  and the criteria to delete the picture
	  * The user must be the owner
	  */
	  $criteria_scrap_id = new Criteria ('scrap_id',$scrap_id);
	  $uid = intval($xoopsUser->getVar('uid'));
	  $criteria_uid = new Criteria ('scrap_to',$uid);
	  $criteria = new CriteriaCompo ($criteria_scrap_id);
	  $criteria->add($criteria_uid);	
	  /**
	  * Try to delete  
	  */
	  if($scraps_factory->getCount($criteria)==1) {
		if($scraps_factory->deleteAll($criteria)) {
		  redirect_header('scrapbook.php?uid='.$uid, 2, _MD_PROFILE_SCRAPDELETED);
		} else {
		  redirect_header('scrapbook.php?uid='.$uid, 2, _MD_PROFILE_NOCACHACA);
		}
	  }
    }
	exit();
  }
}

$profile_template = 'profile_scrapbook.html';
include_once("header.php");
$controler = new ProfileControlerScraps($xoopsDB,$xoopsUser);  
$nbSections = $controler->getNumbersSections();
$criteria_uid = new Criteria('scrap_to',$controler->uidOwner);
$criteria_uid->setOrder('DESC');
$criteria_uid->setSort('date');

if(!($scraps = $controler->fecthScraps($nbSections['nbScraps'], $criteria_uid))) {$xoopsTpl->assign('lang_noscrapsyet',_MD_PROFILE_NOSCRAPSYET);}
 
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
 
//form
$xoopsTpl->assign('lang_entertext',_MD_PROFILE_ENTERTEXTSCRAP);
$xoopsTpl->assign('lang_submit',_MD_PROFILE_SENDSCRAP);
$xoopsTpl->assign('lang_cancel',_MD_PROFILE_CANCEL);

//scraps
$xoopsTpl->assign('scraps',$scraps);	
$xoopsTpl->assign('lang_answerscrap',_MD_PROFILE_ANSWERSCRAP);
$xoopsTpl->assign('lang_tips',_MD_PROFILE_SCRAPTIPS);
$xoopsTpl->assign('lang_bold',_MD_PROFILE_BOLD);
$xoopsTpl->assign('lang_italic',_MD_PROFILE_ITALIC);
$xoopsTpl->assign('lang_underline',_MD_PROFILE_UNDERLINE);

$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYSCRAPBOOK);
$xoopsTpl->assign('section_name',_MD_PROFILE_SCRAPBOOK);

include 'footer.php';
?>