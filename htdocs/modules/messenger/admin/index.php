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

case "stats":

 $pm_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->setSort("msg_time");
 $criteria->setOrder("DESC");
 $criteria->setLimit(5);
 $pm_arr =& $pm_handler->getObjects($criteria);

 mp_adminmenu(5, _MP_ADMENU5);
 mp_collapsableBar('toptable', 'toptableicon');
 echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_10DATE . "</h4>
 <div id='toptable'><br /><table width='100%' cellspacing='1' class='outer'><tr>
 <form name='prvmsg' method='post' action='index.php'>
 <td class='head' align='center'>"._MP_PUBLISHED."</td><td class='head' align='center'>"._MP_POSTER."</td><td class='head' align='center'>"._MP_RECEVER."</td></tr>";
 
 foreach (array_keys($pm_arr) as $i) {	
 $msg_time = formatTimestamp($pm_arr[$i]->getVar('msg_time'),"m");						
 $subject = $myts->makeTboxData4Show($pm_arr[$i]->getVar('subject'));
 $from_user = XoopsUser::getUnameFromId($pm_arr[$i]->getVar('from_userid'));
 $to_user =  XoopsUser::getUnameFromId($pm_arr[$i]->getVar('to_userid'));
				
 echo '<tr>
 <td class="odd" align="center">'.$msg_time.'</td>
 <td class="odd" align="center">'.$from_user.'</td>
 <td class="even" align="center">'.$to_user.'</td>
 </tr></td></tr>';
 }
 echo '</table></div><br /><br />';

//From 
 $result2 = $xoopsDB->query("select *, COUNT(*) As somme from ".$xoopsDB->prefix("priv_msgs"). " GROUP BY from_userid ORDER by somme DESC LIMIT 5");
//
 mp_collapsableBar('midletable', 'midletableicon');
 echo "<img onclick='toggle('midletable'); toggleIcon('midletableicon');' id='midletableicon' name='midletableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_10FROM . "</h4>";
 echo "<div id='midletable'>";
 echo "<br /><table width='100%' cellspacing='1' class='outer'><tr>
 <form name='prvmsg' method='post' action='index.php'>
 <td class='head' align='center'>"._MP_ID."</td><td class='head' align='center'>"._MP_POSTER."</td>
 <td class='head' align='center'>"._MP_MESSAGE."</td></tr>";		
	while($myrow=$xoopsDB->fetchArray($result2)){
	$poster = new XoopsUser($myrow["from_userid"]);						
	$from_user = $poster->getVar('uname');
 echo '<tr><td class="even" align="center">'.$myrow['from_userid'].'</td>
 <td class="odd" align="center">'.$from_user.'</td>
 <td class="even" align="center">'.$myrow['somme'].'</td></tr></td></tr>';
		}
 echo '</table></div><br /><br />';
	
//To
$result3 = $xoopsDB->query("select *, COUNT(*) As somme from ".$xoopsDB->prefix("priv_msgs"). " GROUP BY to_userid ORDER by somme DESC LIMIT 5");
//
 mp_collapsableBar('botomtable', 'botomtableicon');
 echo "<img onclick='toggle('botomtable'); toggleIcon('botomtableicon');' id='botomtableicon' name='botomtableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_10TO . "</h4>";
 echo "<div id='botomtable'>";
 echo "<br /><table width='100%' cellspacing='1' class='outer'><tr>
 <form name='prvmsg' method='post' action='index.php'>
 <td class='head' align='center'>"._MP_ID."</td><td class='head' align='center'>"._MP_RECEVER."</td>
 <td class='head' align='center'>"._MP_MESSAGE."</td></tr>";
	while($myrow=$xoopsDB->fetchArray($result3)){
	$toposter = new XoopsUser($myrow["to_userid"]);						
	$to_user = $toposter->getVar('uname');
 echo '<tr><td class="even" align="center">'.$myrow['to_userid'].'</td>			
 <td class="odd" align="center">'.$to_user.'<br /></td>
 <td class="even" align="center">'.$myrow['somme'].'</td></tr></td></tr>';
		}
	echo '</table><br />';	
	
   break;
   
      case "optimise":
 $sq1 = "OPTIMIZE TABLE ".$xoopsDB->prefix("priv_msgs");
 $result1 = $xoopsDB->queryF($sq1);
 if($result1){
 redirect_header( 'index.php', 1, _MP_OPTOK);
        }else{
           redirect_header( 'index.php', 1, _MP_OPTNO);
        } 
   break;
   
  case "optimisesauv":
  $sq2 = "OPTIMIZE TABLE ".$xoopsDB->prefix("priv_msgscat");
 $result2 = $xoopsDB->queryF($sq2);
 if($result2){
 redirect_header( 'index.php', 1, _MP_OPTOK);
        }else{
           redirect_header( 'index.php', 1, _MP_OPTNO);
        } 
   break;
   
case "default":
   default:

   global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;

//info priv_msgs
$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("priv_msgs")."'";
$result1=$xoopsDB->queryF($sq1); 
$row=$xoopsDB->fetchArray($result1);


