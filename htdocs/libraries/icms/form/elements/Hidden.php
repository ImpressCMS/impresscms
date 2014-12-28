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
 * Creates a hidden form field
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		$Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A hidden field
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Hidden extends icms_form_Element {

	/**
	 * Value
	 * @var	string
	 */
	private $_value;

	/**
	 * Constructor
	 *
	 * @param	string	$name	"name" attribute
	 * @param	string	$value	"value" attribute
	 */
	public function __construct($name, $value) {
		$this->setName($name);
		$this->setHidden();
		$this->setValue($value);
		$this->setCaption("");
	}

	/**
	 * Get the "value" attribute
	 *
	 * @param	bool    $encode To sanitizer the text?
	 * @return	string
	 */
	public function getValue($encode = false) {
		return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
	}

	/**
	 * Sets the "value" attribute
	 *
	 * @param  $value	string
	 */
	public function setValue($value) {
		$this->_value = $value;
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string	HTML
	 */
	public function render() {
		if (is_array($this->getValue())) {
			$ret = '';
			foreach ($this->getValue() as $value){
				$ret .= "<input type='hidden' name='" . $this->getName() . "[]' id='" . $this->getName() . "' value='" . $value . "' />\n";
			}
		} else {
			$ret = "<input type='hidden' name='" . $this->getName() . "' id='" . $this->getName() . "' value='" . $this->getValue() . "' />";
		}

		return $ret;
	}
}

