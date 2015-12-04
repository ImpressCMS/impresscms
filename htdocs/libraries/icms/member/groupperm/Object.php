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
 * Manage groups and memberships
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license	LICENSE.txt
 * @author	Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nube.com.ar>
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A group permission
 *
 * These permissions are managed through a {@link icms_member_groupperm_Handler} object
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\Member\GroupPermission
 * 
 * @property int    $gperm_id      Group permission ID
 * @property int    $gperm_groupid Linked group ID
 * @property int    $gperm_itemid  Linked item ID
 * @property int    $gperm_modid   Linked module ID
 * @property string $gperm_name    Name
 */
class icms_member_groupperm_Object extends icms_ipf_Object {
	/**
	 * Constructor
	 *
	 */
	function __construct(&$handler, $data = array()) {
		
		$this->initVar('gperm_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('gperm_groupid', self::DTYPE_INTEGER, null, false);
		$this->initVar('gperm_itemid', self::DTYPE_INTEGER, null, false);
		$this->initVar('gperm_modid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('gperm_name', self::DTYPE_STRING, null, false, 50);
                
                parent::__construct($handler, $data);
	}
}

