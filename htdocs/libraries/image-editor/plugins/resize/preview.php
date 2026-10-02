<?php
$xoopsOption['nodebug'] = 1;
if (file_exists('../../../../mainfile.php')) include_once '../../../../mainfile.php';
if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

if (!is_object(icms::$user) || in_array(ICMS_GROUP_ANONYMOUS, icms::$user->getGroups())) {
	exit(_NOPERM);
}

$icmsModule = icms::handler('icms_module')->getByDirname('system');
if (!is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit(_NOPERM);
}

use WideImage\WideImage;

/* prevent remote file inclusion / arbitrary file read */
$valid_path = ICMS_IMANAGER_FOLDER_PATH . '/temp';
$file = isset($_GET['file']) ? $_GET['file'] : '';
if (!empty($file) && strncmp(realpath($file), $valid_path, strlen($valid_path)) == 0) {
	$file = realpath($file);
} else {
	exit(_NOPERM);
}

$width = isset($_GET['width']) ? (int) $_GET['width'] : null;
$height = isset($_GET['height']) ? (int) $_GET['height'] : null;

if (substr($width, 0, strlen($width) - 1) == '%' || substr($height, 0, strlen($height) - 1) == '%') {
	$fit = 'fill';
} else {
	$fit = 'inside';
}

$img = WideImage::load($file);

header('Content-type: image/png');
echo $img->resize($width, $height, $fit)->asString('png');
