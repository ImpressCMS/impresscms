<?php
/**
 * MySQLi Database Driver file.
 *
 * @package database
 * @subpackage mysqli
 * @version $Id$
 * @since ImpressCMS 1.0
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Database Object for a MySQL database.
 *
 * @abstract
 *
 * @package     database
 * @subpackage  mysqli
 * @since ImpressCMS 1.0
 *
 * @author      Gustavo Pilla  <nekro@impresscms.org>
 * @copyright   copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabase extends icms_db_icms_mysqli_Database {
	private $_errors;
	public function __construct() {
		parent::__construct();
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_icms_mysqli_Database', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 *	Safe Connection to a MySQL database.
 *
 * @package database
 * @subpackage mysqli
 * @since ImpressCMS 1.0
 *
 * @author Gustavo Alejandro Pilla <nekro@impresscms.org>
 * @copyright copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabaseSafe extends icms_db_icms_mysqli_Safe {
	private $_errors;
	public function __construct() {
		parent::__construct();
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_icms_mysqli_Database', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * {@link query()} method for security reasons.
 *
 * @package database
 * @subpackage mysqli
 * @since ImpressCMS 1.0
 *
 * @author Gustavo Alejandro Pilla <nekro@impresscms.org>
 * @copyright copyright (c) 2008 ImpressCMS
 */
class XoopsMySQLiDatabaseProxy extends icms_db_icms_mysqli_Proxy {
	private $_errors;
	public function __construct() {
		parent::__construct();
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_icms_mysqli_Proxy', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>