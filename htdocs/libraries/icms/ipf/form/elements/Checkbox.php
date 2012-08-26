<?php
/**
 * Form control creating a checkbox element for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_ipf_form_elements_Checkbox extends icms_form_elements_Checkbox {
	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->vars[$key]['form_caption'], $key, $object->getVar($key));
		$control = $object->getControl($key);
		
		if (isset($control['options'])) {
			$this->addOptionArray($control['options']);
		} else {
			// let's find if the method we need to call comes from an already defined object
			if (isset($control['object'])) {
				if (method_exists($control['object'], $control['method'])) {
					if ($option_array = $control['object']->$control['method']()) {
						// Adding the options array to the select element
						$this->addOptionArray($option_array);
					}
				}
			} else {
				// finding the itemHandler; if none, let's take the itemHandler of the $object
				if (isset($control['itemHandler'])) {
					if (!isset($control['module'])) {
						// Creating the specified core object handler
						$control_handler = icms::handler($control['itemHandler']);
					} else {
						$control_handler =& icms_getModuleHandler($control['itemHandler'], $control['module']);
					}
				} else {
					$control_handler =& $object->handler;
				}

				// Checking if the specified method exists
				if (method_exists($control_handler, $control['method'])) {
					$option_array = call_user_func_array(array($control_handler, $control['method']),
						isset($control['params']) ? $control['params'] : array());
					if (is_array($option_array) && count($option_array) > 0) {
						// Adding the options array to the select element
						$this->addOptionArray($option_array);
					}
				}
			}
		}
	}

	/**
	 * prepare HTML for output
	 *
	 * @return	string  $ret  the constructed input form element string
	 */
	public function render() {
		$ret = "";
		if (count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) != "[]") {
			$newname = $this->getName() . "[]";
			$this->setName($newname);
		}
		foreach ($this->getOptions() as $value => $name) {
			$ret .= "<input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
			if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
				$ret .= " checked='checked'";
			}
			$ret .= $this->getExtra() . " />" . $name . "<br/>";
		}
		return $ret;
	}

	/**
	 * Creates validation javascript
	 * @return	string    $js   the constructed javascript
	 */
	public function renderValidationJS() {
		$js = "";
		$js .= "var hasSelections = false;";
		$eltname = $this->getName();
		$eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
		$eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
		if (strpos($eltname, '[') === false) $eltname = $eltname . "[]";
		$js .=
		"for (var i = 0; i < myform['" . $eltname . "'].length; i++) {
			if (myform['" . $eltname . "'][i].checked) {
				hasSelections = true;
			}
		}
		if (hasSelections == false) {
			window.alert(\"{$eltmsg}\");
			myform['" . $eltname . "'][0].focus();
			return false;
		}\n";

		return $js;
	}
}