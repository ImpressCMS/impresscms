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


/* Include the module header */
require_once "header.php";
/* Define the main template before the Xoops Header inclde */
$xoopsConfig['module_cache'] = 0;


global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
	
} else {

 if (isset($_REQUEST['op'])) {
 $op = $_REQUEST['op'];
 } else {
 $op = 'box';
 }
 


$catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);			

if (isset($_POST['preview_messages'])) {
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
//Boite de reception
case "box":
   default:
//nav

$xoopsOption['template_main'] = 'mp_msgbox.html'; 
include XOOPS_ROOT_PATH."/header.php";

$start = empty($_REQUEST['start'])?"0": intval($_REQUEST['start']);
$limit_msg = (empty($_REQUEST['limit_msg']) ? (empty($olimite) ? '10' : $olimite ) : intval($_REQUEST['limit_msg']) );
$sortorder = (empty($_REQUEST['sortorder']) ? (empty($osortorder) ? 'desc' : $osortorder ) : $_REQUEST['sortorder'] );
$sortname = (empty($_REQUEST['sortname']) ? (empty($osortname) ? 'msg_time' : $osortname ) : $_REQUEST['sortname'] );

if (@$_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $catcriteria->add(new Criteria('msg_time', strtotime($_REQUEST['after']),">"));
 $after = strtotime($_REQUEST['after']);		
 } else { $after = 0; }
 
 //$catcriteria->add(new Criteria('msg_pid', 0));
 
 $catcriteria->setStart($start);
 $catcriteria->setLimit($limit_msg);
 $catcriteria->setSort($sortname);
 $catcriteria->setOrder($sortorder);
 $pm =& $pm_handler->getObjects($catcriteria);
 $total_messages = $pm_handler->getCount($catcriteria); 

//count the message
 $countcriteria = new CriteriaCompo();
 $countcriteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $countcriteria->add(new Criteria('cat_msg', $catbox)); 
 $mp_alert = $pm_handler->getCount($countcriteria); 
//

$xoopsTpl->assign('lang_read', _MP_READ);
$xoopsTpl->assign('lang_from', _PM_FROM);
$xoopsTpl->assign('lang_subject', _PM_SUBJECT);
$xoopsTpl->assign('lang_tris', _MP_TRIS);
$xoopsTpl->assign('mp_amount', $mp_alert);
$xoopsTpl->assign('lang_date', _PM_DATE);


$xoopsTpl->assign('mp_select', mp_select($catbox, @$after, @$limit_msg, @$sortname, @$sortorder));
//$xoopsTpl->assign('mp_form',  "<form name='prvmsg' method='post' action='msgbox.php'></form>");
$xoopsTpl->assign('lang_send', _PM_SEND);

if ( $mp_alert == 0 ) {
$xoopsTpl->assign('lang_none', _PM_YOUDONTHAVE);
}

$round = $start + 0;
foreach (array_keys($pm) as $i) { 
//viewstart 
 $eachpost = mp_box($pm[$i], $round);

 $round++;
 //conversation
//$treecriteria = new CriteriaCompo();
//$treecriteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid'))); 
//$treecriteria->add(new Criteria('cat_msg', $catbox)); 
//$treecriteria->add(new Criteria('msg_pid', $pm[$i]->getVar('msg_id')));
//$treecriteria->setSort($sortname);
//$treecriteria->setOrder($sortorder);
//$pm_tree =& $pm_handler->getObjects($treecriteria); 
//$count_tree = count($pm_tree);
// $round++; 
// if ( $count_tree > 0 ) {

//foreach (array_keys($pm_tree) as $e) { 
//icone
//$eachpost = mp_box($pm_tree[$e], $i);

//}
// unset($count_tree);
 //}

 }
 
if ( $total_messages > $limit_msg ) {
  $pagenav = new XoopsPageNav($mp_alert, $limit_msg, $start, 'start', 'op=box&catbox='.$catbox.'&after='.$after.'&limit_msg='.$limit_msg.'&sortorder='.$sortorder.'&sortname='.$sortname);
  $pagenav = $pagenav->renderNav();
} else {
  $pagenav = '';
}

$xoopsTpl->assign('lang_vous',  sprintf(_MP_VOUS, $total_messages."/".$mp_alert));	
$xoopsTpl->assign('mp_pagenav', $pagenav);
$xoopsTpl->assign('mp_news', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/new.png' align='absmiddle' alt='"._MP_N."' />&nbsp;"._MP_N);
$xoopsTpl->assign('mp_reply', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/reply.png' align='absmiddle' alt='"._MP_RE."' />&nbsp;"._MP_RE);
$xoopsTpl->assign('mp_lus', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/lus.png' align='absmiddle' alt='"._MP_R."' />&nbsp;"._MP_R);

if( ( $view_perms & GPERM_MESS ) ) {
if (empty($mpstop)) {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;"><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
if ($catbox == 2) {
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='stop' disabled value='"._MP_MREPLY."'>";
}else {
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='reply' disabled value='"._MP_MREPLY."'>";
} }else {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;" disabled><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"msgbox.php?op=sendbox&reply=1\"' id='stop' disabled value='"._MP_MREPLY."'>";
}
}
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=delete_messages\"' id='del' disabled value='"._MP_MDEL."'>";
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=read_messages&read=1\"' id='lu' disabled value='"._MP_MLU."'>";
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=read_messages&read=0\"' id='nlu' disabled value='"._MP_MNLU."'>";
$box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"delbox.php?option=move_messages\"' id='move' disabled value='"._MP_MMOVE."'>";
$box_actions[] = "<input type='button' OnClick=\"msgpop('&nbsp;"._MP_HELP_MSGBOX."&nbsp;')\" value='?'><div id=\"tooltip\" style=\"visibility: hidden;\"></div>";
$box_actions[] = "<input type='hidden' name='catbox' value='".$catbox."'>";
//$box_actions[] = "<input type='hidden' name='after' value='".$after."'>";
$box_actions[] = "<input type='hidden' name='limit_msg' value='".$limit_msg."'>";
$box_actions[] = "<input type='hidden' name='sortname' value='".$sortname."'>";
$box_actions[] = "<input type='hidden' name='sortorder' value='".$sortorder."'>";
$xoopsTpl->assign('box_actions', $box_actions);

	
break;

case "envoimp":

global $xoopsDB, $xoopsUser, $xoopsConfig, $HTTP_POST_VARS, $_FILES;

//permission
if(!( $view_perms & GPERM_MESS ) ) {
redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
exit();
}

//stockage
if (!empty($mpstop)) {
redirect_header($_SERVER['PHP_SELF'],1, _PM_PURGEMES);
exit();
}	

 $reply = !empty($_REQUEST['reply']) ? 1 : 0;
 
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $pm =& $pm_handler->create();
 
 $sendt = _MP_THEREARESENDT;


if (empty($_REQUEST['to_userid']) && empty($_REQUEST['to_groupe'])) {
	redirect_header("javascript:history.go(-1)",1, _PM_USERNOEXIST);
	exit;
	}

if (empty($_REQUEST['subject'])) {
	$_REQUEST['subject'] = _MP_NOSUBJECT;
	}
	
if ($reply == 1) {
 $pm2 =& $pm_handler->get($_REQUEST['msg_mp']);
 $pm->setVar("msg_pid", $pm2->getVar("msg_pid"));
} else {
 $pm->setVar("msg_pid", $pm_handler->getPid()+1);  
 }	

 if (isset($_FILES)) {
 //$up_handler  = & xoops_gethandler('priv_msgsup');
 //$upid = $up_handler->getCount()+1;
 $upid = mp_upload();
 } else {
 $upid = "0";
 } 

 if (isset($_REQUEST['to_groupe'])) {
 foreach ($_REQUEST['to_groupe'] as $u_groupe => $u_name) {
 $member_handler =& xoops_gethandler('member');
 $members =& $member_handler->getUsersByGroup($u_name, true);
 $mcount = count($members);	
 for ($i = 0; $i < $mcount; $i++) {
 $pm->setVar("msg_pid", 0);
 $pm->setVar("msg_image", $_REQUEST['msg_image']);
 $pm->setVar("subject", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['subject'])));
 $pm->setVar("from_userid", $xoopsUser->getVar("uid"));
 $pm->setVar("to_userid", $members[$i]->getVar('uid')); 
 $pm->setVar("msg_time", time());
 $pm->setVar("msg_text", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['message'])));
 $pm->setVar("anim_msg", $_REQUEST['anim_msg']);
 $pm->setVar("cat_msg", 1); 
 $pm->setVar("file_msg", $upid);
 $erreur = $pm_handler->insert($pm);

 $sendt .= mp_getLinkedUname(intval($members[$i]->getVar('uid'))).",";
 if ($reply == 1) {
 $pm_handler->setReply($pm2);
 } 
 if ($xoopsModuleConfig['notification'] == "1") {
 mp_mail($members[$i]->getVar('uid'));
 } } } }


 if (isset($_REQUEST['to_userid'])) {
 
 foreach ($_REQUEST['to_userid'] as $u_id => $u_id_name) {  
 $pm->setVar("msg_image", $_REQUEST['msg_image']);
 $pm->setVar("subject", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['subject']))); 
 $pm->setVar("from_userid", $xoopsUser->getVar("uid"));
 $pm->setVar("to_userid", $u_id_name); 
 $pm->setVar("msg_time", time());
 $pm->setVar("msg_text", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['message'])));
 $pm->setVar("anim_msg", $_REQUEST['anim_msg']);
 $pm->setVar("cat_msg", 1); 
 $pm->setVar("file_msg", $upid);
 $erreur = $pm_handler->insert($pm);



 $sendt .= mp_getLinkedUname(intval($u_id_name)).",";
 if ($reply == 1) {
 $pm_handler->setReply($pm2);
 } 
 if ($xoopsModuleConfig['notification'] == "1") {
 mp_mail($u_id_name);
 } } }			

