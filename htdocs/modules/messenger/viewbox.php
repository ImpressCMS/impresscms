<?php
/**
*
*
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			Venom <webmaster@exode-fr.com>
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/

/* Include the header */
include_once("header.php");

global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
	
} else {

 	
if (isset($_REQUEST['op'])) {
 $op = $_REQUEST['op'];
} else {
 $op = 'view';
}


 $catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);
 $start = empty($_REQUEST['start']) ? "0": intval($_REQUEST['start']);
 $viewstart = empty($_REQUEST['viewstart']) ? "0": intval($_REQUEST['viewstart']);
 $reply = !empty($_REQUEST['reply']) ? intval($_REQUEST['reply']) : 0;
 $after = empty($_REQUEST['after']) ? '' :  $_REQUEST['after'];
 $total_messages = !empty($_REQUEST['total_messages']) ? intval($_REQUEST['total_messages']) : 0;
 $searchmsg = !empty($_REQUEST['sortmsg']) ? intval($_REQUEST['sortmsg']) : 0;
 $sortname = !empty($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'msg_time';
 $sortorder = (empty($_REQUEST['sortorder']) ? (empty($osortorder) ? 'desc' : $osortorder ) : $_REQUEST['sortorder'] );
 $limit_msg = (empty($_REQUEST['limit_msg']) ? (empty($olimite) ? '10' : $olimite ) : intval($_REQUEST['limit_msg']) );
 $vieworder = (empty($_REQUEST['vieworder']) ? (empty($ovieworder) ? 'flat' : $ovieworder ) : $_REQUEST['vieworder'] ); 
 $treesortorder = (empty($_REQUEST['treesortorder']) ? (empty($osortorder) ? 'desc' : $osortorder ) : $_REQUEST['treesortorder'] );	


if (isset($_POST['preview_messages'])) {
	$send = '1';
	$op = 'sendbox';

} elseif (isset($_POST['post_messages'])) {
	$op = 'envoimp';
}


//alert
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $total = $pm_handler->getCount($criteria); 
 $catcriteria = $criteria;
 $catcriteria->add(new Criteria('cat_msg', $catbox)); 
// $pm =& $pm_handler->getObjects($catcriteria); 
//prend la categorie
 $cat_handler  = & xoops_gethandler('priv_msgscat');
 $criteria2 = new CriteriaCompo(); 
 $criteria2->add(new Criteria('cid', $catbox)); 
 $criteria3 = new CriteriaCompo(new Criteria('uid', $xoopsUser->getVar('uid')));
 $criteria3->add(new Criteria('ver', 1), 'OR'); 
 $criteria2->add($criteria3); 

 $pm_cat =& $cat_handler->getObjects($criteria2);
 foreach (array_keys($pm_cat) as $i) { 
 $catpid = $pm_cat[$i]->getVar('pid'); 
 $cattitle = $pm_cat[$i]->getVar('title');
 }
 unset($criteria);
 

$precistotal = number_format(($total*100)/$xoopsModuleConfig['maxuser'], 0, ",", " ");

//alert stockage
if ( $total > $xoopsModuleConfig['maxuser']) {
    $msg_alert = _MP_ALERT."<br />"._MP_AVERT;
	$mpstop = "stop";		
} 

			  
switch( $op )
{	   
//voir
	   
case "view":
  default:
 $xoopsConfig['module_cache'] = 0;
 $xoopsOption['template_main'] = 'mp_viewbox.html'; 
 include XOOPS_ROOT_PATH."/header.php";
 
 //mode recherche
 if (!empty($searchmsg)) {
  $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('cat_msg', $catbox));
 $criteria->add(new Criteria('msg_id', $sortmsg));
 $criteria->setLimit(1);
 $pm =& $pm_handler->getObjects($criteria);
 } else {
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('cat_msg', $catbox));
 if (@$_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('msg_time', $after,">"));		
 }

 //$criteria->add(new Criteria('msg_id', $sortmsg));
 $criteria->setStart($start);
 $criteria->setLimit(1);
 $criteria->setSort($sortname);
 $criteria->setOrder($sortorder);
 $pm =& $pm_handler->getObjects($criteria);
 }
 //tree
 if (count($pm) > 0) {
 $threadcriteria = new CriteriaCompo(); 
 $threadcriteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $threadcriteria->add(new Criteria('cat_msg', $catbox)); 
 $threadcriteria->add(new Criteria('msg_pid', $pm[$i]->getVar('msg_pid'))); 
 $viewtotal = $pm_handler->getCount($threadcriteria); 
 $threadcriteria->setOrder($treesortorder);
 $threadcriteria->setSort($sortname);
 $threadcriteria->setOrder($sortorder);
 $threadcriteria->setLimit($limit_msg);
 $threadcriteria->setStart($viewstart);
 $pm_tree =& $pm_handler->getObjects($threadcriteria); 
 }

 if(is_object($pm)){
 redirect_header("msgbox.php",1, _PM_REDNON);
 } else {
//$xoopsTpl->assign('lang_read', _MP_READ);
$xoopsTpl->assign('mp_selectview', mp_sortorder());
$xoopsTpl->assign('lang_from', _PM_FROM);
$xoopsTpl->assign('lang_subject', _PM_SUBJECT);
$xoopsTpl->assign('lang_date', _PM_DATE);
$xoopsTpl->assign('lang_tris', _MP_TRIS);
$xoopsTpl->assign('lang_vous', _MP_VOUS);
$xoopsTpl->assign('lang_msg', _MP_MSG);
$xoopsTpl->assign('lang_send', _PM_SEND);
$xoopsTpl->assign('lang_joined', _MP_JOINED);
$xoopsTpl->assign('lang_from2', _MP_FROM2);
$xoopsTpl->assign('lang_posts', _MP_POSTS);
$xoopsTpl->assign('lang_posted', _MP_POSTED);
//$xoopsTpl->assign('lang_ferm', '<a href="javascript:;"><img onClick="mp_cache(\'col1\');" src=\'images/close.png\' title='._MP_CLOSEWIN.' style="cursor:pointer; border-width: 0px; width: 15px; height: 15px;"></a>');

 
foreach (array_keys($pm) as $i) {
//$eachpost = mp_post($view_perms, $pm[$i], @$mpstop);
$result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("priv_msgs")." SET read_msg = '1' WHERE msg_id=".$pm[$i]->getVar('msg_id')."");
	
 	
 $xoopsTpl->assign('msg_id', $pm[$i]->getVar('msg_id'));

//reponse rapide
$pm_uname = XoopsUser::getUnameFromId($pm[$i]->getVar('from_userid'));
$hidden_quote = "[quote]\n";
$hidden_quote .= sprintf(_PM_USERWROTE,$pm_uname);
$hidden_quote .= "\n".$myts->makeTboxData4Show($pm[$i]->getVar('msg_text'))."[/quote]";
//creation du formulaire de quickreply
$form = new XoopsThemeForm(_MP_READMSG, "read", "msgbox.php?send=0&reply=1");
$form->setExtra( "enctype='multipart/form-data'" ); 
$user_select_tray = new XoopsFormLabel(_MP_TOC, XoopsUser::getUnameFromId($pm[$i]->getVar('from_userid')));
$texte_hidden = new XoopsFormHidden("to_userid[]", $pm[$i]->getVar('from_userid'));
$form->addElement($user_select_tray);
$form->addElement($texte_hidden);

$form->addElement(new XoopsFormText(_MP_SUBJECTC, "subject", 50, 100, 'Re: '.$pm[$i]->getVar('subject')), true);
	
$text_select = new XoopsFormDhtmlTextArea(_MP_MESSAGEC, "message", "", 15, 85);
$form->addElement($text_select, true);

$button_tray = new XoopsFormElementTray('' ,'');
$quote_button = new XoopsFormButton('', 'quote', _MP_QUOTE, 'button');
$quote_button->setExtra("onclick='xoopsGetElementById(\"message\").value=xoopsGetElementById(\"message\").value+ xoopsGetElementById(\"hidden_quote\").value;xoopsGetElementById(\"hidden_quote\").value=\"\";'");
$button_tray->addElement($quote_button);
$button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CLEAR, 'reset'));
$button_tray->addElement(new XoopsFormButton('', 'post_messages', _MP_SUBMIT, 'submit'));
$form->addElement($button_tray);

