<?php
/**
 * Form control creating a textbox for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class icms_ipf_form_elements_Text extends icms_form_elements_Text {
	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		 $var = $object->getVarInfo($key);

		if (isset($object->controls[$key])) {
			$control = $object->controls[$key];
			$form_maxlength = isset($control['maxlength'])?$control['maxlength']:(isset($var['maxlength'])?$var['maxlength']:255);
			$form_size = isset($control['size'])?$control['size']:50;
		} else {
			$form_maxlength = 255;
			$form_size = 50;
		}

		parent::__construct(isset($var['form_caption'])?$var['form_caption']:"", $key,
			$form_size, $form_maxlength, $object->getVar($key, 'e'));
	}
}