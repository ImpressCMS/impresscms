<?php
/**
* Installer main english strings declaration file.
* @copyright	The ImpressCMS project http://www.impresscms.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU public license
* @author       Skalpa Keo <skalpa@xoops.org>
* @author       Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
* @since        1.0
* @version		$Id: install.php 9684 2009-12-28 12:30:09Z sato $
* @package 		installer
*/

define("SHOW_HIDE_HELP", "Toon/verberg help tekst");

// Configuration check page
define("SERVER_API", "Server API");
define("PHP_EXTENSION", "%s extensie");
define("CHAR_ENCODING", "Character encoding");
define("XML_PARSING", "XML parsing");
define("OPEN_ID", "OpenID");
define("REQUIREMENTS", "Systeemeissen");
define("_PHP_VERSION", "PHP versie");
define("RECOMMENDED_SETTINGS", "Aanbevolen instellingen");
define("RECOMMENDED_EXTENSIONS", "Aanbevolen extensies");
define("SETTING_NAME", "Naam instelling");
define("RECOMMENDED", "Aanbevolen");
define("CURRENT", "Huidige");
define("RECOMMENDED_EXTENSIONS_MSG", "Deze extensies zijn niet vereist voor normaal gebruik, ze kunnen noodzakelijk zijn om 
sommige specifieke opties (zoals de meerdere-talen of RSS ondersteuning) te kunnen gebruiken. Vandaar, dat het s aanbevolen om ze te installeren.");
define("NONE", "Geen");
define("SUCCESS", "Succesvol");
define("WARNING", "Waarschuwing");
define("FAILED", "Mislukt");



// Titles (main and pages)
define("XOOPS_INSTALL_WIZARD", " %s - Installatie assistent");
define("INSTALL_STEP", "Stap");
define("INSTALL_H3_STEPS", "Stappen");
define("INSTALL_OUTOF", " van ");
define("INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"http://www.impresscms.org\" target=\"_blank\">The ImpressCMS Project</a>");

define("LANGUAGE_SELECTION", "Taalselectie");
define("LANGUAGE_SELECTION_TITLE", "Selecteer uw taal");		// L128
define("INTRODUCTION", "Introductie");
define("INTRODUCTION_TITLE", "Welkom in de ImpressCMS installatie assistent");		// L0
define("CONFIGURATION_CHECK", "Configuratie controle");
define("CONFIGURATION_CHECK_TITLE", "Uw serverinstellingen worden gecontroleerd");
define("PATHS_SETTINGS", "Pad instellingem");
define("PATHS_SETTINGS_TITLE", "Pad instellingen");
define("DATABASE_CONNECTION", "Database verbinding");
define("DATABASE_CONNECTION_TITLE", "Database verbinding");
define("DATABASE_CONFIG", "Database instellingen");
define("DATABASE_CONFIG_TITLE", "Database instellingen");
define("CONFIG_SAVE", "Instellingen opslaan");
define("CONFIG_SAVE_TITLE", "Systeeminstellingen opslaan");
define("TABLES_CREATION", "Tabellen aanmaken");
define("TABLES_CREATION_TITLE", "Database tabellen aanmaken");
define("INITIAL_SETTINGS", "Begin instellingen");
define("INITIAL_SETTINGS_TITLE", "Begin instellingen invullen");
define("DATA_INSERTION", "Data invoegen");
define("DATA_INSERTION_TITLE", "Instellingen opslaan in de database");
define("WELCOME", "Welkom");
define("NO_PHP5", "Geen PHP 5");
define("WELCOME_TITLE", "De installatie van ImpressCMS is afgerond");		// L0
define("MODULES_INSTALL", "Modules installeren");
define("MODULES_INSTALL_TITLE", "Installeren van modules ");
define("NO_PHP5_TITLE", "Geen PHP 5");
define("NO_PHP5_CONTENT", "PHP 5.2.0 is vereist om ImpressCMS correct te laten functioneren - de installatie kan niet verder gaan. Neem contact op met uw hosting provider om uw webomgeving op te waarderen naar 5.2.0 (5.2.8 + is aangeraden) voordat u nogmaals een installatie poging onderneemt. Voor meer informatie, lees <a href='http://community.impresscms.org/modules/smartsection/item.php?itemid=122' >ImpressCMS op PHP5 </a>.");
define("SAFE_MODE", "Safe Mode On");
define("SAFE_MODE_TITLE", "Safe Mode On");
define("SAFE_MODE_CONTENT", "ImpressCMS heeft gedetecteerd dat PHP draait in Safe Mode. Hierdoor kan de installatie niet verder gaan. Neem contact op met uw hosting provider om uw webomgeving te wijzigen voordat u nogmaals een installatie poging onderneemt.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "Fysieke pad voor ImpressCMS documenten in de root"); // L55
define("XOOPS_ROOT_PATH_HELP", "Dit is het fysieke pad van de ImpressCMS bestanden map, de root van uw ImpressCMS aplicatie"); // L59
define("_INSTALL_TRUST_PATH_HELP", "Dit is het fysieke locatie van het ImpressCMS beveiligde pad. Het beveiligde pad is een map waarin ImpressCMS en de modules, voor een betere beveiliging, gevoelige informatie zal opslaan. Het is aangeraden dat deze map zich buiten de web root bevindt, zodat deze niet toegankelijk is voor een browser. Wanneer de map niet bestaat, zal ImpressCMS proberen er een aan te maken. Wanneer dit niet mogelijk is, zult u dit handmatig moeten doen.<br /><br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path/nl'>Klik hier</a> voor meer informatie over het beveiligede pad."); // L59

define("XOOPS_URL_LABEL", "Website locatie (URL)"); // L56
define("XOOPS_URL_HELP", "Hoofd URL die zal worden gebruikt om toegang te krijgen tot uw ImpressCMS installatie"); // L58

define("LEGEND_CONNECTION", "Server verbinding");
define("LEGEND_DATABASE", "Database"); // L51

define("DB_HOST_LABEL", "Server hostnaam");	// L27
define("DB_HOST_HELP", "Hostnaam van de database server. Wanneer u twijfelt, werkt <em>localhost</em> in de meeste gevallen"); // L67
define("DB_USER_LABEL", "Gebruikersnaam");	// L28
define("DB_USER_HELP", "Naam van de gebruiker die zal worden gehanteerd om te verbinden met de database server te realiseren"); // L65
define("DB_PASS_LABEL", "Wachtwoord");	// L52
define("DB_PASS_HELP", "Wachtwoord van uw database gebruikersaccount"); // L68
define("DB_NAME_LABEL", "Database naam");	// L29
define("DB_NAME_HELP", "Naam van de database op de server. De installatieassistent zal proberen een database aan te maken wanneer er nog geen bestaat"); // L64
define("DB_CHARSET_LABEL", "Database karakter set, wij adviseren u om UTF-8 als standaard te gebruiken.");
define("DB_CHARSET_HELP", "MySQL beschikt over karakter set ondersteuning welke het mogelijk maakt dat u data kunt opslaan gebruikmakend van een variëteit aan karakter sets en vergelijkbaar functionerens in verhouding tot een variëteit aan collaties.");
define("DB_COLLATION_LABEL", "Database collatie");
define("DB_COLLATION_HELP", "Een collatie is een set regels  hoe karakters in een karakter set te vergelijken.");
define("DB_PREFIX_LABEL", "Table prefix");	// L30
define("DB_PREFIX_HELP", "Deze prefix zal worden toegevoegd aan alle nieuw aan te maken tabellen om conflicten in de database te voorkomen. Wanneer u twijfelt, gebruik dan de standaard in gegeven prefix"); // L63
define("DB_PCONNECT_LABEL", "Gebruik continue verbinding");	// L54

define("DB_SALT_LABEL", "Wachtwoord versleuteling");	// L98
define("DB_SALT_HELP", "Deze wachtwoord versleuteling zal worden toegepast aan wachtwoorden in de functie icms_encryptPass(), en wordt gebruikt om een totaal uniek en veilig wachtwoord aan te maken. Wijzig deze sleutel niet zodra de website online is, dit zal alle bestaande wachtwoorden ongelidig maken. Dit ter voorkoming van conflicten tussen namen in de database. Wanneer u twijfelt kies dan voor de standaard instelling"); // L97

define("LEGEND_ADMIN_ACCOUNT", "Beheerdersaccount");
define("ADMIN_LOGIN_LABEL", "Beheerder gebruikersnaam"); // L37
define("ADMIN_EMAIL_LABEL", "Beheerder E-mailadres"); // L38
define("ADMIN_PASS_LABEL", "Beheerder wachtwoord"); // L39
define("ADMIN_CONFIRMPASS_LABEL", "Bevestig wachtwoord"); // L74
define("ADMIN_SALT_LABEL", "Wachtwoord versleuteling"); // L99 Password Salt Key

// Buttons
define("BUTTON_PREVIOUS", "Vorige"); // L42
define("BUTTON_NEXT", "Volgende"); // L47
define("BUTTON_FINISH", "Voltooien");
define("BUTTON_REFRESH", "Vernieuwen");
define("BUTTON_SHOW_SITE", "Toon mijn website");

// Messages
define("XOOPS_FOUND", "%s gevonden");
define("CHECKING_PERMISSIONS", "Rechten controleren van bestanden en mappen..."); // L82
define("IS_NOT_WRITABLE", "%s is NIET schrijfbaar."); // L83
define("IS_WRITABLE", "%s is schrijfbaar."); // L84
define("ALL_PERM_OK", "Alle rechten zijn correct ingesteld.");

define("READY_CREATE_TABLES", "Er zijn geen ImpressCMS tabellen gedetecteerd.<br />De installatie is nu gereed om de ImpressCMS systeem tabellen aan te maken.<br />Druk op <em>volgende</em> om door te gaan.");
define("XOOPS_TABLES_FOUND", "Er bestaan ImpressCMS systeem tabellen in uw database.<br />Druk op <em>volgende</em> om naar de volgende stap te gaan."); // L131
define("READY_INSERT_DATA", "De installatie assistent is gereed om de begin data in uw database in te voegen.");
define("READY_SAVE_MAINFILE", "De installatie assistent is nu gereed om de specifieke instellingen op te slaan in <em>mainfile.php</em>.<br />Druk op <em>volgende</em> om door te gaan.");
define("DATA_ALREADY_INSERTED", "ImpressCMS data zijn al opgeslagen in uw database. Er zullen bij deze stap geen verdere data worden opgeslagen.<br />Druk op <em>volgende</em> om naar de volgende stap te gaan.");


// %s is database name
define("DATABASE_CREATED", "Database %s is aangemaakt!"); // L43
// %s is table name
define("TABLE_NOT_CREATED", "Niet mogelijk om tabel %s aan te maken"); // L118
define("TABLE_CREATED", "Tabel %s is aangemaakt."); // L45
define("ROWS_INSERTED", "%d boekingen zijn in gevoegd in tabel %s."); // L119
define("ROWS_FAILED", "Niet gelukt om %d boekingen in te voegen in tabel %s."); // L120
define("TABLE_ALTERED", "Tabel %s is bijgewerkt."); // L133
define("TABLE_NOT_ALTERED", "Bijwerken van tabel %s is mislukt."); // L134
define("TABLE_DROPPED", "Tabel %s verwijderd."); // L163
define("TABLE_NOT_DROPPED", "Verwijderen van tabel %s is mislukt."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Kon geen toegang krijgen tot de specifieke map. Controleer of de map bestaat en leesbaar is door de server.");
define("ERR_NO_XOOPS_FOUND", "Er is geen ImpressCMS installatie gevonden in de opgegeven map.");
define("ERR_INVALID_EMAIL", "Ongeldige e-mail"); // L73
define("ERR_REQUIRED", "Vul alstublieft alle vereiste informatie in."); // L41
define("ERR_PASSWORD_MATCH", "De twee wachtwoorden komen niet met elkaar overeen");
define("ERR_NEED_WRITE_ACCESS", "De server moet toegang met schrijfrechten hebben tot de volgende bestanden en mappen<br />(bijv. <em>chmod 777 directory_name</em> op een UNIX/LINUX server)"); // L72
define("ERR_NO_DATABASE", "Database aanmaken is niet gelukt. Neem contact op met de server beheerder voor details."); // L31
define("ERR_NO_DBCONNECTION", "Het is niet gelukt om verbinding te krijgen met de database server."); // L106
define("ERR_WRITING_CONSTANT", "Schrijven van de constanten %s is mislukt."); // L122

define("ERR_COPY_MAINFILE", "Kon het distributie bestand niet kopiëren naar mainfile.php");
define("ERR_WRITE_MAINFILE", "Kon niet schrijven in mainfile.php. Controleer de schrijfrechten van dit bestand en probeer het nogmaals.");
define("ERR_READ_MAINFILE", "Kon mainfile.php niet openen om te lezen");

define("ERR_WRITE_SDATA", "Kon niet schrijven in sdata.php. Controleer de schrijfrechten van dit bestand en probeer het nogmaals.");
define("ERR_READ_SDATA", "Kon sdata.php niet openen om te lezen");
define("ERR_INVALID_DBCHARSET", "De karakter set '%s' wordt niet ondersteund.");
define("ERR_INVALID_DBCOLLATION", "De collatie '%s' wordt niet ondersteund.");
define("ERR_CHARSET_NOT_SET", "Standaard karakter set is niet ingesteld voor de ImpressCMS database.");


//
define("_INSTALL_SELECT_MODS_INTRO", 'In het selectieblok aan de linker zijde, kunt u de modules selecteren en die installeren op deze website. <br /><br />
Alle geïnstalleerde modules zijn toegankelijk voor de beheerdersgroep en de geregistreerde gebruikers. <br /><br />
Wanneer u rechten wilt instellen voor anonieme gebruikers, doet u dat in het administratiedeel van deze website nadat u de installatie hebt afgerond. <br /><br />
Voor meer informatie over groepenbeheer, bezoekt u alstublieft de <a href="http://wiki.impresscms.org/index.php?title=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Selecteer modules om te installeren');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Selecteer modules die zichtbaar zijn voor bezoekers');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Module %s kon niet worden geïnstalleerd.");
define("_INSTALL_ERRORS", 'Fouten');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Module %s is al geïnstalleerd");
define("_INSTALL_FAILED_TO_EXECUTE", "Niet gelukt om uit te voeren ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Correct uitgevoerd");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s succesvol geïnstalleerd.");
define("_INSTALL_MOD_INSTALL_FAILED", "De installatieassistent kon de volgende module niet installeren: %s.");
define("_INSTALL_NO_PLUS_MOD", "Er zijn geen modules geselecteerd om te installeren. Ga verder met de installatie door op afronden te klikken.");
define("_INSTALL_INSTALLING", "Installeren van module: %s ");

define("_INSTALL_TRUST_PATH", "Beveiligd pad");
define("_INSTALL_TRUST_PATH_LABEL", "Locatie van het beveiligde pad (moet buiten de web root zijn)");
define("_INSTALL_WEB_LOCATIONS", "Website locaties");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Website locaties");

define("_INSTALL_TRUST_PATH_FOUND", "Beveiligd pad gevonden.");
define("_INSTALL_ERR_NO_TRUST_PATH_FOUND", "Beveiligd pad is niet gevonden.");

define("_INSTALL_COULD_NOT_INSERT", "De installatie assistent kon de module %s niet installeren in de database.");
define("_INSTALL_CHARSET", "utf-8");

define("_INSTALL_PHYSICAL_PATH", "Fysiek pad");

define("TRUST_PATH_VALIDATE", "Een gesuggereerde naam voor het beveiligde pad is hierboven voor u aangemaakt. Wanneer u een alternatieve naam wenst, vervang dan de bovenstaande locatie met de door u gewenste naam.<br /><br />Klik wanneer u gereed bent op de beveiligd pad aanmaken knop.");
define("TRUST_PATH_NEED_CREATED_MANUALLY", "Het was niet mogelijk om het beveiligde pad aan te maken. Maak het handmatig aan en klik op de volgende Vernieuwen knop. Indien u het pad handmatig aanmaakt zorg dan dat de locatienaam overeenkomst!");
define("BUTTON_CREATE_TUST_PATH", "Beveiligd pad aanmaken");
define("TRUST_PATH_SUCCESSFULLY_CREATED", "Het beveiligde pad is succesvol aangemaakt.");

// welcome custom blocks
define("WELCOME_WEBMASTER", "Welkom Webmaster !");
define("WELCOME_ANONYMOUS", "Welkom op een door ImpressCMS aangedreven website !");
define("_MD_AM_MULTLOGINMSG_TXT", 'Het was niet mogelijk om in te loggen op de website!! <br />
        <p align="left" style="color:red;">
        Mogelijke oorzaken zijn:<br />
         - U bent reeds ingelogd op de website.<br />
         - Iemand anders heeft met uw gegevens ingelogd op de website.<br />
         - U heeft de website verlaten of de browser gesloten zonder op de uitlog knop te klikken.<br />
        </p>
        Wacht enige minuten en probeer het dan nogmaals. Wanneer het probleem blijft bestaan neem dan contact op met de beheerders van deze website.');
define("_MD_AM_RSSLOCALLINK_DESC", 'http://www.impresscms.org/modules/smartsection/backend.php'); //Link to the rrs of local support site
define("_INSTALL_LOCAL_SITE", 'http://www.impresscms.org/'); //Link to local support site
define("_LOCAOL_STNAME", 'ImpressCMS'); //Link to local support site
define("_LOCAL_SLOCGAN", 'Make a lasting impression'); //Link to local support site
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>'); //footer Link to local support site
define("_LOCAL_SENSORTXT", '#OOPS#'); //Add local translation
define("_ADM_USE_RTL", "0"); // turn this to 1 if your language is right to left
define("_DEF_LANG_TAGS", 'nl,en,fr'); //Add local translation
define("_DEF_LANG_NAMES", 'dutch,english,french'); //Add local translation
define("_LOCAL_LANG_NAMES", 'Nederlands,English,Français'); //Add local translation
define("_EXT_DATE_FUNC", "0"); // change 0 to 1 if this language has an extended date function

######################## Added in 1.2 ###################################
define("ADMIN_DISPLAY_LABEL", "Admin Naam weergave"); // L37
define('_CORE_PASSLEVEL1', 'Te kort');
define('_CORE_PASSLEVEL2', 'Zwak');
define('_CORE_PASSLEVEL3', 'Goed');
define('_CORE_PASSLEVEL4', 'Sterk');
define('DB_PCONNECT_HELP', "Blijvende verbindingen zijn handig met langzamere internet verbindingen. Ze zijn in het algemeen niet vereist voor de meeste installaties. Standaard is 'NEE'. Kies 'NEE' wanneer u twijfelt."); // L69
define("DB_PCONNECT_HELPS", "Blijvende verbindingen zijn handig met langzamere internet verbindingen. Ze zijn in het algemeen niet vereist voor de meeste installaties."); // L69
