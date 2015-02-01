<?php
// $Id: pagenav.php 12329 2013-09-19 13:53:36Z skenow $
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
 * Generates pagination
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: pagenav.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 * Class to facilitate navigation in a multi page document/list
 * @deprecated	Use icms_view_PageNav, instead
 * @todo		Remove in version 1.4
 *
 * @package		kernel
 * @subpackage	util
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsPageNav extends icms_view_PageNav {
	private $_deprecated;

	public function XoopsPageNav($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "") {
		self::__construct($total_items, $items_perpage, $current_start, $start_name, $extra_arg);
	}
	
	public function __construct($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "") {
		parent::__construct($total_items, $items_perpage, $current_start, $start_name, $extra_arg);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_view_PageNav', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
	
}

?>