if (!$erreur) {
redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
 } else {
 
if ($oresend == "1") {
 $pm->setVar("msg_image", $_REQUEST['msg_image']);
 $pm->setVar("subject", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['subject']))); 
 $pm->setVar("from_userid", $xoopsUser->getVar("uid"));
 $pm->setVar("to_userid", $xoopsUser->getVar('uid')); 
 $pm->setVar("msg_time", time());
 $pm->setVar("msg_text", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['message']."\r\n\r\n$sendt")));
 $pm->setVar("anim_msg", $_REQUEST['anim_msg']);
 $pm->setVar("cat_msg", 2); 
 $pm->setVar("read_msg", 1); 
 $pm->setVar("file_msg", $upid);
 $erreur = $pm_handler->insert($pm);
}

redirect_header("msgbox.php?op=box",1, _PM_POSTE);
}
		
break;
   
  case "sendbox":
  
  $xoopsOption['template_main'] = 'mp_subox.html'; 
  include XOOPS_ROOT_PATH."/header.php";
  
if(!( $view_perms & GPERM_MESS ) ) {
redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
exit();
}

if (!empty($mpstop)) {
redirect_header("javascript:history.go(-1)",2, _PM_PURGEMES);
exit();
}

$reply = !empty($_REQUEST['reply']) ? intval($_REQUEST['reply']) : 0;
$send = !empty($_GET['send']) ? 1 : 0;
$cont = !empty($_GET['cont']) ? 1 : 0;
$send2 = !empty($_GET['send2']) ? 1 : 0;
$to_userid = !empty($_GET['to_userid']) ? intval($_GET['to_userid']) : 0;
$ct_contact = !empty($_REQUEST['ct_contact']) ? $_REQUEST['ct_contact'] : 0;
$msg_mp = !empty($_REQUEST['msg_mp']) ? $_REQUEST['msg_mp'] : 0;
$start2 = !empty($_GET['start2']) ? intval($_GET['start2']) : 0;
$chek_userid = !empty($_POST['chek_userid']) ? $_POST['chek_userid'] : "";
$icon = !empty($_POST['icon']) ? $_POST['icon'] : "icon1.gif";
$anim = !empty($_POST['anim']) ? $_POST['anim'] : "";
$subject = !empty($_POST['subject']) ? $_POST['subject'] : "";
$message = !empty($_POST['message']) ? $_POST['message'] : "";
$hidden_quote = !empty($_POST['hidden_quote']) ? $_POST['hidden_quote'] : "";
$formtype = (empty($_REQUEST['formtype']) ? (empty($oformtype) ? '2' : $oformtype ) : $_REQUEST['formtype'] );
if (is_array($msg_mp)) { $msg_mp = $msg_mp[0]; }

