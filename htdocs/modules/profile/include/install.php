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

function xoops_module_install_profile($module) {
    // Create registration steps
    addStep('Basic information', '', 1, 1);
    addStep('Complementary information', '', 2, 0);

    // Create categories
    addCategory('Personal', 1);
    addCategory('Messaging', 2);
    addCategory('Settings', 3);
    addCategory('Community', 4);


    // Add user fields
    include_once ICMS_ROOT_PATH . "/language/" . $GLOBALS['xoopsConfig']['language'] . '/notification.php';
    include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
    $umode_options = array('nest'=>_NESTED, 'flat'=>_FLAT, 'thread'=>_THREADED);
    $uorder_options = array(0 => _OLDESTFIRST,
                            1 => _NEWESTFIRST);
    $notify_mode_options = array(XOOPS_NOTIFICATION_MODE_SENDALWAYS=>_NOT_MODE_SENDALWAYS,
                                 XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE=>_NOT_MODE_SENDONCE,
                                 XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT=>_NOT_MODE_SENDONCEPERLOGIN);
    $notify_method_options = array( XOOPS_NOTIFICATION_METHOD_DISABLE=>_NOT_METHOD_DISABLE,
                                    XOOPS_NOTIFICATION_METHOD_PM=>_NOT_METHOD_PM,
                                    XOOPS_NOTIFICATION_METHOD_EMAIL=>_NOT_METHOD_EMAIL);
    addField('user_icq', _PROFILE_MI_ICQ_TITLE, _PROFILE_MI_ICQ_DESCRIPTION, 1, 'textbox', 1, 1, 1, array(), 2, 255);
    addField('user_aim', _PROFILE_MI_AIM_TITLE, _PROFILE_MI_AIM_DESCRIPTION, 1, 'textbox', 1, 2, 1, array(), 2, 255);
    addField('user_yim', _PROFILE_MI_YIM_TITLE, _PROFILE_MI_YIM_DESCRIPTION, 1, 'textbox', 1, 3, 1, array(), 2, 255);
    addField('user_msnm', _PROFILE_MI_MSN_TITLE, _PROFILE_MI_MSN_DESCRIPTION, 1, 'textbox', 1, 4, 1, array(), 2, 255);

    addField('name', 'Name', '', 2, 'textbox', 1, 1, 1, array(), 1, 255);
    addField('user_from', _PROFILE_MI_FROM_TITLE, _PROFILE_MI_FROM_DESCRIPTION, 2, 'textbox', 1, 2, 1, array(), 2, 255);
    addField('timezone_offset', 'Timezone', '', 2, 'timezone', 1, 3, 1, array(), 2, 0);
    addField('user_occ', _PROFILE_MI_OCCUPATION_TITLE, _PROFILE_MI_OCCUPATION_DESCRIPTION, 2, 'textbox', 1, 4, 1, array(), 2, 255);
    addField('user_intrest', _PROFILE_MI_INTEREST_TITLE, _PROFILE_MI_INTEREST_DESCRIPTION, 2, 'textbox', 1, 5, 1, array(), 2, 255);
    addField('bio', _PROFILE_MI_BIO_TITLE, _PROFILE_MI_BIO_DESCRIPTION, 2, 'textarea', 2, 6, 1, array(), 2, 0);
    addField('user_regdate', 'Member since', '', 2, 'datetime', 3, 7, 0, array(), 0, 10);

    addField('user_viewemail', _PROFILE_MI_VIEWEMAIL_TITLE, '', 3, 'yesno', 3, 1, 1, array(), 1, 1);
    addField('attachsig', 'Attach signature', '', 3, 'yesno', 3, 2, 1, array(), 0, 1);
    addField('user_mailok', 'Receive mails from admins', 'Can administrators contact you periodically via email', 3, 'yesno', 3, 3, 1, array(), 1, 1);
    addField('theme', 'Theme', '', 3, 'theme', 1, 4, 1, array(), 0, 0);
    addField('umode', 'Comments display mode', '', 3, 'select', 3, 5, 1, $umode_options, 0, 0);
    addField('uorder', 'Comments sort order', '', 3, 'select', 3, 6, 1, $uorder_options, 0, 0);
    addField('notify_mode', 'Notification mode', '', 3, 'select', 3, 7, 1, $notify_mode_options, 0, 0);
    addField('notify_method', 'Notification method', '', 3, 'select', 3, 8, 1, $notify_method_options, 0, 0);

    addField('url', _PROFILE_MI_URL_TITLE, _PROFILE_MI_URL_DESCRIPTION, 4, 'textbox', 1, 1, 1, array(), 1, 255);
    addField('posts', 'Posts', '', 4, 'textbox', 3, 2, 1, array(), 0, 255);
    addField('rank', 'Rank', '', 4, 'rank', 3, 3, 1, array(), 0, 0);
    addField('last_login', 'Last login', '', 4, 'datetime', 3, 4, 0, array(), 0, 10);
    addField('user_sig', _PROFILE_MI_SIG_TITLE, _PROFILE_MI_SIG_DESCRIPTION, 4, 'textarea', 1, 5, 1, array(), 0, 0);

    return true;
}

function addField($name, $title, $description, $category, $type, $valuetype, $weight, $canedit, $options, $step_id, $length) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_field")." VALUES (0, ".$category.", '".$type."', ".$valuetype.", '".$name."', '".$title."', '".$description."', 0, $length, ".$weight.", '', 1, ".$canedit.", 1, 0, '".serialize($options)."', ".$step_id.")");
}

function addCategory($name, $weight) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_category")." VALUES (0, '".$name."', '', ".$weight.")");
}

function addStep($name, $desc, $order, $save) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_regstep")." VALUES (0, '".$name."', '".$desc."', ".$order.", ".$save.")");
}
?>