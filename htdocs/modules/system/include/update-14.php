<?php
// $Id: update.php 12313 2013-09-15 21:14:35Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu) //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project //
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

/* check for previous release's upgrades - dbversion < this major release's initial version */
if($dbVersion < 45) include 'update-13.php';

/* Begin upgrade to version 1.4 */
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
catch (Exception $e) {
	echo $e->getMessage();
}

/* upgrade steps for 1.4.3 */
if (!$abortUpdate) $newDbVersion = 46;
try {
	/* things specific to this release */
	if ($dbVersion < $newDbVersion) {

		/** should we throw an exception? The old methods would set $abortUpdate and exit
		 * if the steps weren't successful
		 */
	}

	/* Finish up this portion of the update */
	if (!$abortUpdate) {
		$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
		echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . '<br />';
	}
}

catch (Exception $e) {
	echo $e->getMessage();
}