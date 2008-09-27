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
   
 case "anim":
     default:
 
 
 $rep = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/swf";
 $dossier = opendir($rep);

 $i = 0;

 $debut = empty($_REQUEST['debut'])?0:intval($_REQUEST['debut']);

 $i+=$debut;

 mp_adminmenu(7, _MP_ADMENU7);

mp_collapsableBar('midletable', 'midletableicon');
	echo "<img onclick='toggle('midletable'); toggleIcon('midletableicon');' id='midletableicon' name='midletableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_HELPO . "</h4>";
echo "<div id='midletable'>";
echo "<table width='100%' border='0' cellspacing='1' class='outer'>
	  <tr class=\"odd\"><td>"._MP_WARNINGO."</td></tr></table><br /></div>";

 mp_collapsableBar('toptable', 'toptableicon');
 echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_DESCO . "</h4>";
 echo "<div id='toptable'>";


 while ($Fichier = readdir($dossier))
 {
     $files[] = $Fichier;
 }
sort($files);
$numrow = count($files);

 while ($files[$i] && ($i)<(5+$debut))
{
   if ( $files[$i] != ".." && $files[$i] != "." && $files[$i] != "" && ereg("(.swf)$",$files[$i]) )
    {
$poid=MPPrettySize(filesize($rep."/".$files[$i]));
$taille=getimagesize($rep."/".$files[$i]);

 echo ' <table width="100%" cellspacing="1" class="outer">
 <tr> 
 <td width="60%" rowspan="4">
 <embed src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/swf/'.$files[$i].'" quality="high" wmode="transparent" id="flash" name="flash" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="340" height="180"></embed>	
 </td>
 <td class="head">'._MP_NAMEO.'&nbsp; '.$files[$i].'</td>
  </tr><tr> 
 <td class="odd">'._MP_TAILLEO.'&nbsp;'.$taille[0].'*'.$taille[1].'</td>
 </tr><tr> 
 <td class="head">'._MP_POIDO.'&nbsp;'.$poid.'</td>
  </tr><tr> 
 <td class="odd">
 <form name="form1" method="post" action="anim.php">
 <INPUT TYPE="submit" value="'._MP_SUPR.'">
 <input name="op" type="hidden" value="mpdelanim">
 <input name="del_anim" type="hidden" value="'.$files[$i].'">
 </form></td></tr></table><br />';
   }
   $i++;
#  
#  
 } 

if ( $numrow > 5 ) { 
  $pagenav = new XoopsPageNav($numrow, '5', $debut, 'debut', '');
  $pagenav = $pagenav->renderNav();
} else {
  $pagenav = '';
}

 echo "<div align='right'>".$pagenav."</div></div>";
#  
 closedir($dossier);

   break;
   
  case "mpdelanim":
 $file = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/swf/".$del_anim;
 if (is_file($file)) {
 @unlink($file);
 redirect_header("anim.php", 1, _MP_OOK);
 } else {
 redirect_header("anim.php", 1, _MP_ONO);
 }
   break;    
   }
   xoops_cp_footer();
   
?>