<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\IPF\AbstractDatabaseModel;

/**
 * Form control creating a textbox for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class TextElement extends \ImpressCMS\Core\View\Form\Elements\TextElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to target object
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		 $var = $object->getVarInfo($key);

		if (isset($object->controls[$key])) {
			$control = $object->controls[$key];
			$form_maxlength = $control['maxlength'] ?? $var['maxlength'] ?? 255;
			$form_size = $control['size'] ?? 50;
		} else {
			$form_maxlength = 255;
			$form_size = 50;
		}

		parent::__construct($var['form_caption'] ?? '', $key,
			$form_size, $form_maxlength, $object->getVar($key, 'e'));
	}
}