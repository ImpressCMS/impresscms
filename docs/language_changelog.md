ImpressCMS Language ChangeLog
=============================

This is the changelog documenting the changes in the language constant for every release of ImpressCMS.

Legend :
  +  a constant was added or in some case, a whole new file was added
  +- a constant was modified
  -  a constant was removed OR a whole file was removed
  #  Directory / Folders guide

ImpressCMS 1.4.3
===
finish.php
----------
 - Remove Siteground mention
 
install.php
-----------
 - Remove Siteground mention

ImpressCMS 1.3.11
=======================================================
finish.php
----------
-	<h3>Hosting Sponsor</h3><p><a href='http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15'>SiteGround</a> provides hosting for the ImpressCMS websites and offers environments tailored to your needs and ImpressCMS</p>

install.php
-----------
-	<h3>Hosting Sponsor</h3><p><a href='http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15'>SiteGround</a> provides hosting for the ImpressCMS websites and offers environments tailored to your needs and ImpressCMS</p>

ImpressCMS 1.3.6.1
=======================================================
No Changes

ImpressCMS 1.3.6
=======================================================
No Changes

ImpressCMS 1.3.5
=======================================================
# Directory: install/language/english/
=======================================================
finish.php
---------------------------
+-	<h3>Hosting Sponsor</h3><p><a href='http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15'>SiteGround</a> provides hosting for the ImpressCMS websites and offers environments tailored to your needs and ImpressCMS</p> 

install.php
---------------------------
+-	define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>');

=======================================================
# Directory: language/english/
=======================================================
global.php
---------------------------
+-	define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>');

ImpressCMS 1.3
=======================================================
# Directory: modules/system/language/english/
=======================================================
core.php
---------------------------
+	define('_CORE_CHECKSUM_FILES_ADDED',' files have been added');
+	define('_CORE_CHECKSUM_FILES_REMOVED',' files have been removed');
+	define('_CORE_CHECKSUM_ALTERED_REMOVED',' files have been altered or removed');
+	define('_CORE_CHECKSUM_CHECKFILE','Checking against the file ');
+	define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' files have had their permissions altered');
+	define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'The file containing the checksums is unavailable or unreadable. Validation cannot be completed');
+	define('_CORE_CHECKSUM_ADDING',' Adding');
+	define('_CORE_CHECKSUM_CHECKSUM',' Checksum');
+	define('_CORE_CHECKSUM_PERMISSIONS',' Permissions');

+	define('_CORE_DEPRECATED', ' <strong><em>(Deprecated)</em></strong> - ');
+	define('_CORE_DEPRECATED_REPLACEMENT', ' <strong><em>use %s instead</em></strong>');
+	define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
+	define('_CORE_DEPRECATED_EXTRA', ' <strong><em>%s</em></strong>');
+	define('_CORE_DEPRECATED_MSG', '%s in %s, line %u <br />');
+	define('_CORE_DEPRECATED_CALLEDBY', 'Called by: ');
+	define('_CORE_REMOVE_IN_VERSION', 'This will be removed in version %s');
+	define('_CORE_DEBUG', 'Debug');

+	define('_CORE_OID_URL_EXPECTED', 'Expected an OpenID URL.');
+	define("_CORE_OID_URL_INVALID", 'Authentication error; not a valid OpenID.');
+	define("_CORE_OID_REDIRECT_FAILED", 'Could not redirect to server: %s');
+	define("_CORE_OID_INPROGRESS", "OpenID transaction in progress");

databaseupdater.php
---------------------------
+	define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modulesadmin&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
+	define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'An error occured while deleting specified fields %1$s from table %2$s');
+	define("_DATABASEUPDATER_MSG_DROPFIELD", 'Successfully dropped field %1$s from table %2$s');

global.php
---------------------------
+	define('_CHECKALL', 'Check all');
+	define('_COPYRIGHT', 'Copyright');
+	define("_LONGDATESTRING", "F jS Y, h:iA");
+	define('_AUTHOR', 'Author');
+	define("_CREDITS", "Credits");
+	define("_LICENSE", "License");
+	define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>');

modinfo.php
---------------------------
+	define('_MI_SYSTEM_BLOCK_CP_NEW', 'New Control Panel');

uploader.php
---------------------------
+	define("_ER_UP_PARTIALLY", "The uploaded file was only partially uploaded.");
+	define("_ER_UP_NO_TMP_DIR", "Missing a temporary folder. Please contact the administrator.");
+	define("_ER_UP_CANT_WRITE", "Failed to write file to disk. Please contact the administrator.");



