<?php
// $Id: invite.php 885 2007-07-28 08:36:44Z sudhaker $
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

$xoopsOption['pagetype'] = 'user';

include 'mainfile.php';
$myts =& MyTextSanitizer::getInstance();

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

// If not a user and invite needs one, redirect
if ($xoopsConfigUser['activation_type'] == 3 && $xoopsConfigUser['allow_register'] == 0 && !is_object($xoopsUser)) {
	redirect_header('index.php', 6, _US_INVITEBYMEMBER);
    exit();
}

$op = !isset($_POST['op']) ? 'invite' : $_POST['op'];
$email = isset($_POST['email']) ? trim($myts->stripSlashesGPC($_POST['email'])) : '';

switch ( $op ) {
case 'finish':
	include 'header.php';
	$stop = '';
	if (!$GLOBALS['xoopsSecurity']->check()) {
	    $stop .= implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())."<br />";
	}
	if (!checkEmail($email)) {
		$stop .= _US_INVALIDMAIL.'<br />';
	}
	if ( empty($stop) ) {
		$invite_code = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$xoopsDB =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$sql = sprintf('INSERT INTO '.$xoopsDB->prefix('invites').' (invite_code, from_id, invite_to, invite_date, extra_info) VALUES (%s, %d, %s, %d, %s)', 
			$xoopsDB->quoteString(addslashes($invite_code)),
			is_object($xoopsUser)?$xoopsUser->getVar('uid'):0,
			$xoopsDB->quoteString(addslashes($email)),
			time(),
			$xoopsDB->quoteString(addslashes(serialize(array())))
		);
		$xoopsDB->query($sql);
		// if query executed successful
		if ($xoopsDB->getAffectedRows() == 1) {
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('invite.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->assign('USEREMAIL', $email);
			$xoopsMailer->assign('REGISTERLINK', XOOPS_URL.'/register.php?code='.$invite_code);
			$xoopsMailer->setToEmails($email);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_INVITEREGLINK,XOOPS_URL));
			if ( !$xoopsMailer->send() ) {
				$stop .= _US_INVITEMAILERR;
			} else {
				echo _US_INVITESENT;
			}
		} else {
			$stop .= _US_INVITEDBERR;
		}
	} 
	if (! empty($stop)) {
		echo "<span style='color:#ff0000; font-weight:bold;'>$stop</span>";
		include 'include/inviteform.php';
		$invite_form->display();
	}
	include 'footer.php';
	break;
case 'invite':
default:
	include 'header.php';
	include 'include/inviteform.php';
	$invite_form->display();
	include 'footer.php';
	break;
}
?>