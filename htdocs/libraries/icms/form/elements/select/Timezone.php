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
 * Creates a form with selectable timezone
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: Timezone.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A select box with timezones
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_select_Timezone extends icms_form_elements_Select {

	/**
	 * Constructor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	$value	Pre-selected value (or array of them).
	 * 							Legal values are "-12" to "12" with some ".5"s strewn in ;-)
	 * @param	int		$size	Number of rows. "1" makes a drop-down-box.
	 */
	public function __construct($caption, $name, $value = null, $size = 1) {
		parent::__construct($caption, $name, $value, $size);
		$this->addOptionArray(self::getTimeZoneList());
	}

	/**
	 * Create an array of timezones, translated by the local language files
	 */
	static public function getTimeZoneList() {
		icms_loadLanguageFile('core', 'timezone');
		$time_zone_list = array (
			"-12" => _TZ_GMTM12,
			"-11" => _TZ_GMTM11,
			"-10" => _TZ_GMTM10,
			"-9" => _TZ_GMTM9,
			"-8" => _TZ_GMTM8,
			"-7" => _TZ_GMTM7,
			"-6" => _TZ_GMTM6,
			"-5" => _TZ_GMTM5,
			"-4" => _TZ_GMTM4,
			"-3.5" => _TZ_GMTM35,
			"-3" => _TZ_GMTM3,
			"-2" => _TZ_GMTM2,
			"-1" => _TZ_GMTM1,
			"0" => _TZ_GMT0,
			"1" => _TZ_GMTP1,
			"2" => _TZ_GMTP2,
			"3" => _TZ_GMTP3,
			"3.5" => _TZ_GMTP35,
			"4" => _TZ_GMTP4,
			"4.5" => _TZ_GMTP45,
			"5" => _TZ_GMTP5,
			"5.5" => _TZ_GMTP55,
			"6" => _TZ_GMTP6,
			"7" => _TZ_GMTP7,
			"8" => _TZ_GMTP8,
			"9" => _TZ_GMTP9,
			"9.5" => _TZ_GMTP95,
			"10" => _TZ_GMTP10,
			"11" => _TZ_GMTP11,
			"12" => _TZ_GMTP12
		);
		return $time_zone_list;
	}

}
