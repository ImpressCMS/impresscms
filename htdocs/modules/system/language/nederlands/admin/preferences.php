<?php
// $Id: preferences.php 9546 2009-11-14 12:16:02Z Phoenyx $
//%%%%%%	Admin Module Name  AdminGroup 	%%%%%
// dont change
if (!defined('_AM_DBUPDATED')) {
    define("_AM_DBUPDATED", "Database succesvol bijgewerkt!");
}

define("_MD_AM_SITEPREF", "Instellingen");
define("_MD_AM_SITENAME", "Naam website");
define("_MD_AM_SLOGAN", "Spreuk voor uw website");
define("_MD_AM_ADMINML", "E-mail adres beheerder");
define("_MD_AM_LANGUAGE", "Standaard taal");
define("_MD_AM_STARTPAGE", "Module voor uw startpagina");
define("_MD_AM_NONE", "Geen");
define("_MD_CONTENTMAN", "Inhoud Beheer");
define("_MD_AM_SERVERTZ", "Server tijdzone");
define("_MD_AM_DEFAULTTZ", "Standaard tijdzone");
define("_MD_AM_DTHEME", "Standaard vormgeving");
define("_MD_AM_THEMESET", "Thema set");
define("_MD_AM_ANONNAME", "Gebruikersnaam voor anonieme gebruikers");
define("_MD_AM_MINPASS", "Minimaal aantal tekens voor het wachtwoord");
define("_MD_AM_NEWUNOTIFY", "Stuur een e-mail bij een nieuwe aanmelding?");
define("_MD_AM_SELFDELETE", "Sta gebruikers toe het eigen account te verwijderen?");
define("_MD_AM_LOADINGIMG", "Toon loading... afbeelding?");
define("_MD_AM_USEGZIP", "Gebruik gzip compressie?");
define("_MD_AM_UNAMELVL", "Hoe strikt dienen de gebruikte tekens te zijn?");
define("_MD_AM_STRICT", "Strikt (alleen letters en cijfers)");
define("_MD_AM_MEDIUM", "Middel");
define("_MD_AM_LIGHT", "Laag (aanbevolen bij multi-byte tekens)");
define("_MD_AM_USERCOOKIE", "Naam voor gebruikers cookies.");
define("_MD_AM_USERCOOKIEDSC", "Dit cookie bestand bevat alleen een gebruikersnaam en wordt voor de duur van ? jaar op de pc van de gebruiker opgeslagen (als de gebruiker dat wenst). Als een gebruiker deze cookie heeft, wordt de gebruikersnaam automatisch ingevuld in het login veld.");
define("_MD_AM_USEMYSESS", "Gebruik eigen sessie");
define("_MD_AM_USEMYSESSDSC", "Selecteer ja om de sessie gerelateerde waarden aan uw wensen aan te passen.");
define("_MD_AM_SESSNAME", "Naam sessie");
define("_MD_AM_SESSNAMEDSC", "De naam van de sessie (alleen geldig indien de <i>Gebruik eigen sessie</i> optie is ingeschakeld)");
define("_MD_AM_SESSEXPIRE", "Sessie duur");
define("_MD_AM_SESSEXPIREDSC", "Maximale duur van inactiviteit tijdens een sessie.<br />(alleen mogelijk indien <i>Gebruik PHP native sessie</i> is uitgeschakeld)");
define("_MD_AM_BANNERS", "Activeer reclame banners?");
define("_MD_AM_MYIP", "Uw IP adres");
define("_MD_AM_MYIPDSC", "Dit IP adres zal niet meegeteld worden bij de reclame banners");
define("_MD_AM_ALWDHTML", "Toegestane HTML code in alle berichten");
define("_MD_AM_INVLDMINPASS", "Ongeldige waarde voor minimum lengte wachtwoord.");
define("_MD_AM_INVLDUCOOK", "Ongeldige waarde voor naam gebruikers cookie.");
define("_MD_AM_INVLDSCOOK", "Ongeldige waarde voor naam sessie cookie.");
define("_MD_AM_INVLDSEXP", "Ongeldige waarde voor sessie duur.");
define("_MD_AM_ADMNOTSET", "Geen beheerders e-mail ingevoerd.");
define("_MD_AM_YES", "Ja");
define("_MD_AM_NO", "Nee");
define("_MD_AM_DONTCHNG", "Niet wijzigen!");
define("_MD_AM_REMEMBER", "Vergeet niet de lees- en schrijfrechten voor dit bestand te veranderen (chmod 666) zodat het systeem de instellingen op kan slaan.");
define("_MD_AM_IFUCANT", "Als u de lees- en schrijfrechten niet kunt veranderen kunt u de rest van dit bestand handmatig aanpassen.");


define("_MD_AM_COMMODE", "Standaard reacties weergave");
define("_MD_AM_COMORDER", "Standaard volgorde reacties");
define("_MD_AM_ALLOWHTML", "Sta HTML code in reactie van gebruikers toe?");
define("_MD_AM_DEBUGMODE", "Debug modus");
define("_MD_AM_DEBUGMODEDSC", "Verschillende debug opties. Deze optie dient uitgeschakeld te worden zodra de website openbaar wordt.");
define("_MD_AM_AVATARALLOW", "Sta toe om een eigen pasfoto te uploaden?");
define('_MD_AM_AVATARMP', 'Minimum aantal berichten');
define('_MD_AM_AVATARMPDSC', 'Vul het minimum aantal berichten in dat nodig is om een eigen pasfoto te kunnen uploaden');
define("_MD_AM_AVATARW", "Max pasfoto breedte (pixels)");
define("_MD_AM_AVATARH", "Max pasfoto hoogte (pixels)");
define("_MD_AM_AVATARMAX", "Max pasfoto grootte (bytes)");
define("_MD_AM_AVATARCONF", "Instellingen eigen pasfoto");
define("_MD_AM_CHNGUTHEME", "Verander vormgeving van alle gebruikers");
define("_MD_AM_NOTIFYTO", "Stuur een bericht naar groep");
define("_MD_AM_ALLOWTHEME", "Sta gebruikers toe een eigen vormgeving te kiezen?");
define("_MD_AM_ALLOWIMAGE", "Sta gebruikers toe afbeeldingen in berichten op te nemen?");

define("_MD_AM_USERACTV", "Activering door gebruiker (aangeraden)");
define("_MD_AM_AUTOACTV", "Automatische activering");
define("_MD_AM_ADMINACTV", "Activering door beheerders");
define("_MD_AM_REGINVITE", "Registreren op uitnodiging");
define("_MD_AM_ACTVTYPE", "Selecteer activeringswijze voor nieuwe aanmeldingen");
define("_MD_AM_ACTVGROUP", "Stuur activerings e-mail naar gebruikersgroep");
define("_MD_AM_ACTVGROUPDSC", "Alleen mogelijk wanneer <i>Activering door beheerders</i> ingeschakeld is");
define('_MD_AM_USESSL', 'Gebruik SSL voor login?');
define('_MD_AM_SSLPOST', 'Naam SSL Post variabele');
define('_MD_AM_SSLPOSTDSC', 'De naam van de variabele die gebruikt wordt om een sessie waarde te verzenden via het POST commando. Als u het niet zeker weet, gebruik dan, in verband met veiligheidsoverwegingen, een moeilijk te raden naam.');
define('_MD_AM_DEBUGMODE0', 'Uit');
define('_MD_AM_DEBUGMODE1', 'Gebruik debug (inline mode)');
define('_MD_AM_DEBUGMODE2', 'Gebruik debug (popup mode)');
define('_MD_AM_DEBUGMODE3', 'Smarty Sjablonen Debug');
define('_MD_AM_MINUNAME', 'Minimum aantal tekens voor gebruikersnaam');
define('_MD_AM_MAXUNAME', 'Maximum aantal tekens voor gebruikersnaam');
define('_MD_AM_GENERAL', 'Algemene instellingen');
define('_MD_AM_USERSETTINGS', 'Instellingen gebruikersgegevens');
define('_MD_AM_ALLWCHGMAIL', 'Sta gebruikers toe eigen e-mail adres te wijzigen?');
define('_MD_AM_ALLWCHGMAILDSC', '');
define('_MD_AM_IPBAN', 'IP Blokkade');
define('_MD_AM_BADEMAILS', 'E-mail adressen die niet in het gebruikersprofiel gebruikt mogen worden');
define('_MD_AM_BADEMAILSDSC', 'Tussen elk adres moet een pipeline <b>|</b> te staan, ongeacht hoofd- of kleine letters, <a href="http://gerard.yoursite.nl/got/regex-tut/" target="_blank">regex</a> aan.');
define('_MD_AM_BADUNAMES', 'Namen die niet als gebruikersnaam gebruikt mogen worden');
define('_MD_AM_BADUNAMESDSC', 'Tussen elke naam moet een pipeline <b>|</b>, ongeacht hoofd- of kleine letters, <a href="http://gerard.yoursite.nl/got/regex-tut/" target="_blank">regex</a> aan.');
define('_MD_AM_DOBADIPS', 'Schakel IP blokkade in?');
define('_MD_AM_DOBADIPSDSC', 'Bezoekers met deze IP adressen worden van uw website geblokkeerd');
define('_MD_AM_BADIPS', 'IP adressen die van de website worden geblokkeerd.');
define('_MD_AM_BADIPSDSC', '^aaa.bbb.ccc blokkeert bezoekers met een IP adres dat begint met aaa.bbb.ccc<br />aaa.bbb.ccc$ blokkeert bezoekers met een IP adres dat eindigt op aaa.bbb.ccc<br />aaa.bbb.ccc blokkeert bezoekers met een IP adres dat aaa.bbb.ccc bevat. Tussen elk adres moet een pipeline <b>|</b>, ongeacht hoofd- of kleine letters, <a href="http://gerard.yoursite.nl/got/regex-tut/" target="_blank">regex</a> aan.');
define('_MD_AM_PREFMAIN', 'Instellingen');
define('_MD_AM_METAKEY', 'Meta trefwoorden');
define('_MD_AM_METAKEYDSC', 'De keywords meta tag is een serie trefwaarden die de inhoud van uw website weergeeft. Vul tussen elk trefwoord een komma of een spatie in. (Bijv. XOOPS, PHP, mySQL, portal system)');
define('_MD_AM_METARATING', 'Meta rating');
define('_MD_AM_METARATINGDSC', 'De rating meta tag geeft de classificatie van uw website aan');
define('_MD_AM_METAOGEN', 'Algemeen');
define('_MD_AM_METAO14YRS', '14 jaar');
define('_MD_AM_METAOREST', 'Beperkte toegang');
define('_MD_AM_METAOMAT', 'Volwassenen');
define('_MD_AM_METAROBOTS', 'Meta robots');
define('_MD_AM_METAROBOTSDSC', 'De robots tag vertelt de zoekmachines welke inhoud te indexeren en te doorzoeken');
define('_MD_AM_INDEXFOLLOW', 'Index, follow');
define('_MD_AM_NOINDEXFOLLOW', 'No index, follow');
define('_MD_AM_INDEXNOFOLLOW', 'Index, no follow');
define('_MD_AM_NOINDEXNOFOLLOW', 'No index, no follow');
define('_MD_AM_METAAUTHOR', 'Meta autheur');
define('_MD_AM_METAAUTHORDSC', 'De autheur meta tag geeft de naam van de auteur van het document. Gegevens die ondersteund worden zijn: naam, e-mail adres van de webmaster, bedrijfsnaam of internet adres.');
define('_MD_AM_METACOPYR', 'Meta copyright');
define('_MD_AM_METACOPYRDSC', 'De copyright meta tag geeft de copyright verklaring die u aan uw webdocumenten wilt koppelen.');
define('_MD_AM_METADESC', 'Meta omschrijving');
define('_MD_AM_METADESCDSC', 'De omschrijving meta tag is een algemene omschrijving van de inhoud van uw website.');
define('_MD_AM_METAFOOTER', 'Meta tags en footer');
define('_MD_AM_FOOTER', 'Footer');
define('_MD_AM_FOOTERDSC', 'Zorg ervoor dat u altijd een volledige url intypt, te beginnen met http://, anders zal de link niet goed werken in de modules.');
define('_MD_AM_CENSOR', 'Woord censuur opties');
define('_MD_AM_DOCENSOR', 'Censuur van ongewenste woorden inschakelen?');
define('_MD_AM_DOCENSORDSC', 'Woorden worden gecensureerd wanneer deze optie ingeschakeld is. Inschakeling betekent een tragere website.');
define('_MD_AM_CENSORWRD', 'Te censureren woorden');
define('_MD_AM_CENSORWRDDSC', 'Vul in berichten van gebruikers te censureren woorden in. <br />Tussen elk woord een<b>|</b>, ongeacht hoofd- of kleine letters.');
define('_MD_AM_CENSORRPLC', 'Ongewenste woorden worden vervangen door:');
define('_MD_AM_CENSORRPLCDSC', 'Gecensureerde woorden worden vervangen door deze tekens');

