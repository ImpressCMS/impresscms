<?php
// $Id: cp_functions.php 12313 2013-09-15 21:14:35Z skenow $
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
 * All control panel functions and forming goes from here.
 * Be careful while editing this file!
 *
 * @copyright	The XOOPS Project <http://www.xoops.org/>
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 *
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @package		core
 * @since		XOOPS
 * @version		$Id$
 *
 * @author		The XOOPS Project <http://www.xoops.org>
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @author 		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
/** Creates constant indicating this file has been loaded */
define('XOOPS_CPFUNC_LOADED', 1);
/** Load the template class */

/**
 * Function icms_cp_header
 *
 * @since ImpressCMS 1.2
 *
 * @author rowd (from the XOOPS Community)
 * @author nekro (aka Gustavo Pilla)<nekro@impresscms.org>
 */
function icms_cp_header() {
    \icms::$logger->stopTime('Module init');
    \icms::$logger->startTime('ImpressCMS CP Output Init');

    global $xoopsOption;

    $xoopsOption['isAdminSide'] = true;
    \icms::$response = new \icms_response_HTML($xoopsOption);

    // ################# Preload Trigger startOutputInit ##############
    \icms::$preload->triggerEvent('adminHeader');
}

/**
 * Function icms_cp_footer
 *
 * @since ImpressCMS 1.2
 * @author rowd (from XOOPS Community)
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
function icms_cp_footer() {
    \icms::$logger->stopTime('Module display');
    \icms::$logger->stopTime('XOOPS output init');
    \icms::$logger->startTime('Module display');

    \icms::$response->render();

    \icms::$logger->stopTime();
}

function themecenterposts($title, $content) {
	echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">' . $title . '</td></tr><tr><td><br />' . $content . '<br /></td></tr></table>';
}
/**
 * Creates a multidimensional array with items of the dropdown menus of the admin panel.
 * This array will be saved, by the function xoops_module_write_admin_menu, in a cache file
 * to preserve resources of the server and to maintain compatibility with some modules Xoops.
 *
 * @author TheRplima
 *
 * @return array (content of admin panel dropdown menus)
 */
function impresscms_get_adminmenu() {
    $admin_menu = [
        [ // Control Panel Home menu
            'id' => 'cphome',
            'text' => _CPHOME,
            'link' => '#',
            'menu' => [
                [
                    'link' => ICMS_URL . '/admin.php',
                    'title' => _CPHOME,
                    'absolute' => 1,
                    'small' => ICMS_URL . '/modules/system/images/mini_cp.png',
                ],
                [
                    'link' => ICMS_URL,
                    'title' => _YOURHOME,
                    'absolute' => 1,
                    'small' => ICMS_URL . '/modules/system/images/home.png',
                ],
                [
                    'link' => ICMS_URL . '/user.php?op=logout',
                    'title' => _LOGOUT,
                    'absolute' => 1,
                    'small' => ICMS_URL . '/modules/system/images/logout.png',
                ]
            ],
        ]
    ];

    #########################################################################
    # System Preferences menu
    #########################################################################
    $module_handler = icms::handler('icms_module');
    $mod = & $module_handler->getByDirname('system');
    $menu = array();
    foreach ($mod->getAdminMenu() as $lkn) {
        $lkn['dir'] = 'system';
        $menu[] = $lkn;
    }

    $admin_menu[] = array(
        'id' => 'opsystem',
        'text' => _SYSTEM,
        'link' => ICMS_URL . '/modules/system/admin.php',
        'menu' => $menu,
    );
    #########################################################################
    # end
    #########################################################################

    $admin_menu[] = [
        'id' => 'modules',
        'text' => _MODULES,
        'link' => ICMS_URL . '/modules/system/admin.php?fct=modules',
        'menu' => $module_handler->getAdminMenuItems()
    ];

    $admin_menu[] = [
        'id' => 'news',
        'text' => _ABOUT,
        'link' => '#',
        'menu' => [
            [
                'link' => 'http://www.impresscms.org',
                'title' => _IMPRESSCMS_HOME,
                'absolute' => 1,
            //small' => ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'http://community.impresscms.org',
                'title' => _IMPRESSCMS_COMMUNITY,
                'absolute' => 1,
            //'small' = ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'http://addons.impresscms.org',
                'title' => _IMPRESSCMS_ADDONS,
                'absolute' => 1,
            //'small' => ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'http://wiki.impresscms.org',
                'title' => _IMPRESSCMS_WIKI,
                'absolute' => 1,
            //'small' = ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'http://blog.impresscms.org',
                'title' => _IMPRESSCMS_BLOG,
                'absolute' => 1,
            //'small'] = ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'https://impresscmsdev.assembla.com/spaces/impresscms/new_dashboard',
                'title' => _IMPRESSCMS_PROJECT,
                'absolute' => 1,
            //'small' = ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => 'http://www.impresscms.org/donations/',
                'title' => _IMPRESSCMS_DONATE,
                'absolute' => 1,
            //'small' = ICMS_URL . '/images/impresscms.png',
            ],
            [
                'link' => ICMS_URL . '/admin.php?rssnews=1',
                'title' => _IMPRESSCMS_NEWS,
                'absolute' => 1,
            //'small' => ICMS_URL . '/images/impresscms.png',
            ]
        ]
    ];

    if (_LANGCODE != 'en') {
        array_splice($admin_menu[count($admin_menu) - 1], 1, 0, [
            'link' => _IMPRESSCMS_LOCAL_SUPPORT,
            'title' => _IMPRESSCMS_LOCAL_SUPPORT_TITLE,
            'absolute' => 1,
                //'small' => ICMS_URL . '/images/impresscms.png',
        ]);
    }


    return $admin_menu;
}

function impresscms_sort_adminmenu_modules(\icms_module_Object $a, \icms_module_Object $b) {
	$n1 = strtolower($a->name);
	$n2 = strtolower($b->name);
	return ($n1 == $n2) ? 0 : ($n1 < $n2) ? -1 : +1;
}

/**
 * Writes entire admin menu into cache
 * @param string  $content  content to write to the admin menu file
 * @return true
 * @todo create language constants for the error messages
 */
function xoops_module_write_admin_menu($content) {
	global $icmsConfig;
	$filename = ICMS_CACHE_PATH . '/adminmenu_' . $icmsConfig ['language'] . '.php';
	if (!$file = fopen($filename, "w")) {
            trigger_error('Failed to open admin menu cache file for writing', E_USER_WARNING);
            return false;
	}
	if (fwrite($file, '<?php return ' . var_export($content, true) . ';') === FALSE) {
            trigger_error('Failed to write admin menu cache file', E_USER_WARNING);
            fclose($file);
            return false;
	}
	fclose($file);

	// write index.html file in cache folder
	// file is delete after clear_cache (smarty)
	icms_core_Filesystem::writeIndexFile(ICMS_CACHE_PATH);
	return true;
}
