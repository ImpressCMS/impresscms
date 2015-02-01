<?php
// $Id: admin.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Admin control panel entry page
 *
 * This page is responsible for
 * - displaying the home of the Control Panel
 * - checking for cache/adminmenu.php
 * - displaying RSS feed of the ImpressCMS Project
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author		modified by marcan <marcan@impresscms.org>
 * @version		$Id: admin.php 12313 2013-09-15 21:14:35Z skenow $
 */

define('ICMS_IN_ADMIN', 1);

$xoopsOption['pagetype'] = 'admin';
include 'mainfile.php';
include ICMS_ROOT_PATH . '/include/cp_functions.php';

// test to see if the system module should be updated, added in 1.2
if (icms_getModuleInfo('system')->getDBVersion() < ICMS_SYSTEM_DBVERSION) {
	redirect_header('modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=system', 1, _CO_ICMS_UPDATE_NEEDED);
}

$op = isset($_GET['rssnews']) ? (int) ($_GET['rssnews']) : 0;
if (!empty($_GET['op'])) {$op = (int) ($_GET['op']);}
if (!empty($_POST['op'])) {$op = (int) ($_POST['op']);}

if (!file_exists(ICMS_CACHE_PATH . '/adminmenu_' . $icmsConfig['language'] . '.php')) {
	xoops_module_write_admin_menu(impresscms_get_adminmenu());
}

switch ($op) {
	case 1:
		icms_cp_header();
		showRSS();
		break;
		/*	case 2:
		 xoops_module_write_admin_menu(impresscms_get_adminmenu());
		 redirect_header('javascript:history.go(-1)', 1, _AD_LOGINADMIN);
		 break;*/

	default:
		icms_cp_header();
		break;
}

function showRSS() {
	global $icmsAdminTpl, $icmsConfigPersona;

	$rssurl = $icmsConfigPersona['rss_local'];
	$rssfile = ICMS_CACHE_PATH . '/adminnews_' . _LANGCODE . '.xml';

	// Create a new instance of the SimplePie object
	$feed = new icms_feeds_Simplerss();
	$feed->set_feed_url($rssurl);
	$feed->set_cache_duration(3600);
	$feed->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
	$feed->init();
	$feed->handle_content_type();

	if (!$feed->error) {
		$icmsAdminTpl->assign('admin_rss_feed_link', $feed->get_link());
		$icmsAdminTpl->assign('admin_rss_feed_title', $feed->get_title());
		$icmsAdminTpl->assign('admin_rss_feed_dsc', $feed->get_description());
		$feeditems = array();
		foreach ($feed->get_items() as $item) {
			$feeditem = array();
			$feeditem['link'] = $item->get_permalink();
			$feeditem['title'] = $item->get_title();
			$feeditem['description'] = $item->get_description();
			$feeditem['date'] = $item->get_date();
			$feeditem['guid'] = $item->get_id();
			$feeditems[] = $feeditem;
		}
		$icmsAdminTpl->assign('admin_rss_feeditems', $feeditems);
	}

	$icmsAdminTpl->display('db:admin/system_adm_rss.html');
}
icms_cp_footer();