define('_MD_AM_SEARCH', 'Zoek opties');
define('_MD_AM_DOSEARCH', 'Schakel website zoekopdrachten in?');
define('_MD_AM_DOSEARCHDSC', 'Sta zoekopdrachten voor berichten en items binnen uw website toe.');
define('_MD_AM_MINSEARCH', 'Minimum aantal tekens voor het trefwoord');
define('_MD_AM_MINSEARCHDSC', 'Vul het minimum aantal tekens in dat gebruikers in moeten vullen om een zoekopdracht uit te voeren.');
define('_MD_AM_MODCONFIG', 'Module instel opties');
define('_MD_AM_DSPDSCLMR', 'Toon diclaimer?');
define('_MD_AM_DSPDSCLMRDSC', 'Selecteer <i>Ja</i> om de disclaimer op de registratiepagina te tonen.');
define('_MD_AM_REGDSCLMR', 'Registratie disclaimer');
define('_MD_AM_REGDSCLMRDSC', 'Vul hier de tekst in waarmee nieuwe leden akkoord moeten gaan alvorens zich te registreren voor de website.');
define('_MD_AM_ALLOWREG', 'Sta aanmelding nieuwe gebruikers toe?');
define('_MD_AM_ALLOWREGDSC', 'Selecteer <i>Ja</i> om aanmelding van nieuwe gebruikers toe te staan.');
define('_MD_AM_THEMEFILE', 'Template bestanden uit themes/templates map bijwerken?');
define('_MD_AM_THEMEFILEDSC', 'Wanneer deze optie is ingeschakeld, worden de template .html bestanden automatisch bijgewerkt als er nieuwere bestanden in de themes/templates map van het huidige theme staan. Deze optie dient uitgeschakeld te worden zodra de website openbaar wordt.');
define('_MD_AM_CLOSESITE', 'Sluit de website?');
define('_MD_AM_CLOSESITEDSC', 'Selecteer ja om de website te sluiten, zodat alleen gebruikers in de gelecteerde groepen toegang tot de website hebben.');
define('_MD_AM_CLOSESITEOK', 'Selecteer groepen die toegang tot de website hebben wanneer deze gesloten is.');
define('_MD_AM_CLOSESITEOKDSC', 'Gebruikers in de standaard Webmasters groep hebben altijd toegang.');
define('_MD_AM_CLOSESITETXT', 'Reden voor het sluiten van de website');
define('_MD_AM_CLOSESITETXTDSC', 'Deze tekst wordt getoond wanneer de website gesloten is.');
define('_MD_AM_SITECACHE', 'Site-brede cache');
define('_MD_AM_SITECACHEDSC', 'Slaat de hele inhoud van de website op voor de geselecteerde duur om prestaties te verhogen. Site-brede cache instellingen gaan voor module-cache instellingen, blokken-cache instellingen en module-onderdeel cache, indien aanwezig.');
define('_MD_AM_MODCACHE', 'Module-brede cache');
define('_MD_AM_MODCACHEDSC', 'Slaat module inhoud op voor de geselecteerde duur om prestaties te verhogen. Module-cache instellingen gaan voor module-onderdeel cache instellingen, indien aanwezig.');
define('_MD_AM_NOMODULE', 'Er is geen module die gecached kan worden.');
define('_MD_AM_DTPLSET', 'Standaard Templates');
define('_MD_AM_SSLLINK', 'URL waar de SSL pagina zich bevind');

// added for mailer
define("_MD_AM_MAILER", "Mail instellingen");
define("_MD_AM_MAILER_MAIL", "");
define("_MD_AM_MAILER_SENDMAIL", "");
define("_MD_AM_MAILER_", "");
define("_MD_AM_MAILFROM", "Emailadres afzender");
define("_MD_AM_MAILFROMDESC", "");
define("_MD_AM_MAILFROMNAME", "Naam afzender");
define("_MD_AM_MAILFROMNAMEDESC", "");
// RMV-NOTIFY
define("_MD_AM_MAILFROMUID", "Gebruiker afzender");
define("_MD_AM_MAILFROMUIDDESC", "Wanneer het systeem een privebericht stuurt, onder welke afzender zou het moeten verschijnen?");
define("_MD_AM_MAILERMETHOD", "Gewenste e-mail methode");
define("_MD_AM_MAILERMETHODDESC", "Methode voor het bezorgen van e-mail. Standaard is \"mail\", gebruik alleen een andere optie als deze problemen geeft.");
define("_MD_AM_SMTPHOST", "SMTP host(s)");
define("_MD_AM_SMTPHOSTDESC", "Lijst met SMTP servers om verbinding mee te maken.");
define("_MD_AM_SMTPUSER", "SMTPAuth gebruikersnaam");
define("_MD_AM_SMTPUSERDESC", "Gebruikersnaam om verbinding te maken met een SMTP host via SMTPAuth.");
define("_MD_AM_SMTPPASS", "SMTPAuth wachtwoord");
define("_MD_AM_SMTPPASSDESC", "Wachtwoord om verbinding te maken met een SMTP host via SMTPAuth.");
define("_MD_AM_SENDMAILPATH", "Path naar sendmail");
define("_MD_AM_SENDMAILPATHDESC", "Path naar het sendmail programma (of vervanging) op de webserver.");
define("_MD_AM_THEMEOK", "Selecteerbare thema's");
define("_MD_AM_THEMEOKDSC", "Selecteer hier de thema's waaruit gebruikers een standaard thema kunnen kiezen");


