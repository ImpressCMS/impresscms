<?php
// $Id: main.php 12313 2013-09-15 21:14:35Z skenow $
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
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Modules
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id: main.php 12313 2013-09-15 21:14:35Z skenow $

 */
if (!is_object(icms::$user) || !is_object(icms::$module) || !icms::$user->isAdmin(icms::$module->getVar('mid'))) {
	exit("Access Denied");
}

global $icmsAdminTpl;
$icmsAdminTpl = new icms_view_Tpl();

include_once ICMS_MODULES_PATH . "/system/admin/modulesadmin/modulesadmin.php";
icms_loadLanguageFile('system', 'blocksadmin', TRUE);

/* default values */
$op = 'list';
$backup = '';

/* Set filters */
$filter_get = array();

$filter_post = array(
	'newname' => 'str',
	'oldname' => 'str',
	'newstatus' => 'int',
	'oldstatus' => 'int',
	'weight' => 'int',
	'oldweight' => 'int',
	'module' => 'str',
	'fct' => 'str',
	'op' => 'str',
	'backup' => 'str',
	'confirm_submit' => 'str',
	'confirm_back' => 'str'
);

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_GET);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

if (in_array($op, array('submit', 'install_ok', 'update_ok', 'uninstall_ok', 'refresh_catalog', 'github_update_ok', 'rollback_ok', 'delete_backup_ok', 'remove_ok'))) {
	if (!icms::$security->check()) {
		$op = 'list';
	}
}

// module names are used in paths: only allow plain folder names for the GitHub operations
if (in_array($op, array('github_update', 'github_update_ok', 'remove', 'remove_ok')) && (!isset($module) || !is_string($module) || !preg_match('/^[A-Za-z0-9_-]+$/', $module))) {
	$op = 'list';
}

if ($op == "list" || $op == "refresh_catalog") {
	icms_cp_header();
	echo xoops_module_list($op == "refresh_catalog");
	icms_cp_footer();
	exit();
}

if ($op == "confirm") {
	icms_cp_header();
	$error = array();
	if (!is_writable(ICMS_CACHE_PATH . '/')) {
		// attempt to chmod 666
		if (!chmod(ICMS_CACHE_PATH . '/', 0777)) {
			$error[] = sprintf(_MUSTWABLE, "<strong>" . ICMS_CACHE_PATH . '/</strong>');
		}
	}

	if (count($error) > 0) {
		icms_core_Message::error($error);
		echo "<p><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a></p>";
		icms_cp_footer();
		exit();
	}



	echo "<h4 style='text-align:" . _GLOBAL_LEFT . ";'>" . _MD_AM_PCMFM . "</h4>"
		. "<form action='admin.php' method='post'>"
		. "<input type='hidden' name='fct' value='modulesadmin' />"
		. "<input type='hidden' name='op' value='submit' />"
		. "<table width='100%' border='0' cellspacing='1' class='outer'>"
		. "<tr align='center'><th>" . _MD_AM_MODULE . "</th><th>" . _MD_AM_ACTION . "</th><th>" . _MD_AM_ORDER . "</th></tr>";

	$mcount = 0;
	foreach ($module as $mid) {
		if ($mcount % 2 != 0) {
			$class = 'odd';
		} else {
			$class = 'even';
		}
		echo '<tr class="' . $class . '"><td align="center">' . icms_core_DataFilter::stripSlashesGPC($oldname[$mid]);
		$newname[$mid] = trim(icms_core_DataFilter::stripslashesGPC($newname[$mid]));
		if ($newname[$mid] != $oldname[$mid]) {
			echo '&nbsp;&raquo;&raquo;&nbsp;<span style="color:#ff0000;font-weight:bold;">' . $newname[$mid] . '</span>';
		}
		echo '</td><td align="center">';

		if (isset($newstatus[$mid]) && $newstatus[$mid] ==1) {

			if ($oldstatus[$mid] == 0) {
				echo "<span style='color:#ff0000;font-weight:bold;'>" . _MD_AM_ACTIVATE . "</span>";
			} else {
				echo _MD_AM_NOCHANGE;
			}
		} else {
			$newstatus[$mid] = 0;
			if ($oldstatus[$mid] == 1) {
				echo "<span style='color:#ff0000;font-weight:bold;'>" . _MD_AM_DEACTIVATE . "</span>";
			} else {
				echo _MD_AM_NOCHANGE;
			}
		}
		echo "</td><td align='center'>";
		if ($oldweight[$mid] != $weight[$mid]) {
			echo "<span style='color:#ff0000;font-weight:bold;'>" . $weight[$mid] . "</span>";
		} else {
			echo $weight[$mid];
		}

		echo "<input type='hidden' name='module[]' value='". (int) $mid
			."' /><input type='hidden' name='oldname[" . $mid . "]' value='" . htmlspecialchars($oldname[$mid], ENT_QUOTES)
			."' /><input type='hidden' name='newname[" . $mid . "]' value='" . htmlspecialchars($newname[$mid], ENT_QUOTES)
			."' /><input type='hidden' name='oldstatus[" . $mid . "]' value='" . (int) $oldstatus[$mid]
			."' /><input type='hidden' name='newstatus[" . $mid . "]' value='" . (int) $newstatus[$mid]
			."' /><input type='hidden' name='oldweight[" . $mid . "]' value='" . (int) $oldweight[$mid]
			."' /><input type='hidden' name='weight[" . $mid . "]' value='" . (int) $weight[$mid]
			."' /></td></tr>";
	}

	echo "<tr class='foot' align='center'><td colspan='3'><input type='submit' value='"
		. _MD_AM_SUBMIT . "' />&nbsp;<input type='button' value='" . _MD_AM_CANCEL
		. "' onclick='location=\"admin.php?fct=modulesadmin\"' />" . icms::$security->getTokenHTML()
		. "</td></tr></table></form>";

	icms_cp_footer();
	exit();
}

