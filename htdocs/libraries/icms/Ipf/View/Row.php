<?php
declare(strict_types=1);
/**
 * Class representing a single row of a Icms\Ipf\View\Single
 *
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Ipf
 * @subpackage	View
 * @version		SVH: $Id: Row.php 10326 2010-07-11 18:54:25Z malanciault $
 */

namespace Icms\Ipf\View;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Icms\Ipf\View\Row class
 *
 * Class representing a single row of a Icms\Ipf\View\Single
 *
 * @category	ICMS
 * @package		Ipf
 * @subpackage	View
 * @author		marcan <marcan@smartfactory.ca>
 * @todo		Properly determine visibility of vars and methods and follow naming convention
 */
class Row {

	/**
	 * @var unknown_type
	 */
	public $_keyname;
	/**
	 * @var unknown_type
	 */
	public $_align;
	/**
	 * @var unknown_type
	 */
	public	$_customMethodForValue;
	/**
	 * @var unknown_type
	 */
	public $_header;
	/**
	 * @var unknown_type
	 */
	public $_class;

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

\class_alias(Row::class, 'icms_ipf_view_Row');