$form->addElement(new XoopsFormHidden("msg_mp", $pm[$i]->getVar('msg_id')));
$form->addElement(new XoopsFormHidden('hidden_quote', $hidden_quote));
$form->addElement(new XoopsFormHidden("icon", ""));
//$form->addElement(new XoopsFormHidden("anim", ""));
$form->addElement(new XoopsFormHidden("reply", "1"));

$quick_reply = $form->render();

$xoopsTpl->assign('mp_quik_reply', "<a href='#quick_reply' onclick='javascript: document.getElementById(\"quick_reply\").style.display=\"block\";'>". _MP_QUICKREPLY."</a>");
$xoopsTpl->assign('mp_quik_post', "<div name='quick_reply' id='quick_reply' style='display: none;'>".$quick_reply."</div>");
 }
 
foreach (array_keys($pm_tree) as $i) { 
$result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("priv_msgs")." SET read_msg = '1' WHERE msg_id=".$pm_tree[$i]->getVar('msg_id').""); 
 


if ($vieworder == 'thread') {
$msg_thread = !empty($_REQUEST['msg_thread']) ? intval($_REQUEST['msg_thread']) : $pm_tree[$i]->getVar('msg_pid');
if ($msg_thread != $pm_tree[$i]->getVar('msg_id')) {

if (!$pm_tree[$i]->getVar('msg_image')) {
 $tree_img = "<img src='../../images/read.gif' alt='' />";
 } else {
 $tree_img = "<img src='../../images/subject/".$pm_tree[$i]->getVar('msg_image')."' alt='' />";
 }
 
$tree_poster = new XoopsUser($pm_tree[$i]->getVar("from_userid"));
if ( !$tree_poster->isActive() ) {
$poster = $myts->HtmlSpecialChars($xoopsConfig['anonymous']);
 } else {
$poster = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$tree_poster->getVar("uid").'">'.$tree_poster->getVar("uname").'</a>';
 }

 @$prefix .= "&nbsp;&nbsp;";
 $xoopsTpl->append("topic_trees", array(
 "post_id" => $pm_tree[$i]->getVar('msg_id'), 
 "post_time" => formatTimestamp($pm_tree[$i]->getVar('msg_time')), 
 "post_image" => $tree_img,  
 "post_title" => '<a href="viewbox.php?op=view&start='.$start.'&sortname='.$sortname.'&sortorder='.$sortorder.'&vieworder='.$vieworder.'&treesortorder='.$treesortorder.'&catbox='.$catbox.'&viewstart='.$viewstart.'&msg_thread='.$pm_tree[$i]->getVar('msg_id').'#'.$pm_tree[$i]->getVar('msg_id').'">'.$myts->makeTboxData4Show($pm_tree[$i]->getVar('subject')).'</a>', 
 "post_prefix" => $prefix, 
 "poster" => $poster));
 } else {
 $eachpost = mp_post($view_perms, $pm_tree[$i], @$mpstop);
 } } else {
 $eachpost = mp_post($view_perms, $pm_tree[$i], @$mpstop);
  }
	