// Xoops Authentication constants
define("_MD_AM_AUTH_CONFOPTION_XOOPS", "ImpressCMS database");
define("_MD_AM_AUTH_CONFOPTION_LDAP", "Standaard LDAP directorie");
define("_MD_AM_AUTH_CONFOPTION_AD", "Microsoft Active directorie &copy");
define("_MD_AM_AUTHENTICATION", "Verificatie opties");
define("_MD_AM_AUTHMETHOD", "Verificatie methode");
define("_MD_AM_AUTHMETHODDESC", "Welke verificatie methode wilt u gebruiken voor het aanmelden van gebruikers.");
define("_MD_AM_LDAP_MAIL_ATTR", "LDAP - e-mail veld");
define("_MD_AM_LDAP_MAIL_ATTR_DESC", "De naam van het e-mail attribuut in de LDAP directorie.");
define("_MD_AM_LDAP_NAME_ATTR", "LDAP - Naam veld");
define("_MD_AM_LDAP_NAME_ATTR_DESC", "De veldnaam voor het naam attribuut in de LDAP directorie.");
define("_MD_AM_LDAP_SURNAME_ATTR", "LDAP - Achternaam veld");
define("_MD_AM_LDAP_SURNAME_ATTR_DESC", "De veldnaam voor het achternaam attribuut LDAP directorie.");
define("_MD_AM_LDAP_GIVENNAME_ATTR", "LDAP - Voornaam veld");
define("_MD_AM_LDAP_GIVENNAME_ATTR_DSC", "De veldnaam voor het voornaam attribuut in de LDAP directorie.");
define("_MD_AM_LDAP_BASE_DN", "LDAP - Base DN");
define("_MD_AM_LDAP_BASE_DN_DESC", "The basis DN (Distinguished Name) van de LDAP directorie.");
define("_MD_AM_LDAP_PORT", "LDAP - Poort Nummer");
define("_MD_AM_LDAP_PORT_DESC", "Het poortnummer voor toegang tot de directorie server.");
define("_MD_AM_LDAP_SERVER", "LDAP - Server Naam");
define("_MD_AM_LDAP_SERVER_DESC", "De naam van de LDAP directorie server.");

define("_MD_AM_LDAP_MANAGER_DN", "DN van de LDAP manager");
define("_MD_AM_LDAP_MANAGER_DN_DESC", "De DN van de gebruiker voor de zoekfunctie (eg manager)");
define("_MD_AM_LDAP_MANAGER_PASS", "Wachtwoord van de LDAP manager");
define("_MD_AM_LDAP_MANAGER_PASS_DESC", "Het wachtwoord van de gebruiker om de zoekfunctie te gebruiken");
define("_MD_AM_LDAP_VERSION", "LDAP Versie protocol");
define("_MD_AM_LDAP_VERSION_DESC", "Het LDAP Versie protocol : 2 of 3");
define("_MD_AM_LDAP_USERS_BYPASS", " ImpressCMS gebruiker(s) omleiding LDAP Authenticatie");
define("_MD_AM_LDAP_USERS_BYPASS_DESC", "ImpressCMS gebruiker(s) toestaan om de LDAP login te omleiden. Direct inloggen in ImpressCMS<br>scheid iedere gebruikersnaam met een |");

define("_MD_AM_LDAP_USETLS", " Gebruik TLS verbinding");
define("_MD_AM_LDAP_USETLS_DESC", "Gebruik een TLS (Transport Layer Security) verbinding. TLS gebruikt standaard poort nummer 389<BR>" .
                                  " en de LDAP versie moet worden ingesteld op 3.");

define("_MD_AM_LDAP_LOGINLDAP_ATTR", "LDAP Attribuut gebruiken om gebruikers te zoeken");
define("_MD_AM_LDAP_LOGINLDAP_ATTR_D", "Wanneer de Gebruik de login naam als DN op ja is gezet dient deze te corresponderen met de ImpressCMS loginnaam");
define("_MD_AM_LDAP_LOGINNAME_ASDN", "Gebruik de login naam als DN");
define("_MD_AM_LDAP_LOGINNAME_ASDN_D", "De ImpressCMS login naam is gebruikt in de LDAP DN (eg : uid=<loginnaam>,dc=xoops,dc=org)<br>De input is direct te lezen op de LDAP Server zonder te hoeven zoeken");

define("_MD_AM_LDAP_FILTER_PERSON", "De speciale zoekfilter om gebruikers te vinden via LDAP");
define("_MD_AM_LDAP_FILTER_PERSON_DESC", "Speciale LDAP Filter om gebruikers te vinden. @@loginnaam@@ word vervangen door de gebruikers naam<br> LAAT DIT VELD LEEG ALS U NIET WEET WAT DIT INHOUD !" .
        "<br />Ex : (&(objectclass=person)(samaccountname=@@loginname@@)) voor AD" .
        "<br />Ex : (&(objectclass=inetOrgPerson)(uid=@@loginname@@)) voor LDAP");

define("_MD_AM_LDAP_DOMAIN_NAME", "De domeinnaam");
define("_MD_AM_LDAP_DOMAIN_NAME_DESC", "Windows domeinnaam. alleen voor ADS en NT Servers");

define("_MD_AM_LDAP_PROVIS", "Automatische ImpressCMS account onderhoud");
define("_MD_AM_LDAP_PROVIS_DESC", "Maak een ImpressCMS gebruikersdatabase aan als deze niet bestaat");

define("_MD_AM_LDAP_PROVIS_GROUP", "Standaard groep");
define("_MD_AM_LDAP_PROVIS_GROUP_DSC", "De nieuwe gebruiker is aangemeld voor deze groepen");

define("_MD_AM_LDAP_FIELD_MAPPING_ATTR", "ImpressCMS-Auth server veld overzicht");
define("_MD_AM_LDAP_FIELD_MAPPING_DESC", "Geef hier een overzicht van de  ImpressCMS database velden en de LDAP verificatie systeem velden.".
        "<br /><br />Format [ImpressCMS Database field]=[Auth system LDAP attribute]".
        "<br />for example : email=mail".
        "<br />Gegevens scheiden met een |".
        "<br /><br />!! Voor gevorderde gebruikers !!");

define("_MD_AM_LDAP_PROVIS_UPD", "Handhaaf ImpressCMS account onderhoud");
define("_MD_AM_LDAP_PROVIS_UPD_DESC", "De ImpressCMS gebruikers account wordt altijd gesynchroniseerd door middel van de verificatie server");

//lang constants for secure password
define("_MD_AM_PASSLEVEL", "Minimale beveiligingsniveau");
define("_MD_AM_PASSLEVEL_DESC", "Defineer welk beveiligingsniveau u wilt voor de wachtwoorden van uw gebruikers. Het is aangeraden dit niet te laag of te hoog in te stellen, wees verstandig.");
define("_MD_AM_PASSLEVEL1", "Zeer zwak(onveilig)");
define("_MD_AM_PASSLEVEL2", "Zwak");
define("_MD_AM_PASSLEVEL3", "Redelijk");
define("_MD_AM_PASSLEVEL4", "Sterk");
define("_MD_AM_PASSLEVEL5", "Streng");
define("_MD_AM_PASSLEVEL6", "Geen classificatie");

define("_MD_AM_RANKW", "Positie afbeelding max breedte (pixel)");
define("_MD_AM_RANKH", "Positie afbeelding max hoogte (pixel)");
define("_MD_AM_RANKMAX", "Positie afbeelding max bestandsgrootte (byte)");

define("_MD_AM_MULTILANGUAGE", "Meerdere talen instellingen");
define("_MD_AM_ML_ENABLE", "Meerdere talen mogelijk");
define("_MD_AM_ML_ENABLEDSC", "Stel in op Ja om meerdere talen in de website mogelijk te maken.");
define("_MD_AM_ML_TAGS", "Meerdere talen tags");
define("_MD_AM_ML_TAGSDSC", "Voer de tags in die gebruikt moeten worden op deze website, gescheiden door een komma. Bijvoorbeeld, dit wordt gebruikt om de tags te defineren voor het gebruik van Nederlands en Engels: nl,en");
define("_MD_AM_ML_NAMES", "Taalnamen");
define("_MD_AM_ML_NAMESDSC", "Voer de namen in van de te gebruiken talen, gescheiden door een komma. Bijv.: nederlands,english");
define("_MD_AM_ML_CAPTIONS", "Talen opschrift");
define("_MD_AM_ML_CAPTIONSDSC", "Voer de opschrifen in die u wilt gebruiken voor deze talen. Bijv.: Nederlands,English");
define("_MD_AM_ML_CHARSET", "Charsets");
define("_MD_AM_ML_CHARSETDSC", "Voer de charsets van deze talen in");

define("_MD_AM_REMEMBERME", "Activeer de 'Onhoud mij' optie in het inlogblok.");
define("_MD_AM_REMEMBERMEDSC", "De 'Onhoud mij' optie is niet helemaal veilig, gebruik is op eigen risico!");

define("_MD_AM_PRIVDPOLICY", "Activeer de websites 'Privacy verklaring'.");
define("_MD_AM_PRIVDPOLICYDSC", "De 'Privacy verklaring' moet worden aangepast aan uw website en geactiveerd wanneer u registraties op uw website toestaat.");
define("_MD_AM_PRIVPOLICY", "Voer uw websites 'Privacy verklaring' in.");
define("_MD_AM_PRIVPOLICYDSC", "");

define("_MD_AM_WELCOMEMSG", "Verstuur een welkomsbericht aan nieuwe geregistreerde gebruikers");
define("_MD_AM_WELCOMEMSGDSC", "Verstuur een welkomsbericht aan nieuwe geregistreerde gebruikers wanneer het account wordt geactiveerd. De inhoud van dit bericht kan worden ingesteld in de volgende optie.");
define("_MD_AM_WELCOMEMSG_CONTENT", "Inhoud van het welkomsbericht");
define("_MD_AM_WELCOMEMSG_CONTENTDSC", "U kunt dit bericht, dat wordt verzonden aan nieuwe gebruikers, aanpassen. U kunt de volgende tags gebruiken: <br /><br />- {UNAME} = gebruikersnaam van de gebruiker<br />- {X_UEMAIL} = email van de gebruiker<br />- {X_ADMINMAIL} = emailadres van de webmaster<br />- {X_SITENAME} = naam van de website<br />- {X_SITEURL} = URL van de website");

