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

define( "READY_CREATE_TABLES", "Er werden geen ImpressCMS tabellen gedetecteerd.<br />Het installatieprogramma zal hierna de ImpressCMS systeem tabellen aanmaken.<br />Klik <em>Volgende</em> om door te gaan." );
define( "XOOPS_TABLES_FOUND", "ImpressCMS systeem tabellen bestaan reeds in de databank.<br />Klik <em>Volgende</em> om door te gaan." );
define( "READY_INSERT_DATA", "Het installatieprogramma zal hierna de ImpressCMS systeem tabellen vullen met startdata.<br />Klik <em>Volgende</em> om door te gaan." );
define( "READY_SAVE_MAINFILE", "Het installatieprogramma zal hierna de configuratie opslaan in <em>mainfile.php</em>.<br />Klik <em>Volgende</em> om door te gaan." );
define( "DATA_ALREADY_INSERTED", "ImpressCMS gegevens zijn reeds aanwezig in de databank. Geen extra data zal worden toegevoegd.<br />Klik <em>Volgende</em> om door te gaan." );

// %s is database name
define( "DATABASE_CREATED", "Database %s aangemaakt!" ); // L43
// %s is table name
define( "TABLE_NOT_CREATED", "Kan %s niet aanmaken" ); // L118
define( "TABLE_CREATED", "Tabel %s is aangemaakt." ); // L45
define( "ROWS_INSERTED", "%d rijen werden toegevoegd aan tabel %s." ); // L119
define( "ROWS_FAILED", "%d rijen konden niet worden toegevoegd aan tabel %s." ); // L120
define( "TABLE_ALTERED", "Tabel %s aangepast."); // L133
define( "TABLE_NOT_ALTERED", "tabel %s kon niet worden aangepast."); // L134
define( "TABLE_DROPPED", "Tabel %s verwijderd."); // L163
define( "TABLE_NOT_DROPPED", "tabel %s kon niet worden verwijderd."); // L164

// Error messages
define( "ERR_COULD_NOT_ACCESS", "Geen toegang tot de aangegeven map. Controleer of die bestaat en of de server leesrechten heeft." );
define( "ERR_NO_XOOPS_FOUND", "Er kon geen ImpressCMS installatie gevonden worden in de aangegeven map ." );
define( "ERR_INVALID_EMAIL", "Ongeldig email adres" ); // L73
define( "ERR_REQUIRED", "Geef aub alle vereiste informatie." ); // L41
define( "ERR_PASSWORD_MATCH", "De 2 paswoorden komen niet overeen" );
define( "ERR_NEED_WRITE_ACCESS", "De server moet schrijftoegang krijgen tot de volgende bestanden en mappen<br />(i.e. <em>chmod 777 map_naam</em> op een UNIX/LINUX server)" ); // L72
define( "ERR_NO_DATABASE", "De databank kon niet aangemaakt worden. Contacteer uw serverbeheerder voor meer details." ); // L31
define( "ERR_NO_DBCONNECTION", "Kon niet verbinden met de database server." ); // L106
define( "ERR_WRITING_CONSTANT", "Waarde %s kon niet weggeschreven worden." ); // L122

define( "ERR_COPY_MAINFILE", "Het distributiebestand kon niet gekopieerd worden naar mainfile.php" );
define( "ERR_WRITE_MAINFILE", "Er kon niet geschreven worden in mainfile.php. Controleer de toegangsrechten en probeer opnieuw.");
define( "ERR_READ_MAINFILE", "mainfile.php kon niet gelezen worden" );

define( "ERR_WRITE_SDATA", "Er kon niet geschreven wroden naar sdata.php. Controleer de toegangsrechten en probeer opnieuw.");
define( "ERR_READ_SDATA", "sdata.php kon niet gelezen worden" );
define( "ERR_INVALID_DBCHARSET", "De karakterset '%s' is niet ondersteund." );
define( "ERR_INVALID_DBCOLLATION", "De collation '%s' word niet ondersteund." );
define( "ERR_CHARSET_NOT_SET", "Standaard karakterset is niet gedefiniëerd voor de ImpressCMS database." );

