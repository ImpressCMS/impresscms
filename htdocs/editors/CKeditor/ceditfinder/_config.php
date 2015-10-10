<?php
/**
 * Configuration for ceditFinder
 *
 * @category	ICMS
 * @package		Editors
 * @subpackage	CKEditor
 * @author
 * @author		Modified by ImpressCMS
 */
// This is the basic configuration for ceditFinder
// Note that config parameters may be overridden in the _auth.php file depending upon the environment
//
defined('ceditFinder') or die('Restricted access');
$mypath = dirname(__FILE__);
$path = '';
$pathtrail = explode(DIRECTORY_SEPARATOR, $mypath);
for ($i=0; $i<count($pathtrail)-3; $i++) {
	$path .= $pathtrail[$i] . DIRECTORY_SEPARATOR;
}
include_once $path . 'mainfile.php';

if (!function_exists('removeFileRoot')) {
	function removeFileRoot( $path ) {
		// Remove the fileroot from the given path to leave a relative path
		$path = str_replace( "\\", "/", $path );  // Change Windows directory separator
		return str_replace(ICMS_ROOT_PATH, "", $path);
	}
}
$cfconfig = array(
		'thumbwidth' => 100,									// Thumbnail width
		'thumbheight' => 100,									// Thumbnail height
		'imagefolder' => 'uploads/',							// Image folder (relative to fileroot and baseurl)
		'baseurl' => ICMS_URL . '/',
		'fileroot' => ICMS_ROOT_PATH . '/',
		'imagecache' => removeFileRoot(ICMS_CACHE_PATH . '/imgcache/'),
		'confirmdelete' => true									// Whether to confirm image deletion
);
// end of _config.php