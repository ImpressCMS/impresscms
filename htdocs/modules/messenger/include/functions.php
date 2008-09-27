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
	
function mp_category($precistotal, $catbox, $catpid) {

 global $xoopsDB, $xoopsTpl, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
//dossier categorie
$amount = 0;
$count = 1;
$chcount = 0;

 $cat_handler  = & xoops_gethandler('priv_msgscat');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('pid', 0));
 $criteria->add(new Criteria('uid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('ver', 1), 'OR');
 $criteria->setSort("cid");
 $pm_cat =& $cat_handler->getObjects($criteria);
 $precismov=0; /* MusS : added for remove a notice message in debug mode */

foreach (array_keys($pm_cat) as $i) {
 $criteria2 = new CriteriaCompo();
 $criteria2->add(new Criteria('pid', $pm_cat[$i]->getVar('cid')));
 $criteria2->add(new Criteria('uid', $xoopsUser->getVar('uid')));
 $criteria2->setSort("cid");
 $pm_cat2 =& $cat_handler->getObjects($criteria2);
 $plus = count($pm_cat2);

 $checked = $catbox == $pm_cat[$i]->getVar('cid') ? "checked='checked'" : ""; 
 
 if ($pm_cat[$i]->getVar('cid') == $catpid) {
 $display = "block";
 $img = "deplierbas.gif";
 } else {
 $display = "none";
 $img = "deplierhaut.gif";
 }
 

 if ($plus) {
 $ref = $pm_cat[$i]->getVar('cid');
 $title = "<script type=\"text/javascript\">
 var vis = new Array();
 vis['$ref'] = 'hide';
 </script>
 <div><a href=\"javascript:;\" onclick=\"javascript: swap_couche('".$count."');\"><img name=\"triangle$count\" id=\"triangle$count\" src=\"images/$img\" alt=\"\" width=\"9\" height=\"9\" title=\"D&eacute;plier\" border=\"0\"></a>&nbsp;<input type='radio' id='ct_file' name='ct_file' value='".$pm_cat[$i]->getVar('cid')."' onClick='ChangeStatut2(this)' ".$checked." />&nbsp;<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/file.png' alt='"._MP_RE."' />&nbsp;<a href='msgbox.php?catbox=".$pm_cat[$i]->getVar('cid')."'>".$pm_cat[$i]->getVar('title')."</a></div>";
 }else {
 $title = "<div><img src='images/blank.gif' width='9' height='9' border='0'>&nbsp;<input type='radio' id='ct_file' name='ct_file' value='".$pm_cat[$i]->getVar('cid')."' onClick='ChangeStatut2(this)' ".$checked." />&nbsp;<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/file.png' alt='"._MP_RE."' />&nbsp;<a href='msgbox.php?catbox=".$pm_cat[$i]->getVar('cid')."'>".$pm_cat[$i]->getVar('title')."</a></div>";
 }

 $subcategories = "<div style='display: block'>";
//compte les messages categorie
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('read_msg', 0));
 $criteria->add(new Criteria('cat_msg', $pm_cat[$i]->getVar('cid')));
 $newsct = $pm_handler->getCount($criteria); 

 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('read_msg', 1));
 $criteria->add(new Criteria('cat_msg', $pm_cat[$i]->getVar('cid')));
 $oldct = $pm_handler->getCount($criteria); 
 
 $precis = number_format((($newsct+$oldct)*100)/$xoopsModuleConfig['maxuser'], 0, ",", " ");
	
 foreach (array_keys($pm_cat2) as $i) {

 $checked = $catbox == $pm_cat2[$i]->getVar('cid') ? "checked='checked'" : ""; 

 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('read_msg', 0));
 $criteria->add(new Criteria('cat_msg', $pm_cat2[$i]->getVar('cid')));
 $newscta = $pm_handler->getCount($criteria); 

 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
 $criteria->add(new Criteria('read_msg', 1));
 $criteria->add(new Criteria('cat_msg', $pm_cat2[$i]->getVar('cid')));
 $oldcta = $pm_handler->getCount($criteria); 


 $precisa = number_format((($newscta+$oldcta)*100)/$xoopsModuleConfig['maxuser'], 0, ",", " ");
 @$precismov = $precismov+$precisa;
 $subcategories .= "<td><div style='display: $display; margin-left: 50px; padding: 5px; text-align:left;' class='".$count."'><input type='radio' id='ct_file' name='ct_file' value='".$pm_cat2[$i]->getVar('cid')."'  onClick='ChangeStatut2(this)' ".$checked."/>&nbsp;<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/file.png' alt='"._MP_RE."' />&nbsp;<a href='msgbox.php?catbox=".$pm_cat2[$i]->getVar('cid')."'>".$pm_cat2[$i]->getVar('title')."</a></div></td><td valign='middle' align='center' ><div style='display: $display;' class='".$count."'>".$newscta."</div></td><td valign='middle' align='center'><div style='display: $display;' class='".$count."'>".$oldcta."</div></td><td valign='middle' align='center'><div style='display: $display; padding: 5px;' class='".$count."'>
<span style='border: 1px solid rgb(0, 0, 0); background: rgb(255, 255, 255) none repeat scroll 0%; margin: 4px; text-align:center; display: block; height: 8px; width: 70%; float: left; overflow: hidden;'><span style='background:  ".$xoopsModuleConfig['cssbtext']." none repeat scroll 0%; text-align:left; display: block;  margin-left: ".$precismov."%; height: 8px; font-size: 5px; width: ".$precisa."%; float: left; overflow: hidden;'></span></span>".$precisa."%</div></td></tr><tr >"; 
 $chcount++;
 $amount = $amount + 1;
 } 
 $subcategories .= "</div>";
 $xoopsTpl -> append( 'categories', array( 'id' => $pm_cat[$i]->getVar('cid'),
                'title' => $title,
                'subcategories' => $subcategories,
				'ms_news' => $newsct,
				'count' => $count,
				'ms_old' => $oldct,
				'mp_precis' => "
	<span style='border: 1px solid rgb(0, 0, 0); background: rgb(255, 255, 255) none repeat scroll 0%; margin: 4px; text-align:center; display: block; height: 8px; width: 70%; float: left; overflow: hidden;'><span style='background:  ".$xoopsModuleConfig['cssbtext']." none repeat scroll 0%; text-align:left; display: block; margin-left: ".$precismov."%; height: 8px; width: ".$precis."%; float: left; overflow: hidden;'></span></span>".$precis."%") );
			@$precismov = $precismov+$precis;
			$amount = $amount + 1;
			$count++;
			}	
		}

