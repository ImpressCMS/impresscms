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

$profile_template = 'profile_index.html';
include_once 'header.php';
$modname = basename( dirname( __FILE__ ) );
include_once ICMS_ROOT_PATH.'/modules/'.$modname.'/class/controler.php';

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($icmsUser)){
		$uid = $icmsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}

if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/userinfo.php?uid='.$uid);
	exit();
}

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;
$controler = new ProfileControler($xoopsDB,$icmsUser);
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

$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYPROFILE);
$xoopsTpl->assign('section_name',_MD_PROFILE_PROFILE);

//page atributes
$xoopsTpl->assign('xoops_pagetitle',  sprintf(_MD_PROFILE_PAGETITLE,$icmsModule->getVar("name"), $controler->nameOwner));


/**
 * Filter for new friend petition
 */
$petition=0;
if ($controler->isOwner == 1) {
	$criteria_uidpetition       = new criteria('petioned_uid',$controler->uidOwner);
	if ($newpetition = $controler->petitions_factory->getObjects($criteria_uidpetition)){
		$nb_petitions				= sizeof($newpetition);
		$petitioner_handler 		=& xoops_gethandler('member');
		$petitioner 				=& $petitioner_handler->getUser($newpetition[0]->getVar('petitioner_uid'));
		$petitioner_uid   			= $petitioner->getVar('uid');
		$petitioner_uname 			= $petitioner->getVar('uname');
		$petitioner_avatar			= $petitioner->getVar('user_avatar');
		if(!empty($petitioner_avatar) && "blank.gif" != $petitioner_avatar){
		    $petitioner_avatar = ICMS_UPLOAD_URL."/".$petitioner->getVar('user_avatar');
		    }elseif ($icmsConfigUser['avatar_allow_gravatar'] == 1) {
		    	$petitioner_avatar = $petitioner->gravatar('G', $icmsConfigUser['avatar_width']);
		    }

		$petition_id 				= $newpetition[0]->getVar('friendpet_id');
		$petition=1;
	}
}


/**
 * Criteria for mainvideo
 */
$criteria_uidvideo  = new criteria('uid_owner',$controler->uidOwner);
$criteria_mainvideo = new criteria('main_video',"1");
$criteria_video     = new criteriaCompo($criteria_mainvideo);
$criteria_video->add($criteria_uidvideo);
  $mainvideocode = '';
  $mainvideodesc = '';

if (($nbSections['nbVideos']>0) && ($videos = $controler->videos_factory->getObjects($criteria_video))){
  $mainvideocode = $videos[0]->getVar('youtube_code');
  $mainvideodesc = $videos[0]->getVar('video_desc');
}

/**
 * Friends 
 */    

$criteria_friends = new criteria('friend1_uid',$controler->uidOwner);
$friends = $controler->friendships_factory->getFriends(9, $criteria_friends);

$controler->visitors_factory->purgeVisits();
$evaluation = $controler->friendships_factory->getMoyennes($controler->uidOwner);

/**
 * Tribes 
 */    

$criteria_tribes = new criteria('rel_user_uid',$controler->uidOwner);
$tribes = $controler->reltribeusers_factory->getTribes(9, $criteria_tribes);

/**
 * Visitors 
 */  

if ($controler->isAnonym == 0){    
    /**
     * Fectching last visitors
     */
    if ($controler->uidOwner != $icmsUser->getVar('uid')){
    	$visitor_now = $controler->visitors_factory->create();
    	$visitor_now->setVar('uid_owner', $controler->uidOwner);
    	$visitor_now->setVar('uid_visitor',$icmsUser->getVar('uid'));
    	$visitor_now->setVar('uname_visitor',$icmsUser->getVar('uname'));
    	$controler->visitors_factory->insert($visitor_now);
    }
    $criteria_visitors = new criteria('uid_owner',  $controler->uidOwner);
    //$criteria_visitors->setLimit(5);
    $visitors_object_array = $controler->visitors_factory->getObjects($criteria_visitors);

    /**
     * Lets populate an array with the dati from visitors
     */  
    $i = 0;
    $visitors_array = array();
    foreach ($visitors_object_array as $visitor) {        
      $indice                         = $visitor->getVar("uid_visitor","s");
      $visitors_array[$indice]        = $visitor->getVar("uname_visitor","s");
      $i++;
    }
    
    $xoopsTpl->assign('visitors', $visitors_array);
    $xoopsTpl->assign('lang_visitors',_MD_PROFILE_VISITORS);
}

