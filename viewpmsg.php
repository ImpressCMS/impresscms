<?php
// $Id: viewpmsg.php 12363 2013-11-01 05:06:13Z sato-san $
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
 * View and manage your private messages
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author      sato-san <sato-san@impresscms.org>
 * @package		core
 * @subpackage	Privmessage
 */
$xoopsOption['pagetype'] = 'pmsg';

/* set filter types, if not strings */
$filter_get = array(
);

$filter_post = array(
		'msg_id' => 'int',
		'delete_messages' => 'str',
);

/* set default values for variables */
$msg_id = $delete = 0;
$delete_messages = '';

/* filter the user input */
if (!empty($_GET)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_POST);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

/* we do not have this module in our repository, nor is it on addons - unsupported? skenow 14 July 2014
$module_handler = icms::handler('icms_module');
$messenger_module = $module_handler->getByDirname('messenger');
if ($messenger_module && $messenger_module->getVar('isactive')) {
	header('location: ./modules/messenger/msgbox.php');
	exit();
}
*/

if (!is_object(icms::$user)) {
	$errormessage = _PM_SORRY . '<br />' . _PM_PLZREG . '';
	redirect_header('user.php', 2, $errormessage);
}

$pm_handler = icms::handler('icms_data_privmessage');
if (!empty($delete_messages) && isset($msg_id)) {
	if (!icms::$security->check()) {
		echo implode('<br />', icms::$security->getErrors());
		exit();
	}
	$size = count($msg_id);
	for ($i = 0; $i < $size; $i++) {
		$pm =& $pm_handler->get($msg_id[$i]);
		if ($pm->getVar('to_userid') == icms::$user->getVar('uid')) {
			$pm_handler->delete($pm);
		}
		unset($pm);
	}
	redirect_header('viewpmsg.php', 1, _PM_DELETED);
}

$xoopsOption['template_main'] = 'system_viewmsgs.html';
require ICMS_ROOT_PATH . '/header.php';

$criteria = new icms_db_criteria_Item('to_userid', (int) (icms::$user->getVar('uid')));
$criteria->setOrder('DESC');
$pm_arr =& $pm_handler->getObjects($criteria);

/* get and properly treat the values of each of the objects' properties */
foreach ($pm_arr as $id => $message) {
	$message_list[$id] = $message->getValues();
	$message_list[$id]['sender'] = icms_member_user_Object::getUnameFromId($message_list[$id]['from_userid']);
	$message_list[$id]['sent_time'] = formatTimestamp($message_list[$id]['msg_time']);
}

$icmsTpl->assign(
	array(
		'uid' => icms::$user->getVar('uid'),
		'messages' => $message_list,
		'token' => icms::$security->getTokenHTML(),
	)
);

require 'footer.php';
