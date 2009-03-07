<?php



function smarty_function_xoManager( $params, &$smarty ) {
	global $xoops,$xoopsUser,$xoopsConfig, $_COOKIE, $xoopsModule;

	if ( !isset($xoopsUser) || !is_object($xoopsUser) ) {
		return;
	}
	
require_once XOOPS_ROOT_PATH . '/modules/messenger/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgs.php';
$modhandler = &xoops_gethandler('module');
$xoopsMPModule = &$modhandler->getByDirname("messenger");
$config_handler = &xoops_gethandler('config');
$xoopsModuleConfig = &$config_handler->getConfigsByCat(0, $xoopsMPModule->getVar('mid'));
 $myts =& MyTextSanitizer::getInstance();
mp_online();

echo "<script type=\"text/javascript\">
function cacheFlash()
  {
  window.document.flash.TStopPlay(\"_level0\");
  document.getElementById(\"anim\").style.visibility=\"hidden\";
  }
</script>";
  


 if( file_exists(XOOPS_ROOT_PATH. "/modules/messenger/language/".$xoopsConfig['language']."/blocks.php") ) {
	include(XOOPS_ROOT_PATH. "/modules/messenger/language/".$xoopsConfig['language']."/blocks.php");
} else {
	include(XOOPS_ROOT_PATH ."/modules/messenger/language/english/blocks.php");
}


$pm_handler  =& xoops_gethandler('priv_msgs');
$criteria = new CriteriaCompo( new Criteria('read_msg', 0) );
$criteria->add( new Criteria( 'to_userid', $xoopsUser->getVar('uid') ) );
$count = intval( $pm_handler->getCount($criteria) );

if ($xoopsModuleConfig['auto_mp'] == 1) {

global $xoopsDB;
$member_handler =& xoops_gethandler('member');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('uid', $xoopsModuleConfig['auto_uid'], '>')); 
$finuser =& $member_handler->getUsers($criteria); 
if(count($finuser)>0){
foreach (array_keys($finuser) as $i) {
$pm =& $pm_handler->create();
 $msg_text = str_replace("{X_UNAME}", $finuser[$i]->getVar('uname'),  $xoopsModuleConfig['auto_text']);
$msg_text2 = str_replace("{X_LINK}", XOOPS_URL."/modules/messenger/msgbox.php?op=box", $msg_text);
$msg_text3 = str_replace("{X_ADMINMAIL}", $xoopsConfig['adminmail'], $msg_text2);
$msg_text4 = str_replace("{X_SITENAME}", $xoopsConfig['sitename'], $msg_text3);
$msg_text5 = str_replace("{X_SITEURL}", XOOPS_URL, $msg_text4);

$text = addslashes($msg_text5);

 $pm->setVar("cat_msg", 1);
 $pm->setVar("to_userid", $finuser[$i]->getVar('uid'));
 $pm->setVar("msg_time", time());
 $pm->setVar("from_userid", '1');
 $pm->setVar("subject", $myts->htmlSpecialChars($myts->stripSlashesGPC($xoopsModuleConfig['auto_suject'])));
 $pm->setVar("msg_text", $myts->htmlSpecialChars($myts->stripSlashesGPC($msg_text5)));
 $erreur = $pm_handler->insert($pm);
}

 $sq2 = "UPDATE ".$xoopsDB->prefix("config")." SET conf_value = '".$finuser[$i]->getVar('uid')."' WHERE conf_name = 'auto_uid'"; 
 $result2=$xoopsDB->queryF($sq2);
}
 
}

if (@$_COOKIE['messenger'] < $count) {

if ($xoopsModuleConfig['newmsg'] == "popup" && $count > 0) {
 $mes .= '<div id="pop_up" style="color: '.$xoopsModuleConfig['cssptext'].';
	width: 300px;
	position: absolute;
	height: 130px;
	background: '.$xoopsModuleConfig['csspback'].';
	border: 3px double black;
	padding: 5px;
	text-align: center;
	left: 50%; 
	display: block;
	margin-left: -375px;
	top: 150px;">
	<table><tr><td align="center" width="95%">'._MP_YOUBLOCK.'&nbsp;<span style="color: '.$xoopsModuleConfig['cssbtext'].';"><b>'.$count.'</b></span>&nbsp;'._MP_NMSGBLOCK.'</td>
	<td width="5%"><img src="'.XOOPS_URL.'/modules/messenger/images/close.png"  title="'._MP_CLOSE.'" style="cursor:pointer; border-width: 0px; width: 15px; height: 15px;" onClick="javascript: document.cookie=\'messenger='.$count.';path=/\';document.getElementById(\'pop_up\').style.visibility=\'hidden\';"></td>
	</tr><td colspan="2"><img src="'.XOOPS_URL.'/modules/messenger/images/newmp.png" title="'._MP_MP.'" style="cursor:pointer; border-width: 0px; width: 100px; height: 100px;" OnClick="javascript: document.cookie=\'messenger='.$count.';path=/\';document.getElementById(\'pop_up\').style.visibility=\'hidden\'; window.location.href=\''.XOOPS_URL.'/modules/messenger/msgbox.php\'"></td></tr></table></div>';	
}
if ($xoopsModuleConfig['newmsg'] == "image" && $count > 0) {
$mes .= '<br /><div align="center"><img id="mpimg" src="'.XOOPS_URL.'/modules/messenger/images/newmp.png" title="'._MP_MP.'" style="cursor:pointer; border-width: 0px; width: 100px; height: 100px;"  OnClick="javascript: document.cookie=\'messenger='.$count.';path=/\'; window.location.href=\''.XOOPS_URL.'/modules/messenger/msgbox.php\'"></div>';
}
	
if ($xoopsModuleConfig['newmsg'] == "son" && $count > 0) {
$mes .= '<embed src="'.XOOPS_URL.'/modules/messenger/msg.wav" width="100" height="30" autostart="true" onClick="javascript: document.cookie=\'messenger='.$count.';path=/\';">';
	}
	

if ($xoopsModuleConfig['newmsg'] == "anim" && $count > 0) {
 $mes .= '<div id="anim" style="width: 640px; background: '.$xoopsModuleConfig['csspback'].'; border: 3px double black; position: absolute; height: 380px; left: 50%; 
 margin-left: -375px; top: 100px;"><table><tr>
 <td align="center" width="95%">'._MP_YOUBLOCK.'&nbsp;<span style="color: '.$xoopsModuleConfig['cssbtext'].';"><b>'.$count.'</b></span>&nbsp;'._MP_NMSGBLOCK.'</td>
 <td width="5%"><img src="'.XOOPS_URL.'/modules/messenger/images/close.png"  title="'._MP_CLOSE.'" style="cursor:pointer; border-width: 0px; width: 15px; height: 15px;" onClick="javascript: document.cookie=\'messenger='.$count.';path=/\';document.getElementById(\'anim\').style.visibility=\'hidden\';"></td></tr>
 <tr><td colspan="2"><embed src="'.XOOPS_URL.'/modules/messenger/alert.swf" wmode="transparent" id="flash" loop="false" name="flash" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="640" height="360" OnClick="javascript: document.cookie=\'messenger='.$count.';path=/\';document.getElementById(\'anim\').style.visibility=\'hidden\'; window.location.href=\''.XOOPS_URL.'/modules/messenger/msgbox.php\'"></embed>
 <NOEMBED>'._MP_NOOEIL.'</NOEMBED></td></tr></table></div>';
}
}

if ($xoopsModuleConfig['useralert'] == 1) {

$pm_handler  =& xoops_gethandler('priv_msgs');
$criteria = new CriteriaCompo();
$criteria->add( new Criteria( 'to_userid', $xoopsUser->getVar('uid') ) );
$total = $pm_handler->getCount($criteria);
if ($total > $xoopsModuleConfig['maxuser']) {
 
if (!@$_COOKIE['messengeralert']) {
 $mes .= '<div id="pop_upalert" style="color: '.$xoopsModuleConfig['cssptext'].';
	width: 350px;
	position: absolute;
	height: 130px;
	background: '.$xoopsModuleConfig['csspback'].';
	border: 3px double black;
	padding: 5px;
	text-align: center;
	left: 50%; 
	display: block;
	margin-left: -375px;
	top: 150px;">
	<table><tr><td align="center" width="95%">'._MP_ALERTBLOCK.'</td>
	<td width="5%"><img src="'.XOOPS_URL.'/modules/messenger/images/close.png" title="'._MP_CLOSE.'" style="cursor:pointer; border-width: 0px; width: 15px; height: 15px;" onClick="javascript: document.cookie=\'messengeralert=1;path=/\';document.getElementById(\'pop_upalert\').style.visibility=\'hidden\';"></td>
	</tr><td colspan="2"><img src="'.XOOPS_URL.'/modules/messenger/images/stopmp.png" title="'._MP_MP.'" style="cursor:pointer; border-width: 0px; width: 100px; height: 100px;" OnClick="javascript: document.cookie=\'messengeralert=1;path=/\';document.getElementById(\'pop_upalert\').style.visibility=\'hidden\'; window.location.href=\''.XOOPS_URL.'/modules/messenger/msgbox.php\'"></td></tr></table></div>';
}


}


	}			
	echo $mes;
	
	
}
?>