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
		while (list ( $tplid ) = $icmsDB->fetchRow ( $result )) {
			$tplids [] = $tplid;
		}

		if (count ( $tplids ) > 0) {
			$tplfile_handler = icms::handler('icms_view_template_file');
			$duplicate_files = $tplfile_handler->getObjects ( new icms_criteria_Item ( 'tpl_id', "(" . implode ( ',', $tplids ) . ")", "IN" ) );

			if (count ( $duplicate_files ) > 0) {
				foreach ( array_keys ( $duplicate_files ) as $i) {
					$tplfile_handler->delete ( $duplicate_files [$i] );
				}
			}
		}
	}

	$icmsDatabaseUpdater = icms_database_Factory::getDatabaseUpdater ();
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

	if ($dbVersion < 40 ) include 'update-112-to-122.php';

/*  Begin upgrade to version 1.3 */
	if (!$abortUpdate ) $newDbVersion = 41;

	if ($dbVersion < $newDbVersion) {
	/* Add new tables and data for the help suggestions and quick search */
		$table = new IcmsDatabasetable( 'autosearch_cat' ) ;
		if (!$table->exists()) {
			$table->setStructure(
				"`cid` int(11) NOT NULL auto_increment,
				 `cat_name` varchar(255) NOT NULL,
				 `cat_url` text NOT NULL,
				 PRIMARY KEY (`cid`)"
			);
			if (!$table->createTable()) {
				$abortUpdate = TRUE;
				$newDbVersion = 40;
			}
			if (!$abortUpdate) {
				$search_cats = array(
					"NULL, '" . _MD_AM_ADSENSES . "', '/modules/system/admin.php?fct=adsense'",
					"NULL, '" . _MD_AM_AUTOTASKS . "', '/modules/system/admin.php?fct=autotasks'",
					"NULL, '" . _MD_AM_AVATARS . "', '/modules/system/admin.php?fct=avatars'",
					"NULL, '" . _MD_AM_BANS . "', '/modules/system/admin.php?fct=banners'",
					"NULL, '" . _MD_AM_BKPOSAD . "', '/modules/system/admin.php?fct=blockspadmin'",
					"NULL, '" . _MD_AM_BKAD . "', '/modules/system/admin.php?fct=blocksadmin'",
					"NULL, '" . _MD_AM_COMMENTS . "', '/modules/system/admin.php?fct=comments'",
					"NULL, '" . _MD_AM_CUSTOMTAGS . "', '/modules/system/admin.php?fct=customtag'",
					"NULL, '" . _MD_AM_USER . "', '/modules/system/admin.php?fct=users'",
					"NULL, '" . _MD_AM_FINDUSER . "', '/modules/system/admin.php?fct=finduser'",
					"NULL, '" . _MD_AM_ADGS . "', '/modules/system/admin.php?fct=groups'",
					"NULL, '" . _MD_AM_IMAGES . "', '/modules/system/admin.php?fct=images'",
					"NULL, '" . _MD_AM_MLUS . "', '/modules/system/admin.php?fct=mailusers'",
					"NULL, '" . _MD_AM_MIMETYPES . "', '/modules/system/admin.php?fct=mimetype'",
					"NULL, '" . _MD_AM_MDAD . "', '/modules/system/admin.php?fct=modulesadmin'",
					"NULL, '" . _MD_AM_PREF . "', '/modules/system/admin.php?fct=preferences'",
					"NULL, '" . _MD_AM_RATINGS . "', '/modules/system/admin.php?fct=rating'",
					"NULL, '" . _MD_AM_SMLS . "', '/modules/system/admin.php?fct=smilies'",
					"NULL, '" . _MD_AM_PAGES . "', '/modules/system/admin.php?fct=pages'",
					"NULL, '" . _MD_AM_TPLSETS . "', '/modules/system/admin.php?fct=tplsets'",
					"NULL, '" . _MD_AM_RANK . "', '/modules/system/admin.php?fct=userrank'",
					"NULL, '" . _MD_AM_VERSION . "', '/modules/system/admin.php?fct=version'");
				foreach ($search_cats as $cat) {
					$table->setData( $cat );
				}
				$table->addData();
			}
			unset( $table );
		}

		$table = new IcmsDatabasetable( 'autosearch_list' ) ;
		if (!$table->exists() && !$abortUpdate) {
			$table->setStructure(
				"`id` int(11) NOT NULL auto_increment,
				 `cat_id` int(11) NOT NULL,
				 `name` varchar(255) NOT NULL,
				 `img` varchar(255) NOT NULL,
				 `desc` text NOT NULL,
				 `url` text NOT NULL,
				 PRIMARY KEY (`id`)"
			);
			if (!$table->createTable()) {
				$abortUpdate = TRUE;
				$newDbVersion = 40;
			}
			if (!$abortUpdate) {
				icms_loadLanguageFile( 'system', 'preferences', TRUE );
				$search_items = array(
					"NULL, 1, '" . _MD_AM_ADSENSES . "', '/modules/system/admin/adsense/images/adsense_small.png', 'Adsenses are tags that you can define and use anywhere on your ImpressCMS site. ', '/modules/system/admin.php?fct=adsense'",
					"NULL, 2, '" . _MD_AM_AUTOTASKS . "', '/modules/system/admin/autotasks/images/autotasks_small.png', 'Auto Tasks allow you to create a schedule of actions that the system will perform automatically.', '/modules/system/admin.php?fct=autotasks'",
					"NULL, 3, '" . _MD_AM_AVATARS . "', '/modules/system/admin/avatars/images/avatars_small.png', 'Manage the avatars available to the users of your website.', '/modules/system/admin.php?fct=avatars'",
					"NULL, 4, '" . _MD_AM_BANS . "', '/modules/system/admin/banners/images/banners_small.png', 'Manage ad campaigns and advertiser accounts.', '/modules/system/admin.php?fct=banners'",
					"NULL, 5, '" . _MD_AM_BKPOSAD . "', '/modules/system/admin/blockspadmin/images/blockspadmin_small.png', 'Manage and create blocks positions that are used within the themes on your website.', '/modules/system/admin.php?fct=blockspadmin'",
					"NULL, 6, '" . _MD_AM_BKAD . "', '/modules/system/admin/blocksadmin/images/blocksadmin_small.png', 'Manage and create blocks used throughout your website.', '/modules/system/admin.php?fct=blocksadmin'",
					"NULL, 7, '" . _MD_AM_COMMENTS . "', '/modules/system/admin/comments/images/comments_small.png', 'Manage the comments made by users on your website.', '/modules/system/admin.php?fct=comments'",
					"NULL, 8, '" . _MD_AM_CUSTOMTAGS . "', '/modules/system/admin/customtag/images/customtag_small.png', 'Custom Tags are tags that you can define and use anywhere on your ImpressCMS site.', '/modules/system/admin.php?fct=customtag'",
					"NULL, 9, '" . _MD_AM_USER . "', '/modules/system/admin/users/images/users_small.png', 'Create, Modify or Delete registered users.', '/modules/system/admin.php?fct=users'",
					"NULL, 10, '" . _MD_AM_FINDUSER . "', '/modules/system/admin/findusers/images/findusers_small.png', 'Search through registered users with filters.', '/modules/system/admin.php?fct=findusers'",
					"NULL, 11, '" . _MD_AM_ADGS . "', '/modules/system/admin/groups/images/groups_small.png', 'Manage permissions, members, visibility and access rights of groups of users.', '/modules/system/admin.php?fct=groups'",
					"NULL, 12, '" . _MD_AM_IMAGES . "', '/modules/system/admin/images/images/images_small.png', 'Create groups of images and manage the permissions for each group. Crop and resize uploaded photos.', '/modules/system/admin.php?fct=images'",
					"NULL, 13, '" . _MD_AM_MLUS . "', '/modules/system/admin/mailusers/images/mailusers_small.png', 'Send mail to users of whole groups - or filter recipients based on matching criteria.', '/modules/system/admin.php?fct=mailusers'",
					"NULL, 14, '" . _MD_AM_MIMETYPES . "', '/modules/system/admin/mimetype/images/mimetype_small.png', 'manage the allowed extensions for files uploaded to your website.', '/modules/system/admin.php?fct=mimetype'",
					"NULL, 15, '" . _MD_AM_MDAD . "', '/modules/system/admin/modulesadmin/images/modulesadmin_small.png', 'Manage modules menu weight, status, name or update modules as needed.', '/modules/system/admin.php?fct=modulesadmin'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_AUTHENTICATION . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage security settings related to accessibility. Settings that will effect how users accounts are handled.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=7'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_AUTOTASKS . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Preferences for the Auto Tasks system.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=13'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_CAPTCHA . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage the settings used by captcha throughout your site.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=11'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_GENERAL . "', '/modules/system/admin/preferences/images/preferences_small.png', 'The primary settings page for basic information needed by the system.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=1'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_PURIFIER . "', '/modules/system/admin/preferences/images/preferences_small.png', 'HTMLPurifier is used to protect your site against common attack methods.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=14'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_MAILER . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Configure how your site will handle mail.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=6'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_METAFOOTER . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage your meta information and site footer as well as your crawler options.', '/modules/system/admin/preferences/images/preferences_small.png'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_MULTILANGUAGE . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage your sites Multi-language settings. Enable, and configure what languages are available and how they are triggered.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=8'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_PERSON . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Personalize the system with custom logos and other settings.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=10'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_PLUGINS . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Select which plugins are used and available to be used throughout your site.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=12'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_SEARCH . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage how the search function operates for your users.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=5'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_USERSETTINGS . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage how users register for your site. ser names length, formatting and password options.', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=2'",
					"NULL, 16, '" . _MD_AM_PREF . " - " . _MD_AM_CENSOR . "', '/modules/system/admin/preferences/images/preferences_small.png', 'Manage the language that is not permitted on your site..', '/modules/system/admin.php?fct=preferences&op=show&confcat_id=4'",
					"NULL, 17, '" . _MD_AM_RATINGS . "', '/modules/system/admin/rating/images/rating_small.png', 'Ratings is a new feature in ImpressCMS. With using this tool, you can add a new rating method to your modules, and control the results through this section!', '/modules/system/admin.php?fct=rating'",
					"NULL, 18, '" . _MD_AM_SMLS . "', '/modules/system/admin/smilies/images/smilies_small.png', 'Manage the available smilies and define the code associatted with each.', '/modules/system/admin.php?fct=smilies'",
					"NULL, 19, '" . _MD_AM_PAGES . "', '/modules/system/admin/pages/images/pages_small.png', 'Symlink allows you to create a unique link based on any page of your website, which can be used for blocks specific to a page URL, or to link directly within the content of a module.', '/modules/system/admin.php?fct=pages'",
					"NULL, 20, '" . _MD_AM_TPLSETS . "', '/modules/system/admin/tplsets/images/tplsets_small.png', 'Templates are sets of html/css files that render the screen layout of modules. This function can be accessed by the administrator or webmaster via the Control Panel Home.', '/modules/system/admin.php?fct=tplsets'",
					"NULL, 21, '" . _MD_AM_RANK . "', '/modules/system/admin/userrank/images/userrank_small.png', 'User ranks are picture, used to make difference between users in different levels of your site!', '/modules/system/admin.php?fct=userrank'",
					"NULL, 22, '" . _MD_AM_VERSION . "', '/modules/system/admin/version/images/version_small.png', 'Use this tool to check your system for updates.', '/modules/system/admin.php?fct=version'"
				);
				foreach ($search_items as $item) {
					$table->setData( $item );
				}
				$table->addData();
			}
			unset( $table );
		}

		/* Optimize old tables and fix data structures */
		$table = new IcmsDatabasetable( 'config' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX conf_mod_cat_id, ADD INDEX mod_cat_order(conf_modid, conf_catid, conf_order)", 'Successfully altered the indexes on table config', '' );
		unset( $table );

		$table = new IcmsDatabasetable( 'group_permission' );
		$table->addAlteredField( 'gperm_modid', "SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'", 'gperm_modid' );
		$table->alterTable();
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX itemid, DROP INDEX groupid, DROP INDEX gperm_modid", 'Successfully dropped the indexes on table group_permission', '' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` ADD INDEX name_mod_group (gperm_name(10), gperm_modid, gperm_groupid)", 'Successfully added the indexes on table group_permission', '' );
		unset( $table );

		$table = new IcmsDatabasetable( 'modules' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX hasmain, DROP INDEX hasadmin, DROP INDEX hassearch, DROP INDEX hasnotification, DROP INDEX name, DROP INDEX dirname", 'Successfully dropped the indexes on table modules', '' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` ADD INDEX dirname (dirname(5)), ADD INDEX active_main_weight (isactive, hasmain, weight)", 'Successfully added the indexes on table modules', '' );
		unset( $table );

		$table = new IcmsDatabasetable( 'users' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX email, DROP INDEX uiduname, DROP INDEX unamepass", 'Successfully dropped the indexes on table users', '' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX login_name, ADD UNIQUE INDEX login_name (login_name)", 'Successfully added the indexes on table users', '' );
		unset( $table );

		$table = new IcmsDatabasetable( 'priv_msgs' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX to_userid", 'Successfully dropped the indexes on table priv_msgs', '' );
		unset( $table );

		$table = new IcmsDatabasetable( 'ranks' );
		$icmsDatabaseUpdater->runQuery( "ALTER TABLE `" . $table->name() . "` DROP INDEX rank_min", 'Successfully dropped the indexes on table ranks', '' );
		unset( $table );

		/* Corrects an error from db version 4 */
		$table = new IcmsDatabasetable( 'users' );
		if ($table->fieldExists( 'pass' )) {
			$table->addAlteredField( 'pass', "varchar(255) NOT NULL default ''", 'pass' );
			$table->alterTable();
		}
		unset ( $table );

		/* change IP address to varchar(64) in session to accomodate IPv6 addresses */
		$table = new IcmsDatabasetable('session');
		if ($table->fieldExists('sess_ip')) {
			$table->addAlteredField('sess_ip', "varchar(64) NOT NULL default ''", 'sess_ip');
			$table->alterTable();
		}
		unset($table);

		/* add modname and ipf to modules table */
		$table = new IcmsDatabasetable("modules");
		$alter = false;
		if (!$table->fieldExists("modname")) {
			$table->addNewField("modname", "varchar(25) NOT NULL default ''");
			$alter = true;
		}
		if (!$table->fieldExists("ipf")) {
			$table->addNewField("ipf", "tinyint(1) unsigned NOT NULL default '0'");
			$alter = true;
		}
		if ($alter) $table->addNewFields();
		unset($table, $alter);

		$module_handler = icms::handler('icms_module');
		$modules = $module_handler->getObjects();
		foreach ($modules as $module) {
			if ($module->getInfo("modname") !== false) {
				$module->setVar("modname", $module->getInfo("modname"));
			}
			if ($module->getInfo("object_items") !== false) {
				$module->setVar("ipf", 1);
			}
			$module_handler->insert($module);
		}
		unset($module_handler, $modules);

		/* Finish up this portion of the db update */
		if (!$abortUpdate) {
			$icmsDatabaseUpdater->updateModuleDBVersion( $newDbVersion, 'system' );
			echo sprintf( _DATABASEUPDATER_UPDATE_OK, icms_conv_nr2local( $newDbVersion ) ) . '<br />';
		}
	}
/*  1.3 beta|rc|final release  */

	/*
	 * This portion of the upgrade must remain as the last section of code to execute
	 * Place all release upgrade steps above this point
	 */
	echo "</code>";
    if ($abortUpdate) {
        icms_core_Message::error( sprintf( _DATABASEUPDATER_UPDATE_ERR, icms_conv_nr2local( $newDbVersion ) ), _DATABASEUPDATER_UPDATE_DB, TRUE);
    }
	if ($from_112 && ! $abortUpdate) {
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
	return icms_core_Filesystem::cleanFolders(array('templates_c' => ICMS_ROOT_PATH . "/templates_c/", 'cache' => ICMS_ROOT_PATH . "/cache/" ), $CleanWritingFolders);
}
?>