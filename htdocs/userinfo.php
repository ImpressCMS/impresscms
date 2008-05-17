<?php
/**
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by stranger <stranger@impresscms.ir>
* @version		$Id$
*/
/** Displays user profile
 * @package kernel
 * @subpackage users
 */
/**
 *
 */    
$xoopsOption['pagetype'] = 'user';
include 'mainfile.php';

include_once ICMS_ROOT_PATH.'/class/module.textsanitizer.php';

include_once ICMS_ROOT_PATH . '/modules/system/constants.php';

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
if( !$xoopsConfigUser['allow_annon_view_prof'] && !is_object($xoopsUser) ){
	redirect_header('index.php', 3, _NOPERM);
	exit();
}

$uid = intval($_GET['uid']);
if ($uid <= 0) {
    redirect_header('index.php', 3, _US_SELECTNG);
    exit();
}

$gperm_handler = & xoops_gethandler( 'groupperm' );
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

$isAdmin = $gperm_handler->checkRight( 'system_admin', XOOPS_SYSTEM_USER, $groups);         // isadmin is true if user has 'edit users' admin rights

if (is_object($xoopsUser)) {
    if ($uid == intval($xoopsUser->getVar('uid'))) {
        $xoopsOption['template_main'] = 'system_userinfo.html';
        include ICMS_ROOT_PATH.'/header.php';
        $xoopsTpl->assign('user_ownpage', true);
        $xoopsTpl->assign('lang_editprofile', _US_EDITPROFILE);
        $xoopsTpl->assign('lang_avatar', _US_AVATAR);
        $xoopsTpl->assign('lang_inbox', _US_INBOX);
        $xoopsTpl->assign('lang_logout', _US_LOGOUT);
        if ($xoopsConfigUser['self_delete'] == 1) {
            $xoopsTpl->assign('user_candelete', true);
            $xoopsTpl->assign('lang_deleteaccount', _US_DELACCOUNT);
        } else {
            $xoopsTpl->assign('user_candelete', false);
        }
        $thisUser =& $xoopsUser;
    } else {
        $member_handler =& xoops_gethandler('member');
        $thisUser =& $member_handler->getUser($uid);
        if (!is_object($thisUser) || !$thisUser->isActive() ) {
            redirect_header("index.php",3,_US_SELECTNG);
            exit();
        }
        $xoopsOption['template_main'] = 'system_userinfo.html';
        include ICMS_ROOT_PATH.'/header.php';
        $xoopsTpl->assign('user_ownpage', false);
    }
} else {
    $member_handler =& xoops_gethandler('member');
    $thisUser =& $member_handler->getUser($uid);
    if (!is_object($thisUser) || !$thisUser->isActive()) {
        redirect_header("index.php",3,_US_SELECTNG);
        exit();
    }
    $xoopsOption['template_main'] = 'system_userinfo.html';
    include(ICMS_ROOT_PATH.'/header.php');
    $xoopsTpl->assign('user_ownpage', false);
}
$myts =& MyTextSanitizer::getInstance();
if ( is_object($xoopsUser) && $isAdmin ) {
    $xoopsTpl->assign('lang_editprofile', _US_EDITPROFILE);
    $xoopsTpl->assign('lang_deleteaccount', _US_DELACCOUNT);
    $xoopsTpl->assign('user_uid', intval($thisUser->getVar('uid')));
}
$xoopsTpl->assign('lang_allaboutuser', sprintf(_US_ALLABOUT,$thisUser->getVar('uname')));
$xoopsTpl->assign('lang_avatar', _US_AVATAR);
        if ($xoopsConfigUser['avatar_allow_gravatar'] == 1) {
$xoopsTpl->assign('user_avatarurl', $thisUser->gravatar('G',$xoopsConfigUser['avatar_width']));
		}else{
$xoopsTpl->assign('user_avatarurl', ICMS_UPLOAD_URL.'/'.$thisUser->getVar('user_avatar'));
		}
