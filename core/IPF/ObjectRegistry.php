<?php
/**
 * Persistable object registry
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

namespace ImpressCMS\Core\IPF;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Models\AbstractExtendedHandler;

/**
 * Registry of \ImpressCMS\Core\IPF\AbstractModel
 *
 * Class responsible of caching objects to make them easily reusable without querying the database
 *
 * @deprecated Use PSR caching for this functionality! Will be removed in 2.1
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Registry
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class ObjectRegistry {

	/**
	 *
	 * @var unknown_type
	 */
	private $_registryArray;

	/**
	 * Access the only instance of this class
	 *
	 * @return	object
	 *
	 * @static
	 * @staticvar   object
	 */
	public static function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new ObjectRegistry();
		}
		return $instance;
	}

	/**
	 * Adding objects to the registry
	 *
	 * @param AbstractExtendedHandler $handler of the objects to add
	 * @param CriteriaCompo|false $criteria to pass to the getObjects method of the handler (with id_as_key)
	 *
	 * @return FALSE if an error occured
	 */
	public function addObjectsFromHandler(&$handler, $criteria = false) {
		if (method_exists($handler, 'getObjects')) {
			$objects = $handler->getObjects($criteria, true);
			$this->_registryArray['objects'][$handler->_moduleName][$handler->_itemname] = $objects;
			return $objects;
		} else {
			return false;
		}
	}

	/**
	 * Adding objects as list to the registry
	 *
	 * @param AbstractExtendedHandler $handler of the objects to add
	 * @param CriteriaCompo|false $criteria to pass to the getObjects method of the handler (with id_as_key)
	 *
	 * @return FALSE if an error occured
	 */
	public function addListFromHandler(&$handler, $criteria = false) {
		if (method_exists($handler, 'getList')) {
			$list = $handler->getList($criteria);
			$this->_registryArray['list'][$handler->_moduleName][$handler->_itemname] = $list;
			return $list;
		} else {
			return false;
		}
	}

	/**
	 * Adding objects to the registry from an item name
	 * This method will fetch the handler of the item / module and call the addObjectsFromHandler
	 *
	 * @param string $item name of the item
	 * @param string|false $modulename name of the module
	 * @param CriteriaCompo|false $criteria to pass to the getObjects method of the handler (with id_as_key)
	 *
	 * @return FALSE if an error occured
	 */
	public function addObjectsFromItemName($item, $modulename = false, $criteria = false) {
		if (!$modulename) {
			global $icmsModule;
			if (!is_object($icmsModule)) {
				return false;
			} else {
				$modulename = $icmsModule->getVar("dirname");
			}
		}
		$object_handler = icms_getModuleHandler($item, $modulename);
		return $this->addObjectsFromHandler($object_handler, $criteria);
	}

	/**
	 * Adding objects as a list to the registry from an item name
	 * This method will fetch the handler of the item / module and call the addListFromHandler
	 *
	 * @param string $item name of the item
	 * @param string|false $modulename name of the module
	 * @param CriteriaCompo|false $criteria to pass to the getObjects method of the handler (with id_as_key)
	 *
	 * @return FALSE if an error occured
	 */
	public function addListFromItemName($item, $modulename = false, $criteria = false) {
		if (!$modulename) {
			global $icmsModule;
			if (!is_object($icmsModule)) {
				return false;
			} else {
				$modulename = $icmsModule->getVar('dirname');
			}
		}
		$object_handler = icms_getModuleHandler($item, $modulename);
		return $this->addListFromHandler($object_handler, $criteria);

	}

	/**
	 * Fetching objects from the registry
	 *
	 * @param string $itemname
	 * @param string $modulename
	 *
	 * @return the requested objects or FALSE if they don't exists in the registry
	 */
	public function getObjects($itemname, $modulename) {
		if (!$modulename) {
			global $icmsModule;
			if (!is_object($icmsModule)) {
				return false;
			} else {
				$modulename = $icmsModule->getVar('dirname');
			}
		}
		if (isset($this->_registryArray['objects'][$modulename][$itemname])) {
			return $this->_registryArray['objects'][$modulename][$itemname];
		} else {
			// if they were not in registry, let's fetch them and add them to the reigistry
			$module_handler = icms_getModuleHandler($itemname, $modulename);
			if (method_exists($module_handler, 'getObjects')) {
				$objects = $module_handler->getObjects();
			}
			$this->_registryArray['objects'][$modulename][$itemname] = $objects;
			return $objects;
		}
	}

	/**
	 * Fetching objects from the registry, as a list : objectid => identifier
	 *
	 * @param string $itemname
	 * @param string $modulename
	 *
	 * @return the requested objects or FALSE if they don't exists in the registry
	 */
	public function getList($itemname, $modulename) {
		if (!$modulename) {
			global $icmsModule;
			if (!is_object($icmsModule)) {
				return false;
			} else {
				$modulename = $icmsModule->getVar('dirname');
			}
		}
		if (isset($this->_registryArray['list'][$modulename][$itemname])) {
			return $this->_registryArray['list'][$modulename][$itemname];
		} else {
			// if they were not in registry, let's fetch them and add them to the reigistry
			$module_handler = icms_getModuleHandler($itemname, $modulename);
			if (method_exists($module_handler, 'getList')) {
				$objects = $module_handler->getList();
			}
			$this->_registryArray['list'][$modulename][$itemname] = $objects;
			return $objects;
		}
	}

	/**
	 * Retreive a single object
	 *
	 * @param string $itemname
	 * @param string $key
	 *
	 * @return the requestd object or FALSE if they don't exists in the registry
	 */
	public function getSingleObject($itemname, $key, $modulename = false) {
		if (!$modulename) {
			global $icmsModule;
			if (!is_object($icmsModule)) {
				return false;
			} else {
				$modulename = $icmsModule->getVar('dirname');
			}
		}
		if (isset($this->_registryArray['objects'][$modulename][$itemname][$key])) {
			return $this->_registryArray['objects'][$modulename][$itemname][$key];
		} else {
			$objectHandler = icms_getModuleHandler($itemname, $modulename);
			$object = $objectHandler->get($key);

			if (!$object->isNew()) {
				$this->_registryArray['objects'][$modulename][$itemname][$key] = $object;
				return $object;
			} else {
				return false;
			}
		}
	}
}

