<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * Authentication classes, Base class file
 *
 * defines abstract authentification wrapper class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

/**
 * Authentication base class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @since       XOOPS
 * @package     ICMS\Authentication
 * @author	Pierre-Eric MENUET	<pemphp@free.fr>
 */
class icms_auth_Object {

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
					$ret .= $errstr . '<br/>';
				}
			$ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $this->auth_method);
			}
		}
		return $ret;
	}
}
