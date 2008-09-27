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

include("../../mainfile.php");
/* MusS : Use XOOPS_ROOT_PATH for all include file */
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgs.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgscat.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgscont.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgsopt.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgsup.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/formselectuser.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/selectuser.php';


$mp_module_header = "<link rel='stylesheet' type='text/css' href='" . XOOPS_URL . "/modules/messenger/mpstyle.css'/><script type='text/javascript' src='".XOOPS_URL."/modules/messenger/include/multifile.js'></script>";


$myts = & MyTextSanitizer :: getInstance(); // MyTextSanitizer object
$mydirname = basename( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/$mydirname/include/get_perms.php" ;

if (!empty($xoopsUser)) {
 //option utilisateur
 $opt_handler  = & xoops_gethandler('priv_msgsopt');
 $opt =& $opt_handler->get($xoopsUser->getVar('uid'));
 if (!$opt) {
 $onotif = false; $oresend = false; $osortorder = false;
 $osortname = false; $olimite  = false; $ohome  = false; $ovieworder = false;
 $oformtype = false;
 $msg_alert = _MP_AOERT;
 } else {
 $onotif = $opt->getVar('notif'); $oresend = $opt->getVar('resend');
 $osortorder = $opt->getVar('sortorder'); $osortname = $opt->getVar('sortname');
 $olimite  = $opt->getVar('limite'); $ohome  = $opt->getVar('home'); 
 $ovieworder = $opt->getVar('vieworder'); $oformtype = $opt->getVar('formtype');
 }
 }
/* MusS : Include the pmsg.php file */
if (file_exists(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/pmsg.php')){
	include_once(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/pmsg.php');
}else{
	if (file_exists(XOOPS_ROOT_PATH . '/language/english/pmsg.php')){
		include_once(XOOPS_ROOT_PATH . '/language/english/pmsg.php');
	}else{
    /* MusS : Compatibility with Xoops 2.2.* */
    if(file_exists(XOOPS_ROOT_PATH.'/modules/pm/language/'.$xoopsConfig['language'].'/main.php')){
      include_once XOOPS_ROOT_PATH.'/modules/pm/language/'.$xoopsConfig['language'].'/main.php';
    }else{
      if(file_exists(XOOPS_ROOT_PATH.'/modules/pm/language/english/main.php')){
      include_once XOOPS_ROOT_PATH.'/modules/pm/language/english/main.php';
      }
    }
  }
}
/* MusS : Include modinfo.php for include constant */
if( file_exists(XOOPS_ROOT_PATH."/modules/".$mydirname."/language/".$xoopsConfig['language']."/modinfo.php") ) {
	include_once(XOOPS_ROOT_PATH."/modules/".$mydirname."/language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include_once(XOOPS_ROOT_PATH."/modules/".$mydirname."/language/english/modinfo.php");
}
?>
