<?php
// $Id: main.php 1029 2007-09-09 03:49:25Z phppp $
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
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}
include_once XOOPS_ROOT_PATH."/modules/system/admin/users/users.php";
$allowedHTML = array('user_sig','bio');

if(!empty($_POST)){ foreach($_POST as $k => $v){ if (!in_array($k,$allowedHTML)){${$k} = StopXSS($v);}else{${$k} = $v;}}}
if(!empty($_GET)){ foreach($_GET as $k => $v){ if (!in_array($k,$allowedHTML)){${$k} = StopXSS($v);}else{${$k} = $v;}}}
$op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'mod_users');
if(isset($_GET['op']))
{
	if(isset($_GET['uid'])) {$uid = intval($_GET['uid']);}
}
switch ($op) {

case "modifyUser":
    modifyUser($uid);
    break;
case "updateUser":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=users", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    // RMV-NOTIFY
    $user_avatar = $theme = null;
	if ( !isset( $attachsig ) ) $attachsig = null;
	if ( !isset( $user_viewemail ) ) $user_viewemail = null;
    updateUser($uid, $username, $name, $url, $email, $user_icq, $user_aim, $user_yim, $user_msnm, $user_from, $user_occ, $user_intrest, $user_viewemail, $user_avatar, $user_sig, $attachsig, $theme, $password, $pass2, $rank, $bio, $uorder, $umode, $notify_method, $notify_mode, $timezone_offset, $user_mailok, $language, $openid, $salt, $user_viewoid, $groups);
    break;
case "delUser":
    xoops_cp_header();
    $member_handler =& xoops_gethandler('member');
    $userdata =& $member_handler->getUser($uid);
    xoops_confirm(array('fct' => 'users', 'op' => 'delUserConf', 'del_uid' => $userdata->getVar('uid')), 'admin.php', sprintf(_AM_AYSYWTDU,$userdata->getVar('uname')));
    xoops_cp_footer();
    break;
case "delete_many":
    xoops_cp_header();
    $count = count($memberslist_id);
    if ( $count > 0 ) {
        $list = "<a href='".XOOPS_URL."/userinfo.php?uid=".$memberslist_id[0]."' rel='external'>".$memberslist_uname[$memberslist_id[0]]."</a>";
        $hidden = "<input type='hidden' name='memberslist_id[]' value='".$memberslist_id[0]."' />\n";
        for ( $i = 1; $i < $count; $i++ ) {
            $list .= ", <a href='".XOOPS_URL."/userinfo.php?uid=".$memberslist_id[$i]."' rel='external'>".$memberslist_uname[$memberslist_id[$i]]."</a>";
            $hidden .= "<input type='hidden' name='memberslist_id[]' value='".$memberslist_id[$i]."' />\n";
        }
        echo "<div><h4>".sprintf(_AM_AYSYWTDU," ".$list." ")."</h4>";
        echo _AM_BYTHIS."<br /><br />
        <form action='admin.php' method='post'>
        <input type='hidden' name='fct' value='users' />
        <input type='hidden' name='op' value='delete_many_ok' />
        ".$GLOBALS['xoopsSecurity']->getTokenHTML()."
        <input type='submit' value='"._YES."' />
        <input type='button' value='"._NO."' onclick='javascript:location.href=\"admin.php?op=adminMain\"' />";
        echo $hidden;
        echo "</form></div>";
    } else {
        echo _AM_NOUSERS;
    }
    xoops_cp_footer();
    break;
case "delete_many_ok":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=users", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $count = count($memberslist_id);
    $output = "";
    $member_handler =& xoops_gethandler('member');
    for ( $i = 0; $i < $count; $i++ ) {
        $deluser =& $member_handler->getUser($memberslist_id[$i]);
        $delgroups = $deluser->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $delgroups)) {
            $output .= sprintf('Admin user cannot be deleted. (User: %s)', $deluser->getVar("uname"))."<br />";
        } else {
            if (!$member_handler->deleteUser($deluser)) {
                $output .= "Could not delete ".$deluser->getVar("uname")."<br />";
            } else {
                $output .= $deluser->getVar("uname")." deleted<br />";
            }
            // RMV-NOTIFY
            xoops_notification_deletebyuser($deluser->getVar('uid'));
        }
    }
    xoops_cp_header();
    echo $output;
    xoops_cp_footer();
    break;
case "delUserConf":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=users", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $member_handler =& xoops_gethandler('member');
    $user =& $member_handler->getUser($del_uid);
    $groups = $user->getGroups();
    if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
        xoops_cp_header();
        echo sprintf('Admin user cannot be deleted. (User: %s)', $user->getVar("uname"));
        xoops_cp_footer();
    } elseif (!$member_handler->deleteUser($user)) {
        xoops_cp_header();
        echo "Could not delete ".$deluser->getVar("uname");
        xoops_cp_footer();
    } else {
        $online_handler =& xoops_gethandler('online');
        $online_handler->destroy($del_uid);
        // RMV-NOTIFY
        xoops_notification_deletebyuser($del_uid);
        redirect_header("admin.php?fct=users",1,_AM_DBUPDATED);
    }
    break;
