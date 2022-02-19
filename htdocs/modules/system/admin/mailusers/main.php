<?php
// $Id: main.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Administration of mailusers, main mailusers file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Users
 * @version		SVN: $Id: main.php 12313 2013-09-15 21:14:35Z skenow $
 * @todo	scrub the input arrays (GET and POST)
 */

/*
 * GET variables
 * --none--
 *
 * POST variables
 * (str) op				send, form
 * (str) mail_send_to
 * (int) mail_inactive
 * (int) mail_mailok
 * (int) mail_lastlog_min
 * (int) mail_lastlog_max
 * (int) mail_idle_more
 * (int) mail_idle_less
 * (int) mail_regd_min
 * (int) mail_regd_max
 * (array - int) mail_to_group
 * (array - int) mail_to_user
 * (int) mail_start
 * (str) mail_fromname
 * (str) mail_subject
 * (str) mail_body
 *
 */

/* default values */
$op = "form";
$mail_send_to = $mail_fromname = $mail_subject = $mail_body = '';
$mail_inactive = $mail_mailok = $mail_lastlog_min = $mail_lastlog_max = 0;
$mail_idle_more = $mail_idle_less = $mail_regd_min = $mail_regd_max = 0;
$mail_to_group = $mail_to_user = $mail_start = 0;

/* Set filters */
$filter_get = array();