if ($reply == 1 || $send == 1 || $send2 == 1 || $cont == 1) {

//getHeight ($total);
if ($reply == 1) {	

    if (empty($msg_mp)) {
    redirect_header("index.php",2, _PM_REDNON);
	}
	
$pm_handler  = & xoops_gethandler('priv_msgs');
$pm2 =& $pm_handler->get($msg_mp);
$pm_uname = XoopsUser::getUnameFromId($pm2->getVar("from_userid"));
if (isset($_REQUEST['preview_messages'])) {
$subject = $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['subject']));
}else {
$subject = 'Re: '.$myts->htmlSpecialChars($myts->stripSlashesGPC($pm2->getVar("subject")));
      }
 if (isset($_REQUEST['quotedac']) && $_REQUEST['quotedac'] == 1) {
	        $message = "[quote]\n";
	        $message .= sprintf(_PM_USERWROTE,$pm_uname);
	        $message .= "\n".$myts->htmlSpecialChars($myts->stripSlashesGPC($pm2->getVar("msg_text")))."[/quote]";
	    } else {
	        $hidden_quote = "[quote]\n";
	        $hidden_quote .= sprintf(_PM_USERWROTE,$pm_uname);
	        $hidden_quote .= "\n".$myts->htmlSpecialChars($myts->stripSlashesGPC($pm2->getVar("msg_text")))."[/quote]";
	    }
	
    }
