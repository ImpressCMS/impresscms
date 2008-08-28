<?php
/**
* Helper forms available in the ImpressCMS process
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package	core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
include_once XOOPS_ROOT_PATH."/class/xoopsform/formelement.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/form.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formlabel.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formpassword.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formbutton.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formcheckbox.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formhidden.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formfile.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formradio.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formradioyn.php";
//include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectavatar.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectcountry.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselecttimezone.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectlang.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectgroup.php";
// RMV-NOTIFY
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectuser.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselecttheme.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselectmatchoption.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formtext.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formtextarea.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formdhtmltextarea.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formelementtray.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/themeform.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/simpleform.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formtextdateselect.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formdatetime.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formhiddentoken.php";
//include_once XOOPS_ROOT_PATH."/class/xoopsform/grouppermform.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formcolorpicker.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselecteditor.php";
if(!@include_once XOOPS_ROOT_PATH."/Frameworks/captcha/formcaptcha.php") {
	@include_once XOOPS_ROOT_PATH."/class/captcha/formcaptcha.php";
}
?>