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

require_once ICMS_LIBRARIES_PATH . '/image-editor/include/functions.php';

icms_imageeditor_checkAccess();
icms_imageeditor_checkToken(false);

use WideImage\WideImage;

/* 3 critical parameters must exist - and must be safe */
$image_path = icms_imageeditor_tempPath(filter_input(INPUT_GET, 'image_path'));
$image_url = filter_input(INPUT_GET, 'image_url', FILTER_SANITIZE_URL);

/* compare URL to ICMS_URL - it should be a full URL and within the domain, without traversal */
$submitted_url = parse_url($image_url);
$base_url = parse_url(ICMS_URL); // icms::$urls not available?
if ($submitted_url['scheme'] != $base_url['scheme']) $image_url = null;
if ($submitted_url['host'] != $base_url['host']) $image_url = null;
if ($submitted_url['path'] != parse_url(ICMS_IMANAGER_FOLDER_URL . '/temp/' . basename((string) $image_path), PHP_URL_PATH)) $image_url = null;

$filter = filter_input(INPUT_GET, 'filter');
if (!in_array($filter, icms_imageeditor_filters(), true)) {
	$filter = null;
}

if (!isset($image_path) || !isset($image_url)) {
	echo "alert('" . _ERROR . "');";
} else {
	/*
	 * this goes here instead of the initial conditions
	 * because errors occur when previewing the filter effect
	 */
	if (!isset($filter)) exit();

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

	$save = isset($_GET['save']) ? (int) $_GET['save'] : 0;
	$del = isset($_GET['delprev']) ? (int) $_GET['delprev'] : 0;

	$img = WideImage::load($image_path);
	$temp_img_path = dirname($image_path) . DIRECTORY_SEPARATOR . 'filter_' . basename($image_path);
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
