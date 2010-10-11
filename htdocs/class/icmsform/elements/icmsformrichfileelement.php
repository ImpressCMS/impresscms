<?php
/**
 * Form control creating a rich file element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		  1.1
 * @author		  marcan <marcan@impresscms.org>
 * @version		$Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * @todo	This is not functionnal yet.. it needs further integration
 */

class IcmsFormRichFileElement extends icms_form_elements_Tray {
	/**
	 * Get a config
	 *
	 * @param	string  $form_caption   caption of the form element
	 * @param	string  $key            key of the variable in the passed object
	 * @param	object  $object         the passed object (target object) (@todo which object)
	 */
	function __construct($form_caption, $key, $object) {

		parent::__construct( $form_caption, '&nbsp;' );
		if($object->getVar('url') != '' ){
			$caption = $object->getVar('caption') != '' ? $object->getVar('caption') : $object->getVar('url');
			$this->addElement(new icms_form_elements_Label( '', _CO_ICMS_CURRENT_FILE."<a href='" . str_replace('{ICMS_URL}', ICMS_URL ,$object->getVar('url')) . "' target='_blank' >". $caption."</a><br/><br/>" ) );
		}

		if($object->isNew()){
			$this->addElement(new icms_ipf_form_elements_Fileupload($object, $key));
			$this->addElement(new icms_form_elements_Label('', '<br/><br/><small>'._CO_ICMS_URL_FILE_DSC.'</small>'));
			$this->addElement(new icms_form_elements_Label('','<br/>'._CO_ICMS_URL_FILE));
			$this->addElement(new icms_ipf_form_elements_Text($object, 'url_'.$key));
		}

		$this->addElement(new icms_form_elements_Label('', '<br/>'._CO_ICMS_CAPTION));
		$this->addElement(new icms_ipf_form_elements_Text($object, 'caption_'.$key));
		$this->addElement(new icms_form_elements_Label('', '<br/>'._CO_ICMS_DESC.'<br/>'));
		$this->addElement(new icms_form_elements_Textarea('', 'desc_'.$key, $object->getVar('description')));

		if(!$object->isNew()){
			$this->addElement(new icms_form_elements_Label('','<br/>'._CO_ICMS_CHANGE_FILE));
			$this->addElement(new icms_ipf_form_elements_Fileupload($object, $key));
			$this->addElement(new icms_form_elements_Label('', '<br/><br/><small>'._CO_ICMS_URL_FILE_DSC.'</small>'));
			$this->addElement(new icms_form_elements_Label('','<br/>'._CO_ICMS_URL_FILE));
			$this->addElement(new icms_ipf_form_elements_Text($object, 'url_'.$key));
		}
	}
}