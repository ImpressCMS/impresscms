<?php
/**
 * Version checker, module_info file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Version
 * @since		1.0
 * @version		SVN: $Id: module_info.php 11276 2011-06-21 13:52:19Z mcdonald3072 $
 */

defined('ICMS_ROOT_PATH') || die("ImpressCMS root path not defined");

if ((int) $_GET['mid']) {
	$module_handler = icms::handler('icms_module');
	$versioninfo =& $module_handler->get($_GET['mid']);
} else {
	$mid = str_replace('..', '', trim($_GET['mid']));
	if (file_exists(ICMS_MODULES_PATH . '/' . $mid . '/icms_version.php') || file_exists(ICMS_MODULES_PATH . '/' . $mid . '/xoops_version.php')) {
		$module_handler = icms::handler('icms_module');
		$versioninfo =& $module_handler->create();
		$versioninfo->loadInfo($mid);
	}
}
if (!isset($versioninfo) || !is_object($versioninfo)) {
	exit();
}

$mImg = false;
$mName = false;

if ($modimage = $versioninfo->getInfo('image')) {
	$modimage_path = '/modules/' . $versioninfo->getInfo('dirname') . '/' . $modimage;
	$modimage_realpath = str_replace("\\", "/", realpath(ICMS_ROOT_PATH . $modimage_path));
	if (0 === strpos($modimage_realpath, ICMS_ROOT_PATH) && is_file($modimage_realpath)) {
		$mImg = ICMS_URL . $modimage_path;
	}
}
if ($modname = $versioninfo->getInfo('name')) {
	$mName = htmlspecialchars($modname);
}

$modinfo = array(_VERSION, _DESCRIPTION, _AUTHOR, _CREDITS, _LICENSE);
$modinfoMerged = array();
foreach ($modinfo as $info) {
	if ($info_output = $versioninfo->getInfo(strtolower($info))) {
		$modinfoMerged[$info] = htmlspecialchars($info_output);
	}
}

echo '<!doctype html>';
	echo '<html>';
	echo '<head>';
		echo '<title>' . $mName . '</title>';
		echo '<link rel="stylesheet" href="' . ICMS_LIBRARIES_URL . '/jscore/app/modules/uitools/media/bootstrap.css" />';
	echo '</head>';
	echo '<body>';
	  echo '<div class="well text-center">';
	    echo '<h3>' . $mName . '</h3>';
	    echo '<img src="' . $mImg . '" alt="" /><br /><br />';
	    foreach($modinfoMerged as $key => $value) {
	      echo '<strong>' . $key . ':</strong> ' . $value . '<br /><br />';
	    }
	  echo '</div>';
	echo '</body>';
echo '</html>';