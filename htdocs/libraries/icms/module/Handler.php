<?php
/**
 * Manage modules
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Module
 * @version	$Id$
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path is not defined');

/**
 * Module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of module class objects.
 *
 * @category	ICMS
 * @package		Module
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 **/
class icms_module_Handler extends icms_core_ObjectHandler {
	/**
	 * holds an array of cached module references, indexed by module id
	 *
	 * @var    array
	 * @access private
	 **/
	private $_cachedModule_mid = array();
	/**
	 * holds an array of cached module references, indexed by module dirname
	 *
	 * @var    array
	 * @access private
	 */
	private $_cachedModule_dirname = array();

	/**
	 * Create a new {@link icms_module_Object} object
	 *
	 * @param   boolean     $isNew   Flag the new object as "new"
	 * @return  object      {@link icms_module_Object}
	 **/
	public function &create($isNew = true) {
		$module = new icms_module_Object();
		if ($isNew) { $module->setNew(); }
		return $module;
	}

	/**
	 * Load a module from the database
	 *
	 * @param  	int     $id     ID of the module
	 * @return	object  {@link icms_module_Object} FALSE on fail
	 **/
	public function &get($id) {
		$id = (int) ($id);
		$module = false;
		if ($id > 0) {
			if (!empty($this->_cachedModule_mid[$id])) {
				return $this->_cachedModule_mid[$id];
			} else {
				$sql = "SELECT * FROM " . $this->db->prefix('modules') . " WHERE mid = '" . $id . "'";
				if (!$result = $this->db->query($sql)) {return $module;}
				$numrows = $this->db->getRowsNum($result);
				if ($numrows == 1) {
					$module = new icms_module_Object();
					$myrow = $this->db->fetchArray($result);
					$module->assignVars($myrow);
					$this->_cachedModule_mid[$id] =& $module;
					$this->_cachedModule_dirname[$module->getVar('dirname')] =& $module;
					return $module;
				}
			}
		}
		return $module;
	}

