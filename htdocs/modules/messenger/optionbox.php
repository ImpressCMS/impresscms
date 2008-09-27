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
/* Global Xoops User variable */
global $xoopsUser;
/* If $xoopsUser vriable is define then user is connected */
if (empty($xoopsUser)) {
  redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
} else {
  if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
  } else {
    $op = 'box';
  }
  $catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);
  //alert
  $pm_handler  = & xoops_gethandler('priv_msgs');
  $criteria = new CriteriaCompo();
  $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
  $total = $pm_handler->getCount($criteria); 
  unset($criteria);
  $precistotal = number_format(($total*100)/$xoopsModuleConfig['maxuser'], 0, ",", " ");

//alert stockage
if ( $total > $xoopsModuleConfig['maxuser']) {
    $msg_alert = _MP_ALERT."<br />"._MP_AVERT;
	$mpstop = "stop";		
} 
  
  
  switch($op) {
  //Boite de reception
  case "box": 
  default:
  
  $xoopsOption['template_main'] = 'mp_optionbox.html';
  include XOOPS_ROOT_PATH."/header.php";
    //cherche les options utilisateur
    $pm_handler  = & xoops_gethandler('priv_msgsopt');
    $opt = $pm_handler->get($xoopsUser->getVar('uid'));
    if(!$opt) {
      $limite = "10";
	  $home = "1";
      $notif = "0";
      $resend = "0";
      $formtype = "4";
      $sortname = _MP_TRI_DATE;
      $sortorder = _MP_TRI_ODESC;
      $vieworder = _MP_TRI_FLAT;
    } else {
      $limite = $opt->getVar('limite');
	  $home = $opt->getVar('home');
      $notif = $opt->getVar('notif');
      $resend = $opt->getVar('resend');
      $sortname = $opt->getVar('sortname');
      $sortorder = $opt->getVar('sortorder');
      $vieworder = $opt->getVar('vieworder');
      $formtype = $opt->getVar('formtype');
    }
    //creation du formulaire d'ajout
    $form = new XoopsThemeForm(_MP_DESC_OPT, "read", $_SERVER['PHP_SELF']);
    $form_limit = new XoopsFormText(_MP_LIMIT, "limite", 2, 2, $limite);
    $form->addElement($form_limit);
    if ($xoopsModuleConfig['notification'] == "1") {
      $form_notif = new XoopsFormCheckBox(_MP_NOTIF, 'notif', $notif);
      $form_notif->addOption(1, '&nbsp;');
      $form->addElement($form_notif);
    }
    $form_resend = new XoopsFormCheckBox(_MP_RESEND, 'resend', $resend);
    $form_resend->addOption(1, '&nbsp;');
    $form->addElement($form_resend);   
	$form_home = new XoopsFormCheckBox(_MP_OPT_HOME, 'home', $home);
    $form_home->addOption(1, '&nbsp;');
    $form->addElement($form_home);  

	
 $options = array(); 
 $promotray = new XoopsFormElementTray(''); 
 $option_select = new XoopsFormSelect(_MP_FORMTYPE, 'formtype', $formtype);
// $option_wins = array(
 // '1' => 'Compact' , '2' => 'DHTML','3' => 'htmlarea', '4' => 'Koivi', '5' => 'TinyEditor', '6' => 'Inbetween' , '7' => 'spaw', '8' => 'FCK');
  
 $option_wins =  mp_selecteditor();
 foreach($xoopsModuleConfig['wysiwyg'] as $option){
		if(!empty($option_wins[$option])) 
		$options[$option]=$option_wins[$option];
	}
	
 $option_select->addOptionArray($options);
 $form->addElement($option_select);
	
	
  $form->insertBreak(_MP_TRI_TRI, 'odd');
    $order_select = array('subject' => _MP_TRI_TITLE ,'msg_time' => _MP_TRI_DATE, 'read_msg' => _MP_TRI_READ);
    $thread_select = new XoopsFormSelect (_MP_TRI_HOWTOSORT, "sortname", $sortname);
    $thread_select->addOptionArray($order_select);
    $sortorder_select = array('asc' => _MP_TRI_OASC,'desc' => _MP_TRI_ODESC);
    $sort_select = new XoopsFormSelect (_MP_TRI_SORTORDER, "sortorder", $sortorder);
    $sort_select->addOptionArray($sortorder_select);
    $flat_select = array('flat' => _MP_TRI_FLAT,'thread' => _MP_TRI_THREAD);
    $threadn_select = new XoopsFormSelect (_MP_TRI_VIEWORDER, "order", $vieworder);
    $threadn_select->addOptionArray($flat_select);

    $form->addElement($thread_select);
    $form->addElement($threadn_select);
    $form->addElement($sort_select);
  
    $msg_hidden = new XoopsFormHidden("op", "envoimp");
    $form->addElement($msg_hidden);
    $button_tray = new XoopsFormElementTray('' ,'');
    $post_button = new XoopsFormButton('', 'post_messages', _MP_SUBMIT, "submit");
    $post_button -> setExtra("onclick='document.prvmsg.action=\"optionbox.php?op=envoimp\"'");
    $button_tray->addElement($post_button);
    $button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CANCEL, 'reset'));
    $form->addElement($button_tray);
    $quick_reply = $form->render();
    $xoopsTpl->assign('quick_reply', $quick_reply);		
    //$xoopsTpl->assign('mp_input_reply', "<input type='submit' onclick='document.prvmsg.action=\"optionbox.php?op=envoimp\"' id='lire' value='"._MP_SUBMIT."'>");
    //$xoopsTpl->assign('mp_input_del', "<input type='reset' id='reply' value='"._MP_CANCEL."'>");
    if( ( $view_perms & GPERM_MESS ) ) {
      if (empty($mpstop)) {
        $box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;"><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
        $box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"optionbox.php?op=envoimp\"' id='lire' value='"._MP_SUBMIT."'>";
      } else {
        $box_actions[] = '<select name="add" OnChange="window.document.location=this.options[this.selectedIndex].value;" disabled><option selected>'._MP_MNEWS.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/msgbox.php?op=sendbox&send=1">-> '._MP_MMES.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/contbox.php?op=sendbox">-> '._MP_MCONT.'</option><option value="'.XOOPS_URL.'/modules/'.$mydirname.'/filebox.php?op=sendbox">-> '._MP_MFILE.'</option></select>';
        $box_actions[] = "<input type='submit' onclick='document.prvmsg.action=\"optionbox.php?op=envoimp\"' id='stop' value='"._MP_SUBMIT."'>";
      }
    }
    $box_actions[] = "<input type='submit' id='reset' value='"._MP_CANCEL."'>";
    $box_actions[] = "<input type='button' OnClick=\"msgpop('&nbsp;"._MP_HELP_OPTION."&nbsp;')\" value='?'><div id=\"tooltip\" style=\"visibility: hidden;\"></div>";
    $box_actions[] = "<input type='hidden' name='catbox' value='".$catbox."'>";
    $xoopsTpl->assign('box_actions', $box_actions);
    break;
  case "envoimp":
    global $xoopsDB, $xoopsUser, $xoopsConfig;
    $notif = !empty($_REQUEST['notif']) ? 1 : 0;
    $resend = !empty($_REQUEST['resend']) ? 1 : 0;
    $limite = !empty($_REQUEST['limite']) ? $_REQUEST['limite'] : 10;
	$home = !empty($_REQUEST['home']) ? $_REQUEST['home'] : 0;
    $sortorder = !empty($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'desc';
    $sortname = !empty($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'msg_time';
    $order = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'flat';
    $formtype = !empty($_REQUEST['formtype']) ? $_REQUEST['formtype'] : '4';
    if (empty($xoopsUser)) {
      redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
    } else {
      $pm_handler  = & xoops_gethandler('priv_msgsopt');
      $pm =& $pm_handler->create();
      $pm->setVar("userid", $xoopsUser->getVar('uid'));
      $pm->setVar("notif", $notif);
      $pm->setVar("resend", $resend);
      $pm->setVar("limite", $limite);
	  $pm->setVar("home", $home);
      $pm->setVar("sortname", $sortname);
      $pm->setVar("sortorder", $sortorder);
      $pm->setVar("vieworder", $order);
      $pm->setVar("formtype", $formtype);
      $pm_arr =& $pm_handler->get($xoopsUser->getVar('uid'));
      if ($pm_arr) {
        $erreur =  $pm_handler->update($pm);
      } else {
        $erreur =  $pm_handler->insert($pm);
      }
      if (!$erreur) {
        redirect_header("javascript:history.go(-1)",2, _PM_REDNON);
			} else {
        redirect_header("optionbox.php?op=box",2, _MP_REDIF_NOTIF);
      }
    }
    break;
  }
  

//Affiche les Dossiers
  mp_category($precistotal, $catbox, @$catpid);
//Language & menu
  $xoopsTpl->assign('lang_private', _PM_PRIVATEMESSAGE);
 $xoopsTpl->assign('lang_rece', _PM_RECE);
 $xoopsTpl->assign('lang_mes', _MP_MESSAGE);
 $xoopsTpl->assign('lang_news', _MP_NEWS);
 $xoopsTpl->assign('lang_file', _MP_FILE);
 $xoopsTpl->assign('lang_menu', MpMenu('optionbox.php'));
 $xoopsTpl->assign('mp_precistotal',  sprintf(_MP_MDEBIT, $precistotal.'%'));
// $xoopsTpl->assign('lang_msg',  sprintf(_MP_MSG, $cattitle));
 $xoopsTpl->assign('mp_catbox', $catbox);
 $xoopsTpl->assign('xoops_module_header', $mp_module_header);
	include XOOPS_ROOT_PATH."/footer.php";
	mp_cache();
}
?>
