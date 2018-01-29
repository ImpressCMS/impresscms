<?php
/**
 * Images Manager - Image Filter Tool
 *
 * Applies filters to an image
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package core
 * @since 1.2
 */
$xoopsOption['nodebug'] = 1;
require_once '../../../../mainfile.php';

/* 3 critical parameters must exist - and must be safe */
$image_path = filter_input(INPUT_GET, 'image_path', FILTER_SANITIZE_STRING);
$image_url = filter_input(INPUT_GET, 'image_url', FILTER_SANITIZE_URL);
$filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);

/* prevent remote file inclusion */
$valid_path = ICMS_IMANAGER_FOLDER_PATH . '/temp';
if (!empty($image_path) && strncmp(realpath($image_path), strlen($valid_path)) == 0) {
	$image_path = realpath($image_path);
} else {
	$image_path = null;
}

/* compare URL to ICMS_URL - it should be a full URL and within the domain, without traversal */
$submitted_url = parse_url($image_url);
$base_url = parse_url(ICMS_URL); // icms::$urls not available?
if ($submitted_url['scheme'] != $base_url['scheme']) $image_url = null;
if ($submitted_url['host'] != $base_url['host']) $image_url = null;
if ($submitted_url['path'] != parse_url(ICMS_IMANAGER_FOLDER_URL . '/temp/' . basename($image_path), PHP_URL_PATH)) $image_url = null;

/* possible filter entries */
$filters = array (
		'IMG_FILTER_NEGATE',
		'IMG_FILTER_GRAYSCALE',
		'IMG_FILTER_BRIGHTNESS',
		'IMG_FILTER_CONTRAST',
		'IMG_FILTER_COLORIZE',
		'IMG_FILTER_EDGEDETECT',
		'IMG_FILTER_EMBOSS',
		'IMG_FILTER_GAUSSIAN_BLUR',
		'IMG_FILTER_SELECTIVE_BLUR',
		'IMG_FILTER_MEAN_REMOVAL',
		'IMG_FILTER_SMOOTH',
		'IMG_FILTER_SEPIA'
);

$filter = isset($_GET['filter']) ? filter_var($_GET['filter'], FILTER_SANITIZE_STRING) : null;
if (!in_array($filter, $filters)) $filter = null;

if (!isset($image_path) || !isset($image_url)) {
	echo "alert('" . _ERROR . "');";
} else {
	/*
	 * this goes here instead of the initial conditions
	 * because errors occur when previewing the filter effect
	 */
	if (!isset($filter)) exit();

	$args = array ();

	if (isset($_GET['arg1'])) {
		$args[] = (int) $_GET['arg1'];
	}
	if (isset($_GET['arg2'])) {
		$args[] = (int) $_GET['arg2'];
	}
	if (isset($_GET['arg3'])) {
		$args[] = (int) $_GET['arg3'];
	}
	$save = isset($_GET['save']) ? (int) $_GET['save'] : 0;
	$del = isset($_GET['delprev']) ? (int) $_GET['delprev'] : 0;

	$img = WideImage::load($image_path);
	$arr = explode('/', $image_path);
	$arr[count($arr) - 1] = 'filter_' . $arr[count($arr) - 1];
	$temp_img_path = implode('/', $arr);
	$arr = explode('/', $image_url);
	$arr[count($arr) - 1] = 'filter_' . $arr[count($arr) - 1];
	$temp_img_url = implode('/', $arr);

	if ($del) {
		@unlink($temp_img_path);
		exit();
	}

	if ($filter == 'IMG_FILTER_SEPIA') {
		$img->applyFilter(IMG_FILTER_GRAYSCALE)->applyFilter(IMG_FILTER_COLORIZE, 90, 60, 30)->saveToFile($temp_img_path);
	} else {
		$img->applyFilter(constant($filter), implode(',', $args))->saveToFile($temp_img_path);
	}

	if ($save) {
		if (!@unlink($image_path)) {
			echo "alert('" . _ERROR . "');";
			exit();
		}
		if (!@copy($temp_img_path, $image_path)) {
			echo "alert('" . _ERROR . "');";
			exit();
		}
		if (!@unlink($temp_img_path)) {
			echo "alert('" . _ERROR . "');";
			exit();
		}
		echo 'window.location.reload( true );';
	} else {
		$width = $img->getWidth();
		$height = $img->getHeight();
		echo "var w = window.open('" . $temp_img_url . "','crop_image_preview','width=" . ($width + 20) . ",height=" . ($height + 20) . ",resizable=yes');";
		echo "w.onunload = function (){filter_delpreview();}";
	}
}
