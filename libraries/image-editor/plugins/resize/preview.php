<?php
use WideImage\WideImage;

$xoopsOption['nodebug'] = 1;

$file = $_GET['file'];
$width = isset($_GET['width']) ? (int) $_GET['width'] : null;
$height = isset($_GET['height']) ? (int) $_GET['height'] : null;

if (substr($width, 0, strlen($width)-1) == '%' || substr($height, 0, strlen($height)-1) == '%') {
	$fit = 'fill';
} else {
	$fit = 'inside';
}

$img = WideImage::load($file);

header('Content-type: image/png');
echo $img->resize($width, $height, $fit)->asString('png');
