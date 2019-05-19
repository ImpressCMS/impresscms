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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Administration of users, main functions file
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Users
 */

if (!is_object(icms::$user)
	|| !is_object($icmsModule)
	|| !icms::$user->isAdmin($icmsModule->getVar('mid'))
) {
	exit('Access Denied');
}

/**
 * Displays user information form
 *
 */
function displayUsers() {
	global $icmsConfig, $icmsModule, $icmsConfigUser, $user_handler;
	$userstart = isset($_GET['userstart'])?(int) $_GET['userstart']:0;

	icms_cp_header();
	echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/users/images/users_big.png)">' . _MD_AM_USER . '</div><br />';
	$usercount = $user_handler->getCount(new icms_db_criteria_Item('level', '-1', '!='));
	$nav = new icms_view_PageNav($usercount, 200, $userstart, 'userstart', 'fct=users');

	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item('level', '-1', '!='));
	$criteria->setSort('uname');
	$criteria->setOrder('ASC');
	$criteria->setLimit(200);
	$criteria->setStart($userstart);

	$user_select = new icms_form_elements_Select('', 'uid');
	$user_select->addOptionArray($user_handler->getList($criteria));
	$user_select_tray = new icms_form_elements_Tray(_AM_NICKNAME, '<br />');
	$user_select_tray->addElement($user_select);
	$user_select_nav = new icms_form_elements_Label('', $nav->renderNav(4));
	$user_select_tray->addElement($user_select_nav);

	$op_select = new icms_form_elements_Select('', 'op');
	$op_select->addOptionArray(array('modifyUser'=>_AM_MODIFYUSER, 'delUser'=>_AM_DELUSER));

	$submit_button = new icms_form_elements_Button('', 'submit', _AM_GO, 'submit');
	$fct_hidden = new icms_form_elements_Hidden('fct', 'users');

	$editform = new icms_form_Theme(_AM_EDEUSER, 'edituser', 'admin.php');
	$editform->addElement($user_select_tray);
	$editform->addElement($op_select);
	$editform->addElement($submit_button);
	$editform->addElement($fct_hidden);
	$editform->display();

	echo "<br />\n";
	$usercount = $user_handler->getCount(new icms_db_criteria_Item('level', '-1'));
	$nav = new icms_view_PageNav($usercount, 200, $userstart, 'userstart', 'fct=users');


	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item('level', '-1'));
	$criteria->setSort('uname');
	$criteria->setOrder('ASC');
	$criteria->setLimit(200);
	$criteria->setStart($userstart);

	$user_select = new icms_form_elements_Select('', 'uid');
	$user_select->addOptionArray($user_handler->getList($criteria));
	$user_select_tray = new icms_form_elements_Tray(_AM_NICKNAME, '<br />');
	$user_select_tray->addElement($user_select);
	$user_select_nav = new icms_form_elements_Label('', $nav->renderNav(4));
	$user_select_tray->addElement($user_select_nav);

	$op_select = new icms_form_elements_Select('', 'op');
	$op_select->addOptionArray(array('modifyUser'=>_AM_MODIFYUSER));

	$submit_button = new icms_form_elements_Button('', 'submit', _AM_GO, 'submit');
	$fct_hidden = new icms_form_elements_Hidden('fct', 'users');

	$editform = new icms_form_Theme(_AM_REMOVED_USERS, 'edituser', 'admin.php');
	$editform->addElement($user_select_tray);
	$editform->addElement($op_select);
	$editform->addElement($submit_button);
	$editform->addElement($fct_hidden);
	$editform->display();

	echo "<br />\n";
	$uid_value = '';
	$uname_value = '';
	$login_name_value = '';
	$name_value = '';
	$email_value = '';
	$email_cbox_value = 0;
	$openid_value = '';
	$openid_cbox_value = 0;
	$url_value = '';
	$timezone_value = $icmsConfig['default_TZ'];
	$location_value = '';
	$occ_value = '';
	$interest_value = '';
	$sig_value = '';
	$sig_cbox_value = 0;
	$umode_value = $icmsConfig['com_mode'];
	$uorder_value = $icmsConfig['com_order'];

	include_once ICMS_INCLUDE_PATH . '/notification_constants.php';
	$notify_method_value = XOOPS_NOTIFICATION_METHOD_PM;
	$notify_mode_value = XOOPS_NOTIFICATION_MODE_SENDALWAYS;
	$bio_value = '';
	$rank_value = 0;
	$mailok_value = 0;
	$pass_expired_value = 0;
	$op_value = 'addUser';
	$form_title = _AM_ADDUSER;
	$form_isedit = false;
	$language_value = $icmsConfig['language'];
	$groups = array(ICMS_GROUP_USERS);
	include ICMS_MODULES_PATH . '/system/admin/users/userform.php';
	icms_cp_footer();
}

