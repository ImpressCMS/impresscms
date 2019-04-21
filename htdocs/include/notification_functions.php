<?php
// $Id: notification_functions.php 12313 2013-09-15 21:14:35Z skenow $
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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

/**
 * Handles some notification functions within ImpressCMS
 * @deprecated	These have been relocated into the proper class
 * @todo		Move these functions into icms_data_notification_Handler class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: notification_functions.php 12313 2013-09-15 21:14:35Z skenow $
 */

// RMV-NOTIFY

// FIXME: Do some caching, so we don't retrieve the same category / event
// info many times.

/**
 * Determine if notification is enabled for the selected module.
 *
 * @param  string  $style	  Subscription style: 'block' or 'inline'
 * @param  int	 $module_id  ID of the module  (default current module)
 * @return bool
 * @deprecated	Use the notification class method instead - isEnabled
 * @todo
 */
function notificationEnabled($style, $module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::isEnabled', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::isEnabled($style);
}

/**
 * Get an associative array of info for a particular notification
 * category in the selected module.  If no category is selected,
 * return an array of info for all categories.
 *
 * @param  string  $name	   Category name (default all categories)
 * @param  int	 $module_id  ID of the module (default current module)
 * @return mixed
 * @deprecated	Use the notification class method instead - categoryInfo
 * @todo
 */
function &notificationCategoryInfo($category_name='', $module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::categoryInfo', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::categoryInfo();
}

/**
 * Get associative array of info for the category to which comment events
 * belong.
 *
 * @todo This could be more efficient... maybe specify in
 *		$modversion['comments'] the notification category.
 *	   This would also serve as a way to enable notification
 *		of comments, and also remove the restriction that
 *		all notification categories must have unique item_name. (TODO)
 *
 * @param  int  $module_id  ID of the module (default current module)
 * @return mixed			Associative array of category info
 * @deprecated	Use the notification class method instead - commentCategoryInfo
 * @todo
 */
function &notificationCommentCategoryInfo($module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::commentCategoryInfo', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::commentCategoryInfo();
}

// TODO: some way to include or exclude admin-only events...

/**
 * Get an array of info for all events (each event has associative array)
 * in the selected category of the selected module.
 *
 * @param  string  $category_name  Category name
 * @param  bool	$enabled_only   If true, return only enabled events
 * @param  int	 $module_id	  ID of the module (default current module)
 * @return mixed
 * @deprecated	Use the notification class method instead - categoryEvents
 * @todo
 */
function &notificationEvents($category_name, $enabled_only, $module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::categoryEvents', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::categoryEvents($category_name, $enabled_only, $module_id);

}

/**
 * Determine whether a particular notification event is enabled.
 * Depends on module config options.
 *
 * @todo  Check that this works correctly for comment and other
 *   events which depend on additional config options...
 *
 * @param  array  $category  Category info array
 * @param  array  $event	 Event info array
 * @param  object $module	Module
 * @return bool
 * @deprecated	Use the notification class method instead - eventEnabled
 * @todo
 **/
function notificationEventEnabled(&$category, &$event, &$module) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::eventEnabled', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::eventEnabled($category, $event, $module);

}

/**
 * Get associative array of info for the selected event in the selected
 * category (for the selected module).
 *
 * @param  string  $category_name  Notification category
 * @param  string  $event_name	 Notification event
 * @param  int	 $module_id	  ID of the module (default current module)
 * @return mixed
 * @deprecated	Use the notification class method instead - eventInfo
 * @todo
 */
function &notificationEventInfo($category_name, $event_name, $module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::eventInfo', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::eventInfo($category_name, $event_name);

}

/**
 * Get an array of associative info arrays for subscribable categories
 * for the selected module.
 *
 * @param  int  $module_id  ID of the module
 * @return mixed
 * @deprecated	Use the notification class method instead - subscribableCategoryInfo
 * @todo
 */
function &notificationSubscribableCategoryInfo($module_id=null) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::subscribableCategoryInfo', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::subscribableCategoryInfo($module_id);

}

/**
 * Generate module config info for a particular category, event pair.
 * The selectable config options are given names depending on the
 * category and event names, and the text depends on the category
 * and event titles.  These are pieced together in this function in
 * case we wish to alter the syntax.
 *
 * @param  array  $category  Array of category info
 * @param  array  $event	 Array of event info
 * @param  string $type	  The particular name to generate
 * return string
 * @deprecated	Use the notification class method instead - generateConfig
 * @todo
 **/
function notificationGenerateConfig(&$category, &$event, $type) {
	icms_core_Debug::setDeprecated('icms_data_notification_Handler::generateConfig', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	return icms_data_notification_Handler::generateConfig($category, $event, $type);

}