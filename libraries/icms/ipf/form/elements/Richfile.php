<?php
/**
 * Form control creating a rich file element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class icms_ipf_form_elements_Richfile extends icms_form_elements_Tray {
	/**
	 * Constructor
	 * @param	icms_ipf_Object	$object	target object
	 * @param	string			$key	the key
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), "&nbsp;");
		$fileObj = $object->getFileObj($key);
		$module_handler = icms::handler("icms_module");
		$module = $module_handler->getByDirname($object->handler->_moduleName);

		if ($fileObj->getVar("url") != "") {
			$this->addElement(new icms_form_elements_Label("", _CO_ICMS_CURRENT_FILE . $fileObj->render() . "<br /><br />"));
		}

		if ($fileObj->isNew()) {
			$this->addElement(new icms_ipf_form_elements_Fileupload($fileObj, $key));
			$this->addElement(new icms_form_elements_Label("", "<br /><br /><small>" . _CO_ICMS_URL_FILE_DSC . "</small>"));
			$this->addElement(new icms_form_elements_Label("", "<br />" . _CO_ICMS_URL_FILE));
			$this->addElement(new icms_ipf_form_elements_Text($fileObj, "url_" . $key));
		}

		$this->addElement(new icms_form_elements_Hidden("mid_" . $key, $module->getVar("mid")));
		$this->addElement(new icms_form_elements_Label("", "<br />" . _CO_ICMS_CAPTION));
		$this->addElement(new icms_ipf_form_elements_Text($fileObj, "caption_" . $key));
		$this->addElement(new icms_form_elements_Label("", "<br />" . _CO_ICMS_DESC));
		$this->addElement(new icms_ipf_form_elements_Text($fileObj, "desc_" . $key));

		if (!$fileObj->isNew()) {
			$this->addElement(new icms_form_elements_Label("", "<br />" . _CO_ICMS_CHANGE_FILE));
			$this->addElement(new icms_ipf_form_elements_Fileupload($fileObj, $key));
			$this->addElement(new icms_form_elements_Label("", "<br /><br /><small>" . _CO_ICMS_URL_FILE_DSC . "</small>"));
			$this->addElement(new icms_form_elements_Label("", "<br />" . _CO_ICMS_URL_FILE));
			$this->addElement(new icms_ipf_form_elements_Text($fileObj, "url_" . $key));
		}
	}
}