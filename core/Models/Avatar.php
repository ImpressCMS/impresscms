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
 * @author		Kazumi Ono (aka onokazo)
 */

namespace ImpressCMS\Core\Models;

/**
 * Avatar class
 *
 * @author	Kazumi Ono (aka onokazo)
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @package	ICMS\Data\Avatar
 *
 * @property int        $avatar_id         Avatar ID
 * @property string     $avatar_file       File used for avatar
 * @property string     $avatar_name       Name
 * @property string     $avatar_mimetype   Mimetype of avatar file
 * @property int        $avatar_created    When avatar was created?
 * @property int        $avatar_display    Do we need to show avatar?
 * @property int        $avatar_weight     Weight (used for sorting avatars for user)
 * @property string     $avatar_type       Type
 */
class Avatar extends \ImpressCMS\Core\IPF\AbstractModel {
	/** @var integer */
	private $_userCount;

	/**
	 * Constructor for avatar class, initializing all the properties of the class object
	 *
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('avatar_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('avatar_file', self::DTYPE_STRING, null, false, 30);
		$this->initVar('avatar_name', self::DTYPE_STRING, null, true, 100);
		$this->initVar('avatar_mimetype', self::DTYPE_STRING, null, false, 30);
		$this->initVar('avatar_created', self::DTYPE_INTEGER, null, false);
		$this->initVar('avatar_display', self::DTYPE_INTEGER, 1, false);
		$this->initVar('avatar_weight', self::DTYPE_INTEGER, 0, false);
		$this->initVar('avatar_type', self::DTYPE_STRING, '', false, 1);

                parent::__construct($handler, $data);
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

