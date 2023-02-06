<?php
/**
 * Installer main english strings declaration file.
 * @copyright	The ImpressCMS project https://www.impresscms.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author       Skalpa Keo <skalpa@xoops.org>
 * @author       Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
 * @since        1.0
 * @version		$Id: install.php 12168 2013-05-22 13:25:59Z skenow $
 * @package 		installer
 */

define( "SHOW_HIDE_HELP", "Show/hide help text" );

define ("ALTERNATE_LANGUAGE_MSG","Download een alternatief taalpakket van de ImpressCMS website");
define ("ALTERNATE_LANGUAGE_LNK_MSG", "Kies een taal die niet in de lijst staat.");
define ("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");
// Configuration check page
define( "SERVER_API", "Server API" );
define( "PHP_EXTENSION", "%s extension" );
define( "CHAR_ENCODING", "Character encoding" );
define( "XML_PARSING", "XML parsing" );
define( "REQUIREMENTS", "Vereisten" );
define( "_PHP_VERSION", "PHP versie" );
define( "RECOMMENDED_SETTINGS", "Aanbevolen instellingen" );
define( "RECOMMENDED_EXTENSIONS", "Aanbevolen extensies" );
define( "SETTING_NAME", "Instelling benaming" );
define( "RECOMMENDED", "Aanbevolen" );
define( "CURRENT", "Huidig" );
define( "RECOMMENDED_EXTENSIONS_MSG", "Deze extensies zijn niet vereist voor gewoon gebruik, maar kunnen nodig zijn om bepaalde functionaliteiten (zoals ondersteuning voor meerdere talen of RSS ondersteuning). Het is daardoor aanbevolen ze geïnstalleerd te hebben." );
define( "NONE", "Geen" );
define( "SUCCESS", "Succes" );
define( "WARNING", "Waarschuwing" );
define( "FAILED", "Gefaald" );

