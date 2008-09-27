<?php
// ------------------------------------------------------------------------- //
//                       XOOPS - Module MP Manager                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- // 
//                 Votre nouveau systeme de messagerie priver                //
//                                                                           //
//                               "MP"                                        //
//                                                                           //
//                       http://lexode.info/mods                             //
//                                                                           //
//                                                                           //
//---------------------------------------------------------------------------//
$xoopsOption['pagetype'] = "pmsg";

include("mainfile.php");
$mydirname = "mpmanager" ; 
require_once "modules/mpmanager/include/functions.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/modules/mpmanager/class/priv_msgs.php';
include_once XOOPS_ROOT_PATH.'/modules/mpmanager/class/priv_msgsup.php';
include_once XOOPS_ROOT_PATH.'/modules/mpmanager/class/priv_msgsopt.php';
include XOOPS_ROOT_PATH."/modules/mpmanager/include/get_perms.php" ;

$module_handler =& xoops_gethandler('module');
$xoopsModule =& $module_handler->getByDirname("mpmanager");
$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
$myts = & MyTextSanitizer :: getInstance(); // MyTextSanitizer object

if( file_exists(XOOPS_ROOT_PATH."/modules/mpmanager/language/".$xoopsConfig['language']."/main.php") ) {
	include(XOOPS_ROOT_PATH."/modules/mpmanager/language/".$xoopsConfig['language']."/main.php");
} else {
	include(XOOPS_ROOT_PATH."/modules/mpmanager/language/english/main.php");
}


global $xoopsUser;

if (empty($xoopsUser)) {
    redirect_header("user.php",1,_PM_REGISTERNOW);
	
} else {

//alert
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $mp_alert = $pm_handler->getCount($criteria); 
 //
 //option utilisateur
 if (!empty($xoopsUser)) {
 $opt_handler  = & xoops_gethandler('priv_msgsopt');
 $opt =& $opt_handler->get($xoopsUser->getVar('uid'));
 if (!$opt) {
 $oresend = false;
 $oformtype = false;
 } else {
 $oresend = $opt->getVar('resend');
 $oformtype = $opt->getVar('formtype');
 }
 }

 if (isset($_REQUEST['op'])) {
 $op = $_REQUEST['op'];
 } else {
 $op = 'sendbox';
 }		

 if (isset($_POST['post_messages'])) {
	$op = 'envoimp';
}
	
	xoops_header();
echo '<script type="text/JavaScript"><!--
  var h=600;
  var w=700;
   window.resizeTo(w, h);
 // -->
 </script><script type="text/javascript" src="'.XOOPS_URL.'/modules/mpmanager/include/multifile.js"></script>';
 
 echo '<script type="text/javascript">
function auto_close(delai)
{ setTimeout("self.close();",delai); }
</script>';
				  
switch( $op )
{
//Boite de reception  
case "envoimp":

global $xoopsDB, $xoopsUser, $xoopsConfig, $_FILES;

if(!( $view_perms & GPERM_MESS ) ) {
redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
exit();
}

$pm_handler  = & xoops_gethandler('priv_msgs');
$pm =& $pm_handler->create();
$msg_time = time();
$from_userid = $xoopsUser->getVar('uid');
$sendt = _MP_THEREARESENDT;

if (empty($_REQUEST['to_userid'])) {
	redirect_header("javascript:history.go(-1)",1, _PM_USERNOEXIST);
	exit;
	}

if (empty($_REQUEST['subject'])) {
	$_REQUEST['subject'] = _MP_NOSUBJECT;
	}
			
 if (isset($_FILES)) {
 $upid = mp_upload();
 } else {
 $upid = "0";
 } 
 
 if (isset($_REQUEST['to_userid'])) {
 
 $pm->setVar("msg_pid", $pm_handler->getCount()+1);  
 $pm->setVar("msg_image", $_REQUEST['msg_image']);
 $pm->setVar("subject", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['subject']))); 
 $pm->setVar("from_userid", $xoopsUser->getVar("uid"));
 $pm->setVar("to_userid", $_REQUEST['to_userid']); 
 $pm->setVar("msg_time", time());
 $pm->setVar("msg_text", $myts->htmlSpecialChars($myts->stripSlashesGPC($_REQUEST['message'])));
 $pm->setVar("anim_msg", $_REQUEST['anim_msg']);
 $pm->setVar("cat_msg", 1); 
 $pm->setVar("file_msg", $upid);
 $erreur = $pm_handler->insert($pm);



 $sendt .= mp_getLinkedUname(intval($_REQUEST['to_userid'])).",";
 if ($_REQUEST['reply'] == 1) {
 $pm_handler->setReply($pm2);
 } 
 if ($xoopsModuleConfig['notification'] == "1") {
 mp_mail($_REQUEST['to_userid']);
 }  }			

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

