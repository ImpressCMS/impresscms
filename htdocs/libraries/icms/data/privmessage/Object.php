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
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 *
 * @category	ICMS
 * @package		Privmessage
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A handler for Private Messages
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @category	ICMS
 * @package		Privmessage
 */
class icms_data_privmessage_Object extends icms_core_Object {

	/**
	 * constructor
	 **/
	public function __construct() {
		parent::__construct();
		$this->initVar('msg_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('msg_image', XOBJ_DTYPE_OTHER, 'icon1.gif', false, 100);
		$this->initVar('subject', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('from_userid', XOBJ_DTYPE_INT, null, true);
		$this->initVar('to_userid', XOBJ_DTYPE_INT, null, true);
		$this->initVar('msg_time', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('msg_text', XOBJ_DTYPE_TXTAREA, null, true);
		$this->initVar('read_msg', XOBJ_DTYPE_INT, 0, false);
	}
}

