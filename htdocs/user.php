<?php
// $Id: user.php 1083 2007-10-16 16:42:51Z phppp $
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

$op = 'main';

if ( isset($_POST['op']) ) {
    $op = trim($_POST['op']);
} elseif ( isset($_GET['op']) ) {
    $op = trim($_GET['op']);
}

if ($op == 'main') {
    if ( !$xoopsUser ) {
        $xoopsOption['template_main'] = 'system_userform.html';
        include 'header.php';
        $xoopsTpl->assign('lang_login', _LOGIN);
        $xoopsTpl->assign('lang_username', _USERNAME);
        if (isset($_COOKIE[$xoopsConfig['usercookie']])) {
            $xoopsTpl->assign('usercookie', $_COOKIE[$xoopsConfig['usercookie']]);
        }
        if (isset($_GET['xoops_redirect'])) {
            $xoopsTpl->assign('redirect_page', htmlspecialchars(trim($_GET['xoops_redirect']), ENT_QUOTES));
        }
        $xoopsTpl->assign('lang_password', _PASSWORD);
        $xoopsTpl->assign('lang_notregister', _US_NOTREGISTERED);
        $xoopsTpl->assign('lang_lostpassword', _US_LOSTPASSWORD);
        $xoopsTpl->assign('lang_noproblem', _US_NOPROBLEM);
        $xoopsTpl->assign('lang_youremail', _US_YOUREMAIL);
        $xoopsTpl->assign('lang_sendpassword', _US_SENDPASSWORD);
        $xoopsTpl->assign('mailpasswd_token', $GLOBALS['xoopsSecurity']->createToken());
        $config_handler =& xoops_gethandler('config');
		$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

		if ($xoopsConfigUser['allow_register'] == 1) {
			$xoopsTpl->assign('allow_registration', $xoopsConfigUser['allow_register']);
		}
        $xoopsTpl->assign('xoops_pagetitle', _LOGIN);
        include 'footer.php';
    } elseif ( !empty($_GET['xoops_redirect']) ) {
        header('Location: '.$_GET['xoops_redirect']);
		exit();
    } else {
        header('Location: '.XOOPS_URL.'/userinfo.php?uid='.$xoopsUser->getVar('uid'));
		exit();
    }
    exit();
}

if ($op == 'login') {
    include_once XOOPS_ROOT_PATH.'/include/checklogin.php';
    exit();
}

if ($op == 'logout') {
    $message = '';
    $_SESSION = array();
    session_destroy();
    // clear entry from online users table
    if (is_object($xoopsUser)) {
        $online_handler =& xoops_gethandler('online');
        $online_handler->destroy($xoopsUser->getVar('uid'));
    }
    $message = _US_LOGGEDOUT.'<br />'._US_THANKYOUFORVISIT;
    redirect_header('index.php', 1, $message);
    exit();
}

if ($op == 'actv') {
    $id = intval($_GET['id']);
    $actkey = trim($_GET['actkey']);
    if (empty($id)) {
        redirect_header('index.php',1,'');
        exit();
    }
    $member_handler =& xoops_gethandler('member');
    $thisuser =& $member_handler->getUser($id);
    if (!is_object($thisuser)) {
        exit();
    }
    if ($thisuser->getVar('actkey') != $actkey) {
        redirect_header('index.php',5,_US_ACTKEYNOT);
		exit();
    } else {
        if ($thisuser->getVar('level') > 0 ) {
            redirect_header( 'user.php', 5, _US_ACONTACT, false );
			exit();
        } else {
            if (false != $member_handler->activateUser($thisuser)) {
                $config_handler =& xoops_gethandler('config');
                $xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
                if ($xoopsConfigUser['activation_type'] == 2) {
                    $myts =& MyTextSanitizer::getInstance();
                    $xoopsMailer =& getMailer();
                    $xoopsMailer->useMail();
                    $xoopsMailer->setTemplate('activated.tpl');
                    $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
                    $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
                    $xoopsMailer->assign('SITEURL', XOOPS_URL."/");
                    $xoopsMailer->setToUsers($thisuser);
                    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                    $xoopsMailer->setFromName($xoopsConfig['sitename']);
                    $xoopsMailer->setSubject(sprintf(_US_YOURACCOUNT,$xoopsConfig['sitename']));
                    include 'header.php';
                    if ( !$xoopsMailer->send() ) {
                        printf(_US_ACTVMAILNG, $thisuser->getVar('uname'));
                    } else {
                        printf(_US_ACTVMAILOK, $thisuser->getVar('uname'));
                    }
                    include 'footer.php';
                } else {
                    redirect_header( 'user.php', 5, _US_ACTLOGIN, false );
					exit();
                }
            } else {
                redirect_header('index.php',5,'Activation failed!');
				exit();
            }
        }
    }
    exit();
}

if ($op == 'delete') {
    $config_handler =& xoops_gethandler('config');
    $xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
    if (!$xoopsUser || $xoopsConfigUser['self_delete'] != 1) {
        redirect_header('index.php',5,_US_NOPERMISS);
        exit();
    } else {
        $groups = $xoopsUser->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $groups)){
            // users in the webmasters group may not be deleted
            redirect_header('user.php', 5, _US_ADMINNO);
            exit();
        }
        $ok = !isset($_POST['ok']) ? 0 : intval($_POST['ok']);
        if ($ok != 1) {
            include 'header.php';
            xoops_confirm(array('op' => 'delete', 'ok' => 1), 'user.php', _US_SURETODEL.'<br/>'._US_REMOVEINFO);
            include 'footer.php';
        } else {
            $del_uid = $xoopsUser->getVar("uid");
            $member_handler =& xoops_gethandler('member');
            if (false != $member_handler->deleteUser($xoopsUser)) {
                $online_handler =& xoops_gethandler('online');
                $online_handler->destroy($del_uid);
                xoops_notification_deletebyuser($del_uid);
                redirect_header('index.php', 5, _US_BEENDELED);
				exit();
            }
            redirect_header('index.php',5,_US_NOPERMISS);
			exit();
        }
        exit();
    }
}
?>