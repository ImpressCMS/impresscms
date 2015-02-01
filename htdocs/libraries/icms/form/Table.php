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
 * Creates a form styled by a table
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @package		Form
 * @version		SVN: $Id: Table.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 *
 * @category	ICMS
 * @package     Form
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_Table extends icms_form_Base {
	/**
	 * Insert an empty row in the table to serve as a separator.
	 *
	 * @param	string  $extra  HTML to be displayed in the empty row.
	 * @param	string	$class	CSS class name for <td> tag
	 */
	public function insertBreak($extra = '', $class= '') {
		$class = ($class != '') ? " class='$class'" : '';
		//Fix for $extra tag not showing
		if ($extra) {
			$extra = "<tr><td colspan='2' $class>$extra</td></tr>";
			$this->addElement($extra);
		} else {
			$extra = "<tr><td colspan='2' $class>&nbsp;</td></tr>";
			$this->addElement($extra);
		}
	}

	/**
	 * create HTML to output the form as a table
	 *
	 * @return	string  $ret  the constructed HTML
	 */
	public function render() {
		$ret = $this->getTitle() . "\n<form name='" . $this->getName()
			. "' id='" . $this->getName()
			. "' action='" . $this->getAction()
			. "' method='" . $this->getMethod() . "'" . $this->getExtra()
			. ">\n<table border='0' width='100%'>\n";
		$hidden = '';
		foreach ($this->getElements() as $ele) {
			if (!$ele->isHidden()) {
				$ret .= "<tr valign='top' align='" . _GLOBAL_LEFT . "'><td>" . $ele->getCaption();
				if ($ele_desc = $ele->getDescription()) {
					$ret .= '<br /><br /><span style="font-weight: normal;">' . $ele_desc . '</span>';
				}
				$ret .= "</td><td>" . $ele->render() . "</td></tr>";
			} else {
				$hidden .= $ele->render() . "\n";
			}
		}
		$ret .= "</table>\n$hidden</form>\n";
		return $ret;
	}
}
