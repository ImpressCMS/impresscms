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
 * Manage avatars for users
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Data
 * @subpackage	Avatar
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Avatar class
 *
 * @author		Kazumi Ono (aka onokazo)
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @category	ICMS
 * @package		Data
 * @subpackage	Avatar
 *
 */
class icms_data_avatar_Object extends icms_core_Object {
	/** @var integer */
	private $_userCount;

	/**
	 * Constructor for avatar class, initializing all the properties of the class object
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->initVar('avatar_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('avatar_file', XOBJ_DTYPE_OTHER, null, false, 30);
		$this->initVar('avatar_name', XOBJ_DTYPE_TXTBOX, null, true, 100);
		$this->initVar('avatar_mimetype', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('avatar_created', XOBJ_DTYPE_INT, null, false);
		$this->initVar('avatar_display', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('avatar_weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('avatar_type', XOBJ_DTYPE_OTHER, 0, false);
	}

	/**
	 * Sets the value for the number of users
	 * @param integer $value
	 *
	 */
	public function setUserCount($value) {
		$this->_userCount = (int) $value;
	}

	/**
	 * Gets the value for the number of users
	 * @return integer
	 */
	public function getUserCount() {
		return $this->_userCount;
	}
}

