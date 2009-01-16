<?PHP
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
include("admin_header.php");


$myts =& MyTextSanitizer::getInstance();
  
if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
@$op = 'default';
}



 xoops_cp_header();	 
  
global $xoopsDB, $xoopsConfig, $_REQUEST, $xoopsModule, $myts, $xoopsUser;

switch( $op )
{  
   
case "purge":
      default:
	  
$start = empty($_REQUEST['start'])?0:intval($_REQUEST['start']);

$pm_handler  = & xoops_gethandler('priv_msgs');
  
 mp_adminmenu(3, _MP_ADMENU3);
 mp_collapsableBar('toptable', 'toptableicon');
	echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DPURGE . "</h4>";
    echo "<div id='toptable'>";
    echo "<span style=\"color: #567; margin: 3px 0 18px 0; font-size: small; display: block; \">" . _MP_WARNING . "</span>";
    echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td>";
		
 $form = new XoopsThemeForm(_MP_PURGE, "purge", "purge.php");
  $after = date('Y-m-d');
 if (@$_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $after = strtotime($_REQUEST['after']);		
 }
 $before = date('Y-m-d');
 if (@$_REQUEST['before'] && $_REQUEST['before'] != "YYYY/MM/DD") {
 $before = strtotime($_REQUEST['before']);
 }
//$form->addElement(new XoopsFormDateTime(_PM_AM_PRUNEAFTER, 'after'));
 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_PRUNEAFTER, 'after', '15',$after)); 
 //$form->addElement(new XoopsFormDateTime(_PM_AM_PRUNEBEFORE, 'before'));
 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_PRUNEBEFORE, 'before', '15', $before));
		
 $user_select = new MPFormSelectUser('', 'del_userid', $start, '200', '');
 $user_select_tray = new XoopsFormElementTray(_MP_NICKNAME, "<br />");
 $user_select_tray->addElement($user_select);
 $member_handler = &xoops_gethandler('member');
 $usercount = $member_handler->getUserCount(); 
 $nav = new XoopsPageNav($usercount, '200', $start, "start", "op=purge");
 $user_select_nav = new XoopsFormLabel('', $nav->renderNav(4));
 $user_select_tray->addElement($user_select_nav);
 $form->addElement($user_select_tray);
		
 $groupe_select = new XoopsFormSelectGroup(_MP_GROUPE, "del_groupe", false, '', 5, true);
 $form->addElement($groupe_select);
		
 $form->addElement(new XoopsFormRadioYN(_PM_AM_ONLYREADMESSAGES, 'onlyread', 1));
 $form->addElement(new XoopsFormRadioYN(_PM_AM_INCLUDESEND, 'includesend', 0));
 $form->addElement(new XoopsFormRadioYN(_PM_AM_INCLUDESAVE, 'includesave', 0));
 $form->addElement(new XoopsFormRadioYN(_PM_AM_INCLUDEFILE, 'includefile', 0));
 $form->addElement(new XoopsFormRadioYN(_PM_AM_NOTIFYUSERS, 'notifyusers', 0));
		
 $form->addElement(new XoopsFormHidden('op', 'prune'));
 $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
 $form->display();
 
 echo"</td></tr></table><br />";
break;
   
