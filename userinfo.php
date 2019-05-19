<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author      sato-san <sato-san@impresscms.org>
 * @package		Member
 * @subpackage	User
 */

$xoopsOption['pagetype'] = 'user';
$uid = (int) $_GET['uid'];

if (icms_get_module_status("profile")) {
	$module = icms::handler("icms_module")->getByDirName("profile", true);

	if ($module->config['profile_social'] && file_exists(ICMS_MODULES_PATH . '/profile/index.php')) {
		header('Location: ' . ICMS_MODULES_URL . '/profile/index.php?uid=' . $uid);
		exit();
	} elseif (!$module->config['profile_social'] && file_exists(ICMS_MODULES_PATH . '/profile/userinfo.php')) {
		header('Location: ' . ICMS_MODULES_URL . '/profile/userinfo.php?uid=' . $uid);
		exit();
	}
	unset($module);
}

include_once ICMS_MODULES_PATH . '/system/constants.php';

if (!$icmsConfigUser['allow_annon_view_prof'] && !is_object(icms::$user)) {
	redirect_header(ICMS_URL . '/user.php', 3, _NOPERM);
}
if ($uid <= 0) {
	redirect_header('index.php', 3, _US_SELECTNG);
}

$gperm_handler = icms::handler('icms_member_groupperm');
$groups = is_object(icms::$user)? icms::$user->getGroups():XOOPS_GROUP_ANONYMOUS;

$isAdmin = $gperm_handler->checkRight('system_admin', XOOPS_SYSTEM_USER, $groups);

if (is_object(icms::$user)) {
	if ($uid == icms::$user->getVar('uid')) {
		$xoopsOption['template_main'] = 'system_userinfo.html';
		include ICMS_ROOT_PATH . '/header.php';
		$icmsTpl->assign('user_ownpage', true);
		icms_makeSmarty(array(
			'user_ownpage' => true,
			'lang_editprofile' => _US_EDITPROFILE,
			'lang_avatar' => _US_AVATAR,
			'lang_notifications' => _US_NOTIFICATIONS,
			'lang_inbox' => _US_INBOX,
			'lang_logout' => _US_LOGOUT,
			'lang_administration' => _CPHOME,
			'user_candelete' => $icmsConfigUser['self_delete']? true : false,
			'lang_deleteaccount' => $icmsConfigUser['self_delete']? _US_DELACCOUNT : ''));
		$thisUser = icms::$user;
	} else {
		$thisUser = icms::handler('icms_member')->getUser($uid);
		if (!is_object($thisUser) || !$thisUser->isActive()) {
			redirect_header('index.php', 3, _US_SELECTNG);
		}
		$xoopsOption['template_main'] = 'system_userinfo.html';
		include ICMS_ROOT_PATH . '/header.php';
		$icmsTpl->assign('user_ownpage', false);
	}
} else {
	$thisUser = icms::handler('icms_member')->getUser($uid);
	if (!is_object($thisUser) || !$thisUser->isActive()) {
		redirect_header('index.php', 3, _US_SELECTNG);
	}
	$xoopsOption['template_main'] = 'system_userinfo.html';
	include ICMS_ROOT_PATH . '/header.php';
	$icmsTpl->assign('user_ownpage', false);
}

if (is_object(icms::$user) && $isAdmin) {
	icms_makeSmarty(array(
		'lang_editprofile' => _US_EDITPROFILE,
		'lang_deleteaccount' => _US_DELACCOUNT,
		'user_uid' => (int) $thisUser->getVar('uid')
	));
}

