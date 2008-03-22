<?php

class upgrade_impcms06 {
	
	var $usedFiles = array ();
	var $updater;
	
	function __construct() {
		$this->updater = XoopsDatabaseFactory::getDatabaseUpdater();
	}
	
	function isApplied() {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
	}

	function apply() {
    	// First let's create the dbversion field in the modules table
    	$table = new IcmsDatabasetable('modules');
    	$table->addNewField('dbversion', 'INT(11) DEFAULT 0');
    	return $this->updater->updateTable($table, true);
	}
}
$upg = new upgrade_impcms06();
return $upg;
?>