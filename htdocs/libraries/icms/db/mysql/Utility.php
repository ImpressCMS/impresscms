<?php
/**
 *
 *
 * @copyright	The ImpressCMS Project - http://www.impresscms.org/
 * @license		GNU General Public License (GPL) - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since
 * @category	ICMS
 * @package		Database
 * @subpackage	MySQL
 * @version
 */

/**
 *
 * Redefinida por Claudia A. V. Callegari
 * Participação de Rodrigo Lima
 *
 * Redefined by Claudia A. V. Callegari
 * Participation Rodrigo Lima
 *
 * ImpressCMS.org
 *
 * @copyright	The ImpressCMS Project - http://www.impresscms.org/
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	MySQL
 */
abstract class icms_db_mysql_Utility implements icms_db_IUtility {

	/**
	 * add a prefix.'_' to all tablenames in a query
	 *
	 * @param   string  $query  valid SQL query string
	 * @param   string  $prefix prefix to add to all table names
	 * @return  mixed   FALSE on failure
	 */
	static public function prefixQuery($query, $prefix) {
		$pattern = "/^(INSERT INTO|CREATE TABLE|ALTER TABLE|UPDATE)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
		$pattern2 = "/^(DROP TABLE)(\s)+([`]?)([^`\s]+)\\3(\s)?$/siU";

		if (preg_match($pattern, $query, $matches)) {
			$replace = "\\1 " . $prefix . "_\\4\\5";
			$matches [0] = preg_replace($pattern, $replace, $query);
			$query = $matches [0];
			if (preg_match('/REFERENCES/', $query) or preg_match('/DROP FOREIGN KEY/', $query)) {

				$matches_1 = $matches;  // claudia
				//$pattern = "/(REFERENCES)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
				// alterado abaixo 03/10/2011, sendo que funcionou para ADD CONSTRAINT e não para DROP FOREIGN KEY
				$pattern = "/(REFERENCES|DROP FOREIGN KEY|ADD CONSTRAINT)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
				if (preg_match($pattern, $query, $matches)) {
					$matches [0] = preg_replace($pattern, $replace, $query);
					$matches_1[0] = $matches[0]; // claudia
					$matches = $matches_1;  // claudia
				}
			}
			return $matches;
		} elseif (preg_match($pattern2, $query, $matches)) {
			$replace = "\\1 " . $prefix . "_\\4\\5";
			$matches [0] = preg_replace($pattern2, $replace, $query);

			return $matches;
		} else {

			return FALSE;
		}
	}

	/**
	 * Removes comment and splits large sql files into individual queries
	 * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
	 *
	 * Last revision: September 23, 2001 - gandon
	 *
	 * @param   array    the split sql commands
	 * @param   string   the sql commands
	 * @return  boolean  always true
	 */
	static public function splitSqlFile(&$ret, $sql) {}


	/**
	 * Determine if the SQL string is safe
	 *
	 * @param string $sql
	 * @return bool	TRUE if the string is safe
	*/
	static public function checkSQL($sql) {}

}