define("_MD_AM_SEARCH_USERDATE", "Toon gebruiker en datum in zoekresultaten");
define("_MD_AM_SEARCH_USERDATEDSC", "");
define("_MD_AM_SEARCH_NO_RES_MOD", "Toon modules zonder match in zoekresultaten");
define("_MD_AM_SEARCH_NO_RES_MODDSC", "");
define("_MD_AM_SEARCH_PER_PAGE", "Item per pagina in zoekresultaten");
define("_MD_AM_SEARCH_PER_PAGEDSC", "");

define("_MD_AM_EXT_DATE", "Wilt u gebruikmaken van een uitgebreide/lokale datum functie?");
define("_MD_AM_EXT_DATEDSC", "Let op: bij activering van deze optie zal ImpressCMS een extern datumscript gebruiken, echter <b>ALLEEN</b> wanneer u dit script gebruikt in uw website.<br />Bezoek alstublieft <a href='http://wiki.impresscms.org/index.php?title=Extended_date_function'>extended date function</a> voor meer informatie.");

define("_MD_AM_EDITOR_DEFAULT", "Standaard Editor");
define("_MD_AM_EDITOR_DEFAULT_DESC", "Selecteer de standaard  Editor voor de hele website.");

define("_MD_AM_EDITOR_ENABLED_LIST", "Editors inschakelen");
define("_MD_AM_EDITOR_ENABLED_LIST_DESC", "Selecteer de mogelijke editors bij de modules (Wanneer de module een editor keuze toestaat.)");

define("_MD_AM_ML_AUTOSELECT_ENABLED", "Selecteer automatisch de taal, afhankelijk van de browser instelling");

define("_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE", "Sta anonieme gebruikers toe profielen van gebruikers te zien.");

define("_MD_AM_ENC_TYPE", "Wachtwoord versleuteling wijzigen (Standaard is SHA256)");
define("_MD_AM_ENC_TYPEDSC", "Wijzig het algoritme dat wordt gebruikt om gebruikers wachtwoorden te versleutelen.<br />Het wijzigen van deze instelling zal alle bestaande wachtwoorden ongeldig maken! Alle gebruikers zullen hun wachtwoord moeten resetten nadat de wijziging is aangebracht.");
define("_MD_AM_ENC_MD5", "MD5 (niet aangeraden)");
define("_MD_AM_ENC_SHA256", "SHA 256 (Aangeraden)");
define("_MD_AM_ENC_SHA384", "SHA 384");
define("_MD_AM_ENC_SHA512", "SHA 512");
define("_MD_AM_ENC_RIPEMD128", "RIPEMD 128");
define("_MD_AM_ENC_RIPEMD160", "RIPEMD 160");
define("_MD_AM_ENC_WHIRLPOOL", "WHIRLPOOL");
define("_MD_AM_ENC_HAVAL1284", "HAVAL 128,4");
define("_MD_AM_ENC_HAVAL1604", "HAVAL 160,4");
define("_MD_AM_ENC_HAVAL1924", "HAVAL 192,4");
define("_MD_AM_ENC_HAVAL2244", "HAVAL 224,4");
define("_MD_AM_ENC_HAVAL2564", "HAVAL 256,4");
define("_MD_AM_ENC_HAVAL1285", "HAVAL 128,5");
define("_MD_AM_ENC_HAVAL1605", "HAVAL 160,5");
define("_MD_AM_ENC_HAVAL1925", "HAVAL 192,5");
define("_MD_AM_ENC_HAVAL2245", "HAVAL 224,5");
define("_MD_AM_ENC_HAVAL2565", "HAVAL 256,5");

//Content Manager
define("_MD_AM_CONTMANAGER", "Inhoud beheer");
define("_MD_AM_DEFAULT_CONTPAGE", "Standaard pagina");
define("_MD_AM_DEFAULT_CONTPAGEDSC", "Selecteer de standaard pagina die de gebruiker getoond wordt in de inhoud beheer. Laat leeg om Inhoud beheer de laatst aangemaakte pagina te laten tonen.");
define("_MD_AM_CONT_SHOWNAV", "Toon navigatie menu aan de gebruikerszijde?");
define("_MD_AM_CONT_SHOWNAVDSC", "Selecteer ja om het inhoud beheer navigatie menu te tonen.");
define("_MD_AM_CONT_SHOWSUBS", "Toon gerelateerde pagina\'s?");
define("_MD_AM_CONT_SHOWSUBSDSC", "Selecteer ja om gerelateerde pagina linken te tonen.");
define("_MD_AM_CONT_SHOWPINFO", "Toon inzender en publicatie informatie?");
define("_MD_AM_CONT_SHOWPINFODSC", "Selecteer ja om in de pagina informatie over de inzender en de publicatie te tonen.");
define("_MD_AM_CONT_ACTSEO", "Gebruik menu titel in plaats van de id in de URL (verbeter seo)?");
define("_MD_AM_CONT_ACTSEODSC", "Selecteer ja om de waarde van de menu titel in plaats van het id te gebruiken in de URL van de pagina.");
//Captcha (Security image)
define('_MD_AM_USECAPTCHA', 'Wilt u CAPTCHA gebruiken in het registratie formulier?');
define('_MD_AM_USECAPTCHADSC', 'Selecteer ja om CAPTCHA (anti-spam) in te schakelen in het registratie formulier.');
define('_MD_AM_USECAPTCHAFORM', 'Wilt u CAPTCHA gebruiken in reactie formulieren?');
define('_MD_AM_USECAPTCHAFORMDSC', 'Selecteer ja om CAPTCHA (anti-spam) in te schakelen in reactie formulieren, om spam te voorkomen.');
define('_MD_AM_ALLWHTSIG', 'Sta toe externe afbeeldingen en HTML te tonen in de handtekening?');
define('_MD_AM_ALLWHTSIGDSC', 'Wanneer sommmige aanvallers een externe afbeelding posten door [img] te gebruiken, dan kunnen ze IPs of User-Agents van gebruikers weten die uw website hebben bezocht.<br />Toestaan van HTML kan Script Insertion kwetsbaarheid veroozaken wanneer kwaadwillenden hun handtekening wijzigen.');
define('_MD_AM_ALLWSHOWSIG', 'Wilt u uw gebruikers toestaan om een handtekening te gebruiken in hun profiel en posten, op uw website?');
define('_MD_AM_ALLWSHOWSIGDSC', 'Wanneer u deze optie inschakeld dan zijn uw gebruikers instaat om een persoonlijke handtekening toe te voegen onder hun posten, wanneer zij dat wensen.');
// < personalizações > fabio - Sat Apr 28 11:55:00 BRT 2007 11:55:00
define("_MD_AM_PERSON", "Personalisering");
define("_MD_AM_GOOGLE_ANA", "Google Analytcs");
define("_MD_AM_GOOGLE_ANA_DESC", 'Geef hier de van Google verkregen ID-code in om het eigendom van de website te bevestigen. Hiermee kunt u de volledige statistieken en fouten pagina zien. Vul het rode deel van de id-code in, zoals: UA - <font color=#FF0000><b>xxxxxxxxxxxx-x</b></font>".<br />Meer informatie op <a href="http://www.google.com/webmasters/" target="_blank">http://www.google.com/webmasters</a>.');
define("_MD_AM_LLOGOADM", "Administratiemenu linker logo");
define("_MD_AM_LLOGOADM_DESC", " Selecteer een afbeelding om te gebruiken in de linker bovenhoek van het administratiemenu. <br /><b><i> Om een afbeelding te selecteren of te verzenden dient u minimaal een afbeeldingen categorie aan te maken in systeem --> afbeeldingen manager.</b></i> ");
define("_MD_AM_LLOGOADM_URL", "Administratiemenu linker logo URL");
define("_MD_AM_LLOGOADM_ALT", "Administratiemenu linker logo titel");
define("_MD_AM_RLOGOADM", "Administratiemenu rechter logo");
define("_MD_AM_RLOGOADM_DESC", " Selecteer een afbeelding om te gebruiken in de rechter bovenhoek van het administratiemenu. <br /><b><i> Om een afbeelding te selecteren of te verzenden dient u minimaal een afbeeldingen categorie aan te maken in systeem --> afbeeldingen manager.</b></i> ");
define("_MD_AM_RLOGOADM_URL", "Administratiemenu rechter logo URL");
define("_MD_AM_RLOGOADM_ALT", "Administratiemenu rechter logo Link Titel");
define("_MD_AM_METAGOOGLE", "Google Meta Tag");
define("_MD_AM_METAGOOGLE_DESC", 'Geef hier de van Google verkregen ID-code in om het eigendom van de website te bevestigen. Hiermee kunt u de volledige statistieken en fouten pagina zien. Write down the id-code, like: <small>meta name="verify-v1" content="<font color=#FF0000><b>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</b></font>" </small><br />(you need to write the red bold id-code).<br />Meer informatie op <a href="http://www.google.com/webmasters/" target="_blank">http://www.google.com/webmasters</a>.');

