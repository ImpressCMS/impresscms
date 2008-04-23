<?php
/**
* Automatic update of the system module
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		malanciault <marcan@impresscms.org)
* @version		$Id: update.php 429 2008-003-25 22:21:41Z malanciault $
*/

function xoops_module_update_system(&$module) {
    /**
     * For compatibility upgrade...
     */
     $moduleVersion  = $module->getVar('version');
	if ($moduleVersion < 102) {
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
    
    ob_start();
    
    $dbVersion  = $module->getDBVersion();

	echo "<code>" . _DATABASEUPDATER_UPDATE_UPDATING_DATABASE . "<br />";

	// db migrate version = 1
    $newDbVersion = 1;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";
    	    	
		// Now, first, let's increment the conf_order of user option starting at new_user_notify
		$table = new IcmsDatabasetable('config');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_order', 3, '>'));
    	$table->addUpdateAll('conf_order', 'conf_order + 2', $criteria, true);
	    $icmsDatabaseUpdater->updateTable($table);	
	    unset($table);	
	    
	    // create extended date function's config option
	    $icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'use_ext_date', '_MD_AM_EXT_DATE', 0, '_MD_AM_EXT_DATEDSC', 'yesno', 'int', 8);
	    // create editors config option
	    $icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'editor_default', '_MD_AM_EDITOR_DEFAULT', 'default', '_MD_AM_EDITOR_DEFAULT_DESC', 'editor', 'text', 13);
	    $icmsDatabaseUpdater->insertConfig(XOOPS_CONF, 'editor_enabled_list', '_MD_AM_EDITOR_ENABLED_LIST', ".addslashes(serialize(array('default'))).", '_MD_AM_EDITOR_ENABLED_LIST_DESC', 'editor_multi', 'array', 13);

	    // create 2 new user config options
	    $icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'welcome_msg', '_MD_AM_WELCOMEMSG', 0, '_MD_AM_WELCOMEMSGDSC', 'yesno', 'int', 4);

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
	    $icmsDatabaseUpdater->insertConfig(XOOPS_CONF_USER, 'welcome_msg_content', '_MD_AM_WELCOMEMSG_CONTENT', $default_msg_content, '_MD_AM_WELCOMEMSG_CONTENTDSC', 'textarea', 'text', 5);	   
    }
    
	// db migrate version = 2
	// new content_manager by The_Rplima
    $newDbVersion = 2;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";
    	    	
		// Create table icmspage
		$table = new IcmsDatabasetable('icmspage');
		if (!$table->exists()) {
	    $table->setStructure("page_id mediumint(8) unsigned NOT NULL auto_increment,
		  page_moduleid mediumint(8) unsigned NOT NULL default '1',
		  page_title varchar(255) NOT NULL default '',
		  page_url varchar(255) NOT NULL default '',
		  page_status tinyint(1) unsigned NOT NULL default '1',
		  PRIMARY KEY  (page_id)");
		}
	    if (!$icmsDatabaseUpdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
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
    
	echo "</code>";

   $feedback = ob_get_clean();
    if (method_exists($module, "setMessage")) {
        $module->setMessage($feedback);
    } else {
        echo $feedback;
    }
    $icmsDatabaseUpdater->updateModuleDBVersion($newDbVersion, 'system');
    return true;    
}

?>