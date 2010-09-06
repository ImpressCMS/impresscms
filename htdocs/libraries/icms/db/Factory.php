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

class icms_db_Factory{

	/**
	 * Lowest level of DB abstraction
	 *
	 * @todo this needs to be improved when we add another DB engine, other then MySQL
	 */
	static public function getInstance() {
		return icms_db_icms_Factory::instance();
	}
}