//form
 $select_form = new XoopsSimpleForm('', 'formtype', xoops_getenv('PHP_SELF'), 'get');
 $options = array(); 
 $promotray = new XoopsFormElementTray(''); 
 $option_select = new XoopsFormSelect('', 'formtype', $formtype);
 //$option_wins = array( '1' => 'Compact' , '2' => 'DHTML', '3' => 'htmlarea', '4' => 'Koivi',
 // '5' => 'TinyEditor', '6' => 'Inbetween' , '7' => 'spaw', '8' => 'FCK');
  $option_wins =  mp_selecteditor();
 
 foreach($xoopsModuleConfig['wysiwyg'] as $option){
		if(!empty($option_wins[$option])) $options[$option]=$option_wins[$option];
	}
 $option_select->addOptionArray($options);
 $promotray->addElement($option_select);
 $button_tray = new XoopsFormButton("", "submit", "<>", "submit");
 $button_tray->setExtra("onclick=\"document.prvmsg.action='msgbox.php?op=sendbox&send=".$send."&reply=".$reply."'\"");
 
 $select_form->addElement($promotray);
 $promotray->addElement($button_tray);


$xoopsTpl->assign('mp_selectview', $select_form->render());
//

 include 'include/form.inc.php';

// affiche le message de reponse
if ( $reply == 1 ) {
$poster = new XoopsUser($pm2->getVar("from_userid"));

if ( !$poster->isActive() ) {
$poster_name = $xoopsConfig['anonymous'];
} else {
$poster_name = $poster->getVar("uname");
$poster_rank = $poster->rank();


echo "<br /><span style=\"background-color: #E9E9E9; border:1px solid #C0C0C0;font-size:10px;\">&nbsp;
"._MP_MMES."&nbsp;
<a href=\"javascript:;\" onclick=\"showForm('prevu');\">+</a>/
<a href=\"javascript:;\" onclick=\"hideAll('prevu');\">-</a>&nbsp;</span>
<div id='prevu'>
<table border='0' cellspacing='1' cellpadding='4' width='100%' class='outer'><tr>
<td width='20%' class='foot'><b>"._PM_FROM."</b></td>
<td class='foot'><b>". _PM_SUBJECT."</b></td>
</tr>
<tr><td width='20%' class='even'><b><a href='".XOOPS_URL."/userinfo.php?uid=".$poster->getVar("uid")."'>".$poster_name."</a></td>
    <td class='even'>"._MP_POSTED."&nbsp;".formatTimestamp($pm2->getVar("msg_time"))."</td>
</tr>	
<tr><td class='head' valign='top' rowspan='2'>
		<div class='comUserRank'><div class='comUserRankText'>".$poster_rank['title']."</div>

<img class='comUserRankImg' src='".XOOPS_UPLOAD_URL."/".$poster_rank['image']."' alt='' />

</div>
<img class='comUserImg' src='".XOOPS_UPLOAD_URL."/".$poster->getVar("user_avatar")."' alt=''/>
<div class='comUserStat'><span class='comUserStatCaption'>"._MP_JOINED."</span>
".formatTimestamp($poster->getVar('user_regdate'), 's')."</div>
<div class='comUserStat'><span class='comUserStatCaption'>"._MP_FROM2."</span>
".$poster->getVar('user_from')."</div>
<div class='comUserStat'><span class='comUserStatCaption'>"._MP_POSTS."
</span>".$poster->getVar('posts')."</div>";

if ($poster->isOnline()) {
echo '<div class="comUserStatus"><img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/online.png" title="'._MP_ONLINE.'" style="width: 20px; height: 20px;"/></div>';
} else {
echo '<div class="comUserStatus"><img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/offline.png" title="'._MP_OFFLINE.'" style="width: 20px; height: 20px;"/></div>';
}}
		
echo "</td><td class='head' valign='top'><div class='comTitle'>";


if (!$pm2->getVar("msg_image")) {
		echo "<img src='../../images/read.gif' alt='' />";
		}
		else {
		echo "<img src='../../images/subject/".$pm2->getVar("msg_image")."' alt='' />";
		}
		
echo "&nbsp;".$myts->htmlSpecialChars($myts->stripSlashesGPC($pm2->getVar("subject")))."</div>
<div class='comText'>".$myts->htmlSpecialChars($myts->stripSlashesGPC($pm2->getVar("msg_text")))."
</td></tr></table></div>";
		
	}