redirect_header("javascript:history.go(-1)",1, _PM_MESSAGEPOSTED.'<script type="text/javascript">auto_close(1000);</script>');
}
		
break;
   
  case "sendbox":
  default:
  
if(!( $view_perms & GPERM_MESS ) ) {
redirect_header("javascript:history.go(-1)",1, _PM_REDNON);
exit();
}	

$send = !empty($_GET['send']) ? 1 : 0;
$send2 = !empty($HTTP_GET_VARS['send2']) ? 1 : 0;
$to_userid = !empty($HTTP_GET_VARS['to_userid']) ? intval($HTTP_GET_VARS['to_userid']) : 0;
$msg_id = !empty($HTTP_POST_VARS['msg_id']) ? intval($HTTP_POST_VARS['msg_id']) : 0;
$formtype = (empty($_REQUEST['formtype']) ? (empty($oformtype) ? '4' : $oformtype ) : $_REQUEST['formtype'] );

if ( $send == 1 || $send2 == 1) {

if ( $mp_alert > $xoopsModuleConfig['maxuser']) {
redirect_header("javascript:history.go(-1)",1, _PM_PURGEMES.'<script type="text/javascript">auto_close(1000);</script>');		
} 
//form
$form = new XoopsThemeForm(_MP_READMSG, "read", $_SERVER['PHP_SELF']);
$form->setExtra( "enctype='multipart/form-data'" );
 
$user_select_tray = new XoopsFormLabel(_MP_TO2, XoopsUser::getUnameFromId($to_userid));
$texte_hidden = new XoopsFormHidden("to_userid", $to_userid);
$form->addElement($user_select_tray);
$form->addElement($texte_hidden);

$icons_radio = new XoopsFormRadio(_MP_MESSAGEICON, 'icon');
$subject_icons = XoopsLists::getSubjectsList();
foreach ($subject_icons as $iconfile) {
	$icons_radio->addOption($iconfile, '<img src="'.XOOPS_URL.'/images/subject/'.$iconfile.'" alt="" />');
}
$form->addElement($icons_radio); 

if( ( $view_perms & GPERM_OEIL ) ) {

    $indeximage_select = new XoopsFormText('', 'anim_msg', 25, 50);
	$indeximage_select -> setExtra("readonly=\"readonly\" ");
    $indeximage_tray = new XoopsFormElementTray(_MP_MESSAGEOEIL, '&nbsp;');
    $indeximage_tray->addElement($indeximage_select);
    $indeximage_tray->addElement(new XoopsFormLabel('', "<A HREF=\"#\" onClick=\"window.open('".XOOPS_URL."/modules/mpmanager/pop.php','_blank','toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=0, copyhistory=0, menuBar=0, width=700, height=400');return false;\"><img src=\"".XOOPS_URL."/modules/mpmanager/images/popup.gif\">&nbsp;"._MP_MESSAGEVUOEIL."</A>"));
    $form->addElement($indeximage_tray);
}

$form->addElement(new XoopsFormText(_MP_SUBJECTC, "subject", 50, 100), true);

$editor = WysiwygForm(_MP_MESSAGEC, "message", '', '100%', '400px', $formtype);
$form->addElement($editor);

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

$button_tray = new XoopsFormElementTray('' ,'');
$button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CLEAR, 'reset'));

$button_tray->addElement(new XoopsFormButton('', 'post_messages', _MP_SUBMIT, 'submit'));
$form->addElement($button_tray);

$msg_hidden = new XoopsFormHidden("msg_id", $msg_id);
$form->addElement($msg_hidden);


  echo $form -> render();
}
   break;  
    }
	
xoops_footer();
}
?>