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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Manage Objects
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	ICMS\Core
 */

namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\Properties\AbstractProperties;

/**
 * Base class for all objects in the kernel (and beyond)
 *
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @package	ICMS\Core
 *
 * @since	XOOPS
 * @author	Kazumi Ono (AKA onokazu)
 * */
class AbstractModel extends AbstractProperties {

	/**
	 * is it a newly created object?
	 *
	 * @var bool
	 * @access private
	 */
	private $_isNew = false;

	/**
	 * errors
	 *
	 * @var array
	 * @access private
	 */
	private $_errors = [];

	/**
	 * additional filters registered dynamically by a child class object
	 *
	 * @access private
	 */
	private $_filters = [];

	/**
	 * constructor
	 *
	 * normally, this is called from child classes only
	 * @access public
	 */
	public function __construct() {

	}

	/*    * #@+
    * used for new/clone objects
     *
    * @access public
    */

	public function unsetNew() {
		$this->_isNew = false;
	}

	public function isNew() {
		return $this->_isNew;
	}

	/**
	 * initialize variables for the object
	 *
	 * @access public
	 * @param string $key
	 * @param int $data_type  set to one of self::DTYPE_XXX constants
	 * @param mixed
	 * @param bool $required  require html form input?
	 * @param int $maxlength  for self::DTYPE_STRING, self::DTYPE_INTERGER types only
	 * @param string $options  does this data have any select options?
	 */
	public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '') {
		parent::initVar($key, $data_type, $value, $required, array(
			parent::VARCFG_MAX_LENGTH => $maxlength,
			'options' => $options
				)
		);
	}

	/*    * #@- */

	/**
	 * Assign values to multiple variables in a batch
	 *
	 * Meant for a CGI context:
	 * - prefixed CGI args are considered safe
	 * - avoids polluting of namespace with CGI args
	 *
	 * @access public
	 * @param array $var_arr associative array of values to assign
	 * @param string $pref prefix (only keys starting with the prefix will be set)
	 */
	public function setFormVars($var_arr = null, $pref = 'xo_', $not_gpc = false) {
		$len = strlen($pref);
		foreach ($var_arr as $key => $value) {
			if (strpos($key, $pref) === 0) {
				$this->setVar(substr($key, $len), $value, $not_gpc);
			}
		}
	}

	/**
	 * dynamically register additional filter for the object
	 *
	 * @param string $filtername name of the filter
	 * @access public
	 */
	public function registerFilter($filtername) {
		$this->_filters[] = $filtername;
	}

	/**
	 * Clone current instance
	 *
	 * @return object
	 *
	 * @deprecated Use php function clone! Since 2.0
	 */
	public function xoopsClone() {
		trigger_error('Use php function clone!', E_USER_DEPRECATED);

		return clone $this;
	}

	/**
	 * Sets object modified
	 *
	 * @deprecated Use setVarInfo with self::VARCFG_CHANGED instead. Since 2.0
	 */
	public function setDirty() {
		trigger_error('Use setVarInfo with self::VARCFG_CHANGED instead', E_USER_DEPRECATED);

		$this->setVarInfo(null, parent::VARCFG_CHANGED, true);
	}

	/**
	 * Sets object unmodified
	 *
	 * @deprecated Use setVarInfo with self::VARCFG_CHANGED instead. Since 2.0
	 */
	public function unsetDirty() {
		trigger_error('Use setVarInfo with self::VARCFG_CHANGED instead', E_USER_DEPRECATED);

		$this->setVarInfo(null, parent::VARCFG_CHANGED, false);
	}

	/**
	 * Is object modified?
	 *
	 * @deprecated Use count($this->getChangedVars()) > 0 instead. Since 2.0
	 */
	public function isDirty() {
		trigger_error('Use count($this->getChangedVars()) > 0 instead', E_USER_DEPRECATED);

		return count($this->getChangedVars()) > 0;
	}

	/**
	 * Create cloned copy of current object
	 */
	public function __clone() {
		$this->setNew();
	}

	public function setNew() {
		$this->_isNew = true;
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
	 * add an error
	 *
	 * @param string $value error to add
	 * @access public
	 */
	public function setErrors($err_str, $prefix = false) {
		if (is_array($err_str)) {
			foreach ($err_str as $str) {
				$this->setErrors($str, $prefix);
			}
		} else {
			if ($prefix) {
				$err_str = '[' . $prefix . '] ' . $err_str;
			}
			$this->_errors[] = trim($err_str);
		}
	}

	/**
	 * return the errors for this object as html
	 *
	 * @return string html listing the errors
	 * @access public
	 */
	public function getHtmlErrors() {
		$ret = '<h4>' . _ERROR . '</h4>';
		if (empty($this->_errors)) {
			$ret .= _NONE . '<br />';
		} else {
			$ret .= implode('<br />', $this->_errors);
		}
		return $ret;
	}

	/**
	 *
	 */
	public function hasError() {
		return count($this->_errors) > 0;
	}

	/**
	 * load all additional filters that have been registered to the object
	 *
	 * @access private
	 */
	private function _loadFilters() {

	}

}