case "prune":

 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $savecriteria = new CriteriaCompo();

  if ((!isset($_REQUEST['includefile']) || $_REQUEST['includefile'] == 1)) {
   $savecriteria->add(new Criteria('cat_msg', 2, '!='));
   $savecriteria->add(new Criteria('cat_msg', 1, '!='));
   $savecriteria->add(new Criteria('cat_msg', 3, '!='));
 } else {
 $savecriteria ->add(new Criteria('cat_msg', 1));
 }

 if (isset($_REQUEST['del_groupe']) || isset($_REQUEST['del_userid'])) {
  $tocriteria = new CriteriaCompo();
  }

 if ($_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('msg_time', strtotime($_REQUEST['after']), ">"));
 }
 if ($_REQUEST['before'] && $_REQUEST['before'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('msg_time', strtotime($_REQUEST['before']), "<"));
 }
 if (isset($_REQUEST['onlyread']) && $_REQUEST['onlyread'] == 0) {
 $criteria->add(new Criteria('read_msg', 1));
 }
 if ((!isset($_REQUEST['includesave']) || $_REQUEST['includesave'] == 1)) {
 $savecriteria->add(new Criteria('cat_msg', 3), 'OR');
 }
 if ((!isset($_REQUEST['includesend']) || $_REQUEST['includesend'] == 1)) {
        $savecriteria->add(new Criteria('cat_msg', 2), 'OR');
 }

 if ((isset($_REQUEST['del_userid']) || $_REQUEST['del_userid'] != '')) {

 foreach ($_REQUEST['del_userid'] as $del) {
 $tocriteria->add(new Criteria('to_userid', $del), 'OR');
 } }
	
 if ((isset($_REQUEST['del_groupe']) || $_REQUEST['del_groupe'] != '')) {
 foreach ($_REQUEST['del_groupe'] as $del => $u_name) {
 $member_handler =& xoops_gethandler('member');
 $members =& $member_handler->getUsersByGroup($u_name, true);
 $mcount = count($members);
 for ($i = 0; $i < $mcount; $i++) {
 $tocriteria->add(new Criteria('to_userid',  $members[$i]->getVar('uid')), 'OR');
 } }  }
 
  if (isset($_REQUEST['del_groupe']) || isset($_REQUEST['del_userid'])) {
  $criteria->add($tocriteria);
  }

 $criteria->add($savecriteria); 
 //cherche les fichiers attacher  
 
 //$fileup = $pm_handler->getCountFile($notifycriteria2);	  	
 //foreach ($fileup as $file_msg => $topic) {
 //if (mp_getUptotal($file_msg) == $topic['file']) {
 //$file = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/".$file_msg;
 //if (is_file($file)) {
 //@unlink($file);
 //} } }
 
  if (isset($_REQUEST['notifyusers']) && $_REQUEST['notifyusers'] == 1) {
 $notifycriteria = $criteria;
 $uids = $pm_handler->getCountTouser($notifycriteria);
 }
 
 $up =& $pm_handler->getObjects($criteria);
 foreach (array_keys($up) as $i) { 
 mp_delupload($up[$i]->getVar('file_msg'));
 }
 $deletedrows = $pm_handler->deleteAll($criteria);
 
  if ($deletedrows === false) {
 redirect_header('index.php?op=purge', 2, _PM_AM_ERRORWHILEPRUNING);
 }
 
  if (isset($_REQUEST['notifyusers']) && $_REQUEST['notifyusers'] == 1) {
 $errors = false;
 foreach ($uids as $uid => $messagecount) {
 $pm = $pm_handler->create(); 
 $pm->setVar("cat_msg", 1);
 $pm->setVar("msg_time", time());
 $pm->setVar("subject", _MP_SUBJECT_PRUNE);
 $pm->setVar("msg_text", str_replace('{X_COUNT}', $messagecount['count'], $xoopsModuleConfig['prunemessage']));
 $pm->setVar("to_userid", $uid);
 $pm->setVar("from_userid", $xoopsUser->getVar("uid"));            
 $pm_handler->insert($pm);
 unset($pm);
 }
 
  }
 
 //optimise la table
 if ($xoopsModuleConfig['optimise'] == 1 ) {
 mysql_query('OPTIMIZE TABLE '.$xoopsDB->prefix("priv_msgs")); 
 }
 
 redirect_header('purge.php?after='.strtotime($_REQUEST['after']).'&before='.strtotime($_REQUEST['before']).'', 2, sprintf(_MP_DELETE, $deletedrows));
   
break;   
   
   }
   xoops_cp_footer();
   
?>