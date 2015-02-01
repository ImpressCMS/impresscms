<?php
// $Id: index.php 12313 2013-09-15 21:14:35Z skenow $
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

/**
 * Site index aka home page.
 * redirects to installation, if ImpressCMS is not installed yet
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		skalpa <psk@psykaos.net>
 * @author	    Sina Asghari(aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		AVN: $Id: index.php 12313 2013-09-15 21:14:35Z skenow $
 **/

/** Need the mainfile */
include "mainfile.php";

$member_handler = icms::handler('icms_member');
$group = $member_handler->getUserBestGroup((@is_object(icms::$user) ? icms::$user->getVar('uid') : 0));
$icmsConfig['startpage'] = $icmsConfig['startpage'][$group];

if (isset($icmsConfig['startpage']) && $icmsConfig['startpage'] != "" && $icmsConfig['startpage'] != "--") {
	$arr = explode('-', $icmsConfig['startpage']);
	if (count($arr) > 1) {
		$page_handler = icms::handler('icms_data_page');
		$page = $page_handler->get($arr[1]);
		if (is_object($page)) {
			$url =(substr($page->getVar('page_url'), 0, 7) == 'http://')
				? $page->getVar('page_url') : ICMS_URL . '/' . $page->getVar('page_url');
			header('Location: ' . $url);
		} else {
			$icmsConfig['startpage'] = '--';
			$xoopsOption['show_cblock'] = 1;
			/** Included to start page rendering */
			include "header.php";
			/** Included to complete page rendering */
			include "footer.php";
		}
	} else {
		header('Location: ' . ICMS_MODULES_URL . '/' . $icmsConfig['startpage'] . '/');
	}
	exit();
} else {
	$xoopsOption['show_cblock'] = 1;
	/** Included to start page rendering */
	include "header.php";
	/** Included to complete page rendering */
	include "footer.php";
}
