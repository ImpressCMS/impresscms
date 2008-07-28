<?php
/**
* Initiating Pagination Styles Library
*
* This file is responsible for initiating the Pagination Styles library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		TheRplima <therplima@impresscms.org>
* @version		$Id: paginationstyles.php 1742 2008-04-20 14:46:20Z real_therplima $
*/

class IcmsPreloadPaginationstyles extends IcmsPreloadItem
{
	function eventStartOutputInit() {
		if (file_exists(ICMS_LIBRARIES_PATH . '/paginationstyles/paginationstyles.php')){
		    include_once ICMS_LIBRARIES_PATH . '/paginationstyles/paginationstyles.php';
		    
		    global $xoTheme;
		    $st =& $styles;
		    foreach ($st as $style){
		    	$xoTheme->addStylesheet(ICMS_LIBRARIES_URL . '/paginationstyles/css/'.$style['fcss'].'.css', array("media" => "all"));
		    }
		}
	}
	
	function eventAdminHeader() {
		if (file_exists(ICMS_LIBRARIES_PATH . '/paginationstyles/paginationstyles.php')){
		    include ICMS_LIBRARIES_PATH . '/paginationstyles/paginationstyles.php';
		    
		    $st =& $styles;
		    $ret = '';
		    foreach ($st as $style){
		    	$ret .= '<link rel="stylesheet" type="text/css" media="all" href="'.ICMS_LIBRARIES_URL . '/paginationstyles/css/'.$style['fcss'].'.css" />'."\r\n";
		    }
		    echo $ret;
		}
	}
}
?>