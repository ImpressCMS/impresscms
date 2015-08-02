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
 * Core class for managing comments
 *
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright 	http://www.impresscms.org/ The ImpressCMS Project
 *
 * @category	ICMS
 * @package		Data
 * @subpackage	Comment
 *
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A Comment
 *
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @category	ICMS
 * @package		Data
 * @subpackage	Comment
 *
 */
class icms_data_comment_Object extends icms_ipf_Object {

	/**
	 * Constructor
	 **/
	public function __construct(&$handler, $data = array()) {		
		$this->initVar('com_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('com_pid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_modid', self::DTYPE_INTEGER, null, false);
		$this->initVar('com_icon', self::DTYPE_STRING, null, false, 25);
		$this->initVar('com_title', self::DTYPE_STRING, null, true, 255, true);
		$this->initVar('com_text', self::DTYPE_STRING, null, true, null, true);
		$this->initVar('com_created', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_modified', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_uid', self::DTYPE_INTEGER, 0, true);
		$this->initVar('com_ip', self::DTYPE_STRING, null, false, 15);
		$this->initVar('com_sig', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_itemid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_rootid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_status', self::DTYPE_INTEGER, 0, false);
		$this->initVar('com_exparams', self::DTYPE_STRING, null, false, 255);
		$this->initVar('dohtml', self::DTYPE_INTEGER, 0, false);
		$this->initVar('dosmiley', self::DTYPE_INTEGER, 0, false);
		$this->initVar('doxcode', self::DTYPE_INTEGER, 0, false);
		$this->initVar('doimage', self::DTYPE_INTEGER, 0, false);
		$this->initVar('dobr', self::DTYPE_INTEGER, 0, false);
                
                parent::__construct($handler, $data);
	}

	/**
	 * Is this comment on the root level?
	 *
	 * @return  bool
	 **/
	public function isRoot() {
		return ($this->getVar('com_id') == $this->getVar('com_rootid'));
	}
}