$avatar     = $controler->owner->getVar('user_avatar');
$member_handler =& xoops_gethandler('member');
$thisUser =& $member_handler->getUser($controler->uidOwner);



//permissions
$xoopsTpl->assign('allow_profile_contact',($controler->checkPrivilege('profile_contact'))?1:0);
$xoopsTpl->assign('allow_profile_general',($controler->checkPrivilege('profile_general'))?1:0);
$xoopsTpl->assign('allow_profile_stats',($controler->checkPrivilege('profile_stats'))?1:0);
$xoopsTpl->assign('lang_suspensionadmin',_MD_PROFILE_SUSPENSIONADMIN);
if ($controler->isSuspended==0){	
  $xoopsTpl->assign('isSuspended',0);	
  $xoopsTpl->assign('lang_suspend',_MD_PROFILE_SUSPENDUSER);
  $xoopsTpl->assign('lang_timeinseconds',_MD_PROFILE_SUSPENDTIME);
} else {	
  $xoopsTpl->assign('lang_unsuspend',_MD_PROFILE_UNSUSPEND);
  $xoopsTpl->assign('isSuspended',1);
  $xoopsTpl->assign('lang_suspended',_MD_PROFILE_USERSUSPENDED);	
}
if ($icmsUser && $icmsUser->isAdmin(1)){
  $xoopsTpl->assign('isWebmaster',"1");
}else{
  $xoopsTpl->assign('isWebmaster',"0");
}

//tribes
$xoopsTpl->assign('tribes',$tribes);
if ($nbSections['nbTribes']<=0){
  $xoopsTpl->assign('lang_notribesyet',_MD_PROFILE_NOTRIBESYET);
}
$xoopsTpl->assign('lang_viewalltribes',_MD_PROFILE_ALLTRIBES);

//evaluations
$xoopsTpl->assign('lang_fans',_MD_PROFILE_FANS);
$xoopsTpl->assign('nb_fans',$evaluation['sumfan']);
$xoopsTpl->assign('lang_trusty',_MD_PROFILE_TRUSTY);
$xoopsTpl->assign('trusty',$evaluation['mediatrust']);
$xoopsTpl->assign('trusty_rest',48-$evaluation['mediatrust']);
$xoopsTpl->assign('lang_sexy',_MD_PROFILE_SEXY);
$xoopsTpl->assign('sexy',$evaluation['mediahot']);
$xoopsTpl->assign('sexy_rest',48-$evaluation['mediahot']);
$xoopsTpl->assign('lang_cool',_MD_PROFILE_COOL);
$xoopsTpl->assign('cool',$evaluation['mediacool']);
$xoopsTpl->assign('cool_rest',48-$evaluation['mediacool']);

//petitions to become friend
if ($petition==1){
  $xoopsTpl->assign('lang_youhavexpetitions',sprintf(_MD_PROFILE_YOUHAVEXPETITIONS,$nb_petitions));
  $xoopsTpl->assign('petitioner_uid',$petitioner_uid);
  $xoopsTpl->assign('petitioner_uname',$petitioner_uname);
  $xoopsTpl->assign('petitioner_avatar',$petitioner_avatar);
  $xoopsTpl->assign('petition',$petition);
  $xoopsTpl->assign('petition_id',$petition_id);
  $xoopsTpl->assign('lang_rejected',_MD_PROFILE_UNKNOWNREJECTING);
  $xoopsTpl->assign('lang_accepted',_MD_PROFILE_UNKNOWNACCEPTING);
  $xoopsTpl->assign('lang_acquaintance',_MD_PROFILE_AQUAITANCE);
  $xoopsTpl->assign('lang_friend',_MD_PROFILE_FRIEND);
  $xoopsTpl->assign('lang_bestfriend',_MD_PROFILE_BESTFRIEND);
  $linkedpetioner = '<a href="index.php?uid='.$petitioner_uid.'">'.$petitioner_uname.'</a>';
  $xoopsTpl->assign('lang_askingfriend',sprintf(_MD_PROFILE_ASKINGFRIEND,$linkedpetioner));
}
$xoopsTpl->assign('lang_askusertobefriend',_MD_PROFILE_ASKBEFRIEND);


