<?php
/**
 * Images Manager - Image Crop Tool
 *
 * Crops an image
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package core
 * @since 1.2
 */
$xoopsOption['nodebug'] = 1;

/* 2 critical parameters must exist - and must be safe */
$image_path = filter_input(INPUT_GET, 'image_path', FILTER_SANITIZE_STRING);
$image_url = filter_input(INPUT_GET, 'image_url', FILTER_SANITIZE_URL);

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

if (!isset($image_path) || !isset($image_url)) {
	echo "alert('" . _ERROR . "');";
} else {
	include_once ICMS_LIBRARIES_PATH . '/wideimage/lib/WideImage.php';

	$x = (int) $_GET['x'];
	$y = (int) $_GET['y'];
	$width = (int) $_GET['width'];
	$height = (int) $_GET['height'];

	$percentSize = (int) $_GET['percentSize'];
	$save = isset($_GET['save']) ? (int) $_GET['save'] : 0;
	$del = isset($_GET['delprev']) ? (int) $_GET['delprev'] : 0;

	if ($percentSize > 200) {
		$percentSize = 200;
	}

	$img = WideImage::load($image_path);
	$arr = explode('/', $image_path);
	$arr[count($arr) - 1] = 'crop_' . $arr[count($arr) - 1];
	$temp_img_path = implode('/', $arr);
	$arr = explode('/', $image_url);
	$arr[count($arr) - 1] = 'crop_' . $arr[count($arr) - 1];
	$temp_img_url = implode('/', $arr);

	if ($del) {
		@unlink($temp_img_path);
		exit();
	}

	if (strlen($x) && strlen($y) && $width && $height && $percentSize) {
		if ($percentSize !== "100") {
			$x = $x * ($percentSize / 100);
			$y = $y * ($percentSize / 100);
			$width = $width * ($percentSize / 100);
			$height = $height * ($percentSize / 100);
		}
		$img->crop($x, $y, $width, $height)->saveToFile($temp_img_path);

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
			echo "var w = window.open('" . $temp_img_url . "','crop_image_preview','width=" . ($width + 20) . ",height=" . ($height + 20) . ",resizable=yes');";
			echo "w.onunload = function (){crop_delpreview();}";
		}
	}
}
