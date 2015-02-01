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
 * Creates a textbox form field
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: Text.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 * A simple text field
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Text extends icms_form_Element {
	/**
	 * Size
	 * @var	int
	 */
	private $_size;

	/**
	 * Maximum length of the text
	 * @var	int
	 */
	private $_maxlength;

	/**
	 * Initial text
	 * @var	string
	 */
	private $_value;

	/**
	 * Turns off the browser autocomplete function.
	 * @var 		boolean
	 */
	public $autocomplete = false;

	/**
	 * Constructor
	 *
	 * @param	string	$caption	Caption
	 * @param	string	$name       "name" attribute
	 * @param	int		$size	    Size
	 * @param	int		$maxlength	Maximum length of text
	 * @param	string  $value      Initial text
	 */
	public function __construct($caption, $name, $size, $maxlength, $value = '', $autocomplete = false) {
		$this->setCaption($caption);
		$this->setName($name);
		$this->_size = (int) $size;
		$this->_maxlength = (int) $maxlength;
		$this->setValue($value);
		$this->autoComplete = !empty($autocomplete);
	}

	/**
	 * Get size
	 *
	 * @return	int
	 */
	public function getSize() {
		return $this->_size;
	}

	/**
	 * Get maximum text length
	 *
	 * @return	int
	 */
	public function getMaxlength() {
		return $this->_maxlength;
	}

	/**
	 * Get initial content
	 *
	 * @param	bool    $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compat
	 * @return	string
	 */
	public function getValue($encode = false) {
		return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
	}

	/**
	 * Set initial text value
	 *
	 * @param	$value  string
	 */
	public function setValue($value) {
		$this->_value = $value;
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string  HTML
	 */
	public function render() {
		return "<input type='text' name='" . $this->getName()
			. "' id='" . $this->getName()
			. "' size='" . $this->getSize()
			. "' maxlength='" . $this->getMaxlength()
			. "' value='" . $this->getValue() . "'" . $this->getExtra()
			. " />";
	}
}

