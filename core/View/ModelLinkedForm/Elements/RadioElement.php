<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use icms;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;

/**
 * Form control creating a radio element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class RadioElement extends \ImpressCMS\Core\View\Form\Elements\RadioElement {

	private $_delimeter = '&nbsp;';

	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to target object
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->getVarInfo($key);

		$control = $object->getControl($key);
		if (isset($control['delimeter'])) {
			$this->_delimeter = $control['delimeter'];
		}

		parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'), $this->_delimeter);

		// Adding the options inside this Radio element
		// If the custom method is not from a module, than it's from the core

		if (isset($control['options'])) {
			$this->addOptionArray($control['options']);
		} else {
			// let's find out if the method we need to call comes from an already defined object
			if (isset($control['object'])) {
				if (method_exists($control['object'], $control['method'])) {
					if ($option_array = $control['object']->$control['method']()) {
						// Adding the options array to the Radio element
						$this->addOptionArray($option_array);
					}
				}
			} else {
				// finding the itemHandler; if none, let's take the itemHandler of the $object
				if (isset($control['itemHandler'])) {
					if (!$control['module']) {
						// Creating the specified core object handler
						$control_handler = icms::handler($control['itemHandler']);
					} else {
						$control_handler = & icms_getModuleHandler($control['itemHandler'], $control['module']);
					}
				} else {
					$control_handler = & $object->handler;
				}

				// Checking if the specified method exists
				if (method_exists($control_handler, $control['method'])) {
					// TODO : How could I pass the parameters in the following call ...
					if ($option_array = $control_handler->$control['method']()) {
						// Adding the options array to the Radio element
						$this->addOptionArray($option_array);
					}
				}
			}
		}
	}
}