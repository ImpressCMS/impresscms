<?
/**
* Initiating file for the third party library Scriptaculous
*
* This file is responsible for initiating the Scriptaculous library within ImpressCMS
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		marcan <marcan@impresscms.or>
* @version		$Id$
*/

function icmsLibraryScriptaculous_StartOutputInit() {
	global $xoTheme;
	$xoTheme->addScript(ICMS_LIBRARIES_URL . '/scriptaculous/src/scriptaculous.js');
}

function icmsLibraryScriptaculous_AdminHeader() {
	$ret  = '<script type="text/javascript" src="'.ICMS_LIBRARIES_URL.'/scriptaculous/src/scriptaculous.js"></script>';
	
	echo $ret;
}
?>