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
 * icms_form_elements_Colorpicker component class file
 *
 * This class provides a textfield with a color picker popup. This color picker
 * comes from Tigra project (http://www.softcomplex.com/products/tigra_color_picker/).
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		$Id: Colorpicker.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Color Picker
 *
 * @category	ICMS
 * @package     Form
 * @subpackage	Elements
 *
 * @since		Xoops 2.0.15
 * @author		Zoullou <webmaster@zoullou.org>
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Colorpicker extends icms_form_elements_Text {

	/**
	 * Constructor
	 * @param	string  $caption  Caption of the element
	 * @param	string  $name     Name of the element
	 * @param	string  $value    Value of the element
	 */
	public function __construct($caption, $name, $value = "#FFFFFF") {
		parent::__construct($caption, $name, 9, 7, $value);
	}

	/**
	 * Render the color picker
	 * @return  $string	rendered color picker HTML
	 */
	public function render() {
		if (isset($GLOBALS ['xoTheme'])) {
			$GLOBALS ['xoTheme']->addScript('include/color-picker.js');
		} else {
			echo "<script type=\"text/javascript\" src=\"" . ICMS_URL . "/include/color-picker.js\"></script>";
		}
		$this->setExtra(' style="background-color:' . $this->getValue() . ';"');
		return parent::render() . "\n<input type='reset' value=' ... ' onclick=\"return TCP.popup('" . ICMS_URL . "/include/',document.getElementById('" . $this->getName() . "'));\">\n";
	}

	/**
	 * Returns custom validation Javascript
	 *
	 * @return	string	Element validation Javascript
	 */
	public function renderValidationJS() {
		$eltname = $this->getName();
		$eltcaption = $this->getCaption();
		$eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
		$eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
		$eltmsg = strip_tags($eltmsg);
		return "if (myform.{$eltname}.value == \"\") { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
	}

}
