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
 * @license	LICENSE.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 */

/**
 * A Notification
 *
 * @author	Michael van Dam	<mvandam@caltech.edu>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @package	ICMS\Data\Notification
 *
 * @property int    $not_id        Notification ID
 * @property int    $not_modid     Module ID linked with this notification
 * @property string $not_category  Category
 * @property int    $not_itemid    Item ID linked with this notification
 * @property string $not_event     Notification event
 * @property int    $not_uid       User ID who receives this notification
 * @property int    $not_mode      How this notification should be received?
 */
class icms_data_notification_Object extends icms_ipf_Object {

	/**
	 * Constructor
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('not_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('not_modid', self::DTYPE_INTEGER, null, false);
		$this->initVar('not_category', self::DTYPE_STRING, null, false, 30);
		$this->initVar('not_itemid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('not_event', self::DTYPE_STRING, null, false, 30);
		$this->initVar('not_uid', self::DTYPE_INTEGER, 0, true);
		$this->initVar('not_mode', self::DTYPE_INTEGER, 0, false);

        parent::__construct($handler, $data);
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
	 */
	public function notifyUser($template_dir, $template, $subject, $tags) {
		global $icmsConfigMailer;
		// Check the user's notification preference.

		$member_handler = icms::handler('icms_member');
		$user = & $member_handler->getUser($this->getVar('not_uid'));
		if (!is_object($user)) {
			return true;
		}
		$method = $user->getVar('notify_method');

		$mailer = new icms_messaging_Handler();
		include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
		switch ($method) {
			case XOOPS_NOTIFICATION_METHOD_PM:
				$mailer->usePM();
				$mailer->setFromUser($member_handler->getUser($icmsConfigMailer['fromuid']));
				foreach ($tags as $k=>$v) {
					$mailer->assign($k, $v);
				}
				break;

			case XOOPS_NOTIFICATION_METHOD_EMAIL:
				$mailer->useMail();
				foreach ($tags as $k=>$v) {
					$mailer->assign($k, preg_replace("/&amp;/i", '&', $v));
				}
				break;

			default:
				return true; // report error in user's profile??
				break;
		}

		// Set up the mailer
		$mailer->setTemplateDir($template_dir);
		$mailer->setTemplate($template);
		$mailer->setToUsers($user);
		//global $icmsConfig;
		//$mailer->setFromEmail($icmsConfig['adminmail']);
		//$mailer->setFromName($icmsConfig['sitename']);
		$mailer->setSubject($subject);
		$success = $mailer->send();

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
