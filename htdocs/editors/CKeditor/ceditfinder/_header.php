<?php	defined('ceditFinder') or die('Restricted access'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><?php if (isset($head)) {
	echo $head;
}
?><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><meta http-equiv="Content-Language" content="en" /><link rel="stylesheet" type="text/css" href="css/styles.css" /><title>Image Browser</title><style type="text/css"><?php	// Allow for thumbnail resizing - here we set the size for the thumbnail divs	$divwidth = $cfconfig['thumbwidth'] + 12; if ($divwidth < 48) {
	$divwidth = 48;
}
// Minimum width to allow buttons to be displayed	$divheight = $cfconfig['thumbheight'] + 26; echo ".thumbview {width:" . $divwidth . "px;height:" . $divheight . "px;}\r\n"; echo ".thumbviewimage {width:" . ($cfconfig['thumbwidth'] + 4) . "px;height:" . ($cfconfig['thumbheight'] + 4) . "px;}\r\n"; ?></style></head><body><script type="text/javascript" src="lib/tooltips.js"></script><div id="imagebrowser"><?php// end _header.php