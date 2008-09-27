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

include 'header.php';

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';
$catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);
$sortorder = !empty($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'desc';
$sortname = !empty($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'msg_time';
$after = !empty($_REQUEST['after']) ? $_REQUEST['after'] : '';
$limit_msg = !empty($_REQUEST['limit_msg']) ? $_REQUEST['limit_msg'] : '';

if (empty($_REQUEST['ct_contact']) && empty($_SESSION['ct_contact'])) {
 redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
    exit();
}

global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
	exit();
	}

switch( $option )
{
//supr le message
case "delmp":
//supprime		
$size = count($_SESSION['ct_contact']);
$msg =& $_SESSION['ct_contact'];
 for ( $i = 0; $i < $size; $i++ ) {
 $pm_handler  = & xoops_gethandler('priv_msgscont');
 $pm =& $pm_handler->get($msg[$i]);
 $erreur = $pm_handler->delete($pm);
 }
 unset($pm);
 
 if (!$erreur) {
redirect_header("javascript:history.go(-1)",50, _PM_REDNON);
 } else { 
 redirect_header("contbox.php?op=box&amp;catbox=$catbox&amp;after=$after&amp;limit_msg=$limit_msg&amp;sortname=$sortname&amp;sortorder=$sortorder", 2, _MP_CONTACTDELETED);
 }
   break;

case "default":
   default:

include XOOPS_ROOT_PATH."/header.php";
	
$_SESSION['ct_contact'] =& $_REQUEST['ct_contact'];

	
if (@$_REQUEST['option'] == 'delete_cont' && isset($_POST['ct_contact'])) 
 {
  xoops_confirm(array('catbox' => $catbox, 'after' => $after, 'limit_msg' => $limit_msg, 'sortname' => $sortname, 'sortorder' => $sortorder, 'option' => 'delmp'), 'delcont.php', _MP_IMSURCONT);
 }
 else {
 redirect_header("javascript:history.go(-1)",50, _PM_REDNON);
 }

include XOOPS_ROOT_PATH.'/footer.php';

break;   
}
?>