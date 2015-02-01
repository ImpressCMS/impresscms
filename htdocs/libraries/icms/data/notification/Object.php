<?php
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
 * Manage Notifications
 *
 * @license		LICENSE.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 *
 * @category	ICMS
 * @package		Notification
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * A Notification
 *
 * @author	    Michael van Dam	<mvandam@caltech.edu>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @category	ICMS
 * @package     Notification
 */
class icms_data_notification_Object extends icms_core_Object {

	/**
	 * Constructor
	 **/
	public function __construct() {
		parent::__construct();
		$this->initVar('not_id', XOBJ_DTYPE_INT, NULL, false);
		$this->initVar('not_modid', XOBJ_DTYPE_INT, NULL, false);
		$this->initVar('not_category', XOBJ_DTYPE_TXTBOX, null, false, 30);
		$this->initVar('not_itemid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('not_event', XOBJ_DTYPE_TXTBOX, null, false, 30);
		$this->initVar('not_uid', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('not_mode', XOBJ_DTYPE_INT, 0, false);
	}

	// FIXME:???
	// To send email to multiple users simultaneously, we would need to move
	// the notify functionality to the handler class.  BUT, some of the tags
	// are user-dependent, so every email msg will be unique.  (Unless maybe use
	// smarty for email templates in the future.)  Also we would have to keep
	// track if each user wanted email or PM.

	/**
	 * Send a notification message to the user
	 *
	 * @param  string  $template_dir  Template directory
	 * @param  string  $template      Template name
	 * @param  string  $subject       Subject line for notification message
	 * @param  array   $tags Array of substitutions for template variables
	 *
	 * @return  bool	true if success, false if error
	 **/
	public function notifyUser($template_dir, $template, $subject, $tags) {
		global $icmsConfigMailer;
		// Check the user's notification preference.

		$member_handler = icms::handler('icms_member');
		$user =& $member_handler->getUser($this->getVar('not_uid'));
		if (!is_object($user)) {
			return true;
		}
		$method = $user->getVar('notify_method');

		$xoopsMailer = new icms_messaging_Handler();
		include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
		switch($method) {
			case XOOPS_NOTIFICATION_METHOD_PM:
				$xoopsMailer->usePM();
				$xoopsMailer->setFromUser($member_handler->getUser($icmsConfigMailer['fromuid']));
				foreach ($tags as $k=>$v) {
					$xoopsMailer->assign($k, $v);
				}
				break;

			case XOOPS_NOTIFICATION_METHOD_EMAIL:
				$xoopsMailer->useMail();
				foreach ($tags as $k=>$v) {
					$xoopsMailer->assign($k, preg_replace("/&amp;/i", '&', $v));
				}
				break;

			default:
				return true; // report error in user's profile??
				break;
		}

		// Set up the mailer
		$xoopsMailer->setTemplateDir($template_dir);
		$xoopsMailer->setTemplate($template);
		$xoopsMailer->setToUsers($user);
		//global $icmsConfig;
		//$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
		//$xoopsMailer->setFromName($icmsConfig['sitename']);
		$xoopsMailer->setSubject($subject);
		$success = $xoopsMailer->send();

		// If send-once-then-delete, delete notification
		// If send-once-then-wait, disable notification

		include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
		$notification_handler = icms::handler('icms_data_notification');

		if ($this->getVar('not_mode') == XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE) {
			$notification_handler->delete($this);
			return $success;
		}

		if ($this->getVar('not_mode') == XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT) {
			$this->setVar('not_mode', XOOPS_NOTIFICATION_MODE_WAITFORLOGIN);
			$notification_handler->insert($this);
		}
		return $success;
	}
}
