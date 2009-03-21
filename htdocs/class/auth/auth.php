<?php
// $Id: auth.php 694 2006-09-04 11:33:22Z skalpa $
// auth.php - defines abstract authentification wrapper class
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
 * Authentification base class
 *
 * @package     kernel
 * @subpackage  auth
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsAuth {

  	var	$_dao;

  	var	$_errors;


  	/**
  	 * Authentication Service constructor
  	 */
  	function XoopsAuth (&$dao){
  		$this->_dao = $dao;
  	}

  	/**
     * authenticate
     *
  	 * @abstract need to be write in the derived class
     * @return bool whether user is authenticated
  	 */
  	function authenticate() {
  		$authenticated = false;

  		return $authenticated;
  	}

    /**
     * add an error
     *
     * @param string $value error to add
     * @access public
     */
    function setErrors($err_no, $err_str)
    {
        $this->_errors[$err_no] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     * @access public
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string $ret html listing the errors
     * @access public
     */
    function getHtmlErrors()
    {
    	global $xoopsConfig;
        $ret = '<br />';
        if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 )
        {
	        if (!empty($this->_errors)) {
	            foreach ($this->_errors as $errno => $errstr) {
	                $ret .=  $errstr . '<br/>';
	            }
	        } else {
	            $ret .= _NONE.'<br />';
	        }
	        /**
	         * Fix to replace the message "Incorrect Login using xoops authenticated method"
	         * as this message don't say much to normal users...
	         * This fix of course is temporary and will change in the future
	         */
	        $auth_method_name = $this->auth_method == 'xoops' ? 'standard' : $this->auth_method;
	        $ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $auth_method_name);
        }
	    else {
	    	$ret .= _US_INCORRECTLOGIN;
	    }
        return $ret;
    }
}

?>