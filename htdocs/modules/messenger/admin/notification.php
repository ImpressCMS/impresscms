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
//supr le message
case "delmp":
 
 if (isset($_REQUEST['msg_id'])) {
 $size = count($_REQUEST['msg_id']);
 $msg =& $_REQUEST['msg_id'];
 for ( $i = 0; $i < $size; $i++ ) {
 $sq2 = "UPDATE ".$xoopsDB->prefix("users")." SET notify_method = '0' WHERE uid = ".$msg[$i]; 
 $result2=$xoopsDB->queryF($sq2);
 } 
 redirect_header( 'notification.php?op=tris&start='.$_REQUEST['start'].'&limit='.$_REQUEST['limit'].'&after='.$_REQUEST['after'].'&before='.$_REQUEST['before'].'', 1, _MP_NOTIF_REDIRECT);
 exit();
}
   break;

case "default":
   default:

 $member_handler =& xoops_gethandler('member');
 $pm_handler  = & xoops_gethandler('priv_msgs');
 
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('notify_method', 1));
	
 if (isset($_REQUEST['limit'])) {
 $criteria->setLimit($_REQUEST['limit']);
 $limit = $_REQUEST['limit'];
 } else {
 $criteria->setLimit(10);
 $limit = 10;
 }

 if (isset($_REQUEST['start'])) {
 $criteria->setStart($_REQUEST['start']);
 $start = $_REQUEST['start'];
 } else {
 $criteria->setStart(0);
 $start = 0;
 }

 if (@$_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('last_login', strtotime($_REQUEST['after']), ">"));
 $after = strtotime($_REQUEST['after']);		
 }

 if (@$_REQUEST['before'] && $_REQUEST['before'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('last_login', strtotime($_REQUEST['before']), "<"));
 $before = strtotime($_REQUEST['before']);
 }
 
 $criteria->setSort('uname');
 $criteria->setOrder('ASC');
 $foundusers =& $member_handler->getUsers($criteria, true);
 $numrows = $member_handler->getUserCount($criteria);	

 if ( $numrows > $limit ) {
 $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=tris&limit='.$limit.'&after='.$_REQUEST['after'].'&before='.$_REQUEST['before']);
 $pagenav = $pagenav->renderNav(4);
 } else {
 $pagenav = '';
 }

 mp_adminmenu(4, _MP_ADMENU4);
 mp_collapsableBar('toptable', 'toptableicon');
 echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DNOTIF . "</h4>
 <div id='toptable'>
 <span style=\"color: #567; margin: 3px 0 18px 0; font-size: small; display: block; \">" . _MP_NOTIF_WARNING . "</span>
 <br /><table width='100%' border='0' cellspacing='1' class='outer'><tr><td>";

 $form = new XoopsThemeForm(_MP_TRIENOTIF, "tris", "notification.php");
 $limit_select = array('10' => 10,'15' => 15,'20' => 20,'25' => 25,'30' => 30);
 $promotray = new XoopsFormElementTray(_MP_TRIE_PAR);
 $liste_limit = new XoopsFormSelect (_MP_TRIE_PAR, "limit", $limit);
 $liste_limit->addOptionArray($limit_select);

 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_NOTIFAFTER, 'after', '', @$after));
 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_NOTIFBEFORE, 'before', '', @$before));
 
 $texte_hidden = new XoopsFormHidden("op", "tris");

 $form->addElement($liste_limit);

 $form->addElement($texte_hidden);
 
 $button_tray = new XoopsFormElementTray(_MP_ACTION ,'');
 $button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CLEAR, 'reset'));
 $button_tray->addElement(new XoopsFormButton('', 'submit', _MP_SUBMIT, 'submit'));
 $form->addElement($button_tray);

 $form->display();
 
 echo"</td></tr></table></div><br />";


 mp_collapsableBar('midletable', 'midletableicon');
 echo "<img onclick='toggle('midletable'); toggleIcon('midletableicon');' id='midletableicon' name='midletableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DRESULT . "</h3>
 <div id='midletable'>";
  
 if ($numrows>0) {
 echo '<table width="100%" cellspacing="1" class="outer"><tr>
 <th align="center" colspan="7"><b>('.$numrows.') '._MP_LAST10NOTIF.'</b></th>
 </tr><tr><form name="prvmsg" method="post" action="notification.php">
 <td class="head" align="center">'._MP_NICKNAME.'</td><td class="head" align="center">'._MP_REELNAME.'</td>
 <td class="head" align="center">'._PM_NOTIF_REGDATE.'</td><td class="head" align="center">'._PM_NOTIF_LAST.'</td>
 <td class="head" align="center">'._MP_NB.'</td><td align="center" class="head">
 <input name="allbox" id="allbox" onclick="xoopsCheckAll(\'prvmsg\', \'allbox\');" type="checkbox" value="Check All" /></td></tr>';						
		  foreach (array_keys($foundusers) as $i) {
		  $criteria = new CriteriaCompo();
		   $criteria->add(new Criteria('to_userid', $foundusers[$i]->getVar("uid")));
		    $numpm = $pm_handler->getCount($criteria);
			$uname = $foundusers[$i]->getVar("uname");
			$name = $foundusers[$i]->getVar("name");
			$user_regdate = formatTimeStamp($foundusers[$i]->getVar("user_regdate"),"s");
			$last_login =  formatTimeStamp($foundusers[$i]->getVar("last_login"),"m");

 echo '<tr><td class="even" align="center">'.$uname.'</td>
 <td class="odd" align="center">'.$name.'</td>
 <td class="even" align="center">'.$user_regdate.'</td>
 <td class="odd" align="center">'.$last_login.'</td>
 <td class="even" align="center">'.$numpm.'</td>
 <td valign="top" align="center" class="even">
 <input type="checkbox" id="msg_id[]" name="msg_id[]" value="'.$foundusers[$i]->getVar('uid').'"/>
 </td></tr>';
} 
    
 echo "&nbsp;<td colspan='7' class='even' align='right'><input name='op' type='hidden' id='op' value='delmp'>
 <input type='submit' class='formButton' name='delmp' value='"._PM_NOTIF_DEL."' />
 </td>
 <input name='start' type='hidden' value='$start'>
 <input name='limit' type='hidden' value='$limit'>
 <input name='after[date]' type='hidden' value='".@$_REQUEST['after']['date']."'>
 <input name='after[time]' type='hidden' value='".@$_REQUEST['after']['time']."'>
 <input name='before[date]' type='hidden' value='".@$_REQUEST['before']['date']."'>
 <input name='before[time]' type='hidden' value='".@$_REQUEST['before']['time']."'>
 </table><div align=right>".$pagenav."</div><br />";
 } else {
 echo '<br /><table width="100%" cellspacing="1" class="outer">
 <tr><th align="center" colspan="6"><b>'._MP_NB_MP.'</b></th>
 </tr></table></div><br /><br />';
		}
break;   
   
   }
   xoops_cp_footer();
   
?>