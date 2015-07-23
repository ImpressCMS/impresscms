<?php

/**
 * Contains the classes for updating database tables
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Database
 *
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: Handler.php 12310 2013-09-13 21:33:58Z skenow $
 * @link http://www.smartfactory.ca The SmartFactory
 */
/**
 * icms_db_legacy_updater_Table class
 *
 * Information about an individual table
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	Updater
 *
 * @author marcan <marcan@smartfactory.ca>
 * @link http://www.smartfactory.ca The SmartFactory
 */
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Include the language constants for the icms_db_legacy_updater_Handler
 */
global $icmsConfigPersona;
icms_loadLanguageFile('core', 'databaseupdater');

/**
 * icms_db_legacy_updater_Handler class
 *
 * Class performing the database update for the module
 *
 * @package SmartObject
 * @author marcan <marcan@smartfactory.ca>
 * @link http://www.smartfactory.ca The SmartFactory
 */
class icms_db_legacy_updater_Handler {

    /**
     * xoopsDB database object
     *
     * @var @link XoopsDatabase object
     */
    var $_db;
    var $db;

    /**
     *
     * @var array of messages
     */
    var $_messages = array();

    function __construct() {
        // backward compat
        $this->_db = icms::$xoopsDB;
        $this->db = icms::$xoopsDB;
    }

    /**
     * Use to execute a general query
     *
     * @param string $query query that will be executed
     * @param string $goodmsg message displayed on success
     * @param string $badmsg message displayed on error
     * @param bool 	$force force the query even in a GET process
     *
     * @return bool true if success, false if an error occured
     *
     */
    function runQuery($query, $goodmsg, $badmsg, $force = false) {
        if ($force) {
            $ret = $this->_db->queryF($query);
        } else {
            $ret = $this->_db->query($query);
        }

        if (!$ret) {
            $this->_messages[] = "&nbsp;&nbsp;$badmsg";
            return false;
        } else {
            $this->_messages[] = "&nbsp;&nbsp;$goodmsg";
            return true;
        }
    }

    /**
     * Use to rename a table
     *
     * @param string $from name of the table to rename
     * @param string $to new name of the renamed table
     * @param bool 	$force force the query even in a GET process
     *
     * @return bool true if success, false if an error occured
     */
    function renameTable($from, $to, $force = false) {
        $from = $this->_db->prefix($from);
        $to = $this->_db->prefix($to);
        $query = sprintf("ALTER TABLE %s RENAME %s", $from, $to);
        if ($force) {
            $ret = $this->_db->queryF($query);
        } else {
            $ret = $this->_db->query($query);
        }
        if (!$ret) {
            $this->_messages[] = "&nbsp;&nbsp;" . sprintf(_DATABASEUPDATER_MSG_RENAME_TABLE_ERR, $from);
            return false;
        } else {
            $this->_messages[] = "&nbsp;&nbsp;" . sprintf(_DATABASEUPDATER_MSG_RENAME_TABLE, $from, $to);
            return true;
        }
    }

    /**
     * Use to update a table
     *
     * @param object $table {@link icms_db_legacy_updater_Table} that will be updated
     * @param bool	$force force the query even in a GET process
     *
     * @see icms_db_legacy_updater_Table
     *
     * @return bool true if success, false if an error occured
     */
    function updateTable($table, $force = false) {
        $ret = true;
        $table->force = $force;

        // If table has a structure, create the table
        if ($table->getStructure()) {
            $ret = $table->createTable() && $ret;
        }
        // If table is flag for drop, drop it
        if ($table->_flagForDrop) {
            $ret = $table->dropTable() && $ret;
        }
        // If table has data, insert it
        if ($table->getData()) {
            $ret = $table->addData() && $ret;
        }
        // If table has new fields to be added, add them
        if ($table->getNewFields()) {
            $ret = $table->addNewFields() && $ret;
        }
        // If table has altered field, alter the table
        if ($table->getAlteredFields()) {
            $ret = $table->alterTable() && $ret;
        }
        // If table has droped field, alter the table
        if ($table->getDropedFields()) {
            $ret = $table->dropFields() && $ret;
        }
        // If table has updateAll items, update the table
        if ($table->getUpdateAll()) {
            $ret = $table->updateAll() && $ret;
        }
        return $ret;
    }

    /**
     * Upgrade automaticaly an item of a module
     *
     * Note that currently, $item needs to represent the name of an object derived
     * from SmartObject, for example, $item == 'invoice' wich will represent $dirnameInvoice
     * for example SmartbillingInvoice which extends SmartObject class
     *
     * @param string $dirname dirname of the module
     * @param mixed $item name or array of names of the item to upgrade
     */
    function automaticUpgrade($dirname, $item) {
        if (is_array($item)) {
            foreach ($item as $v) {
                $this->upgradeObjectItem($dirname, $v);
            }
        } else {
            $this->upgradeObjectItem($dirname, $item);
        }
    }

