<?php
/**
 * Image Creation script
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since	XOOPS
 * @package	ICMS\Form\Elements/Captcha
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

error_reporting(0);
icms::$logger->activated = FALSE;

$image_handler = icms::handler('icms_form_elements_captcha_Image');
$image_handler->loadImage();
