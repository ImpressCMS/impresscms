<?php
// $Id: pmlite.php 12313 2013-09-15 21:14:35Z skenow $
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
 * All functions for pm manager are going through here.
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id: pmlite.php 12313 2013-09-15 21:14:35Z skenow $
 */

$xoopsOption['pagetype'] = "pmsg";

include "mainfile.php";
$reply = !empty($_GET['reply']) ? 1 : 0;
$send = !empty($_GET['send']) ? 1 : 0;
$send2 = !empty($_GET['send2']) ? 1 : 0;
$to_userid = !empty($_GET['to_userid']) ? (int) ($_GET['to_userid']) : 0;
$msg_id = !empty($_GET['msg_id']) ? (int) ($_GET['msg_id']) : 0;
if (empty($_GET['refresh']) && isset($_POST['op']) && $_POST['op'] != "submit") {
	$jump = "pmlite.php?refresh=" . time() . "";
	if ($send == 1) {
		$jump .= "&amp;send=" . $send . "";
	} elseif ($send2 == 1) {
		$jump .= "&amp;send2=" . $send2 . "&amp;to_userid=" . $to_userid . "";
	} elseif ($reply == 1) {
		$jump .= "&amp;reply=" . $reply . "&amp;msg_id=" . $msg_id . "";
	} else {
	}
	echo "<html><head><meta http-equiv='Refresh' content='0; url=" . $jump . "' /></head><body></body></html>";
	exit();
}
xoops_header();
if (icms::$user) {
	if (isset($_POST['op']) && $_POST['op'] == "submit") {
		if (!icms::$security->check()) {
			$security_error = true;
		}
		$res = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("users")
			. " WHERE uid='". (int) ($_POST['to_userid']) . "'");
		list($count) = icms::$xoopsDB->fetchRow($res);
		if ($count != 1) {
			echo "<br /><br /><div><h4>" . _PM_USERNOEXIST . "<br />"
				. _PM_PLZTRYAGAIN . "</h4><br />";
			if (isset($security_error) && $security_error == true) {
				echo implode('<br />', icms::$security->getErrors());
			}
			echo "[ <a href='javascript:history.go(-1)'>" . _PM_GOBACK . "</a> ]</div>";
		} else {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm =& $pm_handler->create();
			$pm->setVar("subject", icms_core_DataFilter::filterTextareaInput($_POST['subject']));
			$pm->setVar("msg_text", icms_core_DataFilter::filterHTMLinput($_POST['message'], TRUE, TRUE, TRUE));
			$pm->setVar("to_userid", (int) ($_POST['to_userid']));
			$pm->setVar("from_userid", (int) (icms::$user->getVar("uid")));
			if (!$pm_handler->insert($pm)) {
				echo $pm->getHtmlErrors() . "<br /><a href='javascript:history.go(-1)'>"
					. _PM_GOBACK . "</a>";
			} else {
				// Send a Private Message email notification
				$userHandler = icms::handler('icms_member_user');
				$toUser =& $userHandler->get((int) ($_POST['to_userid']));
				// Only send email notif if notification method is mail
				if ($toUser->getVar('notify_method') == 2) {
					$xoopsMailer = new icms_messaging_Handler();
					$xoopsMailer->useMail();
					$xoopsMailer->setToEmails($toUser->getVar('email'));
					if (icms::$user->getVar('user_viewemail')) {
						$xoopsMailer->setFromEmail(icms::$user->getVar('email'));
						$xoopsMailer->setFromName(icms::$user->getVar('uname'));
					} else {
						$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
						$xoopsMailer->setFromName($icmsConfig['sitename']);
					}
					$xoopsMailer->setTemplate('new_pm.tpl');
					$xoopsMailer->assign('X_SITENAME', $icmsConfig['sitename']);
					$xoopsMailer->assign('X_SITEURL', ICMS_URL . "/");
					$xoopsMailer->assign('X_ADMINMAIL', $icmsConfig['adminmail']);
					$xoopsMailer->assign('X_UNAME', $toUser->getVar('uname'));
					$xoopsMailer->assign('X_FROMUNAME', icms::$user->getVar('uname'));
					$xoopsMailer->assign('X_SUBJECT', icms_core_DataFilter::stripSlashesGPC($_POST['subject']));
					$xoopsMailer->assign('X_MESSAGE', icms_core_DataFilter::stripSlashesGPC($_POST['message']));
					$xoopsMailer->assign('X_ITEM_URL', ICMS_URL . "/viewpmsg.php");
					$xoopsMailer->setSubject(sprintf(_PM_MESSAGEPOSTED_EMAILSUBJ, $icmsConfig['sitename']));
					$xoopsMailer->send();
				}
				echo "<br /><br /><div style='text-align:center;'><h4>" . _PM_MESSAGEPOSTED
					. "</h4><br /><a href=\"javascript:window.opener.location='" . ICMS_URL . "/viewpmsg.php';window.close();\">"
					. _PM_CLICKHERE . "</a><br /><br /><a href=\"javascript:window.close();\">" . _PM_ORCLOSEWINDOW . "</a></div>";
			}
		}
	} elseif ($reply == 1 || $send == 1 || $send2 == 1) {
		if ($reply == 1) {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm =& $pm_handler->get($msg_id);
			if ($pm->getVar("to_userid") == (int) (icms::$user->getVar('uid'))) {
				$pm_uname = icms_member_user_Object::getUnameFromId($pm->getVar("from_userid"));
				$message  = "[quote]\n"
					. sprintf(_PM_USERWROTE, $pm_uname)
					. "\n" . $pm->getVar("msg_text", "E") . "\n[/quote]";
			} else {
				unset($pm);
				$reply = $send2 = 0;
			}
		}
		echo "<form action='pmlite.php' method='post' name='coolsus'>\n"
			. "<table width='300' align='center' class='outer'><tr><td class='head' width='25%'>"
			. _PM_TO . "</td>";
		if ($reply == 1) {
			echo "<td class='even'><input type='hidden' name='to_userid' value='" . $pm->getVar("from_userid") . "' />" . $pm_uname . "</td>";
		} elseif ($send2 == 1) {
			$to_username = icms_member_user_Object::getUnameFromId($to_userid);
			echo "<td class='even'><input type='hidden' name='to_userid' value='" . $to_userid . "' />" . $to_username . "</td>";
		} else {
			$user_sel = new icms_form_elements_select_User("", "to_userid");
			echo "<td class='even'>" . $user_sel->render() . "</td>";
		}
		echo "</tr><tr><td class='head' width='25%'>" . _PM_SUBJECTC . "</td>";
		if ($reply == 1) {
			$subject = $pm->getVar('subject', 'E');
			if (!preg_match("/^Re:/i", $subject)) {
				$subject = 'Re: ' . $subject;
			}
			echo "<td class='even'><input type='text' name='subject' value='" . $subject
				. "' size='30' maxlength='100' /></td>";
		} else {
			echo "<td class='even'><input type='text' name='subject' size='30' maxlength='100' /></td>";
		}
		echo "</tr><tr valign='top'><td class='head' width='25%'>"
			. _PM_MESSAGEC . "</td><td class='even'>";
		if ($reply == 1) {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm =& $pm_handler->get($msg_id);
			if ($pm->getVar("to_userid") == (int) (icms::$user->getVar('uid'))) {
				$pm_uname = icms_member_user_Object::getUnameFromId($pm->getVar("from_userid"));
				$message  = "[quote]\n"
					. sprintf(_PM_USERWROTE, $pm_uname)
					. "\n" . $pm->getVar("msg_text", "E") . "\n[/quote]";
			} else {
				unset($pm);
				$reply = $send2 = 0;
			}
			$textarea = new icms_form_elements_Dhtmltextarea(_PM_MESSAGEC, 'message', $message);
		} else {
			$textarea = new icms_form_elements_Dhtmltextarea(_PM_MESSAGEC, 'message', '');
		}
		echo $textarea->render()
			. "</td></tr>"
			. "<tr><td class='head'>&nbsp;</td><td class='even'>"
			. "<input type='hidden' name='op' value='submit' />" . icms::$security->getTokenHTML()
			. "<input type='submit' class='formButton' name='submit' value='" . _PM_SUBMIT
			. "' />&nbsp;<input type='reset' class='formButton' value='" . _PM_CLEAR
			. "' />&nbsp;<input type='button' class='formButton' name='cancel' value='" . _PM_CANCELSEND
			. "' onclick='javascript:window.close();' /></td></tr></table>\n"
			. "</form>\n";
	}
} else {
	echo "<div>" . _PM_SORRY . "<br /><br /><a href='" . ICMS_URL . "/register.php'>" . _PM_REGISTERNOW . "</a> . </div>";
}

xoops_footer();
