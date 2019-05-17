<?php
/**
 * Image Creation script
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		XOOPS
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author		modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id: img.php 12340 2013-09-22 04:11:09Z skenow $
 */

include "../../../../../mainfile.php";
error_reporting(0);
icms::$logger->activated = FALSE;

$image_handler = icms::handler('icms_form_elements_captcha_Image');
$image_handler->loadImage();
