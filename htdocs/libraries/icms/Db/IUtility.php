<?php
/**
 * Utility interface methods for database functions
 *
 * @copyright	The ImpressCMS Project - http://www.impresscms.org/
 * @license		GNU General Public License (GPL) - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since
 * @category	ICMS
 * @package		Database
 */

declare(strict_types=1);

namespace Icms\Db;

/**
 * Utility interface for all DB drivers
 *
 * @copyright	The ImpressCMS Project - http://www.impresscms.org/
 *
 * @category ICMS
 * @package		Database
 * @since		2.0
 */
interface IUtility
{
	/**
	 * Creates a new utility object
	 */
	public function __construct();

	/**
	 * Removes comment and splits large sql files into individual queries
	 *
	 *
	 * @param  array   the split sql commands
	 * @param  string  the sql commands
	 * @return boolean always true
	 */
	static public function splitSqlFile(&$ret, string $sql): bool;

	/**
	 * add a prefix.'_' to all tablenames in a query
	 *
	 * @param  string  $query   valid SQL query string
	 * @param  string  $prefix  prefix to add to all table names
	 * @return false|string     FALSE on failure
	 */
	static public function prefixQuery(string $query, string $prefix);

	/**
	 * Determine if the SQL string is safe
	 *
	 * @param  string $sql
	 * @return bool   TRUE if the string is safe
	 */
	static public function checkSQL(string $sql): bool;

}

\class_alias(IUtility::class, 'icms_db_IUtility');
