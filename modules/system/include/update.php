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
 * DataBase Update Functions
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		1.0
 * @author		malanciault <marcan@impresscms.org)
 */

// this needs to be the latest db version.
define('SYSTEM_DB_VERSION', ICMS_SYSTEM_DBVERSION);

icms_loadLanguageFile('core', 'databaseupdater');

/**
 * Automatic update of the system module
 *
 * @param object $module reference to the module object
 * @param int $oldversion The old version of the database
 * @param int $dbVersion The database version
 * @return mixed
 */
function icms_module_update_system(&$module, $oldversion = NULL, $dbVersion = NULL) {

	global $icmsConfig, $xoTheme;

	$from_112 = $abortUpdate = FALSE;

	$oldversion = $module->getVar('version');
	if ($oldversion < 120) {
		$result = icms::$xoopsDB->query("SELECT t1.tpl_id FROM "
				. icms::$xoopsDB->prefix('tplfile') . " t1, "
				. icms::$xoopsDB->prefix('tplfile') . " t2 WHERE t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_id > t2.tpl_id");

		$tplids = array();
		while (list($tplid) = icms::$xoopsDB->fetchRow($result)) {
			$tplids[] = $tplid;
		}

		if (count($tplids) > 0) {
			$tplfile_handler = icms::handler('icms_view_template_file');
			$duplicate_files = $tplfile_handler->getObjects(new icms_db_criteria_Item('tpl_id', "(" . implode(',', $tplids) . ")", "IN"));

			if (count($duplicate_files) > 0) {
				foreach (array_keys($duplicate_files) as $i) {
					$tplfile_handler->delete($duplicate_files[$i]);
				}
			}
		}
	}

	$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();

	ob_start();

	$dbVersion = $module->getDBVersion();
	echo sprintf(_DATABASEUPDATER_CURRENTVER, icms_conv_nr2local($dbVersion)) . '<br />';
	echo "<code>" . sprintf(_DATABASEUPDATER_UPDATE_TO, icms_conv_nr2local(ICMS_SYSTEM_DBVERSION)) . "<br />";

	/*
	 * DEVELOPER, PLEASE NOTE !!!
	 *
	 * Everytime we add a new upgrade block here, the dbversion of the System Module will get
	 * incremented. It is very important to modify the ICMS_SYSTEM_DBVERSION accordingly
	 * in htdocs/include/version.php
	 *
	 * When we start a new major release, move all the previous version's upgrade scripts to
	 * a separate file, to minimize file size and memory usage
	 */

	$CleanWritingFolders = FALSE;

	if ($dbVersion < 43) include 'update-122-to-13.php';

/*  Begin upgrade to version 2.0 */
	if (!$abortUpdate) $newDbVersion = 44;

	/*
	 * The update for this version will need to convert the system module to an IPF module
	 * and set the ipf flag to 1 in the modules table.
	 * There will be a lot of files that will need to be removed - all the legacy files and folders
	 * along with the system module changes.
	 */

	if ($dbVersion < $newDbVersion) {
		$remnants = array();
		/* all theses file deletions should not be a reason the update fails - everything will still work,
		 * there will just be some notices
		 */
		if (!icms_core_Filesystem::deleteRecursive(ICMS_ROOT_PATH . "/class/", TRUE)) $remnants[] = "/class/";
		if (!icms_core_Filesystem::deleteRecursive(ICMS_ROOT_PATH . "/kernel/", TRUE)) $remnants[] = "/kernel/";

		/* list of directories in the system/admin/ folder */
		$admin_dir = ICMS_MODULES_PATH . '/system/admin/';
		$dirlist = icms_core_Filesystem::getDirList($admin_dir);
		foreach ($dirlist as $dir) {
			/* remove xoops_version.php and main.php from these folders if the update has
		 	 * been successful.
		 	 */
			if (!icms_core_Filesystem::deleteFile($admin_dir . $dir . '/xoops_version.php')) $remnants[] = $dir . "/xoops_version.php";
			if (!icms_core_Filesystem::deleteFile($admin_dir . $dir . '/main.php')) $remnants[] = $dir . "/main.php";
			/* Remove the system/{function}/class/ subfolder, if it exists */
			if (!icms_core_Filesystem::deleteRecursive($admin_dir . $dir . "/class/", TRUE)) $remnants[] = $dir . "/class/";
			/* @todo ? copy the images folders, but don't delete them - there may be uses in content areas */

		}
		/* Remove system/xoops_version.php */
		if (!icms_core_Filesystem::deleteFile(ICMS_MODULES_PATH . "/system/xoops_version.php")) $remnants[] = "/modules/system/xoops_version.php";

		/* Remove system/admin/blocksadmin/
		 * If this fails, the system will still function, but there will be old files left to delete manually
		 */
		if (!icms_core_Filesystem::deleteRecursive($admin_dir . "blocksadmin/", TRUE)) $remnants[] = "/modules/system/admin/blocksadmin/";
		if (!icms_core_Filesystem::deleteFile(ICMS_MODULES_PATH . "/system/language/english/admin/blocksadmin.php")) $remnants[] = "/modules/system/language/english/admin/blocksadmin.php";
		// @todo deal with symlinks and help files, templates, handle multiple languages

		/* Remove system/admin/blockspadmin/ */
		if (!icms_core_Filesystem::deleteRecursive($admin_dir . "blockspadmin/", TRUE)) $remnants[] = "/modules/system/admin/blockspadmin/";
		if (!icms_core_Filesystem::deleteFile(ICMS_MODULES_PATH . "/system/language/english/admin/blockspadmin.php")) $remnants[] = "/modules/system/language/english/admin/blockspadmin.php";
		// @todo deal with symlinks and help files, templates, handle multiple languages

		/* Remove system/admin/modulesadmin/ */
		if (!icms_core_Filesystem::deleteRecursive($admin_dir . "modulesadmin/", TRUE)) $remnants[] = "/modules/system/admin/modulesadmin/";
		if (!icms_core_Filesystem::deleteFile(ICMS_MODULES_PATH . "/system/language/english/admin/modulesadmin.php")) $remnants[] = "/modules/system/language/english/admin/modulesadmin.php";
		// @todo deal with symlinks and help files, templates, handle multiple languages

		/* Change instances of auth_method "xoops" to "local" */
		$sql = "UPDATE `" . icms::$xoopsDB->prefix('config') . "` SET `conf_value` = 'local' WHERE `conf_name` = 'auth_method' AND `conf_value` = 'xoops';";
		$sql .= "UPDATE `" . icms::$xoopsDB->prefix('configoption') . "` SET `confop_value` = 'local' WHERE `confop_value` = 'xoops' AND `confop_name` = '_MD_AM_AUTH_CONFOPTION_XOOPS';";
		$icmsDatabaseUpdater->runQuery($sql, sprintf(_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL, $sql), sprintf(_DATABASEUPDATER_MSG_QUERY_FAILED, $sql));

        /* Add the MP4 mimetype*/
        $sql = "INSERT INTO `" . icms::$xoopsDB->prefix('system_mimetype') . "` (`mimetypeid`, `extension`, `types`, `name`, `dirname`) VALUES (NULL, 'mp4', 'video/mp4', 'MP4 Video File', '');";
        $icmsDatabaseUpdater->runQuery($sql, sprintf(_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL, $sql), sprintf(_DATABASEUPDATER_MSG_QUERY_FAILED, $sql));

        /* @todo Set the IPF property of the module to '1' here */

            if (file_exists(ICMS_UPLOAD_PATH . '/system/userrank') ) {
                mkdir(ICMS_UPLOAD_PATH . '/icms', 0775);
                rename(ICMS_UPLOAD_PATH . '/system/userrank', ICMS_UPLOAD_PATH . '/icms/member_rank');
            }

        /*
        $table = new icms_db_legacy_updater_Table('system_mimetype');

            if (!$abortUpdate) {

                $mime_cats = array(
                    "NULL, 'mp4', 'video/mp4', 'MP4 Video File', ''");
                foreach ($mime_cats as $mime) {
                    $table->setData($mime);
                }
                $table->addData();
            }
            unset($table);

    }
*/

		/* Finish up this portion of the db update */
		if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
			echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . '<br />';
			if (count($remnants)) {
				icms_core_Message::warning($remnants, "Unable to remove these files - you can remove them manually", TRUE);
			}

			/* Add this as the last instruction of the last version update - outside of this and it will notify every time
			 * they update the system module, even if there isn't an update being applied
			 *
			 * !! Notification of the installation to  - Temporary solution, opt-out or opt-in needed before final release.*/
			icms_module_Handler::installation_notify($newDbVersion, ICMS_URL);
		}
	}

/*
 * This portion of the upgrade must remain as the last section of code to execute
 * Place all release upgrade steps above this point
 */
	echo "</code>";
    if ($abortUpdate) {
        icms_core_Message::error(sprintf(_DATABASEUPDATER_UPDATE_ERR, icms_conv_nr2local($newDbVersion)), _DATABASEUPDATER_UPDATE_DB, TRUE);
    }
	if ($from_112 && ! $abortUpdate) {
		echo _DATABASEUPDATER_MSG_FROM_112;
		echo '<script>setTimeout("window.location.href=\'' . ICMS_MODULES_URL . '/system/admin.php?fct=modules&op=install&module=content&from_112=1\'",20000);</script>';
	}

	$feedback = ob_get_clean();
	if (method_exists($module, "setMessage")) {
		$module->messages = $module->setMessage($feedback);
	} else {
		echo $feedback;
	}

	return icms_core_Filesystem::cleanFolders(array('cache' => ICMS_CACHE_PATH . "/"), $CleanWritingFolders);
}
