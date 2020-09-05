<?php
/**
 * Handler
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of derived class objects as well as some basic operations inherant to objects manipulation
 *
 * @copyright    The ImpressCMS Project http://www.impresscms.org/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since    1.1
 * @author    marcan <marcan@impresscms.org>
 * @author    This was inspired by Mithrandir PersistableObjectHanlder: Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @author    Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 * @todo    Properly determine visibility for methods and vars (private, protected, public) and apply naming conventions
 */

namespace ImpressCMS\Core\Models;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaElement;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Database\DatabaseConnectionInterface;
use ImpressCMS\Core\Debug;
use ImpressCMS\Core\File\Filesystem;
use ImpressCMS\Core\IPF\Module;
use ImpressCMS\Core\Properties\AbstractProperties;
use ImpressCMS\Core\Security\PermissionsManager;
use PDO;

/**
 * Persistable Object Handlder
 *
 * @package    ICMS\IPF
 * @since    1.1
 * @todo    Properly name the vars using the naming conventions
 */
class AbstractExtendedHandler extends AbstractHandler {

	/**
	 * Loaded items cache
	 *
	 * @var array
	 */
	protected static $_loadedItems = array();
	protected static $cached_fields = array();
	/**
	 * The name of the IPF object
	 *
	 * @var string
	 * @todo    Rename using the proper naming convention (this is a public var)
	 */
	public $_itemname = '';
	/**
	 * Name of the table use to store objects linked with this handler
	 *
	 * Note that the name of the table needs to be free of the database prefix.
	 * For example "smartsection_categories"
	 * @var string
	 */
	public $table = '';
	/**
	 * Name of the table key that uniquely identify each object
	 *
	 * For example : "categoryid"
	 * @var string
	 */
	public $keyName = '';
	/**
	 * Name of the class derived from object and which this handler is handling
	 *
	 * Note that this string needs to be lowercase
	 *
	 * For example : "smartsectioncategory"
	 * @var string
	 */
	public $className = '';
	/**
	 * Name of the field which properly identify the object
	 *
	 * For example : "name" (this will be the category's name)
	 * @var string
	 */
	public $identifierName = '';
	/**
	 * Name of the field which will be use as a summary for the object
	 *
	 * For example : "summary"
	 * @var string
	 */
	public $summaryName = '';
	/**
	 * Page name use to basically manage and display the object
	 *
	 * This page needs to be the same in user side and admin side
	 *
	 * For example category.php - we will deduct smartsection/category.php as well as smartsection/admin/category.php
	 * @todo this could probably be automatically deducted from the class name - for example, the class SmartsectionCategory will have "category.php" as it's managing page
	 * @todo    Rename using the proper naming convention - this is a public var
	 *
	 * @var string
	 */
	public $_page = '';
	/**
	 * Full path of the module using this object
	 *
	 * <code>ICMS_URL . "/modules/smartsection/"</code>
	 * @todo this could probably be automatically deducted from the class name as it is always prefixed with the module name
	 * @var string
	 */
	public $_modulePath = '';
	/**
	 * Module URL
	 *
	 * @var string
	 */
	public $_moduleUrl = '';
	/**
	 *
	 * The name of the module for the object
	 * @var string
	 * @todo    Rename using the proper naming convention (This is a public var)
	 */
	public $_moduleName = '';
	/**
	 * Is upload enabled?
	 *
	 * @var bool
	 */
	public $uploadEnabled = false;
	/**
	 * Upload URL
	 *
	 * @var string
	 */
	public $_uploadUrl = '';
	/**
	 * Upload Path
	 *
	 * @var string
	 */
	public $_uploadPath = '';
	/**
	 * Allowed mimetypes
	 *
	 * @var array
	 */
	public $_allowedMimeTypes = [];
	/**
	 * Max allowed file size for upload
	 *
	 * @var int
	 */
	public $_maxFileSize = 1000000;
	/**
	 * Max allowed width for file upload
	 *
	 * @var int
	 */
	public $_maxWidth = 500;
	/**
	 * Max file height for upload
	 *
	 * @var int
	 */
	public $_maxHeight = 500;
	/**
	 * What fields to highlight?
	 *
	 * @var array
	 */
	public $highlightFields = [];
	/**
	 * What columns should be viisble.
	 * Empty array means all.
	 *
	 * @var array
	 */
	public $visibleColumns = [];
	/**
	 * Array containing the events name and functions
	 *
	 * @var array
	 */
	public $eventArray = [];
	/**
	 * Array containing the permissions that this handler will manage on the objects
	 *
	 * @var array
	 */
	public $permissionsArray = [];
	/**
	 * Some SQL that will be used as base for all operations for this handler
	 *
	 * @var string|false|null
	 */
	public $generalSQL = '';
	/**
	 * Events hooks
	 *
	 * @var array
	 */
	public $_eventHooks = [];
	/**
	 * Disabled events
	 *
	 * @var array
	 */
	public $_disabledEvents = [];
	/**
	 * Is debug mode?
	 *
	 * @var bool
	 */
	public $debugMode = false;

