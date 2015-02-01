<?php
/**
 * user select with page navigation
 *
 * limit: Only works with javascript enabled
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: formselectuser.php 12278 2013-08-31 22:12:36Z fiammy $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * @package	 kernel
 * @subpackage  form
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * user select with page navigation
 *
 * @package	 kernel
 * @subpackage  form
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

class XoopsFormSelectUser extends icms_form_elements_select_User {
	private $_deprecated;
	public function __construct($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false, $showremovedusers = false, $justremovedusers = false) {
		parent::__construct($caption, $name, $include_anon, $value, $size, $multiple, $showremovedusers, $justremovedusers);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_select_User', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>
