<?php
/**
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package database
 * @subpackage  mysql
 * @since	ImpressCMS 1.1
 * @author      marcan <marcan@impresscms.org>
 * @author	The ImpressCMS Project
 * @version	$Id: sqlutility.php 12403 2014-01-26 21:35:08Z skenow $
 */

/**
 * This file is deprecated. Including the real file and that's it.
 */
// For backwards compatibility
if (!isset($driver)) $driver = XOOPS_DB_TYPE;
// handle instances when XOOPS_DB_TYPE includes 'pdo.'
if (substr(XOOPS_DB_TYPE, 0, 4) == 'pdo.') $driver = substr(XOOPS_DB_TYPE, 4);
include_once ICMS_ROOT_PATH.'/class/database/drivers/'.$driver.'/sqlutility.php';
?>