if ($op == "submit") {
	$ret = array();
	$write = FALSE;
	foreach ($module as $mid) {

		if (isset($newstatus[$mid]) && $newstatus[$mid] ==1) {

			if ($oldstatus[$mid] == 0) {
				$ret[] = xoops_module_activate($mid);
			}
		} else {
			if ($oldstatus[$mid] == 1) {
				$ret[] = xoops_module_deactivate($mid);
			}
		}
		$newname[$mid] = trim($newname[$mid]);
		if ($oldname[$mid] != $newname[$mid] || $oldweight[$mid] != $weight[$mid]) {
			$ret[] = xoops_module_change($mid, $weight[$mid], $newname[$mid]);
			$write = TRUE;
		}
		flush();
	}
	if ($write) {
		$contents = impresscms_get_adminmenu();
		if (!xoops_module_write_admin_menu($contents)) {
			$ret[] = "<p>" . _MD_AM_FAILWRITE . "</p>";
		}
	}
	icms_cp_header();
	if (count($ret) > 0) {
		foreach ($ret as $msg) {
			if ($msg != '') {
				echo $msg;
			}
		}
	}
	echo "<br /><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'install') {
	$module_handler = icms::handler('icms_module');

	$mod =& $module_handler->create();
	$mod->loadInfoAsVar($module);
	if ($mod->getInfo('image') != FALSE && trim($mod->getInfo('image')) != '') {
		$msgs ='<img src="' . ICMS_MODULES_URL . '/' . $mod->getVar('dirname') . '/' . trim($mod->getInfo('image')) . '" alt="" />';

	}
	$msgs .= '<br /><span style="font-size:smaller;">' . $mod->getVar('name') . '</span><br /><br />' . _MD_AM_RUSUREINS;
	if (empty($from_112)) {
		$from_112 = FALSE;
	}
	icms_cp_header();
	icms_core_Message::confirm(array('module' => $module, 'op' => 'install_ok', 'fct' => 'modulesadmin', 'from_112' => $from_112), 'admin.php', $msgs, _MD_AM_INSTALL);
	icms_cp_footer();
	exit();
}

if ($op == 'install_ok') {
	$ret = array();
	$ret[] = xoops_module_install($module);
	if ($from_112) {
		$ret[] = icms_module_update($module);
	}
	$contents = impresscms_get_adminmenu();
	if (!xoops_module_write_admin_menu($contents)) {
		$ret[] = "<p>" . _MD_AM_FAILWRITE . "</p>";
	}
	icms_cp_header();
	if (count($ret) > 0) {
		foreach ($ret as $msg) {
			if ($msg != '') {
				echo $msg;
			}
		}
	}
	echo "<br /><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'uninstall') {
	$module_handler = icms::handler('icms_module');

	$mod =& $module_handler->getByDirname($module);
	$mod->registerClassPath();

	if ($mod->getInfo('image') != FALSE && trim($mod->getInfo('image')) != '') {
		$msgs ='<img src="' . ICMS_MODULES_URL . '/' . $mod->getVar('dirname') . '/' . trim($mod->getInfo('image')) . '" alt="" />';

	}
	$msgs .= '<br /><span style="font-size:smaller;">' . $mod->getVar('name') . '</span><br /><br />' . _MD_AM_RUSUREUNINS;
	icms_cp_header();
	icms_core_Message::confirm(array('module' => $module, 'op' => 'uninstall_ok', 'fct' => 'modulesadmin'), 'admin.php', $msgs, _YES);
	icms_cp_footer();
	exit();
}

