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
 * Class to create a form field with a date selector
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 **/

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A text field with calendar popup
 *
 * @package	ICMS\Form\Elements
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Date extends icms_form_elements_Text {


	/**
	 * Constructor
	 *
	 * @param string	$caption
	 * @param string	$name
	 * @param int		$size
	 * @param mixed		$value
	 */
	public function __construct($caption, $name, $size = 15, $value= 0) {
		$value = !is_numeric($value) ? time() : (int) ($value);
		parent::__construct($caption, $name, $size, 25, $value);
	}

	/**
	 * Render the Date field
	 */
	public function render() {
		global $icmsConfigPersona;
		$ele_name = $this->getName();
		$ele_value = $this->getValue(false);
		$jstime = formatTimestamp($ele_value, _SHORTDATESTRING);

		include_once ICMS_ROOT_PATH . '/include/calendar' . ($icmsConfigPersona['use_jsjalali'] == true ? 'jalali' : '') . 'js.php';
		
	if ($icmsConfigPersona['use_jsjalali']) {
			include_once ICMS_ROOT_PATH . '/include/jalali.php';
		}
		
		$result = "<input type='text' class='datepick'  name='".$ele_name."' id='".$ele_name."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date(_SHORTDATESTRING, $ele_value)."'".$this->getExtra()." />";
		
		return $result;
	}
}