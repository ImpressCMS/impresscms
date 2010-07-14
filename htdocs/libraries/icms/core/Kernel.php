<?php
/**
 * ICMS kernel Base Class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		icms_core
 * @subpackage	icms_core_Kernel
 * @since		1.1
 * @version		SVN: $Id$
 */

/**
 * Old 1.2- kernel class
 *
 * This class has been replaced by the static "icms" class, to prevent pollution of the global
 *  namespace. Please use icms::method() now, instead of $GLOBALS["impresscms"]->method();
 *
 * @category	ICMS
 * @package		icms_core
 * @subpackage	icms_core_Kernel
 * @since 		1.1
 * @deprecated
 */
class icms_core_Kernel extends icms_core_Object {

	public $paths;
	public $urls;

	public function __construct() {
		$this->paths =& icms::$paths;
		$this->urls =& icms::$urls;
	}
	/**
	 * Convert a ImpressCMS path to a physical one
	 * @param	string	$url URL string to convert to a physical path
	 * @param 	boolean	$virtual
	 * @return 	string
	 */
	public function path($url, $virtual = false) {
		return icms::path($url, $virtual);
	}
	/**
	 * Convert a ImpressCMS path to an URL
	 * @param 	string	$url
	 * @return 	string
	 */
	public function url($url) {
		return icms::url($url);
	}
	/**
	 * Build an URL with the specified request params
	 * @param 	string 	$url
	 * @param 	array	$params
	 * @return 	string
	 */
	public function buildUrl($url, $params = array()) {
		return icms::buildUrl($url,$params);
	}

}

