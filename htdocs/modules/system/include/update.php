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

	// db migrate version = 3
	// customtag feature added by marcan
/*
    $newDbVersion = 3;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";
    	    	
		// Create table system_customtag
		$table = new IcmsDatabasetable('system_customtag');
		if (!$table->exists()) {
	    $table->setStructure("customtagid int(11) unsigned NOT NULL auto_increment,
		  name varchar(255) NOT NULL default '',
		  description text NOT NULL default '',
		  content text NOT NULL default '',
		  language varchar(100) NOT NULL default '',
		  customtag_type tinyint(1) NOT NULL default 0,
		  PRIMARY KEY (customtagid)");
		}
	    $icmsDatabaseUpdater->updateTable($table);
	    unset($table);
    }    
  */  
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