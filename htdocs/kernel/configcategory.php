<?php
// $Id: configcategory.php 506 2006-05-26 23:10:37Z skalpa $
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
 * Manage configuration categories
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		core
 * @subpackage	config
 * @since		XOOPS
 * @author		Kazumi Ono (aka onokazo)
 * @author		http://www.xoops.org The XOOPS Project
 * @version		$Id: configcategory.php 19431 2010-06-16 20:46:34Z david-sf $
 */




if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * A category of configs
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage	config
 * @deprecated	Use icms_config_category_Object, instead
 * @todo		Remove in version 1.4
 */
class XoopsConfigCategory extends icms_config_category_Object
{
	private $_deprecated;
	/**
	 * Constructor
	 *
	 */
	function XoopsConfigCategory()
	{
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_config_category_Object', sprintf(_CORE_REMOVE_IN_VERISON, '1.4'));

	}
}

/**
 * XOOPS configuration category handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS configuration category class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage  config
 * @deprecated	Use icms_config_category_Handler, instead
 * @todo		Remove in version 1.4
 */
class XoopsConfigCategoryHandler extends icms_config_category_Handler
{

	private $_deprecated;
	public function __construct(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_config_category_Handler', sprintf(_CORE_REMOVE_IN_VERISON, '1.4'));
	}

}