//Avatar and Main Video
$xoopsTpl->assign('avatar_url',$avatar);
$xoopsTpl->assign('lang_selectavatar',_MD_PROFILE_SELECTAVATAR);
$xoopsTpl->assign('lang_selectmainvideo',_MD_PROFILE_SELECTMAINVIDEO);
$xoopsTpl->assign('lang_noavatar',_MD_PROFILE_NOAVATARYET);
$xoopsTpl->assign('lang_nomainvideo',_MD_PROFILE_NOMAINVIDEOYET);

if ($nbSections['nbVideos']>0){
  $xoopsTpl->assign('mainvideocode',$mainvideocode);
  $xoopsTpl->assign('mainvideodesc',$mainvideodesc);
  $xoopsTpl->assign('width',$icmsModuleConfig['width_maintube']);// Falta configurar o tamnho do main nas configs e alterar no template
  $xoopsTpl->assign('height',$icmsModuleConfig['height_maintube']);
}
//friends
$xoopsTpl->assign('friends',$friends);
$xoopsTpl->assign('lang_friendstitle',  sprintf(_MD_PROFILE_FRIENDSTITLE,$controler->nameOwner));
$xoopsTpl->assign('lang_viewallfriends',_MD_PROFILE_ALLFRIENDS);

$xoopsTpl->assign('lang_nofriendsyet',_MD_PROFILE_NOFRIENDSYET);

