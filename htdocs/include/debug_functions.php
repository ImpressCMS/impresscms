<?php
/**
 * Debugging functions
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version		$Id: page_pathsettings.php 345 2007-12-24 16:17:22Z malanciault $
 * @link http://impresscms.org ImpressCMS
 */

/**
 * Output a line of debug
 *
 * @param string $msg text to be outputed as a debug line
 * @param bool $exit if TRUE the script will end
 */
function icms_debug($msg, $exit=false)
{
	echo "<div style='padding: 5px; color: red; font-weight: bold'>debug :: $msg</div>";
	if ($exit) {
		die();
	}
}

/**
 * Output a dump of a variable
 *
 * @param string $var variable which will be dumped
 */
function icms_debug_vardump($var)
{
	if (class_exists('MyTextSanitizer')) {
		$myts = MyTextSanitizer::getInstance();
		icms_debug($myts->displayTarea(var_export($var, true)));
	} else {
		$var = var_export($var, true);
		$var = preg_replace("/(\015\012)|(\015)|(\012)/","<br />",$var);
		icms_debug($var);
	}
}
?>