<?php
/**
* Import script of profile module from xoops 2.2.* until 2.3.*
*
* @copyright   The XOOPS project http://www.xoops.org/
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		modules
* @since		1.2
* @author	   Sina Asghari <pesian_stranger@users.sourceforge.net>
* @author      Taiwen Jiang <phppp@users.sourceforge.net>
* @version		$Id$
*/

function icms_module_update_profile(&$module, $oldversion = null, $dbversion = null) 
{
    GLOBAL $xoopsDB;
    
    if ($oldversion < 100) {
      
        // Drop old category table  
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_category");
        $xoopsDB->queryF($sql);
        
        // Drop old field-category link table
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_fieldcategory");
        $xoopsDB->queryF($sql);
        
        // Create new tables for new profile module
        $xoopsDB->queryFromFile(ICMS_ROOT_PATH . "/modules/" . $module->getVar('dirname', 'n') . "/sql/mysql.sql");
        
        icms_module_install_profile($module);
        $goupperm_handler =& xoops_getHandler("groupperm");
        
        $field_handler =& xoops_getModuleHandler('field', $module->getVar('dirname', 'n'));
        $skip_fields = $field_handler->getUserVars();
        $skip_fields[] = 'newemail';
        $skip_fields[] = 'pm_link';
        $sql = "SELECT * FROM `" . $xoopsDB->prefix("user_profile_field") . "` WHERE `field_name` NOT IN ('" . implode("', '", $skip_fields) . "')";
        $result = $xoopsDB->query($sql);
        $fields = array();
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $fields[] = $myrow['field_name'];
            $object =& $field_handler->create();
            $object->setVars($myrow, true);
            $object->setVar('catid', 1);
            if (!empty($myrow['field_register'])) {
                $object->setVar('step_id', 2);
            }
            if (!empty($myrow['field_options'])) {
                $object->setVar('field_options', unserialize($myrow['field_options']));
            }
            $field_handler->insert($object, true);
            
            $gperm_itemid = $object->getVar('fieldid');
            $sql = "UPDATE " . $xoopsDB->prefix("group_permission") . " SET gperm_itemid = " . $gperm_itemid .
                    "   WHERE gperm_itemid = " . $myrow['fieldid'] .
                    "       AND gperm_modid = " . $module->getVar('mid') .
                    "       AND gperm_name IN ('profile_edit', 'profile_search')";
            $xoopsDB->queryF($sql);

            $groups_visible = $goupperm_handler->getGroupIds("profile_visible", $myrow['fieldid'], $module->getVar('mid'));
            $groups_show = $goupperm_handler->getGroupIds("profile_show", $myrow['fieldid'], $module->getVar('mid'));
            foreach ($groups_visible as $ugid) {
                foreach ($groups_show as $pgid) {
                    $sql = "INSERT INTO " . $xoopsDB->prefix("profile_visibility") . 
                        " (fieldid, user_group, profile_group) " .
                        " VALUES " . 
                        " ({$gperm_itemid}, {$ugid}, {$pgid})";
                    $xoopsDB->queryF($sql);
                }
            }
            
            //profile_install_setPermissions($object->getVar('fieldid'), $module->getVar('mid'), $canedit, $visible);
            unset($object);
        }
        
        // Copy data from profile table
        foreach ($fields as $field) {
            $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("profile_profile") . "` u, `" . $xoopsDB->prefix("user_profile") . "` p SET u.{$field} = p.{$field} WHERE u.profileid=p.profileid");
        }
        
        // Drop old profile table
        $sql = "DROP TABLE " . $xoopsDB->prefix("user_profile");
        $xoopsDB->queryF($sql);
        
        // Drop old field module
        $sql = "DROP TABLE " . $xoopsDB->prefix("user_profile_field");
        $xoopsDB->queryF($sql);
        
        // Remove not used items
        $sql =  "DELETE FROM " . $xoopsDB->prefix("group_permission") . 
                "   WHERE `gperm_modid` = " . $module->getVar('mid') . " AND `gperm_name` IN ('profile_show', 'profile_visible')";
        $xoopsDB->queryF($sql);
    }
    
    $icmsDatabaseUpdater = XoopsDatabaseFactory::getDatabaseUpdater();
    $table = new IcmsDatabasetable('profile_category');
    if($table->fieldExists('cat_id')){
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("profile_category") . "` TO `" . $xoopsDB->prefix("profile_category_bak") . "`");
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("profile_field") . "` TO `" . $xoopsDB->prefix("profile_field_bak") . "`");
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("profile_visibility") . "` TO `" . $xoopsDB->prefix("profile_visibility_bak") . "`");
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("profile_profile") . "` TO `" . $xoopsDB->prefix("profile_profile_bak") . "`");
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("profile_regstep") . "` TO `" . $xoopsDB->prefix("profile_regstep_bak") . "`");
        $xoopsDB->queryFromFile(ICMS_ROOT_PATH . "/modules/" . $module->getVar('dirname', 'n') . "/sql/mysql.sql");
        
        icms_module_install_profile($module);
        
        $field_handler =& xoops_getModuleHandler('field', $module->getVar('dirname', 'n'));
        $skip_fields = $field_handler->getUserVars();
        $skip_fields[] = 'newemail';
        $skip_fields[] = 'pm_link';
        $sql = "SELECT * FROM `" . $xoopsDB->prefix("profile_field_bak") . "` WHERE `field_name` NOT IN ('" . implode("', '", $skip_fields) . "')";
        $result = $xoopsDB->query($sql);
        $fields = array();
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $fields[] = $myrow['field_name'];
            $object =& $field_handler->create();
            $object->setVars($myrow, true);
            $object->setVar('catid', 1);
            if (!empty($myrow['field_register'])) {
                $object->setVar('step_id', 2);
            }
            if (!empty($myrow['field_options'])) {
                $object->setVar('field_options', unserialize($myrow['field_options']));
            }
            $field_handler->insert($object, true);
            
            //profile_install_setPermissions($object->getVar('fieldid'), $module->getVar('mid'), $canedit, $visible);
            unset($object);
        }
        
        // Copy data from profile table
        foreach ($fields as $field) {
            $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("profile_profile") . "` u, `" . $xoopsDB->prefix("profile_profile_bak") . "` p SET u.{$field} = p.{$field} WHERE u.profileid=p.profile_id");
        }
        
        // Drop profile tables
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_category_bak");
        $xoopsDB->queryF($sql);
        // Drop profile tables
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_field_bak");
        $xoopsDB->queryF($sql);
        // Drop profile tables
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_visibility_bak");
        $xoopsDB->queryF($sql);
        // Drop profile tables
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_profile_bak");
        $xoopsDB->queryF($sql);
        // Drop profile tables
        $sql = "DROP TABLE " . $xoopsDB->prefix("profile_regstep_bak");
        $xoopsDB->queryF($sql);
    }
    return true;
}
function addField($name, $title, $description, $category, $type, $valuetype, $weight, $canedit, $options, $step_id, $length) {
    global $xoopsDB, $myts;
    if(!$myts){
        $myts =& MyTextSanitizer::getInstance();
    }
	$sql = sprintf("SELECT COUNT(*) FROM %s WHERE field_name = '%s'", $xoopsDB->prefix('profile_field'), $name);
	$result = $xoopsDB->query($sql);
	list($count) = $xoopsDB->fetchRow($result);
	if ($count == 0) {
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_field")." VALUES (0, ".$category.", '".$type."', ".$valuetype.", '".$name."', '".$myts->displayTarea($title, true)."', '".$myts->displayTarea($description, true)."', 0, $length, ".$weight.", '', 1, ".$canedit.", 1, 0, '".serialize($options)."', 1, ".$step_id.")");
	}
}
function icms_module_install_profile($module) {
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
    addField('user_sig', _PROFILE_MI_SIG_TITLE, _PROFILE_MI_SIG_DESCRIPTION, 4, 'dhtml', 1, 5, 1, array(), 0, 0);
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigAuth =& $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);
		if($xoopsConfigAuth['auth_openid'] == 1) {
    addField('openid', 'Your OpenID', 'Your OPENID login data', 4, 'textbox', 1, 1, 1, array(), 1, 255);
    addField('user_viewoid', _PROFILE_MI_VIEWEOID_TITLE, '', 3, 'yesno', 3, 1, 1, array(), 1, 1);
    	}
    return true;
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