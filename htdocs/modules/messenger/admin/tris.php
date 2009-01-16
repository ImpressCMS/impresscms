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
 $pm_handler  = & xoops_gethandler('priv_msgs');
 
 if (isset($_REQUEST['msg_id'])) {
 $size = count($_REQUEST['msg_id']);
 $msg =& $_REQUEST['msg_id'];
 for ( $i = 0; $i < $size; $i++ ) {
 $pm =& $pm_handler->get($msg[$i]);
 mp_delupload($pm->getVar('file_msg'));
 $pm_handler->delete($pm);
 } 
 unset($pm); 
 redirect_header( 'tris.php?op=tris&start='.$_REQUEST['start'].'&limit='.$_REQUEST['limit'].'&after[date]='.$_REQUEST['after']['date'].'&after[time]='.$_REQUEST['after']['time'].'&before[date]='.$_REQUEST['before']['date'].'&before[time]='.$_REQUEST['before']['time'].'&read_msg='.$_REQUEST['read_msg'].'&wuser_id='.$_REQUEST['wuser_id'].'', 1, _MP_DELETE);
 exit();
}
   break;
  
   
case "default":
   default:

 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
	
 if (isset($_REQUEST['limit'])) {
 $criteria->setLimit($_REQUEST['limit']);
 $limit = intval($_REQUEST['limit']);
 } else {
 $criteria->setLimit(10);
 $limit = 10;
 }

 if (isset($_REQUEST['start'])) {
 $criteria->setStart($_REQUEST['start']);
 $start = intval($_REQUEST['start']);
 } else {
 $criteria->setStart(0);
 $start = 0;
 }
 
 $start2 = empty($_REQUEST['start2'])?0:intval($_REQUEST['start2']);
 $after = date('Y-m-d');
 if (@$_REQUEST['after'] && $_REQUEST['after'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('msg_time', strtotime($_REQUEST['after']), ">"));
 $after = strtotime($_REQUEST['after']);		
 }
 $before = date('Y-m-d');
 if (@$_REQUEST['before'] && $_REQUEST['before'] != "YYYY/MM/DD") {
 $criteria->add(new Criteria('msg_time', strtotime($_REQUEST['before']), "<"));
 $before = strtotime($_REQUEST['before']);
 }
	
 if ((isset($_REQUEST['wuser_id']) || @$_REQUEST['wuser_id'] != '' AND @$_REQUEST['wuser_id'] != 0)) {
 $criteria->add(new Criteria('to_userid', $_REQUEST['wuser_id']));
 }
	
 if ((isset($_REQUEST['read_msg']) || @$_REQUEST['read_msg'] == 1 OR @$_REQUEST['read_msg'] == 2)) {
 if (@$_REQUEST['read_msg'] == 1) {
 $criteria->add(new Criteria('read_msg', 1));
 }
 if (@$_REQUEST['read_msg'] == 2) {
 $criteria->add(new Criteria('read_msg', 0));
 } }

 $criteria->setSort("msg_time");
 $criteria->setOrder("DESC");
 $pm_arr =& $pm_handler->getObjects($criteria);
 $numrows = $pm_handler->getCount($criteria);	

 if ( $numrows > $limit ) {
 $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=tris&limit='.$limit.'&after='.@$_REQUEST['after'].'&before='.@$_REQUEST['before'].'&read_msg='.@$_REQUEST['read_msg'].'&wuser_id='.@$_REQUEST['wuser_id']);
 $pagenav = $pagenav->renderNav(4);
 } else {
 $pagenav = '';
 }

 mp_adminmenu(1, _MP_ADMENU1);
 mp_collapsableBar('toptable', 'toptableicon');
 echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DTRIS . "</h4>
 <div id='toptable'><br /><table width='100%' border='0' cellspacing='1' class='outer'><tr><td>";

 $form = new XoopsThemeForm(_MP_TRIE, "tris", "tris.php");
 $limit_select = array('10' => 10,'15' => 15,'20' => 20,'25' => 25,'30' => 30);
 $promotray = new XoopsFormElementTray(_MP_TRIE_PAR);
 $liste_limit = new XoopsFormSelect ("", "limit", $limit);
 $liste_limit->addOptionArray($limit_select);

 $all = array('0' => _MP_ALL); 
 $user_select = new MPFormSelectUser('', 'wuser_id', $start2, '200', @$_REQUEST['wuser_id'], 0 , 0, 0); 
 $user_select->addOptionArray($all);
 $user_select_tray = new XoopsFormElementTray(_MP_NICKNAME, "<br />");
 $user_select_tray->addElement($user_select);
 $member_handler = &xoops_gethandler('member');
 $usercount = $member_handler->getUserCount(); 
 $nav = new XoopsPageNav($usercount, '200', $start2, "start2", "op=tris");
 $user_select_label = new XoopsFormLabel('', $nav->renderNav(4));

 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_PRUNEAFTER, 'after', '15', $after)); 
 $form->addElement(new XoopsFormTextDateSelect(_PM_AM_PRUNEBEFORE, 'before', '15', $before));
 
 $read = array(_MP_TRIE_LU."/"._MP_TRIE_NONLU, _MP_TRIE_LU, _MP_TRIE_NONLU);
 $liste_read = new XoopsFormSelect (_MP_ETAT, "read_msg", @$_REQUEST['read_msg']);
 $liste_read->addOptionArray($read);
 
 $texte_hidden = new XoopsFormHidden("op", "tris");

 $promotray->addElement($liste_limit);
 $promotray->addElement($user_select_tray);
 $promotray->addElement($user_select_label);

 $form->addElement($texte_hidden);
 $form->addElement($promotray);
 $form->addElement($liste_read);
 
 $button_tray = new XoopsFormElementTray(_MP_ACTION ,'');
 $button_tray->addElement(new XoopsFormButton('', 'reset', _MP_CLEAR, 'reset'));
 $button_tray->addElement(new XoopsFormButton('', 'submit', _MP_SUBMIT, 'submit'));
 $form->addElement($button_tray);

 $form->display();
 
 echo"</td></tr></table></div><br />";

 if ($xoopsModuleConfig['optimise'] == 1 )
 {
 mysql_query('OPTIMIZE TABLE '.$xoopsDB->prefix("priv_msgs")); 
 }

 mp_collapsableBar('midletable', 'midletableicon');
 echo "<img onclick='toggle('midletable'); toggleIcon('midletableicon');' id='midletableicon' name='midletableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DRESULT . "</h3>
 <div id='midletable'>";
 
 if ($numrows>0) {
 echo '<table width="100%" cellspacing="1" class="outer"><tr>
 <th align="center" colspan="7"><b>('.$numrows.') '._MP_LAST10ARTS.'</b></th>
 </tr><tr><form name="prvmsg" method="post" action="tris.php">
 <td class="head" align="center"></td><td class="head" align="center">'._MP_PUBLISHED.'</td>
 <td class="head" align="center">'._MP_TITLE.'</td><td class="head" align="center">'._MP_POSTER.'</td>
 <td class="head" align="center">'._MP_RECEVER.'</td><td align="center" class="head">
 <input name="allbox" id="allbox" onclick="xoopsCheckAll(\'prvmsg\', \'allbox\');" type="checkbox" value="Check All" /></td></tr>';						
		  foreach (array_keys($pm_arr) as $i) {	
			$msg_time = formatTimestamp($pm_arr[$i]->getVar('msg_time'),"m");
			$subject = $myts->makeTboxData4Show($pm_arr[$i]->getVar('subject'));
			$from_user = XoopsUser::getUnameFromId($pm_arr[$i]->getVar('from_userid'));
			$to_user =  XoopsUser::getUnameFromId($pm_arr[$i]->getVar('to_userid'));
//icone
 if ($pm_arr[$i]->getVar('read_msg') == 1){
 $msg_icone = '<img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/lus.png" alt='._MP_TRIE_LU.'>';
 } else{
 $msg_icone = '<img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/new.png" alt='._MP_TRIE_NONLU.'>';
 }
 echo '<tr><td class="even" align="center">'.$msg_icone.'</td>
 <td class="odd" align="center">'.$msg_time.'</td>
 <td class="even" align="center">'.$subject.'</td>
 <td class="odd" align="center">'.$from_user.'</td>
 <td class="even" align="center">'.$to_user.'</td>
 <td valign="top" align="center" class="even">
 <input type="checkbox" id="msg_id[]" name="msg_id[]" value="'.$pm_arr[$i]->getVar('msg_id').'"/>
 </td></tr>';
} 
    
 echo "&nbsp;<td colspan='7' class='even' align='right'><input name='op' type='hidden' id='op' value='delmp'>
 <input type='submit' class='formButton' name='delmp' value='"._MP_SUPR."' /></td>
 <input name='start' type='hidden' value='$start'>
 <input name='limit' type='hidden' value='$limit'>
 <input name='wuser_id' type='hidden' value='".@$_REQUEST['wuser_id']."'>
 <input name='read_msg' type='hidden' value='".@$_REQUEST['read_msg']."'>
 <input name='after' type='hidden' value='".@$_REQUEST['after']."'>
 <input name='before' type='hidden' value='".@$_REQUEST['before']."'>
 </table><div align=right>".$pagenav."</div><br />
 <img src=\"".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/lus.png\">&nbsp;"._MP_ICONE."&nbsp;&nbsp;
 <img src=\"".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/new.png\">&nbsp;"._MP_NICONE."<br /></div>";
 } else {
 echo '<br /><table width="100%" cellspacing="1" class="outer">
 <tr><th align="center" colspan="6"><b>'._MP_NB_MP.'</b></th>
 </tr></table></div><br /><br />';
		}
break;   
   
   }
   xoops_cp_footer();
   
?>