case "addUser":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=users", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    if (!$username || !$email || !$password) {
        $adduser_errormsg = _AM_YMCACF;
    } else {
        $member_handler =& xoops_gethandler('member');
        // make sure the username doesnt exist yet
        if ($member_handler->getUserCount(new Criteria('uname', $username)) > 0) {
            $adduser_errormsg = 'User name '.$username.' already exists';
        } else {
            $newuser =& $member_handler->createUser();
            if ( isset($user_viewemail) ) {
                $newuser->setVar("user_viewemail",$user_viewemail);
            }
            if ( isset($attachsig) ) {
                $newuser->setVar("attachsig",$attachsig);
            }
            $newuser->setVar("name", $name);
            $newuser->setVar("uname", $username);
            $newuser->setVar("email", $email);
            if(isset($user_viewoid))
		  {
                $newuser->setVar("user_viewoid",$user_viewoid);
            }
            $newuser->setVar("openid", $openid);
            $newuser->setVar("url", formatURL($url));
            $newuser->setVar("user_avatar",'blank.gif');
            $newuser->setVar("user_icq", $user_icq);
            $newuser->setVar("user_from", $user_from);
            $newuser->setVar("user_sig", $user_sig);
            $newuser->setVar("user_aim", $user_aim);
            $newuser->setVar("user_yim", $user_yim);
            $newuser->setVar("user_msnm", $user_msnm);
            if ($pass2 != "") {
                if ( $password != $pass2 ) {
                    xoops_cp_header();
                    echo "
                    <b>"._AM_STNPDNM."</b>";
                    xoops_cp_footer();
                    exit();
                }
                $newuser->setVar("salt", $salt);
		$password = icms_encryptPass($password, $salt);
                $newuser->setVar("pass", $password);
            }
            $newuser->setVar("timezone_offset", $timezone_offset);
            $newuser->setVar("uorder", $uorder);
            $newuser->setVar("umode", $umode);
            // RMV-NOTIFY
            $newuser->setVar("notify_method", $notify_method);
            $newuser->setVar("notify_mode", $notify_mode);
            $newuser->setVar("bio", $bio);
            $newuser->setVar("rank", $rank);
            $newuser->setVar("level", 1);
            $newuser->setVar("user_occ", $user_occ);
            $newuser->setVar("user_intrest", $user_intrest);
            $newuser->setVar('user_mailok', $user_mailok);
            $newuser->setVar('language', $language);
            if (!$member_handler->insertUser($newuser)) {
                $adduser_errormsg = _AM_CNRNU;
            } else {
            	$groups_failed = array();
				foreach ($groups as $group) {
					if (!$member_handler->addUserToGroup($group, $newuser->getVar('uid'))) {
						$groups_failed[] = $group;
					}
				}
				if (!empty($groups_failed)) {
					$group_names = $member_handler->getGroupList(new Criteria('groupid', "(".implode(", ", $groups_failed).")", 'IN'));
					$adduser_errormsg = sprintf(_AM_CNRNU2, implode(", ", $group_names));
				} else {
					
					/* Hack by marcan <INBOX>
					 * Sending a confirmation email to the newly registered user
					 */
					
					 /**
					  * @todo this has been commented out for now as we need to add a check box on the
					  * form to ask the admin if he wants to send the welcome message or not 
					  */
					/*
					$myts =& MyTextSanitizer::getInstance();
					$xoopsMailer =& getMailer();
					$xoopsMailer->useMail();
					$xoopsMailer->setTemplate('welcome.tpl');
					$xoopsMailer->assign('UNAME', $uname);
		  			$xoopsMailer->assign('PASSWORD', $vpass);
					$xoopsMailer->assign('X_UEMAIL', $email);			
		      		$xoopsMailer->setToEmails($email);
					$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
					$xoopsMailer->setFromName($xoopsConfig['sitename']);
					$xoopsMailer->setSubject(sprintf(_US_YOURREGISTRATION,$myts->stripSlashesGPC($xoopsConfig['sitename'])));
					$xoopsMailer->send();
					/* Hack by marcan <INBOX>
					 * Sending a confirmation email to the newly registered user
					 */			

                    redirect_header("admin.php?fct=users",1,_AM_DBUPDATED);
                }
            }
        }
    }
    xoops_cp_header();
    xoops_error($adduser_errormsg);
    xoops_cp_footer();
    break;
case "synchronize":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=users", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    synchronize($id, $type);
    break;
case "reactivate":
    $result=$xoopsDB->query("UPDATE ".$xoopsDB->prefix("users")." SET level='1' WHERE uid='".intval($uid)."'");
    if(!$result){
        exit();
    }
    redirect_header("admin.php?fct=users&amp;op=modifyUser&amp;uid=".intval($uid),1,_AM_DBUPDATED);
    break;
case "mod_users":
default:
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
    displayUsers();
    break;
}
?>