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
if (!$xoopsUser) {
    redirect_header(ICMS_URL, 2, _NOPERM);
}
$xoopsOption['template_main'] = 'profile_changepass.html';
include ICMS_ROOT_PATH.'/header.php';
$config_handler =& xoops_gethandler('config');
$icmsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
if($icmsConfigUser['pass_level']>20){
icms_PasswordMeter();
}
if (!isset($_POST['submit'])) {
    //show change password form
    include_once ICMS_ROOT_PATH.'/class/xoopsformloader.php';
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEPASSWORD, 'form', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(_PROFILE_MA_OLDPASSWORD, 'oldpass', 10, 50), true);
    $pwd_text = new XoopsFormPassword('', 'password', 10, 255, '', false, ($icmsConfigUser['pass_level']?'password_adv':''));
    $pwd_text2 = new XoopsFormPassword('', 'vpass', 10, 255);
    $pwd_tray = new XoopsFormElementTray(_PROFILE_MA_NEWPASSWORD.'<br />'._PROFILE_MA_VERIFYPASS);
    $pwd_tray->addElement($pwd_text);
    $pwd_tray->addElement($pwd_text2);
    $form->addElement($pwd_tray);
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);

	$xoopsTpl->assign('module_home', icms_getModuleName(true));
	$xoopsTpl->assign('categoryPath', _PROFILE_MA_CHANGEPASSWORD);

}
else {
    include_once ICMS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
    $stop = checkPassword($xoopsUser->getVar('login_name'), $_POST['oldpass'], $_POST['newpass'], $_POST['vpass']);
    if ($stop != '') {
        redirect_header(ICMS_URL.'/modules/'.basename( dirname( __FILE__ ) ).'/userinfo.php?uid='.$xoopsUser->getVar('uid'), 2, $stop);
    }
    else {
        //update password
        $salt = icms_createSalt();
        $pass = icms_encryptPass($_POST['newpass'], $salt);
        $xoopsUser->setVar('pass', $pass);
        $xoopsUser->setVar('enc_type', $icmsConfigUser['enc_type']);
        $xoopsUser->setVar('pass_expired', 0);
        $xoopsUser->setVar('salt', $salt);
        // Now we are using salt so this is not required!!
        //$xoopsUser->setVar('pass', md5($_POST['newpass']));

        $member_handler =& xoops_gethandler('member');
        if ($member_handler->insertUser($xoopsUser)) {
            redirect_header(ICMS_URL.'/modules/'.basename( dirname( __FILE__ ) ).'/userinfo.php?uid='.$xoopsUser->getVar('uid'), 2, _PROFILE_MA_PASSWORDCHANGED);
        }
        redirect_header(ICMS_URL.'/modules/'.basename( dirname( __FILE__ ) ).'/userinfo.php?uid='.$xoopsUser->getVar('uid'), 2, _PROFILE_MA_ERRORDURINGSAVE);
    }
}

include 'footer.php';
?>