if ($op == 'uninstall_ok') {
	$ret = array();
	$ret[] = xoops_module_uninstall($module);
	$contents = impresscms_get_adminmenu();
	if (!xoops_module_write_admin_menu($contents)) {
		$ret[] = "<p>" . _MD_AM_FAILWRITE . "</p>";
	}
	icms_cp_header();
	if (count($ret) > 0) {
		foreach ($ret as $msg) {
			if ($msg != '') {
				echo $msg;
			}
		}
	}
	echo "<a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'update') {
	$module_handler = icms::handler('icms_module');

	$mod =& $module_handler->getByDirname($module);
	if ($mod->getInfo('image') != FALSE && trim($mod->getInfo('image')) != '') {
		$msgs ='<img src="' . ICMS_MODULES_URL . '/' . $mod->getVar('dirname') . '/' . trim($mod->getInfo('image')) . '" alt="" />';

	}
	$msgs .= '<br /><span style="font-size:smaller;">' . $mod->getVar('name') . '</span><br /><br />' . _MD_AM_RUSUREUPD;
	icms_cp_header();

	if (icms_getModuleInfo('system')->getDBVersion() < 14 && (!is_writable(ICMS_PLUGINS_PATH) || !is_dir(ICMS_ROOT_PATH . '/plugins/preloads') || !is_writable(ICMS_ROOT_PATH . '/plugins/preloads'))) {

		icms_core_Message::error(sprintf(_MD_AM_PLUGINSFOLDER_UPDATE_TEXT, ICMS_PLUGINS_PATH,ICMS_ROOT_PATH . '/plugins/preloads'), _MD_AM_PLUGINSFOLDER_UPDATE_TITLE, TRUE);

	}
	if (icms_getModuleInfo('system')->getDBVersion() < 37 && !is_writable(ICMS_IMANAGER_FOLDER_PATH)) {
		icms_core_Message::error(sprintf(_MD_AM_IMAGESFOLDER_UPDATE_TEXT, str_ireplace(ICMS_ROOT_PATH, "", ICMS_IMANAGER_FOLDER_PATH)), _MD_AM_IMAGESFOLDER_UPDATE_TITLE, TRUE);
	}

	icms_core_Message::confirm(array('module' => $module, 'op' => 'update_ok', 'fct' => 'modulesadmin'), 'admin.php', $msgs, _MD_AM_UPDATE);
	icms_cp_footer();
	exit();
}

if ($op == 'update_ok') {
	$ret = array();
	$ret[] = icms_module_update($module);
	$contents = impresscms_get_adminmenu();
	if (!xoops_module_write_admin_menu($contents)) {
		$ret[] = "<p>" . _MD_AM_FAILWRITE . "</p>";
	}
	icms_cp_header();
	if (count($ret) > 0) {
		foreach ($ret as $msg) {
			if ($msg != '') {
				echo $msg;
			}
		}
	}
	echo "<br /><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'github_update') {
	$catalog = new icms_module_Catalog();
	$entry = $catalog->getModule($module);
	icms_cp_header();
	if ($entry === NULL || empty($entry['latest'])) {
		icms_core_Message::error(sprintf(_MD_AM_GITHUB_FAILED, htmlspecialchars($module, ENT_QUOTES)));
	} else {
		$current = icms::handler('icms_module')->create();
		$msgs = sprintf(_MD_AM_GITHUB_CONFIRM, '<strong>' . htmlspecialchars($entry['dirname'], ENT_QUOTES) . '</strong>', htmlspecialchars($entry['latest']['version'], ENT_QUOTES));
		if ($current->loadInfo($module, FALSE)) {
			$msgs .= '<br /><br />' . sprintf(_MD_AM_GITHUB_INSTALLED_VERSION, htmlspecialchars($current->getInfo('version'), ENT_QUOTES));
		}
		if ($entry['latest']['changelog'] != '') {
			$msgs .= '<div style="text-align:' . _GLOBAL_LEFT . '; font-weight: normal; max-height: 20em; overflow: auto;">'
				. nl2br(htmlspecialchars($entry['latest']['changelog'], ENT_QUOTES)) . '</div>';
		}
		icms_core_Message::confirm(array('module' => $entry['dirname'], 'op' => 'github_update_ok', 'fct' => 'modulesadmin'), 'admin.php', $msgs, _MD_AM_GITHUB_INSTALL);
	}
	icms_cp_footer();
	exit();
}

