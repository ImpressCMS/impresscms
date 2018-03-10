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
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

$xoopsOption['pagetype'] = "pmsg";
require_once "mainfile.php";

/* check access permissions */
if (!is_object(icms::$user)) {
	redirect_header("user.php", 0);
}

/* set filter types, if not strings */
$filter_get = array(
		'start' => 'int',
		'total_messages' => 'int',
	);

$filter_post = array(
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
$pm_handler = icms::handler('icms_data_privmessage');

/* Is a message being deleted? */
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

$xoopsOption['template_main'] = 'system_readmsg.html';
require ICMS_ROOT_PATH . '/header.php';

$form = new icms_form_Simple('', 'delete', 'readpmsg.php', 'post', TRUE);

$criteria = new icms_db_criteria_Item('to_userid', (int) (icms::$user->getVar('uid')));
$criteria->setLimit(1);
$criteria->setStart($start);
$criteria->setSort('msg_time');

$pm_arr = $pm_handler->getObjects($criteria);

if (!$pm_handler->setRead($pm_arr[0])) { /* echo "failed"; */ }

$poster = new icms_member_user_Object((int) $pm_arr[0]->getVar("from_userid"));

if (!$poster->isActive()) {
	$poster = FALSE;
}

if (is_object($poster) == TRUE) { // no need to do this for deleted users
	$icmsTpl->assign(
			array(
				'uname' =>  $poster->getVar("uname"),
				'poster_id' =>  $poster->getVar("uid"),
				'gravatar' =>  $poster->gravatar('G', $GLOBALS['icmsConfigUser']['avatar_width']),
				'online' =>  $poster->isOnline()
			)
	);

	if ($poster->getVar("user_from") != "") {
		$icmsTpl->assign('from', $poster->getVar("user_from"));
	}

} else {
	$icmsTpl->assign('anonymous', $icmsConfig['anonymous']);
}

$var = $pm_arr[0]->getVar('msg_text', 'N');

// see if the sender had permission to use wysiwyg for the system module - in 2.0, everyone will
/* @todo remove editor permission check in 2.0 */
$permHandler = icms::handler('icms_member_groupperm');
$filterType = $permHandler->checkRight('use_wysiwygeditor', 1, $poster->getGroups()) ? 'html' : 'text';

$form->addElement(new icms_form_elements_Hidden('delete', '1'));
$form->addElement(new icms_form_elements_Hidden('msg_id', $pm_arr[0]->getVar("msg_id")));


$form->addElement(new icms_form_elements_Button('', 'delete_message', _DELETE, 'submit'));

$previous = $start - 1;
$next = $start + 1;

$icmsTpl->assign(
		array(
			'total_messages' => $total_messages,
			'messages' => $pm_arr,
			'uid' => icms::$user->getVar("uid"),
			'subject' => $pm_arr[0]->getVar("subject"),
			'poster' => $poster,
			'image' => $pm_arr[0]->getVar("msg_image", "E"),
			'sent_time' => formatTimestamp($pm_arr[0]->getVar("msg_time")),
			'message_body' => icms_core_DataFilter::checkVar($var, $filterType, 'output'),
			'msg_id' => $pm_arr[0]->getVar("msg_id"),
			'form' => $form->render(),
			'previous' => $previous,
			'next' => $next
		)
);

require "footer.php";