===================================================
# Directory: modules/system/language/english/admin/
===================================================

admin.php
---------------------------
+	define("_MD_AM_GROUPS_ADVERTISING", "Advertising");
+	define("_MD_AM_GROUPS_CONTENT", "Content");
+	define("_MD_AM_GROUPS_LAYOUT", "Layout");
+	define("_MD_AM_GROUPS_MEDIA", "Media");
+	define("_MD_AM_GROUPS_SITECONFIGURATION", "Site Configuration");
+	define("_MD_AM_GROUPS_SYSTEMTOOLS", "System Tools");
+	define("_MD_AM_GROUPS_USERSANDGROUPS", "Users and Groups");
+	define('_MD_AM_ADSENSES_DSC', 'Adsenses are tags that you can define and use anywhere on your website.');
+	define('_MD_AM_AUTOTASKS_DSC', 'Auto Tasks allow you to create a schedule of actions that the system will perform automatically.');
+	define('_MD_AM_AVATARS_DSC', 'Manage the avatars available to the users of your website.');
+	define('_MD_AM_BANS_DSC', 'Manage ad campaigns and advertiser accounts.');
+	define('_MD_AM_BKPOSAD_DSC', 'Manage and create blocks positions that are used within the themes on your website.');
+	define('_MD_AM_BKAD_DSC', 'Manage and create blocks used throughout your website.');
+	define('_MD_AM_COMMENTS_DSC', 'Manage the comments made by users on your website.');
+	define('_MD_AM_CUSTOMTAGS_DSC', 'Custom Tags are tags that you can define and use anywhere on your website.');
+	define('_MD_AM_USER_DSC', 'Create, Modify or Delete registered users.');
+	define('_MD_AM_FINDUSER_DSC', 'Search through registered users with filters.');
+	define('_MD_AM_ADGS_DSC', 'Manage permissions, members, visibility and access rights of groups of users.');
+	define('_MD_AM_IMAGES_DSC', 'Create groups of images and manage the permissions for each group. Crop and resize uploaded photos.');
+	define('_MD_AM_MLUS_DSC', 'Send mail to users of whole groups - or filter recipients based on matching criteria.');
+	define('_MD_AM_MIMETYPES_DSC', 'Manage the allowed extensions for files uploaded to your website.');
+	define('_MD_AM_MDAD_DSC', 'Manage modules menu weight, status, name or update modules as needed.');
+	define('_MD_AM_RATINGS_DSC', 'With using this tool, you can add a new rating method to your modules, and control the results through this section!');
+	define('_MD_AM_SMLS_DSC', 'Manage the available smilies and define the code associatted with each.');
+	define('_MD_AM_PAGES_DSC', 'Symlink allows you to create a unique link based on any page of your website, which can be used for blocks specific to a page URL, or to link directly within the content of a module.');
+	define('_MD_AM_TPLSETS_DSC', 'Templates are sets of html/css files that render the screen layout of modules.');
+	define('_MD_AM_RANK_DSC', 'User ranks are picture, used to make difference between users in different levels of your website!');
+	define('_MD_AM_VRSN_DSC', 'Use this tool to check your system for updates.');
+	define('_MD_AM_PREF_DSC',"ImpressCMS Site Preferences");

images.php
---------------------
+	define('IMANAGER_NOPERM', 'You are not authorised to access this area!');

modulesadmin.php
---------------------
+	define("_MD_AM_UPDATE_FAIL","Unable to update %s.");
+	define('_MD_AM_FUNCT_EXEC','Function %s is successfully executed.');
+	define('_MD_AM_FAIL_EXEC','Failed to execute %s.');
+	define('_MD_AM_INSTALLING','Installing ');
+	define('_MD_AM_SQL_NOT_FOUND', 'SQL file not found at %s');
+	define('_MD_AM_SQL_FOUND', "SQL file found at %s . <br  /> Creating tables...");
+	define('_MD_SQL_NOT_VALID', ' is not valid SQL!');
+	define('_MD_AM_TABLE_CREATED', 	'Table %s created.');
+	define('_MD_AM_DATA_INSERT_SUCCESS', 'Data inserted to table %s.');
+	define('_MD_AM_RESERVED_TABLE', '%s is a reserved table!');
+	define('_MD_AM_DATA_INSERT_FAIL', 'Could not insert %s to database.');
+	define('_MD_AM_CREATE_FAIL', 'ERROR: Could not create %s');

+	define('_MD_AM_MOD_DATA_INSERT_SUCCESS', 'Module data inserted successfully. Module ID: %s');

