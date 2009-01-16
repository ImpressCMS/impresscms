<?php
// $Id: print.php,v 1.11 2004/09/01 17:48:07 hthouzard Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
 * Print an article
 *
 * This page is used to print an article. The advantage of this script is that you
 * only see the article and nothing else.
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright	(c) The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param 	int		storyid 					Id of news to print
 *
 * @page_title			Story's title - Printer Friendly Page - Topic's title - Site's name
 *
 * @template_name		This page does not use any template
 *
*/
include_once "../../mainfile.php";
include_once XOOPS_ROOT_PATH."/modules/news/class/class.newsstory.php";
include_once XOOPS_ROOT_PATH.'/modules/messenger/include/functions.php';

if ($_SESSION['msg']) {
	$msg_id = $_SESSION['msg'];
} else {
	$msg_id = $_SESSION['msg'];
}

if ( empty($msg_id) ) {
	redirect_header(XOOPS_URL."/modules/messenger/msgbox.php",2,_PM_GOBACK);
}

//verify si utilisateur
global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("user.php",1,_PM_REGISTERNOW);
	}

// Verify permissions
//if(!( $view_perms & GPERM_MESS ) ) {
//redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
//exit();
//}

$xoops_meta_keywords='';
$xoops_meta_description='';


function PrintDouble($msg_id)
{
	global $xoopsDB, $xoopsConfig, $xoopsModule, $story, $xoops_meta_keywords,$xoops_meta_description;
	$myts =& MyTextSanitizer::getInstance();
	
   $size = count($msg_id);
   $msg =& $msg_id;

    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
	echo '<html><head>';
	echo "<title>" . $xoopsConfig['sitename'] . "</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset='._CHARSET.'" />';
	echo '<meta name="AUTHOR" content="'.$xoopsConfig['sitename'].'" />';
    echo "<meta name='COPYRIGHT' content='Copyright (c) ".date('Y')." by " . $xoopsConfig['sitename'] . "' />\n";
	echo "<meta name='DESCRIPTION' content='" . $xoopsConfig['slogan'] . "' />\n";
	echo "<meta name='GENERATOR' content='" . XOOPS_VERSION . "' />\n\n\n";
	echo "<body bgcolor='#ffffff' text='#000000' onload='window.print()'>
    <div style='width: 750px; border: 1px solid #000; padding: 20px;'>
	 	  <div style='text-align: center; display: block; margin: 0 0 6px 0;'>
		  <img src='" . XOOPS_URL . "/modules/messenger/images/mp_logo.png' border='0' alt='' />
		  <br />
		  <br />
		  ";

 for ( $i = 0; $i < $size; $i++ ) {
	$res = $xoopsDB->query("SELECT subject, from_userid, to_userid, msg_time, msg_text from ".$xoopsDB->prefix("priv_msgsave")." where msg_id= '".$msg_id[$i]."'");	
    list($subject, $from_userid, $to_userid, $msg_time, $msg_text) = $xoopsDB->fetchRow($res);
	echo '<h3 style=\'margin: 0;\'>'.$subject.'</h3> 
    <div align=\'center\'><small><b>'._MP_POSTED.'</b>&nbsp;'.formatTimestamp($msg_time).' |  <b>'._MP_FROM2.'</b>: '.XoopsUser::getUnameFromId($from_userid).' |  <b>'._MP_SUBJECT.'</b>&nbsp;'.$myts->htmlSpecialChars($subject).'</small></div><br /><br />
	<div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
	<div style=\'text-align: left\'><tr valign="top" style="font:12px;"><td>'.$msg_text.'</div><br />
	<div style=\'padding-top: 12px; border-top: 2px solid #ccc;\'></div><br />
	<br /><br />';
	}
	
	printf(_MP_THISCOMESFROM,htmlspecialchars($xoopsConfig['sitename'],ENT_QUOTES));
	echo '<br /><a href="'.XOOPS_URL.'/">'.XOOPS_URL.'</a><br />
    	</div></div>
    	</body>
    	</html>
    	';
}


PrintDouble($msg_id);
?>