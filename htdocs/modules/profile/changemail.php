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

include '../../mainfile.php';
if (!$xoopsUser || $xoopsModuleConfig['allow_chgmail'] != 1) {
    redirect_header(ICMS_URL."/modules/".basename( dirname( __FILE__ ) )."/", 2, _NOPERM);
}
include ICMS_ROOT_PATH."/header.php";

if (!isset($_POST['submit']) && !isset($_REQUEST['oldmail'])) {
    //show change password form
    include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEMAIL, 'form', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormText(_PROFILE_MA_NEWMAIL, 'newmail', 15, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->display();
}
else {
    //compute unique key
    $key = md5(substr($xoopsUser->getVar("pass"), 0, 5));
    if (!isset($_REQUEST['oldmail'])) {
        if (!checkEmail($_POST['newmail'])) {
            redirect_header('changemail.php', 2, _PROFILE_MA_INVALIDMAIL);
        }
        else {
            //send email to new email address with key
            $xoopsMailer =& getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setTemplateDir(ICMS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/language/".$xoopsConfig['language']."/mail_template");
            $xoopsMailer->setTemplate('changemail.tpl');
            $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
            $xoopsMailer->assign("X_UNAME", $xoopsUser->getVar('uname'));
            $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
            $xoopsMailer->assign("SITEURL", ICMS_URL."/");
            $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
            $xoopsMailer->assign("NEWEMAIL_LINK", ICMS_URL."/modules/".basename( dirname( __FILE__ ) )."/changemail.php?code=".$key."&oldmail=".$xoopsUser->getVar('email'));
            $xoopsMailer->assign("NEWEMAIL", $_POST['newmail']);
            $xoopsMailer->setToEmails($_POST['newmail']);
            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject(sprintf(_PROFILE_MA_NEWEMAILREQ,$xoopsConfig['sitename']));
            if ($xoopsMailer->send()) {
                //set proposed email as the user's newemail
               	$profile_handler = icms_getmodulehandler( 'profile', basename( dirname( __FILE__ ) ), 'profile' );
               	$profile = $profile_handler->get($xoopsUser->getVar('uid'));
                $profile->setVar('newemail', $_POST['newmail']);
                if ($profile_handler->insert($profile)) {
                    //redirect with success
                    redirect_header(ICMS_URL.'/', 2, _PROFILE_MA_NEWMAILMSGSENT);
                }
            }
            else {
                //relevant error messages
                echo $xoopsMailer->getErrors();
            }
        }
    }
    else {
        //check unique key
        $code = isset($_GET['code']) ? $_GET['code'] : redirect_header(ICMS_URL, 2, _PROFILE_MA_CONFCODEMISSING);
        if ($code == $key) {
            //change email address to the proposed on
            $profile_handler = icms_getmodulehandler( 'profile', basename( dirname( __FILE__ ) ), 'profile' );
           	$profile = $profile_handler->get($xoopsUser->getVar('uid'));
            $xoopsUser->setVar('email', $profile->getVar('newemail'));
            //update user data
            $member_handler =& xoops_gethandler('member');
            if ($member_handler->insertUser($xoopsUser, true)) {
                //redirect with success
                redirect_header(ICMS_URL."/modules/".basename( dirname( __FILE__ ) )."/", 2, _PROFILE_MA_EMAILCHANGED);
            }
            else {
                //error in update process
                echo implode('<br />', $xoopsUser->getErrors());
            }
        }
        else {
            //wrong key
            $eh =& XoopsErrorHandler::getInstance();
		    $eh->errorPage(1, $xoopsModule->getVar('mid'));
        }
    }
}

include ICMS_ROOT_PATH."/footer.php";
?>