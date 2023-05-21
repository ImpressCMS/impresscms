<?php
// $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu) //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project //
// ------------------------------------------------------------------------- //
/**
 * Manage Objects
 *
 * @copyright Copyright (c) 2000 XOOPS.org
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @license LICENSE.txt
 * @category ICMS
 * @package Core
 * @version SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * #@+
 * Object datatype
 */
define('XOBJ_DTYPE_TXTBOX', 1);
define('XOBJ_DTYPE_TXTAREA', 2);
define('XOBJ_DTYPE_INT', 3);
define('XOBJ_DTYPE_URL', 4);
define('XOBJ_DTYPE_EMAIL', 5);
define('XOBJ_DTYPE_ARRAY', 6);
define('XOBJ_DTYPE_OTHER', 7);
define('XOBJ_DTYPE_SOURCE', 8);
define('XOBJ_DTYPE_STIME', 9);
define('XOBJ_DTYPE_MTIME', 10);
define('XOBJ_DTYPE_LTIME', 11);

define('XOBJ_DTYPE_SIMPLE_ARRAY', 101);
define('XOBJ_DTYPE_CURRENCY', 200);
define('XOBJ_DTYPE_FLOAT', 201);
define('XOBJ_DTYPE_TIME_ONLY', 202);
define('XOBJ_DTYPE_URLLINK', 203);
define('XOBJ_DTYPE_FILE', 204);
define('XOBJ_DTYPE_IMAGE', 205);
define('XOBJ_DTYPE_FORM_SECTION', 210);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', 211);

/**
 * #@-
 */

/**
 * Base class for all objects in the kernel (and beyond)
 *
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category ICMS
 * @package Core
 *
 * @since XOOPS
 * @author Kazumi Ono (AKA onokazu)
 * @copyright copyright (c) 2000-2003 XOOPS.org
 *            You should have received a copy of XOOPS_copyrights.txt with
 *            this file. If not, you may obtain a copy from xoops.org
 */
class icms_core_Object {

	/**
	 * holds all variables(properties) of an object
	 *
	 * @var array
	 * @access public
	 */
	public $vars = array();

	/**
	 * variables cleaned for store in DB
	 *
	 * @var array
	 * @access public
	 */
	public $cleanVars = array();

	/**
	 * is it a newly created object?
	 *
	 * @var bool
	 * @access private
	 */
	private $_isNew = false;

	/**
	 * is it a newly created config object?
	 *
	 * @var bool
	 * @access protected
	 */
	protected $_isNewConfig = false;

	/**
	 * has any of the values been modified?
	 *
	 * @var bool
	 * @access private
	 */
	private $_isDirty = false;

	/**
	 * errors
	 *
	 * @var array
	 * @access private
	 */
	private $_errors = array();

	/**
	 * additional filters registered dynamically by a child class object
	 *
	 * @access private
	 */
	private $_filters = array();

	/**
	 * constructor
	 *
	 * normally, this is called from child classes only
	 *
	 * @access public
	 */
	public function __construct() {}

	/**
	 * #@+
	 * used for new/clone objects
	 *
	 * @access public
	 */
	public function setNew() {
		$this->_isNew = true;
	}

	public function unsetNew() {
		$this->_isNew = false;
	}

	public function isNew() {
		return $this->_isNew;
	}

	/**
	 * #@-
	 */

	/**
	 * #@+
	 * used for new config objects when installing/updating module(s)
	 *
	 * @access public
	 */
	public function setNewConfig() {
		$this->_isNewConfig = true;
	}

	public function unsetNewConfig() {
		$this->_isNewConfig = false;
	}

	public function isNewConfig() {
		return $this->_isNewConfig;
	}

	/**
	 * #@-
	 */

	/**
	 * #@+
	 * mark modified objects as dirty
	 *
	 * used for modified objects only
	 *
	 * @access public
	 */
	public function setDirty() {
		$this->_isDirty = true;
	}

	public function unsetDirty() {
		$this->_isDirty = false;
	}

	public function isDirty() {
		return $this->_isDirty;
	}

	/**
	 * #@-
	 */

