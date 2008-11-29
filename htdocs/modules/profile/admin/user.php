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

smart_adminMenu(1, "");
//$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "edit" : 'list');
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
if($op == "editordelete") {
    $op = isset($_REQUEST['delete'])?"delete":"edit";
}
$handler =& xoops_gethandler('member');
/* @var $handler XoopsMemberHandler */

switch($op) {
    default:
    case "list":
        include_once(ICMS_ROOT_PATH."/class/xoopsformloader.php");
        $form = new XoopsThemeForm(_PROFILE_AM_EDITUSER, 'form', 'user.php');
        $form->addElement(new XoopsFormSelectUser(_PROFILE_AM_SELECTUSER, 'id'));
        $form->addElement(new XoopsFormHidden('op', 'editordelete'));
        $button_tray = new XoopsFormElementTray('');
        $button_tray->addElement(new XoopsFormButton('', 'edit', _EDIT, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit'));
        $form->addElement($button_tray);
        //$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->display();

    case "new":
        if (@!include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/".$xoopsConfig['language']."/main.php")) {
            include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/english/main.php");
        }
        include_once('../include/forms.php');
        $obj =& $handler->createUser();
        $obj->setGroups(array(ICMS_GROUP_USERS));
        $form =& getUserForm($obj, false, false, true);
        $form->display();
        break;

    case "edit":
        if (@!include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/".$xoopsConfig['language']."/main.php")) {
            include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/english/main.php");
        }
        $obj =& $handler->getUser($_REQUEST['id']);
        if (in_array(ICMS_GROUP_ADMIN, $obj->getGroups()) && !in_array(ICMS_GROUP_ADMIN, $xoopsUser->getGroups())) {
            // If not webmaster trying to edit a webmaster - disallow
            redirect_header("user.php", 3, _PROFILE_AM_CANNOTEDITWEBMASTERS);
        }
        include_once('../include/forms.php');
        $form =& getUserForm($obj, false, false, true);
        $form->display();
        break;

    case "save":
        if (@!include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/".$xoopsConfig['language']."/main.php")) {
            include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/english/main.php");
        }
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('admin.php',3,_PROFILE_MA_NOEDITRIGHT."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit;
        }
        $uid = 0;
        if (!empty($_POST['uid'])) {
            $uid = intval($_POST['uid']);
            $user =& $handler->getUser($uid);
        }
        else {
            $user =& $handler->createUser();
            $user->setVar('user_regdate', time());
            $user->setVar('level', 1);
        }
        $errors = array();
        $myts =& MyTextSanitizer::getInstance();
        $user->setVar('email', trim($_POST['email']));
        if ($user->getVar('uid') != $xoopsUser->getVar('uid')) {
            $password = '';
            if (!empty($_POST['password'])) {
                $password = $myts->stripSlashesGPC(trim($_POST['password']));
            }
            if ($password != '') {
                if (strlen($password) < $xoopsModuleConfig['minpass']) {
                    $errors[] = sprintf(_PROFILE_MA_PWDTOOSHORT,$xoopsModuleConfig['minpass']);
                }
                $vpass = '';
                if (!empty($_POST['vpass'])) {
                    $vpass = $myts->stripSlashesGPC(trim($_POST['vpass']));
                }
                if ($password != $vpass) {
                    $errors[] = _PROFILE_MA_PASSNOTSAME;
                }
                $user->setVar('pass', md5($password));
            }
            elseif ($user->isNew()) {
                $errors[] = _PROFILE_MA_NOPASSWORD;
            }
            $user->setVar('level', intval($_POST['level']));
        }
        $user->setVar('uname', $_POST['uname']);
        include_once('../include/functions.php');
        $stop = userCheck($user);
        if ($stop != "") {
            $errors[] = $stop;
        }

        // Dynamic fields
        $profile_handler =& icms_getmodulehandler( 'profile', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
        // Get fields
        $fields =& $profile_handler->loadFields();
        // Get ids of fields that can be edited
        $gperm_handler =& xoops_gethandler('groupperm');
        $editable_fields =& $gperm_handler->getItemIds('profile_edit', $xoopsUser->getGroups(), $xoopsModule->getVar('mid'));

        $profile = $profile_handler->get($user->getVar('uid'));

        foreach (array_keys($fields) as $i) {
            $fieldname = $fields[$i]->getVar('field_name');
            if (in_array($fields[$i]->getVar('fieldid'), $editable_fields) && ($fields[$i]->getvar('field_type') == "image" || isset($_REQUEST[$fieldname]))) {
                if (in_array($fieldname, $profile_handler->getUserVars())) {
                    $value = $fields[$i]->getValueForSave($_REQUEST[$fieldname], $user->getVar($fieldname, 'n'));
                    $user->setVar($fieldname, $value);
                }
                else {
                    $value = $fields[$i]->getValueForSave((isset($_REQUEST[$fieldname]) ? $_REQUEST[$fieldname] : ""), $profile->getVar($fieldname, 'n'));
                    $profile->setVar($fieldname, $value);
                }
            }
        }

        $new_groups = isset($_POST['groups']) ? $_POST['groups'] : array();
        //$user->setGroups($new_groups);

        if (count($errors) == 0) {
            if ($handler->insertUser($user)) {
                $profile->setVar('profileid', $user->getVar('uid'));
                $profile_handler->insert($profile);

                include_once(ICMS_ROOT_PATH."/modules/system/constants.php");
                if ($gperm_handler->checkRight("system_admin", XOOPS_SYSTEM_GROUP, $xoopsUser->getGroups(), 1)) {
                    //Update group memberships
                    $cur_groups = $user->getGroups();

                    $added_groups = array_diff($new_groups, $cur_groups);
                    $removed_groups = array_diff($cur_groups, $new_groups);

                    if (count($added_groups) > 0) {
                        foreach ($added_groups as $groupid) {
                            $handler->addUserToGroup($groupid, $user->getVar('uid'));
                        }
                    }
                    if (count($removed_groups) > 0) {
                        foreach ($removed_groups as $groupid) {
                            $handler->removeUsersFromGroup($groupid, array($user->getVar('uid')));
                        }
                    }
                }
                if ($user->isNew()) {
                    redirect_header('user.php', 2, _PROFILE_AM_USERCREATED, false);
                }
                else {
                    redirect_header('user.php', 2, _PROFILE_MA_PROFUPDATED, false);
                }
            }
        }
        else {
            foreach ($errors as $err) {
                $user->setErrors($err);
            }
        }
        $user->setGroups($new_groups);
        include_once('../include/forms.php');
        echo $user->getHtmlErrors();
        $form =& getUserForm($user, $profile);
        $form->display();
        break;

    case "delete":
        if ($_REQUEST['id'] == $xoopsUser->getVar('uid')) {
            redirect_header('user.php', 2, _PROFILE_AM_CANNOTDELETESELF);
        }
        $obj =& $handler->getUser($_REQUEST['id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('user.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()), false);
            }
            $profile_handler = icms_getmodulehandler( 'profile', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
            $profile = $profile_handler->get($obj->getVar('uid'));
            if ($profile->isNew() || $profile_handler->delete($profile)) {
                if ($handler->deleteUser($obj)) {

                    redirect_header('user.php', 3, sprintf(_PROFILE_AM_DELETEDSUCCESS, $obj->getVar('uname')." (".$obj->getVar('email').")"), false);
                }
                else {
                    echo $obj->getHtmlErrors();
                }
            }
            else {
                echo $profile->getHtmlErrors();
            }

        }
        else {
            xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('uname')." (".$obj->getVar('email').")"));
        }
        break;
}

xoops_cp_footer();
?>