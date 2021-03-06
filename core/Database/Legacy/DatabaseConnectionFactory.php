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
 * ImpressCMS Database Factory Class
 *
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

namespace ImpressCMS\Core\Database\Legacy;

use icms;
use ImpressCMS\Core\Database\DatabaseConnectionFactory as CurrentDatabaseConnectionFactory;
use ImpressCMS\Core\Database\Legacy\Updater\DatabaseUpdater;

/**
 * Creates a database connection
 *
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @package	ICMS\Database\Legacy
 */
class DatabaseConnectionFactory extends CurrentDatabaseConnectionFactory {
	/**
	 * Constructor
	 *
	 * Makes nothing.
	 */
	protected function __construct() {
/* Empty! */ }

	/**
	 * Get a reference to the only instance of database class and connects to DB
	 *
	 * if the class has not been instantiated yet, this will also take
	 * care of that
	 *
	 * @static
	 * @staticvar   object  The only instance of database class
	 * @return      object  Reference to the only instance of database class
	 */
	public static function &instance() {
		return icms::getInstance()->get('db');
	}

	/**
	 * Gets a reference to the only instance of database class. Currently
	 * only being used within the installer.
	 *
	 * @static
	 * @staticvar   object  The only instance of database class
	 * @return      object  Reference to the only instance of database class
	 */
	public static function &getDatabase() {
		return icms::getInstance()->get('db');
	}

	/**
	 * Gets the databaseupdater object .
	 *
	 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
	 *
	 * @return	DatabaseUpdater  Updater handler
	 * @static
	 */
	public static function getDatabaseUpdater() {
		return new DatabaseUpdater();
	}
}