	/**
	 * initialize variables for the object
	 *
	 * @access public
	 * @param string $key
	 * @param int $data_type set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
	 * @param mixed
	 * @param bool $required require html form input?
	 * @param int $maxlength for XOBJ_DTYPE_TXTBOX type only
	 * @param string $option does this data have any select options?
	 */
	public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '') {
		$this->vars[$key] = array('value' => $value, 'required' => $required, 'data_type' => $data_type, 'maxlength' => $maxlength, 'changed' => false, 'options' => $options);
	}

	/**
	 * assign a value to a variable
	 *
	 * @access public
	 * @param string $key name of the variable to assign
	 * @param mixed $value value to assign
	 */
	public function assignVar($key, $value) {
		if (isset($value) && isset($this->vars[$key])) {
			$this->vars[$key]['value'] = &$value;
		}
	}

	/**
	 * assign values to multiple variables in a batch
	 *
	 * @access public
	 * @param array $var_array associative array of values to assign
	 */
	public function assignVars($var_arr) {
		foreach ($var_arr as $key => $value) {
			$this->assignVar($key, $value);
		}
	}

	/**
	 * assign a value to a variable
	 *
	 * @access public
	 * @param string $key name of the variable to assign
	 * @param mixed $value value to assign
	 * @param bool $not_gpc
	 */
	public function setVar($key, $value, $not_gpc = false) {
		if (!empty($key) && isset($value) && isset($this->vars[$key])) {
			$this->vars[$key]['value'] = &$value;
			$this->vars[$key]['not_gpc'] = $not_gpc;
			$this->vars[$key]['changed'] = true;
			$this->setDirty();
		}
	}

	/**
	 * assign values to multiple variables in a batch
	 *
	 * @access public
	 * @param array $var_arr associative array of values to assign
	 * @param bool $not_gpc
	 */
	public function setVars($var_arr, $not_gpc = false) {
		foreach ($var_arr as $key => $value) {
			$this->setVar($key, $value, $not_gpc);
		}
	}

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
			if ($pref == substr($key, 0, $len)) {
				$this->setVar(substr($key, $len), $value, $not_gpc);
			}
		}
	}

	/**
	 * returns all variables for the object
	 *
	 * @access public
	 * @return array associative array of key->value pairs
	 */
	public function &getVars() {
		return $this->vars;
	}

	/**
	 * Returns the values of the specified variables
	 *
	 * @param mixed $keys An array containing the names of the keys to retrieve, or null to get all of them
	 * @param string $format Format to use (see getVar)
	 * @param int $maxDepth Maximum level of recursion to use if some vars are objects themselves
	 * @return array associative array of key->value pairs
	 */
	public function getValues($keys = null, $format = 's', $maxDepth = 1) {
		if (!isset($keys)) {
			$keys = array_keys($this->vars);
		}
		$vars = array();
		foreach ($keys as $key) {
			if (isset($this->vars[$key])) {
				if (is_object($this->vars[$key]) && is_a($this->vars[$key], 'icms_core_Object')) {
					if ($maxDepth) {
						$vars[$key] = $this->vars[$key]->getValues(null, $format, $maxDepth - 1);
					}
				} else {
					$vars[$key] = $this->getVar($key, $format);
				}
			}
		}
		return $vars;
	}

	/**
	 * returns a specific variable for the object in a proper format
	 *
	 * @access public
	 * @param string $key key of the object's variable to be returned
	 * @param string $format format to use for the output
	 * @return mixed formatted value of the variable
	 */
	public function getVar($key, $format = 's') {
		$ret = $this->vars[$key]['value'];
		switch ($this->vars[$key]['data_type']) {

			case XOBJ_DTYPE_TXTBOX:
				switch (strtolower($format)) {
					case 's':
					case 'show':
					case 'e':
					case 'edit':
						return icms_core_DataFilter::htmlSpecialchars($ret);
						break 1;

					case 'p':
					case 'preview':
					case 'f':
					case 'formpreview':
						return icms_core_DataFilter::htmlSpecialchars(icms_core_DataFilter::stripSlashesGPC($ret));
						break 1;

					case 'n':
					case 'none':
					default:
						break 1;
				}
				break;

			case XOBJ_DTYPE_TXTAREA:
				switch (strtolower($format)) {
					case 's':
					case 'show':
						$html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
						$xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
						$smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
						$image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
						$br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
						if ($html && (!is_int($ret) && !empty($ret))) {
							if ($br) { // have to use this whilst ever there's a zillion editors in the core
								return icms_core_DataFilter::filterHTMLdisplay($ret, $xcode, $br);
							} else {
								return icms_core_DataFilter::checkVar($ret, 'html', 'output');
							}
						} else {
							return icms_core_DataFilter::checkVar($ret, 'text', 'output');
						}
						break 1;

					case 'e':
					case 'edit':
						return icms_core_DataFilter::checkVar($ret, 'html', 'edit');
						break 1;

					case 'p':
					case 'preview':
						$html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
						$xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
						$smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
						$image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
						$br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
						if ($html) {
							return icms_core_DataFilter::checkVar($ret, 'html', 'input');
						} else {
							return icms_core_DataFilter::checkVar($ret, 'text', 'input');
						}
						break 1;

					case 'f':
					case 'formpreview':
						$filtered = strpos($ret, '<!-- input filtered -->');
						if ($filtered !== FALSE) {
							$ret = str_replace('<!-- input filtered -->', '', $ret);
							$ret = str_replace('<!-- filtered with htmlpurifier -->', '', $ret);
						}

						return htmlspecialchars(icms_core_DataFilter::stripSlashesGPC($ret), ENT_QUOTES);
						break 1;

					case 'n':
					case 'none':
					default:
						break 1;
				}
				break;

			case XOBJ_DTYPE_ARRAY:
				$ret = unserialize($ret);
				break;

			case XOBJ_DTYPE_SOURCE:
				switch (strtolower($format)) {
					case 's':
					case 'show':
						break 1;

					case 'e':
					case 'edit':
						return icms_core_DataFilter::checkVar($ret, 'html', 'edit');
						break 1;

					case 'p':
					case 'preview':
						return icms_core_DataFilter::stripSlashesGPC($ret);
						break 1;

					case 'f':
					case 'formpreview':
						return htmlspecialchars(icms_core_DataFilter::stripSlashesGPC($ret), ENT_QUOTES);
						break 1;

					case 'n':
					case 'none':
					default:
						break 1;
				}
				break;

			default:
				if ($this->vars[$key]['options'] != '' && $ret != '') {
					switch (strtolower($format)) {
						case 's':
						case 'show':
							$selected = explode('|', $ret);
							$options = explode('|', $this->vars[$key]['options']);
							$i = 1;
							$ret = array();
							foreach ($options as $op) {
								if (in_array($i, $selected)) {
									$ret[] = $op;
								}
								$i++ ;
							}
							return implode(', ', $ret);
						case 'e':
						case 'edit':
							$ret = explode('|', $ret);
							break 1;

						default:
							break 1;
					}
				}
				break;
		}
		return $ret;
	}

	/**
	 * clean values of all variables of the object for storage.
	 * also add slashes whereever needed
	 *
	 * We had to put this method in the icms_ipf_Object because the XOBJ_DTYPE_ARRAY does not work properly
	 * at least on PHP 5.1. So we have created a new type XOBJ_DTYPE_SIMPLE_ARRAY to handle 1 level array
	 * as a string separated by |
	 *
	 * @return bool true if successful
	 * @access public
	 */
	public function cleanVars() {
		$existing_errors = $this->getErrors();
		$this->_errors = array();

		foreach ($this->vars as $k => $v) {
			$cleanv = $v['value'];
			if (!$v['changed'] || $this->_isNewConfig || (!is_int($cleanv) && empty($cleanv))) {} else {
				$cleanv = is_string($cleanv) ? trim($cleanv) : $cleanv;
				switch ($v['data_type']) {
					case XOBJ_DTYPE_TXTBOX:
						if ($v['required'] && $cleanv != '0' && $cleanv == '') {
							$this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
							break;
						}
						if (isset($v['maxlength']) && strlen($cleanv) > (int) ($v['maxlength'])) {
							$this->setErrors(sprintf(_XOBJ_ERR_SHORTERTHAN, $k, (int) $v['maxlength']));
							break;
						}
						if (!$v['not_gpc']) {
							$cleanv = icms_core_DataFilter::stripSlashesGPC(icms_core_DataFilter::censorString($cleanv));
						} else {
							$cleanv = icms_core_DataFilter::censorString($cleanv);
						}
						break;

					case XOBJ_DTYPE_TXTAREA:
						if ($v['required'] && $cleanv != '0' && $cleanv == '') {
							$this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
							break;
						}
						if (!$v['not_gpc']) {
							$cleanv = icms_core_DataFilter::stripSlashesGPC($cleanv);
							$cleanv = icms_core_DataFilter::checkVar($cleanv, 'html', 'input');
						} else {
							// $cleanv = icms_core_DataFilter::censorString($cleanv);
							$cleanv = icms_core_DataFilter::checkVar($cleanv, 'html', 'input');
						}
						break;

					case XOBJ_DTYPE_SOURCE:
						if (!$v['not_gpc']) {
							$cleanv = icms_core_DataFilter::stripSlashesGPC($cleanv);
						} else {
							$cleanv = $cleanv;
						}
						break;

					case XOBJ_DTYPE_INT:
					case XOBJ_DTYPE_TIME_ONLY:
						$cleanv = (int) $cleanv;
						break;

					case XOBJ_DTYPE_CURRENCY:
						$cleanv = icms_currency($cleanv);
						break;

					case XOBJ_DTYPE_FLOAT:
						$cleanv = icms_currency($cleanv);
						break;

					case XOBJ_DTYPE_EMAIL:
						if ($v['required'] && $cleanv == '') {
							$this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
							break;
						}
						if ($cleanv != '' && !icms_core_DataFilter::checkVar($cleanv, 'email')) {
							$this->setErrors(_CORE_DB_INVALIDEMAIL);
							break;
						}
						if (!$v['not_gpc']) {
							$cleanv = icms_core_DataFilter::stripSlashesGPC($cleanv);
						}
						break;

					case XOBJ_DTYPE_URL:
						if ($v['required'] && $cleanv == '') {
							$this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
							break;
						}
						if ($cleanv != '' && !preg_match("/^http[s]*:\/\//i", $cleanv)) {
							$cleanv = 'http://' . $cleanv;
						}
						if (!$v['not_gpc']) {
							$cleanv = icms_core_DataFilter::stripSlashesGPC($cleanv);
						}
						break;

					case XOBJ_DTYPE_SIMPLE_ARRAY:
						$cleanv = implode('|', $cleanv);
						break;

					case XOBJ_DTYPE_ARRAY:
						$cleanv = is_array($cleanv) ? serialize($cleanv) : $cleanv;
						break;

					case XOBJ_DTYPE_STIME:
					case XOBJ_DTYPE_MTIME:
					case XOBJ_DTYPE_LTIME:
						$cleanv = !is_string($cleanv) ? (int) $cleanv : strtotime($cleanv);
						if (!($cleanv > 0)) {
							$cleanv = strtotime($cleanv);
						}
						if ($cleanv === false) $cleanv = 0;
						break;

					default:
						break;
				}
			}
			$this->cleanVars[$k] = &$cleanv;
			unset($cleanv);
		}
		if (count($this->_errors) > 0) {
			$this->_errors = array_merge($existing_errors, $this->_errors);
			return false;
		}
		$this->_errors = array_merge($existing_errors, $this->_errors);
		$this->unsetDirty();
		return true;
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
	 * load all additional filters that have been registered to the object
	 *
	 * @access private
	 */
	private function _loadFilters() {}

	/**
	 * create a clone(copy) of the current object
	 *
	 * @access public
	 * @return object clone
	 */
	public function &xoopsClone() {
		$class = get_class($this);
		$clone = new $class();
		foreach ($this->vars as $k => $v) {
			$clone->assignVar($k, $v['value']);
		}
		// need this to notify the handler class that this is a newly created object
		$clone->setNew();
		return $clone;
	}

	/**
	 * add an error
	 *
	 * @param string $value error to add
	 * @access public
	 */
	public function setErrors($err_str) {
		$this->_errors[] = trim($err_str);
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
	 * @return string html listing the errors
	 * @access public
	 */
	public function getHtmlErrors() {
		$ret = '<h4>' . _ERROR . '</h4>';
		if (!empty($this->_errors)) {
			foreach ($this->_errors as $error) {
				$ret .= $error . '<br />';
			}
		} else {
			$ret .= _NONE . '<br />';
		}
		return $ret;
	}
}
