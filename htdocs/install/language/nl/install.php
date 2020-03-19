<?php
/**
 * Installer main english strings declaration file.
 * @copyright	The ImpressCMS project http://www.impresscms.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author       Skalpa Keo <skalpa@xoops.org>
 * @author       Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
 * @since        1.0
 * @package 		installer
 */

define("SHOW_HIDE_HELP", "Helptekst weergeven/verbergen");

define("ALTERNATE_LANGUAGE_MSG", "Download een alternatief taalpakket van de ImpressCMS website");
define("ALTERNATE_LANGUAGE_LNK_MSG", "Selecteer een andere taal die hier niet is vermeld.");
define("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");

// Configuration check page
define("SERVER_API", "Server API");
define("PHP_EXTENSION", "%s extensie");
define("CHAR_ENCODING", "Karakter codering");
define("XML_PARSING", "XML parsing");
define("REQUIREMENTS", "Vereisten");
define("_PHP_VERSION", "PHP versie");
define("RECOMMENDED_SETTINGS", "Aanbevolen instellingen");
define("RECOMMENDED_EXTENSIONS", "Aanbevolen extensies");
define("SETTING_NAME", "Naam instelling");
define("RECOMMENDED", "Aanbevolen");
define("CURRENT", "Huidig");
define("RECOMMENDED_EXTENSIONS_MSG", "Deze extensies zijn niet nodig voor normaal gebruik, maar kunnen nodig zijn om gebruik te maken van
sommige specifieke functies (zoals de taal of RSS support). Daarom wordt aanbevolen om ze geïnstalleerd te hebben." );
define("NONE", "Geen");
define("SUCCESS", "Geslaagd");
define("WARNING", "Waarschuwing");
define("FAILED", "Mislukt");

// Titles (main and pages)
define("XOOPS_INSTALL_WIZARD", " %s - Installatie Wizard");
define("INSTALL_STEP", "Stap");
define("INSTALL_H3_STEPS", "Stappen");
define("INSTALL_OUTOF", " van de ");
define("INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"https://www.impresscms.org\" target=\"_blank\">The ImpressCMS Project</a>");

define("LANGUAGE_SELECTION", "Taal selectie");
define("LANGUAGE_SELECTION_TITLE", "Kies uw taal"); // L128
define("INTRODUCTION", "Inleiding");
define("INTRODUCTION_TITLE", "Welkom bij de ImpressCMS installatieassistent"); // L0
define("CONFIGURATION_CHECK", "Configuratie Controle");
define("CONFIGURATION_CHECK_TITLE", "Controle van de serverconfiguratie");
define("PATHS_SETTINGS", "Paden instellingen");
define("PATHS_SETTINGS_TITLE", "Paden instellingen");
define("DATABASE_CONNECTION", "Database verbinding");
define("DATABASE_CONNECTION_TITLE", "Database verbinding");
define("DATABASE_CONFIG", "Database configuratie");
define("DATABASE_CONFIG_TITLE", "Database configuratie");
define("CONFIG_SAVE", "Instellingen opslaan");
define("CONFIG_SAVE_TITLE", "Uw systeemconfiguratie wordt opgeslagen");
define("TABLES_CREATION", "Tabellen aanmaken");
define("TABLES_CREATION_TITLE", "Database tabellen aanmaken");
define("INITIAL_SETTINGS", "Initiële instellingen");
define("INITIAL_SETTINGS_TITLE", "Voer uw begininstellingen in");
define("DATA_INSERTION", "Invoeging van gegevens");
define("DATA_INSERTION_TITLE", "Je instellingen worden opgeslagen in de database");
define("WELCOME", "Welkom");
define("NO_PHP5", "Geen PHP 5");
define("WELCOME_TITLE", "Installatie van ImpressCMS is voltooid"); // L0
define("MODULES_INSTALL", "Installeer modules");
define("MODULES_INSTALL_TITLE", "Installatie van modules ");
define("NO_PHP5_TITLE", "Geen PHP 5");
define("NO_PHP5_CONTENT", "PHP 5.2.0 minimum is vereist om ImpressCMS goed te laten functioneren - uw installatie kan niet doorgaan. Werk met uw hostingprovider samen om uw omgeving te upgraden naar een versie van PHP die nieuwer is dan 5..0 (5.2.8 + wordt aanbevolen) voordat u opnieuw probeert te installeren. Voor meer informatie lees <a href='https://www.impresscms.org/modules/news/article.php?article_id=122' >ImpressCMS op PHP5 </a>.");
define("SAFE_MODE", "Veilige modus aan");
define("SAFE_MODE_TITLE", "Veilige modus aan");
define("SAFE_MODE_CONTENT", "ImpressCMS has detected PHP is running in Safe Mode. Because of this, your installation cannot continue. Please work with your hosting provider to change your environment before attempting to install again.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "ImpressCMS documents root physical path"); // L55
define("XOOPS_ROOT_PATH_HELP", "This is the physical path of the ImpressCMS documents folder, the very web root of your ImpressCMS application"); // L59

define("XOOPS_URL_LABEL", "Website location (URL)"); // L56
define("XOOPS_URL_HELP", "Main URL that will be used to access your ImpressCMS installation"); // L58

define("LEGEND_CONNECTION", "Server connection");
define("LEGEND_DATABASE", "Database"); // L51

define("DB_HOST_LABEL", "Server hostname"); // L27
define("DB_HOST_HELP", "Hostname of the database server. If you are unsure, <em>localhost</em> works in most cases"); // L67
define("DB_USER_LABEL", "User name"); // L28
define("DB_USER_HELP", "Name of the user account that will be used to connect to the database server"); // L65
define("DB_PASS_LABEL", "Wachtwoord"); // L52
define("DB_PASS_HELP", "Password of your database user account"); // L68
define("DB_NAME_LABEL", "Database name"); // L29
define("DB_NAME_HELP", "The name of database on the host. The installer will attempt to create a database if one does not exist"); // L64
define("DB_CHARSET_LABEL", "Database character set, we STRONGLY recommend you to use UTF-8 as default.");
define("DB_CHARSET_HELP", "MySQL includes character set support that enables you to store data using a variety of character sets and perform comparisons according to a variety of collations.");
define("DB_COLLATION_LABEL", "Database collation");
define("DB_COLLATION_HELP", "A collation is a set of rules for comparing characters in a character set.");
define("DB_PREFIX_LABEL", "Table prefix"); // L30
define("DB_PREFIX_HELP", "This prefix will be added to all new tables created to avoid name conflicts in the database. If you are unsure, just keep the default"); // L63
define("DB_PCONNECT_LABEL", "Use persistent connection"); // L54

define("DB_SALT_LABEL", "Password Salt Key"); // L98
define("DB_SALT_HELP", "This salt key will be appended to passwords in function icms_encryptPass(), and is used to create a totally unique secure password. Do Not change this key once your site is live, doing so will render ALL passwords invalid. If you are unsure, just keep the default"); // L97

define("LEGEND_ADMIN_ACCOUNT", "Administrator account");
define("ADMIN_LOGIN_LABEL", "Admin login"); // L37
define("ADMIN_EMAIL_LABEL", "Admin e-mail"); // L38
define("ADMIN_PASS_LABEL", "Admin password"); // L39
define("ADMIN_CONFIRMPASS_LABEL", "Confirm password"); // L74
define("ADMIN_SALT_LABEL", "Password Salt Key"); // L99

// Buttons
define("BUTTON_PREVIOUS", "Vorige"); // L42
define("BUTTON_NEXT", "Volgende"); // L47
define("BUTTON_FINISH", "Volgende");
define("BUTTON_REFRESH", "Vernieuwen");
define("BUTTON_SHOW_SITE", "Show my site");

// Messages
define("XOOPS_FOUND", "%s found");
define("CHECKING_PERMISSIONS", "Checking file and directory permissions..."); // L82
define("IS_NOT_WRITABLE", "%s is NOT writable."); // L83
define("IS_WRITABLE", "%s is writable."); // L84
define("ALL_PERM_OK", "All Permissions are correct.");

define("READY_CREATE_TABLES", "No ImpressCMS tables were detected.<br />The installer is now ready to create the ImpressCMS system tables.<br />Press <em>next</em> to proceed.");
define("XOOPS_TABLES_FOUND", "The ImpressCMS system tables already exists in your database.<br />Press <em>next</em> to go to the next step."); // L131
define("READY_INSERT_DATA", "The installer is now ready to insert initial data into your database.");
define("READY_SAVE_MAINFILE", "The installer is now ready to save the specified settings to <em>.env</em>.<br />Press <em>next</em> to proceed.");
define("DATA_ALREADY_INSERTED", "ImpressCMS data is stored in your database already. No further data will be stored by this action.<br />Press <em>next</em> to go to the next step.");

// %s is database name
define("DATABASE_CREATED", "Database %s created!"); // L43
// %s is table name
define("TABLE_NOT_CREATED", "Unable to create table %s"); // L118
define("TABLE_CREATED", "Tabel %s is aangemaakt."); // L45
define("ROWS_INSERTED", "%d entries inserted to table %s."); // L119
define("ROWS_FAILED", "Failed inserting %d entries to table %s."); // L120
define("TABLE_ALTERED", "Table %s updated."); // L133
define("TABLE_NOT_ALTERED", "Failed updating table %s."); // L134
define("TABLE_DROPPED", "Table %s dropped."); // L163
define("TABLE_NOT_DROPPED", "Failed deleting table %s."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Could not access the specified folder. Please verify that it exists and is readable by the server.");
define("ERR_NO_XOOPS_FOUND", "No ImpressCMS installation could be found in the specified folder.");
define("ERR_INVALID_EMAIL", "Ongeldige e-mail"); // L73
define("ERR_REQUIRED", "Vul alle verplichten velden in."); // L41
define("ERR_PASSWORD_MATCH", "De twee wachtwoorden komen niet overeen");
define("ERR_NEED_WRITE_ACCESS", "De server moet schrijftoegang krijgen tot de volgende bestanden en mappen<br />(d.w.z. <em>chmod 777 map_name</em> op een UNIX/LINUX server)"); // L72
define("ERR_NO_DATABASE", "Kan de database niet aanmaken. Neem voor meer informatie contact op met de serverbeheerder."); // L31
define("ERR_NO_DBCONNECTION", "Kan geen verbinding maken met de databaseserver."); // L106
define("ERR_WRITING_CONSTANT", "Schrijven constante %s mislukt."); // L122
define('ERR_WRITE_ENV_DATA', 'Error write .env data');
define("ERR_INVALID_DBCHARSET", "The charset '%s' is not supported.");
define("ERR_INVALID_DBCOLLATION", "The collation '%s' is not supported.");
define("ERR_CHARSET_NOT_SET", "Default character set is not set for ImpressCMS database.");

//
define("_INSTALL_SELECT_MODS_INTRO", 'From the list below, please select the modules that you wish to install on this site. <br /><br />
All the installed modules are accessible by the Administrators group and the Registered Users group by default. <br /><br />
If you need to set permissions for Anonymous Users please do so in the Administration Panel after you complete this installer. <br /><br />
For more information regarding Group Administration, please visit the <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Select modules to be installed');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Select modules visible to visitors');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Module %s could not be installed.");
define("_INSTALL_ERRORS", 'Fouten');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Module %s has already been installed");
define("_INSTALL_FAILED_TO_EXECUTE", "Failed to execute ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Executed correctly");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s has been installed succesfully.");
define("_INSTALL_MOD_INSTALL_FAILED", "The wizard could not install module %s.");
define("_INSTALL_INSTALLING", "Installing %s module");

define("_INSTALL_WEB_LOCATIONS", "Web location");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Web location");

define("_INSTALL_COULD_NOT_INSERT", "The wizard was unable to install module %s the database.");
define("_INSTALL_CHARSET", "utf-8");

define("_INSTALL_PHYSICAL_PATH", "Physical path");

define("_MD_AM_MULTLOGINMSG_TXT", 'Het was niet mogelijk in te loggen op de website!! <br />
        <p align="left" style="color:red;">
        Mogelijke oorzaken:<br />
         - U bent reeds ingelogd op de website.<br />
         - Iemand anders is ingelogd op de website en gebruikt uw gebruikernaam en wachtwoord.<br />
         - U heeft de website verlaten of uw browser afgesloten zonder uit te loggen.<br />
        </p>
        Wacht een paar minuten en probeer het nogmaals. Wanneer het probleem zich nog voordoet neem dan contact op met de beheerder van de website.');
define("_INSTALL_LOCAL_SITE", 'http://www.impresscms.be/'); //Link to local support site
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_ADM_USE_RTL", "0"); // turn this to 1 if your language is right to left

######################## Added in 1.2 ###################################
define("ADMIN_DISPLAY_LABEL", "Admin Display Name"); // L37
define('_CORE_PASSLEVEL1', 'Te kort');
define('_CORE_PASSLEVEL2', 'Zwak');
define('_CORE_PASSLEVEL3', 'Goed');
define('_CORE_PASSLEVEL4', 'Sterk');
define('DB_PCONNECT_HELP', "Persistent connections are useful with slower internet connections. They are not generally required for most installations. Default is 'NO'. Choose 'NO' if you are unsure"); // L69
define("DB_PCONNECT_HELPS", "Persistent connections are useful with slower internet connections. They are not generally required for most installations."); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "File Permissions");
