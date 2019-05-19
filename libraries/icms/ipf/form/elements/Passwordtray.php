<?php
/**
 * Form control creating 2 password textboxes to allow the user to enter twice his password, for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class icms_ipf_form_elements_Passwordtray extends icms_form_elements_Tray {
	private $_key;

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->getVarInfo($key);
		$control = $object->controls[$key];

		icms_loadLanguageFile('core', 'user');
		parent::__construct($var['form_caption'] . '<br />' . _US_TYPEPASSTWICE, ' ', $key . '_password_tray');

		$password_box1 = new icms_form_elements_Password('', $key . '1', 10, 32, '', false, "password_adv");
		$this->addElement($password_box1);

		$this->_key = $key;
	}

	public function render() {
		$ret = parent::render();
		$ret .= "<input class='form-control' type='password' name='" . $this->_key . "2' id='" . $this->_key . "2' "
			 .  "size='10' maxlength='32' value='' autocomplete='off' />";
		return $ret;
	}
}