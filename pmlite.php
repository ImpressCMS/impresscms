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
 */

/* this will set which language file will load for this page */
$xoopsOption['pagetype'] = "pmsg";

/* set filter types, if not strings */
$filter_get = array(
		'reply' => 'int',
		'send' => 'int',
		'send2' => 'int',
		'refresh' => 'int',
		'to_userid' => 'int',
		'msg_id' => 'int',
);

$filter_post = array(
		'to_userid' => 'int',
		'msg_id' => 'int',
		'subject' => 'str',
		'message' => 'html',
		'reply' => 'int',
);

/* set default values for variables */
$op = $subject = $message = "";
$reply = $send = $send2 = $refresh = $to_userid = $msg_id = 0;

/* filter the user input */
if (!empty($_GET)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_POST);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

if (empty($refresh) && !empty($op) && $op != _SUBMIT) {
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

$html = '';
/* Since we are using a popup/overlay - no need for any html headers/meta information */

if (!icms::$user) {
	/* Request is made by a non-user, or user that is not logged in */
	icms_core_Message::warning(
		_PM_PLZREG . " <a href='" . ICMS_URL . "/register.php'>" . _PM_REGISTERNOW . "</a> " . _OR . " <a href='" . ICMS_URL . "/user.php'>" . _LOGIN . "</a>",
		_PM_SORRY,
		true
	);
} else {
	if (!empty($op) && $op == _SUBMIT) {
		/* This section is for sending messages */
		if (!icms::$security->check()) {
			$security_error = true;
		}
		$res = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("users")
			. " WHERE uid='" . $to_userid . "'");
		list($count) = icms::$xoopsDB->fetchRow($res);
		if ($count != 1) {
			redirect_header(icms_getPreviousPage(), 5, _PM_USERNOEXIST . ' ' . _PM_PLZTRYAGAIN);
			if (isset($security_error) && $security_error == true) {
				redirect_header(icms_getPreviousPage(), 5, implode('<br />', icms::$security->getErrors()));
			}
		} else {
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm = & $pm_handler->create();
			$pm->setVar("subject", $subject);
			$pm->setVar("msg_text", $message);
			$pm->setVar("to_userid", $to_userid);
			$pm->setVar("from_userid", (int) (icms::$user->getVar("uid")));
			if (!$pm_handler->insert($pm)) {
				redirect_header(icms_getPreviousPage(), 5, $pm->getHtmlErrors());
			} else {
				// Send a Private Message email notification
				$userHandler = icms::handler('icms_member_user');
				$toUser = & $userHandler->get($to_userid);
				// Only send email notif if notification method is mail
				if ($toUser->getVar('notify_method') == 2) {
					$mailer = new icms_messaging_Handler();
					$mailer->useMail();
					$mailer->setToEmails($toUser->getVar('email'));
					if (icms::$user->getVar('user_viewemail')) {
						$mailer->setFromEmail(icms::$user->getVar('email'));
						$mailer->setFromName(icms::$user->getVar('uname'));
					} else {
						$mailer->setFromEmail($icmsConfig['adminmail']);
						$mailer->setFromName($icmsConfig['sitename']);
					}
					$mailer->setTemplate('new_pm.tpl');
					$mailer->assign('X_SITENAME', $icmsConfig['sitename']);
					$mailer->assign('X_SITEURL', ICMS_URL . "/");
					$mailer->assign('X_ADMINMAIL', $icmsConfig['adminmail']);
					$mailer->assign('X_UNAME', $toUser->getVar('uname'));
					$mailer->assign('X_FROMUNAME', icms::$user->getVar('uname'));
					$mailer->assign('X_SUBJECT', icms_core_DataFilter::stripSlashesGPC($subject));
					$mailer->assign('X_MESSAGE', icms_core_DataFilter::stripSlashesGPC($message));
					$mailer->assign('X_ITEM_URL', ICMS_URL . "/viewpmsg.php");
					$mailer->setSubject(sprintf(_PM_MESSAGEPOSTED_EMAILSUBJ, $icmsConfig['sitename']));
					$mailer->send();
				}
				redirect_header(icms_getPreviousPage(), 5, _PM_MESSAGEPOSTED);
			}
		}

	} elseif ($reply != 0 || $send != 0 || $send2 != 0) {
		/* This section is for composing messages */
		$theme = new icms_view_theme_Factory();
		$icmsTheme = & $theme->createInstance(array('contentTemplate' => @$xoopsOption['template_main'],));

		$form = new icms_form_Theme('', 'coolsus', ICMS_URL . '/pmlite.php', 'post', true);

		if ($reply != 0) {
			/* we are replying to a message */
			$pm_handler = icms::handler('icms_data_privmessage');
			$pm = & $pm_handler->get($msg_id);

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
			$userID = null;
		}

		$form->addElement(new icms_form_elements_select_User(_PM_TO, 'to_userid', false, $userID));
		$form->addElement(new icms_form_elements_Text(_SUBJECT, 'subject', 30, 100, $subject), true);
		$form->addElement(new icms_form_elements_Dhtmltextarea(_PM_MESSAGEC, 'message', $message));

		$tray = new icms_form_elements_Tray();
		$tray->addElement(new icms_form_elements_Button('', 'op', _SUBMIT, 'submit'));
		$tray->addElement(new icms_form_elements_Button('', '', _PM_CLEAR, 'reset'));

		$form->addElement($tray);

		$renderedForm = $form->render();
		$icmsTheme->renderMetas();

		$body .= $renderedForm;
	}
}

echo $body;

xoops_footer();