//gestion animation		
if ( @$HTTP_GET_VARS['anim'] == "stop" ) {
 $result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("priv_msgs")." SET anim_msg = '' WHERE msg_id = ".$pm[$i]->getVar('msg_id').""); 
   }

 
	}

 
 $previous = $start - 1;
 $viewprevious = $viewstart - $limit_msg;
 $next = $start + 1 + $i;
 $viewnext = $viewstart + $limit_msg;
       
 if ( $previous >= 0 ) {
$lang_previous = "<a href='viewbox.php?op=view&sortname=".$sortname."&sortorder=".$sortorder."&after=".$after."&start=".$previous."&total_messages=".$total_messages."&vieworder=".$vieworder."&catbox=".$catbox."' title="._MP_PREVIOUS.">&nbsp;<<&nbsp;</a>"; 
 }	else { $lang_previous = ''; }
 
  if ( $viewtotal > $limit_msg ) {
$nav = new XoopsPageNav($viewtotal, $limit_msg, $viewstart, "viewstart", 'viewbox.php?op=view&sortname='.$sortname.'&sortorder='.$sortorder.'&after='.$after.'&start='.$start.'&total_messages='.$total_messages.'&vieworder='.$vieworder.'&catbox='.$catbox);
$xoopsTpl->assign('lang_previous', $lang_previous.$nav->renderNav(4));
 } 
 		
 if ( $viewtotal < $total_messages) {
 $lang_next = "<a href='viewbox.php?op=view&sortname=".$sortname."&sortorder=".$sortorder."&after=".$after."&start=".$next."&amp;total_messages=".$total_messages."&vieworder=".$vieworder."&catbox=".$catbox."' title="._MP_NEXT.">&nbsp;>>&nbsp;</a>";
 } else { $lang_next = ''; }

 $xoopsTpl->assign('lang_next', $lang_next);
 
	}
