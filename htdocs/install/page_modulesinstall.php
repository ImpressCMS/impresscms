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
* @since        0.5
* @author		Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
* @version		$Id$
*/

require_once 'common.inc.php';

if ( !defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'modulesinstall' );
$pageHasForm = true;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

include_once XOOPS_ROOT_PATH."/mainfile.php";
include_once "common.php";
include_once "../class/xoopsblock.php";
include_once "../kernel/module.php";
include_once "../include/cp_functions.php";
include_once './class/dbmanager.php';
include "modulesadmin.php";
$dbm =& new db_manager();

if ( !$dbm->isConnectable() ) {
	$wizard->redirectToPage( '-3' );
	exit();
}
$process = '';
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$process = 'install';
}
    
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	// If there's nothing to do: switch to next page
	if ( empty( $process ) ) {
		$wizard->redirectToPage( '+1' );
		exit();
	}
	if ( $_POST['mod'] == 1 ) {		
		$install_mods = isset($_POST['install_mods']) ? $_POST['install_mods'] : '';
		$anon_accessible_mods = isset($_POST['anon_accessible_mods']) ? $_POST['anon_accessible_mods'] : '';
		if (isset($_POST['install_mods'])){			
			for ($i = 0; $i <= count($install_mods)-1;$i++){
				$content .= xoops_module_install($install_mods[$i]);
				xoops_module_get_admin_menu();
	        }
		} else {
			$content .= _INSTALL_NO_PLUS_MOD;
		}
		//Install protector module by default if found.
		//TODO: Insert Protector installation - leads to blank page as it is now.
        /*if (file_exists(XOOPS_ROOT_PATH.'/modules/protector/xoops_version.php')) {        	
        	$content .= xoops_module_install('protector');
        	include_once "./class/mainfilemanager.php";
		    $mm = new mainfile_manager("../mainfile.php");
		    $mm->setRewrite('PROTECTOR1', 'include( XOOPS_TRUST_PATH.\'/modules/protector/include/precheck.inc.php\')');
		    $mm->setRewrite('PROTECTOR2', 'include( XOOPS_TRUST_PATH.\'/modules/protector/include/postcheck.inc.php\')');
		    
		    $result = $mm->doRewrite();
		    $mm->report();	
        }*/
   
	    $tables = array();
	    $content .= $dbm->report();
	} else {
		$wizard->redirectToPage( '+1' );
		exit();
	}
} else {
	
	$content = _INSTALL_SELECT_MODS_INTRO;
	$content .= '<br /><br /><table border="0" cellspacing="0" cellpadding="0" style="width:600px;"><tr>';
	$content .= '<td valign="top" align="center">';
	$content .= _INSTALL_SELECT_MODULES.'<br>';
	$content .= '<select name="install_mods[]"  multiple>';
	$langarr = getDirList("../modules/");
	foreach ($langarr as $lang) {
		if ($lang == 'system' || $lang == 'protector'){
			continue;
		}
		$content .= "<option value='".$lang."'";
		$content .= ' selected="selected"';
		$content .= ">".$lang."</option>";
	}
	$content .= "</select></td>";
	$content .= '<td valign="top" align="center">';
	$content .= _INSTALL_SELECT_MODULES_ANON_VISIBLE.'<br>';
	$content .= '<select name="anon_accessible_mods[]"  multiple>';
	$langarr = getDirList("../modules/");
	foreach ($langarr as $lang) {
		if ($lang == 'system' || $lang == 'protector'){
			continue;
		}
		$content .= "<option value='".$lang."'";
		if ($lang == 'contact' || $lang == 'news'){
			$content .= ' selected="selected"';
		}
		$content .= ">".$lang."</option>";
	}
	$content .= "</select></td>";
	$content .= '</tr></table>';
	$content .= '<input type="hidden" name="mod" value="1">';
}

include 'install_tpl.php';
?>