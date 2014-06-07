<?php
// $Id: readpmsg.php 12363 2013-11-01 05:06:13Z sato-san $
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
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id: readpmsg.php 12363 2013-11-01 05:06:13Z sato-san $
 */

$xoopsOption['pagetype'] = "pmsg";
require_once "mainfile.php";

/* set filter types, if not strings */
$filter_get[] = array(
		'start' => 'int',
		'total_messages' => 'int',
	);

$filter_post[] = array(
		'msg_id' => 'int',
		'delete' => 'int',
	);

/* set default values for variables */
$start = $total_messages = $msg_id = $delete = 0;

/* filter the user input */
if (!empty($_GET)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_POST);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

/* begin page logic */
if (!is_object(icms::$user)) {
	redirect_header("user.php", 0);
}

$pm_handler = icms::handler('icms_data_privmessage');

if ($delete != 0) {
	if (!icms::$security->check()) {
		echo implode('<br />', icms::$security->getErrors());
		exit();
	}

	$pm =& $pm_handler->get($msg_id);

	if (!is_object($pm) || $pm->getVar('to_userid') != icms::$user->getVar('uid') || !$pm_handler->delete($pm)) {
		exit();
	}

	redirect_header("viewpmsg.php", 1, _PM_DELETED);
}

require ICMS_ROOT_PATH . '/header.php';

$criteria = new icms_db_criteria_Item('to_userid', (int) (icms::$user->getVar('uid')));
$criteria->setLimit(1);
$criteria->setStart($start);
$criteria->setSort('msg_time');
$pm_arr = $pm_handler->getObjects($criteria);

$echo = "<div><h4>". _PM_PRIVATEMESSAGE."</h4></div><br /><a href='userinfo.php?uid="
	. (int) (icms::$user->getVar("uid")) ."'>". _PROFILE
	."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<a href='viewpmsg.php'>"
	. _PM_INBOX ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;\n";

if (empty($pm_arr)) {
	$echo .= '<br /><br />'._PM_YOUDONTHAVE;
} else {
	if (!$pm_handler->setRead($pm_arr[0])) { /* echo "failed"; */ }

	$echo .= $pm_arr[0]->getVar("subject")."<br /><table border='0' cellpadding='4' cellspacing='1' class='outer' width='100%'><tr><th colspan='1'>"
				. _PM_FROM ."</th></tr><tr class='even'>\n";
	$poster = new icms_member_user_Object((int) $pm_arr[0]->getVar("from_userid"));

	if (!$poster->isActive()) {
		$poster = false;
	}

	$echo .= "<td valign='top'>";

	if ($poster != false) { // we need to do this for deleted users
		$echo .= "<a href='userinfo.php?uid=". (int) ($poster->getVar("uid"))."'>".$poster->getVar("uname")."</a><br />\n";

		if ($poster->getVar("user_avatar") != "") {
			$echo .= "<img src='uploads/".$poster->getVar("user_avatar")."' alt='' /><br />\n";
		}

		if ($poster->getVar("user_from") != "") {
			$echo .= _PM_FROM . ": " . $poster->getVar("user_from") . "<br /><br />\n";
		}

		if ($poster->isOnline()) {
			$echo .= "<span style='color:#ee0000;font-weight:bold;'>" . _PM_ONLINE . "</span><br /><br />\n";
		}
	} else {
		$echo .= $icmsConfig['anonymous']; // we need to do this for deleted users
	}

	$echo .= "<br /><img src='images/subject/" . $pm_arr[0]->getVar("msg_image", "E")
		."' alt='' />&nbsp;"
		._PM_SENTC . "" . formatTimestamp($pm_arr[0]->getVar("msg_time"));
	$echo .= "<hr /><b>".$pm_arr[0]->getVar("subject")."</b><br /><br />\n";

	$var = $pm_arr[0]->getVar('msg_text', 'N');

	// see if the sender had permission to use wysiwyg for the system module - in 2.0, everyone will
	/* @todo remove editor permission check in 2.0 */
	$permHandler = icms::handler('icms_member_groupperm');
	$filterType = $permHandler->checkRight('use_wysiwygeditor', 1, $poster->getGroups()) ? 'html' : 'text';

	$echo .= icms_core_DataFilter::checkVar($var, $filterType, 'output')
		. "<br /><br /></td></tr><tr class='foot'><td width='20%' align='"
		. _GLOBAL_LEFT . "'>";

	if ($poster != false) {
	// we dont want to reply to a deleted user!
		$echo .= "<a href='".ICMS_URL."/pmlite.php?reply=1&amp;msg_id="
			. $pm_arr[0]->getVar("msg_id")
			."' class='cboxElement'><input type='button' class='formButton' value='"
			. _REPLY . "' /></a>\n";
	}

	$form = new icms_form_Simple('', 'delete', 'readpmsg.php', 'post', TRUE);
	$form->addElement(new icms_form_elements_Hidden('delete', '1'));
	$form->addElement(new icms_form_elements_Hidden('msg_id', $pm_arr[0]->getVar("msg_id")));
	$form->addElement(new icms_form_elements_Button('', 'delete_message', _DELETE, 'submit'));

	$echo .= $form->render();

	$echo .= "</td></tr><tr><td>";

	$previous = $start - 1;
	$next = $start + 1;

	if ($previous >= 0) {
		$echo .= "<a href='readpmsg.php?start=" . $previous
			. "&amp;total_messages=" . $total_messages
			. "' style='float:" . _GLOBAL_LEFT . "'>"
			. _PM_PREVIOUS . "</a> ";
	}

	if ($next < $total_messages) {
		$echo .= " <a href='readpmsg.php?start=" .  $next
			. "&amp;total_messages=" . $total_messages
			. "' style='float:" . _GLOBAL_RIGHT . "'>"
			. _PM_NEXT . "</a>";
	}

	$echo .= "</td></tr></table>\n";
}

echo $echo;

require "footer.php";
