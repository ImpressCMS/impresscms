<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

use icms;
use ImpressCMS\Core\Form\Elements\HiddenElement;
use ImpressCMS\Core\Form\Elements\LabelElement;
use ImpressCMS\Core\Form\Elements\TrayElement;
use ImpressCMS\Core\IPF\AbstractModel;

/**
 * Form control creating a rich file element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class RichfileElement extends TrayElement {
	/**
	 * Constructor
	 * @param	AbstractModel	$object	target object
	 * @param	string			$key	the key
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), "&nbsp;");
		$fileObj = $object->getFileObj($key);
		$module_handler = icms::handler("icms_module");
		$module = $module_handler->getByDirname($object->handler->_moduleName);

		if ($fileObj->getVar("url") != "") {
			$this->addElement(new LabelElement("", _CO_ICMS_CURRENT_FILE . $fileObj->render() . "<br /><br />"));
		}

		if ($fileObj->isNew()) {
			$this->addElement(new FileUploadElement($fileObj, $key));
			$this->addElement(new LabelElement("", "<br /><br /><small>" . _CO_ICMS_URL_FILE_DSC . "</small>"));
			$this->addElement(new LabelElement("", "<br />" . _CO_ICMS_URL_FILE));
			$this->addElement(new TextElement($fileObj, "url_" . $key));
		}

		$this->addElement(new HiddenElement("mid_" . $key, $module->getVar("mid")));
		$this->addElement(new LabelElement("", "<br />" . _CO_ICMS_CAPTION));
		$this->addElement(new TextElement($fileObj, "caption_" . $key));
		$this->addElement(new LabelElement("", "<br />" . _CO_ICMS_DESC));
		$this->addElement(new TextElement($fileObj, "desc_" . $key));

		if (!$fileObj->isNew()) {
			$this->addElement(new LabelElement("", "<br />" . _CO_ICMS_CHANGE_FILE));
			$this->addElement(new FileUploadElement($fileObj, $key));
			$this->addElement(new LabelElement("", "<br /><br /><small>" . _CO_ICMS_URL_FILE_DSC . "</small>"));
			$this->addElement(new LabelElement("", "<br />" . _CO_ICMS_URL_FILE));
			$this->addElement(new TextElement($fileObj, "url_" . $key));
		}
	}
}