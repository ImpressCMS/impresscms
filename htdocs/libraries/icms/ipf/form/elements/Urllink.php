<?php
/**
 * Form control creating an element to link and URL to an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

class icms_ipf_form_elements_Urllink extends icms_form_elements_Tray {
	/**
	 * Constructor
	 * @param	string						$form_caption	the caption of the form
	 * @param	string						$key			the key
	 * @param	icms_data_urllink_Object	$object			reference to targetobject
	 */
	public function __construct($form_caption, $key, $object) {
		parent::__construct($form_caption, "&nbsp;");

		$this->addElement(new icms_form_elements_Label("", _CO_ICMS_URLLINK_URL));
		$this->addElement(new icms_ipf_form_elements_Text($object, "url_" . $key));
		$this->addElement(new icms_form_elements_Label("", "<br/>" . _CO_ICMS_CAPTION));
		$this->addElement(new icms_ipf_form_elements_Text($object, "caption_" . $key));
		$this->addElement(new icms_form_elements_Label("", "<br/>" . _CO_ICMS_DESC));
		$this->addElement(new icms_ipf_form_elements_Text($object, "desc_" . $key));
		$this->addElement( new icms_form_elements_Label("", "<br/>" . _CO_ICMS_URLLINK_TARGET));
		$targ_val = $object->getVar("target");
		$targetRadio = new icms_form_elements_Radio("", "target_" . $key, $targ_val != "" ? $targ_val : "_blank");
		$control = $object->getControl("target");
		$targetRadio->addOptionArray($control["options"]);
		$this->addElement($targetRadio);
	}
}