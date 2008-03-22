<?php
// $Id: viewpmsg.php 506 2006-05-26 23:10:37Z skalpa $
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

if (!is_object($xoopsUser)) {
    $errormessage = _PM_SORRY."<br />"._PM_PLZREG."";
    redirect_header("user.php",2,$errormessage);
} else {
    $pm_handler =& xoops_gethandler('privmessage');
    if (isset($_POST['delete_messages']) && isset($_POST['msg_id'])) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            echo implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
            exit();
        }
        $size = count($_POST['msg_id']);
        $msg =& $_POST['msg_id'];
            for ( $i = 0; $i < $size; $i++ ) {
            $pm =& $pm_handler->get($msg[$i]);
            if ($pm->getVar('to_userid') == $xoopsUser->getVar('uid')) {
                $pm_handler->delete($pm);
            }
            unset($pm);
        }
        redirect_header("viewpmsg.php",1,_PM_DELETED);
        exit();
    }
    include XOOPS_ROOT_PATH.'/header.php';
	$criteria = new Criteria('to_userid', intval($xoopsUser->getVar('uid')));
	$criteria->setOrder('DESC');
	$pm_arr =& $pm_handler->getObjects($criteria);
    echo "<h4 style='text-align:center;'>". _PM_PRIVATEMESSAGE ."</h4><br /><a href='userinfo.php?uid=". intval($xoopsUser->getVar("uid"))."'>". _PM_PROFILE ."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;". _PM_INBOX ."<br /><br />";
    echo "<form name='prvmsg' method='post' action='viewpmsg.php'>";
	echo "<table border='0' cellspacing='1' cellpadding='4' width='100%' class='outer'>\n";
    echo "<tr align='center' valign='middle'><th><input name='allbox' id='allbox' onclick='xoopsCheckAll(\"prvmsg\", \"allbox\");' type='checkbox' value='Check All' /></th><th><img src='images/download.gif' alt='' border='0' /></th><th>&nbsp;</th><th>". _PM_FROM ."</th><th>". _PM_SUBJECT ."</th><th align='center'>". _PM_DATE ."</th></tr>\n";
    $total_messages = count($pm_arr);
    if ( $total_messages == 0 ) {
        echo "<tr><td class='even' colspan='6' align='center'>"._PM_YOUDONTHAVE."</td></tr> ";
        $display = 0;
    } else {

        $display = 1;
    }
    for ($i = 0; $i < $total_messages; $i++) {
        $class = ($i % 2 == 0) ? 'even' : 'odd';
        echo "<tr align='left' class='$class'><td valign='top' width='2%' align='center'><input type='checkbox' id='msg_id[]' name='msg_id[]' value='".$pm_arr[$i]->getVar("msg_id")."' /></td>\n";
        if ($pm_arr[$i]->getVar('read_msg') == 1) {
            echo "<td valign='top' width='5%' align='center'>&nbsp;</td>\n";
        } else {
            echo "<td valign='top' width='5%' align='center'><img src='images/read.gif' alt='"._PM_NOTREAD."' /></td>\n";
        }
        echo "<td valign='top' width='5%' align='center'><img src='images/subject/".$pm_arr[$i]->getVar("msg_image", "E")."' alt='' /></td>\n";
        $postername = XoopsUser::getUnameFromId($pm_arr[$i]->getVar("from_userid"));
        echo "<td valign='middle' width='10%'>";
        // no need to show deleted users
        if ($postername) {
            echo "<a href='userinfo.php?uid=".intval($pm_arr[$i]->getVar("from_userid"))."'>".$postername."</a>";
        } else {
            echo $xoopsConfig['anonymous'];
        }
        echo "</td>\n";
		echo "<td valign='middle'><a href='readpmsg.php?start=".intval(($total_messages-$i-1)),"&amp;total_messages=".intval($total_messages)."'>".$pm_arr[$i]->getVar("subject")."</a></td>";
        echo "<td valign='middle' align='center' width='20%'>".formatTimestamp($pm_arr[$i]->getVar("msg_time"))."</td></tr>";
    }

    if ( $display == 1 ) {
        echo "<tr class='foot' align='left'><td colspan='6' align='left'><input type='button' class='formButton' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send=1\",\"pmlite\",670,480);' value='"._PM_SEND."' />&nbsp;<input type='submit' class='formButton' name='delete_messages' value='"._PM_DELETE."' />".$GLOBALS['xoopsSecurity']->getTokenHTML()."</td></tr></table></form>";
    } else {
        echo "<tr class='bg2' align='left'><td colspan='6' align='left'><input type='button' class='formButton' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send=1\",\"pmlite\",670,480);' value='"._PM_SEND."' /></td></tr></table></form>";
    }
    include "footer.php";
}
?>
