<?php
// $Id: tableform.php 1151 2007-12-04 15:43:01Z phppp $
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
 * 
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * the base class
 */
include_once XOOPS_ROOT_PATH."/class/xoopsform/form.php";

/**
 * Form that will output formatted as a HTML table
 * 
 * No styles and no JavaScript to check for required fields.
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 
 * @package     kernel
 * @subpackage  form
 */
class XoopsTableForm extends XoopsForm
{

	/**
	 * create HTML to output the form as a table
	 * 
     * @return	string
	 */
	function render()
	{
		$ret = $this->getTitle()."\n<form name='".$this->getName()."' id='".$this->getName()."' action='".$this->getAction()."' method='".$this->getMethod()."' ".$this->getExtra().">\n<table border='0' width='100%'>\n";
		$hidden = '';
		foreach ( $this->getElements() as $ele ) {
			if ( !$ele->isHidden() ) {
				$ret .= "<tr valign='top' align='left'><td>".$ele->getCaption();
				if ($ele->getDescription() != '') {
					$ret .= '<br /><br /><span style="font-weight: normal;">'.$ele->getDescription().'</span>';
				}
				$ret .= "</td><td>".$ele->render()."</td></tr>";
			} else {
				$hidden .= $ele->render()."\n";
			}
		}
		$ret .= "</table>\n$hidden</form>\n";
		return $ret;
	}
}
?>