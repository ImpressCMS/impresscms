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

if (empty($refresh) && !empty($op) && $op != _PM_SUBMIT) {
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
	icms_core_Message::warning(
		_PM_PLZREG . " <a href='" . ICMS_URL . "/register.php'>" . _PM_REGISTERNOW . "</a> " . _OR . " <a href='" . ICMS_URL . "/user.php'>" . _LOGIN . "</a>",
		_PM_SORRY,
		TRUE
	);
} else {
	if (!empty($op) && $op == _PM_SUBMIT) {
		/* This section is for sending messages */

		if (!icms::$security->check()) {
			$security_error = TRUE;
		}
		$res = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("users")
			. " WHERE uid='". $to_userid . "'");
		list($count) = icms::$xoopsDB->fetchRow($res);
		if ($count != 1) {
			redirect_header(icms_getPreviousPage(), 5, _PM_USERNOEXIST . ' ' . _PM_PLZTRYAGAIN);
			if (isset($security_error) && $security_error == TRUE) {
				redirect_header(icms_getPreviousPage(), 5, implode('<br />', icms::$security->getErrors()));
			}
		} else {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm =& $pm_handler->create();
			$pm->setVar("subject", icms_core_DataFilter::filterTextareaInput($subject));
			$pm->setVar("msg_text", icms_core_DataFilter::filterHTMLinput($message, TRUE, TRUE, TRUE));
			$pm->setVar("to_userid", $to_userid);
			$pm->setVar("from_userid", (int) (icms::$user->getVar("uid")));
			if (!$pm_handler->insert($pm)) {
				redirect_header(icms_getPreviousPage(), 5, $pm->getHtmlErrors());
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
			}
		}
	} elseif ($reply != 0 || $send != 0 || $send2 != 0) {
		/* This section is for composing messages */
		$form = new icms_form_Theme('', 'coolsus', ICMS_URL . '/pmlite.php', 'post', TRUE);

		if ($reply != 0) {
			/* we are replying to a message */
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

			$userID = $pm->getVar("from_userid");
		} elseif ($send2 != 0) {
			/* we are sending directly to a member */
			$userID = $to_userid;
		} else {
			/* we are composing a new message from our inbox */
			$userID = NULL;
		}

		$form->addElement(new icms_form_elements_select_User(_PM_TO, 'to_userid', FALSE, $userID));
		$form->addElement(new icms_form_elements_Text(_PM_SUBJECTC, 'subject', 30, 100, $subject), TRUE);
		$form->addElement(new icms_form_elements_Dhtmltextarea(_PM_MESSAGEC, 'message', $message));
		$form->addElement(new icms_form_elements_Button('', 'op', _PM_SUBMIT, 'submit'));
		$form->addElement(new icms_form_elements_Button('', '', _PM_CLEAR, 'reset'));

		$renderedForm = $form->render();

		echo $renderedForm;
	}
}

xoops_footer();
