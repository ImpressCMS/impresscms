<?php
// $Id: user.php 506 2006-05-26 23:10:37Z skalpa $
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
 * Manage of private messages
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: privmessage.php 19431 2010-06-16 20:46:34Z david-sf $
 */



if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * A handler for Private Messages
 *
 * @package		kernel
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 The XOOPS Project (http://www.xoops.org)
 *
 * @version		$Revision: 1102 $ - $Date: 2007-10-18 22:55:52 -0400 (jeu., 18 oct. 2007) $
 * @deprecated	Use icms_data_privmessage_Object, instead
 * @todo		Remove in version 1.4
 */
class XoopsPrivmessage extends icms_data_privmessage_Object
{

	private $_deprecated;
	/**
	 * constructor
	 **/
	function XoopsPrivmessage()
	{
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_data_privmessage_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * XOOPS private message handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS private message class objects.
 *
 * @package		kernel
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 The XOOPS Project (http://www.xoops.org)
 *
 * @version		$Revision: 1102 $ - $Date: 2007-10-18 22:55:52 -0400 (jeu., 18 oct. 2007) $
 * @deprecated	Use icms_data_privmessage_Handler, instead
 * @todo		Remove in version 1.4
 *
 */
class XoopsPrivmessageHandler extends icms_data_privmessage_Handler
{
	private $_deprecated;
	public function __construct(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_data_privmessage_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
