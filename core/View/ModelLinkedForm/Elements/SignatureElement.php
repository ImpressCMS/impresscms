<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\IPF\AbstractModel;
use ImpressCMS\Core\View\Form\Elements\DHTMLTextAreaElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;

/**
 * Form control creating a user signature textarea for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class SignatureElement extends TrayElement {
	/**
	 * Constructor
	 * @param	AbstractModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->getVarInfo($key);
		parent::__construct($var['form_caption'], '<br /><br />', $key . '_signature_tray');

		icms_loadLanguageFile('core', 'user');
		$signature_textarea = new DHTMLTextAreaElement('', $key, $object->getVar($key, 'e'));
		$this->addElement($signature_textarea);
		$attach_checkbox = new \ImpressCMS\Core\View\Form\Elements\CheckboxElement('', 'attachsig', $object->getVar('attachsig', 'e'));
		$attach_checkbox->addOption(1, _US_SHOWSIG);
		$this->addElement($attach_checkbox);
	}
}