<?php
/**
 * DataBase Update Functions
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		1.0
 * @author		malanciault <marcan@impresscms.org)
 * @version		$Id$
 */

icms_loadLanguageFile ( 'core', 'databaseupdater' );

/**
 * Automatic update of the system module
 *
 * @param object $module reference to the module object
 * @param int $oldversion The old version of the database
 * @param int $dbVersion The database version
 * @return mixed
 */
function xoops_module_update_system(&$module, $oldversion = null, $dbVersion = null) {

	global $icmsConfig, $xoTheme;
	$icmsDB = $GLOBALS ['xoopsDB'];

	$from_112 = $abortUpdate = false;

	$oldversion = $module->getVar ( 'version' );
	if ($oldversion < 120) {
		$result = $icmsDB->query ( "SELECT t1.tpl_id FROM " . $icmsDB->prefix ( 'tplfile' ) . " t1, " . $icmsDB->prefix ( 'tplfile' ) . " t2 WHERE t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_id > t2.tpl_id" );

		$tplids = array ( );
		while ( list ( $tplid ) = $icmsDB->fetchRow ( $result ) ) {
			$tplids [] = $tplid;
		}

		if (count ( $tplids ) > 0) {
			$tplfile_handler = & xoops_gethandler ( 'tplfile' );
			$duplicate_files = $tplfile_handler->getObjects ( new Criteria ( 'tpl_id', "(" . implode ( ',', $tplids ) . ")", "IN" ) );

			if (count ( $duplicate_files ) > 0) {
				foreach ( array_keys ( $duplicate_files ) as $i ) {
					$tplfile_handler->delete ( $duplicate_files [$i] );
				}
			}
		}
	}

	$icmsDatabaseUpdater = IcmsDatabaseFactory::getDatabaseUpdater ();
	//$dbVersion  = $module->getDBVersion();
	//$oldversion  = $module->getVar('version');

	ob_start ();

	$dbVersion = $module->getDBVersion ();
	echo sprintf ( _DATABASEUPDATER_CURRENTVER, icms_conv_nr2local ( $dbVersion ) ) . '<br />';
	echo "<code>" . sprintf ( _DATABASEUPDATER_UPDATE_TO, icms_conv_nr2local( ICMS_SYSTEM_DBVERSION ) ). "<br />";

	/*
	 * DEVELOPER, PLEASE NOTE !!!
	 *
	 * Everytime we add a new upgrade block here, the dbversion of the System Module will get
	 * incremented. It is very important to modify the ICMS_SYSTEM_DBVERSION accordingly
	 * in htdocs/include/version.php
	 *
	 * When we start a new major release, move all the previous version's upgrade scripts to
	 * a separate file, to minimize file size and memory usage
	 */

	$CleanWritingFolders = false;

	if ( $dbVersion < 39 ) include 'update-112-to-121.php';


	/*
	 * This portion of the upgrade must remain as the last section of code to execute
	 * Place all release upgrade steps above this point
	 */
	echo "</code>";
    if ( $abortUpdate ) {
        icms_error_msg( sprintf( _DATABASEUPDATER_UPDATE_ERR, icms_conv_nr2local( $newDbVersion ) ), _DATABASEUPDATER_UPDATE_DB, TRUE);
    }
	if ($from_112 && ! $abortUpdate ) {
		/**
		 * @todo create a language constant for this text
		 */
		echo "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modulesadmin&op=install&module=content&from_112=1'>here</a>.</h3></code>";
		echo '<script>setTimeout("window.location.href=\'' . ICMS_URL . '/modules/system/admin.php?fct=modulesadmin&op=install&module=content&from_112=1\'",20000);</script>';
	}

	$feedback = ob_get_clean ();
	if (method_exists ( $module, "setMessage" )) {
		$module->messages = $module->setMessage ( $feedback );
	} else {
		echo $feedback;
	}

	$icmsDatabaseUpdater->updateModuleDBVersion ( $newDbVersion, 'system' );
	return icms_clean_folders ( array ('templates_c' => ICMS_ROOT_PATH . "/templates_c/", 'cache' => ICMS_ROOT_PATH . "/cache/" ), $CleanWritingFolders );
}
?>