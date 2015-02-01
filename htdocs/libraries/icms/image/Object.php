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
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Image
 * @version		SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 * An Image Object
 *
 * @category	ICMS
 * @package		Image
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
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
		$this->initVar('image_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('image_name', XOBJ_DTYPE_OTHER, null, false, 30);
		$this->initVar('image_nicename', XOBJ_DTYPE_TXTBOX, null, true, 100);
		$this->initVar('image_mimetype', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('image_created', XOBJ_DTYPE_INT, null, false);
		$this->initVar('image_display', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('image_weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('image_body', XOBJ_DTYPE_SOURCE, null, true);
		$this->initVar('imgcat_id', XOBJ_DTYPE_INT, 0, false);
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
