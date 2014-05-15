<?php
// $Id: pmlite.php 12363 2013-11-01 05:06:13Z sato-san $
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
 * @version		$Id: pmlite.php 12363 2013-11-01 05:06:13Z sato-san $
 */

/* this will set which language file will load for this page */
$xoopsOption['pagetype'] = "pmsg";

require "mainfile.php";

/* set filter types, if not strings */
$filter_get[] = array(
		'reply' => 'int',
		'send' => 'int',
		'send2' => 'int',
		'refresh' => 'int',
		'to_userid' => 'int',
		'msg_id' => 'int',
);

$filter_post[] = array(
		'to_userid' => 'int',
		'msg_id' => 'int',
		'subject' => 'str',
		'message' => 'str',
);

/* set default values for variables */
$op = $subject = $message = "";
$reply = $send = $send2 = $refresh = $to_userid = $msg_id = 0;

/* filter the user input */
if (!empty($_GET)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_POST);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

if (empty($refresh) && !empty($op) && $op != "submit") {
	$jump = "pmlite.php?refresh=" . time() . "";
	if ($send != 0) {
		$jump .= "&amp;send=" . $send . "";
	} elseif ($send2 != 0) {
		$jump .= "&amp;send2=" . $send2 . "&amp;to_userid=" . $to_userid . "";
	} elseif ($reply != 0) {
		$jump .= "&amp;reply=" . $reply . "&amp;msg_id=" . $msg_id . "";
	} else {
	}
	echo "<html><head><meta http-equiv='Refresh' content='0; url=" . $jump . "' /></head><body></body></html>";
	exit();
}

xoops_header();
if (!icms::$user) {
	echo "<div>" . _PM_SORRY . "<br /><br />" . _PM_PLEACE . "<a href='" . ICMS_URL . "/register.php'>" . _PM_REGISTERNOW . "</a>" . _PM_OR . "<a href='" . ICMS_URL . "/user.php'>" . _PM_LOGINNOW . "</a></div>";
} else {
	if (!empty($op) && $op == "submit") {
		/* This section is for sending messages */
		
		if (!icms::$security->check()) {
			$security_error = true;
		}
		$res = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("users")
			. " WHERE uid='". $to_userid . "'");
		list($count) = icms::$xoopsDB->fetchRow($res);
		if ($count != 1) {
			echo "<br /><br /><div><h4>" . _PM_USERNOEXIST . "<br />"
				. _PM_PLZTRYAGAIN . "</h4><br />";
			if (isset($security_error) && $security_error == TRUE) {
				echo implode('<br />', icms::$security->getErrors());
			}
			echo "[ <a href='javascript:history.go(-1)'>" . _PM_GOBACK . "</a> ]</div>";
		} else {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm =& $pm_handler->create();
			$pm->setVar("subject", icms_core_DataFilter::filterTextareaInput($subject));
			$pm->setVar("msg_text", icms_core_DataFilter::filterHTMLinput($message, TRUE, TRUE, TRUE));
			$pm->setVar("to_userid", $to_userid);
			$pm->setVar("from_userid", (int) (icms::$user->getVar("uid")));
			if (!$pm_handler->insert($pm)) {
				echo $pm->getHtmlErrors() . "<br /><a href='javascript:history.go(-1)'>"
					. _PM_GOBACK . "</a>";
			} else {
				// Send a Private Message email notification
				$userHandler = icms::handler('icms_member_user');
				$toUser =& $userHandler->get($to_userid);
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
					$xoopsMailer->assign('X_SUBJECT', icms_core_DataFilter::stripSlashesGPC($subject));
					$xoopsMailer->assign('X_MESSAGE', icms_core_DataFilter::stripSlashesGPC($message));
					$xoopsMailer->assign('X_ITEM_URL', ICMS_URL . "/viewpmsg.php");
					$xoopsMailer->setSubject(sprintf(_PM_MESSAGEPOSTED_EMAILSUBJ, $icmsConfig['sitename']));
					$xoopsMailer->send();
				}
				redirect_header(icms_getPreviousPage(), 5, _PM_MESSAGEPOSTED);
				echo "<br /><br /><div style='text-align:center;'><h4>" . _PM_MESSAGEPOSTED
					. "</h4><br /><a href='" . ICMS_URL . "/viewpmsg.php'>"
					. _PM_CLICKHERE . "</a></div>";
			}
		}
	} elseif ($reply != 0 || $send != 0 || $send2 != 0) {
		/* This section is for composing messages */
		
		$msg_tmpl = "<form action='" . ICMS_URL . "/pmlite.php' method='post' name='coolsus'>\n"
			. "<table width='300' align='center' class='outer'><tr><td class='head' width='25%'>"
			. _PM_TO . "</td>";
		
		if ($reply != 0) {
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
			
			$subject = $pm->getVar('subject', 'E');
			if (!preg_match("/^Re:/i", $subject)) {
				$subject = 'Re: ' . $subject;
			}
			
			$msg_tmpl .=  "<td class='even'><input type='hidden' name='to_userid' value='" . $pm->getVar("from_userid") . "' />" . $pm_uname . "</td>";
		} elseif ($send2 != 0) {
			$to_username = icms_member_user_Object::getUnameFromId($to_userid);
			$msg_tmpl .=  "<td class='even'><input type='hidden' name='to_userid' value='" . $to_userid . "' />" . $to_username . "</td>";
		} else {
			$user_sel = new icms_form_elements_select_User("", "to_userid");
			$msg_tmpl .=  "<td class='even'>" . $user_sel->render() . "</td>";
		}
		
		$msg_tmpl .=  "</tr><tr><td class='head xoops-form-element-caption-required' width='25%'>" . _PM_SUBJECTC . "<span class='caption-marker'>*</span></td>";
		
		/* if subject == '', $subject is required */
		$msg_tmpl .=  "<td class='even'><input type='text' name='subject' value='" . $subject . "' size='30' maxlength='100' /></td>";
		
		$msg_tmpl .=  "</tr><tr valign='top'><td class='head' width='25%'>" . _PM_MESSAGEC . "</td><td class='even'>";
		
		$textarea = new icms_form_elements_Dhtmltextarea(_PM_MESSAGEC, 'message', $message);
		
		$msg_tmpl .=  $textarea->render()
			. "</td></tr>"
			. "<tr><td class='head'>&nbsp;</td><td class='even'>"
			. "<input type='hidden' name='op' value='submit' />" . icms::$security->getTokenHTML()
			. "<input type='submit' class='formButton' name='submit' value='" . _PM_SUBMIT
			. "' />&nbsp;<input type='reset' class='formButton' value='" . _PM_CLEAR
			. "' /></td></tr></table>\n"
			. "</form>\n";
		
		echo $msg_tmpl;
	}
}

xoops_footer();