// Titles (main and pages)
define( "XOOPS_INSTALL_WIZARD", " %s - Installatie Wizard" );
define( "INSTALL_STEP", "Stap" );
define( "INSTALL_H3_STEPS", "Stappen" );
define( "INSTALL_OUTOF", " van " );
define( "INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"https://www.impresscms.org\" target=\"_blank\">Het ImpressCMS Project</a>" );

define( "LANGUAGE_SELECTION", "Taalkeuze" );
define( "LANGUAGE_SELECTION_TITLE", "Kies uw taal");		// L128
define( "INTRODUCTION", "Inleiding" );
define( "INTRODUCTION_TITLE", "Welcome bij de ImpressCMS installatie assistent" );		// L0
define( "CONFIGURATION_CHECK", "Configuratie controle" );
define( "CONFIGURATION_CHECK_TITLE", "Controle van uw server instellingen" );
define( "PATHS_SETTINGS", "Paden instellingen" );
define( "PATHS_SETTINGS_TITLE", "Paden instellingen" );
define( "DATABASE_CONNECTION", "Database verbinding" );
define( "DATABASE_CONNECTION_TITLE", "Database verbinding" );
define( "DATABASE_CONFIG", "Database instellingen" );
define( "DATABASE_CONFIG_TITLE", "Database instellingen" );
define( "CONFIG_SAVE", "Configuratie opslaan" );
define( "CONFIG_SAVE_TITLE", "Uw systeem configuratie opslaan" );
define( "TABLES_CREATION", "Tabellen aanmaken" );
define( "TABLES_CREATION_TITLE", "Database tabellen aanmaken" );
define( "INITIAL_SETTINGS", "Start instellingen" );
define( "INITIAL_SETTINGS_TITLE", "Geef uw begin instellingen in" );
define( "DATA_INSERTION", "Data toevoegen" );
define( "DATA_INSERTION_TITLE", "Uw instellingen worden opgeslagen in de database" );
define( "WELCOME", "Welkome" );
define( "NO_PHP5", "Geen PHP 7" );
define( "WELCOME_TITLE", "Installatie van ImpressCMS compleet" );		// L0
define( "MODULES_INSTALL", "Installatie van modules" );
define( "MODULES_INSTALL_TITLE", "Installatie van modules " );
define( "NO_PHP5_TITLE", "Geen PHP 7" );
define( "NO_PHP5_CONTENT","PHP 7.0.0 is minimaal vereist (PHP 7.4+ is sterk aanbevolen) om ImpressCMS correct te laten werken. De installatie kan niet verder gaan. Contacteer aub uw hosting aanbieder om uw PHP te upgraden naar een versie die nieuwer is dan 7.0 (PHP 7.4+ is aanbevolen) vooraleer de installatie opnieuw op te starten.");
define( "SAFE_MODE", "Safe Modus aan" );
define( "SAFE_MODE_TITLE", "Safe Modus aan" );
define( "SAFE_MODE_CONTENT", "ImpressCMS heeft vastgesteld dat PHP in 'safe modus' werkt. Hierdoor kan uw installatie niet verdergaan. Contacteer uw hosting aanbieder om uw PHP instellingen aan te passen vooraleer de installatie te herstarten." );

// Settings (labels and help text)
define( "XOOPS_ROOT_PATH_LABEL", "ImpressCMS documents root physical path" ); // L55
define( "XOOPS_ROOT_PATH_HELP", "Dit is de fysieke locatie van de ImpressCMS documenten folder, de basisfolder van uw ImpressCMS applicatie" ); // L59
define( "_INSTALL_TRUST_PATH_HELP", "Dit is de fysieke locatie van uw ImpressCMS Trust Path. Het Trust Path is de folder waar ImpressCMS en zijn modules gevoelige informatie opslaat voor bijkomende beveiliging. Het wordt sterk aangeraden om deze folder buiten de root folder te plaatsen, waardoor hij niet van de buitenwereld kan bereikt worden. Indien deze folder niet bestaat, zal ImpressCMS proberen hem aan te maken. Als dit niet mogelijk is, zal die handmatig moeten worden aangemaakt.<br /><br /><a target='_blank' href='https://www.impresscms.org/modules/simplywiki/index.php?page=Trust_Path'>Klik hier</a> voor meer informatie rond het Trust path." ); // L59

define( "XOOPS_URL_LABEL", "Website locatie (URL)" ); // L56
define( "XOOPS_URL_HELP", "Het basis URL dat zal gebruikt worden om uw ImpressCMS installatie te bereiken" ); // L58

define( "LEGEND_CONNECTION", "Server verbinding" );
define( "LEGEND_DATABASE", "Database" ); // L51

define( "DB_HOST_LABEL", "Server hostname" );	// L27
define( "DB_HOST_HELP",  "Hostname van de database server. Als u niet zeker bent, dan werkt <em>localhost</em> in de meeste gevallen"); // L67
define( "DB_USER_LABEL", "Gebruikersnaam" );	// L28
define( "DB_USER_HELP",  "Gebruikersnaam die wordt gebruikt voor toegang tot de database server"); // L65
define( "DB_PASS_LABEL", "Pasword" );	// L52
define( "DB_PASS_HELP",  "Pasword van uw database gebruikersaccount"); // L68
define( "DB_NAME_LABEL", "Database naam" );	// L29
define( "DB_NAME_HELP",  "De naam van de database op de database server.Het installatieprogramma zal proberen er een aan te maken als er geen database bestaat"); // L64
define( "DB_CHARSET_LABEL", "Database karakter set. We raden UTF-8 aan." );
define( "DB_CHARSET_HELP",  "MySQL includes character set support that enables you to store data using a variety of character sets and perform comparisons according to a variety of collations.");
define( "DB_COLLATION_LABEL", "Database collation" );
define( "DB_COLLATION_HELP",  "Een collation is een set regels die gebruikt worden om karakters te vergelijking.");
define( "DB_PREFIX_LABEL", "Tabel prefix" );	// L30
define( "DB_PREFIX_HELP",  "Deze prefix zal toegevoegd worden aan alle nieuwe tabellen die worden aangemaakt om zo naam conflicten te vermijden tussen meerdere ImpressCMS installaties in dezelfde database. Als je niet zeker bent, behoud wat is voorgesteld"); // L63
define( "DB_PCONNECT_LABEL", "Gebruik persistente verbinding" );	// L54

define( "DB_SALT_LABEL", "Password Salt Key" );	// L98
define( "DB_SALT_HELP",  "Deze salt key zal worden gebruikt om paswoorden te beveiligen, en is nodig om totaal unieke en veilige paswoorden te bekomen. Verander deze sleutel niet, anders worden alle paswoorden onmiddellijk niet meer geldig. In geval van twijfel, behoud de voorgestelde waarde"); // L97

define( "LEGEND_ADMIN_ACCOUNT", "Administrator account" );
define( "ADMIN_LOGIN_LABEL", "Admin login" ); // L37
define( "ADMIN_EMAIL_LABEL", "Admin e-mail" ); // L38
define( "ADMIN_PASS_LABEL", "Admin paswoord" ); // L39
define( "ADMIN_CONFIRMPASS_LABEL", "Bevestig paswoord" ); // L74
define( "ADMIN_SALT_LABEL", "Pasword Salt Key" ); // L99

// Buttons
define( "BUTTON_PREVIOUS", "Vorige" ); // L42
define( "BUTTON_NEXT", "Volgende" ); // L47
define( "BUTTON_FINISH", "Einde" );
define( "BUTTON_REFRESH", "Herlaad" );
define( "BUTTON_SHOW_SITE", "Toon mijn website" );

// Messages
define( "XOOPS_FOUND", "%s gevonden" );
define( "CHECKING_PERMISSIONS", "Controle bestand en folder toegangsrechten ..." ); // L82
define( "IS_NOT_WRITABLE", "%s is NIET schrijfbaar." ); // L83
define( "IS_WRITABLE", "%s is schrijfbaar." ); // L84
define( "ALL_PERM_OK", "Alle toegangen zijn in orde." );

define( "READY_CREATE_TABLES", "No ImpressCMS tables were detected.<br />The installer is now ready to create the ImpressCMS system tables.<br />Press <em>next</em> to proceed." );
define( "XOOPS_TABLES_FOUND", "The ImpressCMS system tables already exists in your database.<br />Press <em>next</em> to go to the next step." ); // L131
define( "READY_INSERT_DATA", "The installer is now ready to insert initial data into your database." );
define( "READY_SAVE_MAINFILE", "The installer is now ready to save the specified settings to <em>mainfile.php</em>.<br />Press <em>next</em> to proceed." );
define( "DATA_ALREADY_INSERTED", "ImpressCMS data is stored in your database already. No further data will be stored by this action.<br />Press <em>next</em> to go to the next step." );

// %s is database name
define( "DATABASE_CREATED", "Database %s created!" ); // L43
// %s is table name
define( "TABLE_NOT_CREATED", "Unable to create table %s" ); // L118
define( "TABLE_CREATED", "Table %s created." ); // L45
define( "ROWS_INSERTED", "%d entries inserted to table %s." ); // L119
define( "ROWS_FAILED", "Failed inserting %d entries to table %s." ); // L120
define( "TABLE_ALTERED", "Table %s updated."); // L133
define( "TABLE_NOT_ALTERED", "Failed updating table %s."); // L134
define( "TABLE_DROPPED", "Table %s dropped."); // L163
define( "TABLE_NOT_DROPPED", "Failed deleting table %s."); // L164

// Error messages
define( "ERR_COULD_NOT_ACCESS", "Could not access the specified folder. Please verify that it exists and is readable by the server." );
define( "ERR_NO_XOOPS_FOUND", "No ImpressCMS installation could be found in the specified folder." );
define( "ERR_INVALID_EMAIL", "Invalid Email" ); // L73
define( "ERR_REQUIRED", "Please enter all the required info." ); // L41
define( "ERR_PASSWORD_MATCH", "The two passwords do not match" );
define( "ERR_NEED_WRITE_ACCESS", "The server must be given write access to the following files and folders<br />(i.e. <em>chmod 777 directory_name</em> on a UNIX/LINUX server)" ); // L72
define( "ERR_NO_DATABASE", "Could not create database. Contact the server administrator for details." ); // L31
define( "ERR_NO_DBCONNECTION", "Could not connect to the database server." ); // L106
define( "ERR_WRITING_CONSTANT", "Failed writing constant %s." ); // L122

define( "ERR_COPY_MAINFILE", "Could not copy the distribution file to mainfile.php" );
define( "ERR_WRITE_MAINFILE", "Could not write into mainfile.php. Please check the file permission and try again.");
define( "ERR_READ_MAINFILE", "Could not open mainfile.php for reading" );

define( "ERR_WRITE_SDATA", "Could not write into sdata.php. Please check the file permission and try again.");
define( "ERR_READ_SDATA", "Could not open sdata.php for reading" );
define( "ERR_INVALID_DBCHARSET", "The charset '%s' is not supported." );
define( "ERR_INVALID_DBCOLLATION", "The collation '%s' is not supported." );
define( "ERR_CHARSET_NOT_SET", "Default character set is not set for ImpressCMS database." );

//
define("_INSTALL_SELECT_MODS_INTRO", 'From the list below, please select the modules that you wish to install on this site. <br /><br />
All the installed modules are accessible by the Administrators group and the Registered Users group by default. <br /><br />
If you need to set permissions for Anonymous Users please do so in the Administration Panel after you complete this installer. <br /><br />
For more information regarding Group Administration, please visit the <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Select modules to be installed');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Select modules visible to visitors');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Module %s could not be installed.");
define("_INSTALL_ERRORS", 'Errors');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Module %s has already been installed");
define("_INSTALL_FAILED_TO_EXECUTE", "Failed to execute ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Executed correctly");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s has been installed succesfully.");
define("_INSTALL_MOD_INSTALL_FAILED", "The wizard could not install module %s.");
define("_INSTALL_NO_PLUS_MOD", "No modules were selected for installation. Please continue the installation by clicking next.");
define("_INSTALL_INSTALLING", "Installing %s module");

define("_INSTALL_TRUST_PATH", "Trust path");
define("_INSTALL_TRUST_PATH_LABEL", "ImpressCMS physical trust path");
define("_INSTALL_WEB_LOCATIONS", "Web location");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Web location");

define("_INSTALL_TRUST_PATH_FOUND", "Trust path found.");
define("_INSTALL_ERR_NO_TRUST_PATH_FOUND", "Trust path was not found.");

define("_INSTALL_COULD_NOT_INSERT", "The wizard was unable to install module %s the database.");
define("_INSTALL_CHARSET","utf-8");

define("_INSTALL_PHYSICAL_PATH","Physical path");

define("TRUST_PATH_VALIDATE","A suggested name for the Trust path has been created above for you. If you wish to use an alternative name, please replace the above location with your choice of name.<br /><br />When done, please click on the Create Trust Path button.");
define("TRUST_PATH_NEED_CREATED_MANUALLY","It was not possible to create the trust path. Please create it manually and click on the following Refresh button.");
define("BUTTON_CREATE_TUST_PATH","Create Trust Path");
define("TRUST_PATH_SUCCESSFULLY_CREATED", "The trust path was successfully created.");

// welcome custom blocks
define("WELCOME_WEBMASTER","Welcome Webmaster !");
define("WELCOME_ANONYMOUS","Welcome to an ImpressCMS powered website !");
define("_MD_AM_MULTLOGINMSG_TXT",'It was not possible to login on the site!! <br />
        <p align="left" style="color:red;">
        Possible causes:<br />
         - You are already logged in on the site.<br />
         - Someone else logged in on the site using your username and password.<br />
         - You left the site or close the browser window without clicking the logout button.<br />
        </p>
        Wait a few minutes and try again later. If the problems still persists contact the site administrator.');
define("_MD_AM_RSSLOCALLINK_DESC",'https://www.impresscms.org/modules/news/rss.php'); //Link to the rrs of local support site
define("_INSTALL_LOCAL_SITE",'https://www.impresscms.org/'); //Link to local support site
define("_LOCAOL_STNAME",'ImpressCMS'); //Link to local support site
define("_LOCAL_SLOCGAN",'Make a lasting impression'); //Link to local support site
define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>'); //footer Link to local support site
define("_LOCAL_SENSORTXT",'#OOPS#'); //Add local translation
define("_ADM_USE_RTL","0"); // turn this to 1 if your language is right to left
define("_DEF_LANG_TAGS",'en,de'); //Add local translation
define("_DEF_LANG_NAMES",'english,german'); //Add local translation
define("_LOCAL_LANG_NAMES",'English,Deutsch'); //Add local translation
define("_EXT_DATE_FUNC","0"); // change 0 to 1 if this language has an extended date function

######################## Added in 1.2 ###################################
define( "ADMIN_DISPLAY_LABEL", "Admin Display Name" ); // L37
define('_CORE_PASSLEVEL1','Too short');
define('_CORE_PASSLEVEL2','Weak');
define('_CORE_PASSLEVEL3','Good');
define('_CORE_PASSLEVEL4','Strong');
define('DB_PCONNECT_HELP', "Persistent connections are useful with slower internet connections. They are not generally required for most installations. Default is 'NO'. Choose 'NO' if you are unsure"); // L69
define( "DB_PCONNECT_HELPS",  "Persistent connections are useful with slower internet connections. They are not generally required for most installations."); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "File Permissions");
