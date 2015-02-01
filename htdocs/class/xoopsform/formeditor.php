<?php
/**
 * Creates a form editor object
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: formeditor.php 12278 2013-08-31 22:12:36Z fiammy $
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
 */
class XoopsFormEditor extends icms_form_elements_Editor {
	private $_deprecated;
	public function __construct($caption, $name, $editor_configs = null, $noHtml=false, $OnFailure = "") {
		parent::__construct($caption, $name, $editor_configs, $noHtml, $OnFailure);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_Editor', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>
