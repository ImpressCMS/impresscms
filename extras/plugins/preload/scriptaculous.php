<?php
/**
* Initiating Projax library
*
* This file is responsible for initiating the Projax library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: scriptaculous.php 1742 2008-04-20 14:46:20Z malanciault $
*/

class IcmsPreloadScriptaculous extends IcmsPreloadItem
{
	function eventStartOutputInit() {
		global $xoTheme;
		$xoTheme->addScript(ICMS_LIBRARIES_URL . '/scriptaculous/src/scriptaculous.js');
	}
	
	function eventAdminHeader() {
		$ret  = '<script type="text/javascript" src="'.ICMS_LIBRARIES_URL.'/scriptaculous/src/scriptaculous.js"></script>';
		
		echo $ret;
	}
}
?>