//search
$xoopsTpl->assign('lang_usercontributions',_MD_PROFILE_USERCONTRIBUTIONS);
$xoopsTpl->assign('lang_detailsinfo',_MD_PROFILE_USERDETAILS);
$xoopsTpl->assign('lang_contactinfo',_MD_PROFILE_CONTACTINFO);
//$xoopsTpl->assign('path_profile_uploads',$icmsModuleConfig['link_path_upload']);
$xoopsTpl->assign('lang_max_nb_pict', sprintf(_MD_PROFILE_YOUCANHAVE,$icmsModuleConfig['nb_pict']));
$xoopsTpl->assign('lang_delete',_MD_PROFILE_DELETE );
$xoopsTpl->assign('lang_editdesc',_MD_PROFILE_EDITDESC );
$xoopsTpl->assign('lang_visitors',_MD_PROFILE_VISITORS);
$xoopsTpl->assign('lang_editprofile',_MD_PROFILE_EDITPROFILE);
$xoopsTpl->assign('user_uname', $thisUser->getVar('uname'));
$xoopsTpl->assign('user_realname', $thisUser->getVar('name'));
$xoopsTpl->assign('lang_uname', _US_NICKNAME);
$xoopsTpl->assign('lang_website', _US_WEBSITE);
$userwebsite = ($thisUser->getVar('url', 'E')!='') ? $myts->makeClickable(formatURL($thisUser->getVar('url', 'E'))) : '';
$xoopsTpl->assign('user_websiteurl',$userwebsite );
$xoopsTpl->assign('lang_email', _US_EMAIL);
$xoopsTpl->assign('lang_privmsg', _US_PM);
$xoopsTpl->assign('lang_icq', _US_ICQ);
$xoopsTpl->assign('user_icq', $thisUser->getVar('user_icq'));
$xoopsTpl->assign('lang_aim', _US_AIM);
$xoopsTpl->assign('user_aim', $thisUser->getVar('user_aim'));
$xoopsTpl->assign('lang_yim', _US_YIM);
$xoopsTpl->assign('user_yim', $thisUser->getVar('user_yim'));
$xoopsTpl->assign('lang_msnm', _US_MSNM);
$xoopsTpl->assign('user_msnm', $thisUser->getVar('user_msnm'));
$xoopsTpl->assign('lang_location', _US_LOCATION);
$xoopsTpl->assign('user_location', $thisUser->getVar('user_from'));
$xoopsTpl->assign('lang_occupation', _US_OCCUPATION);
$xoopsTpl->assign('user_occupation', $thisUser->getVar('user_occ'));
$xoopsTpl->assign('lang_interest', _US_INTEREST);
$xoopsTpl->assign('user_interest', $thisUser->getVar('user_intrest'));
$xoopsTpl->assign('lang_extrainfo', _US_EXTRAINFO);
$var = $thisUser->getVar('bio', 'N');
$xoopsTpl->assign('user_extrainfo', $myts->displayTarea( $var,0,1,1) );
$xoopsTpl->assign('lang_statistics', _US_STATISTICS);
$xoopsTpl->assign('lang_membersince', _US_MEMBERSINCE);
$var = $thisUser->getVar('user_regdate');
$xoopsTpl->assign('user_joindate', formatTimestamp( $var, 's' ) );
$xoopsTpl->assign('lang_rank', _US_RANK);
$xoopsTpl->assign('lang_posts', _US_POSTS);
$xoopsTpl->assign('lang_basicInfo', _US_BASICINFO);
$xoopsTpl->assign('lang_more', _US_MOREABOUT);
$xoopsTpl->assign('lang_myinfo', _US_MYINFO);
$xoopsTpl->assign('user_posts', $thisUser->getVar('posts'));
$xoopsTpl->assign('lang_lastlogin', _US_LASTLOGIN);
$date = $thisUser->getVar("last_login");
if (!empty($date)) {
    $xoopsTpl->assign('user_lastlogin', formatTimestamp($date,"m"));
}
$xoopsTpl->assign('lang_notregistered', _US_NOTREGISTERED);
$xoopsTpl->assign('lang_signature', _US_SIGNATURE);
$var = $thisUser->getVar('user_sig', 'N');
$xoopsTpl->assign('user_signature', $myts->displayTarea( $var, 1, 1, 1 ) );

if ($thisUser->getVar('user_viewemail') == 1) {
  $xoopsTpl->assign('user_email', $thisUser->getVar('email', 'E'));
} else {
  $xoopsTpl->assign('user_email', '&nbsp;');
}  

$xoopsTpl->assign('uname',$thisUser->getVar('uname'));
$xoopsTpl->assign('lang_realname', _US_REALNAME);
$xoopsTpl->assign('name',$thisUser->getVar('name'));

$gperm_handler = & xoops_gethandler( 'groupperm' );
$groups = is_object($icmsUser) ? $icmsUser->getGroups() : ICMS_GROUP_ANONYMOUS;
$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo(new Criteria('hassearch', 1));
$criteria->add(new Criteria('isactive', 1));
$mids = array_keys($module_handler->getList($criteria));


//userrank
$userrank = $thisUser->rank();
if ($userrank['image']) {
    $xoopsTpl->assign('user_rankimage', '<img src="'.ICMS_UPLOAD_URL.'/'.$userrank['image'].'" alt="" />');
}
$xoopsTpl->assign('user_ranktitle', $userrank['title']);

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
            $showall_link = '<a href="../../search.php?action=showallbyuser&amp;mid='.$mid.'&amp;uid='.$thisUser->getVar('uid').'">'._US_SHOWALL.'</a>';
        } else {
            $showall_link = '';
        }
        $xoopsTpl->append('modules', array('name' => $module->getVar('name'), 'results' => $results, 'showall_link' => $showall_link));
    }
    unset($module);
  }
}

/**
 * Closing the page
 */ 
include("footer.php");
?>