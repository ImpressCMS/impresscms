<?php
namespace Icms\Db\Mysql;

/**
 *
 * @copyright The ImpressCMS Project - http://www.impresscms.org/
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since 2.0
 * @category ICMS
 * @package Database
 * @subpackage MySQL
 */

declare(strict_types=1);

/**
 *
 * @copyright The ImpressCMS Project - http://www.impresscms.org/
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	MySQL
 */
class Connection extends \PDO implements \IConnection
{

	/**
	 * Safely escape the string, but strips the outer quotes
	 *
	 * @see \IConnection::escape()
	 * @param  string $string
	 * @return string
	 */
	public function escape(string $string): string
	{
		return substr($this->quote($string), 1, -1);
	}

	/**
	 *
	 * @see \PDO::query()
	 * @param string $query
	 * @param mixed|null $mode
	 * @param mixed $arg3
	 * @return \PDOStatement|false
	 */
	public function query(string $query, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, ...$fetch_mode_args)
	{
		$mode = $mode;
		$args = func_get_args();
		$sql = $args[0];
		// the use of \IConnection is correct - without it, the query count in debug is not correct
		$result = call_user_func_array(array(\self::class, 'query'), $args);

		// trigger events for the debug console - see plugins/preloads/debug_mode.php
		if ($result) {
			\icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => null, 'error' => null));
		} else {
			$errorinfo = $this->errorInfo();
			\icms_Event::trigger('icms_db_IConnection', 'execute', $this, array('sql' => $args[0], 'errorno' => $errorinfo[1], 'error' => $errorinfo[2]));
		}

		return $result;
	}
}

\class_alias(Connection::class, 'icms_db_mysql_Connection');