    /**
     * Get the type of the field based on the info of the var
     *
     * @param array $var array containing information about the var
     * @return string type of the field
     */
    function getFieldTypeFromVar($var) {
        switch ($var[icms_properties_Handler::VARCFG_TYPE]) {
            case icms_properties_Handler::DTYPE_BOOLEAN:
                return 'TINYINT(1) UNSIGNED';
                break;
            case icms_properties_Handler::DTYPE_ARRAY:
                return 'TEXT';
                break;
            case icms_properties_Handler::DTYPE_OBJECT:
                return 'MEDIUMTEXT';
                break;
            case icms_properties_Handler::DTYPE_LIST:
                return 'VARCHAR(100)';
                break;
            case icms_properties_Handler::DTYPE_DATETIME:
                return 'DATETIME';
                break;
            case icms_properties_Handler::DTYPE_FILE:
                return 'BLOB';
                break;
            case icms_properties_Handler::DTYPE_FLOAT:
                if (isset($var[icms_properties_Handler::VARCFG_MAX_LENGTH]) && ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] > 0)) {
                    return 'FLOAT(' . icms_properties_Handler::VARCFG_MAX_LENGTH . ')';
                } else {
                    return 'FLOAT';
                }
                break;
            case icms_properties_Handler::DTYPE_INTEGER:
                if (isset($var[icms_properties_Handler::VARCFG_MAX_LENGTH]) && ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] > 0)) {
                    if ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 4) {
                        return 'TINYINT';
                    } elseif ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 5) {
                        return 'SMALLINT';
                    } elseif ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 7) {
                        return 'MEDIUMINT';
                    } elseif ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 11) {
                        return 'INT';
                    } else {
                        return 'BIGINT';
                    }
                } else {
                    return 'INT';
                }
                break;
            case icms_properties_Handler::DTYPE_LIST:
                return 'TEXT';
                break;
            case icms_properties_Handler::DTYPE_STRING:
                if (isset($var[icms_properties_Handler::VARCFG_MAX_LENGTH])) {
                    if ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 500) {
                        return 'VARCHAR(' . $var[icms_properties_Handler::VARCFG_MAX_LENGTH] . ')';
                    } elseif ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 8000) {
                        return 'TEXT';
                    } elseif ($var[icms_properties_Handler::VARCFG_MAX_LENGTH] < 2097000) {
                        return 'MEDIUMTEXT';
                    } else {
                        return 'LONGTEXT';
                    }
                } else {
                    return 'VARCHAR(255)';
                }
                break;
            default:
                return 'TEXT';
        }
    }

    /**
     * Get the default value based on the info of the var
     *
     * @param array $var array containing information about the var
     * @param bool $key TRUE if the var is the primary key
     * @return string default value
     */
    function getFieldDefaultFromVar($var, $key = false) {
        if (in_array($var[icms_properties_Handler::VARCFG_TYPE], array(
                    icms_properties_Handler::DTYPE_DATETIME,
                    icms_properties_Handler::DTYPE_OBJECT,
                    icms_properties_Handler::DTYPE_LIST,
                    icms_properties_Handler::DTYPE_ARRAY
                )))
            return null;
        elseif (isset($var[icms_properties_Handler::VARCFG_DEFAULT_VALUE]))
            return $var[icms_properties_Handler::VARCFG_DEFAULT_VALUE];
        else
            return null;
    }

    /**
     * Remove table or some rows if table is used for other object
     *
     * @param string $dirname
     * @param string $item
     * @param array $reservedTables
     *
     * @return boolean
     */
    function uninstallObjectItem($dirname, $item, $reservedTables = array()) {
        $module_handler = icms_getModuleHandler($item, $dirname);
        if (!$module_handler) {
            return false;
        }

        $object = $module_handler->create();
        $class = new ReflectionClass($object);
        $isExtention = false;
        if ($pclass = $class->getParentClass()) {
            if ($pclass->isInstantiable() && !in_array($pclass->getName(), array('icms_ipf_Object', 'icms_core_Object'))) {
                $pclass_instance = $pclass->newInstanceArgs(array(&$module_handler));
                $parentObjectVars = $pclass_instance->getVars();
                unset($pclass_instance);
                $isExtention = $pclass->getName();
            }
        }
        unset($class, $pclass);

        if (isset($parentObjectVars)) {
            $objectVars = $object->getVars();
            $sql = '';
            $table = new icms_db_legacy_updater_Table(str_replace(XOOPS_DB_PREFIX . '_', '', $module_handler->table));
            foreach (array_keys($objectVars) as $var) {
                if (!isset($parentObjectVars[$var]))
                    $table->addDropedField($var);
            }
            $ret = $table->dropFields();
        } else {
            if (in_array($module_handler->table, $reservedTables))
                return false;
            $table = new icms_db_legacy_updater_Table(str_replace(XOOPS_DB_PREFIX . '_', '', $module_handler->table));
            $ret = $table->dropTable();
        }
        $this->_messages = array_merge($this->_messages, $table->_messages);
        return $ret;
    }

    /*
     * Upgrades the object
     * @param string $dirname
     */

    function upgradeObjectItem($dirname, $item) {
        $module_handler = icms_getModuleHandler($item, $dirname);
        if (!$module_handler) {
            return false;
        }

        $table = new icms_db_legacy_updater_Table(str_replace(XOOPS_DB_PREFIX . '_', '', $module_handler->table));        
        $object = $module_handler->create();
        $class = new ReflectionClass($object);        
        $isExtention = false;
        if ($pclass = $class->getParentClass()) {
            if ($pclass->isInstantiable() && !in_array($pclass->getName(), array('icms_ipf_Object', 'icms_core_Object', 'icms_ipf_seo_Object'))) {
                $pclass_instance = $pclass->newInstanceArgs(array(&$module_handler));
                $parentObjectVars = $pclass_instance->getVars();
                unset($pclass_instance);
                $isExtention = $pclass->getName();
            }
        }
        unset($class, $pclass);

        $objectVars = $object->getVars();
        if (isset($parentObjectVars)) {
            foreach ($parentObjectVars as $var => $info) {
                if (isset($objectVars[$var]))
                    unset($objectVars[$var]);
            }
        }

        if (!$table->exists()) {
            if ($isExtention) {
                Throw new Exception(sprintf('%s for %s module is extention for %s, but module isn\'t installed yet', $item, $dirname, $isExtention));
                return false;
            }

            // table was never created, let's do it
            $structure = "";
            foreach ($objectVars as $key => $var) {
                if (!isset($var['persistent']) || $var['persistent']) {
                    $type = $this->getFieldTypeFromVar($var);
                    if ($key == $module_handler->keyName) {
                        $extra = "auto_increment";
                    } else {
                        $default = $this->getFieldDefaultFromVar($var, $key);
                        if ($default != null) {
                            $extra = "default '$default'";
                        } else {
                            $extra = false;
                        }
                    }
                    if ($extra) {
                        $structure .= "`$key` $type not null $extra,";
                    } else {
                        $structure .= "`$key` $type not null,";
                    }
                }
            }
            $ModKeyNames = $module_handler->keyName;
            $structure .= "PRIMARY KEY  (";
            if (is_array($ModKeyNames)) {
                $structure .= "`" . $ModKeyNames[0] . "`";
                foreach ($ModKeyNames as $ModKeyName) {
                    $structure .= ($ModKeyName != $ModKeyNames[0]) ? ", `" . $ModKeyName . "`" : "";
                }
            } else {
                $structure .= "`" . $ModKeyNames . "`";
            }
            $structure .= ")";
            $table->setStructure($structure);
            if (!$this->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
            foreach ($table->_messages as $msg) {
                $this->_messages[] = $msg;
            }
        } else {
            $existingFieldsArray = $table->getExistingFieldsArray();
            foreach ($objectVars as $key => $var) {
                if (!isset($var['persistent']) || $var['persistent']) {
                    if (!isset($existingFieldsArray[$key])) {
                        // the fiels does not exist, let's create it
                        $type = $this->getFieldTypeFromVar($var);
                        $default = $this->getFieldDefaultFromVar($var);
                        if ($default != null) {
                            $extra = "default '$default'";
                        } else {
                            $extra = false;
                        }
                        $table->addNewField($key, "$type not null " . $extra);
                    } else {
                        // if field already exists, let's check if the definition is correct
                        $definition = strtolower($existingFieldsArray[$key]);
                        $type = $this->getFieldTypeFromVar($var);

                        if ($key == $module_handler->keyName) {
                            $extra = "auto_increment";
                        } else {
                            $default = $this->getFieldDefaultFromVar($var, $key);
                            if ($default != null) {
                                $extra = "default '$default'";
                            } else {
                                $extra = false;
                            }
                        }
                        $actual_definition = "$type not null";
                        if ($extra) {
                            $actual_definition .= " $extra";
                        }
                        if ($definition != $actual_definition) {
                            $table->addAlteredField($key, $actual_definition);
                        }
                    }
                }
            }

            // check to see if there are some unused fields left in the table
            foreach ($existingFieldsArray as $key => $v) {
                if ((!isset($objectVars[$key]) && !isset($parentObjectVars[$key])) || !(!isset($objectVars[$key]['persistent']) || $objectVars[$key]['persistent'])) {
                    $table->addDropedField($key);
                }
            }

            if (!$this->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
    }

    /**
     * Insert a config in System Preferences
     *
     * @param int $conf_catid
     * @param string $conf_name
     * @param string $conf_title
     * @param mixed $conf_value
     * @param string $conf_desc
     * @param string $conf_formtype
     * @param string $conf_valuetype
     * @param int $conf_order
     */
    function insertConfig($conf_catid, $conf_name, $conf_title, $conf_value, $conf_desc, $conf_formtype, $conf_valuetype, $conf_order) {
        global $dbVersion;
        $configitem_handler = icms::handler('icms_config_item');
        $configitemObj = $configitem_handler->create();
        $configitemObj->setVar('conf_modid', 0);
        $configitemObj->setVar('conf_catid', $conf_catid);
        $configitemObj->setVar('conf_name', $conf_name);
        $configitemObj->setVar('conf_title', $conf_title);
        $configitemObj->setVar('conf_value', $conf_value);
        $configitemObj->setVar('conf_desc', $conf_desc);
        $configitemObj->setVar('conf_formtype', $conf_formtype);
        $configitemObj->setVar('conf_valuetype', $conf_valuetype);
        $configitemObj->setVar('conf_order', $conf_order);
        if (!$configitem_handler->insert($configitemObj)) {
            $querry_answer = sprintf(_DATABASEUPDATER_MSG_CONFIG_ERR, $conf_title);
        } else {
            $querry_answer = sprintf(_DATABASEUPDATER_MSG_CONFIG_SCC, $conf_title);
        }
        $this->_messages[] = $querry_answer;
    }

    /*
     * Module Upgrade
     * @param object reference to Module Object
     * @return bool whether upgrade succeeded or not
     */

    function moduleUpgrade(&$module, $tables_first = false) {
        $dirname = $module->getVar('dirname');

        //		ob_start();

        $dbVersion = $module->getDbversion();

        $newDbVersion = constant(strtoupper($dirname . '_db_version')) ? constant(strtoupper($dirname . '_db_version')) : 0;
        $textcurrentversion = sprintf(_DATABASEUPDATER_CURRENTVER, $dbVersion);
        $textlatestversion = sprintf(_DATABASEUPDATER_LATESTVER, $newDbVersion);
        $this->_messages[] = $textcurrentversion;
        $this->_messages[] = $textlatestversion;
        if (!$tables_first) {
            if ($newDbVersion > $dbVersion) {
                for ($i = $dbVersion + 1; $i <= $newDbVersion; $i++) {
                    $upgrade_function = $dirname . '_db_upgrade_' . $i;
                    if (function_exists($upgrade_function)) {
                        $upgrade_function();
                    }
                }
            }
        }
        $this->_messages[] = _DATABASEUPDATER_UPDATE_UPDATING_DATABASE;

        // if there is a function to execute for this DB version, let's do it
        //$function_

        $this->automaticUpgrade($dirname, $module->modinfo['object_items']);
        /*
          if (method_exists($module, "setMessage")) {
          $module->setMessage($this->_messages);
          } else {
          foreach($this->_messages as $feedback){
          echo $feedback;
          }
          }
         */
        if ($tables_first) {
            if ($newDbVersion > $dbVersion) {
                for ($i = $dbVersion + 1; $i <= $newDbVersion; $i++) {
                    $upgrade_function = $dirname . '_db_upgrade_' . $i;
                    if (function_exists($upgrade_function)) {
                        $upgrade_function();
                    }
                }
            }
        }

        $this->updateModuleDBVersion($newDbVersion, $dirname);
        return true;
    }

    /**
     * Update the DBVersion of a module
     *
     * @param int $newDVersion new database version
     * @param string $dirname dirname of the module
     *
     * @return bool TRUE if success FALSE if not
     */
    function updateModuleDBVersion($newDBVersion, $dirname) {
        if (!$dirname) {
            $dirname = icms_getCurrentModuleName();
        }
        $module_handler = icms::handler('icms_module');
        $module = $module_handler->getByDirname($dirname);
        $module->setVar('dbversion', $newDBVersion);

        if (!$module_handler->insert($module)) {
            $module->setErrors(_DATABASEUPDATER_MSG_DB_VERSION_ERR);
            return false;
        }
        return true;
    }

}

?>