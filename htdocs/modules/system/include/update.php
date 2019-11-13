<?php
// $Id: update.php 12313 2013-09-15 21:14:35Z skenow $
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
 * @version		$Id: update.php 12313 2013-09-15 21:14:35Z skenow $
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
	//$dbVersion  = $module->getDBVersion();
	//$oldversion  = $module->getVar('version');

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

	if ($dbVersion < 40) include 'update-112-to-122.php';
	if ($dbVersion < 45) include 'update-13.php';

	/*  Begin upgrade to version 1.4 */
	if (!$abortUpdate) $newDbVersion = 45;
	try {
		if ($dbVersion < $newDbVersion) {
			// Remove the banners table

			// Remove the data entry for the banners submodule
			$table = new icms_db_legacy_updater_Table('config');
			$icmsDatabaseUpdater->runQuery("ALTER TABLE `" . $table->name() . "` DROP INDEX conf_mod_cat_id, ADD INDEX mod_cat_order(conf_modid, conf_catid, conf_order)", 'Successfully altered the indexes on table config', '');
			unset($table);

			// Remove the 'banners.php' file in the root
			icms_core_Filesystem::deleteFile(ICMS_ROOT_PATH . 'banners.php');
			// Remove the 'banners' subfolder in the modules/system/admin
			icms_core_Filesystem::deleteRecursive(ICMS_ROOT_PATH . "/modules/system/admin/banners", true);
			icms_core_Filesystem::deleteFile(ICMS_ROOT_PATH . "/modules/system/admin/banners.php");

			// Remove the system template files that are no longer necessary
			icms_core_Filesystem::deleteRecursive(ICMS_ROOT_PATH . "/modules/system/templates/admin", true);

			/* Finish up this portion of the db update */

			if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
			echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . '<br />';
			}
		}
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
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
