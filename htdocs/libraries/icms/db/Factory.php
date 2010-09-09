<?php
/**
 * Creates a database object and connection
 *
 * @category	ICMS
 * @package		Database
 *
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id: Factory.php 20013 2010-08-25 01:26:18Z skenow $
 */

class icms_db_Factory {
	/**
	 * Lowest level of DB abstraction
	 *
	 * @todo this needs to be improved when we add another DB engine, other then MySQL
	 * @static
	 * @return icms_db_legacy_Database
	 */
	static public function &instance() {
		return icms_db_legacy_Factory::instance();
	}

	/**
	 *
	 * @deprecated Use icms_db_Factory::instance instead
	 * @todo Remove in version 1.4
	 * @return icms_db_legacy_Database
	 */
	static public function &getInstance() {
		icms_core_Debug::setDeprecated('icms_db_Factory::instance', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		return self::instance();
	}
}