define("_MD_AM_RSSLOCAL", "RSS URL voor admin");
define("_MD_AM_RSSLOCAL_DESC", "Webadres waar de rss is te vinden om te worden getoond onder de beheerpagina.");
define("_MD_AM_FOOTADM", "Administratiemenu footer");
define("_MD_AM_FOOTADM_DESC", "Te tonen inhoud in de footer op de beheerpagina.");
define("_MD_AM_EMAILTTF", "Bron lettertypes voor de e-mailafbeelding");
define("_MD_AM_EMAILTTF_DESC", "Kies een van de lettertypes uit de onderstaande lijst om te worden gebruikt door de email sfbeelding generator.<br />Opmerking: Deze bron zal alleen werken wanneer de optie <b><i>Bescherm e-mails tegen SPAM?</i></b> is ingeschakeld.");
define("_MD_AM_EMAILLEN", "Letter grootte");
define("_MD_AM_EMAILLEN_DESC", "Voer het aantal tekens in dat wordt getoond.");
define("_MD_AM_EMAILCOLOR", "Letterkleur voor de e-mail afbeelding");
define("_MD_AM_EMAILCOLOR_DESC", "Kies een kleur voor de te tonen e-mail afbeelding.<br />Opmerking: Deze bron zal alleen werken wanneer de optie <b><i>Bescherm e-mails tegen SPAM?</i></b> is ingeschakeld.");
define("_MD_AM_EMAILSHADOW", "Shadow color for the email image");
define("_MD_AM_EMAILSHADOW_DESC", "Kies een kleur voor de te tonen schaduw van de e-mail afbeelding. <br />Laat leeg wanneer u geen schaduw wenst.<br />Opmerking: Deze bron zal alleen werken wanneer de optie <b><i>Bescherm e-mails tegen SPAM?</i></b> is ingeschakeld.");
define("_MD_AM_SHADOWX", "X offset voor schaduw");
define("_MD_AM_SHADOWX_DESC", "Voer een waarde in dat de horizontale offset aangeeft van de email schaduw in pixels.<br />Opmerking: Deze bron zal alleen werken wanneer de optie <b><i>Schaduw kleur voor e-mail afbeelding</i></b> is opgegeven.");
define("_MD_AM_SHADOWY", "Y offset voor schaduw");
define("_MD_AM_SHADOWY_DESC", "Voer een waarde in dat de verticale offset aangeeft van de email schaduw in pixels.<br />Opmerking: Deze bron zal alleen werken wanneer de optie <b><i>Schaduw kleur voor e-mail afbeelding</i></b> is opgegeven.");
define("_MD_AM_EDITREMOVEBLOCK", "Wijzigen en verwijderen van blokken vanaf de gebruikerszijde?");
define("_MD_AM_EDITREMOVEBLOCKDSC", "Door deze optie in te schakelen, ziet u twee icoontje in de bloktitel met een directe toegang tot het verwijderen of aanpassen van het blok.");

define("_MD_AM_EMAILPROTECT", "Bescherm e-mails tegen SPAM?");
define("_MD_AM_EMAILPROTECTDSC", "Door \"Ja\" te selecteren zullen alle e-mails die deel uitmaken van de inhoud van de website automatisch worden omgezet in afbeeldingen om SPAM te voorkomen");
define("_MD_AM_MULTLOGINPREVENT", "Inschakelen multilogin verbieden?");
define("_MD_AM_MULTLOGINPREVENTDSC", "Door deze optie in te schakelen, is het voor gebruikers niet mogelijk om nogmaals in te loggen alvorens uit te loggen uit de eerste sessie. Dit is handig om te voorkomen dat iemand zich met de zelde gebruikersnaam met de website verbindt.");
define("_MD_AM_MULTLOGINMSG", "Multilogin redirection bericht:");
define("_MD_AM_MULTLOGINMSG_DESC", "Bericht dat wordt getoond aan de gebruiker die probeert in te loggen met een gebruikersnaam die reeds in gebruik is. <br><i>Dez optie zal alleen functioneren wanneer bovenstaande is ingeschakeld.</i>");
define("_MD_AM_GRAVATARALLOW", "Gebruik van GRAVATAR toestaan?");
define("_MD_AM_GRAVATARALWDSC", "Door dit toe te staan, kunnen gebruikers een aan hun e-mailadres gekoppeld centraal opgeslage avatar/pasfoto gebruiken.");

define("_MD_AM_SHOW_ICMSMENU", "Toon ImpressCMS Project drop down menu?");
define("_MD_AM_SHOW_ICMSMENU_DESC", "Selecteer NEE om het drop down menu niet te tonen en JA om dit wel te tonen.");

define("_MD_AM_SHORTURL", "URL's afkorten?");
define("_MD_AM_SHORTURLDSC", "Door \"Ja\" te selecteren worden alle aan de website toegevoegde url en links in lengte ingekort.");
define("_MD_AM_URLLEN", "Max Lengte");
define("_MD_AM_URLLEN_DESC", "Het maximale aantal karakters dat wordt genegeerd bij het afkorten, langere URK's zullen worden in gekort.");
define("_MD_AM_PRECHARS", "Voor karakters");
define("_MD_AM_PRECHARS_DESC", "Aantal karakters dat wordt getoond in het begin van de afgekorte URL's.");
define("_MD_AM_LASTCHARS", "Na karakters");
define("_MD_AM_LASTCHARS_DESC", "Aantal karakters dat wordt getoond in het einde van de afgekorte URL's.");
define("_MD_AM_SIGMAXLENGTH", "Maximum aantal karakters in gebruikers handtekeningen?");
define("_MD_AM_SIGMAXLENGTHDSC", "Hier kunt u de lengte van uw gebruikers handtekeningen instellen.<br /> iedere karakter meer zal genegeerd worden.<br /><i>Wees voorzichtig, lange handtekeningen kunnen vaak de layout verstoren...</i>");

define("_MD_AM_AUTHOPENID", "OpenID authenticatie inschakelen");
define("_MD_AM_AUTHOPENIDDSC", "Selecteer Ja om OpenID authenticatie mogelijk te maken. Dit  zal gebruikers toestaan in te loggen met hun OpenID account. Voor meer informatie over deOpenID Integratie in ImpressCMS, bezoekt u alstublieft <a href='http://wiki.impresscms.org/index.php?title=ImpressCMS_OpenID/nl'>onze wiki</a>.");
define("_MD_AM_USE_GOOGLE_ANA", "Google Analytics toestaan?");
define("_MD_AM_USE_GOOGLE_ANA_DESC", "");

// added in 1.1.2
define("_MD_AM_UNABLEENCCLOSED", "Bijwerken van de database is mislukt, U kunt de wachtwoord encriptie niet wijzigen terwijl de website gesloten is");

