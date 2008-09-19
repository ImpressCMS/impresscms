<?php
// $Id: formtextdateselect.php 1158 2007-12-08 06:24:20Z phppp $
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
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * A text field with calendar popup
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

class XoopsFormTextDateSelect extends XoopsFormText
{


	/**
	 * Constructor
	 */
	function XoopsFormTextDateSelect($caption, $name, $size = 15, $value= 0)
	{
		$value = !is_numeric($value) ? time() : intval($value);
		$this->XoopsFormText($caption, $name, $size, 25, $value);
	}


	/**
	 * Render the Date Select
	 */
	function render()
	{
	   	$ele_name = $this->getName();
		$ele_value = $this->getValue(false);
		$jstime = formatTimestamp( $ele_value, 'Y-m-d' );
		include_once XOOPS_ROOT_PATH.'/include/calendarjs.php';
//		return "<input type='text' name='".$ele_name."' id='".$ele_name."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date("Y-m-d", $ele_value)."'".$this->getExtra()." /><input type='reset' value=' ... ' onclick='return showCalendar(\"".$ele_name."\");'>";
// Now it is time to let users use their own calendars.
		return "<input id='tmp_".$ele_name."' readonly='readonly' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".(_CALENDAR_TYPE=='jalali' ? Convertnumber2farsi(ext_date("Y-m-d", $ele_value)) : date("Y-m-d", $ele_value))."' /><input type='hidden' name='".$ele_name."' id='".$ele_name."' value='".date("Y-m-d", $ele_value)."' ".$this->getExtra()." /><script type='text/javascript'>
				Calendar.setup({
				inputField  : 'tmp_".$ele_name."',   // id of the input field
		       		ifFormat    : '%Y-%m-%d',       // format of the input field
        			langNumbers : true,
        			dateType	: '"._CALENDAR_TYPE."',
				onUpdate	: function(cal){document.getElementById('".$ele_name."').value = cal.date.print('%Y-%m-%d');},

				});
			</script>";
	}
}

?>