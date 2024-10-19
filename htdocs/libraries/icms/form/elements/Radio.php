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
 * Creates a form radiobutton attribute (base class)
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		$Id: Radio.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A Group of radiobuttons
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Radio extends icms_form_Element {

	/**
	 * Array of Options
	 * @var	array
	 */
	private $_options = array();

	/**
	 * Pre-selected value
	 * @var	string
	 */
	private $_value = null;

	/**
	 * HTML to separate the elements
	 * @var	string
	 */
	private $_delimeter;

	/**
	 * Constructor
	 *
	 * @param	string	$caption	Caption
	 * @param	string	$name		"name" attribute
	 * @param	string	$value		Pre-selected value
	 */
	public function __construct($caption, $name, $value = null, $delimeter = "") {
		$this->setCaption($caption);
		$this->setName($name);
		if (isset($value)) {
			$this->setValue($value);
		}
		$this->_delimeter = $delimeter;
	}

	/**
	 * Get the "value" attribute
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	public function getValue($encode = false) {
		return ($encode && $this->_value !== null)
			? htmlspecialchars($this->_value, ENT_QUOTES)
			: $this->_value;
	}

	/**
	 * Set the pre-selected value
	 *
	 * @param	$value	string
	 */
	public function setValue($value) {
		$this->_value = $value;
	}

	/**
	 * Add an option
	 *
	 * @param	string	$value	"value" attribute - This gets submitted as form-data.
	 * @param	string	$name	"name" attribute - This is displayed. If empty, we use the "value" instead.
	 */
	public function addOption($value, $name = "") {
		if ($name != "") {
			$this->_options[$value] = $name;
		} else {
			$this->_options[$value] = $value;
		}
	}

	/**
	 * Adds multiple options
	 *
	 * @param	array	$options	Associative array of value->name pairs.
	 */
	function addOptionArray($options) {
		if (is_array($options)) {
			foreach ($options as $k => $v) {
				$this->addOption($k, $v);
			}
		}
	}

	/**
	 * Get an array with all the options
	 *
	 * @param	int     $encode     To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
	 * @return	array   Associative array of value->name pairs
	 */
	function getOptions($encode = false) {
		if (!$encode) {
			return $this->_options;
		}
		$value = array();
		foreach ($this->_options as $val => $name) {
			$value[$encode ? htmlspecialchars($val, ENT_QUOTES) : $val]
				= ($encode > 1) ? htmlspecialchars($name, ENT_QUOTES) : $name;
		}
		return $value;
	}

	/**
	 * Get the delimiter of this group
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string  The delimiter
	 */
	public function getDelimeter($encode = false) {
		return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimeter)) : $this->_delimeter;
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string	HTML
	 */
	public function render() {
		$ret = "";
		$ele_name = $this->getName();
		$ele_value = $this->getValue();
		$ele_options = $this->getOptions();
		$ele_extra = $this->getExtra();
		$ele_delimeter = $this->getDelimeter();
		foreach ($ele_options as $value => $name) {
			$ret .= "<input type='radio' name='" . $ele_name . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "'";
			if ($value == $ele_value) {
				$ret .= " checked='checked'";
			}
			$ret .= $ele_extra . " />" . $name . $ele_delimeter . "\n";
		}
		return $ret;
	}
}

