<?php
// $Id: database.php 12403 2014-01-26 21:35:08Z skenow $
// database.php - defines abstract database wrapper class
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
 * Database Base Class
 *
 * Defines abstract database wrapper class
 *
 * @deprecated	1.3	This file will not exist after version 1.3.x
 *
 * @copyright	The XOOPS Project <http://www.xoops.org/>
 * @copyright	XOOPS_copyrights.txt
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	database
 * @since	XOOPS
 * @version	$Id: database.php 12403 2014-01-26 21:35:08Z skenow $
 * @author	The XOOPS Project Community <http://www.xoops.org>
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @author	Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 */

/**
 * Abstract base class for Database access classes
 *
 * @deprecated	1.3	This class will be removed after version 1.3.x
 *
 * @abstract
 *
 * @package database
 * @subpackage  main
 *
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
abstract class IcmsDatabase extends icms_db_legacy_Database {
	private $_errors;
	public function __construct() {
		parent::__construct();
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_legacy_database', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * Abstract base class for Database access classes
 *
 * @deprecated	1.3	This class will be removed after 1.3.x
 *
 * @abstract
 *
 * @package database
 * @subpackage  main
 * @since XOOPS
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
abstract class XoopsDatabase extends IcmsDatabase { /* For Backwards compatibility */ }

/**
 * Only for backward compatibility
 *
 * @deprecated 1.3	Use icms_db_Factory. This class will be removed after 1.3.x
 *
 * @package database
 * @subpackage  main
 * @since XOOPS
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
class Database {
	static public function &getInstance() {
		icms_core_Debug::setDeprecated('icms_db_Factory::instance', sprintf(_CORE_REMOVE_IN_VERSION, '2.0'));
		$db = icms_db_Factory::instance();
		return $db;
	}
}
