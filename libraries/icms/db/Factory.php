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
 * Establishes database class and connection
 *
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Database
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 *
 * @abstract
 */
abstract class icms_db_Factory {

	/**
	 * PDO database adapter. It represents a PDO connection only
	 * Access this as icms::$db
	 *
	 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
	 * @var 		icms_db_IConnection
	 */
	static protected $pdoInstance = false;

	/**
	 * Legacy database adapter - it can represent a legacy database connection or a PDO connection.
	 * Access this as icms::$xoopsDB
	 *
	 * @var icms_db_legacy_Database
	 */
	static protected $xoopsInstance = false;

	/**
	 * Get a reference to the only instance of database class and connects to DB
	 *
	 * if the class has not been instantiated yet, this will also take
	 * care of that
	 *
	 * @copyright    copyright (c) 2000-2007 XOOPS.org
	 * @author        modified by arcandier, The ImpressCMS Project
	 *
	 * @static
	 * @return      object  Reference to the only instance of database class
	 */
	static public function instance()
	{
		if (self::$xoopsInstance !== false) {
			return self::$xoopsInstance;
		}
		$type = env('DB_TYPE');
		$allowWebChanges = defined('XOOPS_DB_PROXY') ? false : true;
		if (substr($type, 0, 4) == 'pdo.') {
			if (false === self::$pdoInstance) {
				self::pdoInstance();
			}
			self::$xoopsInstance = new icms_db_legacy_PdoDatabase(self::$pdoInstance, $allowWebChanges);
		} else {
			$class = env('DB_ALTERNATIVE');
			if (!($class && class_exists($class))) {
				$class = 'icms_db_legacy_' . $type;
				$class .= $allowWebChanges ? '_Safe' : '_Proxy';
			}
			self::$xoopsInstance = new $class();
			/* during a new installation, the icms object does not exist */
			//self::$xoopsInstance->setLogger(icms::$logger);
			/* @todo remove the dependency on the logger class */
			self::$xoopsInstance->setLogger(icms_core_Logger::instance());
			if (!self::$xoopsInstance->connect()) {
				/* this requires that include/functions.php has been loaded */
				icms_loadLanguageFile('core', 'core');
				trigger_error(_CORE_DB_NOTRACEDB, E_USER_ERROR);
			}
		}
		self::$xoopsInstance->setPrefix(env('DB_PREFIX'));
		return self::$xoopsInstance;
	}

	/**
	 * Instanciate the PDO compatible DB adapter (if appropriate).
	 *
	 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
	 *
	 * @throws RuntimeException
	 */
	static public function pdoInstance() {
		if (self::$pdoInstance !== false) {
			return self::$pdoInstance;
		}

		$type = env('DB_TYPE');

		if (substr($type, 0, 4) != 'pdo.') {
			return self::$pdoInstance = null;
		}
		if (!class_exists('PDO', false)) {
			throw new RuntimeException("PDO extension not available.");
		}

		// --> added by Claudia, ImpressCMS.org
		$string_conn = "host=" . env('DB_HOST', 'localhost') . ";dbname=" . env('DB_NAME', 'impresscms');
		$string_conn .= ';port=' . env('DB_PORT', 3306);
		$string_conn .= ';charset=' . env('DB_CHARSET');
		define('ICMS_DB_DSN', $string_conn);
		// <--

		/* this is an array of attributes to pass to the connection before it is established */
		$options = array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT, // default is ERRMODE_SILENT (returns error code, only)
		);

		/* Note: from PHP PDO connections documentation - http://www.php.net/manual/en/pdo.connections.php
		 *
		* If you're using the PDO ODBC driver and your ODBC libraries support ODBC
		* Connection Pooling (unixODBC and Windows are two that do; there may be more),
		* then it's recommended that you don't use persistent PDO connections,
		* and instead leave the connection caching to the ODBC Connection Pooling layer.
		* The ODBC Connection Pool is shared with other modules in the process;
		* if PDO is told to cache the connection, then that connection would never be returned
		* to the ODBC connection pool, resulting in additional connections being created
		* to service those other modules.
		*
		* If you are certain releases of PHP 5.4 you cannot use persistent connections
		* when you have your own database class that derives from the native PDO object.
		* If you do, you will get segmentation faults during the PHP process shutdown.
		* Please see this bug report for more information: https://bugs.php.net/bug.php?id=63176
		*/
		$options[PDO::ATTR_PERSISTENT] = (env('DB_PCONNECT', 0) == 1);

		$driver = substr($type, 4);
		$dsn = $driver . ':' . ICMS_DB_DSN;
		$class = "icms_db_{$driver}_Connection";
		if (!class_exists($class)) {
			$class = "icms_db_Connection";
		}
		return self::$pdoInstance = new $class($dsn, env('DB_USER'), env('DB_PASS'), $options);
	}
}