######################## Added in 1.2 ###################################
define("_MD_AM_CAPTCHA", "Captcha instellingen");
define("_MD_AM_CAPTCHA_MODE", "Captcha modus");
define("_MD_AM_CAPTCHA_MODEDSC", "Selecteer het type Captcha voor uw website");
define("_MD_AM_CAPTCHA_SKIPMEMBER", "Captcha vrije groepen");
define("_MD_AM_CAPTCHA_SKIPMEMBERDSC", "Selecteer groepen voor wie een captcha niet is vereist. Deze groepen zullen het captcha veld niet zien.");
define("_MD_AM_CAPTCHA_CASESENS", "Hoofdletter gevoeligheid");
define("_MD_AM_CAPTCHA_CASESENSDSC", "Karakters in afbeeldingsmodus zijn hoofdletter gevoelig.");
define("_MD_AM_CAPTCHA_MAXATTEMP", "Maximum aantal pogingen");
define("_MD_AM_CAPTCHA_MAXATTEMPDSC", "Maximum aantal pogingen voor iedere sessie.");
define("_MD_AM_CAPTCHA_NUMCHARS", "Max. aantal karakters?");
define("_MD_AM_CAPTCHA_NUMCHARSDSC", "Maximaal aantal karakters om te genereren.");
define("_MD_AM_CAPTCHA_FONTMIN", "Min. lettergrootte");
define("_MD_AM_CAPTCHA_FONTMINDSC", "");
define("_MD_AM_CAPTCHA_FONTMAX", "Max. lettergrootte");
define("_MD_AM_CAPTCHA_FONTMAXDSC", "");
define("_MD_AM_CAPTCHA_BGTYPE", "Achtergrond type");
define("_MD_AM_CAPTCHA_BGTYPEDSC", "Achtergrond type in afbeeldingsmodus");
define("_MD_AM_CAPTCHA_BGNUM", "Achtergrond afbeeldingen");
define("_MD_AM_CAPTCHA_BGNUMDSC", "Aantal achtergrond afbeeldingen om te genereren");
define("_MD_AM_CAPTCHA_POLPNT", "Polygoon punten");
define("_MD_AM_CAPTCHA_POLPNTDSC", "Aantal polygoon punten om te genereren");
define("_MD_AM_BAR", "Balk");
define("_MD_AM_CIRCLE", "Cirkel");
define("_MD_AM_LINE", "Lijn");
define("_MD_AM_RECTANGLE", "Rechthoek");
define("_MD_AM_ELLIPSE", "Elips");
define("_MD_AM_POLYGON", "Polygoon");
define("_MD_AM_RANDOM", "Standaard");
define("_MD_AM_CAPTCHA_IMG", "Afbeelding");
define("_MD_AM_CAPTCHA_TXT", "Tekst");
define("_MD_AM_CAPTCHA_OFF", "Uitgeschakeld");
define("_MD_AM_CAPTCHA_SKIPCHAR", "Karakters overslaan");
define("_MD_AM_CAPTCHA_SKIPCHARDSC", "Deze optie zorgt dat de ingevoerde karakters worden overgeslagen bij het genereren van een Captcha");
define('_MD_AM_PAGISTYLE', 'Stijlvan de pagina linken:');
define('_MD_AM_PAGISTYLE_DESC', 'Selecteer de stijl van de pagina linken.');
define('_MD_AM_ALLWCHGUNAME', 'Gebruikers toestaan om weergave naam te wijzigen?');
define('_MD_AM_ALLWCHGUNAMEDSC', '');
define("_MD_AM_JALALICAL", "Uitgebreide kalender met Jalali gebruiken?");
define("_MD_AM_JALALICALDSC", "Door dit te selecteren, gebruikt u een uitgebreide kalender in formulieren.<br />Houd er rekening mee dat deze kalender mogelijk niet correct werkt met alle browsers.");
define("_MD_AM_NOMAILPROTECT", "Geen");
define("_MD_AM_GDMAILPROTECT", "GD beveiliging");
define("_MD_AM_REMAILPROTECT", "her-Captcha");
define("_MD_AM_RECPRVKEY", "her-Captcha privé api code");
define("_MD_AM_RECPRVKEY_DESC", "");
define("_MD_AM_RECPUBKEY", "her-Captcha publieke api code");
define("_MD_AM_RECPUBKEY_DESC", "");
define("_MD_AM_CONT_NUMPAGES", "Aantal pagina's om weer te geven in tag modus");
define("_MD_AM_CONT_NUMPAGESDSC", "Defineer aantal pagina\'s om weer te geven aan de gebruikerszijde in de tag modus.");
define("_MD_AM_CONT_TEASERLENGTH", "Teaser lengte");
define("_MD_AM_CONT_TEASERLENGTHDSC", "Aantal karakters van de pagina teaser in de lijst bij tag modus.<br />Stel in op 0 voor geen limiet.");
define("_MD_AM_STARTPAGEDSC", "Selecteer de gewenste module of pagina al sstartpagina voor iedere groep.");
define("_MD_AM_DELUSRES", "Verwijder inactieve gebruikers");
define("_MD_AM_DELUSRESDSC", "Deze optie zal alle gebruikers verwijderen die zich hebben geregistreerd, maar hun account nog niet hebben geactiveerd na X dagen.<br />Voer het aantal dagen in.");
define("_MD_AM_PLUGINS", "Plugin beheerder");
define("_MD_AM_SELECTSPLUGINS", "Selecteer de toegestane plugins om te gebruiken");
define("_MD_AM_SELECTSPLUGINS_DESC", "Selecteer hier de plugins die worden gebruikt om uw tekst op te maken.");
define("_MD_AM_GESHI_DEFAULT", "Selecteer de plugin om te gebruiken voor geshi");
define("_MD_AM_GESHI_DEFAULT_DESC", "GeSHi (Generic Syntax Hilighter) is een syntax highlighter voor uw codes.");
define("_MD_AM_SELECTSHIGHLIGHT", "Selecteer het type als highlighter voor de codes");
define("_MD_AM_SELECTSHIGHLIGHT_DESC", "Selecteer de plugin die word gebruikt om uw codes te highlighten.");
define("_MD_AM_HIGHLIGHTER_GESHI", "GeSHi highlighter");
define("_MD_AM_HIGHLIGHTER_PHP", "php highlighter");
define("_MD_AM_HIGHLIGHTER_OFF", "Uitgeschakeld");
define('_MD_AM_DODEEPSEARCH', "'diep' zoeken toestaan?");
define('_MD_AM_DODEEPSEARCHDSC', "Would you like your initial search results page to indicate how many hits were found in each module?  Note: turning this on can slow down the search process!");
define('_MD_AM_NUMINITSRCHRSLTS', "Aantal van initiele zoek resultaten: (voor 'shallow' zoeken)");
define('_MD_AM_NUMINITSRCHRSLTSDSC', "'Shallow' doorzoekingen worden sneller gemaakt door het aantal teruggegeven resultaten per module terug te brengen in de initiele zoekpagina.");
define('_MD_AM_NUMMDLSRCHRESULTS', "Aantal zoekresultaten per pagina:");
define('_MD_AM_NUMMDLSRCHRESULTSDSC', "Dit geeft aan hoeveel hits op de pagina worden weergegeven als de specifieke module zoekresultaten worden getoond.");
define('_MD_AM_ADMIN_DTHEME', 'Admin Thema');
define('_MD_AM_ADMIN_DTHEME_DESC', '');
define('_MD_AM_CUSTOMRED', 'Gebruik Ajax doorstuur methode?');
define('_MD_AM_CUSTOMREDDSC', '');
define('_MD_AM_DTHEMEDSC', 'Standaard thema dat wordt gebruikt op uw website.');

// Added in 1.2