/**
 * Logic and rendering for modifying a member profile
 *
 * @param int $user	userid
 */
function modifyUser($user) {
	global $icmsConfig, $icmsModule, $user_handler;
	icms_cp_header();
	echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/users/images/users_big.png)">' . _MD_AM_USER . '</div><br />';
	$user = & $user_handler->get($user);
	if (is_object($user)) {
		if (!$user->isActive()) {
			icms_core_Message::confirm(array('fct' => 'users', 'op' => 'reactivate', 'uid' => $user->getVar('uid')), 'admin.php', _AM_NOTACTIVE);
			icms_cp_footer();
			exit();
		}

		$uid_value = $user->getVar('uid');
		$uname_value = $user->getVar('uname', 'E');
		$login_name_value = $user->getVar('login_name', 'E');
		$name_value = $user->getVar('name', 'E');
		$email_value = $user->getVar('email', 'E');
		$email_cbox_value = $user->getVar('user_viewemail')?1:0;
		$openid_value = $user->getVar('openid', 'E');
		$openid_cbox_value = $user->getVar('user_viewoid')?1:0;
		$url_value = $user->getVar('url', 'E');
		$temp = $user->getVar('theme');
		$timezone_value = $user->getVar('timezone_offset');
		$location_value = $user->getVar('user_from', 'E');
		$occ_value = $user->getVar('user_occ', 'E');
		$interest_value = $user->getVar('user_intrest', 'E');
		$sig_value = $user->getVar('user_sig', 'E');
		$sig_cbox_value = ($user->getVar('attachsig') == 1)?1:0;
		$umode_value = $user->getVar('umode');
		$uorder_value = $user->getVar('uorder');
		$notify_method_value = $user->getVar('notify_method');
		$notify_mode_value = $user->getVar('notify_mode');
		$bio_value = $user->getVar('bio', 'E');
		$rank_value = $user->rank(false);
		$mailok_value = $user->getVar('user_mailok', 'E');
		$pass_expired_value = $user->getVar('pass_expired')?1:0;
		$op_value = 'updateUser';
		$form_title = _AM_UPDATEUSER . ': ' . $user->getVar('uname');
		$language_value = $user->getVar('language');
		$form_isedit = true;
		$groups = array_values($user->getGroups());
		include ICMS_MODULES_PATH . '/system/admin/users/userform.php';
		echo "<br /><strong>" . _AM_USERPOST . "</strong><br /><br />\n"
			. "<table>\n"
			. "<tr><td>" . _AM_COMMENTS . "</td><td>" . icms_conv_nr2local($user->getVar('posts')) . "</td></tr>\n"
			. "</table>\n"
			. "<br />" . _AM_PTBBTSDIYT . "<br />\n"
			. "<form action=\"admin.php\" method=\"post\">\n"
			. "<input type=\"hidden\" name=\"id\" value=\"" . $user->getVar('uid') . "\">"
			. "<input type=\"hidden\" name=\"type\" value=\"user\">\n"
			. "<input type=\"hidden\" name=\"fct\" value=\"users\">\n"
			. "<input type=\"hidden\" name=\"op\" value=\"synchronize\">\n"
			. icms::$security->getTokenHTML() . "\n"
			. "<input type=\"submit\" value=\"" . _AM_SYNCHRONIZE . "\">\n"
			. "</form>\n";
	} else {
		echo "<h4 style='text-align:" . _GLOBAL_LEFT . ";'>" . _AM_USERDONEXIT . "</h4>";
	}
	icms_cp_footer();
}

