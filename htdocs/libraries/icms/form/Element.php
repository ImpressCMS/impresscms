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
 * Creates a basic form element (Base Class)
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Element
 * @version	$Id: Element.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Abstract base class for form elements
 *
 * @category	ICMS
 * @package     Form
 * @subpackage	Elements
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @author		Taiwen Jiang    <phppp@users.sourceforge.net>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
abstract class icms_form_Element {

	/**
	 * Javascript performing additional validation of this element data
	 *
	 * This property contains a list of Javascript snippets that will be sent to
	 * icms_form_Base::renderValidationJS().
	 * NB: All elements are added to the output one after the other, so don't forget
	 * to add a ";" after each to ensure no Javascript syntax error is generated.
	 *
	 * @var array()
	 */
	public $customValidationCode = array();

	/**#@+
	 * @access private
	 */
	/**
	 * "name" attribute of the element
	 * @var string
	 */
	protected $_name;

	/**
	 * caption of the element
	 * @var	string
	 */
	protected $_caption;

	/**
	 * Accesskey for this element
	 * @var	string
	 */
	private $_accesskey = '';

	/**
	 * HTML classes for this element
	 * @var	array
	 */
	private $_class = array();

	/**
	 * hidden?
	 * @var	bool
	 */
	private $_hidden = false;

	/**
	 * extra attributes to go in the tag
	 * @var	array
	 */
	private $_extra = array();

	/**
	 * required field?
	 * @var	bool
	 */
	private $_required = FALSE;

	/**
	 * description of the field
	 * @var	string
	 */
	private $_description = "";
	/**#@-*/

	/**
	 * constructor
	 *
	 */
	public function __construct() {
		exit(_CORE_CLASSNOTINSTANIATED);
	}

	/**
	 * Is this element a container of other elements?
	 *
	 * @return	bool false
	 */
	public function isContainer() {
		return false;
	}

	/**
	 * set the "name" attribute for the element
	 *
	 * @param	string  $name   "name" attribute for the element
	 */
	public function setName($name) {
		$this->_name = trim($name);
	}

	/**
	 * get the "name" attribute for the element
	 *
	 * @param   bool    encode?
	 * @return  string  "name" attribute
	 */
	public function getName($encode = true) {
		if (false != $encode) {
			return str_replace("&amp;", "&", htmlspecialchars($this->_name, ENT_QUOTES));
		}
		return $this->_name;
	}

	/**
	 * set the "accesskey" attribute for the element
	 *
	 * @param	string  $key   "accesskey" attribute for the element
	 */
	public function setAccessKey($key) {
		$this->_accesskey = trim($key);
	}

	/**
	 * get the "accesskey" attribute for the element
	 *
	 * @return 	string  "accesskey" attribute value
	 */
	public function getAccessKey() {
		return $this->_accesskey;
	}

	/**
	 * If the accesskey is found in the specified string, underlines it
	 *
	 * @param	string  $str   String where to search the accesskey occurence
	 * @return 	string  Enhanced string with the 1st occurence of accesskey underlined
	 */
	public function getAccessString($str) {
		$access = $this->getAccessKey();
		if (!empty($access) && (false !== ($pos = strpos($str, $access)))) {
			return htmlspecialchars(substr($str, 0, $pos), ENT_QUOTES)
				. '<span style="text-decoration:underline">'
				. htmlspecialchars(substr($str, $pos, 1), ENT_QUOTES)
				. '</span>' . htmlspecialchars(substr($str, $pos+1), ENT_QUOTES);
		}
		return htmlspecialchars($str, ENT_QUOTES);
	}

	/**
	 * set the "class" attribute for the element
	 *
	 * @param	string  $key   "class" attribute for the element
	 */
	public function setClass($class) {
		$class = trim($class);
		if (!empty($class)) {
			$this->_class[] = $class;
		}
	}

	/**
	 * get the "class" attribute for the element
	 *
	 * @return 	string  "class" attribute value
	 */
	public function getClass() {
		if(empty($this->_class)) return '';
		$class = array();
		foreach ($this->_class as $class) {
			$class[] = htmlspecialchars($class, ENT_QUOTES);
		}
		return implode(" ", $class);
	}

	/**
	 * set the caption for the element
	 *
	 * @param	string  $caption
	 */
	function setCaption($caption) {
		$this->_caption = trim($caption);
	}

	/**
	 * get the caption for the element
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	public function getCaption($encode = false) {
		return $encode ? htmlspecialchars($this->_caption, ENT_QUOTES) : $this->_caption;
	}

	/**
	 * set the element's description
	 *
	 * @param	string  $description
	 */
	public function setDescription($description) {
		$this->_description = trim($description);
	}

	/**
	 * get the element's description
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	public function getDescription($encode = false) {
		return $encode
				? htmlspecialchars($this->_description, ENT_QUOTES)
				: $this->_description;
	}

	/**
	 * flag the element as "hidden"
	 *
	 */
	public function setHidden() {
		$this->_hidden = true;
	}

	/**
	 * Find out if an element is "hidden".
	 *
	 * @return	bool
	 */
	public function isHidden() {
		return $this->_hidden;
	}

	/**
	 * Find out if an element is required.
	 *
	 * @return	bool
	 */
	public function isRequired() {
		return $this->_required;
	}

	public function setRequired() {
		$this->_required = TRUE;
	}

	/**
	 * Add extra attributes to the element.
	 *
	 * This string will be inserted verbatim and unvalidated in the
	 * element's tag. Know what you are doing!
	 *
	 * @param	string  $extra
	 * @param   string  $replace If true, passed string will replace current content otherwise it will be appended to it
	 * @return	array   New content of the extra string
	 */
	public function setExtra($extra, $replace = false) {
		if ($replace) {
			$this->_extra = array(trim($extra));
		} else {
			$this->_extra[] = trim($extra);
		}
		return $this->_extra;
	}

	/**
	 * Get the extra attributes for the element
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	public function getExtra($encode = false) {
		if (!$encode) {
			return " " . implode(' ', $this->_extra);
		}
		$value = array();
		foreach ($this->_extra as $val) {
			$value[] = str_replace('>', '&gt;', str_replace('<', '&lt;', $val));
		}
		return empty($value) ? "" : " " . implode(' ', $value);
	}

	/**
	 * Render custom javascript validation code
	 *
	 * @see icms_form_Base::renderValidationJS
	 */
	public function renderValidationJS() {
		// render custom validation code if any
		if (!empty($this->customValidationCode)) {
			return implode("\n", $this->customValidationCode);
			// generate validation code if required
		} elseif ($this->isRequired()) {
			$eltname    = $this->getName();
			$eltcaption = $this->getCaption();
			$eltmsg = empty($eltcaption)
						? sprintf(_FORM_ENTER, $eltname)
						: sprintf(_FORM_ENTER, $eltcaption);
			$eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
			return "if (myform.{$eltname}.value == \"\") { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
		}
		return '';
	}

	/**
	 * Generates output for the element.
	 *
	 * This method is abstract and must be overwritten by the child classes.
	 * @abstract
	 */
	abstract public function render();
}