break; 

    }

//submit & perms
if( ( $view_perms & GPERM_MESS ) ) {
if (empty($mpstop)) {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;"><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
if ($catbox == 2) {
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='stop' disabled value='"._MP_MREPLY."'>";
}else {
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='reply' value='"._MP_MREPLY."'>";
} }else {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;" disabled><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='stop' disabled value='"._MP_MREPLY."'>";
}
}
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=delete_messagess\"' id='del'  value='"._MP_MDEL."'>";
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=read_messagess&read=0\"' id='nlu'  value='"._MP_MNLU."'>";
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=move_messagess\"' id='move'  value='"._MP_MMOVE."'>";
$box_actions[] = "<input type='button' OnClick=\"msgpop('&nbsp;"._MP_HELP_MSGVIEW."&nbsp;')\" value='?'><div id=\"tooltip\" style=\"visibility: hidden;\"></div>";
$box_actions[] = "<input type='hidden' name='catbox' value='".$catbox."'>";
$box_actions[] = "<input type='hidden' name='after' value='".$after."'>";
$box_actions[] = "<input type='hidden' name='limit_msg' value='".$limit_msg."'>";
$box_actions[] = "<input type='hidden' name='start' value='".$start."'>";
$box_actions[] = "<input type='hidden' name='searchmsg' value='".$searchmsg."'>";
$box_actions[] = "<input type='hidden' name='sortname' value='".$sortname."'>";
$box_actions[] = "<input type='hidden' name='sortorder' value='".$sortorder."'>";
$box_actions[] = "<input type='hidden' name='treesortorder' value='".$treesortorder."'>";
$box_actions[] = "<input type='hidden' name='total_messages' value='".$total_messages."'>";

$xoopsTpl->assign('box_actions', $box_actions);
//

//affiche les dossier
mp_category($precistotal, $catbox, @$catpid);	
//Affiche les boutons exportation
if( ( $view_perms & GPERM_EXP ) ) {
$xoopsTpl->assign('mp_pdf', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/pdf.png' style=\"cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"makepdf.php?option=pdf_messagess\";document.prvmsg.submit()'>");
$xoopsTpl->assign('mp_print', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/print.png' style=\" cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"print.php?option=print_messagess\";document.prvmsg.submit()'>");
$xoopsTpl->assign('mp_email', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/email.png' title='"._MP_DESCEMAIL."' style=\" cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"email.php?option=email_messagess\";document.prvmsg.submit()'>");
}
//Language & menu
 $xoopsTpl->assign('lang_private', _PM_PRIVATEMESSAGE);
 $xoopsTpl->assign('lang_rece', _PM_RECE);
 $xoopsTpl->assign('lang_mes', _MP_MESSAGE);
 $xoopsTpl->assign('lang_news', _MP_NEWS);
 $xoopsTpl->assign('lang_file', _MP_FILE);
 $xoopsTpl->assign('lang_menu', MpMenu('msgbox.php'));
 $xoopsTpl->assign('mp_precistotal',  sprintf(_MP_MDEBIT, $precistotal.'%'));
 $xoopsTpl->assign('lang_msg',  sprintf(_MP_MSG, $cattitle));
 $xoopsTpl->assign('mp_catbox', $catbox);
 $xoopsTpl->assign('xoops_module_header', $mp_module_header);
	include("../../footer.php");
	mp_cache();
}
?>