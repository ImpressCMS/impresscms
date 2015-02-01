<?php
/**
 * Database connection
 *
 * @category	ICMS
 * @package		Database
 *
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id$
 */

/**
 * Database connection
 *
 * @category	ICMS
 * @package		Database
 */
class icms_db_Connection extends PDO implements icms_db_IConnection {

	/**
	 *  Safely escape the string, but strips the outer quotes
	 *
	 * This is a legacy method and not part of PDO and must be declared in any class that implements this interface
	 *
	 * @see icms_db_IConnection::escape()
	 * @param	string	$string
	 * @return	string
	 */
	public function escape($string) {
		return substr($this->quote($string), 1, -1);
	}

	/**
	 * Executes an SQL statement and returns a result set as an SQL statement object
	 * @see PDO::query()
	 * @return
	 */
	public function query() {
		$args = func_get_args();
		$sql = $args[0];
		// the use of icms_db_IConnection is correct - without it, the query count in debug is not correct
		$result = call_user_func_array(array('parent', 'query'), $args);

		// trigger events for the debug console - see plugins/preloads/debug_mode.php
		if ($result) {
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => NULL, 'error' => NULL));
		} else {
			$errorinfo = $this->errorInfo();
			icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
		}

		return $result;
	}
}
