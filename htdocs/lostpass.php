<?php
/**
* All functions for lost password generator are going through here.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/
/**
 * Form and process for sending a new password to a user
 * @package kernel 
 * @subpackage users
 */
/**
 *
 */    
$xoopsOption['pagetype'] = 'user';
include 'mainfile.php';
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : $email;
if ($email == '') {
    redirect_header('user.php',2,_US_SORRYNOTFOUND);
}

$myts =& MyTextSanitizer::getInstance();
$member_handler =& xoops_gethandler('member');
$getuser =& $member_handler->getUsers(new Criteria('email', $myts->addSlashes($email)));

if (empty($getuser)) {
    $msg = _US_SORRYNOTFOUND;
    redirect_header('user.php',2,$msg);
} else {
    $code = isset($_GET['code']) ? trim($_GET['code']) : '';
    $areyou = substr($getuser[0]->getVar("pass"), 0, 5);
    if ($code != '' && $areyou == $code) {
        $newpass = xoops_makepass();
        $salt = icms_createSalt();
        $pass = icms_encryptPass($newpass, $salt);
        $xoopsMailer =& getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass2.tpl");
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign("SITEURL", ICMS_URL."/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD", $newpass);
        $xoopsMailer->setToUsers($getuser[0]);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,ICMS_URL));
        if ( !$xoopsMailer->send() ) {
            echo $xoopsMailer->getErrors();
        }

        // Next step: add the new password to the database
        $sql = sprintf("UPDATE %s SET pass = '%s', salt = '%s' WHERE uid = '%u'", $xoopsDB->prefix("users"), $pass, $salt, intval($getuser[0]->getVar('uid')));
        if ( !$xoopsDB->queryF($sql) ) {
            include 'header.php';
            echo _US_MAILPWDNG;
            include 'footer.php';
            exit();
        }
        redirect_header("user.php", 3, sprintf(_US_PWDMAILED,$getuser[0]->getVar("uname")), false);
    // If no Code, send it
    } else {
        $xoopsMailer =& getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass1.tpl");
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign("SITEURL", ICMS_URL."/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD_LINK", ICMS_URL."/lostpass.php?email=".$email."&code=".$areyou);
        $xoopsMailer->setToUsers($getuser[0]);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,$xoopsConfig['sitename']));
        include 'header.php';
        if ( !$xoopsMailer->send() ) {
            echo $xoopsMailer->getErrors();
        }
        echo '<h4>';
        printf(_US_CONFMAIL,$getuser[0]->getVar('uname'));
        echo '</h4>';
        include 'footer.php';
    }
}
?>