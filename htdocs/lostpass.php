<?php
// $Id: lostpass.php 2 2005-11-02 18:23:29Z skalpa $
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

$xoopsOption['pagetype'] = "user";
include "mainfile.php";
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : $email;
if ($email == '') {
    redirect_header("user.php",2,_US_SORRYNOTFOUND);
    exit();
}

$myts =& MyTextSanitizer::getInstance();
$member_handler =& xoops_gethandler('member');
$getuser =& $member_handler->getUsers(new Criteria('email', $myts->addSlashes($email)));

if (empty($getuser)) {
    $msg = _US_SORRYNOTFOUND;
    redirect_header("user.php",2,$msg);
    exit();
} else {
    $code = isset($_GET['code']) ? trim($_GET['code']) : '';
    $areyou = substr($getuser[0]->getVar("pass"), 0, 5);
    if ($code != '' && $areyou == $code) {
        $newpass = xoops_makepass();
        $xoopsMailer =& getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass2.tpl");
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign("SITEURL", XOOPS_URL."/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD", $newpass);
        $xoopsMailer->setToUsers($getuser[0]);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,XOOPS_URL));
        if ( !$xoopsMailer->send() ) {
            echo $xoopsMailer->getErrors();
        }

        // Next step: add the new password to the database
        $sql = sprintf("UPDATE %s SET pass = '%s' WHERE uid = '%u'", $xoopsDB->prefix("users"), md5($newpass), intval($getuser[0]->getVar('uid')));
        if ( !$xoopsDB->queryF($sql) ) {
            include "header.php";
            echo _US_MAILPWDNG;
            include "footer.php";
            exit();
        }
        redirect_header("user.php", 3, sprintf(_US_PWDMAILED,$getuser[0]->getVar("uname")), false);
        exit();
    // If no Code, send it
    } else {
        $xoopsMailer =& getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass1.tpl");
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign("SITEURL", XOOPS_URL."/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD_LINK", XOOPS_URL."/lostpass.php?email=".$email."&code=".$areyou);
        $xoopsMailer->setToUsers($getuser[0]);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,$xoopsConfig['sitename']));
        include "header.php";
        if ( !$xoopsMailer->send() ) {
            echo $xoopsMailer->getErrors();
        }
        echo "<h4>";
        printf(_US_CONFMAIL,$getuser[0]->getVar("uname"));
        echo "</h4>";
        include "footer.php";
    }
}
?>