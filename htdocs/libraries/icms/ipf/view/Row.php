<?php
/**
 * Class representing a single row of a icms_ipf_view_Single
 *
 * @license		LICENSE.txt
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * icms_ipf_view_Row class
 *
 * Class representing a single row of a icms_ipf_view_Single
 *
 * @package	ICMS\IPF\View
 * @author	marcan <marcan@smartfactory.ca>
 * @todo	Properly determine visibility of vars and methods and follow naming convention
 */
class icms_ipf_view_Row {

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

