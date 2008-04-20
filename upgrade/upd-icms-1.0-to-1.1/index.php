<?php

class upgrade_impcms06 {
	
	var $usedFiles = array ();
    var $tasks = array('dbversion', 'db');
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
    function check_db()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    function apply_db()
    {
        return $this->update_configs('db');
    }

    function update_configs($task){
	$db = $GLOBALS['xoopsDB'];
        if (!$vars = $this->set_configs($task) ) {
            return false;
        }
        if ($task == "db" && !empty($vars["XOOPS_DB_COLLATION"]) && $pos = strpos($vars["XOOPS_DB_COLLATION"], "_")) {
            $vars["XOOPS_DB_CHARSET"] = substr($vars["XOOPS_DB_COLLATION"], 0, $pos);
            
		    $sql = "ALTER DATABASE `" . XOOPS_DB_NAME . "` DEFAULT CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']);
		    if ( !$db->queryF($sql) ) {
    		    return false;
		    }
		    if ( !$result = $db->queryF("SHOW TABLES") ) {
    		    return false;
		    }
		    while (list($table) = $db->fetchRow($result)) {
    		    $db->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']) );
    		    $db->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']) );
		    }
        }
        
        return true;
    }
        function check_dbversion()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    	function apply_dbversion() {
    	// First let's create the dbversion field in the modules table
    	$table = new IcmsDatabasetable('modules');
    	$table->addNewField('dbversion', 'INT(11) DEFAULT 0');
    	return $this->updater->updateTable($table, true);
	}

    function set_configs($task)
    {
        $ret = array();
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if ( !$configs || !is_array($configs) ) {
            return $ret;
        }
        if (empty($_POST['action']) || $_POST['task'] != $task) {
            return false;
        }
        
        foreach ( $configs as $key => $val ) {
            $ret['XOOPS_' . $key] = $val;
        }
        return $ret;
        
    }
}

$upg = new upgrade_impcms06();
return $upg;
?>