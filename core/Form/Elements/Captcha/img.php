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

use ImpressCMS\Core\Form\Elements\Captcha\Image;

error_reporting(0);
icms::$logger->activated = false;

$image_handler = icms::handler(Image::class);
$image_handler->loadImage();
