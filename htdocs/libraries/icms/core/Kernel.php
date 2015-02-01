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
 * ICMS kernel Base Class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Core
 * @subpackage	Kernel
 * @since		1.1
 * @version		SVN: $Id: Kernel.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * Old 1.2- kernel class
 *
 * This class has been replaced by the static "icms" class, to prevent pollution of the global
 *  namespace. Please use icms::method() now, instead of $GLOBALS["impresscms"]->method();
 *
 * @category	ICMS
 * @package		core
 * @since 		1.1
 *
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @deprecated	This should not even end up in the 1.3 final package - it was introduced during the refactoring
 * @todo		Remove this before 1.3 final
 */
class icms_core_Kernel extends icms_core_Object {

	public $paths;
	public $urls;

	public function __construct() {
		$this->paths =& icms::$paths;
		$this->urls =& icms::$urls;
	}
	/**
	 * Convert a ImpressCMS path to a physical one
	 * @param	string	$url URL string to convert to a physical path
	 * @param 	boolean	$virtual
	 * @return 	string
	 */
	public function path($url, $virtual = false) {
		return icms::path($url, $virtual);
	}
	/**
	 * Convert a ImpressCMS path to an URL
	 * @param 	string	$url
	 * @return 	string
	 */
	public function url($url) {
		return icms::url($url);
	}
	/**
	 * Build an URL with the specified request params
	 * @param 	string 	$url
	 * @param 	array	$params
	 * @return 	string
	 */
	public function buildUrl($url, $params = array()) {
		return icms::buildUrl($url,$params);
	}

}