// HTML Purifier preferences
define("_MD_AM_PURIFIER", "HTMLPurifier Settings");
define("_MD_AM_PURIFIER_ENABLE", "Enable HTML Purifier");
define("_MD_AM_PURIFIER_ENABLEDSC", "Select 'yes' to enable the HTML Purifier filters, disabling this could leave your site vulnerable to attack if you allow your HTML content");
//HTML section
define("_MD_AM_PURIFIER_HTML_TIDYLEVEL", "HTML Tidy Level");
define("_MD_AM_PURIFIER_HTML_TIDYLEVELDSC", "General level of cleanliness the Tidy module should enforce.<br /><br />
None = No extra tidying should be done,<br />Light = Only fix elements that would be discarded otherwise due to lack of support in doctype,<br />
Medium = Enforce best practices,<br />Heavy = Transform all deprecated elements and attributes to standards compliant equivalents.");
define("_MD_AM_PURIFIER_NONE", "None");
define("_MD_AM_PURIFIER_LIGHT", "Light");
define("_MD_AM_PURIFIER_MEDIUM", "Medium (recommended)");
define("_MD_AM_PURIFIER_HEAVY", "Heavy");
define("_MD_AM_PURIFIER_HTML_DEFID", "HTML Definition ID");
define("_MD_AM_PURIFIER_HTML_DEFIDDSC", "Sets the default ID name of the purifier configuration (leave as is, unless you are creating custom configurations & that you know what you are doing");
define("_MD_AM_PURIFIER_HTML_DEFREV", "HTML Definition Revision Number");
define("_MD_AM_PURIFIER_HTML_DEFREVDSC", "Example: revision 3 is more up-to-date than revision 2. Thus, when this gets incremented, the cache handling is smart enough to clean up any older revisions of your definition as well as flush the cache.<br />You can leave this as is unless you know what you are doing & are editing the purifier files directly");
define("_MD_AM_PURIFIER_HTML_DOCTYPE", "HTML DocType");
define("_MD_AM_PURIFIER_HTML_DOCTYPEDSC", "Doctype to use during filtering. Technically speaking this is not actually a doctype (as it does not identify a corresponding DTD), but we are using this name for sake of simplicity. When non-blank, this will override any older directives like XHTML or HTML (Strict).");
define("_MD_AM_PURIFIER_HTML_ALLOWELE", "Allowed Elements");
define("_MD_AM_PURIFIER_HTML_ALLOWELEDSC", "Whitelist of HTML Elements that are allowed to be posted. Any elements entered here will not be filtered out of user posts. You should only allow safe html elements.");
define("_MD_AM_PURIFIER_HTML_ALLOWATTR", "Allowed Attributes");
define("_MD_AM_PURIFIER_HTML_ALLOWATTRDSC", "Whitelist of HTML Attributes that are allowed to be posted. Any attributes entered here will not be filtered out of user posts. You should only allow safe html attirbutes.<br /><br />Format your attributes as follows:<br />element.attribute (example: div.class) will allow you to use the class attribute with div tags. you can also use wildcards: *.class for example will allow the class attribute in all allowed elements.");
define("_MD_AM_PURIFIER_HTML_FORBIDELE", "Forbidden Elements");
define("_MD_AM_PURIFIER_HTML_FORBIDELEDSC", "This is the logical inverse of  HTML.Allowed Elements, and it will override that directive, or any other directive.");
define("_MD_AM_PURIFIER_HTML_FORBIDATTR", "Forbidden Attributes");
define("_MD_AM_PURIFIER_HTML_FORBIDATTRDSC", " While this directive is similar to  HTML Allowed Attributes, for forwards-compatibility with XML, this attribute has a different syntax.<br />Instead of tag.attr, use tag@attr. To disallow href attributes in a tags, set this directive to a@href.<br />You can also disallow an attribute globally with attr or *@attr (either syntax is fine; the latter is provided for consistency with HTML Allowed Attributes).<br /><br />Warning: This directive complements  HTML Forbidden Elements, accordingly, check out that directive for a discussion of why you should think twice before using this directive.");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTH", "Max Image Length");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTHDSC", "This directive controls the maximum number of pixels in the width and height attributes in img tags. This is in place to prevent imagecrash attacks, disable with 0 at your own risk. ");
define("_MD_AM_PURIFIER_HTML_SAFEEMBED", "Enable Safe Embed");
define("_MD_AM_PURIFIER_HTML_SAFEEMBEDDSC", "Whether or not to permit embed tags in documents, with a number of extra security features added to prevent script execution. This is similar to what websites like MySpace do to embed tags. Embed is a proprietary element and will cause your website to stop validating. You probably want to enable this with HTML Safe Object. Highly experimental.");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECT", "Enable Safe Object");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECTDSC", "Whether or not to permit object tags in documents, with a number of extra security features added to prevent script execution. This is similar to what websites like MySpace do to object tags. You may also want to enable  HTML Safe Embed for maximum interoperability with Internet Explorer, although embed tags will cause your website to stop validating. Highly experimental.");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATA", "Relax DTD Name Attribute Parsing");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATADSC", "The W3C specification DTD defines the name attribute to be CDATA, not ID, due to limitations of DTD. In certain documents, this relaxed behavior is desired, whether it is to specify duplicate names, or to specify names that would be illegal IDs (for example, names that begin with a digit.) Set this configuration directive to yes to use the relaxed parsing rules.");
// URI Section
define("_MD_AM_PURIFIER_URI_DEFID", "URI Definition ID");
define("_MD_AM_PURIFIER_URI_DEFIDDSC", "Unique identifier for a custom-built URI definition. If you want to add custom URIFilters, you must specify this value. (leave as is unless you know what you are doing)");
define("_MD_AM_PURIFIER_URI_DEFREV", "URI Definition Revision Number");
define("_MD_AM_PURIFIER_URI_DEFREVDSC", "Example: revision 3 is more up-to-date than revision 2. Thus, when this gets incremented, the cache handling is smart enough to clean up any older revisions of your definition as well as flush the cache.<br />You can leave this as is unless you know what you are doing & are editing the purifier files directly");
define("_MD_AM_PURIFIER_URI_DISABLE", "Disable all URI in user posts");
define("_MD_AM_PURIFIER_URI_DISABLEDSC", "Disabling URI will prevent users from posting any links whatsoever, it is not recommended to enable this except for test purposes.<br />Default is 'No'");
define("_MD_AM_PURIFIER_URI_BLACKLIST", "URI Blacklist");
define("_MD_AM_PURIFIER_URI_BLACKLISTDSC", "Enter Domain names that should be filtered (removed) from user posts.");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEME", "Allowed URI Schemes");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEMEDSC", "Whitelist that defines the schemes that a URI is allowed to have. This prevents XSS attacks from using pseudo-schemes like javascript or mocha.<br />Accepted values (http, https, ftp, mailto, nntp, news)");
define("_MD_AM_PURIFIER_URI_HOST", "URI Host Domain");
define("_MD_AM_PURIFIER_URI_HOSTDSC", "Enter URI Host. Leave blank to disable!");
define("_MD_AM_PURIFIER_URI_BASE", "URI Base Domain");
define("_MD_AM_PURIFIER_URI_BASEDSC", "Enter URI Base. Leave blank to disable!");
define("_MD_AM_PURIFIER_URI_DISABLEEXT", "Disable External Links");
define("_MD_AM_PURIFIER_URI_DISABLEEXTDSC", "Disables links to external websites. This is a highly effective anti-spam and anti-pagerank-leech measure, but comes at a hefty price: nolinks or images outside of your domain will be allowed.<br />Non-linkified URIs will still be preserved. If you want to be able to link to subdomains or use absolute URIs, enable URI Host for your website.");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRES", "Disable External Resources");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRESDSC", "Disables the embedding of external resources, preventing users from embedding things like images from other hosts. This prevents access tracking (good for email viewers), bandwidth leeching, cross-site request forging, goatse.cx posting, and other nasties, but also results in a loss of end-user functionality (they can't directly post a pic they posted from Flickr anymore). Use it if you don't have a robust user-content moderation team. ");
define("_MD_AM_PURIFIER_URI_DISABLERES", "Disable Resources");
define("_MD_AM_PURIFIER_URI_DISABLERESDSC", "Disables embedding resources, essentially meaning no pictures. You can still link to them though. See  URI Disable External Resources for why this might be a good idea.");
define("_MD_AM_PURIFIER_URI_MAKEABS", "URI Make Absolute");
define("_MD_AM_PURIFIER_URI_MAKEABSDSC", "Converts all URIs into absolute forms. This is useful when the HTML being filtered assumes a specific base path, but will actually be viewed in a different context (and setting an alternate base URI is not possible).<br /><br />URI Base must be enabled for this directive to work.");
// Filter Section
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESC", "Escape Dangerous Characters in StyleBlocks");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESCDSC", "Whether or not to escape the dangerous characters <, > and &  as \3C, \3E and \26, respectively. This can be safely set to false if the contents of StyleBlocks will be placed in an external stylesheet, where there is no risk of it being interpreted as HTML.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPE", "Enter StyleBlocks Scope");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPEDSC", "If you would like users to be able to define external stylesheets, but only allow them to specify CSS declarations for a specific node and prevent them from fiddling with other elements, use this directive.<br />It accepts any valid CSS selector, and will prepend this to any CSS declaration extracted from the document.<br /><br />For example, if this directive is set to #user-content and a user uses the selector a:hover, the final selector will be #user-content a:hover.<br /><br />The comma shorthand may be used; consider the above example, with #user-content, #user-content2, the final selector will be #user-content a:hover, #user-content2 a:hover.");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBE", "Allowed Embedding YouTube Video");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBEDSC", "This directive enables YouTube video embedding in HTML Purifier. Check <a href='http://htmlpurifier.org/docs/enduser-youtube.html'>this</a> document on embedding videos for more information on what this filter does.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLK", "Extract Style Blocks?");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKDSC", "Requires CSSTidy Plugin to be installed).<br /><br />This directive turns on the style block extraction filter, which removes style blocks from input HTML, cleans them up with CSSTidy, and places them in the StyleBlocks context variable, for further use by you, usually to be placed in an external stylesheet, or a style block in the head of your document.<br /><br />Warning: It is possible for a user to mount an imagecrash attack using this CSS. Counter-measures are difficult; it is not simply enough to limit the range of CSS lengths (using relative lengths with many nesting levels allows for large values to be attained without actually specifying them in the stylesheet), and the flexible nature of selectors makes it difficult to selectively disable lengths on image tags (HTML Purifier, however, does disable CSS width and height in inline styling). There are probably two effective counter measures: an explicit width and height set to auto in all images in your document (unlikely) or the disabling of width and height (somewhat reasonable). Whether or not these measures should be used is left to the reader.");
define("_MD_AM_PURIFIER_FILTER_CUSTOM", "Select Custom Filters");
define("_MD_AM_PURIFIER_FILTER_CUSTOMDSC", "Select Custom Movie filters From the list");
// Core Section
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGS", "Escape Invalid Tags");
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGSDSC", "When enabled, invalid tags will be written back to the document as plain text. Otherwise, they are silently dropped.");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARS", "Escape Non ASCII Characters");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARSDSC", "This directive overcomes a deficiency in %Core.Encoding by blindly converting all non-ASCII characters into decimal numeric entities before converting it to its native encoding. This means that even characters that can be expressed in the non-UTF-8 encoding will be entity-ized, which can be a real downer for encodings like Big5. It also assumes that the ASCII repetoire is available, although this is the case for almost all encodings. Anyway, use UTF-8!");
define("_MD_AM_PURIFIER_CORE_HIDDENELE", "Enable HTML Hidden Elements");
define("_MD_AM_PURIFIER_CORE_HIDDENELEDSC", "This directive is a lookup array of elements which should have their contents removed when they are not allowed by the HTML definition. For example, the contents of a script tag are not normally shown in a document, so if script tags are to be removed, their contents should be removed to. This is opposed to a b  tag, which defines some presentational changes but does not hide its contents.");
define("_MD_AM_PURIFIER_CORE_COLORKEYS", "Colour Keywords");
define("_MD_AM_PURIFIER_CORE_COLORKEYSDSC", "Lookup array of color names to six digit hexadecimal number corresponding to color, with preceding hash mark. Used when parsing colors.");
define("_MD_AM_PURIFIER_CORE_REMINVIMG", "Remove Invalid Image");
define("_MD_AM_PURIFIER_CORE_REMINVIMGDSC", "This directive enables pre-emptive URI checking in img tags, as the attribute validation strategy is not authorized to remove elements from the document. Default = yes");
// AutoFormat Section
define("_MD_AM_PURIFIER_AUTO_AUTOPARA", "Enable Paragraph Auto Format");
define("_MD_AM_PURIFIER_AUTO_AUTOPARADSC", "This directive turns on auto-paragraphing, where double newlines are converted in to paragraphs whenever possible.<br /> Auto-paragraphing:<br /><br />* Always applies to inline elements or text in the root node,<br />* Applies to inline elements or text with double newlines in nodes that allow paragraph tags,<br />* Applies to double newlines in paragraph tags.<br /></br>p tags must be allowed for this directive to take effect. We do not use br tags for paragraphing, as that is semantically incorrect.<br />To prevent auto-paragraphing as a content-producer, refrain from using double-newlines except to specify a new paragraph or in contexts where it has special meaning (whitespace usually has no meaning except in tags like pre, so this should not be difficult.) To prevent the paragraphing of inline text adjacent to block elements, wrap them in div tags (the behavior is slightly different outside of the root node.)");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURI", "Enable Link Display");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURIDSC", "This directive turns on the in-text display of URIs in <a> tags, and disables those links. For example, <a href=\"http://example.com\">example</a> becomes example (http://example.com).");
define("_MD_AM_PURIFIER_AUTO_LINKIFY", "Enable Auto Linkify");
define("_MD_AM_PURIFIER_AUTO_LINKIFYDSC", "This directive turns on linkification, auto-linking http, ftp and https URLs. a tags with the href attribute must be allowed. ");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFY", "Enable Purifier Internal Linkify");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFYDSC", "Internal auto-formatter that converts configuration directives in syntax %Namespace.Directive to links. a tags with the href attribute must be allowed. (Leave this as is if you are not having any problems)");
define("_MD_AM_PURIFIER_AUTO_CUSTOM", "Allowed Customised AutoFormatting");
define("_MD_AM_PURIFIER_AUTO_CUSTOMDSC", "This directive can be used to add custom auto-format injectors. Specify an array of injector names (class name minus the prefix) or concrete implementations. Injector class must exist. please visit <a href='www.htmlpurifier.org'>HTML Purifier Homepage</a> for more info.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTY", "Remove Empty Elements");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYDSC", " When enabled, HTML Purifier will attempt to remove empty elements that contribute no semantic information to the document. The following types of nodes will be removed:<br /><br />
 * Tags with no attributes and no content, and that are not empty elements (remove \<a\>\</a\> but not \<br /\>), and<br />
 * Tags with no content, except for:<br />
   o The colgroup element, or<br />
   o Elements with the id or name attribute, when those attributes are permitted on those elements.<br /><br />