+	define('_MD_AM_BLOCK_UPDATED', 'Block %s updated. Block ID: %s.');
+	define('_MD_AM_BLOCK_CREATED', 'Block %s created. Block ID: %s.');

+	define('_MD_AM_BLOCKS_ADDING', 'Adding blocks...');
+	define('_MD_AM_BLOCKS_ADD_FAIL', 'ERROR: Could not add block %1$s to the database! Database error: %2$s');
+	define('_MD_AM_BLOCK_ADDED', 'Block %1$s added. Block ID: %2$s');
+	define('_MD_AM_BLOCKS_DELETE', 'Deleting block...');
+	define('_MD_AM_BLOCK_DELETE_FAIL', 'ERROR: Could not delete block %1$s. Block ID: %2$s');
+	define('_MD_AM_BLOCK_DELETED', 'Block %1$s deleted. Block ID: %2$s');
+	define('_MD_AM_BLOCK_TMPLT_DELETE_FAILED', 'ERROR: Could not delete block template %1$s  from the database. Template ID: %2$s');
+	define('_MD_AM_BLOCK_TMPLT_DELETED', 'Block template %1$s  deleted from the database. Template ID: %2$s');
+	define('_MD_AM_BLOCK_ACCESS_FAIL', 'ERROR: Could not add block access right. Block ID: %1$s  Group ID: %2$S');
+	define('_MD_AM_BLOCK_ACCESS_ADDED', 'Added block access right. Block ID: %1$s, Group ID: %2$s');

+	define('_MD_AM_CONFIG_ADDING', 'Adding module config data...');
+	define('_MD_AM_CONFIGOPTION_ADDED', 'Config option added. Name: %1$s Value: %2$s');
+	define('_MD_AM_CONFIG_ADDED', 'Config %s  added to the database.');
+	define('_MD_AM_CONFIG_ADD_FAIL', 'ERROR: Could not insert config %s to the database.');

+	define('_MD_AM_PERMS_ADDING', 'Setting group rights...');
+	define('_MD_AM_ADMIN_PERM_ADD_FAIL', 'ERROR: Could not add admin access right for Group ID %s');
+	define('_MD_AM_ADMIN_PERM_ADDED', 'Added admin access right for Group ID %s');
+	define('_MD_AM_USER_PERM_ADD_FAIL', 'ERROR: Could not add user access right for Group ID: %s');
+	define('_MD_AM_USER_PERM_ADDED', 'Added user access right for Group ID: %s');

+	define('_MD_AM_AUTOTASK_FAIL', 'ERROR: Could not insert autotask to db. Name: %s');
+	define('_MD_AM_AUTOTASK_ADDED', 'Added task to autotasks list. Task Name: %s');
+	define('_MD_AM_AUTOTASK_UPDATE', 'Updating autotasks...');
+	define('_MD_AM_AUTOTASKS_DELETE', 'Deleting Autotasks...');

+	define('_MD_AM_SYMLINKS_DELETE', 'Deleting links from Symlink Manager...');
+	define('_MD_AM_SYMLINK_DELETE_FAIL', 'ERROR: Could not delete link %1$s from the database. Link ID: %2$s');
+	define('_MD_AM_SYMLINK_DELETED', 'Link %1$s deleted from the database. Link ID: %2$s');

+	define('_MD_AM_DELETE_FAIL', 'ERROR: Could not delete %s');

+	define('_MD_AM_MOD_UP_TEM','Updating templates...');
+	define('_MD_AM_TEMPLATE_INSERT_FAIL','ERROR: Could not insert template %s to the database.');
+	define('_MD_AM_TEMPLATE_UPDATE_FAIL','ERROR: Could not update template %s.');
+	define('_MD_AM_TEMPLATE_INSERTED','Template %s added to the database. (ID: %s)');
+	define('_MD_AM_TEMPLATE_COMPILE_FAIL','ERROR: Failed compiling template %s.');
+	define('_MD_AM_TEMPLATE_COMPILED','Template %s compiled.');
+	define('_MD_AM_TEMPLATE_RECOMPILED','Template %s recompiled.');
+	define('_MD_AM_TEMPLATE_RECOMPILE_FAIL','ERROR: Could not recompile template %s.');

+	define('_MD_AM_TEMPLATES_ADDING', 'Adding templates...');
+	define('_MD_AM_TEMPLATES_DELETE', 'Deleting templates...');
+	define('_MD_AM_TEMPLATE_DELETE_FAIL', 'ERROR: Could not delete template %1$s from the database. Template ID: %2$s');
+	define('_MD_AM_TEMPLATE_DELETED', 'Template %1$s  deleted from the database. Template ID: %2$s');
+	define('_MD_AM_TEMPLATE_UPDATED', 'Template %s updated.');

