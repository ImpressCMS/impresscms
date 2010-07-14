<?php
/**
 * Form control creating the options of a block
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		1.2
 * @author		marcan <marcan@impresscms.org>
 * @author		phoenyx
 * @version		$Id:$
 */

if (!defined('ICMS_ROOT_PATH'))
die("ImpressCMS root path not defined");

class IcmsFormBlockoptionsElement extends icms_form_elements_Tray {

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	function IcmsFormBlockoptionsElement($object, $key) {
		$var = $object->vars[$key];
		$this->icms_form_elements_Tray($var['form_caption'], $key, $object->getVar($key, 'e'));
		$this->icms_form_elements_Tray(_CO_SYSTEM_BLOCKSADMIN_OPTIONS, ' ', 'options' . '_password_tray');
		$func = $object->getVar('edit_func');

		require_once ICMS_ROOT_PATH . "/modules/" . $object->handler->getModuleDirname($object->getVar('mid', 'e')) . "/blocks/" . $object->getVar('func_file') ;
		include_once ICMS_ROOT_PATH . "/class/xoopsformloader.php";
		icms_loadLanguageFile($object->handler->getModuleDirname($object->getVar('mid', 'e')), 'blocks');

		if (!function_exists($func)) return;
		$visible_label = new icms_form_elements_Label('', $func (explode('|', $object->getVar('options'))));
		$this->addElement($visible_label);
	}
}
?>