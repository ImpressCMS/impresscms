<?php
/**
 * Installer tables creation page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright    The ImpressCMS project http://www.impresscms.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package		installer
 * @since        1.0
 * @author		Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
 * @version		$Id$
 */
require_once 'common.inc.php';

if (!defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'modulesinstall' );
$pageHasForm = true;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

include_once "../mainfile.php";
var_dump(ICMS_ROOT_PATH);
include_once ICMS_ROOT_PATH . "/include/common.php";
include_once "../include/cp_functions.php";
include_once 'class/dbmanager.php';
include "modulesadmin.php";
$dbm = new db_manager();

if (!$dbm->isConnectable()) {
	$wizard->redirectToPage( '-3' );
	exit();
}
$process = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$process = 'install';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// If there's nothing to do: switch to next page
	if (empty( $process )) {
		$wizard->redirectToPage( '+1' );
		exit();
	}
	if ($_POST['mod'] == 1) {
		/**
		 * Automatically updating the system module before installing the selected modules
		 * @since 1.3
		 */
		$module_handler = icms::handler('icms_module');
		$system_moduleObj = $module_handler->getByDirname('system');
		include_once ICMS_ROOT_PATH . '/modules/system/include/update.php';
		icms_module_update_system($system_moduleObj);

		$install_mods = isset($_POST['install_mods']) ? $_POST['install_mods'] : '';
		$anon_accessible_mods = isset($_POST['anon_accessible_mods']) ? $_POST['anon_accessible_mods'] : '';
		if (isset($_POST['install_mods'])) {
			for ($i = 0; $i <= count($install_mods)-1;$i++) {
				$content .= xoops_module_install($install_mods[$i]);
				impresscms_get_adminmenu();
			}
		} else {
			$content .= _INSTALL_NO_PLUS_MOD;
		}

		$tables = array();
		$content .= "<div style='height:auto;max-height:400px;overflow:auto;'>".$dbm->report()."</div>";
	} else {
		$wizard->redirectToPage( '+1' );
		exit();
	}
} else {
	$langarr = icms_module_Handler::getAvailable();
	if (count($langarr) == 1 && in_array('system', $langarr)) {
		$wizard->redirectToPage( '+1' );
		exit();
	}

	$content .= '<div>'. _INSTALL_SELECT_MODS_INTRO .'</div>';
	$content .= '<div class="dbconn_line">';
	$content .= '<h3>'. _INSTALL_SELECT_MODULES.'</h3>';
	$content .= '<div id="modinstall" name="install_mods[]">';

	foreach ($langarr as $lang) {
		if ($lang == 'system') {
			continue;
		}
		$content .= "<div class=\"langselect\" style=\"text-decoration: none;\"><a href=\"javascript:void(0);\" style=\"text-decoration: none;\"><img src=\"../modules/$lang/images/icon_small.png\" alt=\"$lang\" /><br />$lang <br /><input type=\"checkbox\" checked=\"checked\" name=\"install_mods[]\" value=\"$lang\" /></a></div>";
	}
	$content .= "</div><div class='clear'>&nbsp;</div>";
	$content .= '</div>';
	$content .= '<input type="hidden" name="mod" value="1" />';
}

include 'install_tpl.php';
