<?php
/**
 * Contains the classes for updating database tables
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id$
 * @link http://www.smartfactory.ca The SmartFactory
 * @package SmartObject
 */

if (!defined("ICMS_ROOT_PATH")) {
	die("ImpressCMS root path not defined");
}

/**
 * base class
 */
include_once ICMS_ROOT_PATH."/class/database/databaseupdater.php";

class IcmsMysqliDatabasetable extends icms_db_icms_updater_Table {

}

class IcmsMysqliDatabaseupdater extends icms_db_icms_updater_Handler {

}
?>