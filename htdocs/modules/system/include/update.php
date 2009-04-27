<?php
/**
* DataBase Update Functons
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		malanciault <marcan@impresscms.org)
* @version		$Id$
*/

/**
* Automatic update of the system module
*
* @param object $module reference to the module object
* @param int $oldversion The old version of the database
* @param int $dbVersion The database version
* @return mixed
*/
function xoops_module_update_system(&$module, $oldversion = null, $dbVersion = null) {

	global $xoopsDB;
	$oldversion  = $module->getVar('version');
	if ($oldversion < 120) {
		$result = $xoopsDB->query("SELECT t1.tpl_id FROM ".$xoopsDB->prefix('tplfile')." t1, ".$xoopsDB->prefix('tplfile')." t2 WHERE t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_id > t2.tpl_id");
		
		$tplids = array();
		while (list($tplid) = $xoopsDB->fetchRow($result)) {
			$tplids[] = $tplid;
		}

		if (count($tplids) > 0) {
	    $tplfile_handler =& xoops_gethandler('tplfile');
	    $duplicate_files = $tplfile_handler->getObjects(new Criteria('tpl_id', "(".implode(',', $tplids).")", "IN"));

	    if (count($duplicate_files) > 0) {
				foreach (array_keys($duplicate_files) as $i) {
					$tplfile_handler->delete($duplicate_files[$i]);
				}
	    }
		}
  }

	$icmsDatabaseUpdater = XoopsDatabaseFactory::getDatabaseUpdater();
	//$dbVersion  = $module->getDBVersion();
	//$oldversion  = $module->getVar('version');
   
	ob_start();

	$dbVersion  = $module->getDBVersion();
	echo "<code>" . _DATABASEUPDATER_UPDATE_UPDATING_DATABASE . "<br />";

/**
 * Migrate the db with new changes from 1.1 since 1.0
 * Note: many of these changes have been implemented in the upgrade script, which is essential in 1.1 because
 * of the new dbversion field we have added in the modules table. However, starting with release after 1.1, all
 * upgrade scripts will be added here. Doing so, only the System module will need to be updated by webmaster.
 */

 /**
  * DEVELOPPER, PLEASE NOTE !!!
  *
  * Everytime we add a new upgrade block here, the dbversion of the System Module will get
  * incremented. It is very important to modify the ICMS_SYSTEM_DBVERSION accordingly
  * in htdocs/include/version.php
  */
  
	$CleanWritingFolders = false;
	
	$newDbVersion = 1;
	
	$action = sprintf (_CO_ICMS_UPDATE_DBVERSION, icms_conv_nr2local($newDbVersion));
	if ($dbVersion <= $newDbVersion) {
		echo $action;
		
		// Now, first, let's increment the conf_order of user option starting at new_user_notify
		$table = new IcmsDatabasetable('config');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_order', 3, '>'));
		$table->addUpdateAll('conf_order', 'conf_order + 2', $criteria, true);
		$icmsDatabaseUpdater->updateTable($table);
		unset($table);
		
		// create extended date function's config option
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'use_ext_date', '_MD_AM_EXT_DATE', 0, '_MD_AM_EXT_DATEDSC', 'yesno', 'int', 12);
		// create editors config option
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'editor_default', '_MD_AM_EDITOR_DEFAULT', 'default', '_MD_AM_EDITOR_DEFAULT_DESC', 'editor', 'text', 16);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'editor_enabled_list', '_MD_AM_EDITOR_ENABLED_LIST', ".addslashes(serialize(array('default'))).", '_MD_AM_EDITOR_ENABLED_LIST_DESC', 'editor_multi', 'array', 16);
		// create captcha options
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'use_captchaf', '_MD_AM_USECAPTCHAFORM', 1, '_MD_AM_USECAPTCHAFORMDSC', 'yesno', 'int', 37);
		
		// create 4 new user config options
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'use_captcha', '_MD_AM_USECAPTCHA', 1, '_MD_AM_USECAPTCHADSC', 'yesno', 'int', 3);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'welcome_msg', '_MD_AM_WELCOMEMSG', 0, '_MD_AM_WELCOMEMSGDSC', 'yesno', 'int', 3);
	
		// get the default content of the mail
		global $xoopsConfig;
		$default_msg_content_file = XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/mail_template/' . 'welcome.tpl';
		if (!file_exists($default_msg_content_file)) {
			$default_msg_content_file = XOOPS_ROOT_PATH . '/language/english/mail_template/' . 'welcome.tpl';
		}
		$fp = fopen($default_msg_content_file, 'r');
		if ($fp) {
			$default_msg_content = fread($fp, filesize($default_msg_content_file));
		}
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'welcome_msg_content', '_MD_AM_WELCOMEMSG_CONTENT', $default_msg_content, '_MD_AM_WELCOMEMSG_CONTENTDSC', 'textarea', 'text', 3);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'allwshow_sig', '_MD_AM_ALLWSHOWSIG', 1, '_MD_AM_ALLWSHOWSIGDSC', 'yesno', 'int', 4);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'allow_htsig', '_MD_AM_ALLWHTSIG', 1, '_MD_AM_ALLWHTSIGDSC', 'yesno', 'int', 4);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'sig_max_length', '_MD_AM_SIGMAXLENGTH', '255', '_MD_AM_SIGMAXLENGTHDSC', 'textbox', 'int', 4);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'avatar_allow_gravatar', '_MD_AM_GRAVATARALLOW', '1', '_MD_AM_GRAVATARALWDSC', 'yesno', 'int', 15);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'allow_annon_view_prof', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE', '1', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC', 'yesno', 'int', 36);
		
		// Adding configurations of meta tag&footer
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_METAFOOTER, 'google_meta', '_MD_AM_METAGOOGLE', '', '_MD_AM_METAGOOGLE_DESC', 'textbox', 'text', 9);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_METAFOOTER, 'use_google_analytics', '_MD_AM_USE_GOOGLE_ANA', 0, '_MD_AM_USE_GOOGLE_ANA_DESC', 'yesno', 'int', 21);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_METAFOOTER, 'google_analytics', '_MD_AM_GOOGLE_ANA', '', '_MD_AM_GOOGLE_ANA_DESC', 'textbox', 'text', 21);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_METAFOOTER, 'footadm', '_MD_AM_FOOTADM', 'Powered by ImpressCMS &copy; 2007-' . date("Y", time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>', '_MD_AM_FOOTADM_DESC', 'textarea', 'text', 22);
		
		// Adding configurations of search preferences
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_SEARCH, 'search_user_date', '_MD_AM_SEARCH_USERDATE', '1', '_MD_AM_SEARCH_USERDATE', 'yesno', 'int', 2);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_SEARCH, 'search_no_res_mod', '_MD_AM_SEARCH_NO_RES_MOD', '1', '_MD_AM_SEARCH_NO_RES_MODDSC', 'yesno', 'int', 3);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_SEARCH, 'search_per_page', '_MD_AM_SEARCH_PER_PAGE', '20', '_MD_AM_SEARCH_PER_PAGEDSC', 'textbox', 'int', 4);
		
		// Adding new cofigurations added for multi language
		$icmsDatabaseUpdater->insertConfig(IM_CONF_MULILANGUAGE, 'ml_autoselect_enabled', '_MD_AM_ML_AUTOSELECT_ENABLED', '0', '_MD_AM_ML_AUTOSELECT_ENABLED_DESC', 'yesno', 'int', 1);
		
		// Adding new function of content manager
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'default_page', '_MD_AM_DEFAULT_CONTPAGE', '0', '_MD_AM_DEFAULT_CONTPAGEDSC', 'select_pages', 'int', 1);
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'show_nav', '_MD_AM_CONT_SHOWNAV', '1', '_MD_AM_CONT_SHOWNAVDSC', 'yesno', 'int', 2);
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'show_subs', '_MD_AM_CONT_SHOWSUBS', '1', '_MD_AM_CONT_SHOWSUBSDSC', 'yesno', 'int', 3);
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'show_pinfo', '_MD_AM_CONT_SHOWPINFO', '1', '_MD_AM_CONT_SHOWPINFODSC', 'yesno', 'int', 4);
	
		global $xoopsConfig;
		$default_login_content_file = XOOPS_ROOT_PATH . '/upgrade/language/' . $xoopsConfig['language'] . '/' . 'login.tpl';
		if (!file_exists($default_login_content_file)) {
			$default_login_content_file = XOOPS_ROOT_PATH . '/upgrade/language/english/' . 'login.tpl';
		}
		$fp = fopen($default_login_content_file, 'r');
		if ($fp) {
			$default_login_content = fread($fp, filesize($default_login_content_file));
		}
	
		// Adding new function of Personalization
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo', '_MD_AM_LLOGOADM', '/uploads/img482278e29e81c.png', '_MD_AM_LLOGOADM_DESC', 'select_image', 'text', 1);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo_url', '_MD_AM_LLOGOADM_URL', ''.XOOPS_URL.'/index.php', '_MD_AM_LLOGOADM_URL_DESC', 'textbox', 'text', 2);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo_alt', '_MD_AM_LLOGOADM_ALT', 'ImpressCMS', '_MD_AM_LLOGOADM_ALT_DESC', 'textbox', 'text', 3);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo', '_MD_AM_RLOGOADM', '', '_MD_AM_RLOGOADM_DESC', 'select_image', 'text', 4);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo_url', '_MD_AM_RLOGOADM_URL', '', '_MD_AM_RLOGOADM_URL_DESC', 'textbox', 'text', 5);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo_alt', '_MD_AM_RLOGOADM_ALT', '', '_MD_AM_RLOGOADM_ALT_DESC', 'textbox', 'text', 6);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'rss_local', '_MD_AM_RSSLOCAL', 'http://www.impresscms.org/modules/smartsection/backend.php', '_MD_AM_RSSLOCAL_DESC', 'textbox', 'text', 7);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'editre_block', '_MD_AM_EDITREMOVEBLOCK', '1', '_MD_AM_EDITREMOVEBLOCKDSC', 'yesno', 'int', 8);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'multi_login', '_MD_AM_MULTLOGINPREVENT', '0', '_MD_AM_MULTLOGINPREVENTDSC', 'yesno', 'int', 9);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'multi_login_msg', '_MD_AM_MULTLOGINMSG', $default_login_content, '_MD_AM_MULTLOGINMSG_DESC', 'textarea', 'text', 10);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'email_protect', '_MD_AM_EMAILPROTECT', '0', '_MD_AM_EMAILPROTECTDSC', 'yesno', 'int', 11);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'email_font', '_MD_AM_EMAILTTF', 'arial.ttf', '_MD_AM_EMAILTTF_DESC', 'select_font', 'text', 12);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'email_font_len', '_MD_AM_EMAILLEN', '12', '_MD_AM_EMAILLEN_DESC', 'textbox', 'int', 13);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'email_cor', '_MD_AM_EMAILCOLOR', '#000000', '_MD_AM_EMAILCOLOR_DESC', 'color', 'text', 14);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'email_shadow', '_MD_AM_EMAILSHADOW', '#cccccc', '_MD_AM_EMAILSHADOW_DESC', 'color', 'text', 15);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'shadow_x', '_MD_AM_SHADOWX', '2', '_MD_AM_SHADOWX_DESC', 'textbox', 'int', 16);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'shadow_y', '_MD_AM_SHADOWY', '2', '_MD_AM_SHADOWY_DESC', 'textbox', 'int', 17);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'shorten_url', '_MD_AM_SHORTURL', '0', '_MD_AM_SHORTURLDSC', 'yesno', 'int', 18);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'max_url_long', '_MD_AM_URLLEN', '50', '_MD_AM_URLLEN_DESC', 'textbox', 'int', 19);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'pre_chars_left', '_MD_AM_PRECHARS', '35', '_MD_AM_PRECHARS_DESC', 'textbox', 'int', 20);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'last_chars_left', '_MD_AM_LASTCHARS', '10', '_MD_AM_LASTCHARS_DESC', 'textbox', 'int', 21);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'show_impresscms_menu', '_MD_AM_SHOW_ICMSMENU', '1', '_MD_AM_SHOW_ICMSMENU_DESC', 'yesno', 'int', 22);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'use_hidden', '_MD_AM_HIDDENCONTENT', '0', '_MD_AM_HIDDENCONTENTDSC', 'yesno', 'int', 23);
		// Adding new function of authentication
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_AUTH, 'auth_openid', '_MD_AM_AUTHOPENID', '0', '_MD_AM_AUTHOPENIDDSC', 'yesno', 'int', 1);
	
		$table = new IcmsDatabasetable('imagecategory');
		$icmsDatabaseUpdater->runQuery('INSERT INTO '.$table->name().' (imgcat_id, imgcat_name, imgcat_maxsize, imgcat_maxwidth, imgcat_maxheight, imgcat_display, imgcat_weight, imgcat_type, imgcat_storetype) VALUES (NULL, "Logos", 350000, 350, 80, 1, 0, "C", "file")','Successfully created Logos imagecategory','Problems when try to create Logos imagecategory');
		unset($table);
		global $xoopsDB;
		$result = $xoopsDB->query("SELECT imgcat_id FROM ".$xoopsDB->prefix('imagecategory')." WHERE imgcat_name = 'Logos'");
		list($categ_id) = $xoopsDB->fetchRow($result);
		$table = new IcmsDatabasetable('image');
		$icmsDatabaseUpdater->runQuery('INSERT INTO '.$table->name().' (image_id, image_name, image_nicename, image_mimetype, image_created, image_display, image_weight, imgcat_id) VALUES (NULL, "img482278e29e81c.png", "ImpressCMS", "image/png", '.time().', 1, 0, '.$categ_id.')','Successfully added default ImpressCMS admin logo','Problems when try to add ImpressCMS admin logo');
		unset($table);
		$table = new IcmsDatabasetable('group_permission');
		$icmsDatabaseUpdater->runQuery('INSERT INTO '.$table->name().' VALUES(0,1,'.$categ_id.',1,"imgcat_write")','','');
		$icmsDatabaseUpdater->runQuery('INSERT INTO '.$table->name().' VALUES(0,1,'.$categ_id.',1,"imgcat_read")','','');
		unset($table);
		$table = new IcmsDatabasetable('block_module_link');
		if (!$table->fieldExists('page_id')) {
			$table->addNewField('page_id', "smallint(5) NOT NULL default '0'");
		}
		if (!$icmsDatabaseUpdater->updateTable($table)) {
			/**
			* @todo trap the errors
			*/
		}
		
		$icmsDatabaseUpdater->runQuery('UPDATE '.$table->name().' SET module_id=0, page_id=1 WHERE module_id=-1','Block Visibility Restructured Successfully', 'Failed in Restructure the Block Visibility');
		
		unset($table);
	}

   /**
    * Changing $xoopsConfigPersona['rss_local'] from www.impresscms.org to community.impresscms.org
    */
	$newDbVersion = 2;

	if ($dbVersion < $newDbVersion) {
		echo $action;
		$configitem_handler = xoops_getHandler('configitem');
		// fetch the rss_local configitem
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_name', 'rss_local'));
		$criteria->add(new Criteria('conf_catid', XOOPS_CONF_PERSONA));
		$configitemsObj = $configitem_handler->getObjects($criteria);
		if (isset($configitemsObj[0]) && $configitemsObj[0]->getVar('conf_value', 'n') == 'http://www.impresscms.org/modules/smartsection/backend.php') {
			$configitemsObj[0]->setVar('conf_value', 'http://community.impresscms.org/modules/smartsection/backend.php');
			$configitem_handler->insert($configitemsObj[0]);
			echo "&nbsp;&nbsp;Updating rss_local config with correct info (if value was not previously changed by user)<br />";
		}
	}

	/**
	* A few fields were added in the DB after 1.1 Beta 1. Those fields were added to the upgrade script from 1.0 to 1.1,
	* but it may be a problem for people following each of our release
	* Bug item #2098379 is about this
	*/
	$newDbVersion = 3;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		$table = new IcmsDatabasetable('users');
		if (!$table->fieldExists('openid')) {
			$table->addNewField('openid', "varchar(255) NOT NULL default ''");
			$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);

		$table = new IcmsDatabasetable('users');
		if (!$table->fieldExists('user_viewoid')) {
			$table->addNewField('user_viewoid', "tinyint(1) UNSIGNED NOT NULL default 0");
			$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
	
		$table = new IcmsDatabasetable('users');
		if (!$table->fieldExists('pass_expired')) {
			$table->addNewField('pass_expired', "tinyint(1) UNSIGNED NOT NULL default 0");
			$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
		
		$table = new IcmsDatabasetable('users');
		if (!$table->fieldExists('enc_type')) {
			$table->addNewField('enc_type', "tinyint(2) UNSIGNED NOT NULL default 0");
			$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
	}


	$newDbVersion = 4;

	if($dbVersion < $newDbVersion) {
		echo $action;
		
		$table = new IcmsDatabasetable('users');
		if ($table->fieldExists('pass')) {
			$table->alterTable('pass', 'pass', "varchar(255) NOT NULL default ''");
			$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
	}

	$newDbVersion = 5;
	
	if($dbVersion < $newDbVersion) {
		echo $action;
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'use_jsjalali', '_MD_AM_JALALICAL', '0', '_MD_AM_JALALICALDSC', 'yesno', 'int', 23);
		unset($table);
	}

//Some users had used a copy of working branch and they got multiple option, this is to remove all those re-created options and make a single option
   $newDbVersion = 6;

	if($dbVersion < $newDbVersion) {
		echo $action;
		global $xoopsDB;
		$xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='use_jsjalali'");
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'use_jsjalali', '_MD_AM_JALALICAL', '0', '_MD_AM_JALALICALDSC', 'yesno', 'int', 23);
		unset($table);
	}

	$newDbVersion = 7;

	if ($dbVersion < $newDbVersion) {
		echo $action;
		$configitem_handler = xoops_getHandler('configitem');
		// fetch the rss_local configitem
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_name', 'google_analytics'));
		$criteria->add(new Criteria('conf_catid', XOOPS_CONF_METAFOOTER));
		$configitemsObj = $configitem_handler->getObjects($criteria);
		if (isset($configitemsObj[0]) && $configitemsObj[0]->getVar('conf_formtype', 'n') == 'textarea') {
			$configitemsObj[0]->setVar('conf_formtype', 'textbox');
			$configitem_handler->insert($configitemsObj[0]);
			echo "&nbsp;&nbsp;Updating google_analytics field type<br />";
		}
	}

	$newDbVersion = 8;
	
	if($dbVersion < $newDbVersion) {
		echo $action;
		
		$table = new IcmsDatabasetable('modules');
		if ($table->fieldExists('dbversion')) {
			$icmsDatabaseUpdater->runQuery("ALTER TABLE `" .$table->name()."` MODIFY dbversion INT(11) unsigned NOT NULL DEFAULT 1",'Successfully modified field dbversion in table modules','');
		}
		$icmsDatabaseUpdater->runQuery("ALTER TABLE `" .$table->name()."` MODIFY version smallint(5) unsigned NOT NULL default '102'",'Successfully modified field version in table modules','');
		unset($table);
	}


	$newDbVersion = 9;
	
	if($dbVersion < $newDbVersion) {
		echo $action;
		$table = new IcmsDatabasetable('users');
		$icmsDatabaseUpdater->runQuery("ALTER TABLE `" .$table->name()."` DROP INDEX unamepass, ADD INDEX unamepass (uname (10), pass (10))",'Successfully altered the index unamepass on table users','');
		$icmsDatabaseUpdater->runQuery("ALTER TABLE `" .$table->name()."` MODIFY pass_expired tinyint(1) unsigned NOT NULL default 0",'Successfully altered field pass_expired in table users','');
		unset($table);
	}

	$newDbVersion = 10;
	if($dbVersion < $newDbVersion) {
		echo "Database migrate to version " . $newDbVersion . "<br />";
		
		$db = $GLOBALS['xoopsDB'];
		
		if (getDbValue($db, 'newblocks', 'show_func', 'show_func="b_social_bookmarks"') == 0) {
		$sql = "SELECT bid FROM `".$db->prefix('newblocks')."` WHERE show_func='b_social_bookmarks'";
		$result = $db->query($sql);
		list($new_block_id) = $db->FetchRow($result);
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0, 1);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		}
		
		if (getDbValue($db, 'newblocks', 'show_func', 'show_func="b_content_show"') == 0) {
		$sql = "SELECT bid FROM `".$db->prefix('newblocks')."` WHERE show_func='b_content_show'";
		$result = $db->query($sql);
		list($new_block_id) = $db->FetchRow($result);
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0, 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		}
		
		if (getDbValue($db, 'newblocks', 'show_func', 'show_func="b_content_menu_show"') == 0) {
		$sql = "SELECT bid FROM `".$db->prefix('newblocks')."` WHERE show_func='b_content_menu_show'";
		$result = $db->query($sql);
		list($new_block_id) = $db->FetchRow($result);
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0, 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		}
		
		if (getDbValue($db, 'newblocks', 'show_func', 'show_func="b_content_relmenu_show"') == 0) {
		$sql = "SELECT bid FROM `".$db->prefix('newblocks')."` WHERE show_func='b_content_relmenu_show'";
		$result = $db->query($sql);
		list($new_block_id) = $db->FetchRow($result);
		$db->queryF(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0, 0);");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		}
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 16, 1, 'system_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 17, 1, 'system_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 18, 1, 'system_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 19, 1, 'system_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 20, 1, 'system_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 1, 1, 'content_admin');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 2, 1, 'group_manager');");
		$db->queryF(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, 3, 1, 'group_manager');");
		
	}

	$newDbVersion = 11;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'bad_unames'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'bad_emails'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'text' WHERE conf_name = 'meta_keywords'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'text' WHERE conf_name = 'meta_description'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'censor_words'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'ldap_users_bypass'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'text' WHERE conf_name = 'ldap_field_mapping'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'text' WHERE conf_name = 'reg_disclaimer'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'bad_ips'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'smtphost'");
		$icmsDatabaseUpdater->db->queryF("UPDATE `" . $icmsDatabaseUpdater->db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'text' WHERE conf_name = 'multi_login_msg'");
	}

	$newDbVersion = 12;

	if ($dbVersion < $newDbVersion) {
		echo $action;
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db, 'configcategory', 'confcat_name', 'confcat_name="_MD_AM_CAPTCHA"') == 0) {
			$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('11','_MD_AM_CAPTCHA')");
		}
		// Adding new function of Captcha
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_mode', '_MD_AM_CAPTCHA_MODE', 'image', '_MD_AM_CAPTCHA_MODEDSC', 'select', 'text', 1);
		$config_id = $db->getInsertId();
		
		$sql = "INSERT INTO " . $db->prefix('configoption') .
		" (confop_id, confop_name, confop_value, conf_id)" .
		" VALUES" .
		" (NULL, '_MD_AM_CAPTCHA_OFF', 'none', {$config_id})," .
		" (NULL, '_MD_AM_CAPTCHA_IMG', 'image', {$config_id})," .
		" (NULL, '_MD_AM_CAPTCHA_TXT', 'text', {$config_id})";
		if (!$db->queryF( $sql )) {
			return false;
		}
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_skipmember', '_MD_AM_CAPTCHA_SKIPMEMBER', '', '_MD_AM_CAPTCHA_SKIPMEMBERDSC', 'group_multi', 'array', 2);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_casesensitive', '_MD_AM_CAPTCHA_CASESENS', '0', '_MD_AM_CAPTCHA_CASESENSDSC', 'yesno', 'int', 3);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_skip_characters', '_MD_AM_CAPTCHA_SKIPCHAR', '', '_MD_AM_CAPTCHA_SKIPCHARDSC', 'textarea', 'array', 4);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_maxattempt', '_MD_AM_CAPTCHA_MAXATTEMP', '8', '_MD_AM_CAPTCHA_MAXATTEMPDSC', 'textbox', 'int', 5);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_num_chars', '_MD_AM_CAPTCHA_NUMCHARS', '4', '_MD_AM_CAPTCHA_NUMCHARSDSC', 'textbox', 'int', 6);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_fontsize_min', '_MD_AM_CAPTCHA_FONTMIN', '10', '_MD_AM_CAPTCHA_FONTMINDSC', 'textbox', 'int', 7);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_fontsize_max', '_MD_AM_CAPTCHA_FONTMAX', '12', '_MD_AM_CAPTCHA_FONTMAXDSC', 'textbox', 'int', 8);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_background_type', '_MD_AM_CAPTCHA_BGTYPE', '100', '_MD_AM_CAPTCHA_BGTYPEDSC', 'select', 'text', 9);
		$config_id = $db->getInsertId();
		
		$sql2 = "INSERT INTO " . $db->prefix('configoption') .
		" (confop_id, confop_name, confop_value, conf_id)" .
		" VALUES" .
		" (NULL, '_MD_AM_BAR', '0', {$config_id})," .
		" (NULL, '_MD_AM_CIRCLE', '1', {$config_id})," .
		" (NULL, '_MD_AM_LINE', '2', {$config_id})," .
		" (NULL, '_MD_AM_RECTANGLE', '3', {$config_id})," .
		" (NULL, '_MD_AM_ELLIPSE', '4', {$config_id})," .
		" (NULL, '_MD_AM_POLYGON', '5', {$config_id})," .
		" (NULL, '_MD_AM_RANDOM', '100', {$config_id})";
		if (!$db->queryF( $sql2 )) {
			return false;
		}
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_background_num', '_MD_AM_CAPTCHA_BGNUM', '50', '_MD_AM_CAPTCHA_BGNUMDSC', 'textbox', 'int', 10);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_CAPTCHA, 'captcha_polygon_point', '_MD_AM_CAPTCHA_POLPNT', '3', '_MD_AM_CAPTCHA_POLPNTDSC', 'textbox', 'int', 11);
	}

	$newDbVersion = 13;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		
		$db->queryF("UPDATE `" . $db->prefix('config') . "` SET conf_formtype = 'textsarea', conf_valuetype = 'array' WHERE conf_name = 'reg_disclaimer'");
		
	}


	$newDbVersion = 14;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		icms_copyr(ICMS_ROOT_PATH.'/preload', ICMS_ROOT_PATH.'/plugins/preloads');
		if (is_file(ICMS_ROOT_PATH.'/plugins/preloads')){
		icms_deleteFile(ICMS_ROOT_PATH.'/preload');
		}
	}

	$newDbVersion = 15;
	
	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		$table = new IcmsDatabasetable('users');
		if (!$table->fieldExists('login_name')) {
			$table->addNewField('login_name', "varchar(255) NOT NULL default ''");
			$icmsDatabaseUpdater->updateTable($table);
			$xoopsDB->queryF("UPDATE `" . $xoopsDB->prefix("users") . "` SET login_name=uname");
			$icmsDatabaseUpdater->runQuery("ALTER TABLE `" .$table->name()."` ADD INDEX login_name (login_name)",'Successfully altered the index login_name on table users','');
		}
		unset($table);
	}


	$newDbVersion = 16;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		$db = $GLOBALS['xoopsDB'];
		$sql = "SELECT conf_id FROM `".$db->prefix('config')."` WHERE conf_name = 'email_protect'";
		$result = $db->query($sql);
		list($conf_id) = $db->FetchRow($result);
		$db->queryF(" INSERT INTO " . $db->prefix("configoption") . " VALUES ('', '_MD_AM_NOMAILPROTECT', '0', " . $conf_id . ");");
		$db->queryF(" INSERT INTO " . $db->prefix("configoption") . " VALUES ('', '_MD_AM_GDMAILPROTECT', '1', " . $conf_id . ");");
		$db->queryF(" INSERT INTO " . $db->prefix("configoption") . " VALUES ('', '_MD_AM_REMAILPROTECT', '2', " . $conf_id . ");");
		$db->queryF("UPDATE `" . $db->prefix('config') . "` SET conf_formtype = 'select', conf_valuetype = 'text' WHERE conf_name = 'email_protect'");
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'recprvkey', '_MD_AM_RECPRVKEY', '', '_MD_AM_RECPRVKEY_DESC', 'textbox', 'text', 17);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'recpubkey', '_MD_AM_RECPUBKEY', '', '_MD_AM_RECPUBKEY_DESC', 'textbox', 'text', 17);
	}


	$newDbVersion = 17;
	
	if ($dbVersion < $newDbVersion) {
		echo $action;
		$db = $GLOBALS['xoopsDB'];
		//$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'delusers', '_MD_AM_DELUSRES', '90', '_MD_AM_DELUSRESDSC', 'textbox', 'int', 3);
		if (getDbValue($db, 'configcategory', 'confcat_name', 'confcat_name="_MD_AM_PLUGINS"') == 0) {
			$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('12','_MD_AM_PLUGINS')");
		}
		$pluginsvalue = '';
		$config_handler = xoops_gethandler('config');
		$icmsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
		if ($icmsConfigPersona['use_hidden'] == 1) {$pluginsvalue = 'hiddencontent';}
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_PLUGINS, 'sanitizer_plugins', '_MD_AM_SELECTSPLUGINS', addslashes(serialize(array($pluginsvalue))), '_MD_AM_SELECTSPLUGINS_DESC', 'select_plugin', 'array', 1);
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_PLUGINS, 'code_sanitizer', '_MD_AM_SELECTSHIGHLIGHT', 'none', '_MD_AM_SELECTSHIGHLIGHT_DESC', 'select', 'text', 2);
		$config_id = $db->getInsertId();
		$sql = "INSERT INTO " . $db->prefix('configoption') .
		" (confop_id, confop_name, confop_value, conf_id)" .
		" VALUES" .
		" (NULL, '_MD_AM_HIGHLIGHTER_OFF', 'none', {$config_id})," .
		" (NULL, '_MD_AM_HIGHLIGHTER_PHP', 'php', {$config_id})," .
		" (NULL, '_MD_AM_HIGHLIGHTER_GESHI', 'geshi', {$config_id})";
		if (!$db->queryF( $sql )) {
			return false;
		}
		$icmsDatabaseUpdater->insertConfig(ICMS_CONF_PLUGINS, 'geshi_default', '_MD_AM_GESHI_DEFAULT', 'php', '_MD_AM_GESHI_DEFAULT_DESC', 'select_geshi', 'text', 3);
		$db->queryF("UPDATE `" . $db->prefix('config') . "` SET conf_valuetype = 'array' WHERE conf_name = 'startpage'");
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'delusers', '_MD_AM_DELUSRES', '30', '_MD_AM_DELUSRESDSC', 'textbox', 'int', 6);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'allow_chguname', '_MD_AM_ALLWCHGUNAME', '0', '_MD_AM_ALLWCHGUNAMEDSC', 'yesno', 'int', 11);
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'num_pages', '_MD_AM_CONT_NUMPAGES', '10', '_MD_AM_CONT_NUMPAGESDSC', 'textbox', 'int', 5);
		$icmsDatabaseUpdater->insertConfig(IM_CONF_CONTENT, 'teaser_length', '_MD_AM_CONT_TEASERLENGTH', '500', '_MD_AM_CONT_TEASERLENGTHDSC', 'textbox', 'int', 6);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'pagstyle', '_MD_AM_PAGISTYLE', 'default', '_MD_AM_PAGISTYLE_DESC', 'select_paginati', 'text', 24);
	}

	$newDbVersion = 18;
	
	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		$table = new IcmsDatabasetable('icmscontent');
		if (!$table->fieldExists('content_tags')) {
		$table->addNewField('content_tags', "text");
		$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
		$table = new IcmsDatabasetable('imagecategory');
		if (!$table->fieldExists('imgcat_foldername')) {
		$table->addNewField('imgcat_pid', "varchar(100) default ''");
		$icmsDatabaseUpdater->updateTable($table);
		}
		if (!$table->fieldExists('imgcat_pid')) {
		$table->addNewField('imgcat_pid', "smallint(5) unsigned NOT NULL default '0'");
		$icmsDatabaseUpdater->updateTable($table);
		}
		unset($table);
		
		/**
		* DEVELOPPER, PLEASE NOTE !!!
		*
		* Everytime we add a new modules to system, the cache folders must get cleaned up so,
		* set a value for '$CleanWritingFolders' in each upgrade block here, if there is a cache
		* cleaning required please add $CleanWritingFolders = 1 and otherwise $CleanWritingFolders = 0
		* Like this bellow:
		*/
		$CleanWritingFolders = true;
	}



	$newDbVersion = 19;

	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		$module_handler = xoops_gethandler('module');
		$smartprofile_module = $module_handler->getByDirname('smartprofile');
		$table = new IcmsDatabasetable('profile_category');
		if($smartprofile_module && $smartprofile_module->getVar('isactive') && !$table->exists()){
			$xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("smartprofile_category") . "` TO `" . $xoopsDB->prefix("profile_category") . "`");
			$xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("smartprofile_field") . "` TO `" . $xoopsDB->prefix("profile_field") . "`");
			$xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("smartprofile_visibility") . "` TO `" . $xoopsDB->prefix("profile_visibility") . "`");
			$xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("smartprofile_profile") . "` TO `" . $xoopsDB->prefix("profile_profile") . "`");
			$xoopsDB->queryF("RENAME TABLE `" . $xoopsDB->prefix("smartprofile_regstep") . "` TO `" . $xoopsDB->prefix("profile_regstep") . "`");
			$command = array("ALTER TABLE `".$xoopsDB->prefix("profile_profile")."` ADD `newemail` varchar(255) NOT NULL default '' AFTER `profile_id`",
			"ALTER TABLE `".$xoopsDB->prefix("profile_field")."` ADD `exportable` int unsigned NOT NULL default 0 AFTER `step_id`",
			"UPDATE `" . $xoopsDB->prefix('modules') . "` SET dirname='profile' WHERE dirname='smartprofile'"
			);

			foreach($command as $sql){
				if (!$result = $xoopsDB->queryF($sql)) {
					icms_debug('An error occurred while executing "' . $sql . '" - ' . $xoopsDB->error());
					return false;
				}
			}
		}
	}


	$newDbVersion = 20;
	
	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		// Adding configurations of search preferences
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_SEARCH, 'enable_deep_search', '_MD_AM_DODEEPSEARCH', '1', '_MD_AM_DODEEPSEARCHDSC', 'yesno', 'int', 2);
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_SEARCH, 'num_shallow_search', '_MD_AM_NUMINITSRCHRSLTS', '5', '_MD_AM_NUMINITSRCHRSLTSDSC', 'textbox', 'int', 4);
	}


	$newDbVersion = 21;
	
	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		// create extended date function's config option
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'theme_admin_set', '_MD_AM_ADMIN_DTHEME', 'iTheme', '_MD_AM_ADMIN_DTHEME_DESC', 'theme', 'other', 12);
	}


	$newDbVersion = 22;
	
	if ($dbVersion < $newDbVersion) {
		global $xoopsDB;
		echo $action;
		$xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('modules') . "` WHERE dirname='waiting'");
		$xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('newblocks') . "` WHERE dirname='waiting'");
		$xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('tplfile') . "` WHERE tpl_module='waiting'");
	}

	$newDbVersion = 23;
	
	if($dbVersion < $newDbVersion) {
		echo $action;
		global $xoopsDB;
		$xoopsDB->queryF("DELETE FROM `" . $xoopsDB->prefix('config') . "` WHERE conf_name='pass_level'");
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'pass_level', '_MD_AM_PASSLEVEL', '1', '_MD_AM_PASSLEVEL_DESC', 'yesno', 'int', 2);
	}


	$newDbVersion = 24;
	
	if($dbVersion < $newDbVersion) {
		echo $action;
		$icmsDatabaseUpdater->insertConfig(XOOPS_CONF_PERSONA, 'use_custom_redirection', '_MD_AM_CUSTOMRED', '0', '_MD_AM_CUSTOMREDDSC', 'yesno', 'int', 9);
	}

     $newDbVersion = 25;
     
     if($dbVersion < $newDbVersion)
     {
          $table = new IcmsDatabasetable('icmscontent');
          if(!$table->fieldExists('content_seo_description'))
          {
               $table->addNewField('content_seo_description', "text");
               $icmsDatabaseUpdater->updateTable($table);
          }
          unset($table);

          $table = new IcmsDatabasetable('icmscontent');
          if(!$table->fieldExists('content_seo_keywords'))
          {
               $table->addNewField('content_seo_keywords', "text");
               $icmsDatabaseUpdater->updateTable($table);
          }
          unset($table);
     }

	echo "</code>";

 /**
  * DEVELOPPER, PLEASE NOTE !!!
  *
  * Everytime we add a new upgrade block here, the dbversion of the System Module will get
  * incremented. It is very important to modify the ICMS_SYSTEM_DBVERSION accordingly
  * in htdocs/include/version.php
  */


	$feedback = ob_get_clean();
	if (method_exists($module, "setMessage")) {
		$module->setMessage($feedback);
	} else {
		echo $feedback;
	}

	$icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
	return icms_clean_folders(array('templates_c' => ICMS_ROOT_PATH."/templates_c/", 'cache' => ICMS_ROOT_PATH."/cache/"), $CleanWritingFolders);
}
?>