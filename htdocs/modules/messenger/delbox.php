<?php
// $Id: delete.php,v 1.5 2005/05/15 12:24:47 phppp Exp $
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
/* Include the header */
include_once("header.php");

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';
$catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);
$sortorder = !empty($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'desc';
$sortname = !empty($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'msg_time';
$after = !empty($_REQUEST['after']) ? $_REQUEST['after'] : '';
$limit_msg = !empty($_REQUEST['limit_msg']) ? $_REQUEST['limit_msg'] : '';

if (empty($_REQUEST['msg_mp']) && empty($_SESSION['msg_mp'])) {
 redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
    exit();
}

global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("".XOOPS_URL."/user.php",2,_PM_REGISTERNOW);
	exit();
	}

switch( $option )
{
//supr le message
case "delmp":
//supprime
$size = count($_SESSION['msg_mp']);
$msg =& $_SESSION['msg_mp'];

 for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]);
 
 //upload
 mp_delupload($pm->getVar('file_msg'));
 
 $erreur = $pm_handler->delete($pm);
 }
 
 unset($pm); 


 if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else { 
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_DELETED);
}
   break;

//supprimer la conversation   
case "delmps":

 $size = count($_SESSION['msg_mp']);
 $msg =& $_SESSION['msg_mp'];

 for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]);
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('msg_pid', $pm->getVar('msg_pid'))); 
 }
 mp_delupload($pm->getVar('file_msg'));
 $erreur = $pm_handler->deleteAll($criteria);
 unset($pm); 


 if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else { 
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_DELETED);
}
   break;

case "reads":
$size = count($_SESSION['msg_mp']);
$msg =& $_SESSION['msg_mp'];
$read = empty($_REQUEST['read'])?0:intval($_REQUEST['read']);
for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]); 
 $erreur = $pm_handler->setReadall($pm);
}
if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else {
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_CLASSE);
}

break;
 
case "read":
$size = count($_SESSION['msg_mp']);
$msg =& $_SESSION['msg_mp'];
$read = empty($_REQUEST['read'])?0:intval($_REQUEST['read']);
for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]); 
 $erreur = $pm_handler->setRead($pm, $read);
}
if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else {
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_CLASSE);
}

break;

case "move":
$size = count($_SESSION['msg_mp']);
$msg =& $_SESSION['msg_mp'];
$cat_msg = empty($_REQUEST['ct_file'])?1:intval($_REQUEST['ct_file']);

for ( $i = 0; $i < $size; $i++ ) {

 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]); 
 $erreur = $pm_handler->setMove($pm, $cat_msg);
        }
if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else {
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_MOVE);
}
break;

case "moves":
$size = count($_SESSION['msg_mp']);
$msg =& $_SESSION['msg_mp'];
$cat_msg = empty($_REQUEST['ct_file'])?1:intval($_REQUEST['ct_file']);
for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->get($msg[$i]); 
 $erreur = $pm_handler->setMoveall($pm, $cat_msg);
}
if (!$erreur) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
 } else {
redirect_header("msgbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_MOVE);
}

break;

case "default":
   default:

include XOOPS_ROOT_PATH."/header.php";
	
$_SESSION['msg_mp'] =& $_REQUEST['msg_mp'];

if (@$_REQUEST['option'] == 'move_messages' && isset($_REQUEST['msg_mp']))
 {
 xoops_confirm(array('ct_file' => $_REQUEST['ct_file'], 'catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'move'), 'delbox.php', _MP_IMSURMOVE);
 }
 	
elseif (@$_REQUEST['option'] == 'move_messagess' && isset($_REQUEST['msg_mp']))
 {
 xoops_confirm(array('ct_file' => $_REQUEST['ct_file'], 'catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'moves'), 'delbox.php', _MP_IMSURMOVEALL);
 }
 
elseif (@$_REQUEST['option'] == 'read_messages' && isset($_REQUEST['msg_mp']))
 {
 xoops_confirm(array('read' => $_REQUEST['read'], 'catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'read'), 'delbox.php', _MP_IMSURREAD);
 }

elseif (@$_REQUEST['option'] == 'read_messagess' && isset($_REQUEST['msg_mp']))
 {
 xoops_confirm(array('read' => $_REQUEST['read'], 'catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'reads'), 'delbox.php', _MP_IMSURREADALL);
 }
	
elseif (@$_REQUEST['option'] == 'delete_messages' && isset($_REQUEST['msg_mp'])) 
 {
  xoops_confirm(array('catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'delmp'), 'delbox.php', _MP_IMSURONE);
 }
 
 elseif (@$_REQUEST['option'] == 'delete_messagess' && isset($_REQUEST['msg_mp'])) 
 {
  xoops_confirm(array('catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'delmps'), 'delbox.php', _MP_IMSURALL);
 }
 else {
 redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
 }

include XOOPS_ROOT_PATH.'/footer.php';

break;   
}
?>