<?php
/**
 * Administration of images, preview file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	Administration
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

include '../../../../mainfile.php' ;
include ICMS_LIBRARIES_PATH.'/wideimage/lib/WideImage.php';

$file = $_GET['file'];
$resize = isset($_GET['resize'])?$_GET['resize']:1;
$filter = isset($_GET['filter'])?$_GET['filter']:null;
$args = array();
if (isset($_GET['arg1'])) {
	$args[] = $_GET['arg1'];
}
if (isset($_GET['arg2'])) {
	$args[] = $_GET['arg2'];
}
if (isset($_GET['arg3'])) {
	$args[] = $_GET['arg3'];
}

$image_handler = icms::handler('icms_image');
$imgcat_handler = icms::handler('icms_image_category');

$image =& $image_handler->getObjects(new icms_criteria_Item('image_name', $file),false,true);
$imagecategory =& $imgcat_handler->get($image[0]->getVar('imgcat_id'));

$categ_path = $imgcat_handler->getCategFolder($imagecategory);
$categ_url  = $imgcat_handler->getCategFolder($imagecategory,1,'url');

if ($imagecategory->getVar('imgcat_storetype') == 'db') {
	$img = WideImage::loadFromString($image[0]->getVar('image_body'));
} else {
	$path = (substr($categ_path,-1) != '/')?$categ_path.'/':$categ_path;
	$img = WideImage::load($path.$file);
}
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
			echo $img->resize(400, 300)->applyFilter(constant($filter),implode(',',$args))->asString('png');
		} else {
			echo $img->applyFilter(constant($filter),implode(',',$args))->asString('png');
		}
	}
} else {
	if ($resize && ($width > 400 || $height > 300)) {
		echo $img->resize(400, 300)->asString('png');
	} else {
		echo $img->asString('png');
	}
}

?>