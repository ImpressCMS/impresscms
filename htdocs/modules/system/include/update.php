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

use Icms\Db\Legacy\Factory as icms_db_legacy_Factory;

icms_loadLanguageFile("core", "databaseupdater");

// this needs to be the latest db version - and the constant must start with the module's dirname
if (is_object(icms::$module)) {
	define("SYSTEM_DB_VERSION", icms::$module->getDBVersion());
} else {
	define("SYSTEM_DB_VERSION", 49);
}

/**
 * Automatic update of the system module
 *
 * @param object $module reference to the module object
 * @param int $oldversion The old version of the database
 * @param int $dbVersion The database version
 * @return mixed
 */
function xoops_module_update_system(
	&$module,
	$oldversion = null,
	$dbVersion = null,
) {
	global $xoTheme;

	$from_112 = $abortUpdate = false;

	$oldversion = $module->getVar("version");
	if ($oldversion < 120) {
		$result = icms::$xoopsDB->query(
			"SELECT t1.tpl_id FROM " .
				icms::$xoopsDB->prefix("tplfile") .
				" t1, " .
				icms::$xoopsDB->prefix("tplfile") .
				" t2 WHERE t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_id > t2.tpl_id",
		);

		$tplids = [];
		while ([$tplid] = icms::$xoopsDB->fetchRow($result)) {
			$tplids[] = $tplid;
		}

		if (count($tplids) > 0) {
			$tplfile_handler = icms::handler("icms_view_template_file");
			$duplicate_files = $tplfile_handler->getObjects(
				new icms_db_criteria_Item(
					"tpl_id",
					"(" . implode(",", $tplids) . ")",
					"IN",
				),
			);

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
	echo sprintf(_DATABASEUPDATER_CURRENTVER, icms_conv_nr2local($dbVersion)) .
		"<br />";
	echo "<code>" .
		sprintf(
			_DATABASEUPDATER_UPDATE_TO,
			icms_conv_nr2local(ICMS_SYSTEM_DBVERSION),
		) .
		"<br />";

	/**
	 * DEVELOPER, PLEASE NOTE !!!
	 *
	 * Everytime we add a new upgrade block here, the dbversion of the System Module will get
	 * incremented. It is very important to modify the ICMS_SYSTEM_DBVERSION accordingly
	 * in htdocs/include/version.php
	 *
	 * When we start a new major release, move all the previous version's upgrade scripts to
	 * a separate file, to minimize file size and memory usage. When creating the new file, be sure to
	 * check for the need to include earlier update files. Only check for the previous file here,
	 * cascading the checks in each file.
	 *
	 * Every release should run this once, even if only to make sure the module's version
	 * gets updated. It also clears the templates_c and cache folders.
	 */

	$CleanWritingFolders = false;

	/* check for previous release's upgrades - dbversion < this major release's initial version */
	if ($dbVersion < 46) {
		include "update-14.php";
	}

	/* Begin automatic upgrades available with IPF-compliant objects
	 * This can be done before any specific upgrade tasks are started
	 */
	$icmsDatabaseUpdater->automaticUpgrade("icms_data", ["file", "urllink"]);
	$icmsDatabaseUpdater->moduleUpgrade($module);

	/* Begin upgrade to version 2.0.0 beta 1 */
	if (!$abortUpdate) {
		$newDbVersion = 47;
	}
	try {
		if ($dbVersion < $newDbVersion) {
			// Remove all the legacy files that are were removed in 1.5.0
			// TODO: make a generic file removal function.
			// Remove the 'deprecated' files in the root and all OpenID related files

			$removeFolders_150 = [
				ICMS_ROOT_PATH . "/kernel",
				ICMS_ROOT_PATH . "/class",
			];

			$removeOpenIDfiles = [
				ICMS_ROOT_PATH . "/modules/system/templates/system_openid.html",
				ICMS_ROOT_PATH . "/try_auth.php",
				ICMS_ROOT_PATH . "/finish_auth.php",
				ICMS_ROOT_PATH . "/libraries/icms/auth/Openid.php",
			];
			$removeOpenIDfolders = [ICMS_ROOT_PATH . "/libraries/phpopenid"];

			// Determine if FCKeditor is in use and remove it if it is not
			//$config_handler = icms::handler('icms_config');
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(
				new icms_db_criteria_Item("conf_value", "FCKeditor"),
			);
			$config = icms::$config->getConfigs($criteria);
			$confcount = count($config);

			if ($confcount == 0) {
				icms_core_Filesystem::deleteRecursive(
					ICMS_EDITOR_PATH . "/FCKeditor",
					true,
				);
			}

			// Determine if TinyMCE is in use and remove it if it is not
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item("conf_value", "tinymce"));
			$config = icms::$config->getConfigs($criteria);
			$confcount = count($config);

			if ($confcount == 0) {
				icms_core_Filesystem::deleteRecursive(
					ICMS_EDITOR_PATH . "/tinymce",
					true,
				);
			}

			// first, remove the files and the folders that contain deprecated classes.
			foreach ($removeFolders_150 as $foldertoremove) {
				echo icms_core_Filesystem::deleteRecursive(
					$foldertoremove,
					true,
				) . "</br>";
			}

			// Third, check if openID is configured as login method. If not, remove.
			if (!defined("ICMS_INCLUDE_OPENID")) {
				foreach ($removeOpenIDfiles as $filetoremove) {
					icms_core_Filesystem::deleteFile($filetoremove);
					echo "Removed " . $filetoremove . "</br>";
				}
				foreach ($removeOpenIDfolders as $foldertoremove) {
					icms_core_Filesystem::deleteRecursive(
						$foldertoremove,
						true,
					);
					echo "Removed" . $foldertoremove . "</br>";
				}
			}
		}

		/* Finish up this portion of the db update */
		if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion(
				$newDbVersion,
				"system",
			);
			echo sprintf(
				_DATABASEUPDATER_UPDATE_OK,
				icms_conv_nr2local($newDbVersion),
			) . "<br />";
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	/* Begin upgrade to version 2.0.0 beta 2 */
	if (!$abortUpdate) {
		$newDbVersion = 48;
	}
	try {
		/* things specific to this release */
		if ($dbVersion < $newDbVersion) {
			// remove old banners tables
			$tablestodrop = ["banner", "bannerclient", "bannerfinish"];
			foreach ($tablestodrop as $table) {
				$tableObj = new icms_db_legacy_updater_Table($table);
				if ($tableObj->exists()) {
					$tableObj->dropTable();
				}
			}

			// remove unused config itmes
			$itemstoremove = ["banners", "auth_openid"];
			foreach ($itemstoremove as $item) {
				$criteria = new icms_db_criteria_Compo();
				$criteria->add(new icms_db_criteria_Item("conf_name", $item));
				$config = icms::$config->getConfigs($criteria);
				if (count($config) > 0) {
					icms::$config->deleteConfig($config[0]);
				}
			}

			// remove columns from the users table
			$tabletoupdate = "users";
			$columnstoremove = ["openid", "user_viewoid"];
			$table = new icms_db_legacy_updater_Table($tabletoupdate);
			foreach ($columnstoremove as $column) {
				if ($table->fieldExists($column)) {
					$table->addDropedField($column);
				}
			}
			if ($table->getDropedFields()) {
				$table->dropFields();
			}

			// remove the content.php file if the module is not installed
			$module_handler = icms::handler("icms_module");
			$installed_mods = $module_handler->getList();

			if (!in_array("content", $installed_mods)) {
				$filetoremove = ICMS_ROOT_PATH . "/content.php";
				icms_core_Filesystem::deleteFile($filetoremove);
			}
			/* Finish up this portion of the db update */
			if (!$abortUpdate) {
				$icmsDatabaseUpdater->updateModuleDBVersion(
					$newDbVersion,
					"system",
				);
				echo sprintf(
					_DATABASEUPDATER_UPDATE_OK,
					icms_conv_nr2local($newDbVersion),
				) . "<br />";
			}
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	/* Begin upgrade to version 2.0.0 (formerly: vendor move to trust path) */
	// The vendor move of this step is part of step 50, which installs the host,
	// vendor/ included, into the trust path; only the version is recorded here.
	if (!$abortUpdate) {
		$newDbVersion = 49;
	}
	if (!$abortUpdate && $dbVersion < $newDbVersion) {
		$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, "system");
		echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . "<br />";
	}

	/* Repair the HTML filter settings of sites installed with 2.0.x on PHP 8 */
	// Their installer started the system module at dbversion 40 and ran step 42
	// (update-13.php), which appends iframe to the allowed elements and attributes
	// but read the lists as empty on PHP 8: only the iframe entries were left, so
	// the filter removed every other tag (links and formatting in blocks, ...).
	// The default lists are added back; entries the site added are kept.
	if (!$abortUpdate && $dbVersion < 50) {
		$purifierDefaults = [
			'purifier_HTML_AllowedElements' => ['a', 'abbr', 'acronym', 'b', 'blockquote', 'br', 'caption', 'cite', 'code', 'dd', 'del', 'dfn', 'div', 'dl',
				'dt', 'em', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'img', 'ins', 'kbd', 'li', 'ol', 'p', 'pre', 's',
				'span', 'strike', 'strong', 'sub', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'tt', 'u', 'ul', 'var'],
			'purifier_HTML_AllowedAttributes' => ['a.class', 'a.href', 'a.id', 'a.name', 'a.rev', 'a.style', 'a.title', 'a.target', 'a.rel', 'abbr.title', 'acronym.title',
				'blockquote.cite', 'div.align', 'div.style', 'div.class', 'div.id', 'font.size', 'font.color', 'h1.style', 'h2.style', 'h3.style', 'h4.style', 'h5.style', 'h6.style',
				'img.src', 'img.alt', 'img.title', 'img.class', 'img.align', 'img.style', 'img.height', 'img.width', 'li.style', 'ol.style', 'p.style', 'span.style', 'span.class', 'span.id',
				'table.class', 'table.id', 'table.border', 'table.cellpadding', 'table.cellspacing', 'table.style', 'table.width', 'td.abbr', 'td.align', 'td.class', 'td.id', 'td.colspan',
				'td.rowspan', 'td.style', 'td.valign', 'tr.align', 'tr.class', 'tr.id', 'tr.style', 'tr.valign', 'th.abbr', 'th.align', 'th.class', 'th.id', 'th.colspan', 'th.rowspan',
				'th.style', 'th.valign', 'ul.style'],
		];
		$configTable = icms::$xoopsDB->prefix('config');
		foreach ($purifierDefaults as $confName => $defaults) {
			$result = icms::$xoopsDB->query("SELECT conf_value FROM `" . $configTable . "` WHERE conf_modid = 0 AND conf_name = '" . $confName . "'");
			[$raw] = $result ? (icms::$xoopsDB->fetchRow($result) ?: [null]) : [null];
			if ($raw === null) {
				continue;
			}
			$current = @unserialize((string) $raw);
			$current = is_array($current) ? $current : [];
			// a site that removed the basic defaults on purpose keeps at least one of them
			if (array_intersect(array_slice($defaults, 0, 2), $current) !== []) {
				continue;
			}
			$repaired = array_values(array_unique(array_merge($defaults, $current)));
			$icmsDatabaseUpdater->runQuery(
				$sql = "UPDATE `" . $configTable . "` SET conf_value = " . icms::$xoopsDB->quoteString(serialize($repaired))
					. " WHERE conf_modid = 0 AND conf_name = '" . $confName . "'",
				sprintf(_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL, $confName),
				sprintf(_DATABASEUPDATER_MSG_QUERY_FAILED, $confName),
			);
		}
		unset($purifierDefaults, $configTable, $confName, $defaults, $result, $raw, $current, $repaired);
	}

	/* Begin upgrade to version 2.1.0 (Symfony host in the trust path) */
	if (!$abortUpdate) {
		$newDbVersion = 50;
	}
	try {
		// Every release ships the host in the staging directory trust_dist/, so the
		// host is installed whenever that directory exists, not only once.
		$stagingPath = ICMS_ROOT_PATH . "/" . icms_core_Filesystem::TRUST_DISTRIBUTION_DIR;
		if (!$abortUpdate && ($dbVersion < $newDbVersion || is_dir($stagingPath))) {
			// This request may still load classes from the trust path or the staging
			// directory, so the verified copy is activated at the end of the request.
			$installResult = icms_core_Filesystem::installTrustDistribution(
				$stagingPath,
				ICMS_TRUST_PATH,
				ICMS_ROOT_PATH,
				true,
			);

			if (in_array($installResult["status"], ["ok", "skipped", "nodist"], true)) {
				echo htmlspecialchars($installResult["message"], ENT_QUOTES) . "<br />";
				if ($installResult["warning"] !== "") {
					echo '<span style="color:orange;">' .
						htmlspecialchars($installResult["warning"], ENT_QUOTES) .
						"</span><br />";
				}
			} else {
				// The trust path is unchanged; the admin fixes the cause (see the
				// message) and runs the update again.
				$abortUpdate = true;
				echo '<span style="color:red;">' .
					"ERROR: Could not install the host files into the trust path. " .
					nl2br(htmlspecialchars($installResult["message"], ENT_QUOTES)) .
					"</span><br />";
			}

			if (!$abortUpdate && $dbVersion < $newDbVersion) {
				$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, "system");
				echo sprintf(_DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local($newDbVersion)) . "<br />";
			}
		}
		unset($stagingPath);
	} catch (Throwable $e) {
		$abortUpdate = true;
		echo $e->getMessage();
	}

	/**
	 * This portion of the upgrade must remain as the last section of code to execute
	 * Place all release upgrade steps above this point
	 */

	echo "</code>";
	if ($abortUpdate) {
		icms_core_Message::error(
			sprintf(
				_DATABASEUPDATER_UPDATE_ERR,
				icms_conv_nr2local($newDbVersion),
			),
			_DATABASEUPDATER_UPDATE_DB,
			true,
		);
	}
	if ($from_112 && !$abortUpdate) {
		/* will this work anymore? It depends on having the content module as part of the release */
		echo _DATABASEUPDATER_MSG_FROM_112;
		echo '<script>setTimeout("window.location.href=\'' .
			ICMS_MODULES_URL .
			'/system/admin.php?fct=modulesadmin&op=install&module=content&from_112=1\'",20000);</script>';
	}

	$feedback = ob_get_clean();
	if (method_exists($module, "setMessage")) {
		$module->messages = $module->setMessage($feedback);
	} else {
		echo $feedback;
	}

	return icms_core_Filesystem::cleanFolders(
		[
			"templates_c" => ICMS_COMPILE_PATH . "/",
			"cache" => ICMS_CACHE_PATH . "/",
		],
		$CleanWritingFolders,
	);
}
