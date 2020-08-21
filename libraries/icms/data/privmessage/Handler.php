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
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Manage private messages
 *
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 */

/**
 * Private message handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of private message class objects.
 *
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2007 XOOPS.org
 * @package    ICMS\Data\Privmessage
 */
class icms_data_privmessage_Handler extends icms_ipf_Handler
{

	public function __construct(&$db)
	{
		parent::__construct($db, 'data_privmessage', 'msg_id', 'subject', 'msg_text', 'icms', 'priv_msgs', 'msg_id');
	}

	/**
	 * Mark a message as read
	 * @param \icms_data_privmessage_Object $pm Private message
	 * @return    bool
	 */
	public function setRead(&$pm)
	{
		if (!is_a($pm, 'icms_data_privmessage_Object')) {
			return false;
		}

		$sql = sprintf("UPDATE %s SET read_msg = '1' WHERE msg_id = '%u'", $this->table, (int)$pm->msg_id);
		if (!$this->db->queryF($sql)) {
			return false;
		}
		return true;
	}

	/**
	 * Gets message count for user
	 *
	 * @param icms_member_user_Object|null $user User for whom get message count
	 *
	 * @return int
	 */
	public function getCountForUser(\icms_member_user_Object $user = null): int {
		static $msgCount = [];
		if ($user === null) {
			$user = icms::$user;
		}
		if (!isset($msgCount[$user->uid])) {
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('read_msg', 0));
			$criteria->add(new icms_db_criteria_Item('to_userid', $user->uid));
			$msgCount[$user->uid] = (int)$this->getCount($criteria);
		}
		return $msgCount[$user->uid];
	}
}