	/**
	 * Constructor - called from child classes
	 *
	 * @param DatabaseConnectionInterface $db Database object
	 * @param string $itemname Object to be managed
	 * @param string $keyname Name of the table key that uniquely identify each object
	 * @param string $idenfierName Name of the field which properly identify the object
	 * @param string $summaryName Name of the field which will be use as a summary for the object
	 * @param string $modulename Directory name of the module controlling this object
	 * @param null|string $table Table to use for this handler
	 * @param bool $cacheHandler Cache handler
	 */
	public function __construct(&$db, $itemname, $keyname, $idenfierName, $summaryName, $modulename = null, $table = null, $cacheHandler = false)
	{

		parent::__construct($db);

		// Todo: Autodect module
		if ($modulename === null || $modulename === 'icms') {
			$this->_moduleName = 'icms';
			if (!class_exists($classname = '\\ImpressCMS\\Core\\Models\\' . ucfirst($itemname))) {
				$classname = $this->_moduleName . '_' . $itemname . '_Object';
			}
			if ($table == null) {
				$table = $itemname;
			}
		} else {
			$this->_moduleName = $modulename;
			$classname = substr(get_class($this), 0, -7);
			if ($table === null) {
				$table = $this->_moduleName . '_' . $itemname;
			}
		}

		/**
		 * @todo this could probably be removed after refactopring is completed
		 * to be evaluated...
		 */
		if (!class_exists($classname)) {
			$classname = ucfirst($this->_moduleName) . ucfirst($itemname);
		}

		$this->table = $db->prefix($table);
		$this->_itemname = $itemname;
		$this->keyName = $keyname;
		$this->className = $classname;
		$this->identifierName = $idenfierName;
		$this->summaryName = $summaryName;
		$this->_page = $itemname . '.php';
		$this->_modulePath = ICMS_ROOT_PATH . '/modules/' . $this->_moduleName . '/';
		$this->_moduleUrl = ICMS_URL . '/modules/' . $this->_moduleName . '/';
		$this->_uploadPath = ICMS_UPLOAD_PATH . '/' . $this->_moduleName . '/';
		$this->_uploadUrl = ICMS_UPLOAD_URL . '/' . $this->_moduleName . '/';
	}

	/**
	 *
	 * @param string $event
	 * @param string $method
	 */
	public function addEventHook($event, $method)
	{
		$this->_eventHooks[$event] = $method;
	}

	/**
	 * Add a permission that this handler will manage for its objects
	 *
	 * Example : $this->addPermission('view', _AM_SSHOP_CAT_PERM_READ, _AM_SSHOP_CAT_PERM_READ_DSC);
	 *
	 * @param string $perm_name name of the permission
	 * @param string $caption caption of the control that will be displayed in the form
	 * @param string|false $description description of the control that will be displayed in the form
	 */
	public function addPermission($perm_name, $caption, $description = false) {
		$this->permissionsArray[] = [
			'perm_name' => $perm_name,
			'caption' => $caption,
			'description' => $description
		];
	}

	/**
	 *
	 * @param CriteriaItem $criteria
	 * @param string $perm_name
	 * @return bool
	 */
	public function setGrantedObjectsCriteria(&$criteria, $perm_name) {
		$icmspermissions_handler = new PermissionsManager($this);
		$grantedItems = $icmspermissions_handler->getGrantedItems($perm_name);
		if (count($grantedItems) > 0) {
			$criteria->add(new CriteriaItem($this->keyName, '(' . implode(', ', $grantedItems) . ')', 'IN'));
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Runs precalculated info
	 *
	 * @param array $field_func
	 * @param CriteriaElement $criteria
	 * @param bool $debug
	 *
	 * @return array
	 */
	public function getCalculatedInfo(array $field_func, CriteriaElement $criteria = null, $debug = false) {
		if (empty($field_func)) {
			return array();
		}

		$sql = 'SELECT ';
		foreach ($field_func as $field => $func) {
			$sql .= $func . '(`' . $field . '`) ' . $field . '_' . $func . ', ';
		}
		$sql = substr($sql, 0, -2);
		$sql .= ' FROM ' . $this->table;

		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
			if ($criteria->groupby) {
				$sql .= $criteria->getGroupby();
			}
			if ($criteria->getSort() !== '') {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
		} else {
			$args = [];
		}

		if ($debug) {
			Debug::message($sql);
		}

		$result = $this->db->perform($sql, $args);

		if (!$result) {
			return null;
		}

		return $result->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * retrieve objects with debug mode - so will show the query
	 *
	 * @param CriteriaElement $criteria Criteria conditions to be met
	 * @param bool $id_as_key use the ID as key for the array?
	 * @param bool $as_object return an array of objects?
	 * @param bool|string $sql Extra SQL to use in query
	 * @return array
	 *
	 * @deprecated Use getObjects() instead. Since 2.0
	 *
	 */
	public function getObjectsD($criteria = null, $id_as_key = false, $as_object = true, $sql = false)
	{
		return $this->getObjects($criteria, $id_as_key, $as_object, $sql, true);
	}

	/**
	 * retrieve objects from the database
	 *
	 * @param CriteriaElement $criteria Criteria conditions to be met
	 * @param bool $id_as_key use the ID as key for the array?
	 * @param bool $as_object return an array of objects?
	 * @param false|string $sql Extra sql to use in query?
	 * @param bool $debug Do we need to debug
	 *
	 * @return array
	 */
	public function getObjects($criteria = null, $id_as_key = false, $as_object = true, $sql = false, $debug = false)
	{
		$limit = $start = 0;

		if ($this->generalSQL) {
			$sql = $this->generalSQL;
		} elseif (!$sql) {
			$sql = 'SELECT ' . $this->getFields(true, true) . ' FROM `' . $this->table . '` AS ' . $this->_itemname;
		}

		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
			if ($criteria->getSort() !== '') {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		} else {
			$args = [];
		}

		if ($debug) {
			Debug::message($sql);
		}

		if (!empty($limit)) {
			$sql .= ' LIMIT ' . ((int)$start) . ', ' . ((int)$limit);
		}

		$result = $this->db->perform($sql, $args);

		return (!$result) ? [] : $this->convertResultSet($result, $id_as_key, $as_object);
	}

	/**
	 * Gets all fields for SQL
	 *
	 * @param bool $getcurrent Get current fields
	 * @param bool $forSQL Returns fields result as for SQL
	 *
	 * @return string
	 */
	protected function getFields($getcurrent = true, $forSQL = false)
	{
		if (!empty($this->visibleColumns) && $getcurrent) {
			$ret = $this->visibleColumns;
		} else {
			if (!isset(self::$cached_fields[$this->className])) {
				$obj = new $this->className($this, array());
				$ret = array();
				foreach ($obj->getVars() as $key => $var) {
					if (isset($var['persistent']) && !$var['persistent']) {
						continue;
					}
					$ret[] = $key;
				}
				self::$cached_fields[$this->className] = $ret;
			} else {
				$ret = self::$cached_fields[$this->className];
			}
		}
		if ($forSQL) {
			return '`' . implode('`, `', $ret) . '`';
		}
		return $ret;
	}

	/**
	 * Convert a database resultset to a returnable array
	 *
	 * @param object $result database resultset
	 * @param bool $id_as_key - should NOT be used with joint keys
	 * @param bool $as_object
	 *
	 * @return array
	 */
	public function convertResultSet($result, $id_as_key = false, $as_object = true)
	{
		if ($id_as_key === true) {
			$id_as_key = $this->keyName;
		} elseif (($id_as_key == 'parent_id') && isset($this->parentName)) {
			$id_as_key = $this->parentName;
		}

		if ($as_object === null) {
			return ($id_as_key !== false) ? $this->convertResultSet_RAWWithKey($result, $id_as_key) : $this->convertResultSet_RAW($result);
		} else {
			return ($id_as_key !== false) ? $this->convertResultSet_ObjectWithKey($result, $id_as_key, $as_object) : $this->convertResultSet_Object($result, $as_object);
		}
	}

	protected function convertResultSet_RAWWithKey($result, $key)
	{
		$ret = array();
		if ($this->keyName === $key) {
			while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
				$ret[$key] = $myrow;
			}
		} else {
			while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
				$ret[$myrow[$key]][$myrow[$this->keyName]] = $myrow;
			}
		}
		return $ret;
	}

	protected function convertResultSet_RAW($result)
	{
		$ret = array();
		while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
			$ret[] = $myrow;
		}
		return $ret;
	}

