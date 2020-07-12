<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

use ImpressCMS\Core\IPF\AbstractModel;

/**
 * Form control creating an advanced file upload element
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @package	ICMS\IPF\Form\Elements
 */
class FileElement extends \ImpressCMS\Core\View\Form\Elements\FileElement {
	private $_object;
	private $_key;

	/**
	 * Constructor
	 * @param	AbstractModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$this->_object = $object;
		$this->_key = $key;
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVarInfo($key)['form_maxfilesize'] ?? 0);
		$this->setExtra(' size=30');
	}

	/**
	 * prepare HTML for output
	 *
	 * @return	string	$ret  the constructed HTML
	 */
	public function render() {
		$ret = '';
		if ($value = $this->_object->getVar($this->_key)) {
			$ret .= '<div>' . _CO_ICMS_CURRENT_FILE
				. "<a href='" . $this->_object->getUploadDir() . $value
				. "' target='_blank' >" . $this->_object->getVar($this->_key) . '</a></div>';
		}

		$ret .= "<div><input type='hidden' name='MAX_FILE_SIZE' value='" . $this->getMaxFileSize() . "' />
		        <input type='file' name='" . $this->getName() . "' id='" . $this->getName() . "'" . $this->getExtra() . " />
		        <input type='hidden' name='icms_upload_file[]' id='icms_upload_file[]' value='" . $this->getName() . "' /></div>";

		return $ret;
	}
}