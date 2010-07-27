<?php
/**
 * Form control creating an image upload element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class IcmsFormLanguageElement extends icms_form_elements_select_Lang {

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {

		$var = $object->vars[$key];
		$control = $object->controls[$key];
		$all = isset($control['all']) ? true : false;

		parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'));
		if ($all) {
			$this->addOption('all', _ALL);
		}
	}
}

