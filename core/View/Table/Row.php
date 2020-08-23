<?php
/**
 * Class representing a single row of a \ImpressCMS\Core\IPF\View\ViewSingleObject
 *
 * @license		LICENSE.txt
 */

namespace ImpressCMS\Core\View\Table;

/**
 * \ImpressCMS\Core\IPF\View\ViewRow class
 *
 * Class representing a single row of a \ImpressCMS\Core\IPF\View\ViewSingleObject
 *
 * @package	ICMS\IPF\View
 * @author	marcan <marcan@smartfactory.ca>
 * @todo	Properly determine visibility of vars and methods and follow naming convention
 */
class Row {

	/**
	 * Keyname
	 *
	 * @var string
	 */
	public $_keyname = '';

	/**
	 * Align of text
	 *
	 * @var string
	 */
	public $_align = 'left';

	/**
	 * Custom method for value
	 *
	 * @var callable|null
	 */
	public	$_customMethodForValue;

	/**
	 * What do display in header?
	 *
	 * @var array
	 */
	public $_header = array();

	/**
	 * Class
	 *
	 * @var string
	 */
	public $_class = '';

	/**
	 * The constructor
	 *
	 * @param $keyname
	 * @param $customMethodForValue
	 * @param $header
	 * @param $class
	 */
	public function __construct($keyname, $customMethodForValue = false, $header = false, $class = false) {
		$this->_keyname = $keyname;
		$this->_customMethodForValue = $customMethodForValue;
		$this->_header = $header;
		$this->_class = $class;
	}

	/**
	 * Accessor for the keyname var
	 */
	public function getKeyName() {
		return $this->_keyname;
	}

	/**
	 * Accessor for the header var
	 */
	public function isHeader() {
		return $this->_header;
	}
}

