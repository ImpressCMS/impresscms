<?php
/**
 * Form control creating a multi selectbox for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS/IPF/Form/Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_ipf_form_elements_Selectmulti extends icms_ipf_form_elements_Select  {
	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$this->_multiple = true;
		parent::__construct($object, $key);
	}
}