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
 * Creates a simple form
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @version		SVN: $Id: Simple.php 12313 2013-09-15 21:14:35Z skenow $
 * @todo		this class is not used by the core; we will probably remove it in 1.4
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Form that will output as a simple HTML form with minimum formatting
 *
 * @category	ICMS
 * @package     Form
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_Simple extends icms_form_Base {
	/**
	 * This method is required - this method in the parent (abstract) class is also abstract
	 * @param string $extra
	 */
	public function insertBreak($extra = NULL) {
	}
	/**
	 * create HTML to output the form with minimal formatting
	 *
	 * @return	string
	 */
	public function render() {
		$ret = $this->getTitle() . "\n<form name='" . $this->getName()
			. "' id='" . $this->getName()
			. "' action='" . $this->getAction()
			. "' method='" . $this->getMethod() . "'" . $this->getExtra()
			. ">\n";
		foreach ($this->getElements() as $ele) {
			if (!$ele->isHidden()) {
				$ret .= "<strong>" . $ele->getCaption() . "</strong><br />" . $ele->render() . "<br />\n";
			} else {
				$ret .= $ele->render() . "\n";
			}
		}
		$ret .= "</form>\n";
		return $ret;
	}
}
