<?php
/**
 * Creates a form editor object
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
/**
 *
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * XoopsEditor hanlder
 *
 * @author	D.J.
 * @copyright	copyright (c) 2000-2005 XOOPS.org
 *
 * @package     kernel
 * @subpackage  form
 * @todo		To be removed as this is not used anywhere in the core
 */
class XoopsFormEditor extends icms_form_elements_Editor {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_Editor', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>