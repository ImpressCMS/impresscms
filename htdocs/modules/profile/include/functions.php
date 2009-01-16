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

/**
* Check a user's uname, email, password and password verification
*
* @param object $user {@link XoopsUser} to check
*
* @return string
*/
function userCheck($user)
{
    global $xoopsModuleConfig;
    $stop = '';
    if (!checkEmail($user->getVar('email'))) {
        $stop .= _PROFILE_MA_INVALIDMAIL;
    }
    foreach ($xoopsModuleConfig['bad_emails'] as $be) {
        if (!empty($be) && preg_match("/".$be."/i", $user->getVar('email'))) {
            $stop .= _PROFILE_MA_INVALIDMAIL;
            break;
        }
    }
    if (strrpos($user->getVar('email'),' ') > 0) {
        $stop .= _PROFILE_MA_EMAILNOSPACES.'<br />';
    }
    switch ($xoopsModuleConfig['uname_test_level']) {
        case 0:
        // strict
        $restriction = '/[^a-zA-Z0-9\_\-]/';
        break;
        case 1:
        // medium
        $restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
        break;
        case 2:
        // loose
        $restriction = '/[\000-\040]/';
        break;
    }
    if (strlen($user->getVar('uname')) > $xoopsModuleConfig['max_uname']) {
        $stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOLONG, $xoopsModuleConfig['max_uname'])."<br />";
    }
    if (strlen($user->getVar('uname')) < $xoopsModuleConfig['min_uname']) {
        $stop .= sprintf(_PROFILE_MA_DISPLAYNAMETOOSHORT, $xoopsModuleConfig['min_uname'])."<br />";
    }
    foreach ($xoopsModuleConfig['bad_unames'] as $bu) {
	    if(empty($bu) ||$user->isAdmin()) continue;
        if (preg_match("/".$bu."/i", $user->getVar('uname'))) {
            $stop .= _PROFILE_MA_DISPLAYNAMERESERVED."<br />";
            break;
        }
    }

    $member_handler =& xoops_gethandler('member');
    $display_criteria = new Criteria('uname', $user->getVar('uname'));
    if ($user->getVar('uid') > 0) {
        //existing user, so let's keep the user's own row out of this
        $display_criteria = new CriteriaCompo($display_criteria);

        $useriddisplay_criteria = new Criteria('uid', $user->getVar('uid'), '!=');

        $display_criteria->add($useriddisplay_criteria);
    }
    $display_count = $member_handler->getUserCount($display_criteria);
    unset($display_criteria);
    if ($display_count > 0) {
        $stop .= _PROFILE_MA_DISPLAYNAMETAKEN."<br />";
    }
    if ( $user->getVar('email')) {
        $count_criteria = new Criteria('email', $user->getVar('email'));
        if ($user->getVar('uid') > 0) {
            //existing user, so let's keep the user's own row out of this
            $count_criteria = new CriteriaCompo($count_criteria);
            $count_criteria->add(new Criteria('uid', $user->getVar('uid'), '!='));
        }
        $count = $member_handler->getUserCount($count_criteria);
        unset($count_criteria);
        if ( $count > 0 ) {
            $stop .= _PROFILE_MA_EMAILTAKEN."<br />";
        }
    }

    return $stop;
}

/**
* Check password - used when changing password
*
* @param string $uname username of the user changing password
* @param string $oldpass old password
* @param string $newpass new password
* @param string $vpass verification of new password (must be the same as $newpass)
*
* @return string
**/
function checkPassword($uname, $oldpass, $newpass, $vpass) {
    $stop = "";
    $uname = trim($uname);
    if ($oldpass == "") {
        $stop .= _PROFILE_MA_ENTERPWD;
    }
    else {
        //check if $oldpass is correct
        $member_handler =& xoops_gethandler('member');
        if (!$member_handler->loginUser(addslashes($uname), addslashes($oldpass))) {
            $stop .= _PROFILE_MA_WRONGPASSWORD;
        }
    }
    if ( $newpass == '' || !$vpass || $vpass == '' ) {
        $stop .= _PROFILE_MA_ENTERPWD.'<br />';
    }
    global $xoopsModuleConfig;
    if ( ($newpass != $vpass) ) {
        $stop .= _PROFILE_MA_PASSNOTSAME.'<br />';
    } elseif ( ($newpass != '') && (strlen($newpass) < $xoopsModuleConfig['minpass']) ) {
        $stop .= sprintf(_PROFILE_MA_PWDTOOSHORT,$xoopsModuleConfig['minpass'])."<br />";
    }
    return $stop;
}
?>