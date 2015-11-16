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
/**
 * Manage configuration options
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	Kazumi Ono (aka onokazo)
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A Config-Option
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @package	ICMS\Config\Option
 * 
 * @property int        $confop_id     Config option id
 * @property string     $confop_name   Name
 * @property string     $confop_value  Value
 * @property int        $conf_id       Config ID
 */
class icms_config_option_Object extends icms_ipf_Object {
	/**
	 * Constructor
	 */
	public function __construct(&$handler, $data = array()) {		
		$this->initVar('confop_id', self::DTYPE_INTEGER, null);
		$this->initVar('confop_name', self::DTYPE_STRING, null, true, 255);
		$this->initVar('confop_value', self::DTYPE_STRING, null, true, 255);
		$this->initVar('conf_id', self::DTYPE_INTEGER, 0);
                
                parent::__construct($handler, $data);
	}
}

