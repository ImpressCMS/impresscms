<?php
// $Id: notification_update.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Handles all notification update functions within ImpressCMS
 *
 * @todo		This should be a method of the icms_data_notification_Handler class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: notification_update.php 12313 2013-09-15 21:14:35Z skenow $
 */

// RMV-NOTIFY

// This module expects the following arguments:
//
// not_submit
// not_redirect (to return back after update)
// not_mid (TODO)
// not_uid (TODO)
// not_list[1][params] = {category},{itemid},{event}
// not_list[1][status] = 1 if selected; 0 or missing if not selected
// etc...

// @TODO: can we put arguments in the not_redirect argument??? do we need
// to specially encode them first???

// @TODO: allow 'GET' also so we can process 'unsubscribe' requests??

if (!defined('ICMS_ROOT_PATH') || !is_object($icmsModule)) {
	exit();
}

include_once ICMS_ROOT_PATH.'/include/notification_constants.php';
icms_loadLanguageFile('core', 'notification');

if (!isset($_POST['not_submit'])) {
	exit();
}

if (!icms::$security->check()) {
	redirect_header($_POST['not_redirect'], 3, implode('<br />', icms::$security->getErrors()));
	exit();
}

// NOTE: in addition to the templates provided in the block and view
// modes, we can have buttons, etc. which load the arguments to be
// read by this script.  That way a module can really customize its
// look as to where/how the notification options are made available.

$update_list = $_POST['not_list'];

$module_id = $icmsModule->getVar('mid');
$user_id = is_object(icms::$user) ? icms::$user->getVar('uid') : 0;

// For each event, update the notification depending on the status.
// If status=1, subscribe to the event; otherwise, unsubscribe.

// FIXME: right now I just ignore database errors (e.g. if already
//  subscribed)... deal with this more gracefully?

$notification_handler = icms::handler('icms_data_notification');

foreach ($update_list as $update_item) {

	list($category, $item_id, $event) = explode( ',', $update_item['params'] );
	$status = !empty($update_item['status']) ? 1 : 0;

	if (!$status) {
		$notification_handler->unsubscribe($category, $item_id, $event, $module_id, $user_id);
	} else {
		$notification_handler->subscribe($category, $item_id, $event);
	}

}

// @TODO: something like grey box summary of actions (like multiple comment
// deletion), with a button to return back...  NOTE: we need some arguments
// to help us get back to where we were...

// @TODO: finish integration with comments... i.e. need calls to
// notifyUsers at appropriate places... (need to figure out where
// comment submit occurs and where comment approval occurs)...

$redirect_args = array();
foreach ($update_list as $update_item) {
	list($category,$item_id,$event) = explode( ',',$update_item['params'] );
	$category_info =& icms_data_notification_Handler::categoryInfo($category);
	if (!empty($category_info['item_name'])) {
		$redirect_args[$category_info['item_name']] = $item_id;
	}
}

// @TODO: write a central function to put together args with '?' and '&'
// symbols...
$argstring = '';
$first_arg = 1;
foreach (array_keys($redirect_args) as $arg) {
	if ($first_arg) {
		$argstring .= "?" . $arg . "=" . $redirect_args[$arg];
		$first_arg = 0;
	} else {
		$argstring .= "&" . $arg . "=" . $redirect_args[$arg];
	}
}

redirect_header ($_POST['not_redirect'].$argstring, 3, _NOT_UPDATEOK);
exit();

