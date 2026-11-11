<?php
/**
 * Serves the image of the image captcha
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 */

ob_start();
include "../../../../../mainfile.php";
icms::$logger->activated = FALSE;

$imageHandler = new \Icms\Form\Elements\Captcha\ImageHandler();
$imageHandler->loadImage();
