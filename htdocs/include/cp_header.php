<?php
/**
* module files can include this file for admin authorization
* the file that will include this file must be located under xoops_url/modules/module_directory_name/admin_directory_name/
* 
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package	core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by underdog <underdog@impresscms.org>
* @version		$Id$
*/
//error_reporting(0);
include_once '../../../mainfile.php';
include_once XOOPS_ROOT_PATH . "/include/cp_functions.php";
$moduleperm_handler = & xoops_gethandler( 'groupperm' );
if ( $xoopsUser ) {
  $url_arr = explode('/',strstr($xoopsRequestUri,'/modules/'));
  $module_handler =& xoops_gethandler('module');
  $xoopsModule =& $module_handler->getByDirname($url_arr[2]);
  unset($url_arr);

  if ( !$moduleperm_handler->checkRight( 'module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
      redirect_header( XOOPS_URL . "/user.php", 1, _NOPERM );
  }
} else {
  redirect_header( XOOPS_URL . "/user.php", 1, _NOPERM );
}

// set config values for this module
if ( $xoopsModule->getVar( 'hasconfig' ) == 1 || $xoopsModule->getVar( 'hascomments' ) == 1 ) {
  $config_handler = & xoops_gethandler( 'config' );
  $xoopsModuleConfig = & $config_handler->getConfigsByCat( 0, $xoopsModule->getVar( 'mid' ) );
}

// include the default language file for the admin interface
icms_loadLanguageFile($xoopsModule->dirname(), 'admin');

?>