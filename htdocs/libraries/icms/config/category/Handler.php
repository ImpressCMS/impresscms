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
 * Manage configuration categories
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Config
 * @subpackage	Category
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Configuration category handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of configuration category class objects.
 *
 * @author  	Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 				You should have received a copy of XOOPS_copyrights.txt with
 * 				this file. If not, you may obtain a copy from xoops.org
 *
 * @category	ICMS
 * @package     Config
 * @subpackage  Category
 */
class icms_config_category_Handler extends icms_core_ObjectHandler {

	/**
	 * Create a new category
	 *
	 * @param	bool    $isNew  Flag the new object as "new"?
	 *
	 * @return	object  New {@link icms_config_category_Object}
	 * @see htdocs/kernel/icms_core_ObjectHandler#create()
	 */
	public function &create($isNew = true)	{
		$confcat = new icms_config_category_Object();
		if ($isNew) {
			$confcat->setNew();
		}
		return $confcat;
	}

	/**
	 * Retrieve a {@link icms_config_category_Object}
	 *
	 * @param	int $id ConfigCategoryID to get
	 *
	 * @return	object|false  {@link icms_config_category_Object}, FALSE on fail
	 * @see htdocs/kernel/icms_core_ObjectHandler#get($int_id)
	 */
	public function &get($id) {
		$confcat = false;
		$id = (int) $id;
		if ($id > 0) {
			$sql = "SELECT * FROM " . $this->db->prefix('configcategory') . " WHERE confcat_id='" . $id . "'";
			if (!$result = $this->db->query($sql)) {
				return $confcat;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$confcat = new icms_config_category_Object();
				$confcat->assignVars($this->db->fetchArray($result), false);
			}
		}
		return $confcat;
	}

	/**
	 * Insert a {@link icms_config_category_Object} into the DataBase
	 *
	 * @param	object   &$confcat  {@link icms_config_category_Object}
	 *
	 * @return	bool    TRUE on success
	 * @see htdocs/kernel/icms_core_ObjectHandler#insert($object)
	 */
	public function insert(&$confcat) {
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (!is_a($confcat, 'icms_config_category_Object')) {
			return false;
		}
		if (!$confcat->isDirty()) {
			return true;
		}
		if (!$confcat->cleanVars()) {
			return false;
		}
		foreach ( $confcat->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if ($confcat->isNew()) {
			$confcat_id = $this->db->genId('configcategory_confcat_id_seq');
			$sql = sprintf(
				"INSERT INTO %s (confcat_id, confcat_name, confcat_order)
				VALUES ('%u', %s, '%u')",
				$this->db->prefix('configcategory'), (int) ($confcat_id), $this->db->quoteString($confcat_name), (int) ($confcat_order)
				);
		} else {
			$sql = sprintf(
				"UPDATE %s SET confcat_name = %s, confcat_order = '%u'
				WHERE confcat_id = '%u'",
				$this->db->prefix('configcategory'), $this->db->quoteString($confcat_name), (int) ($confcat_order), (int) ($confcat_id));
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		if (empty($confcat_id)) {
			$confcat_id = $this->db->getInsertId();
		}
		$confcat->assignVar('confcat_id', $confcat_id);
		return $confcat_id;
	}

	/**
	 * Delelete a {@link icms_config_category_Object}
	 *
	 * @param	object  &$confcat   {@link icms_config_category_Object}
	 *
	 * @return	bool    TRUE on success
	 * @see htdocs/kernel/icms_core_ObjectHandler#delete($object)
	 */
	public function delete(&$confcat) {
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (!is_a($confcat, 'icms_config_category_Object')) {
			return false;
		}

		$sql = sprintf(
			"DELETE FROM %s WHERE confcat_id = '%u'",
			$this->db->prefix('configcategory'), (int) ($configcategory->getVar('confcat_id'))
			);
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	/**
	 * Get some {@link icms_config_category_Object}s
	 *
	 * @param	object  $criteria   {@link icms_db_criteria_Element}
	 * @param	bool    $id_as_key  Use the IDs as keys to the array?
	 *
	 * @return	array   Array of {@link icms_config_category_Object}s
	 */
	public function getObjects($criteria = null, $id_as_key = false) {
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM ' . $this->db->prefix('configcategory');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= ' '.$criteria->renderWhere();
			$sort = !in_array($criteria->getSort(), array('confcat_id', 'confcat_name', 'confcat_order'))
					? 'confcat_order'
					: $criteria->getSort();
			$sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$confcat = new icms_config_category_Object();
			$confcat->assignVars($myrow, false);
			if (!$id_as_key) {
				$ret[] =& $confcat;
			} else {
				$ret[$myrow['confcat_id']] =& $confcat;
			}
			unset($confcat);
		}
		return $ret;
	}
}

