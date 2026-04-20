<?php
/**
 * Form control creating 2 password textboxes to allow the user to enter twice his password, for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: Passwordtray.php 10711 2010-10-10 17:11:29Z phoenyx $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_ipf_form_elements_Passwordtray extends icms_form_elements_Tray {
	private $_key;

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key){
		$var = $object->vars[$key];
		$control = $object->controls[$key];

		icms_loadLanguageFile('core', 'user');
		parent::__construct($var['form_caption'] . '<br />' . _US_TYPEPASSTWICE, ' ', $key . '_password_tray');

		$password_box1 = new icms_form_elements_Password('', $key . '1', 10, 32, '', FALSE, "password_adv");
		$this->addElement($password_box1);

		$this->_key = $key;
	}

	public function render() {
		// Use template-based rendering instead of direct HTML generation
		$this->tpl = new icms_view_Tpl();

		// Get rendered elements from parent tray
		$tray_elements = array();
		foreach ($this->getElements() as $element) {
			$tray_elements[] = $element->render();
		}

		// Create second password field HTML
		$second_password_field = "<input type='password' name='" . $this->_key . "2' id='" . $this->_key . "2' "
			. "size='10' maxlength='32' value='' autocomplete='off' />";

		// Assign template variables
		$this->tpl->assign('tray_elements', $tray_elements);
		$this->tpl->assign('tray_delimeter', $this->getDelimeter());
		$this->tpl->assign('second_password_field', $second_password_field);

		// Use template
		$element_html_template = 'icms_form_elements_passwordtray_display.html';

		// Try file template first (for testing), then fall back to database template
		if (file_exists(ICMS_ROOT_PATH . '/templates/' . $element_html_template)) {
			return $this->tpl->fetch('file:' . ICMS_ROOT_PATH . '/templates/' . $element_html_template);
		} else {
			return $this->tpl->fetch('db:' . $element_html_template);
		}
	}
}