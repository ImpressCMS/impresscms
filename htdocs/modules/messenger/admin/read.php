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

 xoops_cp_header();
 
  global $xoopsDB, $xoopsConfig, $_REQUEST, $xoopsModule, $myts, $xoopsUser;
header("Location: http://".XOOPS_ROOT_PATH. "/modules/".$xoopsModule->dirname()."/msgbox.php?op=sendbox&send=1");


echo '<script language="javascript"
 type="text/javascript">
<!--
window.location.replace(
 "http://'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/msgbox.php?op=sendbox&send=1");
-->
</script>';
exit;

   xoops_cp_footer();
   
?>