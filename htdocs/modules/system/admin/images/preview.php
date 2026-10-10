<?php
/**
 * Administration of images, preview file
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @license LICENSE.txt
 * @package Administration
 * @subpackage Images
 * @version SVN: $Id: preview.php 11141 2011-03-30 13:39:21Z m0nty_ $
 */
include '../../../../mainfile.php';

require_once ICMS_LIBRARIES_PATH . '/image-editor/include/functions.php';

icms_imageeditor_checkAccess();

use WideImage\WideImage;

/*
 * GET variables
 * file
 * resize
 * filter
 * arg1
 * arg2
 * arg3
 *
 * no POST variables
 *
 */
$file = basename((string) filter_input(INPUT_GET, 'file'));
$resize = isset($_GET['resize']) ? (int) $_GET['resize'] : 1;
$filter = filter_input(INPUT_GET, 'filter');
if (!in_array($filter, icms_imageeditor_filters(), true)) {
	$filter = NULL;
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

$image_handler = icms::handler('icms_image');
$imgcat_handler = icms::handler('icms_image_category');

$image = $image_handler->getObjects(new icms_db_criteria_Item('image_name', $file), FALSE, TRUE);
if (empty($image)) {
	exit(_ERROR);
}

$imagecategory = $imgcat_handler->get($image[0]->getVar('imgcat_id'));
if (!is_object($imagecategory)) {
	exit(_ERROR);
}

$categ_path = $imgcat_handler->getCategFolder($imagecategory);
$categ_url = $imgcat_handler->getCategFolder($imagecategory, 1, 'url');

if ($imagecategory->getVar('imgcat_storetype') == 'db') {
	$img = WideImage::loadFromString($image[0]->getVar('image_body'));
} else {
	$path = (substr($categ_path, -1) != '/') ? $categ_path . '/' : $categ_path;
	$img = WideImage::load($path . $file);
}
$width = $img->getWidth();
$height = $img->getHeight();

header('Content-type: image/png');
if (NULL !== $filter) {
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
} else {
	if ($resize && ($width > 400 || $height > 300)) {
		echo $img->resize(400, 300)->asString('png');
	} else {
		echo $img->asString('png');
	}
}

