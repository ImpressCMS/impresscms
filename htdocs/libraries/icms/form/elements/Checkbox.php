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
 * Creates a checkbox form attribut
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version	$Id: Checkbox.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * One or more Checkbox(es)
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Checkbox extends icms_form_Element {

	/**
	 * Unified checkbox options array
	 * Each element contains: array('value' => string, 'label' => string, 'checked' => boolean)
	 * @var array
	 */
	private $_checkboxOptions = array();

	/**
	 * @deprecated Use _checkboxOptions instead. Kept for backward compatibility.
	 * Available options
	 * @var array
	 */
	private $_options = array();

	/**
	 * @deprecated Use _checkboxOptions instead. Kept for backward compatibility.
	 * pre-selected values in array
	 * @var	array
	 */
	private $_value = array();

	/**
	 * HTML to separate the elements
	 * @var	string
	 */
	private $_delimeter;

	/**
	 * Constructor
	 *
	 * @param	string  $caption
	 * @param	string  $name
	 * @param	mixed   $value  Either one value as a string or an array of them.
	 */
	public function __construct($caption, $name, $value = null, $delimeter = "&nbsp;") {
		$this->setCaption($caption);
		$this->setName($name);
		$this->_checkboxOptions = array();
		if (isset($value)) {
			$this->setValue($value);
		}
		$this->_delimeter = $delimeter;
	}

	/**
	 * Get the "value" - returns array of checked option values
	 *
	 * @param	bool    $encode   Would you like to sanitize the text?
	 * @return	array
	 */
	public function getValue($encode = false) {
		$checkedValues = array();
		foreach ($this->_checkboxOptions as $option) {
			if ($option['checked']) {
				$checkedValues[] = $encode ? htmlspecialchars($option['value'], ENT_QUOTES) : $option['value'];
			}
		}

		// Backward compatibility: also populate _value array
		$this->_value = $checkedValues;

		return $checkedValues;
	}

	/**
	 * Set the "value" - marks specified option values as checked
	 *
	 * @param	mixed $value Single value or array of values to mark as checked
	 */
	public function setValue($value) {
		// Convert single value to array for consistent processing
		$valuesToCheck = is_array($value) ? $value : array($value);

		// Update checked state in unified options array
		foreach ($this->_checkboxOptions as &$option) {
			$option['checked'] = in_array($option['value'], $valuesToCheck);
		}

		// Backward compatibility: also update _value array
		$this->_value = $valuesToCheck;
	}

	/**
	 * Add an option
	 *
	 * @param	string  $value Option value
	 * @param	string  $name  Option label (defaults to value if empty)
	 */
	public function addOption($value, $name = "") {
		$label = ($name != "") ? $name : $value;

		// Add to unified options array
		$this->_checkboxOptions[] = array(
			'value' => $value,
			'label' => $label,
			'checked' => in_array($value, $this->_value)
		);

		// Backward compatibility: also update _options array
		$this->_options[$value] = $label;
	}

	/**
	 * Add multiple Options at once
	 *
	 * @param	array   $options    Associative array of value->name pairs
	 */
	public function addOptionArray($options) {
		if (is_array($options)) {
			foreach ($options as $k => $v) {
				$this->addOption($k, $v);
			}
		}
	}

	/**
	 * Get an array with all the options
	 *
	 * @param	int     $encode     To sanitize the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
	 * @return	array   Associative array of value->name pairs
	 */
	public function getOptions($encode = false) {
		$options = array();
		foreach ($this->_checkboxOptions as $option) {
			$val = $encode ? htmlspecialchars($option['value'], ENT_QUOTES) : $option['value'];
			$name = ($encode > 1) ? htmlspecialchars($option['label'], ENT_QUOTES) : $option['label'];
			$options[$val] = $name;
		}

		// Backward compatibility: also update _options array
		if (!$encode) {
			$this->_options = $options;
		}

		return $options;
	}

	/**
	 * Get the unified checkbox options array
	 *
	 * @param	bool    $encode To sanitize the text?
	 * @return	array   Array of checkbox options with value, label, and checked state
	 */
	public function getCheckboxOptions($encode = false) {
		if (!$encode) {
			return $this->_checkboxOptions;
		}

		$encodedOptions = array();
		foreach ($this->_checkboxOptions as $option) {
			$encodedOptions[] = array(
				'value' => htmlspecialchars($option['value'], ENT_QUOTES),
				'label' => htmlspecialchars($option['label'], ENT_QUOTES),
				'checked' => $option['checked']
			);
		}
		return $encodedOptions;
	}

	/**
	 * Get the delimiter of this group
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string  The delimiter
	 */
	public function getDelimeter($encode = false) {
		return $encode
				? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimeter))
				: $this->_delimeter;
	}

	/**
	 * prepare HTML for output
	 *
	 * @return    string
	 */
	public function render() {
		$ele_name = $this->getName();
		$ele_value = $this->getValue();
		$ele_options = $this->getOptions();
		$ele_checkbox_options = $this->getCheckboxOptions();
		$ele_extra = $this->getExtra();
		$ele_delimeter = $this->getDelimeter();

		// Add array notation to name if multiple options exist
		if (count($ele_checkbox_options) > 1 && substr($ele_name, -2, 2) != "[]") {
			$ele_name = $ele_name . "[]";
			$this->setName($ele_name);
		}

		$this->tpl = new icms_view_Tpl();
		$this->tpl->assign('ele_name', $ele_name);
		$this->tpl->assign('ele_id', $ele_name);
		$this->tpl->assign('ele_value', $ele_value);
		$this->tpl->assign('ele_options', $ele_options);
		$this->tpl->assign('ele_checkbox_options', $ele_checkbox_options);
		$this->tpl->assign('ele_extra', $ele_extra);
		$this->tpl->assign('ele_delimeter', $ele_delimeter);

		$element_html_template = $this->customTemplate ? $this->customTemplate : 'icms_form_elements_checkbox_display.html';

		// Try file template first (for testing), then fall back to database template
		$templatePath = defined('ICMS_ROOT_PATH') ? ICMS_ROOT_PATH . '/templates/' . $element_html_template : null;
		if ($templatePath && file_exists($templatePath)) {
		    return $this->tpl->fetch('file:' . $templatePath);
		}
		return $this->tpl->fetch('db:' . $element_html_template);
	}

	/**
	 * Backward compatibility methods
	 */

	/**
	 * Get legacy options array (for backward compatibility)
	 * @deprecated Use getCheckboxOptions() instead
	 * @return array
	 */
	public function getLegacyOptions() {
		return $this->_options;
	}

	/**
	 * Get legacy value array (for backward compatibility)
	 * @deprecated Use getValue() instead
	 * @return array
	 */
	public function getLegacyValue() {
		return $this->_value;
	}

	/**
	 * Set options using legacy format (for backward compatibility)
	 * @deprecated Use addOption() or addOptionArray() instead
	 * @param array $options
	 */
	public function setLegacyOptions($options) {
		$this->_options = $options;
		$this->_checkboxOptions = array();
		foreach ($options as $value => $label) {
			$this->addOption($value, $label);
		}
	}
}
