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

define("SHOW_HIDE_HELP", "die Hilfe Anzeigen oder Verstecken");

define("ALTERNATE_LANGUAGE_MSG", "Laden Sie ein alternatives Sprachpaket von der ImpressCMS Website herunter");
define("ALTERNATE_LANGUAGE_LNK_MSG", "Wählen Sie eine andere Sprache, die hier nicht aufgeführt ist.");
define("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");

// Configuration check page
define("SERVER_API", "Server Verbindung");
define("PHP_EXTENSION", "%s Erweiterung");
define("CHAR_ENCODING", "Zeichenkodierung");
define("XML_PARSING", "XML parsing");
define("REQUIREMENTS", "Anforderungen");
define("_PHP_VERSION", "PHP Version");
define("RECOMMENDED_SETTINGS", "Empfohlene Einstellungen");
define("RECOMMENDED_EXTENSIONS", "Empfohlene Erweiterungen");
define("SETTING_NAME", "Setting name");
define("RECOMMENDED", "Empfohlen");
define("CURRENT", "Aktuell");
define("RECOMMENDED_EXTENSIONS_MSG", "Diese Erweiterungen sind nicht für den normalen Gebrauch erforderlich, aber sie können notwendig sein, um bestimmte Funktionen (wie die mehrsprachige oder RSS-Unterstützung) zu nutzen. Daher wird empfohlen, sie installiert zu haben." );
define("NONE", "Nichts");
define("SUCCESS", "Vorhanden");
define("WARNING", "Warnung");
define("FAILED", "Fehlt");

