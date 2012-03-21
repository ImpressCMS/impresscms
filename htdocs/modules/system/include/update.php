<?php
/**
 * DataBase Update Functions
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		1.0
 * @author		malanciault <marcan@impresscms.org)
 * @version		$Id$
 */

icms_loadLanguageFile('core', 'databaseupdater');
/**
 * Automatic update of the system module
 *
 * @param object $module reference to the module object
 * @param int $oldversion The old version of the database
 * @param int $dbVersion The database version
 * @return mixed
 */
function xoops_module_update_system(&$module, $oldversion = NULL, $dbVersion = NULL) {

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

	if ($dbVersion < 42) include 'update-122-to-13.php';

/*  Begin upgrade to version 2.0 */
	if (!$abortUpdate) $newDbVersion = 43;

	/*
	 * The update for this version will need to convert the system module to an IPF module
	 * and set the ipf flag to 1 in the modules table.
	 * There will be a lot of files that will need to be removed - all the legacy files and folders
	 * along with the system module changes.
	 */

	if ($dbVersion < $newDbVersion) {
		/* list of directories in the system/admin/ folder */
		$admin_dir = ICMS_MODULES_PATH . '/system/admin/';
		$dirlist = icms_core_Filesystem::getDirList($admin_dir);
		foreach ($dirlist as $dir) {
			/* remove xoops_version.php and main.php from these folders if the update has
		 	 * been successful.
		 	 */
			if (!icms_core_Filesystem::deleteFile($admin_dir . $dir . '/xoops_version.php')) $abortUpdate = TRUE;
			if (!icms_core_Filesystem::deleteFile($admin_dir . $dir . '/main.php')) $abortUpdate = TRUE;
			/* Remove the system/{function}/class/ subfolder, if it exists */
			if (!icms_core_Filesystem::deleteRecursive($admin_dir . $dir . "/class/", TRUE)) $abortUpdate = TRUE;
		}
		/* Remove system/xoops_version.php */
		if (!icms_core_Filesystem::deleteFile(ICMS_MODULES_PATH . "/system/xoops_version.php")) $abortUpdate = TRUE;

		/* Remove system/admin/blocksadmin/ */
		if (!icms_core_Filesystem::deleteRecursive($admin_dir . "blocksadmin/", TRUE)) $abortUpdate = TRUE;
		if (!icms_core_Filesystem::deleteRecursive($admin_dir . "language/english/admin/blocksadmin.php", TRUE)) $abortUpdate = TRUE;
		// deal with symlinks and help files

		/* Remove system/admin/blockspadmin/ */
		//if (!icms_core_Filesystem::deleteRecursive($admin_dir . "blockspadmin/", TRUE)) $abortUpdate = TRUE;

		/* Remove system/admin/modulesadmin/ */
		//if (!icms_core_Filesystem::deleteRecursive($admin_dir . "modulesadmin/", TRUE)) $abortUpdate = TRUE;

		/* Finish up this portion of the db update */
		if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
			echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . '<br />';
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
		echo '<script>setTimeout("window.location.href=\'' . ICMS_MODULES_URL . '/system/admin.php?fct=modulesadmin&op=install&module=content&from_112=1\'",20000);</script>';
	}

	$feedback = ob_get_clean();
	if (method_exists($module, "setMessage")) {
		$module->messages = $module->setMessage($feedback);
	} else {
		echo $feedback;
	}

	return icms_core_Filesystem::cleanFolders(array('templates_c' => ICMS_COMPILE_PATH . "/", 'cache' => ICMS_CACHE_PATH . "/"), $CleanWritingFolders);
}