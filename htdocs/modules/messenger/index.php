<?php
/**
* Module`s index
* When users click on a modules link, they come to here
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/

/* Include the module header */
require_once "header.php";
/* Define the main template before the Xoops Header inclde */
$xoopsOption['template_main'] = 'mp_index.html';
/* Include the header */
include XOOPS_ROOT_PATH."/header.php";
require_once "include/calendar.php";
/* Global Xoops User variable */
global $xoopsUser;
/* If $xoopsUser vriable is define then user is connected */
if (empty($xoopsUser)) {
  redirect_header("".XOOPS_URL."/user.php",1,_PM_REGISTERNOW);
} else {

$op = empty($_REQUEST['op'])? '' :intval($_REQUEST['op']);	

if ($ohome == 0 && $op != 'menu') {
  header('location: '.XOOPS_URL.'/modules/messenger/msgbox.php');
  }


  $catbox=1;
  $pm_handler  = & xoops_gethandler('priv_msgs');
  $criteria = new CriteriaCompo();
  $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
  $total = $pm_handler->getCount($criteria);
  $criteria->add(new Criteria('read_msg', 0));
  $criteria->add(new Criteria('cat_msg', $catbox));
  $newsct = $pm_handler->getCount($criteria);

  unset($criteria);
  
  $cat_handler  = & xoops_gethandler('priv_msgscat');
  $criteria = new CriteriaCompo(); 
  $criteria->add(new Criteria('cid', $catbox)); 
  $criteria3 = new CriteriaCompo(new Criteria('uid', $xoopsUser->getVar('uid')));
  $criteria3->add(new Criteria('ver', 1), 'OR'); 
  $criteria->add($criteria3); 

  $pm_cat =& $cat_handler->getObjects($criteria);
  foreach (array_keys($pm_cat) as $i) { 
    $catpid = $pm_cat[$i]->getVar('pid'); 
    $cattitle = $pm_cat[$i]->getVar('title');
  }
  
  $mp_total = number_format(($total*100)/$xoopsModuleConfig['maxuser'], 0, ",", " ");
  $percent="<span style='border: 1px solid rgb(0, 0, 0); background: rgb(255, 255, 255) none repeat scroll 0%; margin: 4px; text-align:center; display: block; height: 8px; width: 70%; float: left; overflow: hidden;'><span style='background:  ".$xoopsModuleConfig['cssbtext']." none repeat scroll 0%; text-align:left; display: block; height: 8px; width: ".$mp_total."%; float: left; overflow: hidden;'></span></span>".$mp_total."%";

  $calendar=calendar();
  $xoopsTpl->assign('mp_index_title', _MP_INDEX_TITLE);
  $xoopsTpl->assign('mp_index_message', _MP_MBOX);
  $xoopsTpl->assign('mp_index_message_desc', _MP_INDEX_MSG_DESC);
  $xoopsTpl->assign('mp_index_message_total', $cattitle.': <b>'.$total.'</b> '._MP_MESSAGE);
  $xoopsTpl->assign('mp_index_message_new', _MP_N.': <b>'.$newsct.'</b> '._MP_MESSAGE);
  $xoopsTpl->assign('mp_index_contact', _MP_MCONT);
  $xoopsTpl->assign('mp_index_contact_desc', _MP_INDEX_CONTACT_DESC);
  $xoopsTpl->assign('mp_index_folder', _MP_MFILE);
  $xoopsTpl->assign('mp_index_folder_desc', _MP_INDEX_FOLDER_DESC);
  $xoopsTpl->assign('mp_index_options', _MP_MOPTION);
  $xoopsTpl->assign('mp_index_options_desc', _MP_INDEX_OPTN_DESC);
  $xoopsTpl->assign('mp_calendrier', $calendar);
  $xoopsTpl->assign('mp_percent', $percent);
   $xoopsTpl->assign('xoops_module_header', $mp_module_header);
}
include XOOPS_ROOT_PATH."/footer.php";
?>
