<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

use ImpressCMS\Core\IPF\AbstractModel;
use ImpressCMS\Core\View\Form\AbstractFormElement;

/**
 * Form control creating a section in a form for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class FormSectionElement extends AbstractFormElement {
	/**
	 * @var string
	 * @access	private
	 */
	private $_value;

	/**
	 *
	 * @var bool
	 * @access private
	 */
	private $_close;

	/**
	 * Constructor
	 *
	 * @param	AbstractModel	$object	reference to targetobject
	 * @param	string			$key	name of the form section
	 */
	public function __construct($object, $key) {
		$control = $object->getControl($key);

		$this->setName($key);
		$this->_value = $object->getVarInfo($key)['value'];
		$this->_close = isset($control['close'])?$control['close']:false;
	}

	/**
	 * Get the text
	 *
	 * @return	string
	 */
	public function getValue() {
		return $this->_value;
	}

	/**
	 * Get the section type (opener / closer)
	 *
	 * @return bool
	 */
	public function isClosingSection() {
		return $this->_close;
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string
	 */
	public function render() {
		if ($this->_close) {
			return;
		}
		return $this->getValue();
	}
}