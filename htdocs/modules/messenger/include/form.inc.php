<?php
// $Id: forumform.inc.php,v 1.1.1.51 2004/11/15 18:19:20 phppp Exp $
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

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
} 

if (isset($_REQUEST['preview_messages'])) {

$xoopsTpl->assign('mp_selectview', '<span style="background-color: #E9E9E9;border:1px solid #C0C0C0;font-size:10px;">&nbsp; '._MP_PREVIEW.'&nbsp;
<a href="javascript:;" onclick="showForm(\'preview\');">+</a>/
<a href="javascript:;" onclick="hideAll(\'preview\');">-</a>&nbsp;</span>');

@$content .= "<div id='preview'><table border='0' cellspacing='1' cellpadding='4' width='100%' class='outer'> 
<tr>
<th align='center'>".$myts->makeTboxData4Show($_POST['subject'])."</th>
</tr><tr>
   <td class='head' valign='top'><div class='comText'> 
     ".$myts->makeTareaData4Show($_POST['message'])."</td>
  </tr></table></div><br />";	
}
//form

$form = new XoopsThemeForm(_MP_READMSG, "read", $_SERVER['PHP_SELF'].'?send='.$send.'&reply='.$reply.'&cont='.$cont);
$form->setExtra( "enctype='multipart/form-data'" ); 

if ( $reply == 1 ) {
	$user_select_tray = new XoopsFormLabel(_MP_TOC, $pm_uname);
	$texte_hidden = new XoopsFormHidden("to_userid[]", $pm2->getVar("from_userid"));
	$form->addElement($user_select_tray);
    $form->addElement($texte_hidden);
	$reply_hidden = new XoopsFormHidden("reply", $reply);
	$form->addElement($reply_hidden);
    } elseif ( $send2 == 1 ) {
	$user_select_tray = new XoopsFormLabel(_MP_TOC, XoopsUser::getUnameFromId($to_userid));
	$texte_hidden = new XoopsFormHidden("to_userid[]", $to_userid);
	$form->addElement($user_select_tray);
    $form->addElement($texte_hidden);
	} elseif ($cont == 1 && !isset($_POST['preview_messages'])) {
	
	if (empty($_REQUEST['ct_contact'])) {
    redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
	}
	
 $to_username = new MPSelectUser(_MP_TOC, 'to_userid', @$_REQUEST['ct_contact'], '', 3, true); 
 $to_username->setDescription(sprintf(_MP_UNOTE,$xoopsModuleConfig['senduser']));
 $form->addElement($to_username);
	}
	
	else {	 
 $to_username = new MPSelectUser(_MP_TOC, 'to_userid', @$_REQUEST['to_userid'], '', 3, true); 
 $to_username->setDescription(sprintf(_MP_UNOTE,$xoopsModuleConfig['senduser']));
 $form->addElement($to_username);
 
 }		

//$select_form

//form
if ( $reply != 1 &&  $send2 != 1 && $cont != 1 ) {

$groupe_select = new XoopsFormSelect(_MP_GROUPE, "to_groupe", false, "", 5, true);
		$member_handler =& xoops_gethandler('member');
		$group_list = &$member_handler->getGroupList();
		
foreach ($group_list as $group_id => $group_name) {
foreach ($groupe_perms as $perm_name => $perm_data) {
if ($perm_data == $group_id) {
     $groupe_select->addOption($group_id, $group_name);
}
}
}
if (!empty($perm_data)) {
		$form->addElement($groupe_select);
}
	}
	


$icons_radio = new XoopsFormRadio(_MP_MESSAGEICON, 'msg_image', $icon);
$subject_icons = XoopsLists::getSubjectsList();
foreach ($subject_icons as $iconfile) {
	$icons_radio->addOption($iconfile, '<img src="'.XOOPS_URL.'/images/subject/'.$iconfile.'" alt="" />');
}
$form->addElement($icons_radio); 

if( ( $view_perms & GPERM_OEIL ) ) {
    $indeximage_select = new XoopsFormText('', 'anim_msg', 25, 50, $anim);
	$indeximage_select -> setExtra("readonly=\"readonly\" ");
    $indeximage_tray = new XoopsFormElementTray(_MP_MESSAGEOEIL, '&nbsp;');
    $indeximage_tray->addElement($indeximage_select);
    $indeximage_tray->addElement(new XoopsFormLabel('', "<A HREF=\"javascript:;\" onClick=\"window.open('pop.php','_blank','toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=0, copyhistory=0, menuBar=0, width=700, height=400');return false;\"><img src=\"images/popup.gif\">&nbsp;"._MP_MESSAGEVUOEIL."</A>"));
    $form->addElement($indeximage_tray);
}



$form->addElement(new XoopsFormText(_MP_SUBJECTC, "subject", 50, 100, $subject), true);

$editor = WysiwygForm(_MP_MESSAGEC, "message", $message, '100%', '400px', $formtype);
$form->addElement($editor);

//$text_select = new XoopsFormDhtmlTextArea(_MP_MESSAGE, "message", $message, 15, 85);
//$form->addElement($text_select, true);

$button_tray = new XoopsFormElementTray('' ,'');
//upload
if( ( $view_perms & GPERM_UP ) ) {
$indeximage_up = new XoopsFormFile('', 'fileup', $xoopsModuleConfig['mimemax']);  
$indeximage_uptray = new XoopsFormElementTray(_MP_MIMEFILE, '&nbsp;');
$indeximage_uptray->addElement($indeximage_up);
$indeximage_uptray->addElement(new XoopsFormLabel('', '<br />'._MP_MIMETYPE.mp_mimetypes().'<br />'._MP_MIME.sprintf(_MD_NUMBYTES, $xoopsModuleConfig['mimemax'])));
$form->addElement($indeximage_uptray);

$up_tray = new XoopsFormElementTray(_MP_MIMEFILE, '&nbsp;');
$up_tray->addElement(new XoopsFormLabel('', "<div id=\"files_list\"></div>
<script>
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), ".$xoopsModuleConfig['upmax']." );
	multi_selector.addElement( document.getElementById( 'fileup' ) );
</script>"));
$form->addElement($up_tray);
}


//
$post_button = new XoopsFormButton('', 'post_messages', _MP_SUBMIT, "submit");
$button_tray->addElement($post_button);
//preview
$preview_button = new XoopsFormButton('', 'preview_messages', _MP_PREVIEW, "submit");
$button_tray->addElement($preview_button);
// quote
if ($reply == 1) {
$quote_button = new XoopsFormButton('', 'quote', _MP_QUOTE, 'button');
$quote_button->setExtra("onclick='xoopsGetElementById(\"message\").value=xoopsGetElementById(\"message\").value+ xoopsGetElementById(\"hidden_quote\").value;xoopsGetElementById(\"hidden_quote\").value=\"\";'");
$button_tray->addElement($quote_button);
}
//
$button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CLEAR, 'reset'));
$form->addElement($button_tray);
$form->addElement(new XoopsFormHidden('hidden_quote', $hidden_quote));
$form_formtype = new XoopsFormHidden("formtype", $formtype);
$form->addElement($form_formtype);
$msg_hidden = new XoopsFormHidden("msg_mp", $msg_mp);
$form->addElement($msg_hidden);

@$content .= $form->render();

$xoopsTpl->assign('mp_form', $content);
?>
