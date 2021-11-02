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

define("SHOW_HIDE_HELP", "Die Hilfe Anzeigen oder Verstecken");

define("ALTERNATE_LANGUAGE_MSG", "Laden Sie ein alternatives Sprachpaket von der ImpressCMS Website herunter.");
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
define("RECOMMENDED_EXTENSIONS_MSG", "Diese Erweiterungen sind nicht für den normalen Gebrauch erforderlich, aber sie können notwendig sein, um
	bestimmte Funktionen (wie die mehrsprachige oder RSS-Unterstützung) zu nutzen. Daher wird empfohlen, sie installiert zu haben." );
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
define("INTRODUCTION_TITLE", "Willkommen beim  ImpressCMS Installationsassistenten"); // L0
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
define("WELCOME_TITLE", "Installation von ImpressCMS abgeschlossen."); // L0
define("MODULES_INSTALL", "Module installieren");
define("MODULES_INSTALL_TITLE", "Die Installation der Module.");
define("NO_PHP5_TITLE", "Keine PHP 5");
define("NO_PHP5_CONTENT", "Für das einwandfreie Funktionieren von ImpressCMS  wird ein Minimum von PHP 5.2.0 benötigt - Ihre Installation kann nicht fortgesetzt werden. Bitte arbeiten Sie mit Ihrem Hosting-Provider zusammen, um Ihre Umgebung auf eine Version von PHP zu aktualisieren, die älter als 5 ist. .0 (5.2.8 + wird empfohlen) bevor Sie versuchen erneut zu installieren. Für weitere Informationen lesen Sie <a href='https://www.impresscms.org/modules/news/article.php?article_id=122' >ImpressCMS  unter PHP5 </a>.");
define("SAFE_MODE", "Sicherer Modus an");
define("SAFE_MODE_TITLE", "Sicherer Modus an");
define("SAFE_MODE_CONTENT", "ImpressCMS  hat festgestellt, dass PHP im Safe Mode läuft. Aus diesem Grund kann Ihre Installation nicht fortgesetzt werden. Bitte arbeiten Sie mit Ihrem Hosting-Provider zusammen, um Ihre Umgebung zu ändern, bevor Sie versuchen erneut zu installieren.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "ImpressCMS  - Pfad für Basisverzeichnis (Physikalischer Pfad)"); // L55
define("XOOPS_ROOT_PATH_HELP", "Dies ist der physikalische Pfad der ImpressCMS Dateien und Ordner, sozusagen das Basisverzeichnis Ihrer Anwendung. Man spricht hier auch von einem ImpressCMS Root-Verzeichnis."); // L59

define("XOOPS_URL_LABEL", "Virtueller Pfad (URL)"); // L56
define("XOOPS_URL_HELP", "Haupt-URL, die verwendet wird, um nach der Installtion den Zugriff auf das ImpressCMS  zu erhalten."); // L58

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
define("DB_COLLATION_HELP", "Wenn Sie unsicher sind, verwenden Sie die Voreinstellung. Die Klausel (auch Kollation genannt) gibt die Standardsortierfolge der Datenbank an.");
define("DB_PREFIX_LABEL", "Tabellenpräfix"); // L30
define("DB_PREFIX_HELP", "Dieser Präfix wird zu allen neuen Tabellen hinzugefügt, die erstellt wurden, um Namenskonflikte in der Datenbank zu vermeiden. Wenn Sie sich nicht sicher sind, behalten Sie einfach die Voreinstellung. Besonders wichtig ist dies, wenn eine Datenbank für mehrere Websites genutzt werden soll."); // L63
define("DB_PCONNECT_LABEL", "Brauchen Sie eine beständige Verbindung?"); // L54

define("DB_SALT_LABEL", "Kennwort - privater Schlüssel"); // L98
define("DB_SALT_HELP", "Der private Schlüssel wird als Kennwort in der Function icms_encryptPass() verwendet und erstellt, damit ein völlig einzigartiges und sicheres Kennwort für das CMS entsteht. Ändern Sie den Schlüssel nicht mehr, nachdem die Installation durchgeführt wurde, sonst werden damit alle Kennwörter ungültig. Wenn Sie sich unsicher sind, benutzen Sie die Voreinstellungen."); // L97

define("LEGEND_ADMIN_ACCOUNT", "Administrator Konto");
define("ADMIN_LOGIN_LABEL", "Admin-Login"); // L37
define("ADMIN_EMAIL_LABEL", "Admin-E-Mail"); // L38
define("ADMIN_PASS_LABEL", "Admin-Passwort"); // L39
define("ADMIN_CONFIRMPASS_LABEL", "Admin Kennwort wiederholen"); // L74
define("ADMIN_SALT_LABEL", "Kennwort - privater Schlüssel (Salt Key)"); // L99

// Buttons
define("BUTTON_PREVIOUS", "Zurück"); // L42
define("BUTTON_NEXT", "Weiter"); // L47
define("BUTTON_FINISH", "Ende");
define("BUTTON_REFRESH", "Neu laden");
define("BUTTON_SHOW_SITE", "Installation verlassen und Webseite jetzt anzeigen");

// Messages
define("XOOPS_FOUND", "%s wurde gefunden");
define("CHECKING_PERMISSIONS", "Überprüfe die Datei- und Verzeichnisberechtigungen..."); // L82
define("IS_NOT_WRITABLE", "%s ist NICHT beschreibbar."); // L83
define("IS_WRITABLE", "%s ist beschreibbar."); // L84
define("ALL_PERM_OK", "Alle Berechtigungen sind korrekt.");

define("READY_CREATE_TABLES", "Es wurden keine ImpressCMS Tabellen gefunden.<br />Der Installer ist nun bereit um die Tabellen fÃ¼r das ImpressCMS zu erstellen.<br />Klicken Sie auf <em>Weiter</em> fÃ¼r diesen Vorgang.");
define("XOOPS_TABLES_FOUND", "Die ImpressCMS Systemtabellen sind schon in der Datenbank vorhanden.<br />Klicken Sie auf <em>Weiter</em> für den nächsten Schritt."); // L131
define("READY_INSERT_DATA", "Das Installationsprogramm ist nun bereit, die Daten in die Datenbank zu schreiben.");
define("READY_SAVE_MAINFILE", "Der Installer ist nun bereit zum Speichern der Angaben in die <em>mainfile.php</em>.<br />Klicken Sie auf <em>Weiter</em> um diesen Schritt durchzuführen.");
define("DATA_ALREADY_INSERTED", "ImpressCMS Daten schon in der Datenbank vorhanden. Es werden <b>keine</b> weiteren Daten eingetragen.<br />Drücken Sie<em>Weiter</em> um fortzufahren.");

// %s is database name
define("DATABASE_CREATED", "Datenbank %s erstellt!"); // L43
// %s is table name
define("TABLE_NOT_CREATED", "Tabelle konnten nicht erstellt werden %s"); // L118
define("TABLE_CREATED", "Tabelle %s erstellt."); // L45
define("ROWS_INSERTED", "%d Einträge in die Tabelle %s hinzugefügt."); // L119
define("ROWS_FAILED", "%d Tabelleneinträge in Tabelle %s fehlgeschlagen."); // L120
define("TABLE_ALTERED", "Tabelle %s aktualisiert."); // L133
define("TABLE_NOT_ALTERED", "Aktualisierung von Tabelle %s fehlgeschlagen."); // L134
define("TABLE_DROPPED", "Tabelle %s gelöscht."); // L163
define("TABLE_NOT_DROPPED", "Löschen der Tabelle %s fehlgeschlagen."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Auf den Ordner kann nicht zugegriffen werden. Bitte Ã¼berprÃ¼fen Sie, ob der Ordner existitert und berschreibbar ist.");
define("ERR_NO_XOOPS_FOUND", "Im angegebenen Ordner konnte keine ImpressCMS Installation gefunden werden.");
define("ERR_INVALID_EMAIL", "Ungültige E-Mail"); // L73
define("ERR_REQUIRED", "Bitte geben Sie alle erforderlichen Informationen ein."); // L41
define("ERR_PASSWORD_MATCH", "Die zwei Passwörter stimmen nicht überein");
define("ERR_NEED_WRITE_ACCESS", "Dem Server muss Schreibzugriff auf die folgenden Dateien und Ordner<br />gewährt werden (z.B. <em>chmod 777 directory_name</em> auf einem UNIX/LINUX Server)"); // L72
define("ERR_NO_DATABASE", "Datenbank konnte nicht erstellt werden. Kontaktieren Sie den Server-Administrator für Details."); // L31
define("ERR_NO_DBCONNECTION", "Es konnte keine Verbindung zum Datenbank-Server hergestellt werden."); // L106
define("ERR_WRITING_CONSTANT", "%s konnte nicht geschrieben."); // L122
define('ERR_WRITE_ENV_DATA', 'Fehler beim Schreiben der .env Daten');
define("ERR_INVALID_DBCHARSET", "Das Zeichensatz '%s' wird nicht unterstützt.");
define("ERR_INVALID_DBCOLLATION", "Das Zeichensatz '%s' wird nicht unterstützt.");
define("ERR_CHARSET_NOT_SET", "Standardzeichensatz ist für die ImpressCMS Datenbank nicht gesetzt.");

//
define("_INSTALL_SELECT_MODS_INTRO", 'Wählen Sie aus der Liste unten bitte die Module aus, die Sie auf dieser Seite installieren möchten. <br /><br />
Alle installierten Module sind standardmäßig für die Administratorengruppe und die Registrierte Benutzergruppe zugänglich. <br /><br />
Wenn Sie die Berechtigungen für anonyme Benutzer festlegen müssen, tun Sie dies bitte im Administrationsbereich, nachdem Sie diesen Installer abgeschlossen haben. <br /><br />
Für weitere Informationen zur Gruppenadministration besuchen Sie bitte das <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">Wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Wählen Sie die Module, welche installiert werden sollen');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Wählen Sie die Module, welche für die Besucher sichbar sein sollen.');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Das Modul %s konnte nicht installiert werden.");
define("_INSTALL_ERRORS", 'Fehler');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Das Modul %s wurde erfolgreich installiert");
define("_INSTALL_FAILED_TO_EXECUTE", "Nicht korrekt ausgeführt ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Korrekt ausgeführt");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Modul %s wurde erfolgreich installiert.");
define("_INSTALL_MOD_INSTALL_FAILED", "Der Assistent konnte Modul %s nicht installieren.");
define("_INSTALL_INSTALLING", "Installiere %s Modul");

define("_INSTALL_WEB_LOCATIONS", "Adresse im Internet");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Adresse im Internet");

define("_INSTALL_COULD_NOT_INSERT", "Der Assistent konnte Modul %s nicht in der Datenbank installieren.");
define("_INSTALL_CHARSET", "utf-8");

define("_INSTALL_PHYSICAL_PATH", "Physikalisches Verzeichnis");

define("_MD_AM_MULTLOGINMSG_TXT", 'Es war nicht möglich, sich auf der Website anzumelden!! <br />
        <p align="left" style="color:red;">
        Mögliche Ursachen:<br />
         - Sie sind bereits eingeloggt.<br />
         - Jemand anderes hat sich mit Ihrem Benutzernamen und Passwort auf der Website eingeloggt.<br />
         - Sie haben die Seite verlassen oder das Browser-Fenster geschlossen, ohne auf den Logout-Button zu klicken.<br />
        </p>
        Warte einige Minuten und versuche es später erneut. Wenn die Probleme weiterhin bestehen, kontaktieren Sie den Administrator.');
define("_INSTALL_LOCAL_SITE", 'https://www.impresscms.org/'); //Link to local support site
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_ADM_USE_RTL", "0"); // turn this to 1 if your language is right to left

######################## Added in 1.2 ###################################
define("ADMIN_DISPLAY_LABEL", "Angezeigter Name"); // L37
define('_CORE_PASSLEVEL1', 'Zu kurz');
define('_CORE_PASSLEVEL2', 'Schwach');
define('_CORE_PASSLEVEL3', 'Gut');
define('_CORE_PASSLEVEL4', 'Stark');
define('DB_PCONNECT_HELP', "Eine beständige Verbindung ist nur bei sehr langsamen Internetverbindungen nützlich. In den meisten Fällen ist das nicht erfolderlich, deshalb ist standardmässig 'Nein' gewälht. Wählen Sie auch 'Nein' wenn Sie unsicher sind."); // L69
define("DB_PCONNECT_HELPS", "Eine beständige Verbindung ist nur bei sehr langsamen Internetverbindungen nüzlich. Daher ist diese Option nicht erfolderlich"); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "Datei-Berechtigungen");