$userrank = $thisUser->rank();
$date = $thisUser->getVar('last_login');
icms_makeSmarty(array(
	'user_avatarurl' => $icmsConfigUser['avatar_allow_gravatar'] == true
		?$thisUser->gravatar('G', $icmsConfigUser['avatar_width'])
		: ICMS_UPLOAD_URL . '/' . $thisUser->getVar('user_avatar'),
	'user_websiteurl' => ($thisUser->getVar('url', 'E') == '')?''
		: '<a href="' . $thisUser->getVar('url', 'E') . '" rel="external">' . $thisUser->getVar('url') . '</a>',
	'lang_website' => _US_WEBSITE,
	'user_realname' => $thisUser->getVar('name'),
	'lang_realname' => _US_REALNAME,
	'lang_avatar' => _US_AVATAR,
	'lang_allaboutuser' => sprintf(_US_ALLABOUT, $thisUser->getVar('uname')),
	'user_alwopenid' => $icmsConfigAuth['auth_openid'],
	'lang_openid', $icmsConfigAuth['auth_openid'] == true? _US_OPENID_FORM_CAPTION : '',
	'lang_email' => _US_EMAIL,
	'lang_privmsg' => _US_PM,
	'lang_icq' => _US_ICQ,
	'user_icq' => $thisUser->getVar('user_icq'),
	'lang_aim' => _US_AIM,
	'user_aim' => $thisUser->getVar('user_aim'),
	'lang_yim' => _US_YIM,
	'user_yim' => $thisUser->getVar('user_yim'),
	'lang_msnm' => _US_MSNM,
	'user_msnm' => $thisUser->getVar('user_msnm'),
	'lang_location' => _US_LOCATION,
	'user_location' => $thisUser->getVar('user_from'),
	'lang_occupation' => _US_OCCUPATION,
	'user_occupation' => $thisUser->getVar('user_occ'),
	'lang_interest' => _US_INTEREST,
	'user_interest' => $thisUser->getVar('user_intrest'),
	'lang_extrainfo' => _US_EXTRAINFO,
	'user_extrainfo' => icms_core_DataFilter::checkVar($thisUser->bio, 'text', 'output'),
	'lang_statistics' => _US_STATISTICS,
	'lang_membersince' => _US_MEMBERSINCE,
	'user_joindate' => formatTimestamp($thisUser->getVar('user_regdate'), 's'),
	'lang_rank' => _US_RANK,
	'lang_posts' => _US_POSTS,
	'lang_basicInfo' => _US_BASICINFO,
	'lang_more' => _US_MOREABOUT,
	'lang_myinfo' => _US_MYINFO,
	'user_posts' => icms_conv_nr2local($thisUser->getVar('posts')),
	'lang_lastlogin' => _US_LASTLOGIN,
	'lang_notregistered' => _US_NOTREGISTERED,
	'user_pmlink' => is_object(icms::$user)
		?"<a class='cboxElement' href='" . ICMS_URL . "/pmlite.php?send2=1&amp;to_userid=" . (int) $thisUser->getVar('uid') . "'>
		<input type='button' class='formButton' value='" . sprintf(_SENDPMTO, $thisUser->getVar('uname')) . "' /></a>"
		: '',
	'user_rankimage' => $userrank['image']?
		'<img src="' . $userrank['image'] . '" alt="' . $userrank['title'] . '" />':'',
	'user_ranktitle' => $userrank['title'],
	'user_lastlogin' => !empty($date)? formatTimestamp($thisUser->getVar('last_login'), 'm'):'',
	'icms_pagetitle' => sprintf(_US_ALLABOUT, $thisUser->getVar('uname')),
	'user_email' => ($thisUser->getVar('user_viewemail') == true
			|| (is_object(icms::$user)
			&& (icms::$user->isAdmin()
			|| (icms::$user->getVar('uid') == $thisUser->getVar('uid')))))
		?$thisUser->getVar('email', 'E')
		: '&nbsp;',
	'user_openid' => ($icmsConfigAuth['auth_openid'] == true
			&& ($thisUser->getVar('user_viewoid') == true
			|| (is_object(icms::$user)
			&& (icms::$user->isAdmin()
			|| (icms::$user->getVar('uid') == $thisUser->getVar('uid'))))))
		?$thisUser->getVar('openid', 'E')
		: '&nbsp;'
));

if ($icmsConfigUser['allwshow_sig'] == true && strlen(trim($thisUser->user_sig)) > 0) {
   	icms_makeSmarty(array(
		'user_showsignature' => true,
		'lang_signature' => _US_SIGNATURE,
		'user_signature' => icms_core_DataFilter::checkVar($thisUser->user_sig, 'html', 'output')
	));
}

$module_handler = icms::handler('icms_module');
$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('hassearch', 1));
$criteria->add(new icms_db_criteria_Item('isactive', 1));
$mids = array_keys($module_handler->getList($criteria));

foreach ($mids as $mid) {
	if ($gperm_handler->checkRight('module_read', $mid, $groups)) {
		$module = $module_handler->get($mid);
		$results = $module->search('', '', 5, 0, (int) $thisUser->getVar('uid'));
		$count = count($results);
		if (is_array($results) && $count > 0) {
			for ($i = 0; $i < $count; $i++) {
				if (isset($results[$i]['image']) && $results[$i]['image'] != '') {
					$results[$i]['image'] = 'modules/' . $module->getVar('dirname') . '/' . $results[$i]['image'];
				} else {
					$results[$i]['image'] = 'images/icons/' . $icmsConfig['language'] . '/posticon2.gif';
				}
				if (isset($results[$i]['link']) && $results[$i]['link'] != '') {
					if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
						$results[$i]['link'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['link'];
					}
				}
				$results[$i]['title'] = icms_core_DataFilter::htmlSpecialChars($results[$i]['title']);
				$results[$i]['time'] = $results[$i]['time']? formatTimestamp($results[$i]['time']):'';
			}
			if ($count == 5) {
				$showall_link = '<a href="search.php?action=showallbyuser&amp;mid=' . (int) $mid .
					'&amp;uid=' . (int) $thisUser->getVar('uid') . '">' . _US_SHOWALL . '</a>';
			} else {
				$showall_link = '';
			}
			$icmsTpl->append('modules', array('name' => $module->getVar('name'),
												'results' => $results,
												'showall_link' => $showall_link
												));
		}
		unset ($module);
	}
}

include ICMS_ROOT_PATH . '/footer.php';
