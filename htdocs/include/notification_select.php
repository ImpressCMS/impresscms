<?php
// $Id: notification_select.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Handles all notification select functions within ImpressCMS
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
 * @version	$Id: notification_select.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH')) {
	exit();
}
include_once ICMS_ROOT_PATH.'/include/notification_constants.php';
$xoops_notification = array();
$xoops_notification['show'] = isset($icmsModule) && is_object(icms::$user) && icms_data_notification_Handler::isEnabled('inline') ? 1 : 0;
if ($xoops_notification['show']) {
	icms_loadLanguageFile('core', 'notification');
	$notification_handler = icms::handler('icms_data_notification');
	$categories =& $notification_handler->subscribableCategoryInfo();
	$event_count = 0;
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$section['name'] = $category['name'];
			$section['title'] = $category['title'];
			$section['description'] = $category['description'];
			$section['itemid'] = $category['item_id'];
			$section['events'] = array();
			$subscribed_events = $notification_handler->getSubscribedEvents($category['name'], $category['item_id'], $icmsModule->getVar('mid'), icms::$user->getVar('uid'));
			foreach ($notification_handler->categoryEvents($category['name'], true) as $event) {
				if (!empty($event['admin_only']) && !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
					continue;
				}
				if (!empty($event['invisible'])) {
					continue;
				}
				$subscribed = in_array($event['name'], $subscribed_events) ? 1 : 0;
				$section['events'][$event['name']] = array ('name'=>$event['name'], 'title'=>$event['title'], 'caption'=>$event['caption'], 'description'=>$event['description'], 'subscribed'=>$subscribed);
				$event_count ++;
			}
			$xoops_notification['categories'][$category['name']] = $section;
		}
		$xoops_notification['target_page'] = "notification_update.php";
		$xoops_notification['redirect_script'] = xoops_getenv('PHP_SELF');
		$xoopsTpl->assign(array('lang_activenotifications' => _NOT_ACTIVENOTIFICATIONS, 'lang_notificationoptions' => _NOT_NOTIFICATIONOPTIONS, 'lang_updateoptions' => _NOT_UPDATEOPTIONS, 'lang_updatenow' => _NOT_UPDATENOW, 'lang_category' => _NOT_CATEGORY, 'lang_event' => _NOT_EVENT, 'lang_events' => _NOT_EVENTS, 'lang_checkall' => _NOT_CHECKALL, 'lang_notificationmethodis' => _NOT_NOTIFICATIONMETHODIS, 'lang_change' => _NOT_CHANGE, 'editprofile_url' => ICMS_URL . '/edituser.php?uid=' . icms::$user->getVar('uid')));
		switch (icms::$user->getVar('notify_method')) {
			case XOOPS_NOTIFICATION_METHOD_DISABLE:
				$xoopsTpl->assign('user_method', _NOT_DISABLE);
				break;
			case XOOPS_NOTIFICATION_METHOD_PM:
				$xoopsTpl->assign('user_method', _NOT_PM);
				break;
			case XOOPS_NOTIFICATION_METHOD_EMAIL:
				$xoopsTpl->assign('user_method', _NOT_EMAIL);
				break;
		}
	} else {
		$xoops_notification['show'] = 0;
	}
	if ($event_count == 0) {
		$xoops_notification['show'] = 0;
	}
}
$xoopsTpl->assign('xoops_notification', $xoops_notification);

