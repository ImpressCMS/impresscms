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
		/* list of directories in the system/admin/ folder
		 * remove xoops_version.php and main.php from these folders if the update has 
		 * been successful. 
		 * 
		 * Remove the class/ subfolder, if it exists 
		 * 
		 * Remove system/xoops_version.php
		 */
		$admin_dir = ICMS_MODULES_PATH . '/system/admin';
		$dirlist = icms_core_Filesystem::getDirList($admin_dir);
			
		/* Finish up this portion of the db update */
		if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
			echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . '<br />';
		}
	}
	
	
	if (!$abortUpdate) $newDbVersion = 42;
	/* 1.3.2 release - HTML Purifier 4.4.0 update */
	
	if ($dbVersion < $newDbVersion) {
		/* New HTML Purifier options -
		 * purifier_URI_SafeIframeRegexp. after purifier_URI_AllowedSchemes
		 * purifier_HTML_SafeIframe, after purifier_HTML_SafeObject
		 */
		$table = new icms_db_legacy_updater_Table("config");
		
		// retrieve the value of the position before the config to be inserted. 
		$configs = icms::$config->getConfigs(icms_buildCriteria(array("conf_name" => "purifier_URI_AllowedSchemes")));
		$p = $configs[0]->getVar('conf_order') + 1;

		//move all the other options down
		$icmsDatabaseUpdater->runQuery("UPDATE `" . $table->name() . "` SET conf_order = conf_order + 2 WHERE conf_order >= " . $p . " AND conf_catid = " . ICMS_CONF_PURIFIER);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_PURIFIER, 'purifier_URI_SafeIframeRegexp', '_MD_AM_PURIFIER_URI_SAFEIFRAMEREGEXP', '', '_MD_AM_PURIFIER_URI_SAFEIFRAMEREGEXPDSC', 'textsarea', 'array', $p);

		// retrieve the value of the position before the config to be inserted. 
		$configs = icms::$config->getConfigs(icms_buildCriteria(array("conf_name" => "purifier_HTML_SafeObject")));
		$p = $configs[0]->getVar('conf_order') + 1;
		//move all the other options down
		$icmsDatabaseUpdater->runQuery("UPDATE `" . $table->name() . "` SET conf_order = conf_order + 2 WHERE conf_order >= " . $p . " AND conf_catid = " . ICMS_CONF_PURIFIER);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_PURIFIER, 'purifier_HTML_SafeIframe', '_MD_AM_PURIFIER_HTML_SAFEIFRAME', '0', '_MD_AM_PURIFIER_HTML_SAFEIFRAMEDSC', 'yesno', 'int', $p);		
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

	$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
	return icms_core_Filesystem::cleanFolders(array('templates_c' => ICMS_COMPILE_PATH . "/", 'cache' => ICMS_CACHE_PATH . "/"), $CleanWritingFolders);
}