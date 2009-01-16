<?php
/**
* Installer initial site configuration page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.3.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/
/**
 *
 */ 
require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'siteinit' );
	$pageHasForm = true;
	$pageHasHelp = false;

	$vars =& $_SESSION['siteconfig'];

	$error =& $_SESSION['error'];

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$vars['adminsalt'] = $_POST['adminsalt'];
	$vars['adminname'] = $_POST['adminname'];
	$vars['adminlogin_name'] = $_POST['adminlogin_name'];
	$vars['adminmail'] = $_POST['adminmail'];
	$vars['adminpass'] = $_POST['adminpass'];
	$vars['adminpass2'] = $_POST['adminpass2'];
	$error = '';

    if (!preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $vars['adminmail'] ) ) {
    	$error = ERR_INVALID_EMAIL;
    } elseif ( @empty( $vars['adminlogin_name'] ) || @empty( $vars['adminname'] )  || @empty( $vars['adminlogin_name'] ) || @empty( $vars['adminpass'] ) || @empty( $vars['adminmail']) || empty( $vars['adminsalt']) ) {
    	$error = ERR_REQUIRED;
	} elseif ( $vars['adminpass'] != $vars['adminpass2'] ) {
    	$error = ERR_PASSWORD_MATCH;
	}
	if ( $error ) {
    	$wizard->redirectToPage( '+0' );
    	return 200;
	} else {
    	$wizard->redirectToPage( '+1' );
    	return 302;
	}
}

    ob_start();
?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>
<?php
function createSalt() {
	include_once './include/functions.php';
	return icms_createSalt(64);
}
$adminsalt = createSalt();
?>

<?php
include_once XOOPS_ROOT_PATH."/modules/system/language/".$wizard->language."/admin/preferences.php";
?>
<script type="text/javascript" src="include/passwordquality.js"></script>
<script type="text/javascript">
var qualityName1 = "<?php echo _MD_AM_PASSLEVEL1;?>";
var qualityName2 = "<?php echo _MD_AM_PASSLEVEL2;?>";
var qualityName3 = "<?php echo _MD_AM_PASSLEVEL3;?>";
var qualityName4 = "<?php echo _MD_AM_PASSLEVEL4;?>";
var qualityName5 = "<?php echo _MD_AM_PASSLEVEL5;?>";
var qualityName6 = "<?php echo _MD_AM_PASSLEVEL6;?>";

var minpass = "8";
var pass_level = "60";
</script>
<fieldset>
    <input type="hidden" name="regex"  id="regex" value="[^0-9]" />
    <input type="hidden" name="regex1" id="regex1" value="[0-9a-zA-Z]" />
    <input type="hidden" name="regex2" id="regex2" value="[^A-Z]" />
    <input type="hidden" name="regex3" id="regex3" value="([0-9])\1+" />
    <input type="hidden" name="regex4" id="regex4" value="(\W)\1+" />
    <input type="hidden" name="regex5" id="regex5" value="([A-Z])\1+" />
    
	<legend><?php echo LEGEND_ADMIN_ACCOUNT; ?></legend>
	<label for="adminname"><?php echo ADMIN_DISPLAY_LABEL; ?></label>
	<input type="text" name="adminname" id="adminname" maxlength="25" value="<?php echo htmlspecialchars( $vars['adminname'], ENT_QUOTES ); ?>" />
	<label for="adminlogin_name"><?php echo ADMIN_LOGIN_LABEL; ?></label>
	<input type="text" name="adminlogin_name" id="adminlogin_name" maxlength="25" value="<?php echo htmlspecialchars( $vars['adminlogin_name'], ENT_QUOTES ); ?>" />
	<label for="adminmail"><?php echo ADMIN_EMAIL_LABEL; ?></label>
	<input type="text" name="adminmail" id="adminmail" maxlength="255" value="<?php echo htmlspecialchars( $vars['adminmail'], ENT_QUOTES ); ?>" />
	<label for="adminpass"><?php echo ADMIN_PASS_LABEL; ?></label>
	<input type="password" name="adminpass" id="adminpass" maxlength="255" value="" />
	<script language="javascript">
<?php if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
echo 'document.getElementById("adminpass").style.minWidth = "60%";
	  document.getElementById("adminpass").style.cssFloat = "right";
	  document.getElementById("adminpass").style.styleFloat = "right";';
	   } else {
echo 'document.getElementById("adminpass").style.minWidth = "60%";
	  document.getElementById("adminpass").style.cssFloat = "left";
	  document.getElementById("adminpass").style.styleFloat = "left";';
           }
?>
	</script>
	 <script language="javascript" src="<?php echo XOOPS_URL;?>/install/include/<?php if(defined('_ADM_USE_RTL') && _ADM_USE_RTL){echo 'percent_bar_rtl.js';}else{echo 'percent_bar.js';}?>
"></script>
	<?php if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
echo '<br style="clear:right;" />';
	   } else {
echo '<br style="clear:left;" />';
           }
?>
<label for="adminpass2"><?php echo ADMIN_CONFIRMPASS_LABEL; ?></label>
	<input type="password" name="adminpass2" id="adminpass2" maxlength="255" value="" />
	<input type="hidden" name="adminsalt" id="adminsalt" maxlength="255" value="<?php echo $adminsalt; ?>" />
</fieldset>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	$error = '';
    include 'install_tpl.php';
?>