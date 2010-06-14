<?php
/**
 * Debugging functions
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @link http://impresscms.org ImpressCMS
 * @package core
 * @subpackage Debugging
 * @version	$Id$
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

/**
 * Provides a backtrace for deprecated methods and functions, will be in the error section of debug
 *
 * @since ImpressCMS 1.3
 * @package core
 * @subpackage Debugging
 * @param string $replacement Method or function to be used instead of the deprecated method or function
 * @param string $extra Additional information to provide about the change
 */
function icms_deprecated( $replacement='', $extra='' ) {
	$trace = debug_backtrace();
	array_shift( $trace );
	$level = '';
	$msg = ' <strong><em>(Deprecated)</em></strong> - ';
	foreach ( $trace as $step ) {
	    $level .= '-';
		if ( isset( $step['file'] ) ) {
		    if( $step['function'] != 'include' && $step['function'] != 'include_once' && $step['function'] != 'require' && $step['function'] != 'require_once') {
				trigger_error( $level . $msg . (isset( $step['class'] ) ? $step['class'] : '') . (isset( $step['type'] ) ? $step['type'] : '' ) . $step['function'] . ' in ' . $step['file'] . ', line ' . $step['line'] . ( $replacement ? ' <strong><em>use ' . $replacement . ' instead</em></strong>' : '' ) . ( $extra ? ' <strong><em> ' . $extra . ' </em></strong>' : '' ), E_USER_NOTICE ) ;
			}
		}
		$msg = 'Called by ';
		$replacement = '';
	}
}
