<?php

/**
* $Id$
* Module: SmartContent
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

define("_DATABASEUPDATER_IMPORT", "Import");
define("_DATABASEUPDATER_CURRENTVER", "Current version: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Database Version %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Data added in table %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Error adding data in table %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Changing field %s in table %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Error changing field %s in table %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Table %s created");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Error creating table %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Successfully added field %s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Error adding field %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Your database is out-of-date. Please upgrade your database tables!<br><b>Note : The SmartFactory strongly recommends you to backup all SmartSection tables before running this upgrade script.</b><br>");
define("_DATABASEUPDATER_NOUPDATE", "Your database is up-to-date. No updates are necessary.");
define("_DATABASEUPDATER_UPDATE_DB", "Updating Database");
define("_DATABASEUPDATER_UPDATE_ERR", "Errors updating to version %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Update Now!");
define("_DATABASEUPDATER_UPDATE_OK", "Successfully updated to version %s");
define("_DATABASEUPDATER_UPDATE_TO", "Updating to version %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Updating database...");
define("_DATABASEUPDATER_MSG_DROPFIELD", "Succesffuly droped field %s");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Records of table %s were successfully updated");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "An error occured while updating records in table %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Specified records of table %s were successfully deleted");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "An error occured while deleting specified records in table %s");
?>