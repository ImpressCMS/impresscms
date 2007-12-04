<?php
// $Id: readpmsg.php 2 2005-11-02 18:23:29Z skalpa $
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

$xoopsOption['pagetype'] = "pmsg";
include_once "mainfile.php";

if ( !is_object($xoopsUser) ) {
    redirect_header("user.php",0);
    exit();
} else {
    $pm_handler =& xoops_gethandler('privmessage');
    if ( !empty($_POST['delete']) ) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            echo implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
            exit();
        }
        $pm =& $pm_handler->get(intval($_POST['msg_id']));
        if (!is_object($pm) || $pm->getVar('to_userid') != $xoopsUser->getVar('uid') || !$pm_handler->delete($pm)) {
            exit();
        } else {
            redirect_header("viewpmsg.php",1,_PM_DELETED);
            exit();
        }
    }
    $start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
    $total_messages = !empty($_GET['total_messages']) ? intval($_GET['total_messages']) : 0;
    include XOOPS_ROOT_PATH.'/header.php';
    $criteria = new Criteria('to_userid', $xoopsUser->getVar('uid'));
    $criteria->setLimit(1);
    $criteria->setStart($start);
    $criteria->setSort('msg_time');
    $pm_arr =& $pm_handler->getObjects($criteria);
    echo "<div><h4>". _PM_PRIVATEMESSAGE."</h4></div><br /><a href='userinfo.php?uid=". $xoopsUser->getVar("uid") ."'>". _PM_PROFILE ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<a href='viewpmsg.php'>". _PM_INBOX ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;\n";
    if (empty($pm_arr)) {
        echo '<br /><br />'._PM_YOUDONTHAVE;
    } else {
        if (!$pm_handler->setRead($pm_arr[0])) {
            //echo "failed";
        }
        echo $pm_arr[0]->getVar("subject")."<br /><form action='readpmsg.php' method='post' name='delete".$pm_arr[0]->getVar("msg_id")."'><table border='0' cellpadding='4' cellspacing='1' class='outer' width='100%'><tr><th colspan='2'>". _PM_FROM ."</th></tr><tr class='even'>\n";
        $poster = new XoopsUser($pm_arr[0]->getVar("from_userid"));
        if ( !$poster->isActive() ) {
            $poster = false;
        }
        echo "<td valign='top'>";
        if ( $poster != false ) { // we need to do this for deleted users
                echo "<a href='userinfo.php?uid=".$poster->getVar("uid")."'>".$poster->getVar("uname")."</a><br />\n";
            if ( $poster->getVar("user_avatar") != "" ) {
                echo "<img src='uploads/".$poster->getVar("user_avatar")."' alt='' /><br />\n";
            }
            if ( $poster->getVar("user_from") != "" ) {
                echo _PM_FROMC."".$poster->getVar("user_from")."<br /><br />\n";
            }
            if ( $poster->isOnline() ) {
            echo "<span style='color:#ee0000;font-weight:bold;'>"._PM_ONLINE."</span><br /><br />\n";
            }
        } else {
            echo $xoopsConfig['anonymous']; // we need to do this for deleted users
        }
        echo "</td><td><img src='images/subject/".$pm_arr[0]->getVar("msg_image", "E")."' alt='' />&nbsp;"._PM_SENTC."".formatTimestamp($pm_arr[0]->getVar("msg_time"));
        echo "<hr /><b>".$pm_arr[0]->getVar("subject")."</b><br /><br />\n";
        echo $pm_arr[0]->getVar("msg_text") . "<br /><br /></td></tr><tr class='foot'><td width='20%' colspan='2' align='left'>";
        // we dont want to reply to a deleted user!
        if ( $poster != false ) {
            echo "<a href='#' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?reply=1&amp;msg_id=".$pm_arr[0]->getVar("msg_id")."\",\"pmlite\",450,380);'><img src='".XOOPS_URL."/images/icons/reply.gif' alt='"._PM_REPLY."' /></a>\n";
        }
        echo "<input type='hidden' name='delete' value='1' />";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<input type='hidden' name='msg_id' value='".$pm_arr[0]->getVar("msg_id")."' />";
        echo "<a href='#".$pm_arr[0]->getVar("msg_id")."' onclick='javascript:document.delete".$pm_arr[0]->getVar("msg_id").".submit();'><img src='".XOOPS_URL."/images/icons/delete.gif' alt='"._PM_DELETE."' /></a>";
        echo "</td></tr><tr><td colspan='2' align='right'>";
        $previous = $start - 1;
            $next = $start + 1;
            if ( $previous >= 0 ) {
            echo "<a href='readpmsg.php?start=".$previous."&amp;total_messages=".$total_messages."'>"._PM_PREVIOUS."</a> | ";
        } else {
            echo _PM_PREVIOUS." | ";
        }
        if ( $next < $total_messages ) {
            echo "<a href='readpmsg.php?start=".$next."&amp;total_messages=".$total_messages."'>"._PM_NEXT."</a>";
        } else {
            echo _PM_NEXT;
        }
        echo "</td></tr></table></form>\n";
    }
    include "footer.php";
}
?>