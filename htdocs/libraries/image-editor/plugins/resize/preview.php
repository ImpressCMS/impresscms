<?php
$xoopsOption['nodebug'] = 1;
if (file_exists('../../../../mainfile.php')) include_once '../../../../mainfile.php';
if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

require_once ICMS_LIBRARIES_PATH . '/image-editor/include/functions.php';

icms_imageeditor_checkAccess();

use WideImage\WideImage;

/* prevent remote file inclusion / arbitrary file read */
$file = icms_imageeditor_tempPath(filter_input(INPUT_GET, 'file'));
if ($file === null) {
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