if ($op == 'github_update_ok') {
	global $icmsGithubUpdateMessages;
	$icmsGithubUpdateMessages = array();
	$updater = xoops_module_github_updater();
	$installed = icms::handler('icms_module')->getByDirname($module);
	$isInstalled = is_object($installed) && $installed->getVar('mid') > 0;

	$tables = array();
	$version = '';
	$files = icms::handler('icms_module')->create();
	if ($files->loadInfo($module, FALSE)) {
		$version = $files->getInfo('version');
		if ($isInstalled && is_array($files->getInfo('tables'))) {
			$tables = $files->getInfo('tables');
		}
	}

	$result = $updater->update($module, $tables, $version,
		$isInstalled ? 'xoops_module_github_run_update' : NULL,
		'xoops_module_github_tables');

	if ($isInstalled) {
		$contents = impresscms_get_adminmenu();
		xoops_module_write_admin_menu($contents);
	}

	icms_cp_header();
	$name = htmlspecialchars($module, ENT_QUOTES);
	if ($result !== FALSE) {
		icms_core_Message::result(sprintf(_MD_AM_GITHUB_DONE, $name));
	} else {
		icms_core_Message::error(sprintf(_MD_AM_GITHUB_FAILED, $name) . '<br />' . $updater->getErrors());
	}
	echo '<p>' . $updater->getMessages() . '</p>';
	foreach ($icmsGithubUpdateMessages as $msg) {
		echo $msg;
	}
	if ($result !== FALSE && !$isInstalled) {
		echo '<p>' . _MD_AM_GITHUB_INSTALLNOW . ' <a href="admin.php?fct=modulesadmin&amp;op=install&amp;module=' . urlencode($module) . '">' . _MD_AM_INSTALL . '</a></p>';
	}
	echo "<br /><a href='admin.php?fct=modulesadmin&amp;op=backups'>" . _MD_AM_BACKUPS . "</a> | <a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'backups') {
	$updater = xoops_module_github_updater();
	icms_cp_header();
	echo "<h4 style='text-align:" . _GLOBAL_LEFT . ";'>" . _MD_AM_BACKUPS . "</h4>"
		. "<table width='100%' border='0' cellspacing='1' class='outer'>"
		. "<tr align='center'><th>" . _MD_AM_MODULE . "</th><th>" . _MD_AM_VERSION . "</th><th>" . _MD_AM_BACKUP_DATE . "</th><th>"
		. _MD_AM_BACKUP_STATUS . "</th><th>" . _MD_AM_ACTION . "</th></tr>";
	$backups = $updater->getBackups();
	foreach ($backups as $i => $backup) {
		$id = urlencode($backup['id']);
		echo "<tr class='" . ($i % 2 ? 'odd' : 'even') . "' align='center'>"
			. "<td>" . htmlspecialchars($backup['dirname'], ENT_QUOTES) . "</td>"
			. "<td>" . htmlspecialchars($backup['version'] !== '' ? $backup['version'] : '-', ENT_QUOTES) . " &raquo; " . htmlspecialchars($backup['to_version'], ENT_QUOTES) . "</td>"
			. "<td>" . formatTimestamp($backup['created'], 'm') . "</td>"
			. "<td>" . xoops_module_backup_status($backup['status']) . "</td>"
			. "<td><a href='admin.php?fct=modulesadmin&amp;op=rollback&amp;backup=" . $id . "'>" . _MD_AM_BACKUP_ROLLBACK . "</a>"
			. " | <a href='admin.php?fct=modulesadmin&amp;op=delete_backup&amp;backup=" . $id . "'>" . _MD_AM_BACKUP_DELETE . "</a></td></tr>";
	}
	if (!$backups) {
		echo "<tr class='even'><td colspan='5' align='center'>" . _MD_AM_BACKUP_NONE . "</td></tr>";
	}
	echo "</table><br /><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'rollback' || $op == 'delete_backup') {
	$updater = xoops_module_github_updater();
	$info = $updater->getBackup($backup);
	icms_cp_header();
	if ($info === NULL) {
		icms_core_Message::error(sprintf(_MD_AM_BACKUP_ROLLBACKFAIL, htmlspecialchars($backup, ENT_QUOTES)));
	} elseif ($op == 'rollback') {
		$msgs = sprintf(_MD_AM_BACKUP_CONFIRM_ROLLBACK, '<strong>' . htmlspecialchars($info['dirname'], ENT_QUOTES) . '</strong>',
			htmlspecialchars($info['version'], ENT_QUOTES), formatTimestamp($info['created'], 'm'));
		icms_core_Message::confirm(array('backup' => $info['id'], 'op' => 'rollback_ok', 'fct' => 'modulesadmin'), 'admin.php', $msgs, _MD_AM_BACKUP_ROLLBACK);
	} else {
		$msgs = sprintf(_MD_AM_BACKUP_CONFIRM_DELETE, '<strong>' . htmlspecialchars($info['dirname'], ENT_QUOTES) . '</strong>', formatTimestamp($info['created'], 'm'));
		icms_core_Message::confirm(array('backup' => $info['id'], 'op' => 'delete_backup_ok', 'fct' => 'modulesadmin'), 'admin.php', $msgs, _MD_AM_BACKUP_DELETE);
	}
	icms_cp_footer();
	exit();
}

