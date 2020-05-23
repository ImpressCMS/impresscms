<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

/**
 * Form control creating a file upload element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @package	ICMS\IPF\Form\Elements
 */
class icms_ipf_form_elements_Fileupload extends icms_ipf_form_elements_Upload {
	/**
	 * Constructor
	 * @param	\icms_ipf_Object    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object, $key);
		// Override name for upload purposes
		$this->setName('upload_' . $key);
	}

	/**
	 * prepare HTML for output
	 *
	 * @return	string	HTML
	 */
	public function render() {
		return "<input type='hidden' name='MAX_FILE_SIZE' value='" . $this->getMaxFileSize() . "' />
		        <input type='file' name='" . $this->getName() . "' id='" . $this->getName() . "'" . $this->getExtra() . " />
		        <input type='hidden' name='icms_upload_file[]' id='icms_upload_file[]' value='" . $this->getName() . "' />";
	}
}