<?php
// $Id: checklogin.php 1083 2007-10-16 16:42:51Z phppp $
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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/user.php';
$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
if ($uname == '' || $pass == '') {
    redirect_header(XOOPS_URL.'/user.php', 1, _US_INCORRECTLOGIN);
    exit();
}
$member_handler =& xoops_gethandler('member');
$myts =& MyTextsanitizer::getInstance();

include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($uname));
$user = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));

if (false != $user) {
    if (0 == $user->getVar('level')) {
        redirect_header(XOOPS_URL.'/index.php', 5, _US_NOACTTPADM);
        exit();
    }
    if ($xoopsConfig['closesite'] == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
            exit();
        }
    }
    $user->setVar('last_login', time());
    if (!$member_handler->insertUser($user)) {
    }
    // Regenrate a new session id and destroy old session
    $GLOBALS["sess_handler"]->regenerate_id(true);
    $_SESSION = array();
    $_SESSION['xoopsUserId'] = $user->getVar('uid');
    $_SESSION['xoopsUserGroups'] = $user->getGroups();
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }
    if (!empty($_POST['xoops_redirect']) && !strpos($_POST['xoops_redirect'], 'register')) {
		$_POST['xoops_redirect'] = trim( $_POST['xoops_redirect'] );
        $parsed = parse_url(XOOPS_URL);
        $url = isset($parsed['scheme']) ? $parsed['scheme'].'://' : 'http://';
        if ( isset( $parsed['host'] ) ) {
        	$url .= $parsed['host'];
			if ( isset( $parsed['port'] ) ) {
				$url .= ':' . $parsed['port'];
			}
        } else {
        	$url .= $_SERVER['HTTP_HOST'];
        }
        if ( @$parsed['path'] ) {
        	if ( strncmp( $parsed['path'], $_POST['xoops_redirect'], strlen( $parsed['path'] ) ) ) {
	        	$url .= $parsed['path'];
        	}
        }
		$url .= $_POST['xoops_redirect'];
    } else {
        $url = XOOPS_URL.'/index.php';
    }

    // RMV-NOTIFY
    // Perform some maintenance of notification records
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->doLoginMaintenance($user->getVar('uid'));

    redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false);
}elseif(empty($_POST['xoops_redirect'])){
	redirect_header(XOOPS_URL.'/user.php', 5, $xoopsAuth->getHtmlErrors());
}else{
	redirect_header(XOOPS_URL.'/user.php?xoops_redirect='.urlencode(trim($_POST['xoops_redirect'])), 5, $xoopsAuth->getHtmlErrors(), false);
}
exit();

?>