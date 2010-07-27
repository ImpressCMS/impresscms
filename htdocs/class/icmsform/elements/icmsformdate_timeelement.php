<?php
/**
 * Form control creating a DateTime Picker element for an object derived from icms_ipf_Object
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

/**
 * Create a date/time picker element for a object based on IPF
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 */
class IcmsFormDate_timeElement extends icms_form_elements_Datetime {

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->vars[$key]['form_caption'], $key, 15, $object->getVar($key, 'e'));
	}
}

