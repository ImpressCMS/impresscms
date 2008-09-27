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
/* Include the header */
include_once("header.php");

$msg_id = empty($_REQUEST['msg_mp']) ? '' : $_REQUEST['msg_mp'];
$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

if ( empty($msg_id) ) {
	redirect_header(XOOPS_URL."/modules/messenger/msgbox.php",2,_PM_REDNON);
}

//verify si utilisateur
global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsModule, $xoops_meta_keywords ,$xoops_meta_description;

if (empty($xoopsUser)) {
 redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
	}

// Verify permissions
//if(!( $view_perms & GPERM_MESS ) ) {
//redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
//exit();
//}

$xoops_meta_keywords='';
$xoops_meta_description='';

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
		 <h3>" . $xoopsConfig['sitename'] . "</h3><h5>" . $xoopsConfig['slogan'] . "</h5>
		  <br />
		  ";
	  
 for ( $i = 0; $i < $size; $i++ ) {
 
switch( $option )
{
case "default":
   default:
   redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
   break;
case "print_messages":
  $pm_handler  = & xoops_gethandler('priv_msgs'); 
  $pm =& $pm_handler->get($msg_id[$i]);
  
  	echo '<h4 style=\'margin: 0;\'>'.$pm->getVar('subject').'</h4> 
    <div align=\'center\'><small><b>'._MP_POSTED.'</b>&nbsp;'.formatTimestamp($pm->getVar('msg_time')).' |  <b>'._MP_FROM2.'</b>: '.XoopsUser::getUnameFromId($pm->getVar('from_userid')).' |  <b>'._MP_SUBJECT.'</b>&nbsp;'.$myts->htmlSpecialChars($pm->getVar('subject')).'</small></div><br /><br />
	<div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
	<div style=\'text-align: left\'><tr valign="top" style="font:12px;"><td>'.$pm->getVar('msg_text').'</div><br />
	<div style=\'padding-top: 12px; border-top: 2px solid #ccc;\'></div><br />
	<br /><br />';
	
  break;
  
case "print_messagess":
 $pm_handler  = & xoops_gethandler('priv_msgs'); 
 $pm =& $pm_handler->get($msg_id[$i]);
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('msg_pid', $pm->getVar('msg_pid'))); 
 $pm =& $pm_handler->getObjects($criteria);
 
 foreach (array_keys($pm) as $i) { 
 
 	echo '<h4 style=\'margin: 0;\'>'.$pm[$i]->getVar('subject').'</h4> 
    <div align=\'center\'><small><b>'._MP_POSTED.'</b>&nbsp;'.formatTimestamp($pm[$i]->getVar('msg_time')).' |  <b>'._MP_FROM2.'</b>: '.XoopsUser::getUnameFromId($pm[$i]->getVar('from_userid')).' |  <b>'._MP_SUBJECT.'</b>&nbsp;'.$myts->htmlSpecialChars($pm[$i]->getVar('subject')).'</small></div><br /><br />
	<div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
	<div style=\'text-align: left\'><tr valign="top" style="font:12px;"><td>'.$pm[$i]->getVar('msg_text').'</div><br />
	<div style=\'padding-top: 12px; border-top: 2px solid #ccc;\'></div><br />
	<br /><br />';
 
 }
 break;
 }

	}
	
	printf(_MP_THISCOMESFROM,htmlspecialchars($xoopsConfig['sitename'],ENT_QUOTES));
	echo '<br /><a href="'.XOOPS_URL.'/">'.XOOPS_URL.'</a><br />
    	</div></div>
    	</body>
    	</html>
    	';
?>