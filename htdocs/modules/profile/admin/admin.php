<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

include 'header.php';
xoops_cp_header();

icms_adminMenu(7, "");
echo _MA_PROFILE_CONFIGEVERYTHING;
//echo "<a href='../../system/admin.php?fct=modulesadmin&op=update&module=profile'>Update</a>";

echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer' style='margin-top: 15px;'>
        <tr>
            <td class='bg3'><b>"._MA_PROFILE_ALLTESTSOK."</b></td>
        </tr>";
$a = mysql_get_server_info();
//$b = substr($a, 0, strpos($a, "-"));
$b = explode("-",$a,2);
$b=$b[0];
$c = explode(".",$b);
echo "<tr><td class='odd'>";
if ($c[0]>4 || ($c[0]==4 && $c[1]>0)) {
  echo "<img src='../images/green.gif' align='baseline'> ";
  echo "Mysql Version: <b>".$b;
} else {
  echo "<img src='../images/red.gif'> ";
  echo "Mysql Version: <b>".$b. "</b> "._MA_PROFILE_MYSQL4_OR_HIGHER." </td></tr>";
} 
if (extension_loaded('gd')) {
echo "        <tr>
            <td class='even'><img src='../images/green.gif' align='baseline'> "._MA_PROFILE_GDEXTENSIONOK."
     
     "._MA_PROFILE_MOREINFO." <a href='http://www.libgd.org/Main_Page'> Gd Library</a> </td>

        </tr>";
                
} else {
     echo "
<tr>
            <td class='even'><img src='../images/red.gif'> "._MA_PROFILE_GDEXTENSIONFALSE." "
     ._MA_PROFILE_CONFIGPHPINI."
     "._MA_PROFILE_MOREINFO." <a href='http://www.libgd.org/Main_Page'>Gd Library</a> </td>

        </tr>";}
if ( (str_replace('.', '', PHP_VERSION)) > 520 ){              
 echo "              <tr>
            <td class='odd'><img src='../images/green.gif' align='baseline'> "._MA_PROFILE_PHP5PRESENT." ". PHP_VERSION."</td>

        </tr>";} else {
     echo "
                <tr>
            <td class='odd'><img src='../images/red.gif' align='baseline'> "._MA_PROFILE_PHP5NOTPRESENT." ". PHP_VERSION."</td>

        </tr>
    
     ";}

if (!is_dir(XOOPS_ROOT_PATH."/uploads/".basename(  dirname(  dirname( __FILE__ ) ) )."/mp3/")) {
  echo "<tr>
          <td class='odd'><img src='../images/red.gif'> /uploads/".basename(  dirname(  dirname( __FILE__ ) ) )."/mp3/ "._MA_PROFILE_MP3_IS_NOT_EXISTS."</td>
        </tr>";
}elseif (!is_writable(XOOPS_ROOT_PATH."/uploads/".basename(  dirname(  dirname( __FILE__ ) ) )."/mp3/")) {
  echo "<tr>
          <td class='odd'><img src='../images/red.gif'>".XOOPS_ROOT_PATH."/uploads/".basename(  dirname(  dirname( __FILE__ ) ) )."/mp3/ "._MA_PROFILE_MP3_IS_NOT_WRITABLE."</td>
        </tr>";
}else{
  echo "<tr>
          <td class='odd'><img src='../images/green.gif' align='baseline'>".XOOPS_ROOT_PATH."/uploads/".basename(  dirname(  dirname( __FILE__ ) ) )."/mp3/ "._MA_PROFILE_MP3_EXISTS_AND_WRITABLE."</td>
        </tr>";
}

echo "<tr><td class='odd'><img src='../images/messagebox_info.gif'> ".sprintf(_MA_PROFILE_MAXBYTESPHPINI,ini_get('post_max_size'))."</td></tr>";     
if (function_exists('memory_get_usage')){
echo "<tr><td class='even'><img src='../images/messagebox_info.gif'> "._MA_PROFILE_MEMORYLIMIT." ".icms_convert_size(memory_get_usage())."</td></tr>";     
}
echo "</table>";
xoops_cp_footer();
?>