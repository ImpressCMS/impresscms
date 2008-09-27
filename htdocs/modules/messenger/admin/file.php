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
   
case "file":
    default:

global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $xoopsUser;
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $cat_handler  = & xoops_gethandler('priv_msgscat');
 $criteria = new CriteriaCompo();
	
if (isset($_REQUEST['limit'])) {
	$criteria->setLimit($_REQUEST['limit']);
	$limit = $_POST['limit'];
} else {
    $criteria->setLimit(10);
	$limit = 10;
}

if (isset($_REQUEST['start'])) {
	$criteria->setStart($_REQUEST['start']);
	$start = $_POST['start'];
} else {
    $criteria->setStart(0);
	$start = 0;
}

 $criteria->setSort("cid");
 $criteria->setOrder("ASC");
 $criteria->add(new Criteria('ver', 1));
 $pm_cat =& $cat_handler->getObjects($criteria);

 $criteria = new CriteriaCompo();
 $criteria->setSort("cid");
 $criteria->setOrder("ASC");
 $criteria->add(new Criteria('ver', 1, '!='));
 $pm_cat2 =& $cat_handler->getObjects($criteria);
 $numrows = $cat_handler->getCount($criteria);

if ( $numrows > $limit ) {
 
  $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=tris&limit='.$limit.'&wuser_id='.$wuser_id.'&read_msg='.$read_msg.'&dates_msg='.$dates_msg);
  $pagenav = $pagenav->renderNav();
} else {
  $pagenav = '';
}

 mp_adminmenu(6, _MP_ADMENU6);

	mp_collapsableBar('toptable', 'toptableicon');
	echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_FILEPUBLIC . "</h4>";
echo "<div id='toptable'>";
		

echo '<br /><table width="100%" cellspacing="1" class="outer">'
        .'<tr>'
		."<td class='head' align='center'>"._MP_ID."</td><td class='head' align='center'>"._MP_FILETITLE."</td><td class='head' align='center'>"._MP_FILEMSG."</td></tr>";
 foreach (array_keys($pm_cat) as $i) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_msg', $pm_cat[$i]->getVar('cid')));
    $numrows = $pm_handler->getCount($criteria);
 echo '<tr><td class="even" align="center">'.$pm_cat[$i]->getVar('cid').'</td>			
 <td class="even" align="center">'.$pm_cat[$i]->getVar('title').'</td>
 <td class="even" align="center">'.$numrows.'</td></tr>';
		}
	echo "</table></div><br />";

 mp_collapsableBar('toptable', 'toptableicon');
 echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_FILEDESC . "</h4>";
 echo "<div id='toptable'>";	
 echo '<br /><form name="prvmsg" method="post" action="file.php">
 <table width="100%" cellspacing="1" class="outer"><tr>
 <td class="head" align="center">'._MP_ID.'</td><td class="head" align="center">'._MP_RECEVER.'</td>
 <td class="head" align="center">'._MP_FILETITLE.'</td><td class="head" align="center">'._MP_FILEMSG.'</td>
 <td class="head" align="center">&nbsp;</td></tr>';
 foreach (array_keys($pm_cat2) as $i) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_msg', $pm_cat2[$i]->getVar('cid')));
    $numrows = $pm_handler->getCount($criteria);
	
 echo '<tr><td class="even" align="center">'.$pm_cat2[$i]->getVar('cid').'</td>			
 <td class="odd" align="center">'.$from_user = XoopsUser::getUnameFromId($pm_cat2[$i]->getVar('uid')).'<br /></td>
 <td class="even" align="center">'.$pm_cat2[$i]->getVar('title').'</td>
 <td class="even" align="center">'.$numrows.'</td><td class="even" align="center">
 <input name="ct_file[]" type="checkbox" value="'.$pm_cat2[$i]->getVar('cid').'"></td></tr>';
 }
 echo "<tr><td colspan='5' class='even'>
 <input name='op' type='hidden' value='delfile'>
 <input type='submit' onclick='if (confirm(\""._MP_IMSURFILE."\")) {document.prvmsg.action=\"file.php\";}' id='del' value='"._MP_SUPR."'></td>
  </tr></table></div></form>";
			
   break;
   
 case "delfile":
 $pm_handler  = & xoops_gethandler('priv_msgs');
 $cat_handler  = & xoops_gethandler('priv_msgscat');
 if (isset($HTTP_POST_VARS['ct_file'])) {
 $size = count($_POST['ct_file']);
 $msg =& $_POST['ct_file'];
 for ( $i = 0; $i < $size; $i++ ) {
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('cat_msg', $msg[$i]));
 $pm =& $pm_handler->getObjects($criteria);
 $cat =& $cat_handler->get($msg[$i]);
//cherche les fichiers
 $notifycriteria2 = $criteria;
 $fileup = $pm_handler->getCountFile($notifycriteria2);	  	
 foreach ($fileup as $file_msg => $topic) {
	  if (mp_getUptotal($file_msg) == $topic['file']) {
	  $file = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/".$file_msg;
	  if (is_file($file)) {
	  @unlink($file);
		}
	  }}
 $deletedrows = $pm_handler->deleteAll($criteria);
 $deletedrows = $cat_handler->delete($cat);
        }
 unset($pm);
 unset($cat);
 redirect_header("file.php",1, _MP_FILEDELETED);
}

   break;
   
   
   }
   xoops_cp_footer();
   
?>