<?php
// $Id: online.php 694 2006-09-04 11:33:22Z skalpa $
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
 * Manage of original Xoops Objects
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: object.php 19419 2010-06-13 22:52:12Z skenow $
 * @deprecated	Moving to new architecture
 * @todo		Remove in version 1.4
 */



/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */

/**
 * Base class for all objects in the Xoops kernel (and beyond)
 *
 * @author Kazumi Ono (AKA onokazu)
 * @copyright copyright &copy; 2000 XOOPS.org
 * @package kernel
 * @deprecated	Use icms_core_Object, instead
 * @todo		Remove in version 1.4
 **/
class XoopsObject extends icms_core_Object
{
	private $_deprecated;
	public function XoopsObject() {
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}

/**
 * XOOPS object handler class.
 * This class is an abstract class of handler classes that are responsible for providing
 * data access mechanisms to the data source of its corresponsing data objects
 * @package kernel
 * @abstract
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright &copy; 2000 The XOOPS Project
 * @deprecated	Use icms_core_ObjectHandler, instead
 * @todo		Remove in version 1.4
 */
abstract class XoopsObjectHandler extends icms_core_ObjectHandler
{
	private $_deprecated;
	public function XoopsObjectHandler(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_ObjectHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}

