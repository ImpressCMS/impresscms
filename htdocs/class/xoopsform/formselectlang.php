<?php
/**
 * Creates a form attribute which is able to select a language
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

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");
/**
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * lists of values
 */
include_once ICMS_ROOT_PATH."/class/xoopslists.php";
/**
 * parent class
 */
include_once ICMS_ROOT_PATH."/class/xoopsform/formselect.php";

/**
 * A select field with available languages
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsFormSelectLang extends icms_form_elements_select_Lang {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_select_Lang', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>