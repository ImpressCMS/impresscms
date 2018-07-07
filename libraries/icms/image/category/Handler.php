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
 * Manage of Image categories
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

/**
 * Image caetgory handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of image category class objects.
 *
 * @package	ICMS\Image\Category
 * @author	Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_image_category_Handler extends \icms_ipf_Handler {

		/**
		 * Constructor
		 *
		 * @param \icms_db_IConnection $db              Database connection
		 */
		public function __construct(&$db) {
				parent::__construct($db, 'image_category', 'imgcat_id', 'imgcat_name', '', 'icms', 'imagecategory');
		}

	/**
	 * Retrieve array of {@link icms_image_category_Object}s meeting certain conditions
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} with conditions for the image categories
	 * @param bool $id_as_key should the image category's imgcat_id be the key for the returned array?
	 *
	 * @return array {@link icms_image_category_Object}s matching the conditions
	 */
	public function getObjects($criteria = null, $id_as_key = false, $as_object = true, $sql = false, $debug = false) {
			$this->generalSQL = 'SELECT DISTINCT c.* FROM ' . $this->table . ' c LEFT JOIN '
			. $this->db->prefix('group_permission') . " l ON l.gperm_itemid=c.imgcat_id";

			$criteria_main = new \icms_db_criteria_Compo();
			$criteria_main->add(new \icms_db_criteria_Item('l.gperm_name', ['imgcat_read', 'imgcat_write'], ' IN '));
			$criteria_main->setSort('imgcat_weight, imgcat_id');
			$criteria_main->setOrder('ASC');

			if ($criteria !== null) {
				$criteria_main->add($criteria);
			}
			return parent::getObjects($criteria_main, $id_as_key, $as_object, $sql, $debug);
	}

	/**
	 * get number of {@link icms_image_category_Object}s matching certain conditions
	 *
	 * @param string $criteria conditions to match
	 *
	 * @return int number of {@link icms_image_category_Object}s matching the conditions
	 */
	public function getCount($criteria = null) {
			$this->generalSQL = 'SELECT COUNT(*) FROM ' . $this->table . ' i LEFT JOIN '
			. $this->db->prefix('group_permission') . " l ON l.gperm_itemid=i.imgcat_id";


			$criteria_main = new \icms_db_criteria_Compo();
			$criteria_main->add(new \icms_db_criteria_Item('l.gperm_name', ['imgcat_read', 'imgcat_write'], ' IN '));

			if ($criteria !== null) {
				$criteria_main->add($criteria);
			}

			return parent::getCount($criteria_main);
	}

		/**
		 * Get a list of {@link icms_image_category_Object}s matching certain conditions
		 *
		 * @param array         $groups         Groups list
		 * @param string        $perm           Permission name
		 * @param null|integer  $display        Do we need to list only visible or hidden items?
		 * @param string|null   $storetype      How to store images of this category?
		 *
		 * @return array                        array of {@link icms_image_category_Object}s matching the conditions
		 */
	public function getList($groups = array(), $perm = 'imgcat_read', $display = null, $storetype = null) {
		$criteria = new icms_db_criteria_Compo();
		if (is_array($groups) && !empty($groups)) {
			$criteriaTray = new icms_db_criteria_Compo();
			foreach ($groups as $gid) {
				$criteriaTray->add(new icms_db_criteria_Item('gperm_groupid', $gid), 'OR');
			}
			$criteria->add($criteriaTray);
			if ($perm == 'imgcat_read' || $perm == 'imgcat_write') {
				$criteria->add(new icms_db_criteria_Item('gperm_name', $perm));
				$criteria->add(new icms_db_criteria_Item('gperm_modid', 1));
			}
		}
		if (isset($display)) {
			$criteria->add(new icms_db_criteria_Item('imgcat_display', (int) ($display)));
		}
		if (isset($storetype)) {
			$criteria->add(new icms_db_criteria_Item('imgcat_storetype', $storetype));
		}
		$categories = $this->getObjects($criteria, true);
		$ret = array();
		foreach (array_keys($categories) as $i) {
			$ret[$i] = $categories[$i]->getVar('imgcat_name');
		}
		return $ret;
	}

	/**
	 * Gets list of categories for that image
	 *
	 * @param array         $groups         The usergroups to get the permissions for
	 * @param string        $perm           The permissions to retrieve
	 * @param string        $display        How display?
	 * @param string        $storetype      Storage type
	 * @param int           $imgcat_id      The image cat id
	 *
	 * @return array  list of categories
	 */
		public function getCategList($groups = array(), $perm = 'imgcat_read', $display = null, $storetype = null, $imgcat_id = null) {
		$criteria = new icms_db_criteria_Compo();
		if (is_array($groups) && !empty($groups)) {
			$criteriaTray = new icms_db_criteria_Compo();
			foreach ($groups as $gid) {
				$criteriaTray->add(new icms_db_criteria_Item('gperm_groupid', $gid), 'OR');
			}
			$criteria->add($criteriaTray);
			if ($perm == 'imgcat_read' || $perm == 'imgcat_write') {
				$criteria->add(new icms_db_criteria_Item('gperm_name', $perm));
				$criteria->add(new icms_db_criteria_Item('gperm_modid', 1));
			}
		}
		if (isset($display)) {
			$criteria->add(new icms_db_criteria_Item('imgcat_display', (int) ($display)));
		}
		if (isset($storetype)) {
			$criteria->add(new icms_db_criteria_Item('imgcat_storetype', $storetype));
		}
		if ($imgcat_id === null) {
					$imgcat_id = 0;
				}
		$criteria->add(new icms_db_criteria_Item('imgcat_pid', $imgcat_id));
		$categories = $this->getObjects($criteria, true);
		$ret = array();
		foreach (array_keys($categories) as $i) {
			$ret[$i] = $categories[$i]->getVar('imgcat_name');
			$subcategories = $this->getCategList($groups, $perm, $display, $storetype, $categories[$i]->getVar('imgcat_id'));
			foreach (array_keys($subcategories) as $j) {
				$ret[$j] = '-' . $subcategories[$j];
			}
		}

		return $ret;
	}

	/**
	 * Get the folder path or url
	 *
	 * @param integer $imgcat - Category ID
	 * @param string $full - if true return the full path or url else the relative path
	 * @param string $type - path or url
	 *
	 * @return string - full folder path or url
	 */
	public function getCategFolder(\icms_image_category_Object &$imgcat, $full = true, $type = 'path') {
		if ($imgcat->imgcat_pid != 0) {
			$sup = $this->get($imgcat->imgcat_pid);
			$supcateg = $this->getCategFolder($sup, false, $type);
		} else {
			$supcateg = 0;
		}
		$folder = ($supcateg)?$supcateg . '/':'';
		if ($full) {
			$folder = ($type == 'path')
					? ICMS_IMANAGER_FOLDER_PATH . '/' . $folder
					: ICMS_IMANAGER_FOLDER_URL . '/' . $folder;
		}

		return $folder . $imgcat->getVar('imgcat_foldername');
	}
}
