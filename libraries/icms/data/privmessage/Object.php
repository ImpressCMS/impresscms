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
 * Private messages
 *
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 */

/**
 * A handler for Private Messages
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @package	ICMS\Data\Privmessage
 *
 * @property int    $msg_id       Message ID
 * @property string $msg_image    Image
 * @property string $subject      Subject
 * @property int    $from_userid  From what User ID
 * @property int    $to_userid    To what User ID
 * @property int    $msg_time     Sending time
 * @property string $msg_text     Text (content)
 * @property int    $read_msg     Is this message read?
 */
class icms_data_privmessage_Object extends icms_ipf_Object {

	/**
	 * constructor
	 **/
	public function __construct(&$handler, $data = array()) {
		$this->initVar('msg_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('msg_image', self::DTYPE_STRING, 'icon1.gif', false, 100);
		$this->initVar('subject', self::DTYPE_STRING, null, true, 255);
		$this->initVar('from_userid', self::DTYPE_INTEGER, null, true);
		$this->initVar('to_userid', self::DTYPE_INTEGER, null, true);
		$this->initVar('msg_time', self::DTYPE_DATETIME, null, false);
		$this->initVar('msg_text', self::DTYPE_STRING, null, true);
		$this->initVar('read_msg', self::DTYPE_INTEGER, 0, false);

                parent::__construct($handler, $data);
	}
}

