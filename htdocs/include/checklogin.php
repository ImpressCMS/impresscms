<?php
// $Id: checklogin.php 12474 2014-11-08 14:18:35Z skenow $
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
/**
 * The check login include file
 * This file is included from several others during their login validation process
 *  - user.php, site-closed.php, finish_auth.php. checklogin.php does not return
 *  to any of those files calling it. The outcome is a redirect with a result
 *
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category
 * @package		Members
 * @subpackage	Users
 * @since		XOOPS
 * @version		$Id: checklogin.php 12474 2014-11-08 14:18:35Z skenow $
 */

defined('ICMS_ROOT_PATH') || exit();

icms_loadLanguageFile('core', 'user');
$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);

/* make sure redirect stays within domain and isn't open to exploit */
if (!isset($redirect)) {

	$redirect = isset($_GET['xoops_redirect'])
		? $_GET['xoops_redirect']
		: isset($_POST['xoops_redirect'])
			? $_POST['xoops_redirect']
			: ICMS_URL;
	
		$redirect = htmlspecialchars(trim($redirect));
		if ($redirect !== htmlspecialchars($_SERVER['REQUEST_URI'])) $redirect = ICMS_URL;
}

/* if redirect goes to the register page, divert to main page - users don't go to register */
if ($redirect && strpos($redirect, 'register') !== FALSE) {
	$redirect = ICMS_URL;
}

/* prevent breaking out of the domain */
$pos = strpos($redirect, '://');
if ($pos !== FALSE) {
	$icmsLocation = substr(ICMS_URL, strpos(ICMS_URL, '://') + 3);
	if (substr($redirect, $pos + 3, strlen($icmsLocation)) != $icmsLocation) {
		$redirect = ICMS_URL;
	} elseif (substr($redirect, $pos + 3, strlen($icmsLocation) + 1) == $icmsLocation . '.') {
		$redirect = ICMS_URL;
	}
}

/* Commented out for OpenID , we need to change it to make a better validation if OpenID is used
 if ($uname == '' || $pass == '') {
	 redirect_header(ICMS_URL.'/user.php', 1, _US_INCORRECTLOGIN);
	 exit();
 }
 */

$member_handler = icms::handler('icms_member');

icms_loadLanguageFile('core', 'auth');
$icmsAuth =& icms_auth_Factory::getAuthConnection(icms_core_DataFilter::addSlashes($uname));

$uname4sql = addslashes(icms_core_DataFilter::stripSlashesGPC($uname));
$pass4sql = icms_core_DataFilter::stripSlashesGPC($pass);

/* Check to see if being access by a user - if not, attempt to authenticate */
if (empty($user) || !is_object($user)) {
	$user =& $icmsAuth->authenticate($uname4sql, $pass4sql);
}

/* User exists: check to see if the user has been activated.
 * If not, redirect with 'no permission' message
 */
if (FALSE != $user) {
	if (0 == $user->getVar('level')) {
		redirect_header(ICMS_URL . '/', 5, _US_NOACTTPADM);
		exit();
	}
	
	/* Check to see if logins from multiple locations is permitted.
	 * If it is not, check for existing login and redirect if detected
	 */
	if ($icmsConfigPersona['multi_login']) {
		if (is_object($user)) {
			$online_handler = icms::handler('icms_core_Online');
			$online_handler->gc(300);
			$onlines =& $online_handler->getAll();
			foreach ($onlines as $online) {
				if ($online['online_uid'] == $user->getVar('uid')) {
					$user = FALSE;
					redirect_header(ICMS_URL . '/', 3, _US_MULTLOGIN);
				}
			}
			
			if (is_object($user)) {
				$online_handler->write(
					$user->getVar('uid'),
					$user->getVar('uname'),
					time(),
					0,
					$_SERVER['REMOTE_ADDR']
				);
			}
		}
	}
	
	/* Check if site is closed and verify user's group can access if it is */
	if ($icmsConfig['closesite'] == 1) {
		$allowed = FALSE;
		foreach ( $user->getGroups() as $group) {
			if (in_array($group, $icmsConfig['closesite_okgrp']) || ICMS_GROUP_ADMIN == $group) {
				$allowed = TRUE;
				break;
			}
		}
		
		if (!$allowed) {
			redirect_header(ICMS_URL . '/', 1, _NOPERM);
			exit();
		}
	}

	/* Continue with login - all negative checks have been passed */
	$user->setVar('last_login', time());
	if (!$member_handler->insertUser($user)) {}
	// Regenerate a new session id and destroy old session
	session_regenerate_id(TRUE);
	$_SESSION = array();
	$_SESSION['xoopsUserId'] = $user->getVar('uid');
	$_SESSION['xoopsUserGroups'] = $user->getGroups();
	
	if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '') {
		setcookie($icmsConfig['session_name'], session_id(), time()+(60 * $icmsConfig['session_expire']), '/',  '', 0);
	}
	
	$_SESSION['xoopsUserLastLogin'] = $user->getVar('last_login');
	
	if (!$member_handler->updateUserByField($user, 'last_login', time())) {}
	
	$user_theme = $user->getVar('theme');
	
	if (in_array($user_theme, $icmsConfig['theme_set_allowed'])) {
		$_SESSION['xoopsUserTheme'] = $user_theme;
	}

	// autologin hack V3.1 GIJ (set cookie)
	$secure = substr(ICMS_URL, 0, 5) == 'https' ? 1 : 0; // we need to secure cookie when using SSL
	$icms_cookie_path = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH :
	preg_replace( '?http://[^/]+(/.*)$?' , "$1" , ICMS_URL );
	
	if ($icms_cookie_path == ICMS_URL) $icms_cookie_path = '/';
	
	if (!empty($_POST['rememberme'])) {
		$expire = time() + (defined('ICMS_AUTOLOGIN_LIFETIME') ? ICMS_AUTOLOGIN_LIFETIME : 604800) ; // 1 week default
		setcookie('autologin_uname', $user->getVar('login_name'), $expire, $icms_cookie_path, '', $secure, 0);
		$Ynj = date('Y-n-j') ;
		setcookie('autologin_pass', $Ynj . ':' . md5($user->getVar('pass') . ICMS_DB_PASS . ICMS_DB_PREFIX . $Ynj),
		$expire, $icms_cookie_path, '', $secure, 0);
	}
	// end of autologin hack V3.1 GIJ

	// Perform some maintenance of notification records
	$notification_handler = icms::handler('icms_data_notification');
	$notification_handler->doLoginMaintenance($user->getVar('uid'));

	/* check if user's password has expired and send to reset password page if it has */
	$is_expired = $user->getVar('pass_expired');
	if ($is_expired == 1) {
		redirect_header(ICMS_URL . '/user.php?op=resetpass', 5, _US_PASSEXPIRED, FALSE);
	} else {
    	redirect_header($redirect, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), FALSE);
    }

} elseif (!isset($_POST['xoops_redirect']) && !isset($_GET['xoops_redirect'])) {
	/* if not a user and redirect has not been set, go back to the user page */
	redirect_header(ICMS_URL . '/user.php', 5, $icmsAuth->getHtmlErrors());

} else {
	/* if not a user and redirect has been set, go back to that page */
	redirect_header(
		ICMS_URL . '/user.php?xoops_redirect='
		. urlencode($redirect), 5, $icmsAuth->getHtmlErrors(), FALSE
	);
}

exit();