if ($op == 'rollback_ok') {
	global $icmsGithubUpdateMessages;
	$icmsGithubUpdateMessages = array();
	$updater = xoops_module_github_updater();
	$info = $updater->getBackup($backup);
	$ok = FALSE;
	if ($info !== NULL) {
		$installed = icms::handler('icms_module')->getByDirname($info['dirname']);
		$isInstalled = is_object($installed) && $installed->getVar('mid') > 0;
		$ok = $updater->rollback($info['id'],
			$isInstalled ? 'xoops_module_github_run_update' : NULL,
			xoops_module_github_tables($info['dirname']));
		if ($isInstalled) {
			$contents = impresscms_get_adminmenu();
			xoops_module_write_admin_menu($contents);
		}
	}
	icms_cp_header();
	$name = htmlspecialchars($info !== NULL ? $info['dirname'] : $backup, ENT_QUOTES);
	if ($ok) {
		icms_core_Message::result(sprintf(_MD_AM_BACKUP_ROLLEDBACK, $name));
	} else {
		icms_core_Message::error(sprintf(_MD_AM_BACKUP_ROLLBACKFAIL, $name) . '<br />' . $updater->getErrors());
	}
	echo '<p>' . $updater->getMessages() . '</p>';
	foreach ($icmsGithubUpdateMessages as $msg) {
		echo $msg;
	}
	echo "<br /><a href='admin.php?fct=modulesadmin&amp;op=backups'>" . _MD_AM_BACKUPS . "</a> | <a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'delete_backup_ok') {
	$updater = xoops_module_github_updater();
	icms_cp_header();
	if ($updater->deleteBackup($backup)) {
		icms_core_Message::result(_MD_AM_BACKUP_DELETED);
	} else {
		icms_core_Message::error(sprintf(_MD_AM_BACKUP_ROLLBACKFAIL, htmlspecialchars($backup, ENT_QUOTES)));
	}
	echo "<br /><a href='admin.php?fct=modulesadmin&amp;op=backups'>" . _MD_AM_BACKUPS . "</a>";
	icms_cp_footer();
	exit();
}

if ($op == 'remove' || $op == 'remove_ok') {
	$installed = icms::handler('icms_module')->getByDirname($module);
	$name = htmlspecialchars($module, ENT_QUOTES);
	icms_cp_header();
	if ((is_object($installed) && $installed->getVar('mid') > 0) || strtolower($module) === 'system') {
		// installed modules are uninstalled first, the regular way
		icms_core_Message::error(sprintf(_MD_AM_REMOVE_INSTALLED, $name));
	} elseif ($op == 'remove') {
		icms_core_Message::confirm(array('module' => $module, 'op' => 'remove_ok', 'fct' => 'modulesadmin'), 'admin.php',
			sprintf(_MD_AM_REMOVE_CONFIRM, '<strong>' . $name . '</strong>', htmlspecialchars(ICMS_MODULES_PATH . '/' . $module, ENT_QUOTES)), _MD_AM_REMOVE);
	} else {
		$updater = xoops_module_github_updater();
		if ($updater->removeModule($module)) {
			icms_core_Message::result(sprintf(_MD_AM_REMOVE_DONE, $name));
		} else {
			icms_core_Message::error(sprintf(_MD_AM_REMOVE_FAILED, $name) . '<br />' . $updater->getErrors());
		}
		echo '<p>' . $updater->getMessages() . '</p>';
	}
	echo "<br /><a href='admin.php?fct=modulesadmin'>" . _MD_AM_BTOMADMIN . "</a>";
	icms_cp_footer();
	exit();
}
