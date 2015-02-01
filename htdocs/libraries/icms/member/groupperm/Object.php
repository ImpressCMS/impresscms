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
 * @license		LICENSE.txt
 *
 * @author		Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nube.com.ar>
 * @category	ICMS
 * @package		Member
 * @subpackage	Groupperm
 * @version		SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A group permission
 *
 * These permissions are managed through a {@link icms_member_groupperm_Handler} object
 * @category	ICMS
 * @package     Member
 * @subpackage	GroupPermission
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_member_groupperm_Object extends icms_core_Object {
	/**
	 * Constructor
	 *
	 */
	function __construct() {
		parent::__construct();
		$this->initVar('gperm_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_groupid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_itemid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('gperm_modid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('gperm_name', XOBJ_DTYPE_OTHER, null, false);
	}
}