function MpMenu($current) {

$catbox = empty($_REQUEST['catbox'])?1:intval($_REQUEST['catbox']);

global $xoopsTpl, $catbox;

$_menu = array('index.php?op=menu' => _MP_HOME, 'msgbox.php?catbox='.$catbox.'' => _MP_MBOX,'contbox.php' => _MP_CONTACT, 'filebox.php' => _MP_MFILE, 'optionbox.php' => _MP_MOPTION);

$form = "<div id='mpbuttonbar'>";
foreach ($_menu as $option => $name) {
        if ($option == $current) {
            $opt_selected = "id='current'";
        } else {
            $opt_selected = "";
        } 
      $form .= "<a href='" . $option . "' $opt_selected><span>" . $name . "</span></a>";
    } 
    $form .= "</div>";

return $form;
	
}

function WysiwygForm($caption, $name, $value, $width = '100%', $height = '400px', $formtype)
{

 global $xoopsModuleConfig;

	$editor = false;
	switch($formtype){
	
		//compact no html
case "1":
			$editor = new XoopsFormTextArea( $caption, $name, $value, 15, 85 );	
		break;
	    //dhtml
case "2":
default:
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );	
		break;
		//htmlarea
case "3":
		if ( is_readable(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php"))	{
		include_once(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php");
		$editor = new XoopsFormHtmlarea($caption, $name, $value, $width, $height);
		$isWysiwyg = true;
	    $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
	//koivi
case "4":
		if (is_readable(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php"))	{
		$mp_module_header= include_once XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php';
		$wysiwyg_text_area_01= new XoopsFormWysiwygTextArea( $caption, $name, $value, '100%', '400px','');
		$wysiwyg_text_area_01->setUrl("/class/wysiwyg");
		$wysiwyg_text_area_01->setSkin("default");
		$editor = $wysiwyg_text_area_01;
		$isWysiwyg = true;
	    $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
	//tinieditor
case "5":
		if ( is_readable(XOOPS_ROOT_PATH ."/class/xoopseditor/tinyeditor/formtinyeditortextarea.php"))	{
		 include_once XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php';
		$editor = new XoopsFormTinyeditorTextArea(array('caption'=> $caption, 'name'=> $name, 'value'=> $value, 'width'=>'100%', 'height'=>'400px'),false);
		$isWysiwyg = true;
	    $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
	//inbetween
case "6":
		if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/inbetween/forminbetweentextarea.php"))	{
		include_once XOOPS_ROOT_PATH . '/class/xoopseditor/inbetween/forminbetweentextarea.php';
		$editor = new XoopsFormInbetweenTextArea(array('caption'=> $caption, 'name'=> $name, 'value'=> $value, 'width'=>'100%', 'height'=>'400px'),false);
		$isWysiwyg = true;
	    $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
		//spaw
case "7":
if (is_readable(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php"))	{
			include_once(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php");
			$editor = new XoopsFormSpaw($caption, $name, $value, 15, 85);
			$isWysiwyg = true;
	        $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
	//FCK
case "8":
		if ( is_readable(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php"))	{
			include_once(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php");
			$editor = new XoopsFormFckeditor($caption, $name, $value, 15, 85);
			$isWysiwyg = true;
	        $dohtml = 1;
		} else {
		$editor = new XoopsFormDhtmlTextArea( $caption, $name, $value, 15, 85 );
		}
		break;
	}

	return $editor;
}

function mp_selecteditor()
{

$selecteditor = array();
$selecteditor[1] = 'Compact';
$selecteditor[2] = 'DHTML';
if ( is_readable(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php"))	{
$selecteditor[3] = 'htmlarea';
}
if (is_readable(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php"))	{
$selecteditor[4] = 'Koivi';
}
if ( is_readable(XOOPS_ROOT_PATH ."/class/xoopseditor/tinyeditor/formtinyeditortextarea.php"))	{
$selecteditor[5] = 'TinyEditor';
}
if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/inbetween/forminbetweentextarea.php"))	{
$selecteditor[6] = 'Inbetween'; 
}
if (is_readable(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php"))	{
$selecteditor[7] = 'spaw';
}
if ( is_readable(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php"))	{
$selecteditor[8] = 'FCK';
}
	
	return $selecteditor;
}
 
function MPPrettySize($size)
{
    $mb = 1024 * 1024;
    if ($size > $mb)
    {
        $mysize = sprintf ("%01.2f", $size / $mb) . " Mo";
    }elseif ($size >= 1024)
    {
        $mysize = sprintf ("%01.2f", $size / 1024) . " Ko";
    }
    else
    {
        $mysize = sprintf(_MD_NUMBYTES, $size);
    }
    return $mysize;
}

function mp_getLinkedUname($userid)
{
    $userid = intval($userid);
    if ($userid > 0) {
        $member_handler =& xoops_gethandler('member');
        $user =& $member_handler->getUser($userid);
        if (is_object($user)) {
            $linkeduser = '[url='.XOOPS_URL.'/userinfo.php?uid='.$userid.']'. $user->getVar('uname').'[/url]';
            return $linkeduser;
        }
    }
    return $GLOBALS['xoopsConfig']['anonymous'];
}



function mp_getUptotal($fileup)
{
 global $xoopsDB, $xoopsModule, $xoopsUser;
$sq2 = "SELECT COUNT(*) from ".$xoopsDB->prefix("priv_msgs")." where file_msg='".$fileup."'";	  
$result2 = $xoopsDB->query($sq2); 			
list($total) = $xoopsDB->fetchRow($result2);
return $total;
}	

function mp_cache()
{

echo "<script type=\"text/javascript\" src=\"layer.js\"></script>";

}
	
function Status($name) 
{
 global $xoopsDB;
$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("priv_msgs")."'";
$result1=$xoopsDB->queryF($sq1); 
$row=$xoopsDB->fetchArray($result1);
 if ( !$row ) { 
 die('Erreur sql : '.$sql );
  } 
 $mpstatus = $row[$name];

return $mpstatus;
 }
  
function mp_select ($catbox, $after, $limit_msg, $sortname, $sortorder)
{
 
 $form = new xoopsSimpleForm("", "tris", "msgbox.php");
 
 $promotray = new XoopsFormElementTray('');
 $liste_date = new XoopsFormTextDateSelect(_MP_TRI_TRI, 'after', '15', $after); 
 
 $limit_select = array('10' => 10,'15' => 15,'20' => 20,'25' => 25,'30' => 30);
 $user_select = new XoopsFormSelect ("", "limit_msg", $limit_msg);
 $user_select->addOptionArray($limit_select);
 
 $order_select = array('subject' => _MP_TRI_TITLE ,'msg_time' => _MP_TRI_DATE, 'read_msg' => _MP_TRI_READ);
 $thread_select = new XoopsFormSelect ("", "sortname", $sortname);
 $thread_select->addOptionArray($order_select);
 
 $sortorder_select = array('asc' => _MP_TRI_OASC,'desc' => _MP_TRI_ODESC);
 $sort_select = new XoopsFormSelect ("", "sortorder", $sortorder);
 $sort_select->addOptionArray($sortorder_select);

 $promotray->addElement($liste_date);
 $promotray->addElement($user_select);
 $promotray->addElement($thread_select);
 $promotray->addElement($sort_select);
  
 $button_tray = new XoopsFormButton('', 'button', '<>', 'submit');
 //$button_tray->setExtra("onclick='document.prvmsg.action=\"msgbox.php\"");
 $promotray->addElement($button_tray);
 
 $form->addElement($promotray);
 $form->addElement(new XoopsFormHidden('catbox', $catbox));
 
 return $form->render();
  }
  
function mp_selectcont ($url, $op, $catbox, $after, $limit_msg, $sortname, $sortorder)
{
 $form = new XoopsSimpleForm("", "tris", "contbox.php");
 
//$form->addElement(new XoopsFormTextDateSelect(_PM_AM_PRUNEBEFORE, 'befor', '15', $_REQUEST['before']));
 
 $promotray = new XoopsFormElementTray('');
 
 $liste_date = new XoopsFormTextDateSelect(_MP_TRI_TRI, 'after', '15', $after); 
 $promotray->addElement($liste_date);
 
 $limit_select = array('10' => 10,'15' => 15,'20' => 20,'25' => 25,'30' => 30);
 $user_select = new XoopsFormSelect ('', 'limit_msg', $limit_msg);
 $user_select->addOptionArray($limit_select);
 $promotray->addElement($user_select); 
  
 $order_select = array('ct_uname' => _MP_TRI_PSEUDO, 'ct_name' => _MP_TRI_NAME, 'ct_regdate' => _MP_TRI_DATE);
 $thread_select = new XoopsFormSelect ('', 'sortname', $sortname);
 $thread_select->addOptionArray($order_select);
 $promotray->addElement($thread_select);
  
 $sortorder_select = array('asc' => _MP_TRI_OASC,'desc' => _MP_TRI_ODESC);
 $sort_select = new XoopsFormSelect ('', 'sortorder', $sortorder);
 $sort_select->addOptionArray($sortorder_select);
 $promotray->addElement($sort_select);
  
 $button_tray = new XoopsFormButton('', 'submit', '<>', 'submit');
 $button_tray->setExtra("onclick='document.prvmsg.action=\"contbox.php\"");
 $promotray->addElement($button_tray);
 
 $form->addElement($promotray);
 $form->addElement(new XoopsFormHidden('catbox', $catbox));
 
 return $form->render();
  }
  
function mp_sortorder ()
{

global $vieworder, $treesortorder;

$_select = array('flat' => _MP_TRI_FLAT,'thread' => _MP_TRI_THREAD);
$o_select = array('asc' => _MP_TRI_OASC,'desc' => _MP_TRI_ODESC);

$form = "<select name='vieworder'>";
foreach ($_select as $option => $name) {
        if ($option == $vieworder) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = "";
        } 
      $form .= "<option value='" . $option . "' $opt_selected>" . $name . "</option>";
    } 
    $form .= "</select>";
	
$form .= "&nbsp;<select name='treesortorder'>";
foreach ($o_select as $option => $name) {
        if ($option == $treesortorder) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = "";
        } 
      $form .= "<option value='" . $option . "' $opt_selected>" . $name . "</option>";
    } 
    $form .= "</select>";
	
$form .= "&nbsp;<input type='submit' onclick='document.prvmsg.action=\"viewbox.php?op=view\"' value='<>'>";
 
 return $form;
  }


function mp_online()
{

global $xoopsUser, $xoopsModule;

$online_handler =& xoops_gethandler('online');
mt_srand((double)microtime()*1000000);
    // set gc probabillity to 10% for now..
if (mt_rand(1, 100) < 11) {
$online_handler->gc(300);
}
    if (is_object($xoopsUser)) {
        $uid = $xoopsUser->getVar('uid');
        $uname = $xoopsUser->getVar('uname');
    } else {
        $uid = 0;
        $uname = '';
    }
    if (is_object($xoopsModule)) {
        $online_handler->write($uid, $uname, time(), $xoopsModule->getVar('mid'), $_SERVER['REMOTE_ADDR']);
    } else {
        $online_handler->write($uid, $uname, time(), 0, $_SERVER['REMOTE_ADDR']);
    }

}									
						   
function mp_mail ($user) {
global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;
 
 $option_handler  = & xoops_gethandler('priv_msgsopt');
 $tonotif =& $option_handler->get($user);
 $tonotif = !empty($tonotif) ? $tonotif->getVar('notif') : false;
 
if ($tonotif == "1") {
//email
$myts =& MyTextSanitizer::getInstance();
$xoopsMailer =& getMailer();
$userHandler =& xoops_gethandler('user');
$toUser =& $userHandler->get($user);
$xoopsMailer->setFromEmail ($xoopsConfig['adminmail']);
$xoopsMailer->setToEmails($toUser->email());
$xoopsMailer->setFromName($xoopsConfig['sitename']);
$xoopsMailer->setSubject(sprintf(_MP_MAIL_NOTIF, $xoopsConfig['sitename']));
$text = str_replace("{X_UNAME}", $toUser->getVar("uname"), $xoopsModuleConfig['temail']);
$text2 = str_replace("{X_LINK}", XOOPS_URL."/modules/messenger/msgbox.php?op=box", $text);
$xoopsMailer->setBody($text2);
$xoopsMailer->send();
}}

function mp_mimetypes(){
global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;
$mimetypes = '';
foreach ($xoopsModuleConfig['mimetypes'] as $bu) {
	 $bu=substr(strchr($bu, "/"),1) ; 
	$mimetypes.= '&nbsp;|&nbsp;.'.$bu;
	}
	if(strlen($mimetypes)>='100'){$mimetypes=substr($mimetypes,0,'150') . "..." ;}
	return $mimetypes;
	}

			
function mp_adminmenu ($currentoption = 0, $breadcrumb = '')
{
global $xoopsModule, $xoopsConfig;
	/* Nice buttons styles */
	echo "
    	<style type='text/css'>
    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:12px; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() . "/images/bg.png') repeat-x left bottom; font-size:12px; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		#buttonbar li { display:inline; margin:0; padding:0; }
		#buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() ."/images/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
		#buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() . "/images/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		/* Commented Backslash Hack hides rule from IE5-Mac \*/
		#buttonbar a span {float:none;}
		/* End IE5-Mac hack */
		#buttonbar a:hover span { color:#333; }
		#buttonbar #current a { background-position:0 -150px; border-width:0; }
		#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		#buttonbar a:hover { background-position:0% -150px; }
		#buttonbar a:hover span { background-position:100% -150px; }
		</style>
    ";

	

	$tblColors = Array();
	$tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = $tblColors[9] = '';
	$tblColors[$currentoption] = 'current';

	if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {

		include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
	} else {
		include_once XOOPS_ROOT_PATH . '/modules/'. $xoopsModule->dirname() .'/language/english/modinfo.php';
	}

	echo "<div id='buttontop'>";
	echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	echo "<td style=\"width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule -> getVar( 'mid' )."\">" . _MP_ADMENU9 . "</a> | <a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname') . "'>" . _MP_UPDATE . "</a> | <a href=\"about.php\">" . _MP_ABOUT . "</a></td>";
	echo "<td style=\"width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\"><strong>" . $xoopsModule->name() . " " . _MP_MODULEADMIN . "</strong> " . $breadcrumb . "</td>";
	echo "</tr></table>";
	echo "</div>";

	echo "<div id='buttonbar'>";
	echo "<ul>";
	echo "<li id='" . $tblColors[0] . "'><a href=\"index.php\"><span>"._MI_MP_ADMENU0."</span></a></li>";
	echo "<li id='" . $tblColors[1] . "'><a href=\"tris.php\"><span>" . _MI_MP_ADMENU1 . "</span></a></li>";
	//echo "<li id='" . $tblColors[2] . "'><a href=\"read.php\"><span>" . _MI_MP_ADMENU2 . "</span></a></li>";
	echo "<li id='" . $tblColors[3] . "'><a href=\"purge.php\"><span>" . _MI_MP_ADMENU3 . "</span></a></li>";
	echo "<li id='" . $tblColors[4] . "'><a href=\"notification.php\"><span>" . _MI_MP_ADMENU4 . "</span></a></li>";
	echo "<li id='" . $tblColors[5] . "'><a href=\"index.php?op=stats\"><span>" . _MI_MP_ADMENU5 . "</span></a></li>";
    echo "<li id='" . $tblColors[6] . "'><a href=\"file.php\"><span>" . _MI_MP_ADMENU6 . "</span></a></li>";
	echo "<li id='" . $tblColors[7] . "'><a href=\"anim.php\"><span>" . _MI_MP_ADMENU7 . "</span></a></li>";
	echo "<li id='" . $tblColors[8] . "'><a href=\"groupperm_global.php\"><span>" . _MI_MP_ADMENU8 . "</span></a></li>";

	echo "</ul></div>";
	echo "<pre>&nbsp;</pre><pre>&nbsp;</pre>";
}

function mp_collapsableBar($tablename = '', $iconname = '')
{
	
    ?>
	<script type="text/javascript"><!--
	function goto_URL(object)
	{
		window.location.href = object.options[object.selectedIndex].value;
	}
	
	function toggle(id)
	{
		if (document.getElementById) { obj = document.getElementById(id); }
		if (document.all) { obj = document.all[id]; }
		if (document.layers) { obj = document.layers[id]; }
		if (obj) {
			if (obj.style.display == "none") {
				obj.style.display = "";
			} else {
				obj.style.display = "none";
			}
		}
		return false;
	}
	
	var iconClose = new Image();
	iconClose.src = '../images/icon/close12.gif';
	var iconOpen = new Image();
	iconOpen.src = '../images/icon/open12.gif';
	
	function toggleIcon ( iconName )
	{
		if ( document.images[iconName].src == window.iconOpen.src ) {
			document.images[iconName].src = window.iconClose.src;
		} else if ( document.images[iconName].src == window.iconClose.src ) {
			document.images[iconName].src = window.iconOpen.src;
		}
		return;
	}
	
	//-->
	</script>
	<?php
	echo "<h4 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}

  /**
   * compareFile check if two file ar the same
   **/
  function compareFile($source,$dest) {
    if (md5_file($source) == md5_file($dest)) {
      return true;
    }else{
      return false;
    }
  }

  /**
   * isWritable check if fopen function is enable
   **/
  function isWritable () {
    $fs = fopen("../cache/write.txt","w");
    fputs($fs, "test");
    fclose($fs);
    return file_exists("../cache/write.txt");
  }

  /**
   * isRenameActive check if rename function is enable
   **/
  function isRenameActive() {
    $action=@rename("../cache/write.txt","../cache/rename.txt");
    if (!$action) $action=@rename("../cache/rename.txt","../cache/write.txt");
    return $action;
  }

  /**
   * isCopyActive check if copy function is enable
   **/
  function isCopyActive() {
    $action=@copy("../cache/write.txt","../cache/copy.txt");
    if (!$action) $action=@copy("../cache/rename.txt","../cache/copy.txt");
    return $action;
  }

  /**
   * isDeleteActive check if unlink function is enable
   **/
  function isDeleteActive() {
    $action=@unlink("../cache/rename.txt");
    return $action;
  }

  /**
   * getServerStats display server information
   **/
  function getServerStats() {
    global $xoopsModuleConfig, $xoopsDB, $module_handler;
    
    $sql = 'SELECT conf_id FROM ' . $xoopsDB->prefix('config') . ' WHERE conf_name = "theme_set"';
    $res = $xoopsDB->query( $sql );
    list( $conf_id ) = $xoopsDB->fetchRow( $res );

    $module         =& $module_handler->getByDirname('system');
    $config_handler =& xoops_gethandler('config');
    $config_theme   = $config_handler->getConfig($conf_id, true);

    echo "
    <div>&nbsp;</div>
    <fieldset>
      <legend style='font-weight: bold; color: #900;'>"._MP_XH_TITLE."</legend>
        <div style='padding: 8px;'>
          <div>"._MP_XH_PHPINI."</div>";
    $safemode=(ini_get('safe_mode')) ? _MP_XH_ON._AM_WFD_DOWN_SAFEMODEPROBLEMS : _MP_XH_OFF;
    $registerglobals=(!ini_get('register_globals')) ? "<span style='color: green;'>"._MP_XH_OFF."</span>" : "<span style='color: red;'>"._MP_XH_ON."</span>";
    $downloads = (ini_get('file_uploads')) ? "<span style='color: green;'>"._MP_XH_ON."</span>" : "<span style='color: red;'>"._MP_XH_OFF."</span>";
    echo "
        <ul>
          <li>"._MP_XH_SAFEMODESTATUS.$safemode."</li>
          <li>"._MP_XH_REGISTERGLOBALS.$registerglobals."</li>
          <li>"._MP_XH_SERVERUPLOADSTATUS.$downloads."</li>
          <li>"._MP_XH_MAXUPLOADSIZE." <span style='color: blue;font-weight: bold;'>".ini_get('upload_max_filesize')."</span></li>
          <li>"._MP_XH_MAXPOSTSIZE." <span style='color: blue;font-weight: bold;'>".ini_get('post_max_size')."</span></li>
        </ul>
      <div>"._MP_XH_GENERAL."</div>
        <ul>
          <li>"._MP_XH_SERVERPATH." <b>".XOOPS_ROOT_PATH."</b></li>
          <li>"._MP_THEME_SET." <b>".$config_theme->getConfValueForOutput()."</b></li>
        </ul>";
    $write=isWritable();
    $copy=isCopyActive();
    $rename=isRenameActive();
    $copy=isCopyActive();
    $delete=isDeleteActive();
    echo "
      <div>"._MP_XH_FCTINI."</div>
        <ul>
          <li>"._MP_XH_FCT." <i>fopen</i>() :".(($write)?"<span style='color: green;'>"._MP_XH_ON."</span>":"<span style='color: red;'>"._MP_XH_OFF."</span>")."</li>
          <li>"._MP_XH_FCT." <i>rename</i>() :".(($rename)?"<span style='color: green;'>"._MP_XH_ON."</span>":"<span style='color: red;'>"._MP_XH_OFF."</span>")."</li>
          <li>"._MP_XH_FCT." <i>copy</i>() :".(($copy)?"<span style='color: green;'>"._MP_XH_ON."</span>":"<span style='color: red;'>"._MP_XH_OFF."</span>")."</li>
          <li>"._MP_XH_FCT." <i>unlink</i>() :".(($delete)?"<span style='color: green;'>"._MP_XH_ON."</span>":"<span style='color: red;'>"._MP_XH_OFF."</span>")."</li>
        </ul>
        </div>
    </fieldset>";
  }
  
  function getFilesStatus() {
    include_once XOOPS_ROOT_PATH.'/modules/messenger/class/class.FS_Storage.php';
    global $xoopsModuleConfig, $xoopsDB, $module_handler;

    $sql = 'SELECT conf_id FROM ' . $xoopsDB->prefix('config') . ' WHERE conf_name = "theme_set"';
    $res = $xoopsDB->query( $sql );
    list( $conf_id ) = $xoopsDB->fetchRow( $res );

    $module         =& $module_handler->getByDirname('system');
    $config_handler =& xoops_gethandler('config');
    $config_theme   = $config_handler->getConfig($conf_id, true);
    
    $i=0;
    $mp_files[$i]['path'] = 'class/smarty/xoops_plugins/function.xoManager.php';
    $mp_files[$i]['desc'] = 'Display a popup, an image or an animation for send a warning to user. It can be used in the theme or in the template file';
    $mp_files[$i]['action'] = 'new';
    $i++;
    $mp_files[$i]['path'] = 'class/smarty/xoops_plugins/function.xoManagerCount.php';
    $mp_files[$i]['desc'] = 'Count new message';
    $mp_files[$i]['action'] = 'new';
    $i++;
    $mp_files[$i]['path'] = 'class/smarty/xoops_plugins/function.xoManagerCountAll.php';
    $mp_files[$i]['desc'] = 'Count all the message';
    $mp_files[$i]['action'] = 'new';
    $i++;
    $mp_files[$i]['path'] = 'class/smarty/xoops_plugins/function.xoManagerCountRead.php';
    $mp_files[$i]['desc'] = 'Count read messages';
    $mp_files[$i]['action'] = 'new';
    $i++;
    $mp_files[$i]['path'] = 'pmlite.php';
    $mp_files[$i]['desc'] = 'Popup for send message';
    $mp_files[$i]['action'] = 'copy';
    $i++;
    $mp_files[$i]['path'] = 'block/system_block_user.html';
    $mp_files[$i]['desc'] = 'System block overload (only for 2.0.14+)';
    $mp_files[$i]['action'] = 'template';
    $i++;
    $mp_files[$i]['path'] = 'system_userinfo';
    $mp_files[$i]['desc'] = 'System user overload (only for 2.0.14+)';
    $mp_files[$i]['action'] = 'template';
    
    
    echo "
    <br />
    
    <table class='outer' cellspacing='1' cellpadding='0'>
    <th colspan='3'>Liste des fichiers</th>
    ";
    foreach ($mp_files as $file) {
      switch ($file['action']) {
        case 'new':
          if(file_exists(XOOPS_ROOT_PATH.'/'.$file['path'])) {
            echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/OK.png' alt='"._MP_FILE_OK."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='remove' /><input type='submit' class='formButton' value='Remove'></form></td>
            </tr>";
          }else{
            echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/KO.png' alt='"._MP_FILE_KO."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='new' /><input type='submit' class='formButton' value='Install'></form></td>
            </tr>";
          }
          break;
        case 'copy':
          if(compareFile(XOOPS_ROOT_PATH.'/'.$file['path'],XOOPS_ROOT_PATH.'/modules/messenger/Root/'.$file['path'])) {
            echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/OK.png' alt='"._MP_FILE_OK."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='restore' /><input type='submit' class='formButton' value='Restore'></form></td>
            </tr>";
          }else{
            echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/KO.png' alt='"._MP_FILE_KO."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='copy' /><input type='submit' class='formButton' value='Install'></form></td>
            </tr>";
          }
          break;
        case 'template':
          if(file_exists(XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/modules/system/'.$file['path'])){
            if(!compareFile(XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/modules/system/'.$file['path'],XOOPS_ROOT_PATH.'/modules/messenger/Root/themes/modules/system/'.$file['path'])) {
           echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/KO.png' alt='"._MP_FILE_KO."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='install_template' /><input type='submit' class='formButton' value='Install'></form></td>
            </tr>";
            }else{
           echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/OK.png' alt='"._MP_FILE_OK."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='remove_template' /><input type='submit' class='formButton' value='Remove'></form></td>
            </tr>";
            }
          }else{
           echo "
            <tr>
              <td class='odd' style='width:20px;'><img style='margin:2px;' src='../images/KO.png' alt='"._MP_FILE_KO."' /></td>
              <td class='even'><b>".$file['path']."</b><br />&nbsp;&nbsp;<i>".$file['desc']."</i></td>
              <td class='odd' style='text-align:center;'><form method='POST' action='install.php'><input type='hidden' name='file' value='".$file['path']."' /><input type='hidden' name='action' value='install_template' /><input type='submit' class='formButton' value='Install'></form></td>
            </tr>";
          }
          break;
      }
    }
    echo "
    </table>
    
    ";
  }
?>
