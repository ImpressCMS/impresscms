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
define("SAFE_MODE_CONTENT", "ImpressCMS heeft gedetecteerd dat PHP draait in de Safe Mode. Daarom kan uw installatie niet doorgaan. Werk met uw hostingprovider samen om uw omgeving te wijzigen voordat u probeert opnieuw te installeren.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "ImpressCMS documenten fysiek hoofdpad"); // L55
define("XOOPS_ROOT_PATH_HELP", "Dit is het fysieke pad van de ImpressCMS documentenmap, de webroot van uw ImpressCMS applicatie"); // L59

define("XOOPS_URL_LABEL", "Website locatie (URL)"); // L56
define("XOOPS_URL_HELP", "Hoofd URL die zal worden gebruikt voor toegang tot uw ImpressCMS installatie"); // L58

define("LEGEND_CONNECTION", "Server verbinding");
define("LEGEND_DATABASE", "Databank"); // L51

define("DB_HOST_LABEL", "Server hostnaam"); // L27
define("DB_HOST_HELP", "Hostnaam van de databaseserver. Als u het niet zeker weet, werkt <em>localhost</em> in de meeste gevallen"); // L67
define("DB_USER_LABEL", "Gebruikers naam"); // L28
define("DB_USER_HELP", "Naam van de gebruikersaccount die zal worden gebruikt om verbinding te maken met de database server"); // L65
define("DB_PASS_LABEL", "Wachtwoord"); // L52
define("DB_PASS_HELP", "Wachtwoord van uw databank gebruikersaccount"); // L68
define("DB_NAME_LABEL", "Naam databank"); // L29
define("DB_NAME_HELP", "De naam van de databank op de host. Het installatieprogramma zal proberen een nieuwe databank aan te maken als deze niet bestaat"); // L64
define("DB_CHARSET_LABEL", "Database tekenreeks, wij raden je STERK aan UTF-8 als standaard te gebruiken.");
define("DB_CHARSET_HELP", "MySQL bevat ondersteuning voor de karakterset die u in staat stelt gegevens op te slaan met behulp van een verscheidenheid aan karaktersets en vergelijkingen uitvoeren volgens een verscheidenheid aan collaties.");
define("DB_COLLATION_LABEL", "Database collatie");
define("DB_COLLATION_HELP", "Een collatie is een set regels voor het vergelijken van tekens in een karakterset.");
define("DB_PREFIX_LABEL", "Tabel voorvoegsel"); // L30
define("DB_PREFIX_HELP", "Dit voorvoegsel zal worden toegevoegd aan alle nieuwe tabellen om te voorkomen dat er conflicten ontstaan in de database. Als u het niet zeker weet, houd dan de standaard"); // L63
define("DB_PCONNECT_LABEL", "Gebruik permanente verbinding"); // L54

define("DB_SALT_LABEL", "Wachtwoord Salt sleutel"); // L98
define("DB_SALT_HELP", "Deze zout-sleutel zal worden toegevoegd aan wachtwoorden in functie icms_encryptPass() en wordt gebruikt om een volledig uniek wachtwoord te maken. Pas deze sleutel niet aan zodra de site actief is, anders worden ALLE wachtwoorden ongeldig. Als je het niet zeker weet, houd dan de standaard waarde in"); // L97

define("LEGEND_ADMIN_ACCOUNT", "Beheerder account");
define("ADMIN_LOGIN_LABEL", "Beheerder login"); // L37
define("ADMIN_EMAIL_LABEL", "Beheerder e-mail"); // L38
define("ADMIN_PASS_LABEL", "Beheerder wachtwoord"); // L39
define("ADMIN_CONFIRMPASS_LABEL", "Bevestig wachtwoord"); // L74
define("ADMIN_SALT_LABEL", "Wachtwoord Salt sleutel"); // L99

// Buttons
define("BUTTON_PREVIOUS", "Vorige"); // L42
define("BUTTON_NEXT", "Volgende"); // L47
define("BUTTON_FINISH", "Volgende");
define("BUTTON_REFRESH", "Vernieuwen");
define("BUTTON_SHOW_SITE", "Toon mijn site");

// Messages
define("XOOPS_FOUND", "%s gevonden");
define("CHECKING_PERMISSIONS", "Bestands- en mapmachtigingen controleren..."); // L82
define("IS_NOT_WRITABLE", "%s is NIET schrijfbaar."); // L83
define("IS_WRITABLE", "%s is schrijfbaar."); // L84
define("ALL_PERM_OK", "Alle machtigingen zijn correct.");

define("READY_CREATE_TABLES", "Er zijn geen ImpressCMS tabellen gedetecteerd.<br />Het installatieprogramma is nu klaar om de ImpressCMS systeemtabellen aan te maken.<br />Druk op <em>volgende</em> om verder te gaan.");
define("XOOPS_TABLES_FOUND", "De ImpressCMS systeemtabellen bestaan al in uw database.<br />Klik op <em>Volgende</em> om naar de volgende stap te gaan."); // L131
define("READY_INSERT_DATA", "Het installatieprogramma is nu klaar om de initiële gegevens in uw database te plaatsen.");
define("READY_SAVE_MAINFILE", "Het installatieprogramma is nu klaar om de opgegeven instellingen op te slaan op <em>.env</em>.<br />Druk op <em>volgende</em> om verder te gaan.");
define("DATA_ALREADY_INSERTED", "ImpressCMS gegevens zijn al aanwezig in uw database. Er worden geen verdere gegevens opgeslagen door deze actie.<br />Druk op <em>Volgende</em> om naar de volgende stap te gaan.");

// %s is database name
define("DATABASE_CREATED", "Database %s aangemaakt!"); // L43
// %s is table name
define("TABLE_NOT_CREATED", "Kan tabel %s niet aanmaken"); // L118
define("TABLE_CREATED", "Tabel %s is aangemaakt."); // L45
define("ROWS_INSERTED", "%d elementen toegevoegd aan tabel %s."); // L119
define("ROWS_FAILED", "Fout bij het invoegen van %d elementen in tabel %s."); // L120
define("TABLE_ALTERED", "Tabel %s bijgewerkt."); // L133
define("TABLE_NOT_ALTERED", "Tabel %s bijwerken mislukt."); // L134
define("TABLE_DROPPED", "Tabel %s is verwijderd."); // L163
define("TABLE_NOT_DROPPED", "Verwijderen van tabel %s mislukt."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Kon de opgegeven map niet openen. Controleer of deze bestaat en leesbaar is voor de server.");
define("ERR_NO_XOOPS_FOUND", "Er kon geen installatie van ImpressCMS gevonden worden in de opgegeven map.");
define("ERR_INVALID_EMAIL", "Ongeldige e-mail"); // L73
define("ERR_REQUIRED", "Vul alle verplichten velden in."); // L41
define("ERR_PASSWORD_MATCH", "De twee wachtwoorden komen niet overeen");
define("ERR_NEED_WRITE_ACCESS", "De server moet schrijftoegang krijgen tot de volgende bestanden en mappen<br />(d.w.z. <em>chmod 777 map_name</em> op een UNIX/LINUX server)"); // L72
define("ERR_NO_DATABASE", "Kan de database niet aanmaken. Neem voor meer informatie contact op met de serverbeheerder."); // L31
define("ERR_NO_DBCONNECTION", "Kan geen verbinding maken met de databaseserver."); // L106
define("ERR_WRITING_CONSTANT", "Schrijven constante %s mislukt."); // L122
define('ERR_WRITE_ENV_DATA', 'Fout bij schrijven van .env gegevens');
define("ERR_INVALID_DBCHARSET", "De tekenset '%s' wordt niet ondersteund.");
define("ERR_INVALID_DBCOLLATION", "De collatie '%s' wordt niet ondersteund.");
define("ERR_CHARSET_NOT_SET", "Standaard tekenset is niet ingesteld voor ImpressCMS database.");

//
define("_INSTALL_SELECT_MODS_INTRO", 'Selecteer de modules die u op deze site wilt installeren uit de lijst hieronder. <br /><br />
Alle geïnstalleerde modules zijn standaard toegankelijk voor de beheerdersgroep en de Geregistreerde Gebruikersgroep. <br /><br />
Als u de rechten voor anonieme gebruikers wilt instellen, doe dit dan in het beheerpaneel nadat u dit installatieprogramma heeft voltooid. <br /><br />
Bezoek de <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">wiki</a> voor meer informatie over Groepsbeheer.');

define("_INSTALL_SELECT_MODULES", 'Selecteer modules om te installeren');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Selecteer modules zichtbaar voor bezoekers');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Module %s kon niet worden geïnstalleerd.");
define("_INSTALL_ERRORS", 'Fouten');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Module %s is reeds geïnstalleerd");
define("_INSTALL_FAILED_TO_EXECUTE", "Fout bij uitvoeren ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Succesvol uitgevoerd");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s is met succes geïnstalleerd.");
define("_INSTALL_MOD_INSTALL_FAILED", "De wizard kon module %s niet installeren.");
define("_INSTALL_INSTALLING", "Installatie van %s module");

define("_INSTALL_WEB_LOCATIONS", "Web locatie");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Web locatie");

define("_INSTALL_COULD_NOT_INSERT", "De wizard kon module %s niet toevoegen aan de database.");
define("_INSTALL_CHARSET", "utf-8");

define("_INSTALL_PHYSICAL_PATH", "Fysieke pad");

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
define("ADMIN_DISPLAY_LABEL", "Beheerder Display Naam"); // L37
define('_CORE_PASSLEVEL1', 'Te kort');
define('_CORE_PASSLEVEL2', 'Zwak');
define('_CORE_PASSLEVEL3', 'Goed');
define('_CORE_PASSLEVEL4', 'Sterk');
define('DB_PCONNECT_HELP', "Persistente verbindingen zijn handig bij langzamere internetverbindingen. Ze zijn over het algemeen niet vereist voor de meeste installaties. Standaard is 'NIET'. Kies 'NIET' als u niet zeker bent"); // L69
define("DB_PCONNECT_HELPS", "Persistente verbindingen zijn handig bij langzamere internetverbindingen. Ze zijn over het algemeen niet vereist voor de meeste installaties."); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "Toegangsrechten bestand");
