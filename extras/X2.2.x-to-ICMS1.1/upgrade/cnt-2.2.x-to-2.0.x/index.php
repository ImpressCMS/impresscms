<?php
/**
 * Upgrader from 2.2.* to 2.0.x
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       1.1
 * @author		Sina Asghari <pesian_stranger@users.sourceforge.net>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

class upgrade_220
{
	var $usedFiles = array ();
    var $tasks = array('config', 'profile', 'block', 'pm');
	var $updater;

	function __construct() {
		$this->updater = XoopsDatabaseFactory::getDatabaseUpdater();
	}

    function isApplied()
    {
        if (!isset($_SESSION[__CLASS__]) || !is_array($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        foreach ($this->tasks as $task) {
            if (!in_array($task, $_SESSION[__CLASS__])) {
                if (!$res = $this->{"check_{$task}"}()) {
                    $_SESSION[__CLASS__][] = $task;
                }
            }
        }
        return empty($_SESSION[__CLASS__]) ? true : false;
    }
    function apply()
    {
        $tasks = $_SESSION[__CLASS__];
        foreach ($tasks as $task) {
            $res = $this->{"apply_{$task}"}();
            if (!$res) return false;
            array_shift($_SESSION[__CLASS__]);
        }
        return true;
    }
    
    /**
     * Check if config category already removed
     *
     */
    function check_config()
    {
        $sql = "SHOW COLUMNS FROM `" . $GLOBALS['xoopsDB']->prefix('configcategory') . "` LIKE 'confcat_modid'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) return true;
        if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) return false;
        return true;
    }

    /**
     * Check if user profile table already converted
     *
     */
    function check_profile()
    {
        $sql = "SHOW TABLES LIKE '" . $GLOBALS['xoopsDB']->prefix("user_profile") . "'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) return true;
        if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) return false;
        return true;
    }

    /**
     * Check if block table already converted
     *
     */
    function check_block()
    {
        $sql = "SHOW TABLES LIKE '" . $GLOBALS['xoopsDB']->prefix("block_instance") . "'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) return true;
        if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) return false;
        return true;
    }

    /**
     * Check if private message table already converted
     *
     */
    function check_pm()
    {
        $sql = "SHOW COLUMNS FROM " . $GLOBALS['xoopsDB']->prefix("priv_msgs");
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) return false;
        if ($GLOBALS['xoopsDB']->getRowsNum($result) > 8) return false;
        return true;
    }
    
    function apply_config()
    {
        $xoopsDB = $GLOBALS['xoopsDB'];
        
        $result = true;
        
        //Set core configuration back to zero for system module
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = 0 WHERE conf_modid = 1");
        
        //Change debug modes so there can only be one active at any one time
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_formtype = 'select', conf_valuetype = 'int' WHERE conf_name = 'debug_mode'");
        
        //Reset category ID for non-system configs
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_catid = 0 WHERE conf_modid > 1 AND conf_catid > 0");
        
        // remove admin theme configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='theme_set_admin'");

        //Drop non-System config categories
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('configcategory') . "` WHERE confcat_modid > 1");

        //Drop category information fields added in 2.2
        $xoopsDB->queryF("ALTER TABLE `" . $xoopsDB->prefix("configcategory") . "` DROP `confcat_nameid`, DROP `confcat_description`, DROP `confcat_modid`");
        
        // Re-add user configuration category
        $xoopsDB->queryF("INSERT INTO `" . $xoopsDB->prefix("configcategory") . "` (confcat_id, confcat_name, confcat_order) VALUES (2, '_MD_AM_USERSETTINGS', 2)");
    
        //Rebuild user configuration items
        
        //Change change config mods
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_MINPASS', conf_desc = '_MD_AM_MINPASSDSC' WHERE conf_name = 'minpass'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_MINUNAME', conf_desc = '_MD_AM_MINUNAMEDSC' WHERE conf_name = 'min_uname'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_NEWUNOTIFY', conf_desc = '_MD_AM_NEWUNOTIFYDSC' WHERE conf_name = 'new_user_notify'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_NOTIFYTO', conf_desc = '_MD_AM_NOTIFYTODSC' WHERE conf_name = 'new_user_notify_group'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_ACTVTYPE', conf_desc = '_MD_AM_ACTVTYPEDSC' WHERE conf_name = 'activation_type'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_ACTVGROUP', conf_desc = '_MD_AM_ACTVGROUPDSC' WHERE conf_name = 'activation_group'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_UNAMELVL', conf_desc = '_MD_AM_UNAMELVLDSC' WHERE conf_name = 'uname_test_level'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_AVATARALLOW', conf_desc = '_MD_AM_AVATARALWDSC' WHERE conf_name = 'avatar_allow_upload'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_AVATARW', conf_desc = '_MD_AM_AVATARWDSC' WHERE conf_name = 'avatar_width'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_AVATARH', conf_desc = '_MD_AM_AVATARHDSC' WHERE conf_name = 'avatar_height'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_AVATARMAX', conf_desc = '_MD_AM_AVATARMAXDSC' WHERE conf_name = 'avatar_maxsize'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_SELFDELETE', conf_desc = '_MD_AM_SELFDELETEDSC' WHERE conf_name = 'self_delete'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_BADUNAMES', conf_desc = '_MD_AM_BADUNAMESDSC' WHERE conf_name = 'bad_unames'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_BADEMAILS', conf_desc = '_MD_AM_BADEMAILSDSC' WHERE conf_name = 'bad_emails'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_MAXUNAME', conf_desc = '_MD_AM_MAXUNAMEDSC' WHERE conf_name = 'max_uname'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_AVATARMP', conf_desc = '_MD_AM_AVATARMPDSC' WHERE conf_name = 'avatar_minposts'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_ALLWCHGMAIL', conf_desc = '_MD_AM_ALLWCHGMAILDSC' WHERE conf_name = 'allow_chgmail'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_DSPDSCLMR', conf_desc = '_MD_AM_DSPDSCLMRDSC' WHERE conf_name = 'display_disclaimer'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_REGDSCLMR', conf_desc = '_MD_AM_REGDSCLMRDSC' WHERE conf_name = 'disclaimer'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_modid = '0', conf_catid = '2', conf_title = '_MD_AM_ALLOWREG', conf_desc = '_MD_AM_ALLOWREGDSC' WHERE conf_name = 'allow_register'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("config") . "` SET reg_disclaimer=disclaimer");
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("config") . " DROP disclaimer");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("config") . "` SET reg_dispdsclmr=display_disclaimer");
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("config") . " DROP display_disclaimer");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("config") . "` SET maxuname=max_uname");
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("config") . " DROP max_uname");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("config") . "` SET minuname=min_uname");
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("config") . " DROP min_uname");

        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('config') . "` SET conf_value = 'iTheme' WHERE conf_name = 'theme_set'");

        // remove profile_search configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='profile_search'");

        // remove prunemessage configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='prunemessage'");

        // remove perpage configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='perpage'");

        // remove max_save configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='max_save'");

        // remove prunesubject configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='prunesubject'");

        // remove allowed_groups configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='allowed_groups'");

        // remove ldap_uid_attr configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='ldap_uid_attr'");

        // remove ldap_uid_asdn configuration item
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='ldap_uid_asdn'");

        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_USERACTV' WHERE confop_name = '_PROFILE_MI_USERACTV'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_AUTOACTV' WHERE confop_name = '_PROFILE_MI_AUTOACTV'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_ADMINACTV' WHERE confop_name = '_PROFILE_MI_ADMINACTV'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_STRICT' WHERE confop_name = '_PROFILE_MI_STRICT'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_MEDIUM' WHERE confop_name = '_PROFILE_MI_MEDIUM'");
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix('configoption') . "` SET confop_name = '_MD_AM_LIGHT' WHERE confop_name = '_PROFILE_MI_LIGHT'");
        
        return $result;
    }

    function apply_profile()
    {
        $xoopsDB = $GLOBALS['xoopsDB'];
        // Restore users table
        $xoopsDB->queryF(
            "ALTER TABLE `" . $xoopsDB->prefix("users") . "`
              ADD url varchar(100) NOT NULL default '',
              ADD user_regdate int(10) unsigned NOT NULL default '0',
              ADD user_icq varchar(15) NOT NULL default '',
              ADD user_from varchar(100) NOT NULL default '',
              ADD user_sig tinytext NOT NULL,
              ADD user_viewemail tinyint(1) unsigned NOT NULL default '0',
              ADD actkey varchar(8) NOT NULL default '',
              ADD user_aim varchar(18) NOT NULL default '',
              ADD user_yim varchar(25) NOT NULL default '',
              ADD user_msnm varchar(100) NOT NULL default '',
              ADD posts mediumint(8) unsigned NOT NULL default '0',
              ADD attachsig tinyint(1) unsigned NOT NULL default '0',
              ADD theme varchar(100) NOT NULL default '',
              ADD timezone_offset float(3,1) NOT NULL default '0.0',
              ADD last_login int(10) unsigned NOT NULL default '0',
              ADD umode varchar(10) NOT NULL default '',
              ADD uorder tinyint(1) unsigned NOT NULL default '0',
              ADD notify_method tinyint(1) NOT NULL default '1',
              ADD notify_mode tinyint(1) NOT NULL default '0',
              ADD user_occ varchar(100) NOT NULL default '',
              ADD bio tinytext NOT NULL,
              ADD user_intrest varchar(150) NOT NULL default '',
              ADD user_mailok tinyint(1) unsigned NOT NULL default '1'
              "
              );
        
        // Copy data from profile table
        $profile_fields = array("url", "user_regdate", "user_icq", "user_from", "user_sig", "user_viewemail", "actkey", "user_aim", "user_yim", "user_msnm", "posts", "attachsig", "theme", "timezone_offset", "last_login", "umode", "uorder", "notify_method", "notify_mode", "user_occ", "bio", "user_intrest", "user_mailok");
        foreach ($profile_fields as $field) {
            $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("users") . "` u, `" . $xoopsDB->prefix("user_profile") . "` p SET u.{$field} = p.{$field} WHERE u.uid=p.profileid");
        }
        //Set loginname as uname
        $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("users") . "` SET uname=loginname");
        
        //Drop loginname
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("users") . " DROP loginname");
        
        // Drop user profile table
        $xoopsDB->queryF("DROP TABLE " . $xoopsDB->prefix("user_profile"));
        
        // Drop user profile field table
        $xoopsDB->queryF("DROP TABLE " . $xoopsDB->prefix("user_profile_field"));

        // Drop user profile_fieldcategory table
        $xoopsDB->queryF("DROP TABLE " . $xoopsDB->prefix("profile_fieldcategory"));
        
        // Drop user profile_category field table
        $xoopsDB->queryF("DROP TABLE " . $xoopsDB->prefix("profile_category"));

        // remove pm module
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('modules') . "` WHERE dirname='pm'");

        // remove profile module
        $xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('modules') . "` WHERE dirname='profile'");

        return true;
    }

    function apply_block()
    {
        $xoopsDB = $GLOBALS['xoopsDB'];
        $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix("block_module_link") . " SET module_id = -1, pageid = 0 WHERE module_id < 2 AND pageid = 1");
        
        //Change block module link to remove pages
        //Remove page links for module subpages
        $xoopsDB->queryF("DELETE FROM " . $xoopsDB->prefix("block_module_link") . " WHERE pageid > 0");

        //Drop primary key since it includes pageid
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("block_module_link") . " DROP PRIMARY KEY");
        
        //Drop pageid field
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("block_module_link") . " DROP pageid");
        
        // Recreate primary key
        $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix("block_module_link") . " ADD PRIMARY KEY (`block_id` , `module_id`)");
    
        // Create new block table
        $sql = "CREATE TABLE " . $xoopsDB->prefix("newblocks_icms") . " (
              bid mediumint(8) unsigned NOT NULL auto_increment,
              mid smallint(5) unsigned NOT NULL default '0',
              func_num tinyint(3) unsigned NOT NULL default '0',
              options varchar(255) NOT NULL default '',
              name varchar(150) NOT NULL default '',
              title varchar(255) NOT NULL default '',
              content text NOT NULL,
              side tinyint(1) unsigned NOT NULL default '0',
              weight smallint(5) unsigned NOT NULL default '0',
              visible tinyint(1) unsigned NOT NULL default '0',
              block_type char(1) NOT NULL default '',
              c_type char(1) NOT NULL default '',
              isactive tinyint(1) unsigned NOT NULL default '0',
              dirname varchar(50) NOT NULL default '',
              func_file varchar(50) NOT NULL default '',
              show_func varchar(50) NOT NULL default '',
              edit_func varchar(50) NOT NULL default '',
              template varchar(50) NOT NULL default '',
              bcachetime int(10) unsigned NOT NULL default '0',
              last_modified int(10) unsigned NOT NULL default '0',
              PRIMARY KEY  (bid),
              KEY mid (mid),
              KEY visible (visible),
              KEY isactive_visible_mid (isactive,visible,mid),
              KEY mid_funcnum (mid,func_num)
            ) TYPE=MyISAM;
            ";
        $xoopsDB->queryF($sql);
        
        // Copy data from block instance table and blocks table
        $sql =	"	INSERT INTO " . $xoopsDB->prefix("newblocks_icms") .
    			"		(bid, mid, options, name, title, side, weight, visible, " .
    			"			block_type, ".
    			"			isactive, dirname, func_file,".
    			"			show_func, edit_func, template, bcachetime, last_modified)".
        		"	SELECT " .
    			"		i.instanceid, c.mid, i.options, c.name, i.title, i.side, i.weight, i.visible, ".
    			"		CASE WHEN c.show_func='b_system_custom_show' THEN 'C' WHEN c.mid = 1 THEN 'S' ELSE 'M' END,".
    			"		c.isactive, c.dirname, c.func_file,".
    			"		c.show_func, c.edit_func, c.template, i.bcachetime, c.last_modified".
        		"	FROM " . $xoopsDB->prefix("block_instance") . " AS i," .
        		"		" . $xoopsDB->prefix("newblocks") . " AS c" .
        		"	WHERE i.bid = c.bid" .
        		"	ORDER BY i.instanceid ASC";
        $xoopsDB->queryF($sql);

        // Dealing with tables
        $xoopsDB->queryF("DROP TABLE `" . $xoopsDB->prefix("block_instance") . "`;");
        $xoopsDB->queryF("DROP TABLE `" . $xoopsDB->prefix("newblocks") . "`;");
        $xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("newblocks_icms") . "` TO `" . $xoopsDB->prefix("newblocks") . "`;");

        // Deal with custom blocks, convert options to type and content
        $sql = "SELECT bid, options FROM `" . $xoopsDB->prefix("newblocks") . "` WHERE show_func='b_system_custom_show'";
        $result = $xoopsDB->query($sql);
        while (list($bid, $options) = $xoopsDB->fetchRow($result)) {
    	    $_options = unserialize($options);
    	    $content = $_options[0]; 
    	    $type = $_options[1];
    	    $xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("newblocks") . "` SET c_type = '{$type}', options = '', content = " . $xoopsDB->quote($content) . " WHERE bid = {$bid}");
        }
        
        // Deal with block options, convert array values to "," and "|" delimited
        $sql = "SELECT bid, options FROM `" . $xoopsDB->prefix("newblocks") . "` WHERE show_func <> 'b_system_custom_show' AND options <> ''";
        $result = $xoopsDB->query($sql);
        while (list($bid, $_options) = $xoopsDB->fetchRow($result)) {
    	    $options = unserialize($_options);
            if (empty($options) || !is_array($options)) $options = array();
            $count = count($options);
            //Convert array values to comma-separated
            for ($i = 0; $i < $count; $i++) {
                if (is_array($options[$i])) {
                    $options[$i] = implode(',', $options[$i]);
                }
            }
            $options = implode('|', $options);
        	$sql = "UPDATE `" . $xoopsDB->prefix("newblocks") . "` SET options = " . $xoopsDB->quote($options) . " WHERE bid = {$bid}";
    	    $xoopsDB->queryF($sql);
        }
        
        return true;
    }
    
    function apply_pm()
    {
        $xoopsDB = $GLOBALS['xoopsDB'];
        $sql = "ALTER TABLE `" . $xoopsDB->prefix("priv_msgs") . "`
    	    DROP `from_delete`,
    	    DROP `to_delete`,
    	    DROP `to_save`,
    	    DROP `from_save`;
    	    ";
        $xoopsDB->queryF($sql);
        
        return true;
    }
}

$upg = new upgrade_220();
return $upg;

?>
