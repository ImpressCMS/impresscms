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
/**
 * DB Manager Class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	installer
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: dbmanager.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 * database manager for XOOPS installer
 *
 * @author Haruki Setoyama  <haruki@planewave.org>
 * @version $Id: dbmanager.php 12329 2013-09-19 13:53:36Z skenow $
 * @access public
 **/
class db_manager {

	public $s_tables = array();
	public $f_tables = array();
	public $db;

	public function __construct() {
		$this->db = icms_db_legacy_Factory::getDatabase();
		$this->db->setPrefix(XOOPS_DB_PREFIX);
		$this->db->setLogger(icms_core_Logger::instance());
	}

	public function isConnectable() {
		return $this->db->connect(false) != false;
	}

	public function queryFromFile($sql_file_path) {
		if (!is_file($sql_file_path)) {
			return false;
		}
		$sql_query = file_get_contents($sql_file_path);
		if ($sql_query === false) {
			return false;
		}
		$sql_query = trim($sql_query);
		$pieces = array();
		icms_db_legacy_mysql_Utility::splitSqlFile($pieces, $sql_query);
		$this->db->connect();
		foreach ($pieces as $piece) {
			$piece = trim($piece);
			// [0] contains the prefixed query
			// [1] contains the type of the query
			// [4] contains unprefixed table name
			$prefixed_query = icms_db_legacy_mysql_Utility::prefixQuery($piece, $this->db->prefix());
			if ($prefixed_query == false) {
				continue;
			}
			$table = $this->db->prefix($prefixed_query[4]);
			switch ($prefixed_query[1]) {
				case 'CREATE TABLE':
					$this->recordResult('create', $table, $this->db->query($prefixed_query[0]) != false);
					break;
				case 'INSERT INTO':
					$success = $this->db->query($prefixed_query[0]) != false;
					$this->recordResult('insert', $table, $success, $success ? $this->db->getAffectedRows() : 1);
					break;
				case 'ALTER TABLE':
					$this->recordResult('alter', $table, $this->db->query($prefixed_query[0]) != false);
					break;
				case 'DROP TABLE':
					$this->recordResult('drop', $table, $this->db->query('DROP TABLE ' . $table) != false);
					break;
			}
		}
		return true;
	}

	/**
	 * Records the result of a query for the final report
	 *
	 * @param string $command create, insert, alter or drop
	 * @param string $table Name of the (prefixed) table
	 * @param bool $success Whether the query was successful
	 * @param int $count Number of affected rows (used by inserts, which are accumulated)
	 */
	private function recordResult(string $command, string $table, bool $success, int $count = 1): void {
		$results = &$this->s_tables;
		if (!$success) {
			$results = &$this->f_tables;
		}
		if ($command === 'insert') {
			$results[$command][$table] = ($results[$command][$table] ?? 0) + $count;
		} elseif (!isset($results[$command][$table])) {
			$results[$command][$table] = 1;
		}
	}

	public $successStrings = array(
    	'create'	=> TABLE_CREATED,
    	'insert'	=> ROWS_INSERTED,
    	'alter'		=> TABLE_ALTERED,
    	'drop'		=> TABLE_DROPPED,
	);
	public $failureStrings = array(
    	'create'	=> TABLE_NOT_CREATED,
    	'insert'	=> ROWS_FAILED,
    	'alter'		=> TABLE_NOT_ALTERED,
    	'drop'		=> TABLE_NOT_DROPPED,
	);


	public function report() {
		$commands = array( 'create', 'insert', 'alter', 'drop' );
		$content = '<ul class="log">';
		foreach ( $commands as $cmd) {
			if (!empty($this->s_tables[$cmd])) {
				foreach ( $this->s_tables[$cmd] as $key => $val) {
					$content .= '<li class="success">';
					$content .= ($cmd!='insert') ? sprintf( $this->successStrings[$cmd], $key ) : sprintf( $this->successStrings[$cmd], $val, $key );
					$content .= "</li>\n";
				}
			}
		}
		foreach ( $commands as $cmd) {
			if (!empty($this->f_tables[$cmd])) {
				foreach ( $this->f_tables[$cmd] as $key => $val) {
					$content .= '<li class="failure">';
					$content .= ($cmd!='insert') ? sprintf( $this->failureStrings[$cmd], $key ) : sprintf( $this->failureStrings[$cmd], $val, $key );
					$content .= "</li>\n";
				}
			}
		}
		$content .= '</ul>';
		return $content;
	}

	public function query($sql) {
		$this->db->connect();
		return $this->db->query($sql);
	}

	public function prefix($table) {
		$this->db->connect();
		return $this->db->prefix($table);
	}

	public function fetchArray($ret) {
		$this->db->connect();
		return $this->db->fetchArray($ret);
	}

	public function insert($table, $query) {
		$this->db->connect();
		$table = $this->db->prefix($table);
		$query = 'INSERT INTO '.$table.' '.$query;
		if (!$this->db->queryF($query)) {
			if (!isset($this->f_tables['insert'][$table])) {
				$this->f_tables['insert'][$table] = 1;
			} else {
				$this->f_tables['insert'][$table]++;
			}
			return false;
		} else {
			if (!isset($this->s_tables['insert'][$table])) {
				$this->s_tables['insert'][$table] = $this->db->getAffectedRows();
			} else {
				$this->s_tables['insert'][$table] += $this->db->getAffectedRows();
			}
			return $this->db->getInsertId();
		}
	}

	public function isError() {
		return !empty($this->f_tables);
	}

	public function tableExists($table) {
		$table = trim($table);
		$ret = false;
		if ($table != '') {
			$this->db->connect();
			$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix($table);
			$ret = false != $this->db->query($sql);
		}
		return $ret;
	}
}