$xoopsTpl->assign('mp_formulaire', "<form name='prvmsg' method='post' action='".$_SERVER['PHP_SELF']."?send=$send&reply=$reply&cont=$cont' enctype='multipart/form-data'>");

if( ( $view_perms & GPERM_MESS ) ) {
if (empty($mpstop)) {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;"><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
if ($catbox == 2) {
$box_actions[] = "<input type='submit' name='post_messages' onsubmit='xoopsFormValidate_read();' id='post_messages' disabled value='"._MP_SUBMIT."'>";
$box_actions[] = "<input type='submit'  name='preview_messages' id='preview_messages' disabled value='"._MP_PREVIEW."'>";
}else {
$box_actions[] = "<input type='submit' name='post_messages' onsubmit='xoopsFormValidate_read();' id='post_messages' value='"._MP_SUBMIT."'>";
$box_actions[] = "<input type='submit'  name='preview_messages' id='preview_messages' value='"._MP_PREVIEW."'>";
} }else {
$box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;" disabled><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
$box_actions[] = "<input type='submit' name='post_messages' onsubmit='xoopsFormValidate_read();' id='post_messages' disabled value='"._MP_SUBMIT."'>";
$box_actions[] = "<input type='submit'  name='preview_messages' id='preview_messages' value='"._MP_PREVIEW."'>";
}
}
if ($reply == 1) {
$box_actions[] = "<input type='button' name='quote' onclick='xoopsGetElementById(\"message\").value=xoopsGetElementById(\"message\").value+ xoopsGetElementById(\"hidden_quote\").value;xoopsGetElementById(\"hidden_quote\").value=\"\";'' id='quote'  value='"._MP_QUOTE."'>";
}
$box_actions[] = "<input type='reset' id='reply' value='"._MP_CLEAR."'>";
$box_actions[] = "<input type='button' OnClick=\"msgpop('&nbsp;"._MP_HELP_MSGBOX."&nbsp;')\" value='?'><div id=\"tooltip\" style=\"visibility: hidden;\"></div>";
$box_actions[] = "<input type='hidden' name='msg_mp' id='msg_mp' value='".$msg_mp."'>";
$xoopsTpl->assign('box_actions', $box_actions);


}

   break;  
    }

//alert
if (!empty($msg_alert)) {
$xoopsTpl->assign('mp_alert', "<table border='0' cellspacing='1' cellpadding='4' width='100%' class='outer'><tr><td  class='odd' align='center'>".$msg_alert."</td></tr></table>"); 
}
//Affiche les Dossiers
mp_category($precistotal, $catbox, @$catpid);
//Affiche les boutons exportation	
if( ( $view_perms & GPERM_EXP ) ) {
$xoopsTpl->assign('mp_pdf', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/pdf.png' title='"._MP_DESCPDF."' style=\"cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"makepdf.php?option=pdf_messages\";document.prvmsg.submit()'>");
$xoopsTpl->assign('mp_print', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/print.png' title='"._MP_DESCPRINTER."' style=\" cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"print.php?option=print_messages\";document.prvmsg.submit()'>");
$xoopsTpl->assign('mp_email', "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/email.png' title='"._MP_DESCEMAIL."' style=\" cursor:pointer; border-width: 0px; width: 30px; height: 30px;\" onclick='document.prvmsg.action=\"email.php?option=email_messages\";document.prvmsg.submit()'>");
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

	include XOOPS_ROOT_PATH."/footer.php";
	mp_cache();
}
?>
