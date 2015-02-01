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
 * Legacy MySQL utilities
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Database
 * @subpackage  Legacy
 *
 * @version		SVN: $Id: Utility.php 12403 2014-01-26 21:35:08Z skenow $
 */

/**
 * Provide some utility methods for databases
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	Legacy
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
class icms_db_legacy_mysql_Utility implements icms_db_IUtility {

	/**
	 * Creates a new utility object
	 */
	public function __construct(){
	}

	/**
	 * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
	 *
	 * Removes comment and splits large sql files into individual queries
	 *
	 * Last revision: September 23, 2001 - gandon
	 *
	 * @param   array    the split sql commands
	 * @param   string   the sql commands
	 * @return  boolean  always true
	 */
	static public function splitMySqlFile(&$ret, $sql) {
		$sql               = trim($sql);
		$sql_len           = strlen($sql);
		$char              = '';
		$string_start      = '';
		$in_string         = FALSE;

		for ($i = 0; $i < $sql_len; ++$i) {
			$char = $sql[$i];

			// We are in a string, check for not escaped end of
			// strings except for backquotes that can't be escaped
			if ($in_string) {
				for (;;) {
					$i = strpos($sql, $string_start, $i);
					// No end of string found -> add the current
					// substring to the returned array
					if (!$i) {
						$ret[] = $sql;
						return TRUE;
					} elseif ($string_start == '`' || $sql[$i-1] != '\\') {
						// Backquotes or no backslashes before
						// quotes: it's indeed the end of the
						// string -> exit the loop
						$string_start      = '';
						$in_string         = FALSE;
						break;
					} else {
						// one or more Backslashes before the presumed
						// end of string...
						// first checks for escaped backslashes
						$j                     = 2;
						$escaped_backslash     = FALSE;
						while ($i-$j > 0 && $sql[$i-$j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						// ... if escaped backslashes: it's really the
						// end of the string -> exit the loop
						if ($escaped_backslash) {
							$string_start  = '';
							$in_string     = FALSE;
							break;
						} else {
							$i++;
						}
					}
				}
			} elseif ($char == ';') {
				// We are not in a string, first check for delimiter...
				// if delimiter found, add the parsed part to the returned array
				$ret[]    = substr($sql, 0, $i);
				$sql      = ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len  = strlen($sql);
				if ($sql_len) {
					$i      = -1;
				} else {
					// The submited statement(s) end(s) here
					return TRUE;
				}
			} elseif (($char == '"') || ($char == '\'') || ($char == '`')) {
				// ... then check for start of a string,...
				$in_string    = TRUE;
				$string_start = $char;
			} elseif ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
				// for start of a comment (and remove this comment if found)...
				// starting position of the comment depends on the comment type
				$start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
				// if no "\n" exits in the remaining string, checks for "\r"
				// (Mac eol style)
				$end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
					? strpos(' ' . $sql, "\012", $i+2)
					: strpos(' ' . $sql, "\015", $i+2);
				if (!$end_of_comment) {
					// no eol found after '#', add the parsed part to the returned
					// array and exit
					// RMV fix for comments at end of file
					$last = trim(substr($sql, 0, $i-1));
					if (!empty($last)) {
						$ret[] = $last;
					}
					return TRUE;
				} else {
					$sql     = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
					$sql_len = strlen($sql);
					$i--;
				}
			}
		}

		// add any rest to the returned array
		if (!empty($sql) && trim($sql) != '') {
			$ret[] = $sql;
		}
		return TRUE;
	}

	/**
	 * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
	 *
	 * Removes comment and splits large sql files into individual queries
	 *
	 * Last revision: September 23, 2001 - gandon
	 *
	 * @param   array    the split sql commands
	 * @param   string   the sql commands
	 * @return  boolean  always true
	 */
	static public function splitSqlFile(&$ret, $sql) {
		$sql               = trim($sql);
		$sql_len           = strlen($sql);
		$char              = '';
		$string_start      = '';
		$in_string         = FALSE;

		for ($i = 0; $i < $sql_len; ++$i) {
			$char = $sql[$i];

			// We are in a string, check for not escaped end of
			// strings except for backquotes that can't be escaped
			if ($in_string) {
				for (;;) {
					$i         = strpos($sql, $string_start, $i);
					// No end of string found -> add the current
					// substring to the returned array
					if (!$i) {
						$ret[] = $sql;
						return TRUE;
					} elseif ($string_start == '`' || $sql[$i-1] != '\\') {
						// Backquotes or no backslashes before
						// quotes: it's indeed the end of the
						// string -> exit the loop
						$string_start      = '';
						$in_string         = FALSE;
						break;
					} else {
						// one or more Backslashes before the presumed
						// end of string...
						// first checks for escaped backslashes
						$j                     = 2;
						$escaped_backslash     = FALSE;
						while ($i-$j > 0 && $sql[$i-$j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						// ... if escaped backslashes: it's really the
						// end of the string -> exit the loop
						if ($escaped_backslash) {
							$string_start  = '';
							$in_string     = FALSE;
							break;
						} else {
							// ... else loop
							$i++;
						}
					}
				}
			} elseif ($char == ';') {
				// We are not in a string, first check for delimiter...
				// if delimiter found, add the parsed part to the returned array
				$ret[]    = substr($sql, 0, $i);
				$sql      = ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len  = strlen($sql);
				if ($sql_len) {
					$i      = -1;
				} else {
					// The submited statement(s) end(s) here
					return TRUE;
				}
			} elseif (($char == '"') || ($char == '\'') || ($char == '`')) {
				// ... then check for start of a string,...
				$in_string    = TRUE;
				$string_start = $char;
			} elseif ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
				// for start of a comment (and remove this comment if found)...
				// starting position of the comment depends on the comment type
				$start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
				// if no "\n" exits in the remaining string, checks for "\r"
				// (Mac eol style)
				$end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
					? strpos(' ' . $sql, "\012", $i+2)
					: strpos(' ' . $sql, "\015", $i+2);
				if (!$end_of_comment) {
					// no eol found after '#', add the parsed part to the returned
					// array and exit
					// RMV fix for comments at end of file
					$last = trim(substr($sql, 0, $i-1));
					if (!empty($last)) {
						$ret[] = $last;
					}
					return TRUE;
				} else {
					$sql     = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
					$sql_len = strlen($sql);
					$i--;
				}
			}
		}

		// add any rest to the returned array
		if (!empty($sql) && trim($sql) != '') {
			$ret[] = $sql;
		}
		return TRUE;
	}

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
		if (preg_match($pattern, $query, $matches) || preg_match($pattern2, $query, $matches)) {
			$replace = "\\1 " . $prefix . "_\\4\\5";
			$matches[0] = preg_replace($pattern, $replace, $query);
			return $matches;
		}
		return FALSE;
	}

	/**
	 * Determine if the SQL string is safe
	 *
	 * @see	ProtectorMySQLDatabase::checkSql()
	 *
	 * @param string $sql
	 * @return bool
	 */
	static public function checkSQL($sql) {
		/* use Protector's db layer to prevent SQLi */
		if (defined('XOOPS_DB_ALTERNATIVE') && class_exists(XOOPS_DB_ALTERNATIVE)) {
			$class = XOOPS_DB_ALTERNATIVE;
			$protectorDB = new $class();

			$sql4check = substr($sql , 7);
			foreach ($protectorDB->doubtful_needles as $needle) {
				if(stristr($sql4check , $needle)) {
					$protectorDB->checkSql($sql) ;
					return FALSE;
				}
			}
		}

		return TRUE;
	}
}
