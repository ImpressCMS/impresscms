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
 * Site index aka home page.
 * redirects to installation, if ImpressCMS is not installed yet
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ImpressCMS\Core
 * @since	XOOPS
 * @author	skalpa <psk@psykaos.net>
 * @author	Sina Asghari(aka stranger) <pesian_stranger@users.sourceforge.net>
 **/

/** mainfile is required, if it doesn't exist - installation is needed */

include 'mainfile.php';

if (!defined('ICMS_MODULES_URL')) {
    header("Location: /install/index.php");
}

$path = preg_replace('/[^a-zA-Z0-9\/]/', '', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

if (preg_match_all('|([^/]+)/([^/]+)/([^/]+)(.*)|', $path, $params, PREG_SET_ORDER) === 1) {
    \icms::$logger->disableRendering();
    list(, $module, $controller_name, $action, $params) = $params[0];
    $handler = new \icms_controller_Handler();
    try {
        $handler->exec(
            $module,
            $handler->type,
            $controller_name,
            strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($action),
            $handler->parseParamsStringToArray($module, $controller_name, $params)
        );
    } catch (Exception $ex) {
        header('Location: /error.php?e=404');
    }
} else {
    $member_handler = \icms::handler('icms_member');
    $group = $member_handler->getUserBestGroup(
        (!empty(\icms::$user) && is_object(\icms::$user)) ? \icms::$user->uid : 0
    );        
    
    $icmsConfig['startpage'] = $icmsConfig['startpage'][$group];

    if (isset($icmsConfig['startpage']) && $icmsConfig['startpage'] != '' && $icmsConfig['startpage'] != "--") {
        $arr = explode('-', $icmsConfig['startpage']);
        if (count($arr) > 1) {
            $page_handler = \icms::handler('icms_data_page');
            $page = $page_handler->get($arr[1]);
            if (is_object($page)) {
                header('Location: ' . $page->getURL());
            } else {
                $icmsConfig['startpage'] = '--';
                new icms_response_DefaultEmptyPage();
            }
        } else {
            header('Location: ' . ICMS_MODULES_URL . '/' . $icmsConfig['startpage'] . '/');
        }
        exit();
    } else {
        $icmsResponse = new \icms_response_DefaultEmptyPage();
        $icmsResponse->render();
    }
}