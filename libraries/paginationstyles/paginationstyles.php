<?php
/**
* Pagination Styles Library Configuration File
*
* This file is responsible for configuration of the Pagination Styles library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.2
* @author		TheRplima <therplima@impresscms.org>
*/

$style_list = \ImpressCMS\Core\File\Filesystem::getFileList(ICMS_LIBRARIES_PATH . '/paginationstyles/css/', '', ['css'], TRUE);

foreach ($style_list as $filename) {
	$filename = str_ireplace('.css', '', $filename);
	$styles[] = array(
		'name' => ucfirst($filename),
		'fcss' => $filename,
	);
}
