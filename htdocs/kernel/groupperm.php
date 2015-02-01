<?php
// $Id: groupperm.php 506 2006-05-26 23:10:37Z skalpa $
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
 * @copyright	The XOOPS Project <http://www.xoops.org/>
 * @copyright	XOOPS_copyrights.txt
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @since		XOOPS
 *
 * @author		Kazumi Ono (aka onokazo)
 * @author	The XOOPS Project Community <http://www.xoops.org>
 * @author	Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nube.com.ar>
 *
 * @package	core
 * @subpackage	groupperm
 * @version		$Id: groupperm.php 19431 2010-06-16 20:46:34Z david-sf $
 */



if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A group permission
 *
 * These permissions are managed through a {@link XoopsGroupPermHandler} object
 *
 * @package     kernel
 * @subpackage	member
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @deprecated	Use icms_member_groupperm_Object
 * @todo		Remove in version 1.4
 */
class XoopsGroupPerm extends icms_member_groupperm_Object
{
	private $_deprecated;

	/**
	 * Constructor
	 *
	 */
	function XoopsGroupPerm()
	{
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_member_groupperm_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * XOOPS group permission handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS group permission class objects.
 * This class is an abstract class to be implemented by child group permission classes.
 *
 * @see          XoopsGroupPerm
 * @author       Kazumi Ono  <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @deprecated	Use icms_member_groupperm_Handler, instead
 * @todo		Remove in version 1.4
 */
class XoopsGroupPermHandler extends icms_member_groupperm_Handler
{
	private $_deprecated;
	public function __construct(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_member_groupperm_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}


}
