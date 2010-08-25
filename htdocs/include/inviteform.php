<?php
/**
 * Handles all functions for the invitation form within ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	1.1
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

if (!defined("ICMS_ROOT_PATH")) {
	die("ImpressCMS root path not defined");
}
include_once ICMS_ROOT_PATH."/class/xoopslists.php";
include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";

$invite_form = new icms_form_Theme(_US_USERINVITE, "userinvite", "invite.php", "post", true);
$invite_form->addElement(new icms_form_elements_Text(_US_EMAIL, "email", 25, 60, $myts->htmlSpecialChars($email)), true);
$invite_form->addElement(new icms_form_elements_Captcha(_SECURITYIMAGE_GETCODE, "scode"), true);
$invite_form->addElement(new icms_form_elements_Hidden("op", "finish"));
$invite_form->addElement(new icms_form_elements_Button("", "submit", _US_SUBMIT, "submit"));

?>