Please be very careful when using this functionality; while it may not seem that empty elements contain useful information, they can alter the layout of a document given appropriate styling. This directive is most useful when you are processing machine-generated HTML, please avoid using it on regular user HTML.<br /><br />
Elements that contain only whitespace will be treated as empty. Non-breaking spaces, however, do not count as whitespace. See 'Remove Empty Spaces' for alternate behavior.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSP", "Remove Non-Breaking Spaces");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPDSC", "When enabled, HTML Purifier will treat any elements that contain only non-breaking spaces as well as regular whitespace as empty, and remove them when 'Remove Empty Elements' is enabled.<br /><br />
See 'Remove Empty Nbsp Override' for a list of elements that don't have this behavior applied to them.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPT", "Remove empty Nbsp Override");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPTDSC", "When enabled, this directive defines what HTML elements should not be removed if they have only a non-breaking space in them.");
// Attribute Section
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGET", "Allowed Frame Targets");
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGETDSC", "Lookup table of all allowed link frame targets. Some commonly used link targets include _blank, _self, _parent and _top. Values should be lowercase, as validation will be done in a case-sensitive manner despite W3C's recommendation. XHTML 1.0 Strict does not permit the target attribute so this directive will have no effect in that doctype. XHTML 1.1 does not enable the Target module by default, you will have to manually enable it (see the module documentation for more details.)");
define("_MD_AM_PURIFIER_ATTR_ALLOWREL", "Allowed Document Relationships");
define("_MD_AM_PURIFIER_ATTR_ALLOWRELDSC", "List of allowed forward document relationships in the rel attribute. Common values may be nofollow or print.<br /><br />Default = external, nofollow, external nofollow & lightbox.");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSES", "Allowed Class Values");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSESDSC", "List of allowed class values in the class attribute. Leave This empty to allow all values in the Class Attribute.");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSES", "Forbidden Class Values");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSESDSC", "List of Forbidden class values in the class attribute. Leave This empty to allow all values in the Class Attribute.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMG", "Default Invalid Image");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGDSC", "This is the default image an img tag will be pointed to if it does not have a valid src attribute. In future versions, we may allow the image tag to be removed completely, but due to design issues, this is not possible right now.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALT", "Default Invalid Image Alt Tag");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALTDSC", "This is the content of the alt tag of an invalid image if the user had not previously specified an alt attribute. It has no effect when the image is valid but there was no alt attribute present.");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALT", "Default Image Alt Tag");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALTDSC", "This is the content of the alt tag of an image if the user had not previously specified an alt attribute.<br />This applies to all images without a valid alt attribute, as opposed to Default Invalid Alt Tag, which only applies to invalid images, and overrides in the case of an invalid image.<br />Default behavior with null is to use the basename of the src tag for the alt.");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATA", "Use NMTokens or CDATA specifications");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATADSC", "If null, class will auto-detect the doctype and, if matching XHTML 1.1 or XHTML 2.0, will use the restrictive NMTOKENS specification of class. Otherwise, it will use a relaxed CDATA definition. If true, the relaxed CDATA definition is forced; if false, the NMTOKENS definition is forced. To get behavior of HTML Purifier prior to 4.0.0, set this directive to false. Some rational behind the auto-detection: in previous versions of HTML Purifier, it was assumed that the form of class was NMTOKENS, as specified by the XHTML Modularization (representing XHTML 1.1 and XHTML 2.0). The DTDs for HTML 4.01 and XHTML 1.0, however specify class as CDATA. HTML 5 effectively defines it as CDATA, but with the additional constraint that each name should be unique (this is not explicitly outlined in previous specifications).");
define("_MD_AM_PURIFIER_ATTR_ENABLEID", "Allow ID Attribute?");
define("_MD_AM_PURIFIER_ATTR_ENABLEIDDSC", "Allows the ID attribute in HTML. This is disabled by default due to the fact that without proper configuration user input can easily break the validation of a webpage by specifying an ID that is already on the surrounding HTML. If you don't mind throwing caution to the wind, enable this directive, but I strongly recommend you also consider blacklisting IDs you use (ID Blacklist) or prefixing all user supplied IDs (ID Prefix).");
define("_MD_AM_PURIFIER_ATTR_IDPREFIX", "Set Attribute ID Prefix");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXDSC", "String to prefix to IDs. If you have no idea what IDs your pages may use, you may opt to simply add a prefix to all user-submitted ID attributes so that they are still usable, but will not conflict with core page IDs. Example: setting the directive to 'user_' will result in a user submitted 'foo' to become 'user_foo' Be sure to set 'Allow ID Attribute' to yes before using this.");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCAL", "Allowed Customised AutoFormatting");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCALDSC", "Temporary prefix for IDs used in conjunction with Attribute ID Prefix. If you need to allow multiple sets of user content on web page, you may need to have a seperate prefix that changes with each iteration. This way, seperately submitted user content displayed on the same page doesn't clobber each other. Ideal values are unique identifiers for the content it represents (i.e. the id of the row in the database). Be sure to add a seperator (like an underscore) at the end. Warning: this directive will not work unless Attribute ID Prefix is set to a non-empty value!");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLIST", "Attribute ID Blacklist");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLISTDSC", "Array of IDs not allowed in the document.");
// CSS Section
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANT", "Allow !important in CSS Styles");
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANTDSC", "This parameter determines whether or not !important cascade modifiers should be allowed in user CSS. If no, !important will stripped.");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKY", "Allow Tricky CSS Styles");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKYDSC", "This parameter determines whether or not to allow \"tricky\" CSS properties and values. Tricky CSS properties/values can drastically modify page layout or be used for deceptive practices but do not directly constitute a security risk. For example, display:none; is considered a tricky property that will only be allowed if this directive is set to no.");
define("_MD_AM_PURIFIER_CSS_ALLOWPROP", "Allowed CSS Properties");
define("_MD_AM_PURIFIER_CSS_ALLOWPROPDSC", "If HTML Purifier's style attributes set is unsatisfactory for your needs, you can overload it with your own list of tags to allow. Note that this method is subtractive: it does its job by taking away from HTML Purifier usual feature set, so you cannot add an attribute that HTML Purifier never supported in the first place.<br /><br />Warning: If another preference conflicts with the elements here, that preference will win and override.");
define("_MD_AM_PURIFIER_CSS_DEFREV", "CSS Definition Revision");
define("_MD_AM_PURIFIER_CSS_DEFREVDSC", "Revision identifier for your custom definition. See HTML Definition Revision for details.");
define("_MD_AM_PURIFIER_CSS_MAXIMGLEN", "CSS Max Image Length");
define("_MD_AM_PURIFIER_CSS_MAXIMGLENDSC", "This parameter sets the maximum allowed length on img tags, effectively the width and height properties. Only absolute units of measurement (in, pt, pc, mm, cm) and pixels (px) are allowed. This is in place to prevent imagecrash attacks, disable with null at your own risk. This directive is similar to HTML Max Image Length, and both should be concurrently edited, although there are subtle differences in the input format (the CSS max is a number with a unit).");
define("_MD_AM_PURIFIER_CSS_PROPRIETARY", "Allow Safe Proprietary CSS");
define("_MD_AM_PURIFIER_CSS_PROPRIETARYDSC", "Whether or not to allow safe, proprietary CSS values.");
// purifier config options
define("_MD_AM_PURIFIER_401T", "HTML 4.01 Transitional");
define("_MD_AM_PURIFIER_401S", "HTML 4.01 Strict");
define("_MD_AM_PURIFIER_X10T", "XHTML 1.0 Transitional");
define("_MD_AM_PURIFIER_X10S", "XHTML 1.0 Strict");
define("_MD_AM_PURIFIER_X11", "XHTML 1.1");
define("_MD_AM_PURIFIER_WEGAME", "WEGAME Movies");
define("_MD_AM_PURIFIER_VIMEO", "Vimeo Movies");
define("_MD_AM_PURIFIER_LOCALMOVIE", "Local Movies");
define("_MD_AM_PURIFIER_GOOGLEVID", "Google Video");
define("_MD_AM_PURIFIER_LIVELEAK", "LiveLeak Movies");

define("_MD_AM_UNABLECSSTIDY", "CSSTidy Plugin is not found, Please copy the make sure you have CSSTidy located in your plugins folder.");

// Autotasks
if (!defined('_MD_AM_AUTOTASKS')) {
    define('_MD_AM_AUTOTASKS', 'Auto taken');
}
define("_MD_AM_AUTOTASKS_SYSTEM", "Processing system");
define("_MD_AM_AUTOTASKS_HELPER", "Helper application");
define("_MD_AM_AUTOTASKS_HELPER_PATH", "Path for helper application");

define("_MD_AM_AUTOTASKS_SYSTEMDSC", "Which task system should be used to execute tasks?");
define("_MD_AM_AUTOTASKS_HELPERDSC", "For any processing system other than 'internal', please specify a helper application. However only one application will be used, so choose carefully.");
define("_MD_AM_AUTOTASKS_HELPER_PATHDSC", "If your helper application is not located in system default path, you have to specify the path to your helper application.");
define("_MD_AM_AUTOTASKS_USER", "Systeem gebruiker");
define("_MD_AM_AUTOTASKS_USERDSC", "Systeem gebruiker om te gebruiken voor taak uitvoering.");

//source editedit
define("_MD_AM_SRCEDITOR_DEFAULT", "Standaard broncode Editor");
define("_MD_AM_SRCEDITOR_DEFAULT_DESC", "Selecteer de standaard editor voor het aanpassen van de broncodes.");
