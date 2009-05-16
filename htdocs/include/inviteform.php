<?php
/**
* Handles all functions for the invitation form within ImpressCMS
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

if (!defined("ICMS_ROOT_PATH")) {
	die("ImpressCMS root path not defined");
}
include_once ICMS_ROOT_PATH."/class/xoopslists.php";
include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";

$invite_form = new XoopsThemeForm(_US_USERINVITE, "userinvite", "invite.php", "post", true);
$invite_form->addElement(new XoopsFormText(_US_EMAIL, "email", 25, 60, $myts->htmlSpecialChars($email)), true);
$invite_form->addElement(new XoopsFormHidden("op", "finish"));
$invite_form->addElement(new XoopsFormButton("", "submit", _US_SUBMIT, "submit"));

?>