/**
 * Updates the member profile, saving the changes to the database
 *
 * @param $uid
 * @param $uname
 * @param $loginName
 * @param $name
 * @param $url
 * @param $email
 * @param $userFrom
 * @param $userOCC
 * @param $userInterest
 * @param $userViewEmail
 * @param $userAvatar
 * @param $userSignature
 * @param $attachsig
 * @param $theme
 * @param $pass
 * @param $pass2
 * @param $rank
 * @param $bio
 * @param $uorder
 * @param $umode
 * @param $notifyMethod
 * @param $notifyMode
 * @param $timezoneOffset
 * @param $userMailOk
 * @param $language
 * @param $openid
 * @param $userViewOID
 * @param $passExpired
 * @param $groups
 */
function updateUser(
	$uid,
	$uname,
	$loginName,
	$name,
	$url,
	$email,
	$userFrom,
	$userOCC,
	$userInterest,
	$userViewEmail,
	$userAvatar,
	$userSignature,
	$attachsig,
	$theme,
	$pass,
	$pass2,
	$rank,
	$bio,
	$uorder,
	$umode,
	$notifyMethod,
	$notifyMode,
	$timezoneOffset,
	$userMailOk,
	$language,
	$openid,
	$userViewOID,
	$passExpired,
	$groups = array()
) {
	global $icmsConfig, $icmsModule, $icmsConfigUser, $user_handler;
	$edituser = & $user_handler->get($uid);
	if ($edituser->getVar('uname') != $uname && $user_handler->getCount(new icms_db_criteria_Item('uname', $uname)) > 0 || $edituser->getVar('login_name') != $login_name && $user_handler->getCount(new icms_db_criteria_Item('login_name', $login_name)) > 0) {
		icms_cp_header();
		echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/users/images/users_big.png)">' . _MD_AM_USER . '</div><br />';
		echo _AM_UNAME . ' ' . $uname . ' ' . _AM_ALREADY_EXISTS;
		icms_cp_footer();
	} else {
		$edituser->setVar('name', $name);
		$edituser->setVar('uname', $uname);
		$edituser->setVar('login_name', $loginName);
		$edituser->setVar('email', $email);
		$edituser->setVar('openid', $openid);
		$userViewOID = (isset($userViewOID) && $userViewOID == 1)?1:0;
		$edituser->setVar('user_viewoid', $userViewOID);
		$url = isset($url)? formatURL($url):'';
		$edituser->setVar('url', $url);
		$edituser->setVar('user_from', $userFrom);
		if ($icmsConfigUser['allow_htsig'] == 0) {
			$signature = strip_tags(icms_core_DataFilter::codeDecode($user_sig, 1));
			$edituser->setVar('user_sig', icms_core_DataFilter::icms_substr($signature, 0, (int) $icmsConfigUser['sig_max_length']));
		} else {
			$signature = icms_core_DataFilter::checkVar($userSignature, 'html', 'input');
			$edituser->setVar('user_sig', $signature);
		}
		$userViewEmail = (isset($userViewEmail) && $userViewEmail == 1)?1:0;
		$edituser->setVar('user_viewemail', $userViewEmail);
		$attachsig = (isset($attachsig) && $attachsig == 1)?1:0;
		$edituser->setVar('attachsig', $attachsig);
		$edituser->setVar('timezone_offset', $timezoneOffset);
		$edituser->setVar('uorder', $uorder);
		$edituser->setVar('umode', $umode);
		$edituser->setVar('notify_method', $notifyMethod);
		$edituser->setVar('notify_mode', $notifyMode);
		$edituser->setVar('bio', $bio);
		$edituser->setVar('rank', $rank);
		$edituser->setVar('user_occ', $userOCC);
		$edituser->setVar('user_intrest', $userInterest);
		$edituser->setVar('user_mailok', $userMailOk);
		$edituser->setVar('language', $language);
		if ($pass2 != '') {
			if ($pass != $pass2) {
				icms_cp_header();
				echo "<strong>" . _AM_STNPDNM . "</strong>";
				icms_cp_footer();
				exit();
			}

			$icmspass = new icms_core_Password();
			$edituser->setVar('pass_expired', $passExpired);
			$pass = $icmspass->encryptPass($pass);
			$edituser->setVar('pass', $pass);
		}
		if (!$user_handler->insert($edituser)) {
			icms_cp_header();
			echo $edituser->getHtmlErrors();
			icms_cp_footer();
		} else {
			if ($groups != array()) {
				$oldgroups = $edituser->getGroups();
				//If the edited user is the current user and the current user WAS in the webmaster's group and is NOT in the new groups array
				if ($edituser->getVar('uid') == icms::$user->getVar('uid') && (in_array(ICMS_GROUP_ADMIN, $oldgroups)) && !(in_array(ICMS_GROUP_ADMIN, $groups))) {
					//Add the webmaster's group to the groups array to prevent accidentally removing oneself from the webmaster's group
					$groups[] = ICMS_GROUP_ADMIN;
				}
				$member_handler = icms::handler('icms_member');
				foreach ($oldgroups as $groupid) {
					$member_handler->removeUsersFromGroup($groupid, array($edituser->getVar('uid')));
				}
				foreach (
					$groups as $groupid) {$member_handler->addUserToGroup($groupid, $edituser->getVar('uid'));
				}
			}
			redirect_header('admin.php?fct=users', 1, _ICMS_DBUPDATED);
		}
	}
	exit();
}