+	define('_MD_AM_MOD_TABLES_DELETE', 'Deleting module tables...');
+	define('_MD_AM_MOD_TABLE_DELETE_FAIL', 'ERROR: Could not drop table %s');
+	define('_MD_AM_MOD_TABLE_DELETED', 'Table %s dropped.');
+	define('_MD_AM_MOD_TABLE_DELETE_NOTALLOWED', 'ERROR: Not allowed to drop table %s!');

+	define('_MD_AM_COMMENTS_DELETE', 'Deleting comments...');
+	define('_MD_AM_COMMENT_DELETE_FAIL', 'ERROR: Could not delete comments');
+	define('_MD_AM_COMMENT_DELETED', 'Comments deleted');

+	define('_MD_AM_NOTIFICATIONS_DELETE', 'Deleting notifications...');
+	define('_MD_AM_NOTIFICATION_DELETE_FAIL', 'ERROR: Could not delete notifications');
+	define('_MD_AM_NOTIFICATION_DELETED', 'Notifications deleted');

+	define('_MD_AM_GROUPPERM_DELETE', 'Deleting group permissions...');
+	define('_MD_AM_GROUPPERM_DELETE_FAIL', 'ERROR: Could not delete group permissions');
+	define('_MD_AM_GROUPPERM_DELETED', 'Group permissions deleted');

+	define('_MD_AM_CONFIGOPTIONS_DELETE', 'Deleting module config options...');
+	define('_MD_AM_CONFIGOPTION_DELETE_FAIL', 'ERROR: Could not delete config data from the database. Config ID: %s');
+	define('_MD_AM_CONFIGOPTION_DELETED', 'Config data deleted from the database. Config ID: %s');

preferences.php
---------------------
-	define("_MD_AM_PURIFIER_FILTER_CUSTOM","Enter Custom Filters");
-	define("_MD_AM_PURIFIER_FILTER_CUSTOMDSC","Enter the name of the custom filters to enable. (seperate with |)<br />Custom Filter files must be located in the 'libraries/htmlpurifier/standalone/HTMLPurifier/Filter' directory & the filename must be the same as the classname (see existing filters for example)<br />you must also precede the filter name with 'new'. example new HTMLPurifier_Filter_Vimeo()");
+	define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOM","Allow Custom Filters");
+	define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOMDSC","Allow Custom Filters?<br /><br />if enabled this will allow you to use custom filters located in;<br />'libraries/htmlpurifier/standalone/HTMLPurifier/Filter'");

+	define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPAT","Enable IE Flash Compatibility");
+	define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPATDSC","If true, HTML Purifier will generate Internet Explorer compatibility code for all object code. This is highly recommended if you enable HTML.SafeObject.");
+	define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRN","Allow FullScreen in Flash objects");
+	define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRNDSC","If true, HTML Purifier will allow use of 'allowFullScreen' in embedded flash content when using HTML.SafeObject.");
+	define("_MD_AM_PURIFIER_CORE_NORMALNEWLINES","Normalize Newlines");
+	define("_MD_AM_PURIFIER_CORE_NORMALNEWLINESDSC","Whether or not to normalize newlines to the operating system default. When false, HTML Purifier will attempt to preserve mixed newline files.");
+	define('_MD_AM_AUTHENTICATION_DSC', 'Manage security settings related to accessibility. Settings that will effect how users accounts are handled.');
+	define('_MD_AM_AUTOTASKS_PREF_DSC', 'Preferences for the Auto Tasks system.');
+	define('_MD_AM_CAPTCHA_DSC', 'Manage the settings used by captcha throughout your site.');
+	define('_MD_AM_GENERAL_DSC', 'The primary settings page for basic information needed by the system.');
+	define('_MD_AM_PURIFIER_DSC', 'HTMLPurifier is used to protect your site against common attack methods.');
+	define('_MD_AM_MAILER_DSC', 'Configure how your site will handle mail.');
+	define('_MD_AM_METAFOOTER_DSC', 'Manage your meta information and site footer as well as your crawler options.');
+	define('_MD_AM_MULTILANGUAGE_DSC', 'Manage your sites Multi-language settings. Enable, and configure what languages are available and how they are triggered.');
+	define('_MD_AM_PERSON_DSC', 'Personalize the system with custom logos and other settings.');
+	define('_MD_AM_PLUGINS_DSC', 'Select which plugins are used and available to be used throughout your site.');
+	define('_MD_AM_SEARCH_DSC', 'Manage how the search function operates for your users.');
+	define('_MD_AM_USERSETTINGS_DSC', 'Manage how users register for your site. ser names length, formatting and password options.');
+	define('_MD_AM_CENSOR_DSC', 'Manage the language that is not permitted on your site.');
+	define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOM","Allow Custom Filters");
+	define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOMDSC","Allow Custom Filters?<br /><br />if enabled this will allow you to use custom filters located in;<br />'libraries/htmlpurifier/standalone/HTMLPurifier/Filter'");

