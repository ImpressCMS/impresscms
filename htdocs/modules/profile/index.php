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
$xoopsTpl->assign('uid_owner',$uid);
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYPROFILE);
$xoopsTpl->assign('section_name',_MD_PROFILE_PROFILE);
$member_handler =& xoops_gethandler('member');
$thisUser =& $member_handler->getUser($uid);

$xoopsTpl->assign('lang_viewallfriends',_MD_PROFILE_ALLFRIENDS);

$xoopsTpl->assign('lang_nofriendsyet',_MD_PROFILE_NOFRIENDSYET);

//search
$xoopsTpl->assign('lang_usercontributions',_MD_PROFILE_USERCONTRIBUTIONS);
$xoopsTpl->assign('lang_detailsinfo',_MD_PROFILE_USERDETAILS);
$xoopsTpl->assign('lang_contactinfo',_MD_PROFILE_CONTACTINFO);
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