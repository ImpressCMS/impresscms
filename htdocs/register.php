<?php
// $Id: register.php 1029 2007-09-09 03:49:25Z phppp $
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
 *  Registration process for new users
 *  Gathers required information and validates the new user
 *  @package kernel
 *  @subpackage users
 */
/**
 *
 */       
$xoopsOption['pagetype'] = 'user';

include 'mainfile.php';
$myts =& MyTextSanitizer::getInstance();

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

if ($xoopsConfigUser['allow_register'] == 0 && $xoopsConfigUser['activation_type'] != 3) {
	redirect_header('index.php', 6, _US_NOREGISTER);
}
/**
 *  Validates username, email address and password entries during registration
 *  Username is validated for uniqueness and length, password is validated for length and strictness,
 *  email is validated as a proper email address pattern  
 *  
 *  @param string $uname Username entered by the user
 *  @param string $email Email address entered by the user   
 *  @param string $pass Password entered by the user
 *  @param string $vpass Password verification entered by the user
 *  @return string of errors encountered while validating the user information, will be blank if successful 
 */     
function userCheck($uname, $email, $pass, $vpass)
{
	global $xoopsConfigUser;
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$stop = '';
	if (!checkEmail($email)) {
		$stop .= _US_INVALIDMAIL.'<br />';
	}
	foreach ($xoopsConfigUser['bad_emails'] as $be) {
		if (!empty($be) && preg_match("/".$be."/i", $email)) {
			$stop .= _US_INVALIDMAIL.'<br />';
			break;
		}
	}
	if (strrpos($email,' ') > 0) {
		$stop .= _US_EMAILNOSPACES.'<br />';
	}
	$uname = xoops_trim($uname);
	switch ($xoopsConfigUser['uname_test_level']) {
	case 0:
		// strict
		$restriction = '/[^a-zA-Z0-9\_\-]/';
		break;
	case 1:
		// medium
		$restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
		break;
	case 2:
		// loose
		$restriction = '/[\000-\040]/';
		break;
	}
	if (empty($uname) || preg_match($restriction, $uname)) {
		$stop .= _US_INVALIDNICKNAME."<br />";
	}
	if (strlen($uname) > $xoopsConfigUser['maxuname']) {
		$stop .= sprintf(_US_NICKNAMETOOLONG, $xoopsConfigUser['maxuname'])."<br />";
	}
	if (strlen($uname) < $xoopsConfigUser['minuname']) {
		$stop .= sprintf(_US_NICKNAMETOOSHORT, $xoopsConfigUser['minuname'])."<br />";
	}
	foreach ($xoopsConfigUser['bad_unames'] as $bu) {
		if (!empty($bu) && preg_match("/".$bu."/i", $uname)) {
			$stop .= _US_NAMERESERVED."<br />";
			break;
		}
	}
	if (strrpos($uname, ' ') > 0) {
		$stop .= _US_NICKNAMENOSPACES."<br />";
	}
	$sql = sprintf('SELECT COUNT(*) FROM %s WHERE uname = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($uname)));
	$result = $xoopsDB->query($sql);
	list($count) = $xoopsDB->fetchRow($result);
	if ($count > 0) {
		$stop .= _US_NICKNAMETAKEN."<br />";
	}
	$count = 0;
	if ( $email ) {
		$sql = sprintf('SELECT COUNT(*) FROM %s WHERE email = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($email)));
		$result = $xoopsDB->query($sql);
		list($count) = $xoopsDB->fetchRow($result);
		if ( $count > 0 ) {
			$stop .= _US_EMAILTAKEN."<br />";
		}
	}
	if ( !isset($pass) || $pass == '' || !isset($vpass) || $vpass == '' ) {
		$stop .= _US_ENTERPWD.'<br />';
	}
	if ( (isset($pass)) && ($pass != $vpass) ) {
		$stop .= _US_PASSNOTSAME.'<br />';
	} elseif ( ($pass != '') && (strlen($pass) < $xoopsConfigUser['minpass']) ) {
		$stop .= sprintf(_US_PWDTOOSHORT,$xoopsConfigUser['minpass'])."<br />";
	}
	return $stop;
}
$op = !isset($_POST['op']) ? 'register' : $_POST['op'];
$uname = isset($_POST['uname']) ? $myts->stripSlashesGPC($_POST['uname']) : '';
$email = isset($_POST['email']) ? trim($myts->stripSlashesGPC($_POST['email'])) : '';
$url = isset($_POST['url']) ? trim($myts->stripSlashesGPC($_POST['url'])) : '';
$pass = isset($_POST['pass']) ? $myts->stripSlashesGPC($_POST['pass']) : '';
$vpass = isset($_POST['vpass']) ? $myts->stripSlashesGPC($_POST['vpass']) : '';
$timezone_offset = isset($_POST['timezone_offset']) ? floatval($_POST['timezone_offset']) : $xoopsConfig['default_TZ'];
$user_viewemail = (isset($_POST['user_viewemail']) && intval($_POST['user_viewemail'])) ? 1 : 0;
$user_mailok = (isset($_POST['user_mailok']) && intval($_POST['user_mailok'])) ? 1 : 0;
$agree_disc = (isset($_POST['agree_disc']) && intval($_POST['agree_disc'])) ? 1 : 0;
$actkey = isset($_POST['actkey']) ? trim($myts->stripSlashesGPC($_POST['actkey'])) : '';
$salt = isset($_POST['salt']) ? trim($myts->stripSlashesGPC($_POST['salt'])) : '';
switch ( $op ) {
case 'newuser':
	include 'header.php';
	$stop = '';
	if (!$GLOBALS['xoopsSecurity']->check()) {
	    $stop .= implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())."<br />";
	}
	if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
		if (empty($agree_disc)) {
			$stop .= _US_UNEEDAGREE.'<br />';
		}
	}
	$stop .= userCheck($uname, $email, $pass, $vpass);
	if (empty($stop)) {
		echo _US_USERNAME.": ".$myts->htmlSpecialChars($uname)."<br />";
		echo _US_EMAIL.": ".$myts->htmlSpecialChars($email)."<br />";
		if ($url != '') {
			$url = formatURL($url);
			echo _US_WEBSITE.': '.$myts->htmlSpecialChars($url).'<br />';
		}
		$f_timezone = ($timezone_offset < 0) ? 'GMT '.$timezone_offset : 'GMT +'.$timezone_offset;
		echo _US_TIMEZONE.": $f_timezone<br />";
		echo "<form action='register.php' method='post'>
		<input type='hidden' name='uname' value='".$myts->htmlSpecialChars($uname)."' />
		<input type='hidden' name='email' value='".$myts->htmlSpecialChars($email)."' />";
		echo "<input type='hidden' name='user_viewemail' value='".$user_viewemail."' />
		<input type='hidden' name='timezone_offset' value='".$timezone_offset."' />
		<input type='hidden' name='url' value='".$myts->htmlSpecialChars($url)."' />
		<input type='hidden' name='pass' value='".$myts->htmlSpecialChars($pass)."' />
		<input type='hidden' name='vpass' value='".$myts->htmlSpecialChars($vpass)."' />
		<input type='hidden' name='user_mailok' value='".$user_mailok."' />
		<input type='hidden' name='actkey' value='".$myts->htmlSpecialChars($actkey)."' />
		<input type='hidden' name='salt' value='".$myts->htmlSpecialChars($salt)."' />
		<br /><br /><input type='hidden' name='op' value='finish' />".$GLOBALS['xoopsSecurity']->getTokenHTML()."<input type='submit' value='". _US_FINISH ."' /></form>";
	} else {
		echo "<span style='color:#ff0000;'>$stop</span>";
		include 'include/registerform.php';
		$reg_form->display();
	}
	$xoopsTpl->assign('xoops_pagetitle', _US_USERREG);
	include 'footer.php';
	break;
case 'finish':
	include 'header.php';
	$stop = userCheck($uname, $email, $pass, $vpass);
	if (!$GLOBALS['xoopsSecurity']->check()) {
	    $stop .= implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())."<br />";
	}
  if(@include_once XOOPS_ROOT_PATH."/libraries/captcha/captcha.php") {
	include_once(XOOPS_ROOT_PATH ."/class/xoopsformloader.php");
	if ($xoopsConfigUser['use_captcha'] == 1) {
            $xoopsCaptcha = XoopsCaptcha::instance();
            if(! $xoopsCaptcha->verify() ) {
                   $stop = $xoopsCaptcha->getMessage();
                    
            }
    }
}

	if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
		if (empty($agree_disc)) {
			$stop .= _US_UNEEDAGREE.'<br />';
		}
	}

	if ( empty($stop) ) {
		$member_handler =& xoops_gethandler('member');
		$newuser =& $member_handler->createUser();
		$newuser->setVar('user_viewemail',$user_viewemail, true);
		$newuser->setVar('uname', $uname, true);
		$newuser->setVar('email', $email, true);
		if ($url != '') {
			$newuser->setVar('url', formatURL($url), true);
		}
		$newuser->setVar('user_avatar','blank.gif', true);
		include_once 'include/checkinvite.php';
		$valid_actkey = check_invite_code($actkey);
		$newuser->setVar('actkey', $valid_actkey ? $actkey : substr(md5(uniqid(mt_rand(), 1)), 0, 8), true);
		$salt = icms_createSalt();
		$newuser->setVar('salt', $salt, true);
		$pass = icms_encryptPass($pass, $salt);
		$newuser->setVar('pass', $pass, true);
		$newuser->setVar('timezone_offset', $timezone_offset, true);
		$newuser->setVar('user_regdate', time(), true);
		$newuser->setVar('uorder',$xoopsConfig['com_order'], true);
		$newuser->setVar('umode',$xoopsConfig['com_mode'], true);
		$newuser->setVar('user_mailok',$user_mailok, true);
		$newuser->setVar('notify_method', 2);
		if ($valid_actkey || $xoopsConfigUser['activation_type'] == 1) {
			$newuser->setVar('level', 1, true);
		}
		if (!$member_handler->insertUser($newuser)) {
			echo _US_REGISTERNG;
			include 'footer.php';
			exit();
		}
		$newid = intval($newuser->getVar('uid'));
		if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
			echo _US_REGISTERNG;
			include 'footer.php';
			exit();
		}
		// Activate automatically
		if ($xoopsConfigUser['new_user_notify'] == 1) {
			$newuser->sendWelcomeMessage();		
			$newuser->newUserNotifyAdmin();	
		}
		// update invite_code (if any)
		if ($valid_actkey) {
			update_invite_code($actkey, $newid);
		}
		if ($xoopsConfigUser['activation_type'] == 1 || $xoopsConfigUser['activation_type'] == 3) {
			redirect_header('index.php', 4, _US_ACTLOGIN);
			exit();
		}

		$thisuser = new XoopsUser($newid);

		// Activation by user
		if ($xoopsConfigUser['activation_type'] == 0) {
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('register.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->setToUsers(new XoopsUser($newid));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $uname));
			if ( !$xoopsMailer->send() ) {
				echo _US_YOURREGMAILNG;
			} else {
				echo _US_YOURREGISTERED;
			}
			$thisuser->newUserNotifyAdmin();
		// activation by admin
		} elseif ($xoopsConfigUser['activation_type'] == 2) {
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('adminactivate.tpl');
			$xoopsMailer->assign('USERNAME', $uname);
			$xoopsMailer->assign('USEREMAIL', $email);
			$xoopsMailer->assign('USERACTLINK', XOOPS_URL.'/user.php?op=actv&id='.$newid.'&actkey='.$actkey);
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$member_handler =& xoops_gethandler('member');
			$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['activation_group']));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $uname));
			if ( !$xoopsMailer->send() ) {
				echo _US_YOURREGMAILNG;
			} else {
				echo _US_YOURREGISTERED2;
			}
		}
	} else {
		echo "<span style='color:#ff0000; font-weight:bold;'>$stop</span>";
		include 'include/registerform.php';
		$reg_form->display();
	}
	$xoopsTpl->assign('xoops_pagetitle', _US_USERREG);
	include 'footer.php';
	break;
case 'register':
default:
	$invite_code = $_GET['code'];
	if ($xoopsConfigUser['activation_type'] == 3 || !empty($invite_code)) {
		include 'include/checkinvite.php';
		load_invite_code($invite_code);
	}
	// invite is ok, show register form
	include 'header.php';
	include 'include/registerform.php';
	$reg_form->display();
	$xoopsTpl->assign('xoops_pagetitle', _US_USERREG);
	include 'footer.php';
	break;
}
?>