	protected function convertResultSet_ObjectWithKey($result, $key, $as_object)
	{
		$fields_sk = $this->getSkipKeys();
		$se = count($fields_sk) === 0;
		$ret = array();
		if ($this->keyName === $key) {
			while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
				$kname = $myrow[$this->keyName];
				if (isset(self::$_loadedItems[$this->className][$kname])) {
					$iname = self::$_loadedItems[$this->className][$kname]->getVar($this->keyName);
					if ($as_object) {
						$ret[$iname] = &self::$_loadedItems[$this->className][$kname];
					} else {
						$ret[$iname] = self::$_loadedItems[$this->className][$kname]->toArray();
					}
					continue;
				}
				$obj = new $this->className($this, $myrow);
				if (!$obj->isLoadedOnCreation()) {
					$obj->assignVars($myrow);
					$obj->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
				}
				if (isset($fields_sk)) {
					$obj->setVarInfo($fields_sk, AbstractProperties::VARCFG_NOTLOADED, true);
				}
				//if (!$obj->handler)
				//    $obj->handler = $this;
				if ($this->uploadEnabled) {
					$obj->setImageDir($this->getImageUrl(), $this->getImagePath());
				}

				if ($se) {
					self::$_loadedItems[$this->className][$kname] = $obj;
					if ($as_object) {
						$ret[$obj->getVar($this->keyName)] = &self::$_loadedItems[$this->className][$kname];
					} else {
						$ret[$obj->getVar($this->keyName)] = $obj->toArray();
					}
				} else {
					$ret[$obj->getVar($this->keyName)] = $as_object ? $obj : $obj->toArray();
				}
			}
		} else {
			while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
				$kname = $myrow[$this->keyName];
				if (isset(self::$_loadedItems[$this->className][$kname])) {
					$iname = self::$_loadedItems[$this->className][$kname]->getVar($this->keyName);
					$cname = self::$_loadedItems[$this->className][$kname]->getVar($key, 'e');
					if ($as_object) {
						$ret[$cname][$iname] = &self::$_loadedItems[$this->className][$kname];
					} else {
						$ret[$cname][$iname] = self::$_loadedItems[$this->className][$kname]->toArray();
					}
					continue;
				}
				$obj = new $this->className($this, $myrow);
				if (!$obj->isLoadedOnCreation()) {
					$obj->assignVars($myrow);
					$obj->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
				}
				if (isset($fields_sk)) {
					$obj->setVarInfo($fields_sk, AbstractProperties::VARCFG_NOTLOADED, true);
				}
				//if (!$obj->handler)
				//    $obj->handler = $this;
				if ($this->uploadEnabled) {
					$obj->setImageDir($this->getImageUrl(), $this->getImagePath());
				}

				if ($se) {
					self::$_loadedItems[$this->className][$myrow[$this->keyName]] = $obj;
					if ($as_object) {
						$ret[$obj->getVar($key, 'e')][$obj->getVar($this->keyName)] = &self::$_loadedItems[$this->className][$kname];
					} else {
						$ret[$obj->getVar($key, 'e')][$obj->getVar($this->keyName)] = $obj->toArray();
					}
				} else {
					$ret[$obj->getVar($key, 'e')][$obj->getVar($this->keyName)] = $as_object ? $obj : $obj->toArray();
				}
			}
		}
		return $ret;
	}

	/**
	 * Get array with keys for skipping
	 *
	 * @return array
	 */
	public function getSkipKeys()
	{
		if (!empty($this->visibleColumns)) {
			$fields_sk = $this->getFields(false, false);
			return array_diff($fields_sk, $this->visibleColumns);
		} else {
			return [];
		}
	}

	/**
	 *
	 */
	public function getImageUrl()
	{
		return $this->_uploadUrl . $this->_itemname . '/';
	}

	/**
	 *
	 */
	public function getImagePath()
	{
		$dir = $this->_uploadPath . $this->_itemname;
		if (!file_exists($dir)) {
			Filesystem::mkdir($dir);
		}
		return $dir . '/';
	}

	protected function convertResultSet_Object($result, $as_object)
	{
		$fields_sk = $this->getSkipKeys();
		$se = count($fields_sk) === 0;
		$ret = array();
		while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
			$kname = $myrow[$this->keyName];

			if (isset(self::$_loadedItems[$this->className][$kname])) {
				//$iname = self::$_loadedItems[$this->className][$kname]->getVar($this->keyName);
				if ($as_object) {
					$ret[] = &self::$_loadedItems[$this->className][$kname];
				} else {
					$ret[] = self::$_loadedItems[$this->className][$kname]->toArray();
				}
				continue;
			}

			$obj = new $this->className($this, $myrow);
			if (!$obj->isLoadedOnCreation()) {
				$obj->assignVars($myrow);
				$obj->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
			}

			if (isset($fields_sk)) {
				$obj->setVarInfo($fields_sk, AbstractProperties::VARCFG_NOTLOADED, true);
			}
			//if (!$obj->handler)
			//    $obj->handler = $this;
			if ($this->uploadEnabled) {
				$obj->setImageDir($this->getImageUrl(), $this->getImagePath());
			}

			if ($se) {
				self::$_loadedItems[$this->className][$kname] = $obj;
				if ($as_object) {
					$ret[] = &self::$_loadedItems[$this->className][$kname];
				} else {
					$ret[] = $obj->toArray();
				}
			} else {
				$ret[] = $as_object ? $obj : $obj->toArray();
			}


		}
		return $ret;
	}

	/**
	 * retrieve object
	 *
	 * @param string|float|int $id ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
	 * @param bool $as_object whether to return an object or an array
	 *
	 * @return AbstractExtendedModel|false
	 *
	 * @deprecated Use get() instead. Since 2.0
	 */
	public function &getD($id, $as_object = true)
	{
		return $this->get($id, $as_object, true);
	}

	/**
	 * retrieve a object by id
	 *
	 * @param string|int|float $id ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
	 * @param bool $as_object whether to return an object or an array
	 *
	 * @return AbstractExtendedModel|false
	 */
	public function &get($id, $as_object = true, $debug = false, $criteria = false)
	{
		if (is_array($this->keyName)) {
			if (!$criteria) {
				$criteria = new CriteriaCompo();
			}
			foreach ($this->keyName as $i => $keyName) {
				/**
				 * In some situations, the $id is not an INTEGER. icms_ipf_ObjectTag is an example.
				 * Is the fact that we removed the intval() represents a security risk ?
				 */
				//$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem($keyName, ($id[$i]), '=', $this->_itemname));
				$criteria->add(new CriteriaItem($keyName, $id[$i], '=', $this->_itemname));
			}
		} else {
			if (!$criteria) {
				$criteria = new CriteriaItem($this->keyName, $id);
			} else {
				//$criteria = new \ImpressCMS\Core\Database\Criteria\CriteriaItem($this->keyName, intval($id), '=', $this->_itemname);
				/**
				 * In some situations, the $id is not an INTEGER. icms_ipf_ObjectTag is an example.
				 * Is the fact that we removed the intval() represents a security risk ?
				 */
				$criteria->add(new CriteriaItem($this->keyName, $id, '=', $this->_itemname));
			}
		}

		$criteria->setLimit(1);

		$obj_array = $this->getObjects($criteria, false, $as_object);
		//patch : weird bug of indexing by id even if id_as_key = false;
		if (count($obj_array) && !isset($obj_array[0]) && is_object($obj_array[$id])) {
			$obj_array[0] = $obj_array[$id];
			unset($obj_array[$id]);
			$obj_array[0]->unsetNew();
		}

		if (count($obj_array) !== 1) {
			$obj = $this->create();
			return $obj;
		}

		return $obj_array[0];
	}

	/**
	 * create a new object
	 *
	 * @param bool $isNew Flag the new objects as "new"?
	 *
	 * @return AbstractExtendedModel
	 */
	public function &create($isNew = true)
	{

		$obj = new $this->className($this);

		if ($isNew) {
			$obj->setNew();
		}

		if ($this->uploadEnabled) {
			$obj->setImageDir($this->getImageUrl(), $this->getImagePath());
		}

		return $obj;
	}

	/**
	 *
	 * @param object $criteria
	 * @param int $limit
	 * @param int $start
	 * @return array
	 * @deprecated Use getList() instead. Since 2.0
	 *
	 */
	public function getListD($criteria = null, $limit = 0, $start = 0)
	{
		return $this->getList($criteria, $limit, $start, true);
	}

	/**
	 * Retrieve a list of objects as arrays - DON'T USE WITH JOINT KEYS
	 *
	 * @param CriteriaElement $criteria Criteria conditions to be met
	 * @param int   $limit      Max number of objects to fetch
	 * @param int   $start      Which record to start at
	 *
	 * @return array
	 */
	public function getList($criteria = null, $limit = 0, $start = 0, $debug = false)
	{
		return $this->getCustomList($this->keyName, $this->getIdentifierName(false), $criteria, $limit, $start, $debug);
	}

	/**
	 * Retrieve a list of objects as arrays - DON'T USE WITH JOINT KEYS
	 *
	 * @param string|string[] $keyName  Key name
	 * @param string $keyValue Key value
	 * @param CriteriaElement $criteria Criteria conditions to be met
	 * @param int   $limit     Max number of objects to fetch
	 * @param int   $start     Which record to start at
	 * @param bool $debug Debug mode?
	 *
	 * @return array
	 */
	public function getCustomList($keyName, $keyValue, $criteria = null, $limit = 0, $start = 0, $debug = false)
	{
		$ret = array();
		if ($criteria === null) {
			$criteria = new icms_db_criteria_Compo();
		}

		if ($criteria->getSort() === '') {
			$criteria->setSort($keyValue);
		}

		$sql = 'SELECT ' . (is_array($keyName) ? implode(', ', $keyName) : $keyName);
		if (!empty($keyValue)) {
			$sql .= ', ' . $keyValue;
		}
		$sql .= ' FROM ' . $this->table . ' AS ' . $this->_itemname;
		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
			if ($criteria->getSort() !== '') {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		} else {
			$args = [];
		}

		if ($debug) {
			Debug::message($sql);
		}

		if (!empty($limit)) {
			$sql .= ' LIMIT ' . ((int)$start) . ', ' . ((int)$limit);
		}

		$result = $this->db->perform($sql, $args);
		if (!$result) {
			return $ret;
		}

		while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
			//identifiers should be textboxes, so sanitize them like that
			$ret[$myrow[$keyName]] = empty($keyValue) ? 1 : htmlentities($myrow[$keyValue]);
		}

		return $ret;
	}

	/**
	 *
	 * @param bool $withprefix
	 * @return string
	 */
	public function getIdentifierName($withprefix = true)
	{
		if ($withprefix) {
			return $this->_itemname . '.' . $this->identifierName;
		} else {
			return $this->identifierName;
		}
	}

	/**
	 *
	 * @deprecated Use save() instead. Since 2.0
	 *
	 * @param    object $obj
	 * @param    bool $force
	 * @param    bool $checkObject
	 * @param    bool $debug
	 *
	 * @return bool
	 */
	public function insertD(&$obj, $force = false, $checkObject = true, $debug = false)
	{
		trigger_error('Use save() instead', E_USER_DEPRECATED);

		return $this->save($obj, $force);
	}

	/**
	 * Saves one or many items
	 *
	 * @param array /object $data    What to save?
	 * @param bool $force Force saving?
	 *
	 * @return bool
	 */
	public function save($obj_instances, $force = false)
	{
		if (is_array($obj_instances)) {
			$data = &$obj_instances;
		} else {
			$data = [&$obj_instances];
		}
		$scount = 0;
		$for_insert = [];
		$for_update = [];
		foreach ($data as $i => $obj) {
			if ($obj->handler->className !== $this->className) {
				$obj->setErrors(get_class($obj) . ' Differs from ' . $this->className);
				continue;
			}
			if (!$obj->isChanged()) {
				$obj->setErrors('Not changed'); //will usually not be outputted as errors are not displayed when the method returns true, but it can be helpful when troubleshooting code - Mith
				$scount++;
				continue;
			}
			if ($obj->seoEnabled) {
				$obj->updateMetas();
			}
			if ($this->executeEvent('beforeSave', $data[$i]) === false) {
				continue;
			}
			if ($obj->isNew()) {
				if ($this->executeEvent('beforeInsert', $data[$i]) === false) {
					continue;
				}

				$for_insert[] = &$data[$i];
			} else {
				if ($this->executeEvent('beforeUpdate', $data[$i]) === false) {
					continue;
				}

				$for_update[] = &$data[$i];
			}
		}

		if (($count = count($for_insert)) > 0) {

			if ($count > 1) {

				//$this->db->query('LOCK TABLES ' . $this->table . ' WRITE;');

				$this->db->beginTransaction();
				$result = $this->doInsert($for_insert[0]);
				if ($result) {
					$insert_id = $this->db->lastInsertId();
					$id = $insert_id;
					$for_insert[0]->setVar($this->keyName, $id++);
					$for_insert[0]->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);

					$for_insert = array_slice($for_insert, 1);
					$result = $this->doInsert($for_insert);
					if ($result) {
						foreach ($for_insert as $i => $obj) {
							$for_insert[$i]->setVar($this->keyName, $id++);
							$for_insert[$i]->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
							$for_insert[$i]->unsetNew();
							if (!$this->executeEvent('afterInsert', $for_insert[$i])) {
								$scount--;
								continue;
							}
						}
						$scount += $result->rowCount();
					}
				}
				$this->db->commit();

				//$this->db->query('UNLOCK TABLES;');
			} else {
				$this->doInsert($for_insert);

				$id = $this->db->lastInsertId();
				$for_insert[0]->setVar($this->keyName, $id);
				$for_insert[0]->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
				$for_insert[0]->unsetNew();
				$scount = (int)$this->executeEvent('afterInsert', $for_insert[0]);
			}
		}

		if (($count = count($for_update)) > 0) {
			$result = $this->doUpdate(($count === 1) ? $for_update[0] : $for_update);
			if ($result !== false) {

				$scount += $result->rowCount();

				foreach ($for_update as $i => $obj) {
					$for_update[$i]->setVarInfo(null, AbstractProperties::VARCFG_CHANGED, false);
					if (!$this->executeEvent('afterUpdate', $for_update[$i])) {
						$scount--;
						continue;
					}
				}
			}
		}

		foreach ($data as $i => $obj) {
			$this->executeEvent('afterSave', $data[$i]);
		}

		return $scount > 0;
	}

	/**
	 * Execute the function associated with an event
	 * This method will check if the function is available
	 *
	 * @param string $event name of the event
	 * @param object $executeEventObj $object on which is performed the event
	 * @return mixed result of the execution of the function or FALSE if the function was not executed
	 */
	public function executeEvent($event, &$executeEventObj)
	{
		if (!in_array($event, $this->_disabledEvents, true)) {
			if (method_exists($this, $event)) {
				$ret = $this->$event($executeEventObj);
				if (!$ret) {
					$executeEventObj->setErrors('An error occured during the ' . $event . ' event');
				}
			} else {
				// check to see if there is a hook for this event
				if (isset($this->_eventHooks[$event])) {
					$method = $this->_eventHooks[$event];
					// check to see if the method specified by this hook exists
					if (method_exists($this, $method)) {
						$ret = $this->$method($executeEventObj);
					}
				}
				$ret = true;
			}
			return $ret;
		}
		return true;
	}

	/**
	 * Generate insert SQL and bind params by data
	 *
	 * @param array/object $data
	 *
	 * @return PDOStatement
	 */
	protected function doInsert($data)
	{
		if (!is_array($data)) {
			$data = array($data);
		}
		$sql = 'INSERT INTO `' . $this->table . '` (`';

		$params = [];
		foreach ($data as $i => $obj) {
			$fieldsToStoreInDB = $obj->getVarsForQuery(false);
			if ($i === 0) {
				$sql .= implode('`,`', array_keys($fieldsToStoreInDB)) . '`) VALUES';
			} else {
				$sql .= ', ';
			}
			$bindKeys = [];
			foreach ($fieldsToStoreInDB as $k => $v) {
				$params[$k] = $v;
				$bindKeys[] = ':' . $k;
			}
			$sql .= '(' . implode(',', $bindKeys) . ')';
		}
		return $this->db->perform($sql, $params);
	}

	/**
	 * Runs update command
	 *
	 * @param array/object $data    Objects to process
	 *
	 * @return PDOStatement|null|false
	 */
	protected function doUpdate($data)
	{
		$params = [];
		$bindFieldName = 'field_';
		$bindFieldSuffixId = 0;
		if (is_array($data)) {
			$sql = 'UPDATE `' . $this->table . '` SET ' . "\r\n";
			if (is_array($this->keyName)) {
				$case = '  CASE `' . implode('`, `', $this->keyName) . "`\r\n";
				$when = array();
				$criteria = new icms_db_criteria_Compo();
				foreach ($data as $i => $obj) {
					$fieldsToStoreInDB = $obj->getVarsForQuery(true);
					/**
					 * @var icms_db_criteria_Element $cr
					 */
					$cr = $obj->getCriteriaForSelectByID();
					$criteria->add($cr, 'OR');
					$rendered_criteria = $cr->render(true);
					$params = $cr->getBindData();
					foreach ($fieldsToStoreInDB as $key => $value) {
						if (in_array($key, $this->keyName, true)) {
							continue;
						}
						$when[$key][$i] = '    WHEN ' . $rendered_criteria . ' THEN ' . $value;
					}
				}
				if (empty($when)) {
					return null;
				}
				$first = true;
				foreach (array_keys($when) as $wdata) {
					if (!$first) {
						$sql .= ', ';
					} else {
						$first = false;
					}
					$sql .= '`' . $wdata . '` = ' . $case . implode("\r", $when[$wdata]) . ' END ' . "\r";
				}
				$sql .= ' ' . $criteria->renderWhere(true);
				$params = array_merge($params, $criteria->getBindData());
			} else {
				$case = '  CASE `' . $this->keyName . "` \r\n";
				$when = array();
				$ids = array();
				foreach ($data as $i => $obj) {
					$fieldsToStoreInDB = $obj->getVarsForQuery(true);
					$id = $obj->id();
					foreach ($fieldsToStoreInDB as $key => $value) {
						if ($key === $this->keyName) {
							continue;
						}
						$when[$key][$i] = '    WHEN ' . $id . ' THEN :' . $bindFieldName . (++$bindFieldSuffixId);
						$params[$bindFieldName . $bindFieldSuffixId] = $value;
					}
					$ids[] = $id;
				}
				if (empty($when)) {
					return null;
				}
				$first = true;
				foreach (array_keys($when) as $wdata) {
					if (!$first) {
						$sql .= ', ';
					} else {
						$first = false;
					}
					$sql .= '`' . $wdata . '` = ' . $case . implode("\r", $when[$wdata]) . ' END ' . "\r";
				}
				$criteria = new icms_db_criteria_Item($this->keyName, $ids, 'IN');
				$sql .= $criteria->renderWhere(true);
				$params = array_merge($params, $criteria->getBindData());
			}
		} else {
			$fieldsToStoreInDB = $data->getVarsForQuery(true);
			if (empty($fieldsToStoreInDB)) {
				return null;
			}

			$sql = 'UPDATE `' . $this->table . '` SET';
			foreach ($fieldsToStoreInDB as $key => $value) {
				if ((!is_array($this->keyName) && $key === $this->keyName)
					|| (is_array($this->keyName) && in_array($key, $this->keyName, true))) {
					continue;
				}
				if (isset($notfirst)) {
					$sql .= ',';
				}
				$sql .= ' `' . $key . '` = :' . $bindFieldName . (++$bindFieldSuffixId);
				$params[$bindFieldName . $bindFieldSuffixId] = $value;
				$notfirst = true;
			}
			$criteria = $data->getCriteriaForSelectByID();
			$sql .= ' ' . $criteria->renderWhere(true);
			$params = array_merge($params, $criteria->getBindData());
		}
		if (!$sql) {
			return false;
		}
		return $this->db->perform($sql, $params);
	}

	/**
	 * insert a new object in the database
	 *
	 * @param object $obj reference to the object
	 * @param bool $force whether to force the query execution despite security settings
	 * @param bool $checkObject check if the object is dirty and clean the attributes
	 * @return bool FALSE if failed, TRUE if already present and unchanged or successful
	 * @deprecated Use save() instead. Since 2.0
	 *
	 */
	public function insert(&$obj, $force = false, $checkObject = true, $debug = false)
	{
		return $this->save(array(&$obj), $force);
	}

	/**
	 *
	 * @param array $arrayObjects
	 * @return array|bool
	 */
	public function getObjectsAsArray($arrayObjects)
	{
		$ret = array();
		foreach ($arrayObjects as $key => $object) {
			$ret[$key] = $object->toArray();
		}
		return count($ret) > 0 ? $ret : false;
	}

	/**
	 * Execute fast change with data
	 *
	 * @param mixed $id
	 * @param string $field
	 * @param int|float $value
	 * @param string $math_func
	 * @param bool $force
	 * @param bool $debug
	 * @return array
	 */
	public function doFastChange($id, $field, $value = 1, $math_func = '+', $force = false, $debug = false)
	{
		return $this->query('UPDATE `' . $this->keyName . '` SET `' . $field . '` = `' . $field . '` ' . $math_func . ' ' . $value, null, $force, $debug);
	}

	/**
	 * query the database with the constructed $criteria object
	 *
	 * @param string $sql The SQL Query
	 * @param \icms_db_criteria_Element $criteria Criteria conditions to be met
	 * @param bool $force Force the query? (DEPRECATED: Do not use this - does nothing)
	 * @param bool $debug Turn Debug on? (DEPRECATED: Do not use this)
	 *
	 * @return array
	 */
	public function query($sql, $criteria, $force = false, $debug = false)
	{
		$ret = array();

		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
			if ($criteria->groupby) {
				$sql .= $criteria->getGroupby();
			}
			if ($criteria->getSort() !== '') {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
		} else {
			$args = [];
		}
		if ($debug) {
			Debug::message($sql);
		}

		$result = $this->db->perform($sql, $args);

		if (!$result) {
			return $ret;
		}

		return $result->fetchAll(PDO::FETCH_NUM);
	}

	/**
	 * count objects matching a condition
	 *
	 * @param CriteriaElement $criteria Criteria to match
	 * @return int count of objects
	 */
	public function getCount($criteria = null)
	{
		$field = '';
		$groupby = false;
		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			if ($criteria->groupby !== '') {
				$groupby = true;
				$field = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
			}
		}
		/**
		 * if we have a generalSQL, lets used this one.
		 * This needs to be improved...
		 */
		if ($this->generalSQL) {
			$sql = $this->generalSQL;
			$sql = str_replace('SELECT *', 'SELECT COUNT(*)', $sql);
		} else {
			$sql = 'SELECT ' . $field . 'COUNT(*) FROM ' . $this->table . ' AS ' . $this->_itemname;
		}
		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
			if ($criteria->groupby !== '') {
				$sql .= $criteria->getGroupby();
			}
		} else {
			$args = [];
		}

		$result = $this->db->perform($sql, $args);
		if (!$result) {
			return 0;
		}
		if ($groupby === false) {
			list($ret) = $result->fetch(PDO::FETCH_NUM);
		} else {
			$ret = array();
			while (list($id, $count) = $result->fetch(PDO::FETCH_NUM)) {
				$ret[$id] = (int)$count;
			}
		}

		return $ret;
	}

	/**
	 *
	 * @param string[]|string $event
	 */
	public function disableEvent($event)
	{
		if (is_array($event)) {
			foreach ($event as $v) {
				$this->_disabledEvents[] = $v;
			}
		} else {
			$this->_disabledEvents[] = $event;
		}
	}

	/**
	 * Build an array containing all the ids of an array of objects as array
	 *
	 * @param AbstractExtendedModel[] $objectsAsArray array of \ImpressCMS\Core\IPF\AbstractModel
	 * @return array
	 */
	public function getIdsFromObjectsAsArray($objectsAsArray)
	{
		$ret = array();
		foreach ($objectsAsArray as $array) {
			$ret[] = $array[$this->keyName];
		}
		return $ret;
	}

	/**
	 * Change a value for objects with a certain criteria
	 *
	 * @param   string  $fieldname  Name of the field
	 * @param   string  $fieldvalue Value to write
	 * @param CriteriaElement $criteria Criteria
	 *
	 * @return  bool
	 * */
	public function updateAll($fieldname, $fieldvalue, $criteria = null)
	{
		$sql = 'UPDATE `' . $this->table . '` SET `' . $fieldname . '`=:fieldvalue';
		if (isset($criteria) && $criteria instanceof \icms_db_criteria_Element) {
			$sql .= ' ' . $criteria->renderWhere(true);
			$args = $criteria->getBindData();
		} else {
			$args = [];
		}
		if (is_array($fieldvalue)) {
			$args['fieldvalue'] = implode(',', $fieldvalue);
		} else {
			$args['fieldvalue'] = $fieldvalue;
		}
		$result = $this->db->perform($sql, $args);
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	 * delete all objects meeting the conditions
	 *
	 * @param CriteriaElement $criteria Criteria with conditions to meet
	 * @param bool $quick Do not load object on deletion?
	 *
	 * @return bool
	 */
	public function deleteAll($criteria = null, $quick = false)
	{
		if (!$criteria) {
			return false;
		}
		if ($quick) {
			$sql = 'DELETE FROM `' . $this->table . '` ';
			if ($criteria) {
				$sql .= $criteria->renderWhere(true);
				$args = $criteria->getBindData();
			} else {
				$args = [];
			}
			$rows = $this->db->perform($sql, $args)->rowCount();
		} else {
			$rows = 0;
			$objects = $this->getObjects($criteria);
			foreach ($objects as $obj) {
				if ($this->delete($obj, true)) {
					$rows++;
				}
			}
		}
		return $rows > 0 ? $rows : true;
	}

	/**
	 * delete an object from the database
	 *
	 * @param object $obj reference to the object to delete
	 *
	 * @return bool FALSE if failed.
	 */
	public function delete(&$obj)
	{
		if (!$this->executeEvent('beforeDelete', $obj)) {
			return false;
		}

		/**
		 * @var $criteria icms_db_criteria_Element
		 */
		$criteria = $obj->getCriteriaForSelectByID();
		$sql = 'DELETE FROM `' . $this->table . '` ' . $criteria->renderWhere(true);
		$result = $this->db->perform($sql, $criteria->getBindData());
		if (!$result) {
			return false;
		}

		$url_links = array();
		$url_files = array();
		foreach ($obj->getVars() as $key => $var) {
			if (isset($var[AbstractProperties::VARCFG_DEP_DATA_TYPE])) {
				if ($var[AbstractProperties::VARCFG_DEP_DATA_TYPE] === AbstractProperties::DTYPE_DEP_URLLINK) {
					$url_links[] = $obj->getVar($key, 'n');
				} elseif ($var[AbstractProperties::VARCFG_DEP_DATA_TYPE] === AbstractProperties::DTYPE_DEP_FILE) {
					$url_files[] = $obj->getVar($key, 'n');
				}
			}
		}
		if (!empty($url_links)) {
			$urllink_handler = icms::handler('icms_data_urllink');
			$urllink_handler->deleteAll(new icms_db_criteria_Item($urllink_handler->keyName, $url_links, ' IN '));
			unset($urllink_handler);
		}
		if (!empty($url_files)) {
			$urllink_handler = icms::handler('icms_data_file');
			$urllink_handler->deleteAll(new icms_db_criteria_Item($urllink_handler->keyName, $url_files, ' IN '));
			unset($urllink_handler);
		}

		$this->deleteGrantedPermissions($obj);

		return $this->executeEvent('afterDelete', $obj);
	}

	/**
	 * delete granted permssions for an object
	 *
	 * @param object $obj optional
	 * @return    bool    TRUE
	 */
	private function deleteGrantedPermissions($obj = null) {
		/**
		 * @var GroupPermHandler $gperm_handler
		 */
		$gperm_handler = icms::handler('icms_member_groupperm');
		/**
		 * @var Module $module
		 */
		$module = icms::handler('icms_module')->getByDirname($this->_moduleName);
		$permissions = $this->getPermissions();
		if ($permissions === false) {
			return true;
		}
		foreach ($permissions as $permission) {
			$gperm_handler->deleteByModule(
				$module->mid,
				$permission['perm_name'],
				$obj === null ? null : $obj->id()
			);
		}
		return true;
	}

	/**
	 * Accessor for the permissions array property
	 */
	public function getPermissions()
	{
		return $this->permissionsArray;
	}

	/**
	 *
	 */
	public function getModuleInfo()
	{
		return icms_getModuleInfo($this->_moduleName);
	}

	/**
	 *
	 */
	public function getModuleConfig()
	{
		return icms_getModuleConfig($this->_moduleName);
	}

	/**
	 *
	 */
	public function getModuleItemString() {
		return $this->_moduleName . '_' . $this->_itemname;
	}

	/**
	 *
	 * @param $object
	 */
	public function updateCounter($object)
	{
		if (isset($object->counter)) {
			$this->db->perform(
				sprintf('UPDATE `%s` SET counter=:counter WHERE `%s`=:id', $this->table, $this->keyName),
				[
					'counter' => $object->counter + 1,
					'id' => $object->id()
				]
			);
		}
	}

	/**
	 * Enabled upload for item
	 *
	 * @param bool|array $allowedMimeTypes Allowed mime types array
	 * @param bool|int $maxFileSize Max file size
	 * @param bool|int $maxWidth Max width for image
	 * @param bool|int $maxHeight Max height for image
	 */
	public function enableUpload($allowedMimeTypes = false, $maxFileSize = false, $maxWidth = false, $maxHeight = false)
	{
		$this->uploadEnabled = true;
		$this->_allowedMimeTypes = $allowedMimeTypes?:$this->_allowedMimeTypes;
		$this->_maxFileSize = $maxFileSize?:$this->_maxFileSize;
		$this->_maxWidth = $maxWidth?:$this->_maxWidth;
		$this->_maxHeight = $maxHeight?:$this->_maxHeight;
	}

}

