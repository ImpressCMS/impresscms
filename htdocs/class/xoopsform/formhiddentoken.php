<?php
/**
 * Creates a hidden token form attribute
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

if (!defined('ICMS_ROOT_PATH')) {
	die("ImpressCMS root path not defined");
}

/**
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2005 XOOPS.org
 */
/**
 * A hidden token field
 *
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2005 XOOPS.org
 */
class icms_form_elements_HiddenToken extends icms_form_elements_Hidden {

	/**
	 * Constructor
	 *
	 * @param   string  $name       "name" attribute
	 * @param   int     $timeout    timeout variable for the createToken function
	 */
	function icms_form_elements_HiddenToken($name = _CORE_TOKEN, $timeout = 0){
		$this->icms_form_elements_Hidden($name . '_REQUEST', $GLOBALS['xoopsSecurity']->createToken($timeout, $name));
	}
}

?>