$xoopsTpl->assign('lang_realname', _US_REALNAME);
$xoopsTpl->assign('user_realname', $thisUser->getVar('name'));
$xoopsTpl->assign('lang_website', _US_WEBSITE);
if ( $thisUser->getVar('url', 'E') == '') {
	$xoopsTpl->assign('user_websiteurl', '');
} else {
	$xoopsTpl->assign('user_websiteurl', '<a href="'.$thisUser->getVar('url', 'E').'" rel="external">'.$thisUser->getVar('url').'</a>');
}
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
$xoopsTpl->assign('user_extrainfo', $myts->makeTareaData4Show( $var,0,1,1) );
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
$xoopsTpl->assign('lang_notregistered', _US_NOTREGISTERED);
        if ($xoopsConfigUser['allwshow_sig'] == 1) {
            $xoopsTpl->assign('user_showsignature', true);
$xoopsTpl->assign('lang_signature', _US_SIGNATURE);
$var = $thisUser->getVar('user_sig', 'N');
$xoopsTpl->assign('user_signature', $myts->makeTareaData4Show( $var, 0, 1, 1 ) );
        }

if ($thisUser->getVar('user_viewemail') == 1) {
    $xoopsTpl->assign('user_email', $thisUser->getVar('email', 'E'));
} else {
    if (is_object($xoopsUser)) {
        // All admins will be allowed to see emails, even those that are not allowed to edit users (I think it's ok like this)
        if ($xoopsUserIsAdmin || ($xoopsUser->getVar("uid") == $thisUser->getVar("uid"))) {
            $xoopsTpl->assign('user_email', $thisUser->getVar('email', 'E'));
        } else {
            $xoopsTpl->assign('user_email', '&nbsp;');
        }
    }
}
if (is_object($xoopsUser)) {
    $xoopsTpl->assign('user_pmlink', "<a href=\"javascript:openWithSelfMain('".ICMS_URL."/pmlite.php?send2=1&amp;to_userid=".intval($thisUser->getVar('uid'))."', 'pmlite', 550, 450);\"><img src=\"".ICMS_URL."/images/icons/pm.gif\" alt=\"".sprintf(_SENDPMTO,$thisUser->getVar('uname'))."\" /></a>");
} else {
    $xoopsTpl->assign('user_pmlink', '');
}
$userrank =& $thisUser->rank();
if ($userrank['image']) {
    $xoopsTpl->assign('user_rankimage', '<img src="'.ICMS_UPLOAD_URL.'/'.$userrank['image'].'" alt="" />');
}
$xoopsTpl->assign('user_ranktitle', $userrank['title']);
$date = $thisUser->getVar("last_login");
if (!empty($date)) {
    $xoopsTpl->assign('user_lastlogin', formatTimestamp($date,"m"));
}


$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo(new Criteria('hassearch', 1));
$criteria->add(new Criteria('isactive', 1));
$mids =& array_keys($module_handler->getList($criteria));

foreach ($mids as $mid) {
  if ( $gperm_handler->checkRight('module_read', $mid, $groups)) {
    $module =& $module_handler->get($mid);
    $results =& $module->search('', '', 5, 0, intval($thisUser->getVar('uid')));
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

            $results[$i]['title'] = $myts->makeTboxData4Show($results[$i]['title']);
            $results[$i]['time'] = $results[$i]['time'] ? formatTimestamp($results[$i]['time']) : '';
        }
        if ($count == 5) {
            $showall_link = '<a href="search.php?action=showallbyuser&amp;mid='.intval($mid).'&amp;uid='.intval($thisUser->getVar('uid')).'">'._US_SHOWALL.'</a>';
        } else {
            $showall_link = '';
        }
        $xoopsTpl->append('modules', array('name' => $module->getVar('name'), 'results' => $results, 'showall_link' => $showall_link));
    }
    unset($module);
  }
}

$xoopsTpl->assign('xoops_pagetitle', sprintf(_US_ALLABOUT,$thisUser->getVar('uname')));

include ICMS_ROOT_PATH.'/footer.php';
?>