$filter_post = array(
	'op' => 'str',
	'mail_send_to' => 'str',
	'mail_inactive' => 'int',
	'mail_mailok' => 'int',
	'mail_lastlog_min' => 'int',
	'mail_lastlog_max' => 'int',
	'mail_idle_more' => 'int',
	'mail_idle_less' => 'int',
	'mail_regd_min' => 'int',
	'mail_regd_max' => 'int',
	'mail_to_group' => 'int',
	'mail_to_user' => 'int',
	'mail_start' => 'int',
	'mail_fromname' => 'str',
	'mail_subject' => 'str',
	'mail_body' => 'str'
);

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_GET);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
} else {
	$limit = 100;

	if (!icms::$security->check() || $op == "form") {
		icms_cp_header();
		echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/mailusers/images/mailusers_big.png)">' . _MD_AM_MLUS . '</div><br />';
		if ($op != "form" && $error_msg = icms::$security->getErrors(TRUE)) {
			echo "<div class='errorMsg'>{$error_msg}</div>";
		}
		$display_criteria = 1;
		include ICMS_MODULES_PATH . "/system/admin/mailusers/mailform.php";
		$form->display();
		icms_cp_footer();
	}

	if ($op == "send" && !empty($mail_send_to)) {
		$added = array();
		$added_id = array();
		$criteria = array();
		$count_criteria = 0; // user count via criteria;
		if (!empty($mail_inactive)) {
			$criteria[] = "level = 0";
		} else {
			if (!empty($mail_mailok)) {
				$criteria[] = 'user_mailok = 1';
			}
			if (!empty($mail_lastlog_min)) {
				$f_mail_lastlog_min = trim($mail_lastlog_min);
				$time = mktime(0, 0, 0, substr($f_mail_lastlog_min, 5, 2), substr($f_mail_lastlog_min, 8, 2), substr($f_mail_lastlog_min, 0, 4));
				if ($time > 0) {
					$criteria[] = "last_login > $time";
				}
			}
			if (!empty($mail_lastlog_max)) {
				$f_mail_lastlog_max = trim($mail_lastlog_max);
				$time = mktime(0, 0, 0, substr($f_mail_lastlog_max, 5, 2), substr($f_mail_lastlog_max, 8, 2), substr($f_mail_lastlog_max, 0, 4));
				if ($time > 0) {
					$criteria[] = "last_login < $time";
				}
			}
			if (!empty($mail_idle_more)) {
				$f_mail_idle_more = (int) ($mail_idle_more);
				$time = 60 * 60 * 24 * $f_mail_idle_more;
				$time = time() - $time;
				if ($time > 0) {
					$criteria[] = "last_login < $time";
				}
			}
			if (!empty($mail_idle_less)) {
				$f_mail_idle_less = $mail_idle_less;
				$time = 60 * 60 * 24 * $f_mail_idle_less;
				$time = time() - $time;
				if ($time > 0) {
					$criteria[] = "last_login > $time";
				}
			}
		}

		if (!empty($mail_regd_min)) {
			$f_mail_regd_min = trim($mail_regd_min);
			$time = mktime(0, 0, 0, substr($f_mail_regd_min, 5, 2), substr($f_mail_regd_min, 8, 2), substr($f_mail_regd_min, 0, 4));
			if ($time > 0) {
				$criteria[] = "user_regdate > $time";
			}
		}

		if (!empty($mail_regd_max)) {
			$f_mail_regd_max = trim($mail_regd_max);
			$time = mktime(0, 0, 0, substr($f_mail_regd_max, 5, 2), substr($f_mail_regd_max, 8, 2), substr($f_mail_regd_max, 0, 4));
			if ($time > 0) {
				$criteria[] = "user_regdate < $time";
			}
		}

		if (!empty($criteria) || !empty($mail_to_group)) {
			$criteria_object = new icms_db_criteria_Compo();
			$criteria_object->setStart(@$mail_start);
			$criteria_object->setLimit($limit);
			foreach ($criteria as $c) {
				list ($field, $op, $value) = explode(' ', $c);
				$crit = new icms_db_criteria_Item($field, $value, $op);
				$crit->prefix = "u";
				$criteria_object->add($crit, 'AND');
			}
			$member_handler = icms::handler('icms_member');
			// may not need this since we're now using checkVarArray
			// $groups = empty($mail_to_group) ? array() : array_map('intval', $mail_to_group);
			$getusers = $member_handler->getUsersByGroupLink($groups, $criteria_object, TRUE);
			$count_criteria = $member_handler->getUserCountByGroupLink($groups, $criteria_object);
			foreach ($getusers as $getuser) {
				if (!in_array($getuser->getVar("uid"), $added_id)) {
					$added[] = $getuser;
					$added_id[] = $getuser->getVar("uid");
				}
			}
		}

		if (!empty($mail_to_user)) {
			foreach ($mail_to_user as $to_user) {
				if (!in_array($to_user, $added_id)) {
					$added[] = new icms_member_user_Object($to_user);
					$added_id[] = $to_user;
				}
			}
		}

		$added_count = count($added);
		icms_cp_header();
		echo '<div class="CPbigTitle" style="background-image: url('. ICMS_MODULES_URL . '/system/admin/mailusers/images/mailusers_big.png)">' . _MD_AM_MLUS . '</div><br />';
		if ($added_count > 0) {
			$xoopsMailer = new icms_messaging_Handler();
			for ($i = 0; $i < $added_count; $i++) {
				$xoopsMailer->setToUsers($added[$i]);
			}

			$xoopsMailer->setFromName(icms_core_DataFilter::stripSlashesGPC($mail_fromname));
			$xoopsMailer->setFromEmail(icms_core_DataFilter::stripSlashesGPC($mail_fromemail));
			$xoopsMailer->setSubject(icms_core_DataFilter::stripSlashesGPC($mail_subject));
			$xoopsMailer->setBody(icms_core_DataFilter::stripSlashesGPC($mail_body));
			if (in_array("mail", $mail_send_to)) {
				$xoopsMailer->useMail();
			}
			if (in_array("pm", $mail_send_to) && empty($mail_inactive)) {
				$xoopsMailer->usePM();
			}

			$xoopsMailer->send(TRUE);
			echo $xoopsMailer->getSuccess();
			echo $xoopsMailer->getErrors();

			if ($count_criteria > $limit) {
				$form = new icms_form_Theme(_AM_SENDMTOUSERS, "mailusers", "admin.php?fct=mailusers", 'post', TRUE);
				if (!empty($mail_to_group)) {
					foreach ($mail_to_group as $mailgroup) {
						$group_hidden = new icms_form_elements_Hidden("mail_to_group[]", $mailgroup);
						$form->addElement($group_hidden);
					}
				}
				$inactive_hidden = new icms_form_elements_Hidden("mail_inactive", @$mail_inactive);
				$lastlog_min_hidden = new icms_form_elements_Hidden("mail_lastlog_min", $mail_lastlog_min, 'text');
				$lastlog_max_hidden = new icms_form_elements_Hidden("mail_lastlog_max", $mail_lastlog_max, 'text');
				$regd_min_hidden = new icms_form_elements_Hidden("mail_regd_min", $mail_regd_min, 'text');
				$regd_max_hidden = new icms_form_elements_Hidden("mail_regd_max", $mail_regd_max, 'text');
				$idle_more_hidden = new icms_form_elements_Hidden("mail_idle_more", $mail_idle_more, 'text');
				$idle_less_hidden = new icms_form_elements_Hidden("mail_idle_less", $mail_idle_less, 'text');
				$fname_hidden = new icms_form_elements_Hidden("mail_fromname", $mail_fromname, 'text');
				$femail_hidden = new icms_form_elements_Hidden("mail_fromemail", $mail_fromemail, 'text');
				$subject_hidden = new icms_form_elements_Hidden("mail_subject", $mail_subject, 'text');
				$body_hidden = new icms_form_elements_Hidden("mail_body", $mail_body, 'text');
				$start_hidden = new icms_form_elements_Hidden("mail_start", $mail_start + $limit);
				$mail_mailok_hidden = new icms_form_elements_Hidden("mail_mailok", $mail_mailok);
				$op_hidden = new icms_form_elements_Hidden("op", "send");
				$submit_button = new icms_form_elements_Button("", "mail_submit", _AM_SENDNEXT, "submit");
				$sent_label = new icms_form_elements_Label(_AM_SENT, sprintf(_AM_SENTNUM, $mail_start + 1, $mail_start + $limit, $count_criteria + $added_count - $limit));
				$form->addElement($sent_label);
				$form->addElement($inactive_hidden);
				$form->addElement($lastlog_min_hidden);
				$form->addElement($lastlog_max_hidden);
				$form->addElement($regd_min_hidden);
				$form->addElement($regd_max_hidden);
				$form->addElement($idle_more_hidden);
				$form->addElement($idle_less_hidden);
				$form->addElement($fname_hidden);
				$form->addElement($femail_hidden);
				$form->addElement($subject_hidden);
				$form->addElement($body_hidden);
				$form->addElement($op_hidden);
				$form->addElement($start_hidden);
				$form->addElement($mail_mailok_hidden);
				if (isset($mail_send_to) && is_array($mail_send_to)) {
					foreach ($mail_send_to as $v) {
						$form->addElement(new icms_form_elements_Hidden("mail_send_to[]", $v));
					}
				} else {
					$to_hidden = new icms_form_elements_Hidden("mail_send_to", 'mail');
					$form->addElement($to_hidden);
				}
				$form->addElement($submit_button);
				$form->display();
			} else {
				echo "<h4>" . _AM_SENDCOMP . "</h4>";
			}
		} else {
			echo "<h4>" . _AM_NOUSERMATCH . "</h4>";
		}
		icms_cp_footer();
	}
}
