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


include "header.php";
require_once XOOPS_ROOT_PATH.'/modules/messenger/include/functions.php';
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
$debut = isset($_GET['debut']) ? trim($_GET['debut']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : $type;

    xoops_header();
echo '<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style>';

if ($xoopsUser) {
 	
echo '<table border=0 class="outer" cellspacing="1" cellepadding="0" align="center">';

$rep = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/swf";
$dossier = opendir($rep);

$i = 0;

if ( isset($debut) ) {
	@$debut = intval($debut);
} else {
	@$debut = 0;
} 

 $i+=$debut;
#  
 while ($Fichier = readdir($dossier))
 {
 $ext = strtolower(substr($Fichier, strrpos($Fichier, '.') + 1));
   if($ext=="swf") {
     $files[] = $Fichier;
	 }
 }

sort($files);
$numrow = count($files);

  echo '
 <th class="head" align="center"><b>'._MP_OEIL.'</b></th>
 <tr>';
 while ($files[$i] && ($i)<(5+$debut))
{
 if ( $files[$i] != ".." && $files[$i] != "." && $files[$i] != "" && ereg("(.swf)$",$files[$i]) )
    {
$poid=MPPrettySize(filesize($rep."/".$files[$i]));
$taille=getimagesize($rep."/".$files[$i]);
$name = str_replace('.', '', $files[$i]); 


 echo ' <table class="outer" style="background:#fff;" width="100%" border="0">
  <tr> 
    <td width="60%" rowspan="4">
<div id="z'.$name.'">
<embed src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/swf/'.$files[$i].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="340" height="180" id="f'.$name.'"></embed><NOEMBED>'._MP_NOOEIL.'</NOEMBED></div>
</td>
    <td class="head">'._MP_NAMEO.'&nbsp; '.$files[$i].'</td>
  </tr>
  <tr> 
    <td class="odd">'._MP_TAILLEO.'&nbsp;'.$taille[0].'*'.$taille[1].'</td>
  </tr>
  <tr> 
    <td class="head">'._MP_POIDO.'&nbsp;'.$poid.'</td>
  </tr>
  <tr> 
    <td class="odd">';

echo "<INPUT TYPE=\"BUTTON\" value=\""._MP_ADDO."\" onclick=\"opener.document.getElementById('anim_msg').value='".$files[$i]."'; window.close();\">
<br />
<input type=\"submit\" name=\"Submit2\" value=\""._MP_PLAY."\" onClick=\"document.getElementById('z".$name."').style.display='block';\"> 
<input type=\"submit\" name=\"Submit2\" value=\""._MP_STOP."\" onclick=\"document.getElementById('z".$name."').style.visibility='hidden';f".$name.".TStopPlay('_level0');\"> 

<script type=\"text/javascript\">
document.getElementById(\"z".$name."\").style.display='none';
</script>
</td>
  </tr>
</table><br />";
   }

   $i++;
#  
#  
 } 
  
   echo "</td>
</tr>";

if ( $numrow > 5 ) { 
  $pagenav = new XoopsPageNav($numrow, '5', $debut, 'debut');
  $pagenav = $pagenav->renderNav();
} else {
  $pagenav = '';
}

 echo "</tr></table>
 <div align='right'>".$pagenav."</div>";
#  
 closedir($dossier);


        echo '<div style="text-align:center;"><input class="formButton" value="'._CLOSE.'" type="button" onclick="javascript:  opener=self; window.close();" /></div>';

} else {
    echo _PM_SORRY."<br /><br /><a href='".XOOPS_URL."/register.php'>"._PM_REGISTERNOW."</a>.";
}
    xoops_footer();

?>