$sq2 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("priv_msgscat")."'";
$result2=$xoopsDB->queryF($sq2); 
$myrow=$xoopsDB->fetchArray($result2);

//info upload
$dirup = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/";
$racine=opendir($dirup);
$taille=0;
$fileup = 0;
 while($dossier=@readdir($racine)){
if(!in_array($dossier, Array("..", "."))){
$taille+=@filesize("$dirup/$dossier");
 $fileup++;
 }
}
 @closedir($racine);
 

mp_adminmenu(0, _MP_ADMENU0);
 
//verifier l'update
if (!$row or !$myrow or !$myrow['Rows']) {
mp_collapsableBar('toptable', 'toptableicon');
echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;</h4>";
echo "<div id='toptable'>";
echo "<br />";
echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
."<tr class=\"odd\"><td><span style='color: #ff0000; font-weight: bold'>"._MP_ERRORUP."<br /><a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->dirname() . "'>UPDATE</a></td></tr></table></div><br />";
}
//


	mp_collapsableBar('toptable', 'toptableicon');
	echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_NB . "</h4>";
echo "<div id='toptable'>";
echo "<br />";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr class=\"odd\"><td>";

	echo "<br />";
	printf(_MP_THEREARE,$row['Rows']);
	echo "<br />";
	printf(_MP_THEREAREFILE,$myrow['Rows']);
	echo "<br />";
	printf(_MP_THEREAREUP,$fileup);
   	echo"</td></tr></table></div><br />";

//optimise	 
 if ($xoopsModuleConfig['optimise'] == 0 )
 {
$optimise = _MP_OPT;
}
else { $optimise = "("._MP_OPTAUTO.")"; }

//table des stats
mp_collapsableBar('midletable', 'midletableicon');
	echo "<img onclick='toggle('midletable'); toggleIcon('midletableicon');' id='midletableicon' name='midletableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_ESP . "</h4>";
echo "<div id='midletable'>";


echo"<br /><table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr class=\"odd\"><td>";  
echo '<pre><strong>' . $dirup.'</strong><br />';
echo _MP_LENGTH.':<b> ' . MPPrettySize($taille). '</b><br />';
echo"</td></tr></table><br />";

echo"<br /><table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr class=\"odd\"><td>";  
echo '<pre>Table: <strong>' . $row['Name']. '</strong><br />';
echo _MP_LENGTH.':<b> ' . MPPrettySize($row['Data_length']). '</b><br />';
echo _MP_DATE_FREE.':<b> ' . MPPrettySize($row['Data_free']).'&nbsp;<a href="index.php?op=optimise">'.$optimise.'</a></b><br />';
echo _MP_TOTAL.':<b> ' .  MPPrettySize($row['Data_length'] + $row['Index_length']) . '</b><br />';
echo"</td></tr></table><br />";

echo"<br /><table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr class=\"odd\"><td>";  
echo '<pre>Table: <strong>' . $myrow['Name'] . '</strong><br />';
echo _MP_LENGTH.':<b> ' . MPPrettySize($myrow['Data_length']). '</b><br />';
echo _MP_DATE_FREE.':<b> ' . MPPrettySize($myrow['Data_free']).'&nbsp;<a href="index.php?op=optimisesauv">'.$optimise.'</a></b><br />';
echo _MP_TOTAL.':<b> ' .  MPPrettySize($myrow['Data_length'] + $myrow['Index_length']) . '</b><br />';
echo"</td></tr></table></div><br />";

//Alert stokage
mp_collapsableBar('bottomtable', 'bottomtableicon');
	echo "<img onclick='toggle('bottomtable'); toggleIcon('bottomtableicon');' id='bottomtableicon' name='bottomtableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_STOCK . "</h4>";
echo "<div id='bottomtable'>";
$mp_alert = $row['Data_length'] + $row['Index_length'] + $myrow['Data_length'] + $myrow['Index_length'];
if 	($mp_alert > $xoopsModuleConfig['maxalert'])
{
       echo "<br /><table width='100%' border='0' cellspacing='1' class='outer'><tr class=\"odd\"><td>"
       ._MP_ALERT.":<b>".MPPrettySize($mp_alert)."</b> sur <b>".MPPrettySize($xoopsModuleConfig['maxalert'])."</b><br /></td></tr></table>";
       } else {
       echo "<br /><table width='100%' border='0' cellspacing='1' class='outer'><tr class=\"odd\"><td>";
       $precis = number_format(($mp_alert*100)/$xoopsModuleConfig['maxalert'], 0, ",", " ");
	  printf(_MP_POURCENT,$precis.'%');
      echo "<span style='border: 1px solid rgb(0, 0, 0); background: rgb(255, 255, 255) none repeat scroll 0%; margin-top: 2px; text-align:left; margin-right: 4px; margin-left: 4px; display: block; height: 15px; width: 90%; float: left;  overflow: hidden;'>
      <span style='background:  ".$xoopsModuleConfig['cssbtext']." none repeat scroll 0%; display: block; height: 15px; font-size: 5px; width: ".$precis."%;'></span></span></td></tr></table></div>";
}
   break;
   
   
   }
   xoops_cp_footer();
   
?>