<?php
/**
 *
 * ImpressCMS Autoloader
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		1.3
 * @author		Marc-André Lanciault (aka marcan) <mal@inboxintl.com>
 * @version		$Id$
 */

class icms_core_Autoloader {

	/**
	 * Debug feature of autoloader
	 * @var bool debug enabling debug
	 */
	static protected $debug=false;

	/**
	 * Locates and loads the appropriate file, based on a class name
	 * @param $class
	 */
	static public function autoload( $class) {
		if (self::$debug) echo '== class: ' . $class . '<br />';

		if (substr($class, 0, 4) == 'mod_') {
			$module = substr($class, 4, strpos($class, '_', 4) - 4);
			$alteredClass = str_replace('mod_' . $module . '_', '', $class);
			$path = ICMS_ROOT_PATH . '/modules/' . $module . '/class/' . $alteredClass . '.php';
			if (file_exists($path))
				include_once $path;
			else {
				if (self::$debug) echo '[error] path not found ' . $path . '<br />';
			}
		} else {
			$file = str_replace( '_', DIRECTORY_SEPARATOR, $class );
			$path = ICMS_ROOT_PATH . '/libraries/' . $file . '.php';
			if (file_exists($path))
				include_once $path;
			else {
				if (self::$debug) echo '[error] path not found ' . $path . '<br />';
			}
		}
		if (!class_exists($class)) {
			if (self::$debug) echo '[error] class not found ' . $class . '<br />';
		}
	}

	/**
	 * Adds the icms_core_Autoloader to the spl autoload stack
	 */
	static public function register() {
		static $reg = false;
		if (!$reg) {
			spl_autoload_register( array( 'icms_core_Autoloader', 'autoload' ) );
			$reg = true;
		}
	}
}