<?php
/**
 * Authentication classes, Base class file
 *
 * defines abstract authentification wrapper class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Authentication
 * @version		SVN: $Id: Object.php 11731 2012-06-17 01:25:04Z skenow $
 */

/**
 * Authentication base class
 *
 * @category	ICMS
 * @package     Authentication
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 */
class icms_auth_Object {

	private $_dao;

	private $_errors;

	/**
	 * Authentication Service constructor
	 */
	public function __construct() {}

	/**
	 * authenticate
	 *
	 * @abstract need to be written in the derived class
	 * @return bool whether user is authenticated
	 * @todo	Cannot declare this as abstract until the OpenID method is compliant
	 */
	public function authenticate($uname, $pwd = null) {}

	/**
	 * add an error
	 *
	 * @param string $value error to add
	 * @access public
	 */
	public function setErrors($err_no, $err_str) {
		$this->_errors[$err_no] = trim($err_str);
	}

	/**
	 * return the errors for this object as an array
	 *
	 * @return array an array of errors
	 * @access public
	 */
	public function getErrors() {
		return $this->_errors;
	}

	/**
	 * return the errors for this object as html
	 *
	 * @return string $ret html listing the errors
	 * @access public
	 */
	public function getHtmlErrors() {
		global $icmsConfigPersona;
		$ret = '<br />';
		// @todo	is this the only time you'll see the error messages?
		if ($icmsConfigPersona['debug_mode'] < 3) {
			$ret .= _US_INCORRECTLOGIN;
		} else {
			if (empty($this->_errors)) {
				$ret .= _NONE . '<br />';
			} else {
				foreach ($this->_errors as $errno => $errstr) {
					$ret .=  $errstr . '<br/>';
				}
			$ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $this->auth_method);
			}
		}
		return $ret;
	}
}
