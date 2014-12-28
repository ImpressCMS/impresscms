<?php
// $Id: lostpass.php 12313 2013-09-15 21:14:35Z skenow $
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
 * All functions for lost password generator are going through here.
 *
 * Form and process for sending a new password to a user
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Member
 * @subpackage	Users
 * @since		XOOPS
 * @version		SVN: $Id: lostpass.php 12313 2013-09-15 21:14:35Z skenow $
 */

$xoopsOption['pagetype'] = 'user';
/** Include mainfile.php - required */
include 'mainfile.php';
/* $_POST parameters
 *	email
 *
 *	$_GET parameters
 *	code
 */
/* set default value for $code */
$code = '';

$filter_get = $filter_post = array('email' => array('email', 'options' => array(0, 0)));

/* set default value for parameters */
$code = '';

if (!empty($_GET)) {
    $clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
    extract($clean_GET);
}
if (!empty($_POST)) {
    $clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
    extract($clean_POST);
}
if ($email == '') {
	redirect_header('user.php', 2, _US_SORRYNOTFOUND);
}

$member_handler = icms::handler('icms_member');
$criteria = new icms_db_criteria_Compo();
$criteria->add(new icms_db_criteria_Item('email',$email));
$criteria->add(new icms_db_criteria_Item('level', '-1', '!='));
$getuser =& $member_handler->getUsers($criteria);

if (empty($getuser)) {
	$msg = _US_SORRYNOTFOUND;
	redirect_header('user.php', 2, $msg);
} else {
	$icmspass = new icms_core_Password();

	$areyou = substr($getuser[0]->getVar('pass'), 0, 5);
	if ($code != '' && $areyou == $code) {
		$newpass = $icmspass->createSalt(8);
		$pass = $icmspass->encryptPass($newpass);
		$xoopsMailer = new icms_messaging_Handler();
		$xoopsMailer->useMail();
		$xoopsMailer->setTemplate('lostpass2.tpl');
		$xoopsMailer->assign('SITENAME', $icmsConfig['sitename']);
		$xoopsMailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
		$xoopsMailer->assign('SITEURL', ICMS_URL . '/');
		$xoopsMailer->assign('IP', $_SERVER['REMOTE_ADDR']);
		$xoopsMailer->assign('NEWPWD', $newpass);
		$xoopsMailer->setToUsers($getuser[0]);
		$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
		$xoopsMailer->setFromName($icmsConfig['sitename']);
		$xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ, ICMS_URL));
		if (!$xoopsMailer->send()) {
			echo $xoopsMailer->getErrors();
		}

		// Next step: add the new password to the database
		$sql = sprintf("UPDATE %s SET pass = '%s', pass_expired = '%u' WHERE uid = '%u'",
						icms::$xoopsDB->prefix('users'), $pass, 1, (int) $getuser[0]->getVar('uid'));
		if (!icms::$xoopsDB->queryF($sql)) {
			/** Include header.php to start page rendering */
			include 'header.php';
			echo _US_MAILPWDNG;
			/** Include footer.php to complete page rendering */
			include 'footer.php';
			exit();
		}
		redirect_header('user.php', 3, sprintf(_US_PWDMAILED, $getuser[0]->getVar('uname')), FALSE);
		// If no Code, send it
	} else {
		$xoopsMailer = new icms_messaging_Handler();
		$xoopsMailer->useMail();
		$xoopsMailer->setTemplate('lostpass1.tpl');
		$xoopsMailer->assign('SITENAME', $icmsConfig['sitename']);
		$xoopsMailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
		$xoopsMailer->assign('SITEURL', ICMS_URL . '/');
		$xoopsMailer->assign('IP', $_SERVER['REMOTE_ADDR']);
		$xoopsMailer->assign('NEWPWD_LINK', ICMS_URL . '/lostpass.php?email=' . $email . '&code=' . $areyou);
		$xoopsMailer->setToUsers($getuser[0]);
		$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
		$xoopsMailer->setFromName($icmsConfig['sitename']);
		$xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ, $icmsConfig['sitename']));
		/** Include header.php to start page rendering */
		include 'header.php';
		if (!$xoopsMailer->send()) {
			echo $xoopsMailer->getErrors();
		}
		echo '<h4>';
		printf(_US_CONFMAIL, $getuser[0]->getVar('uname'));
		echo '</h4>';
		/** Include footer.php to complete page rendering */
		include 'footer.php';
	}
}
