<?php
/**
 * module files can include this file for admin authorization
 * the file that will include this file must be located under xoops_url/modules/module_directory_name/admin_directory_name/
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by underdog <underdog@impresscms.org>
 * @version	$Id$
 */
// Make sure the kernel launches the module in admin mode and checks the correct permissions
define('ICMS_IN_ADMIN', 1);

//error_reporting(0);
/** Load the mainfile */
include_once '../../../mainfile.php';
/** Load the admin functions */
include_once ICMS_ROOT_PATH . '/include/cp_functions.php';

// include the default language file for the admin interface
icms_loadLanguageFile($icmsModule->getVar('dirname'), 'admin');

