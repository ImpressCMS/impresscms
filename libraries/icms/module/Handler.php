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
 * Manage modules
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    LICENSE.txt
 */

/**
 * Module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of module class objects.
 *
 * @package    ICMS\Module
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @copyright    Copyright (c) 2000 XOOPS.org
 */
class icms_module_Handler
	extends icms_ipf_Handler {

	/**
	 * Constructor
	 *
	 * @param object $db
	 */
	public function __construct(&$db, $module = 'icms') {
		if (!$module) {
					$module = 'icms_member';
		}
		parent::__construct($db, 'module', 'mid', 'dirname', 'name', $module, 'modules', true);
	}

	/**
	 * Returns an array of all available modules, based on folders in the modules directory
	 *
	 * The getList method cannot be used for this, because uninstalled modules are not listed
	 * in the database
	 *
	 * @since    1.3
	 * @return    array    List of folder names in the modules directory
	 */
	static public function getAvailable() {
		$dirtyList = $cleanList = array();
		$dirtyList = icms_core_Filesystem::getDirList(ICMS_MODULES_PATH . '/');
		foreach ($dirtyList as $item) {
			if (file_exists(ICMS_MODULES_PATH . '/' . $item . '/icms_version.php')) {
				$cleanList[$item] = $item;
			} elseif (file_exists(ICMS_MODULES_PATH . '/' . $item . '/xoops_version.php')) {
				$cleanList[$item] = $item;
			}
		}
		return $cleanList;
	}

	/**
	 * Get a list of active modules, with the folder name as the key
	 *
	 * This method is necessary to be able to use a static method
	 *
	 * @since    1.3
	 * @return    array    List of active modules
	 */
	static public function getActive() {
		$module_handler = new self(icms::$xoopsDB);
		$criteria = new icms_db_criteria_Item('isactive', 1);
		return $module_handler->getList($criteria, true);
	}

	/**
	 * Checks if the current user can access the specified module
	 * @param icms_module_Object $module
	 * @param bool $inAdmin
	 * @return bool
	 */
	static public function checkModuleAccess($module, $inAdmin = false) {
		if ($inAdmin && !icms::$user) {
			return false;
		}
		/* @var $perm_handler icms_member_groupperm_Handler */
		$perm_handler = icms::handler('icms_member_groupperm');
		if ($inAdmin) {
			if (!$module) {
				// We are in /admin.php
				return icms::$user->isAdmin(-1);
			} else {
				return $perm_handler->checkRight('module_admin', $module->getVar('mid'), icms::$user->getGroups());
			}
		} elseif ($module) {
			$groups = (icms::$user)? icms::$user->getGroups():ICMS_GROUP_ANONYMOUS;
			return $perm_handler->checkRight('module_read', $module->getVar('mid'), $groups);
		}
		// We are in /something.php: let the page handle permissions
		return true;
	}

	/**
	 * Load a module from the database
	 *
	 * @param    int $id ID of the module
	 * @param    bool $loadConfig set to TRUE in case you want to load the module config in addition
	 * @param    bool $debug Debug enabled for object?
	 * @param   bool|object $criteria Criteria for getting object if needed
	 *
	 * @return    object  {@link icms_module_Object} FALSE on fail
	 */
	public function &get($id, $loadConfig = false, $debug = false, $criteria = false)
	{
		$module = parent::get($id, true, $debug, $criteria);
		if ($loadConfig) {
					$this->loadConfig($module);
		}
		return $module;
	}

	/**
	 * load config for a module before caching it
	 *
	 * @param    icms_module_Object $module
	 * @return    bool                TRUE
	 */
	private function loadConfig($module) {
		if ($module->config !== null) {
			return true;
		}
		icms_loadLanguageFile($module->getVar("dirname"), "main");
		if ($module->getVar("hasconfig") == 1
			|| $module->getVar("hascomments") == 1
			|| $module->getVar("hasnotification") == 1
		) {
			$module->config = icms::$config->getConfigsByCat(0, $module->getVar("mid"));
		}
		return true;
	}

	/**
	 * Load a module by its dirname
	 *
	 * @todo Make caching work!
	 *
	 * @param    string $dirname
	 * @param    bool $loadConfig set to TRUE in case you want to load the module config in addition
	 * @return    object  {@link icms_module_Object} FALSE on fail
	 */
	public function getByDirname($dirname, $loadConfig = false) {
		//if (!($module = $this->getFromCache('dirname', $dirname))) {
		$criteria = new icms_db_criteria_Item('dirname', trim($dirname));
		$criteria->setLimit(1);
		$objs = $this->getObjects($criteria);
		if (isset($objs[0])) {
			$module = $objs[0];
		} else {
			$module = null;
		}
		//}
		if ($module && $loadConfig) {
			$this->loadConfig($module);
		}
		return $module;
	}

	public function beforeSave(icms_module_Object &$module) {
		$module->setVar('last_update', time());
		return true;
	}

	/**
	 * Delete a module from the database
	 *
	 * @param   icms_module_Object &$module
	 * @param   bool $force Force to delete?
	 *
	 * @return  bool
	 */
	public function delete(&$module, $force = false) {
		if (!parent::delete($module, $force)) {
					return false;
		}
		// delete admin permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_admin' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int) $module->getVar('mid')
		);
		$this->db->query($sql);
		// delete read permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int) $module->getVar('mid')
		);
		$this->db->query($sql);

		$sql = sprintf(
			"SELECT block_id FROM %s WHERE module_id = '%u'",
			$this->db->prefix('block_module_link'), (int) $module->getVar('mid')
		);
		if ($result = $this->db->query($sql)) {
			$block_id_arr = array();
			while ($myrow = $this->db->fetchArray($result)) {
				array_push($block_id_arr, $myrow['block_id']);
			}
		}

		// loop through block_id_arr
		if (isset($block_id_arr)) {
			foreach ($block_id_arr as $i) {
				$sql = sprintf(
					"SELECT block_id FROM %s WHERE module_id != '%u' AND block_id = '%u'",
					$this->db->prefix('block_module_link'), (int) $module->getVar('mid'), (int) $i
				);
				if ($result2 = $this->db->query($sql)) {
					if (0 < $this->db->getRowsNum($result2)) {
						// this block has other entries, so delete the entry for this module
						$sql = sprintf(
							"DELETE FROM %s WHERE (module_id = '%u') AND (block_id = '%u')",
							$this->db->prefix('block_module_link'), (int) $module->getVar('mid'), (int) $i
						);
						$this->db->query($sql);
					} else {
						// this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
						$sql = sprintf(
							"UPDATE %s SET visible = '0' WHERE bid = '%u'",
							$this->db->prefix('newblocks'), (int) $i
						);
						$this->db->query($sql);
						$sql = sprintf(
							"UPDATE %s SET module_id = '-1' WHERE module_id = '%u'",
							$this->db->prefix('block_module_link'), (int) $module->getVar('mid')
						);
						$this->db->query($sql);
					}
				}
			}
		}
		if (!empty($this->_cachedModule[$module->getVar('dirname')])) {
			unset($this->_cachedModule[$module->getVar('dirname')]);
		}
		if (!empty($this->_cachedModule_lookup[$module->getVar('mid')])) {
			unset($this->_cachedModule_lookup[$module->getVar('mid')]);
		}
		return true;
	}

	/**
	 * Function and rendering for installation of a module
	 *
	 * @param    string $dirname
	 * @return    string    Results of the installation process
	 */
	public function install($dirname) {

	}

	/**
	 * Logic for uninstalling a module
	 *
	 * @param unknown_type $dirname
	 * @return    string    Result messages for uninstallation
	 */
	public function uninstall($dirname) {

	}

	/**
	 * Logic for updating a module
	 *
	 * @param    str $dirname
	 * @return    str    Result messages from the module update
	 */
	public function update($dirname) {

	}

	/**
	 * Logic for activating a module
	 *
	 * @param    int $mid
	 * @return    string    Result message for activating the module
	 */
	public function activate($mid) {

	}

	/**
	 * Logic for deactivating a module
	 *
	 * @param    int $mid
	 * @return    string    Result message for deactivating the module
	 */
	public function deactivate($mid) {

	}

	/**
	 * Logic for changing the weight (order) and name of modules
	 *
	 * @param int $mid Unique ID for the module to change
	 * @param int $weight Integer value of the weight to be applied to the module
	 * @param str $name Name to be applied to the module
	 */
	public function change($mid, $weight, $name) {

	}

	/**
	 *
	 * @param    string $dirname Directory name of the module
	 * @param    string $template Name of the template file
	 * @param    boolean $block Are you trying to retrieve the template for a block?
	 */
	public function getTemplate($dirname, $template, $block = false) {

	}

	/**
	 * Get all menu items of all activated modules
	 *
	 * @return array
	 */
	public function getAdminMenuItems() {
		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('hasadmin', 1));
		$criteria->add(new icms_db_criteria_Item('isactive', 1));
		$criteria->setOrder('ASC');
		$criteria->setSort('name');
		$modules = $this->getObjects($criteria);
		$modules_menu = [];
		foreach ($modules as $module) {
			$modules_menu[] = $module->getAdminMenuItems();
		}
		return $modules_menu;
	}
}