// Titles (main and pages)
define("XOOPS_INSTALL_WIZARD", " %s - Installationsassistent");
define("INSTALL_STEP", "Schritt");
define("INSTALL_H3_STEPS", "Schritte");
define("INSTALL_OUTOF", " von ");
define("INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"https://www.impresscms.org\" target=\"_blank\">The ImpressCMS Project</a>");

define("LANGUAGE_SELECTION", "Sprache auswählen");
define("LANGUAGE_SELECTION_TITLE", "Wählen Sie Ihre Sprache"); // L128
define("INTRODUCTION", "Vorbereitungen/Info");
define("INTRODUCTION_TITLE", "Willkommen beim ImpressCMS Installationsassistenten"); // L0
define("CONFIGURATION_CHECK", "Konfigurations-Überprüfung");
define("CONFIGURATION_CHECK_TITLE", "Überprüfe die Serverkonfiguration");
define("PATHS_SETTINGS", "Verzeichnisse");
define("PATHS_SETTINGS_TITLE", "Verzeichnisse");
define("DATABASE_CONNECTION", "Datenbankverbindung");
define("DATABASE_CONNECTION_TITLE", "Datenbank-Verbindung");
define("DATABASE_CONFIG", "Datenbank-Konfiguration");
define("DATABASE_CONFIG_TITLE", "Datenbank-Konfiguration");
define("CONFIG_SAVE", "Konfiguration speichern");
define("CONFIG_SAVE_TITLE", "Speichern Sie Ihre System-Konfiguration");
define("TABLES_CREATION", "Datenbank-Tabellen");
define("TABLES_CREATION_TITLE", "Datenbank-Tabellen werden erstellt");
define("INITIAL_SETTINGS", "Erste Einstellungen");
define("INITIAL_SETTINGS_TITLE", "Bitte geben Sie hier Ihre Daten ein");
define("DATA_INSERTION", "Datenbank füllen");
define("DATA_INSERTION_TITLE", "Speichern Ihrer Einstellungen in der Datenbank");
define("WELCOME", "Willkommen");
define("NO_PHP5", "Keine PHP 5");
define("WELCOME_TITLE", "Installation von ImpressCMS abgeschlossen"); // L0
define("MODULES_INSTALL", "Module installieren");
define("MODULES_INSTALL_TITLE", "Die Installation der Module");
define("NO_PHP5_TITLE", "Keine PHP 5");
define("NO_PHP5_CONTENT", "Für das einwandfreie Funktionieren von ImpressCMS wird ein Minimum von PHP 5.2.0 benötigt - Ihre Installation kann nicht fortgesetzt werden. Bitte arbeiten Sie mit Ihrem Hosting-Provider zusammen, um Ihre Umgebung auf eine Version von PHP zu aktualisieren, die älter als 5 ist. .0 (5.2.8 + wird empfohlen) bevor Sie versuchen erneut zu installieren. Für weitere Informationen lesen Sie <a href='https://www.impresscms.org/modules/news/article.php?article_id=122' >ImpressCMS unter PHP5 </a>.");
define("SAFE_MODE", "Sicherer Modus an");
define("SAFE_MODE_TITLE", "Sicherer Modus an");
define("SAFE_MODE_CONTENT", "ImpressCMS hat festgestellt, dass PHP im Safe Mode läuft. Aus diesem Grund kann Ihre Installation nicht fortgesetzt werden. Bitte arbeiten Sie mit Ihrem Hosting-Provider zusammen, um Ihre Umgebung zu ändern, bevor Sie versuchen erneut zu installieren.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "ImpressCMS - Pfad für Basisverzeichnis (Physikalischer Pfad)"); // L55
define("XOOPS_ROOT_PATH_HELP", "Dies ist der physikalische Pfad der ImpressCMS Dateien und Ordner, sozusagen das Basisverzeichnis Ihrer Anwendung. Man spricht hier auch von einem ImpressCMS Root-Verzeichnis."); // L59

define("XOOPS_URL_LABEL", "Virtueller Pfad (URL)"); // L56
define("XOOPS_URL_HELP", "Haupt-URL, die verwendet wird, um nach der Installtion den Zugriff auf das ImpressCMS zu erhalten."); // L58

define("LEGEND_CONNECTION", "Serververbindung");
define("LEGEND_DATABASE", "Datenbank Art"); // L51

define("DB_HOST_LABEL", "Server Hostname"); // L27
define("DB_HOST_HELP", "Hostname des Datenbankservers. Wenn Sie unsicher sind, <em>localhost</em> funktioniert in den meisten Fällen"); // L67
define("DB_USER_LABEL", "Benutzername"); // L28
define("DB_USER_HELP", "Das ist der Username Ihres Datenbank-Kontos"); // L65
define("DB_PASS_LABEL", "Passwort"); // L52
define("DB_PASS_HELP", "Das ist das Kennwort welches verlangt wird, um auf Ihre Datenbank zuzugreifen."); // L68
define("DB_NAME_LABEL", "Datenbankname"); // L29
define("DB_NAME_HELP", "Das ist der Name Ihrer Datenbank in der die Tabellen erstellt werden. Der Installer wird versuchen, eine Datenbank zu erstellen, wenn diese noch nicht existiert."); // L64
define("DB_CHARSET_LABEL", "Datenbank-Zeichensatz, wir empfehlen Ihnen die Verwendung von UTF-8 als Standard.");
define("DB_CHARSET_HELP", "MySQL beinhaltet die Unterstützung für Zeichensätze, die es Ihnen ermöglicht, Daten mit verschiedenen Zeichensätzen zu speichern und Vergleiche nach verschiedenen Sortierfolgen durchzuführen.");
define("DB_COLLATION_LABEL", "Datenbank Klausel");
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
define("BUTTON_PREVIOUS", "Previous"); // L42
define("BUTTON_NEXT", "Next"); // L47
define("BUTTON_FINISH", "Finish");
define("BUTTON_REFRESH", "Refresh");
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
define("TABLE_CREATED", "Table %s created."); // L45
define("ROWS_INSERTED", "%d entries inserted to table %s."); // L119
define("ROWS_FAILED", "Failed inserting %d entries to table %s."); // L120
define("TABLE_ALTERED", "Table %s updated."); // L133
define("TABLE_NOT_ALTERED", "Failed updating table %s."); // L134
define("TABLE_DROPPED", "Table %s dropped."); // L163
define("TABLE_NOT_DROPPED", "Failed deleting table %s."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Could not access the specified folder. Please verify that it exists and is readable by the server.");
define("ERR_NO_XOOPS_FOUND", "No ImpressCMS installation could be found in the specified folder.");
define("ERR_INVALID_EMAIL", "Invalid Email"); // L73
define("ERR_REQUIRED", "Please enter all the required info."); // L41
define("ERR_PASSWORD_MATCH", "The two passwords do not match");
define("ERR_NEED_WRITE_ACCESS", "The server must be given write access to the following files and folders<br />(i.e. <em>chmod 777 directory_name</em> on a UNIX/LINUX server)"); // L72
define("ERR_NO_DATABASE", "Could not create database. Contact the server administrator for details."); // L31
define("ERR_NO_DBCONNECTION", "Could not connect to the database server."); // L106
define("ERR_WRITING_CONSTANT", "Failed writing constant %s."); // L122
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
define("_INSTALL_ERRORS", 'Errors');
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

define("_MD_AM_MULTLOGINMSG_TXT", 'It was not possible to login on the site!! <br />
        <p align="left" style="color:red;">
        Possible causes:<br />
         - You are already logged in on the site.<br />
         - Someone else logged in on the site using your username and password.<br />
         - You left the site or close the browser window without clicking the logout button.<br />
        </p>
        Wait a few minutes and try again later. If the problems still persists contact the site administrator.');
define("_INSTALL_LOCAL_SITE", 'https://www.impresscms.org/'); //Link to local support site
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_ADM_USE_RTL", "0"); // turn this to 1 if your language is right to left

######################## Added in 1.2 ###################################
define("ADMIN_DISPLAY_LABEL", "Admin Display Name"); // L37
define('_CORE_PASSLEVEL1', 'Too short');
define('_CORE_PASSLEVEL2', 'Weak');
define('_CORE_PASSLEVEL3', 'Good');
define('_CORE_PASSLEVEL4', 'Strong');
define('DB_PCONNECT_HELP', "Persistent connections are useful with slower internet connections. They are not generally required for most installations. Default is 'NO'. Choose 'NO' if you are unsure"); // L69
define("DB_PCONNECT_HELPS", "Persistent connections are useful with slower internet connections. They are not generally required for most installations."); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "File Permissions");
