<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

/**
 * Form control creating an image upload element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class LanguageElement extends icms_form_elements_select_Lang {
	/**
	 * Constructor
	 * @param	\icms_ipf_Object    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->getVarInfo($key);
		$control = $object->controls[$key];
		$all = isset($control['all'])? true : false;

		parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'));
		if ($all) {
			$this->addOption('all', _ALL);
		}
	}
}