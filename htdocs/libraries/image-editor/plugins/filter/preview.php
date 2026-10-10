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

$resize = isset($_GET['resize']) ? (int) $_GET['resize'] : 1;
$filter = filter_input(INPUT_GET, 'filter');
if (!in_array($filter, icms_imageeditor_filters(), true)) {
	$filter = null;
}
$args = array();
if (isset($_GET['arg1'])) {
	$args[] = (int) $_GET['arg1'];
}
if (isset($_GET['arg2'])) {
	$args[] = (int) $_GET['arg2'];
}
if (isset($_GET['arg3'])) {
	$args[] = (int) $_GET['arg3'];
}

$img = WideImage::load($file);

$width = $img->getWidth();
$height = $img->getHeight();

header('Content-type: image/png');
if (!is_null($filter)) {
	if ($filter == 'IMG_FILTER_SEPIA') {
		if ($resize && ($width > 400 || $height > 300)) {
			echo $img->resize(400, 300)->applyFilter(IMG_FILTER_GRAYSCALE)->applyFilter(IMG_FILTER_COLORIZE, 90, 60, 30)->asString('png');
		} else {
			echo $img->applyFilter(IMG_FILTER_GRAYSCALE)->applyFilter(IMG_FILTER_COLORIZE, 90, 60, 30)->asString('png');
		}
	} else {
		if ($resize && ($width > 400 || $height > 300)) {
			echo $img->resize(400, 300)->applyFilter(constant($filter), implode(',', $args))->asString('png');
		} else {
			echo $img->applyFilter(constant($filter), implode(',', $args))->asString('png');
		}
	}
}
