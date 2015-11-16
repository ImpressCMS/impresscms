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
 * Manage template sets
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Base class for all template sets
 *
 * @author	Kazumi Ono (AKA onokazu)
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\View\Template\Set
 * 
 * @property int    $tplset_id      Template set ID
 * @property string $tplset_name    Name
 * @property string $tplset_desc    Description
 * @property string $tplset_credits Credits
 * @property int    $tplset_created When it was created?
 * */
class icms_view_template_set_Object extends icms_ipf_Object {

	/**
	 * constructor
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('tplset_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('tplset_name', self::DTYPE_STRING, null, false, 50);
		$this->initVar('tplset_desc', self::DTYPE_STRING, null, false, 255);
		$this->initVar('tplset_credits', self::DTYPE_STRING, null, false);
		$this->initVar('tplset_created', self::DTYPE_INTEGER, 0, false);
                
                parent::__construct($handler, $data);
	}
}

