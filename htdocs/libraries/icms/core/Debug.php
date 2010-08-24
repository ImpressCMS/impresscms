<?php
/**
 * A static class for debugging
 *
 * Using a static class instead of a include file with global functions, along with
 * autoloading of classes, reduces the memory usage and only includes files when needed.
 *
 * @category	Core
 * @package		Debug
 * @author		Steve Kenow <skenow@impresscms.org>
 * @copyright	(c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id$
 * @since		1.3
 */

/**
 * This class and its methods handle all the debug messages
 */
class icms_core_Debug {

	/* Since all the methods are static, there is no __construct necessary	 */

	/**
	 * Output a line of debug
	 * This takes the place of icms_debug()
	 *
	 * @param string $msg
	 * @param boolean $exit
	 */
	static public function message($msg, $exit = false) {
		echo "<div style='padding: 5px; color: red; font-weight: bold'>". _CORE_DEBUG . " :: " . $msg . "</div>";
		if ($exit) {
			die();
		}
	}
	/**
 	 * Output a dump of a variable
 	 * This takes the place of icms_debug_vardump()
 	 *
 	 * @param string $var
 	 */
 	static public function vardump($var) {
 		if (class_exists('icms_core_Textsanitizer')) {
			$myts = icms_core_Textsanitizer::getInstance();
			self::message($myts->displayTarea(var_export($var, true)));
 		} else {
			$var = var_export($var, true);
			$var = preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $var);
			self::message($var);
 		}
 	}

 	/**
 	 * Provides a backtrace for deprecated methods and functions, will be in the error section of debug
 	 * This takes the place of icms_deprecated()
 	 *
 	 * @param string $replacement Method or function to be used instead of the deprecated method or function
 	 * @param string $extra Additional information to provide about the change
 	 */
 	static public function setDeprecated($replacement='', $extra='') {
		icms_loadLanguageFile('core', 'core');
		$trace = debug_backtrace();
		array_shift($trace);
		$level = $msg = $message = '';
		$pre = _CORE_DEPRECATED;
		if ($trace[0]['function'] != 'include' && $trace[0]['function'] != 'include_once' && $trace[0]['function'] != 'require' && $trace[0]['function'] != 'require_once') {
			$pre .= $trace[0]['function'] . ': ';
		}

		foreach ( $trace as $step) {
		    $level .= '-';
			if (isset($step['file'])) {
			    	$message .= $level . $msg
						. (isset( $step['class'] ) ? $step['class'] : '')
						. (isset( $step['type'] ) ? $step['type'] : '' )
						. sprintf(_CORE_DEPRECATED_MSG, $step['function'], $step['file'], $step['line']);
			}
			$msg = _CORE_DEPRECATED_CALLEDBY;
		}

		trigger_error(
			$pre . ( $replacement ? sprintf(_CORE_DEPRECATED_REPLACEMENT, $replacement) : '' )
			. ( $extra ? sprintf(_CORE_DEPRECATED_EXTRA, $extra) : '' )
			. _CORE_DEPRECATED_CALLSTACK . $message
		, E_USER_NOTICE
		);
 	}
 }

