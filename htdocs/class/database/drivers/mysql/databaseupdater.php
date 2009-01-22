<?php
/**
 * Contains the classes for updating database tables
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: databaseupdater.php 5669 2008-10-14 19:53:37Z pesian_stranger $
 * @link http://www.smartfactory.ca The SmartFactory
 * @package SmartObject
 */

if (!defined("XOOPS_ROOT_PATH")) {
	die("ImpressCMS root path not defined");
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