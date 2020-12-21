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

/**
 * All common information used in the core goes from here.
 * Be careful while editing this file!
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 */

/** make sure mainfile is included, for security and functionality */
defined("ICMS_MAINFILE_INCLUDED") or die();

if (!isset($xoopsOption)) {
	$xoopsOption = array();
}

// -- Initialize kernel and launch bootstrap
icms::getInstance()->setup()->boot();

// -- Easiest ML by Gijoe (no longer needed here)

/* This address the strict compliance for PHP 5.3/5.4, but the rest of our timezone handling
 * can be improved beyond this. ~skenow
 */
date_default_timezone_set(timezone_name_from_abbr("", $icmsConfig['default_TZ'] * 3600, 0));

// -- Include site-wide lang file
icms_loadLanguageFile('core', 'global');
icms_loadLanguageFile('core', 'theme');
icms_loadLanguageFile('core', 'core');
icms_loadLanguageFile('system', 'common');

if (defined('_ADM_USE_RTL') && _ADM_USE_RTL == 1) {
	define('_GLOBAL_LEFT', 'right');
	define('_GLOBAL_RIGHT', 'left');
} else {
	define('_GLOBAL_LEFT', 'left');
	define('_GLOBAL_RIGHT', 'right');
}

// -- Include page-specific lang file
if (isset($xoopsOption['pagetype']) && false === strpos($xoopsOption['pagetype'], '.')) {
	icms_loadLanguageFile('core', $xoopsOption['pagetype']);
}

defined("XOOPS_USE_MULTIBYTES") or define("XOOPS_USE_MULTIBYTES", 0);

if ($icmsConfig['closesite'] == 1) {
	include ICMS_INCLUDE_PATH . '/site-closed.php';
}

global $xoopsOption, $icmsConfig;
if (isset($xoopsOption['nodebug']) && $xoopsOption['nodebug']) {
	/**
	 * @var icms_core_Logger $logger
	 */
	$logger = icms::getInstance()->get('logger');
	$logger->disableLogger();
}

if ($icmsConfigPersona['multi_login']) {
	if (is_object(icms::$user)) {
		$online_handler = icms::handler('icms_core_Online');
		$online_handler->write(icms::$user->uid, icms::$user->uname,
							   time(), 0, $_SERVER['REMOTE_ADDR']);
	}
}

// -- finalize boot process
icms::$preload->triggerEvent('finishCoreBoot');
