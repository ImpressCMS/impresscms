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
 * Administration of users, main file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author		modified by UnderDog <underdog@impresscms.org>
 */

/* set get and post filters before including admin_header, if not strings */
$filter_post = array(
	'user_sig' => 'html',
	'bio'=> 'html',
);

$filter_get = array(
	'uid' => 'int',
);

/* set default values for variables. $op and $fct are handled in the header */
$op = '';

/** common header for the admin functions */
include "admin_header.php";
$user_handler = $icms_admin_handler;

include_once ICMS_MODULES_PATH . "/system/admin/users/users.php";

switch ($op) {
	case 'modifyUser':
		modifyUser($uid);
		break;

	case 'updateUser':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=users', 3, implode('<br />', icms::$security->getErrors()));
		}
		$user_avatar = $theme = null;
		if (!isset($attachsig)) {
			$attachsig = null;
		}
		if (!isset($user_viewemail)) {
			$user_viewemail = null;
		}
		if (!isset($user_viewoid)) {
			$user_viewoid = null;
		}
		if (!isset($openid)) {
			$openid = null;
		}
		$groups = isset($_POST['groups'])?$groups:array(ICMS_GROUP_ANONYMOUS);
		if (@is_array($groups_hidden)) {
			$groups = array_unique(array_merge($groups, $groups_hidden));
		}
		updateUser($uid, $username, $login_name, $name, $url, $email,
					$user_from, $user_occ, $user_intrest, $user_viewemail, $user_avatar, $user_sig, $attachsig,
					$theme, $password, $pass2, $rank, $bio, $uorder, $umode, $notify_method, $notify_mode,
					$timezone_offset, $user_mailok, $language, $openid, $user_viewoid, $pass_expired, $groups
				);
		break;

	case 'delUser':
		icms_cp_header();
		$userdata = & $user_handler->get($uid);
		icms_core_Message::confirm(array('fct' => 'users',
											'op' => 'delUserConf',
											'del_uid' => $userdata->getVar('uid')
										), 'admin.php', sprintf(_AM_AYSYWTDU, $userdata->getVar('uname')));
		icms_cp_footer();
		break;

	case 'delete_many':
		icms_cp_header();
		$count = count($memberslist_id);
		if ($count > 0) {
			$list = "<a href='" . ICMS_URL . "/userinfo.php?uid=" . $memberslist_id[0] . "' rel='external'>"
				. $memberslist_uname[$memberslist_id[0]] . "</a>";
			$hidden = "<input type='hidden' name='memberslist_id[]' value='" . $memberslist_id[0] . "' />\n";
			for ($i = 1; $i < $count; $i++) {
				$list .= ", <a href='" . ICMS_URL . "/userinfo.php?uid=" . $memberslist_id[$i] . "' rel='external'>"
					. $memberslist_uname[$memberslist_id[$i]] . "</a>";
				$hidden .= "<input type='hidden' name='memberslist_id[]' value='" . $memberslist_id[$i] . "' />\n";
			}
			echo "<div><h4>" . sprintf(_AM_AYSYWTDU, " " . $list . " ") . "</h4>"
				. _AM_BYTHIS . "<br /><br /><form action='admin.php' method='post'>"
				. "<input type='hidden' name='fct' value='users' />"
				. "<input type='hidden' name='op' value='delete_many_ok' />"
				. icms::$security->getTokenHTML()
				. "<input type='submit' value='" . _YES . "' />"
				. "<input type='button' value='" . _NO
				. "' onclick='javascript:location.href=\"admin.php?op=adminMain\"' />"
				. $hidden . "</form></div>";
		} else {echo _AM_NOUSERS; }
		icms_cp_footer();
		break;

	case 'delete_many_ok':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=users', 3, implode('<br />', icms::$security->getErrors()));
		}
		$count = count($memberslist_id);
		$output = '';
		$member_handler = icms::handler('icms_member');
		for ($i = 0; $i < $count; $i++) {
			$deluser = & $user_handler->get($memberslist_id[$i]);
			$delgroups = $deluser->getGroups();
			if (in_array(ICMS_GROUP_ADMIN, $delgroups)) {
				$output .= sprintf(
						_AM_ADMIN_CAN_NOT_BE_DELETEED . ' (' . _AM_NICKNAME . ': %s)',
						$deluser->getVar('uname')
					) . '<br />';
			} else {
				if (!$user_handler->delete($deluser)) {
					$output .= _AM_COULD_NOT_DELETE . ' ' . $deluser->getVar('uname') . '<br />';
				} else {
					$output .= $deluser->getVar('uname') . ' ' . _AM_USERS_DELETEED . '<br />';
				}
				xoops_notification_deletebyuser($deluser->getVar('uid'));
			}
		}
		icms_cp_header();
		echo $output;
		icms_cp_footer();
		break;

	case 'delUserConf':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=users', 3, implode('<br />', icms::$security->getErrors()));
		}
		$member_handler = icms::handler('icms_member');
		$user = & $user_handler->get($del_uid);
		$groups = $user->getGroups();
		if (in_array(ICMS_GROUP_ADMIN, $groups)) {
			icms_cp_header();
			echo sprintf(_AM_ADMIN_CAN_NOT_BE_DELETEED . '.(' . _AM_NICKNAME . ': %s)', $user->getVar('uname'));
			icms_cp_footer();
		} elseif (!$user_handler->delete($user)) {
			icms_cp_header();
			echo _AM_ADMIN_CAN_NOT_BE_DELETEED . $deluser->getVar('uname');
			icms_cp_footer();
		} else {
			$online_handler = icms::handler('icms_core_Online');
			$online_handler->destroy($del_uid);
			xoops_notification_deletebyuser($del_uid);
			redirect_header('admin.php?fct=users', 1, _ICMS_DBUPDATED);
		}
		break;

	case 'addUser':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=users', 3, implode('<br />', icms::$security->getErrors()));
		}
		if (!$username || !$email || !$password || !$login_name) {
			$adduser_errormsg = _AM_YMCACF;
		} else {
			$member_handler = icms::handler('icms_member');
			// make sure the username doesnt exist yet
			if ($user_handler->getCount(new icms_db_criteria_Item('uname', $username)) > 0
				|| $user_handler->getCount(new icms_db_criteria_Item('login_name', $login_name)) > 0
			) {
				$adduser_errormsg = _AM_NICKNAME . ' ' . $username . ' ' . _AM_ALREADY_EXISTS;
			} elseif ($user_handler->getCount(new icms_db_criteria_Item('email', $email)) > 0) {
				$adduser_errormsg = _AM_A_USER_WITH_THIS_EMAIL_ADDRESS . ' "' . $email . '" ' . _AM_ALREADY_EXISTS;
			} else {
				$newuser = & $user_handler->create();
				if (isset($user_viewemail)) {
					$newuser->setVar('user_viewemail', $user_viewemail);
				}
				if (isset($user_viewoid)) {
					$newuser->setVar('user_viewoid', $user_viewoid);
				}
				if (isset($attachsig)) {
					$newuser->setVar('attachsig', $attachsig);
				}
				$newuser->setVar('name', $name);
				$newuser->setVar('login_name', $login_name);
				$newuser->setVar('uname', $username);
				$newuser->setVar('email', $email);
				$newuser->setVar('url', formatURL($url));
				$newuser->setVar('user_avatar', 'blank.gif');
				$newuser->setVar('user_regdate', date('U'));
				$newuser->setVar('user_from', $user_from);
				$newuser->setVar('user_sig', $user_sig);
				if ($pass2 != '') {
					if ($password != $pass2) {
						icms_cp_header();
						echo '<strong>' . _AM_STNPDNM . '</strong>';
						icms_cp_footer();
						exit();
					}
					if ($password == $username || $password == icms_core_DataFilter::utf8_strrev($username, true)
						|| strripos($password, $username) === true || $password == $login_name
						|| $password == icms_core_Datafilter::utf8_strrev($login_name, true)
						|| strripos($password, $login_name) === true
					) {
						icms_cp_header();
						echo '<strong>' . _AM_BADPWD . '</strong>';
						icms_cp_footer();
						exit();
					}

					$icmspass = new icms_core_Password();
					$password = $icmspass->encryptPass($password, $salt, $enc_type);
					$newuser->setVar('pass', $password);
				}
				$newuser->setVar('timezone_offset', $timezone_offset);
				$newuser->setVar('uorder', $uorder);
				$newuser->setVar('umode', $umode);
				$newuser->setVar('notify_method', $notify_method);
				$newuser->setVar('notify_mode', $notify_mode);
				$newuser->setVar('bio', $bio);
				$newuser->setVar('rank', $rank);
				$newuser->setVar('level', 1);
				$newuser->setVar('user_occ', $user_occ);
				$newuser->setVar('user_intrest', $user_intrest);
				$newuser->setVar('user_mailok', $user_mailok);
				$newuser->setVar('language', $language);

				if ($icmsConfigAuth['auth_openid'] == 1) {
					$newuser->setVar('openid', $openid); }
					if (!$user_handler->insert($newuser)) {
						$adduser_errormsg = _AM_CNRNU;
					} else {
						$groups_failed = array();
						if (!isset($_POST['groups'])) {
							$groups = array(ICMS_GROUP_ANONYMOUS);
						}
						foreach ($groups as $group) {
							if (!$member_handler->addUserToGroup($group, $newuser->getVar('uid'))) {
								$groups_failed[] = $group;
							}
						}
						if (!empty($groups_failed)) {
							$group_names = $member_handler->getGroupList(
									new icms_db_criteria_Item('groupid', "(" . implode(", ", $groups_failed) . ")", 'IN')
							);
							$adduser_errormsg = sprintf(_AM_CNRNU2, implode(", ", $group_names));
						} else {
							 /* Hack by marcan <INBOX>
							 * Sending a confirmation email to the newly registered user
							 */
							redirect_header('admin.php?fct=users', 1, _ICMS_DBUPDATED);
						}
					}
			}
		}
		icms_cp_header();
		icms_core_Message::error($adduser_errormsg);
		icms_cp_footer();
		break;

	case 'synchronize':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=users', 3, implode('<br />', icms::$security->getErrors()));
		}
		synchronize($id, $type);
		break;

	case 'reactivate':
		$result = icms::$xoopsDB->query(
				"UPDATE " . icms::$xoopsDB->prefix('users') . " SET level='1' WHERE uid='" . (int) $uid . "'"
		);
		if (!$result) {
			exit();
		}
		redirect_header('admin.php?fct=users&amp;op=modifyUser&amp;uid=' . (int) $uid, 1, _ICMS_DBUPDATED);
		break;

	case 'mod_users':
	default:
		displayUsers();
		break;
}
