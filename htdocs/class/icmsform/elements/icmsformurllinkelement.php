<?php
/**
 * Form control creating an element to link and URL to an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		  1.1
 * @author		  marcan <marcan@impresscms.org>
 * @version		$Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormUrlLinkElement extends icms_form_elements_Tray {

	/**
	 * Constructor
	 * @param	object    $form_caption   the caption of the form
	 * @param	string    $key            the key
	 * @param	object    $object         reference to targetobject (@todo, which object will be passed here?)
	 */
	function __construct($form_caption, $key, $object) {
		parent::__construct($form_caption, '&nbsp;' );

		$this->addElement(new icms_form_elements_Label('', '<br/>'._CO_ICMS_URLLINK_URL));
		$this->addElement(new icms_ipf_form_elements_Text($object, 'url_'.$key));
		$this->addElement(new icms_form_elements_Label( '', '<br/>'._CO_ICMS_CAPTION));
		$this->addElement(new icms_ipf_form_elements_Text($object, 'caption_'.$key));
		$this->addElement(new icms_form_elements_Label('', '<br/>'._CO_ICMS_DESC.'<br/>'));
		$this->addElement(new icms_form_elements_Textarea('', 'desc_'.$key, $object->getVar('description')));
		$this->addElement( new icms_form_elements_Label('', '<br/>'._CO_ICMS_URLLINK_TARGET));
		$targ_val = $object->getVar('target');
		$targetRadio = new icms_form_elements_Radio('', 'target_'.$key, $targ_val!= '' ? $targ_val : '_blank');
		$control = $object->getControl('target');
		$targetRadio->addOptionArray($control['options']);

		$this->addElement($targetRadio);
	}
}

?>