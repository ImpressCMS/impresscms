<?php
// $Id: cp_header.php 12313 2013-09-15 21:14:35Z skenow $
/**
 * module files can include this file for admin authorization
 * the file that will include this file must be located under icms_url/modules/module_directory_name/admin_directory_name/
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @author      skalpa
 * @version		SVN: $Id: cp_header.php 12313 2013-09-15 21:14:35Z skenow $
 */
// Make sure the kernel launches the module in admin mode and checks the correct permissions
define('ICMS_IN_ADMIN', 1);

/** Load the admin functions */
include_once ICMS_ROOT_PATH . '/include/cp_functions.php';

// include the default language file for the admin interface
icms_loadLanguageFile(icms::$module->getVar('dirname'), 'admin');
