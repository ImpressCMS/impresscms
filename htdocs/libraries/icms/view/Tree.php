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
 * Handles all tree functions within ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		View
 * @subpackage	Tree
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: Tree.php 12313 2013-09-15 21:14:35Z skenow $
 */
/**
 * Class icms_view_Tree
 *
 * @category	ICMS
 * @package		View
 * @subpackage	Tree
 * @author Kazumi Ono (AKA onokazu)
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_view_Tree {
	/** @var string table with parent-child structure */
	public $table;
	/** @var string name of unique id for records in table $table */
	public $id;
	/** @var string name of parent id used in table $table */
	public $pid;
	/** @var string specifies the order of query results */
	public $order;
	/** @var string name of a field in table $table which will be used when  selection box and paths are generated */
	public $title;
	/** @var object an instance of the database object */
	private $db;

	/**
	 * Constructor of class icms_view_Tree
	 * Sets the names of table, unique id, and parent id
	 * @param string $table_name Name of table containing the parent-child structure
	 * @param string $id_name Name of the unique id field in the table
	 * @param $pid_name Name of the parent id field in the table
	 **/
	public function __construct($table_name, $id_name, $pid_name) {
		$this->db =& icms_db_Factory::instance();
		$this->table = $table_name;
		$this->id = $id_name;
		$this->pid = $pid_name;
	}

	/**
	 * Overloading method to allow access to private properties outside the class
	 *
	 * Instead of creating separate methods for each private property, this allows
	 * you to access (read) the properties and still keep them from being written from
	 * the public scope
	 *
	 * @param string $name
	 */
	public function __get($name) {
		if (property_exists(__CLASS__, $name)) {
			return $this->$name;
		}
		throw new RuntimeException("You tried to access a property $name that doesn't exist in " . __CLASS__);
	}
			
	/**
	 * Returns an array of first child objects for a given id($sel_id)
	 * @param integer $sel_id
	 * @param string $order Sort field for the list
	 * @return array $arr
	 **/
	public function getFirstChild($sel_id, $order = '') {
		$sel_id = (int) $sel_id;
		$arr = array();
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '="' . $sel_id . '"';
		if ($order != '') {
			$sql .= ' ORDER BY ' . $order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ($count==0) {
			return $arr;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			array_push($arr, $myrow);
		}
		return $arr;
	}

	/**
	 * Returns an array of all FIRST child ids of a given id($sel_id)
	 * @param integer $sel_id
	 * @return array $idarray
	 **/
	public function getFirstChildId($sel_id) {
		$sel_id = (int) $sel_id;
		$idarray = array();
		$result = $this->db->query('SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '="' . $sel_id . '"');
		$count = $this->db->getRowsNum($result);
		if ($count == 0) {
			return $idarray;
		}
		while (list($id) = $this->db->fetchRow($result)) {
			array_push($idarray, $id);
		}
		return $idarray;
	}

	/**
	 * Returns an array of ALL child ids for a given id($sel_id)
	 * @param integer $sel_id
	 * @param string $order Sort field for the list
	 * @param array $idarray
	 * @return array $idarray
	 **/
	public function getAllChildId($sel_id, $order = '', $idarray = array()) {
		$sel_id = (int) $sel_id;
		$sql = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '="' . $sel_id . '"';
		if ($order != '') {
			$sql .= ' ORDER BY ' . $order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ($count==0) {
			return $idarray;
		}
		while (list($r_id) = $this->db->fetchRow($result)) {
			array_push($idarray, $r_id);
			$idarray = $this->getAllChildId($r_id, $order, $idarray);
		}
		return $idarray;
	}

	/**
	 * Returns an array of ALL parent ids for a given id($sel_id)
	 * @param integer $sel_id
	 * @param string $order
	 * @param array $idarray
	 * @return array $idarray
	 **/
	public function getAllParentId($sel_id, $order = '', $idarray = array()) {
		$sel_id = (int) $sel_id;
		$sql = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '="' . $sel_id . '"';
		if ($order != '') {
			$sql .= ' ORDER BY ' . $order;
		}
		$result = $this->db->query($sql);
		list($r_id) = $this->db->fetchRow($result);
		if ($r_id == 0) {
			return $idarray;
		}
		array_push($idarray, $r_id);
		$idarray = $this->getAllParentId($r_id, $order, $idarray);
		return $idarray;
	}

	/**
	 * Generates path from the root id to a given id($sel_id)
	 * the path is delimited with "/"
	 * @param integer $sel_id
	 * @param string $title
	 * @param string $path
	 * @return string $path
	 */
	public function getPathFromId($sel_id, $title, $path = '') {
		$sel_id = (int) $sel_id;
		$result = $this->db->query('SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . '="' . $sel_id . '"');
		if ($this->db->getRowsNum($result) == 0) {
			return $path;
		}
		list($parentid, $name) = $this->db->fetchRow($result);
		$name = icms_core_DataFilter::htmlSpecialChars($name);
		$path = '/' . $name . $path . '';
		if ($parentid == 0) {
			return $path;
		}
		$path = $this->getPathFromId($parentid, $title, $path);
		return $path;
	}

	/**
	 * Makes a nicely ordered selection box
	 * @param string $title Field containing the items to display in the list
	 * @param string $order Sort order of the options
	 * @param integer $preset_id is used to specify a preselected item
	 * @param integer $none set to 1 to add an option with value 0
	 * @param string $sel_name Name of the select element
	 * @param string $onchange	Action to take when the selection is changed
	 **/
	public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = "") {
		if ($sel_name == "") {
			$sel_name = $this->id;
		}
		echo "<select name = '" . $sel_name . "'";
		if ($onchange != "") {
			echo " onchange='" . $onchange . "'";
		}
		echo ">\n";
		$sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "='0'";
		if ($order != "") {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		if ($none) {
			echo "<option value='0'>----</option>\n";
		}
		while (list($catid, $name) = $this->db->fetchRow($result)) {
			$sel = "";
			if ($catid == $preset_id) {
				$sel = " selected='selected'";
			}
			echo "<option value='$catid'$sel>$name</option>\n";
			$sel = "";
			$arr = $this->getChildTreeArray($catid, $order);
			foreach ($arr as $option) {
				$option['prefix'] = str_replace(".", "--", $option['prefix']);
				$catpath = $option['prefix'] . "&nbsp;" . icms_core_DataFilter::htmlSpecialChars($option[$title]);
				if ($option[$this->id] == $preset_id) {
					$sel = " selected='selected'";
				}
				echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
				$sel = "";
			}
		}
		echo "</select>\n";
	}

	/**
	 * Generates nicely formatted linked path from the root id to a given id
	 * @param integer $sel_id
	 * @param string $title
	 * @param string $funcURL
	 * @param string $path
	 * @param string $separator Allows custom designation of separator in linked path
	 * $return string $path
	 **/
	public function getNicePathFromId($sel_id, $title, $funcURL, $path = '', $separator = _BRDCRMB_SEP) {
		$path = !empty($path) ? $separator . $path : $path;
		$sel_id = (int) $sel_id;
		$sql = 'SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . '="' . $sel_id . '"';
		$result = $this->db->query($sql);
		if ($this->db->getRowsNum($result) == 0) {
			return $path;
		}
		list($parentid, $name) = $this->db->fetchRow($result);
		$name = icms_core_DataFilter::htmlSpecialChars($name);
		$path = '<a href="' . $funcURL . '&amp;' . $this->id . '=' . $sel_id . '">' . $name . '</a>' . $path . "";
		if ($parentid == 0) {
			return $path;
		}
		$path = $this->getNicePathFromId($parentid, $title, $funcURL, $path, $separator);
		return $path;
	}

	/**
	 * Generates id path from the root id to a given id
	 * the path is delimited with "/"
	 * @param integer $sel_id
	 * @param string $path
	 * @return string $path
	 **/
	public function getIdPathFromId($sel_id, $path = "") {
		$sel_id = (int) $sel_id;
		$result = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '="' . $sel_id . '"');
		if ($this->db->getRowsNum($result) == 0) {
			return $path;
		}
		list($parentid) = $this->db->fetchRow($result);
		$path = '/' . $sel_id . $path . '';
		if ($parentid == 0) {
			return $path;
		}
		$path = $this->getIdPathFromId($parentid, $path);
		return $path;
	}

	/**
	 * @param integer $sel_id
	 * @param string $order
	 * @param array $parray
	 * @return array $parray
	 **/
	public function getAllChild($sel_id = 0, $order = '', $parray = array()) {
		$sel_id = (int) $sel_id;
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '="' . $sel_id . '"';
		if ($order != '') {
			$sql .= ' ORDER BY ' . $order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ($count == 0) {
			return $parray;
		}
		while ($row = $this->db->fetchArray($result)) {
			array_push($parray, $row);
			$parray = $this->getAllChild($row[$this->id], $order, $parray);
		}
		return $parray;
	}

	/**
	 * @param integer $sel_id
	 * @param string $order
	 * @param array $parray
	 * @param string $r_prefix
	 * @return array $parray
	 **/
	public function getChildTreeArray($sel_id = 0, $order = '', $parray = array(), $r_prefix = '') {
		$sel_id = (int) $sel_id;
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '="' . $sel_id . '"';
		if ($order != '') {
			$sql .= ' ORDER BY ' . $order;
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ($count == 0) {
			return $parray;
		}
		while ($row = $this->db->fetchArray($result)) {
			$row['prefix'] = $r_prefix . '.';
			array_push($parray, $row);
			$parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
		}
		return $parray;
	}
}