/**
 * Update count of posts in comments and bb_posts (old forums)
 *
 * @param int $id	Unique ID of the member to synchronize
 * @param str $type	'user' or 'all users'
 */
function synchronize($id, $type) {
	switch ($type) {
		case 'user':
			// Array of tables from which to count 'posts'
			$tables = array();
			// Count comments (approved only: com_status == XOOPS_COMMENT_ACTIVE)
			include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
			$tables[] = array('table_name' => 'xoopscomments', 'uid_column' => 'com_uid', 'criteria' => new icms_db_criteria_Item('com_status', XOOPS_COMMENT_ACTIVE));
			// Count forum posts
			$tables[] = array('table_name' => 'bb_posts', 'uid_column' => 'uid');
			$total_posts = 0;
			foreach ($tables as $table) {
				$criteria = new icms_db_criteria_Compo();
				$criteria->add(new icms_db_criteria_Item($table['uid_column'], $id));
				if (!empty($table['criteria'])) {$criteria->add($table['criteria']); }
				$sql = "SELECT COUNT(*) AS total FROM " . icms::$xoopsDB->prefix($table['table_name']) . ' ' . $criteria->renderWhere();
				if ($result = icms::$xoopsDB->query($sql)) {
					if ($row = icms::$xoopsDB->fetchArray($result)) {$total_posts = $total_posts + $row['total']; }
				}
			}
			$sql = "UPDATE " . icms::$xoopsDB->prefix("users") . " SET posts = '" . (int) $total_posts . "' WHERE uid = '" . (int) $id . "'";
			if (!$result = icms::$xoopsDB->query($sql)) {exit(sprintf(_AM_CNUUSER % s, $id)); }
			break;

		case 'all users':
			$sql = "SELECT uid FROM " . icms::$xoopsDB->prefix('users') . "";
			if (!$result = icms::$xoopsDB->query($sql)) {exit(_AM_CNGUSERID); }
			while ($row = icms::$xoopsDB->fetchArray($result)) {
				$id = $row['uid'];
				synchronize($id, "user");
			}
			break;

		default:
			break;
	}
	redirect_header('admin.php?fct=users&amp;op=modifyUser&amp;uid=' . $id, 1, _ICMS_DBUPDATED);
	exit();
}
