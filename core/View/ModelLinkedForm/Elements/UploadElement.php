<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\IPF\AbstractDatabaseModel;

/**
 * Form control creating a simple file upload element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class UploadElement extends \ImpressCMS\Core\View\Form\Elements\FileElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to target object
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVarInfo($key)['form_maxfilesize'] ?? 0);
		$this->setExtra(' size=30');
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