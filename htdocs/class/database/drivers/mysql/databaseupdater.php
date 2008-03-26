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

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

/**
 * base class
 */
include_once XOOPS_ROOT_PATH."/class/database/databaseupdater.php";

class IcmsMysqlDatabasetable extends IcmsDatabasetable {
	
}

class IcmsMysqlDatabaseupdater extends IcmsDatabaseupdater {

}
?>