	/**
	 * Load a module by its dirname
	 *
	 * @param	string    $dirname
	 * @return	object  {@link icms_module_Object} FALSE on fail
	 **/
	public function &getByDirname($dirname) {
		if (!empty($this->_cachedModule_dirname[$dirname]) && $this->_cachedModule_dirname[$dirname]->getVar('dirname') == $dirname) {
			return $this->_cachedModule_dirname[$dirname];
		} else {
			$module = false;
			$sql = "SELECT * FROM " . $this->db->prefix('modules') . " WHERE dirname = '" . trim($dirname) . "'";
			if (!$result = $this->db->query($sql)) {return $module;}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$module = new icms_module_Object();
				$myrow = $this->db->fetchArray($result);
				$module->assignVars($myrow);
				$this->_cachedModule_dirname[$dirname] =& $module;
				$this->_cachedModule_mid[$module->getVar('mid')] =& $module;
			}
			return $module;
		}
	}

	/**
	 * Inserts a module into the database
	 *
	 * @param   object  &$module reference to a {@link icms_module_Object}
	 * @return  bool
	 **/
	public function insert(&$module) {
		if (get_class($module) != 'icms_module_Object') { return false; }
		if (!$module->isDirty()) { return true; }
		if (!$module->cleanVars()) { return false; }

		/**
		 * Editing the insert and update methods
		 * this is temporaray as will soon be based on a persistableObjectHandler
		 **/
		$fieldsToStoreInDB = array();
		foreach ($module->cleanVars as $k => $v) {
			if ($k == 'last_update') { $v = time(); }
			if ($module->vars[$k]['data_type'] == XOBJ_DTYPE_INT) {
				$cleanvars[$k] = (int) ($v);
			} elseif (is_array($v)) {
				$cleanvars[$k] = $this->db->quoteString(implode(',', $v));
			} else {
				$cleanvars[$k] = $this->db->quoteString($v);
			}
			$fieldsToStoreInDB[$k] = $cleanvars[$k];
		}

		if ($module->isNew()) {
			$sql = "INSERT INTO " . $this->db->prefix('modules')
				. " (" . implode(',', array_keys($fieldsToStoreInDB))
				. ") VALUES (" . implode(',', array_values($fieldsToStoreInDB)) . ")";
		} else {
			$sql = "UPDATE " . $this->db->prefix('modules') . " SET";
			foreach ($fieldsToStoreInDB as $key => $value) {
				if (isset($notfirst)) { $sql .= ","; }
				$sql .= " " . $key . " = " . $value;
				$notfirst = true;
			}
			$whereclause = 'mid' . " = " . $module->getVar('mid');
			$sql .= " WHERE " . $whereclause;
		}

		if (!$result = $this->db->query($sql)) { return false; }
		if ($module->isNew()) { $module->assignVar('mid', $this->db->getInsertId()); }
		if (!empty($this->_cachedModule_dirname[$module->getVar('dirname')])) {
			unset($this->_cachedModule_dirname[$module->getVar('dirname')]);
		}
		if (!empty($this->_cachedModule_mid[$module->getVar('mid')])) {
			unset($this->_cachedModule_mid[$module->getVar('mid')]);
		}
		return true;
	}

	/**
	 * Delete a module from the database
	 *
	 * @param   object  &$module {@link icms_module_Object}
	 * @return  bool
	 **/
	public function delete(&$module) {
		if (get_class($module) != 'icms_module_Object') { return false; }

		$sql = sprintf(
			"DELETE FROM %s WHERE mid = '%u'",
			$this->db->prefix('modules'), (int) ($module->getVar('mid'))
		);
		if (!$result = $this->db->query($sql)) { return false; }

		// delete admin permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_admin' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int) ($module->getVar ('mid'))
		);
		$this->db->query($sql);
		// delete read permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int) ($module->getVar ('mid'))
		);
		$this->db->query($sql);

		$sql = sprintf(
			"SELECT block_id FROM %s WHERE module_id = '%u'",
			$this->db->prefix('block_module_link'), (int) ($module->getVar('mid'))
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
					$this->db->prefix('block_module_link'), (int) ($module->getVar('mid')), (int) ($i)
				);
				if ($result2 = $this->db->query($sql)) {
					if (0 < $this->db->getRowsNum($result2)) {
						// this block has other entries, so delete the entry for this module
						$sql = sprintf(
							"DELETE FROM %s WHERE (module_id = '%u') AND (block_id = '%u')",
							$this->db->prefix('block_module_link'), (int) ($module->getVar('mid')), (int) ($i)
						);
						$this->db->query($sql);
					} else {
						// this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
						$sql = sprintf(
							"UPDATE %s SET visible = '0' WHERE bid = '%u'",
							$this->db->prefix('newblocks'), (int) ($i)
						);
						$this->db->query($sql);
						$sql = sprintf(
							"UPDATE %s SET module_id = '-1' WHERE module_id = '%u'",
							$this->db->prefix('block_module_link'), (int) ($module->getVar('mid'))
						);
						$this->db->query($sql);
					}
				}
			}
		}

		if (!empty($this->_cachedModule_dirname[$module->getVar('dirname')])) {
			unset($this->_cachedModule_dirname[$module->getVar('dirname')]);
		}
		if (!empty($this->_cachedModule_mid[$module->getVar('mid')])) {
			unset($this->_cachedModule_mid[$module->getVar('mid')]);
		}
		return true;
	}

	/**
	 * Load some modules
	 *
	 * @param   object  $criteria   {@link icms_db_criteria_Element}
	 * @param   boolean $id_as_key  Use the ID as key into the array
	 * @return  array
	 **/
	public function getObjects($criteria = null, $id_as_key = false) {
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM " . $this->db->prefix('modules');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
			$sql .= " ORDER BY weight " . $criteria->getOrder() . ", mid ASC";
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) { return $ret; }
		while ($myrow = $this->db->fetchArray($result)) {
			$module = new icms_module_Object();
			$module->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] = & $module;
			} else {
				$ret[$myrow['mid']] =& $module;
			}
			$this->_cachedModule_mid[$myrow['mid']] =& $module;
			$this->_cachedModule_dirname[$myrow['dirname']] =& $module;
			unset($module);
		}
		return $ret;
	}

	/**
	 * Count some modules
	 *
	 * @param   object  $criteria   {@link icms_db_criteria_Element}
	 * @return  int
	 **/
	public function getCount($criteria = null) {
		$sql = "SELECT COUNT(*) FROM " . $this->db->prefix('modules');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) { return 0; }
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * returns an array of installed module names
	 *
	 * @param   bool    $criteria
	 * @param   boolean $dirname_as_key
	 *      if true, array keys will be module directory names
	 *      if false, array keys will be module id
	 * @return  array
	 */
	public function getList($criteria = null, $dirname_as_key = false) {
		$ret = array();
		$modules = & $this->getObjects($criteria, true);
		foreach (array_keys($modules) as $i) {
			if (!$dirname_as_key) {
				$ret[$i] = $modules[$i]->getVar('name');
			} else {
				$ret[$modules[$i]->getVar('dirname')] = $modules[$i]->getVar('name');
			}
		}
		return $ret;
	}

	/**
	 * Returns an array of all available modules, based on folders in the modules directory
	 *
	 * The getList method cannot be used for this, because uninstalled modules are not listed
	 * in the database
	 *
	 * @since	1.3
	 * @return	array	List of folder names in the modules directory
	 */
	static public function getAvailable() {
		$dirtyList = $cleanList = array();
		$dirtyList = icms_core_Filesystem::getDirList(ICMS_ROOT_PATH . '/modules/');
		foreach ($dirtyList as $item) {
			if (file_exists(ICMS_ROOT_PATH . '/modules/' . $item . '/icms_version.php')) {
				$cleanList[$item] = $item;
			} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $item . '/xoops_version.php')) {
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
	 * @since	1.3
	 * @return	array	List of active modules
	 */
	static public function getActive() {
		$module_handler = new self(icms::$db);
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('isactive', 1));
		return $module_handler->getList($criteria, TRUE);
	}
}