tplsets.php
---------------------
+	define('_MD_TPLSET_DEFAULT_NOEDIT', 'Default template files cannot be edited.');
+	define('_MD_TPLSET_INSERT_FAILED', 'Could not insert template file %s to the database.');
+	define("_MD_TPLSET_TEMPLATE_NOTEXIST", 'Selected template (ID: %s) does not exist');
+	define("_MD_TPLSET_DELETE_FAIL", 'Could not delete %s from the database.');
+	define("_MD_TPLSET_DEFAULT_NODELETE", 'Default template files cannot be deleted.');
+	define("_MD_TPLSET_DELETING", 'Deleting template files...');
+	define("_MD_TPLSET_DELETE_OK", "Template %s deleted");
+	define("_MD_TPLSET_UNIQUE_NAME", "Template set name must be a different name.");
+	define("_MD_TPLSET_EXISTS", "Template set %s already exists");
+	define("_MD_TPLSET_CREATE_FAILED", "Could not create template set %s");
+	define("_MD_TPLSET_COPY_FAILED", "Failed copying template %s");
+	define("_MD_TPLSET_COPY_OK", "Template %s  copied");
+	define("_MD_TPLSET_CREATE_OK", "Template set %s  created");
+	define("_MD_TPLSET_TPLFILES_NOTEXIST", 'Template files for %s do not exist');
+	define("_MD_TPLSET_FILE_NOTEXIST", 'Selected file does not exist');
+	define("_MD_TPLSET_INSERT_OK", 'Template %s added to the database.');
+	define("_MD_TPLSET_INSTALLING_BLOCKS", "Installing block template files");
+	define("_MD_TPLSET_BLOCK_INSERT_FAILED", "Could not insert block template %s to the database.");
+	define("_MD_TPLSET_BLOCK_INSERT_OK", "Block template %s added to the database");
+	define("_MD_TPLSET_TEMPLATE_ADDED",	'Module template files for template set %s generated and installed.');
+	define("_MD_TPLSET_NAME_NOT_BLANK", "Template name cannot be blank");
+	define("_MD_TPLSET_INVALID_NAME", "Template name contained invalid characters");
+	define("_MD_TPLSET_NOT_FOUND", 'Could not find %s in the default template set.');
+	define("_MD_TPLSET_IMGSET_CREATE_FAILED", "Could not create image set.");
+	define("_MD_TPLSET_IMGSET_CREATED", "Image set %s created.");
+	define("_MD_TPLSET_IMGSET_LINK_FAILED", "Failed linking image set to template set");
+	define("_MD_TPLSET_FILE_UNNECESSARY", 'Template file %s does not need to be installed (PHP files using this template file does not exist');
+	define("_MD_TPLSET_UPDATED", "Template file %s updated");
+	define("_MD_TPLSET_COMPILED", "Template file %s compiled");
+	define("_MD_TPLSET_IMPORT_FAILED", "Could not import file ");
+	define("_MD_TPLSET_DELETING_DATA". 'Deleting template set data...');
+	define("_MD_TPLSET_COPYING", "Copying template files...");
+	define("_MD_TPLSET_INSTALLING", "Installing module template files for template set %s");

finusersphp.php
---------------------------
+	define("_AM_ACTIONS","Actions");

comments.php
---------------------------
+	define('_MD_AM_ACTION', 'Actions');
+	define('_MD_AM_MESSAGE_ICON', 'Icon');

banners.php
---------------------------
+	define("_AM_BANNERS_DEPRECATED", "This feature is deprecated and will be removed with ImpressCMS 1.4!<br />Please consider installing the banners module.");

avatars.php
---------------------------
+	define('_MD_RESTRICTIONS', 'Restrictions');


===================================================
# Directory: install/language/english
===================================================

welcome.php
---------------------------
+-	<li><a href="http://www.php.net/" rel="external">PHP</a> 5.2 or higher (5.2.8 or higher recommended, <strong>5.3 is now supported</strong>) and 16mb minimum memory allocation</li>
