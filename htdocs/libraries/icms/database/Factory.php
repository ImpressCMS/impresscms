<?php
/**
 * Creates a database object and connection
 *
 * @category	ICMS
 * @package		Database
 *
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id$
 */

/**
 * ImpressCMS Database Factory Class
 *
 * @category	ICMS
 * @package		Database
 *
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
class icms_database_Factory{

	/**
	 * Constructor
	 *
	 * Makes nothing.
	 */
	protected function __construct() { /* Empty! */ }

	/**
	 * Get a reference to the only instance of database class and connects to DB
	 *
	 * if the class has not been instantiated yet, this will also take care of that
	 *
	 * @static
	 * @staticvar   object  The only instance of database class
	 * @return      object  Reference to the only instance of database class
	 */
	static public function &instance() {
		static $instance;
		if (!isset($instance)) {
			$file = ICMS_ROOT_PATH . '/class/database/drivers/' . XOOPS_DB_TYPE . '/database.php';
			require_once $file;
			if (defined('XOOPS_DB_ALTERNATIVE') && class_exists(XOOPS_DB_ALTERNATIVE)) {
				$class = XOOPS_DB_ALTERNATIVE ;
			} elseif (!defined('XOOPS_DB_PROXY')) {
				$class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseSafe';
			} else {
				$class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseProxy';
			}
			$instance = new $class();
			$instance->setLogger(icms_core_Logger::instance());
			$instance->setPrefix(XOOPS_DB_PREFIX);
			if (!$instance->connect()) {
				icms_loadLanguageFile('core', 'core');
				trigger_error(_CORE_DB_NOTRACEDB, E_USER_ERROR);
			}
		}
		return $instance;
	}

	/**
	 * For backward compatibility
	 *
	 * @todo	remove in 1.4
	 * @return	object	Reference to the only instance of database class
	 */
	static public function &getDatabaseConnection() {
		icms_core_Debug::setDeprecated('icms_database_Factory::instance', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		return self::instance();
	}

	/**
	 * Gets a reference to the only instance of database class. Currently
	 * only being used within the installer.
	 *
	 * @static
	 * @staticvar   object  The only instance of database class
	 * @return      object  Reference to the only instance of database class
	 */
	static public function &getDatabase() {
		static $database;
		if (!isset($database)) {
			$file = ICMS_ROOT_PATH . '/class/database/drivers/' . XOOPS_DB_TYPE . '/database.php';
			require_once $file;
			if (!defined('XOOPS_DB_PROXY')) {
				$class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseSafe';
			} else {
				$class = 'Xoops' . ucfirst(XOOPS_DB_TYPE) . 'DatabaseProxy';
			}
			$database = new $class();
		}
		return $database;
	}

	/**
	 * Gets the databaseupdater object .
	 *
	 * @return	object  @link IcmsDatabaseUpdater
	 * @static
	 */
	static public function getDatabaseUpdater() {
		$file = ICMS_ROOT_PATH . '/class/database/drivers/' . XOOPS_DB_TYPE . '/databaseupdater.php';
		require_once $file;
		$class = 'Icms' . ucfirst(XOOPS_DB_TYPE) . 'Databaseupdater';
		$databaseUpdater = new $class();
		return $databaseUpdater;
	}
}