//
define("_INSTALL_SELECT_MODS_INTRO", 'Kies  uit onderstaande lijst de modules die moeten geïnstalleerd worden op deze site. <br /><br />
Al de geïnstalleerde modules zijn toegankelijk standaard toegangkelijk voor de gebruikers in de Administrators en Registered Users groepen. <br /><br />
Toegangen geven aan anonieme gebruikers kan in het Administratiepaneel na het uitvoeren van het installatieprogramma. <br /><br />
Meer informatie rond groepen beheer kan u vinden op de <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Kies de modules om te installeren');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Kies modules die zichtbaar zijn voor bezoekers');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Module %s kon niet worden geïnstalleerd.");
define("_INSTALL_ERRORS", 'Foutmeldingen');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Module %s is reeds geïnstalleerd");
define("_INSTALL_FAILED_TO_EXECUTE", "Kon niet uitvoeren ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Correct uitgevoerd");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s werd geïnstalleerd.");
define("_INSTALL_MOD_INSTALL_FAILED", "De wizard kon module %s niet installeren.");
define("_INSTALL_NO_PLUS_MOD", "Er werden geen modules geselecteerd voor instalatie. Ga verder door op Volgende te klikken.");
define("_INSTALL_INSTALLING", "Installatie van module %s");

define("_INSTALL_TRUST_PATH", "Trust path");
define("_INSTALL_TRUST_PATH_LABEL", "ImpressCMS fysiek trust path");
define("_INSTALL_WEB_LOCATIONS", "Web locatie");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Web locatie");

define("_INSTALL_TRUST_PATH_FOUND", "Trust path gevonden.");
define("_INSTALL_ERR_NO_TRUST_PATH_FOUND", "Trust path werd niet gevonden.");

define("_INSTALL_COULD_NOT_INSERT", "De wizard kon de data voor module %s niet toevoegen aan de database.");
define("_INSTALL_CHARSET","utf-8");

define("_INSTALL_PHYSICAL_PATH","Fysische locatie");

define("TRUST_PATH_VALIDATE","Hierboven een voorgestelde naam voor het Trust Path. Indien u een alternatieve naam of locatie wil, pas die dan daar aan.<br /><br />Om verder te gaan, klik op de 'Trustpath Aanmaken' knop.");
define("TRUST_PATH_NEED_CREATED_MANUALLY","Het trust path kon niet worden aangemaakt. Maak het handmatig aan en ververs uw browserpagina.");
define("BUTTON_CREATE_TUST_PATH","Trust Path Aanmaken");
define("TRUST_PATH_SUCCESSFULLY_CREATED", "Het trust path werd aangemaakt.");

// welcome custom blocks
define("WELCOME_WEBMASTER","Welkom Webmaster !");
define("WELCOME_ANONYMOUS","Welkom op een website mogelijk gemaakt door ImpressCMS!");
define("_MD_AM_MULTLOGINMSG_TXT",'Niet mogelijk om in te loggen op de site!! <br />
        <p align="left" style="color:red;">
        Mogelijke oorzaken:<br />
         - U bent reeds ingelogd op de site.<br />
         - Iemand anders is ingelogd op de site met dit gebruikersnaam en paswoord.<br />
         - U hebt de site verlaten zonder uit te loggen.<br />
        </p>
        Probeer over enkele minuten opnieuw. Als het probleem zich blijft voordoen, contacteer dan de site beheerder.');
define("_MD_AM_RSSLOCALLINK_DESC",'https://www.impresscms.org/modules/news/rss.php'); //Link to the rrs of local support site
define("_INSTALL_LOCAL_SITE",'https://www.impresscms.org/'); //Link to local support site
define("_LOCAOL_STNAME",'ImpressCMS'); //Link to local support site
define("_LOCAL_SLOCGAN",'Maak een blijvende indruk'); //Link to local support site
define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">Het ImpressCMS Project</a>'); //footer Link to local support site
define("_LOCAL_SENSORTXT",'#OOPS#'); //Add local translation
define("_ADM_USE_RTL","0"); // turn this to 1 if your language is right to left
define("_DEF_LANG_TAGS",'en,de,nl'); //Add local translation
define("_DEF_LANG_NAMES",'english,german,dutch'); //Add local translation
define("_LOCAL_LANG_NAMES",'English,Deutsch,Nederlands'); //Add local translation
define("_EXT_DATE_FUNC","0"); // change 0 to 1 if this language has an extended date function

######################## Added in 1.2 ###################################
define( "ADMIN_DISPLAY_LABEL", "Beheerder weergavenaam" ); // L37
define('_CORE_PASSLEVEL1','Te kort');
define('_CORE_PASSLEVEL2','Zwak');
define('_CORE_PASSLEVEL3','Goed');
define('_CORE_PASSLEVEL4','Sterk');
define('DB_PCONNECT_HELP', "Blijvende connecties kunnen interessant zijn voor tragere internet verbindingen. Ze zijn voor de meeste installaties niet nodig. Standaard is \'Neen\', kies \'Neen\' als u niet zeker bent.); // L69
define( 'DB_PCONNECT_HELPS',  'Blijvende connecties kunnen interessant zijn voor tragere internet verbindingen. Ze zijn voor de meeste installaties niet nodig.'); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "Bestandsrechten");
