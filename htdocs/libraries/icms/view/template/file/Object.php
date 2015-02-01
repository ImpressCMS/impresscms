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
 * Template file object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 * @version		SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Base class for all templates
 *
 * @author Kazumi Ono (AKA onokazu)
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 **/
class icms_view_template_file_Object extends icms_core_Object {

	/**
	 * constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->initVar('tpl_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('tpl_refid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tpl_tplset', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('tpl_file', XOBJ_DTYPE_TXTBOX, null, true, 100);
		$this->initVar('tpl_desc', XOBJ_DTYPE_TXTBOX, null, false, 100);
		$this->initVar('tpl_lastmodified', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tpl_lastimported', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tpl_module', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('tpl_type', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('tpl_source', XOBJ_DTYPE_SOURCE, null, false);
	}

	/**
	 * Gets Template Source
	 */
	public function getSource()	{
		return $this->getVar('tpl_source');
	}

	/**
	 * Gets Last Modified timestamp
	 */
	public function getLastModified()	{
		return $this->getVar('tpl_lastmodified');
	}
}

