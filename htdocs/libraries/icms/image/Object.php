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
 * Manage images
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org 
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * An Image Object
 *
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\Image
 * 
 * @property int    $image_id          Image ID
 * @property string $image_name        Name
 * @property string $image_nicename    Nice name
 * @property string $image_mimetype    Mimetype
 * @property int    $image_created     When was created?
 * @property int    $image_display     Show be this image displayed on selection?
 * @property int    $image_weight      Weight used for sorting for user
 * @property string $image_body        Image contents
 * @property int    $imgcat_id         Image category ID
 */
class icms_image_Object extends icms_core_Object {
	/**
	 * Info of Image file (width, height, bits, mimetype)
	 *
	 * @var array
	 */
	public $image_info = array();

	/**
	 * Constructor
	 **/
	public function __construct() {
		parent::__construct();
		$this->initVar('image_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('image_name', self::DTYPE_STRING, null, false, 30);
		$this->initVar('image_nicename', self::DTYPE_STRING, null, true, 100);
		$this->initVar('image_mimetype', self::DTYPE_STRING, null, false, 30);
		$this->initVar('image_created', self::DTYPE_INTEGER, null, false);
		$this->initVar('image_display', self::DTYPE_INTEGER, 1, false);
		$this->initVar('image_weight', self::DTYPE_INTEGER, 0, false);
		$this->initVar('image_body', self::DTYPE_STRING, null, true, array(
                    self::VARCFG_SOURCE_FORMATING => 'binary'
                ));
		$this->initVar('imgcat_id', self::DTYPE_INTEGER, 0, false);
	}

	/**
	 * Returns information
	 *
	 * @param string  $path  the path to search through
	 * @param string  $type  the path type, url or other
	 * @param bool  $ret  return the information or keep it stored
	 *
	 * @return array  the array of image information
	 */
	public function getInfo($path, $type = 'url', $ret = false) {
		$path = (substr($path,-1) != '/') ? $path . '/' : $path;
		if ($type == 'url') {
			$img = $path . $this->getVar('image_name');
		} else {
			$img = $path;
		}
		$get_size = getimagesize($img);
		$this->image_info = array(
			'width' => $get_size[0],
			'height' => $get_size[1],
			'bits' => $get_size['bits'],
			'mime' => $get_size['mime']
		);
		if ($ret) {
			return $this->image_info;
		}
	}
}
