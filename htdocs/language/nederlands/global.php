<?php
// $Id: global.php 9539 2009-11-13 19:10:14Z pesianstranger $
//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT', 'Even geduld aub');
define('_FETCHING', 'Bezig met inladen...');
define('_TAKINGBACK', 'We brengen u terug naar waar u vandaan komt....');
define('_LOGOUT', 'Uitloggen');
define('_SUBJECT', 'Onderwerp');
define('_MESSAGEICON', 'Bericht icoon');
define('_COMMENTS', 'Reacties');
define('_POSTANON', 'Anoniem posten');
define('_DISABLESMILEY', 'Smileys uitschakelen');
define('_DISABLEHTML', 'Html uitschakelen');
define('_PREVIEW', 'Voorbeeld');

define('_GO', 'Start!');
define('_NESTED', 'Genest');
define('_NOCOMMENTS', 'Geen reacties');
define('_FLAT', 'Plat');
define('_THREADED', 'Ingekort');
define('_OLDESTFIRST', 'Oudste Eerst');
define('_NEWESTFIRST', 'Nieuwste Eerst');
define('_MORE', 'meer...');
define('_IFNOTRELOAD', 'Indien de pagina niet automatisch vernieuwt, klik dan <a href=%s>HIER</a>');
define('_WARNINSTALL2', 'WAARSCHUWING: de map install staat nog op uw server. Verwijder deze om veiligheidsredenen.');
define('_WARNINWRITEABLE', 'WAARSCHUWING: Bestand %s is overschrijfbaar door de server. <br />Verander de rechten voor dit bestand vanwege veiligheidsredenen.<br /> in Unix (444), in Win32 (alleen-lezen)');
define('_WARNINNOTWRITEABLE', 'WAARSCHUWING: Bestand %s is niet overschrijfbaar door de server. <br />Verander de rechten voor dit bestand vanwege functionaliteitsredenen.<br /> in Unix (777), in Win32 (schrijfbaar)');

// Error messages issued by XoopsObject::cleanVars()
define('_XOBJ_ERR_REQUIRED', '%s is vereist');
define('_XOBJ_ERR_SHORTERTHAN', '%s dient korter te zijn dan %d karakters.');

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE', 'Profiel');
define('_POSTEDBY', 'Gepost door');
define('_VISITWEBSITE', 'Bezoek website');
define('_SENDPMTO', 'Stuur PM naar %s');
define('_SENDEMAILTO', 'Verstuur E-mail naar %s');
define('_ADD', 'Toevoegen');
define('_REPLY', 'Beantwoorden');
define('_DATE', 'Datum');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN', 'Hoofd');
define('_MANUAL', 'Handleiding');
define('_INFO', 'Info');
define('_CPHOME', 'Systeembeheer');
define('_YOURHOME', 'Uw Homepage');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE', 'Wie is on-line');
define('_GUESTS', 'Gasten');
define('_MEMBERS', 'Leden');
define('_ONLINEPHRASE', '<b>%s</b> gebruiker(s) zijn on-line');
define('_ONLINEPHRASEX', '<b>%s</b> gebruiker(s) zijn op <b>%s</b>');
define('_CLOSE', 'Sluit venster');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC', 'Citaat:');

//%%%%%%	File Name admin.php 	%%%%%
define('_NOPERM', 'Sorry, u hebt geen toegang tot dit gedeelte.');

//%%%%%		Common Phrases		%%%%%
define('_NO', 'Nee');
define('_YES', 'Ja');
define('_EDIT', 'Wijzig');
define('_DELETE', 'Verwijder');
define('_SUBMIT', 'Verstuur');
define('_MODULENOEXIST', 'Geselecteerde module bestaat niet!');
define('_ALIGN', 'Uitlijnen');
define('_LEFT', 'Links');
define('_CENTER', 'Midden');
define('_RIGHT', 'Rechts');
define('_FORM_ENTER', 'Bestandsnaam %s');
// %s represents file name
define('_MUSTWABLE', 'Bestand %s moet overschrijfbaar zijn op de server!');
// Module info
define('_PREFERENCES', 'Instellingen');
define('_VERSION', 'Versie');
define('_DESCRIPTION', 'Beschrijving');
define('_ERRORS', 'Fouten');
define('_NONE', 'Geen');
define('_ON', 'op');
define('_READS', 'gelezen');
define('_SEARCH', 'Zoeken');
define('_ALL', 'Allemaal');
define('_TITLE', 'Titel');
define('_OPTIONS', 'Opties');
define('_QUOTE', 'Citeer');
define('_HIDDENC', 'Verborgen inhoud:');
define('_HIDDENTEXT', 'Deze inhoud is niet zichtbaar voor anonieme gebruikers, <a href="'.ICMS_URL.'/register.php" title="Registreren op ' . htmlspecialchars($icmsConfig ['sitename'], ENT_QUOTES) . '">registreer</a> u alstublieft om het te kunnen zien.');
define('_LIST', 'Lijst');
define('_LOGIN', 'Inloggen');
define('_USERNAME', 'Gebruikersnaam: ');
define('_PASSWORD', 'Wachtwoord: ');
define('_SELECT', 'Selecteer');
define('_IMAGE', 'Afbeelding');
define('_SEND', 'Verstuur');
define('_CANCEL', 'Annuleren');
define('_ASCENDING', 'Oplopende volgorde');
define('_DESCENDING', 'Aflopende volgorde');
define('_BACK', 'Terug');
define('_NOTITLE', 'Geen titel');

/* Image manager */
define('_IMGMANAGER', 'Afbeelding manager');
define('_NUMIMAGES', '%s afbeeldingen');
define('_ADDIMAGE', 'Voeg afbeelding bestand toe');
define('_IMAGENAME', 'Naam:');
define('_IMGMAXSIZE', 'Max grootte toegelaten (bytes):');
define('_IMGMAXWIDTH', 'Max breedte toegelaten (pixels):');
define('_IMGMAXHEIGHT', 'Max hoogte toegelaten (pixels):');
define('_IMAGECAT', 'Categorie:');
define('_IMAGEFILE', 'Afbeelding bestand:');
define('_IMGWEIGHT', 'Toon volgorde in afb. manager:');
define('_IMGDISPLAY', 'Tonen van afbeelding?');
define('_IMAGEMIME', 'MIME type:');
define('_FAILFETCHIMG', 'Kon het bestand niet uploaden %s');
define('_FAILSAVEIMG', 'Mislukt uploaden afbeelding %s in de database');
define('_NOCACHE', 'Geen cache');
define('_CLONE', 'Kloon');
define('_INVISIBLE', 'Onzichtbaar');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define('_STARTSWITH', 'Start met');
define('_ENDSWITH', 'Eindigend op');
define('_MATCHES', 'Vergelijkend');
define('_CONTAINS', 'Bevat');

//%%%%%%	File Name commentform.php 	%%%%%
define('_REGISTER', 'Registreer');

//%%%%%%	File Name xoopscodes.php 	%%%%%
define('_SIZE', 'Lettertype grootte');  // font size
define('_FONT', 'Lettertype');  // font family
define('_COLOR', 'KLEUR');  // font color
define('_EXAMPLE', 'VOORBEELD');
define('_ENTERURL', 'Type de URL van de link die u wilt invoegen:');
define('_ENTERWEBTITLE', 'Type de titel van de website:');
define('_ENTERIMGURL', 'Type de URL van het plaatje dat u wilt invoegen.');
define('_ENTERIMGPOS', 'Bepaal de plaats van het plaatje.');
define('_IMGPOSRORL', "'R' or 'r' voor rechts, 'L' or 'l' voor links, of laat het leeg.");
define('_ERRORIMGPOS', 'FOUT! Bepaal de plaats voor het plaatje.');
define('_ENTEREMAIL', 'Type het email adres dat u wilt invoegen.');
define('_ENTERCODE', 'Type de codes die je wilt toevoegen');
define('_ENTERQUOTE', 'Type de tekst die u wilt als quote.');
define('_ENTERHIDDEN', 'Type de tekst die niet zichtbaar dient te zijn voor anonieme gebruikers.');
define('_ENTERTEXTBOX', 'type uw tekst in de tekstbox.');

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 seconde');
define('_SECONDS', '%s seconden');
define('_MINUTE', '1 minuut');
define('_MINUTES', '%s minuten');
define('_HOUR', '1 uur');
define('_HOURS', '%s uren');
define('_DAY', '1 dag');
define('_DAYS', '%s dagen');
define('_WEEK', '1 week');
define('_MONTH', '1 maand');

define('_DATESTRING', 'j/n/Y G:i:s');
define('_MEDIUMDATESTRING', 'j/n/Y G:i');
define('_SHORTDATESTRING', 'j/n/Y');
/*
The following characters are recognized in the format string:
a - "am" or "pm"
A - "AM" or "PM"
d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
D - day of the week, textual, 3 letters; i.e. "Fri"
F - month, textual, long; i.e. "January"
h - hour, 12-hour format; i.e. "01" to "12"
H - hour, 24-hour format; i.e. "00" to "23"
g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
i - minutes; i.e. "00" to "59"
j - day of the month without leading zeros; i.e. "1" to "31"
l (lowercase 'L') - day of the week, textual, long; i.e. "Friday"
L - boolean for whether it is a leap year; i.e. "0" or "1"
m - month; i.e. "01" to "12"
n - month without leading zeros; i.e. "1" to "12"
M - month, textual, 3 letters; i.e. "Jan"
s - seconds; i.e. "00" to "59"
S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd"
t - number of days in the given month; i.e. "28" to "31"
T - Timezone setting of this machine; i.e. "MDT"
U - seconds since the epoch
w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
Y - year, 4 digits; i.e. "1999"
y - year, 2 digits; i.e. "99"
z - day of the year; i.e. "0" to "365"
Z - timezone offset in seconds (i.e. "-43200" to "43200")
*/


//%%%%%		LANGUAGE SPECIFIC SETTINGS   %%%%%
define('_CHARSET', 'utf-8');
define('_LANGCODE', 'nl');

// change 0 to 1 if this language is a multi-bytes language
define('XOOPS_USE_MULTIBYTES', '0');
// change 0 to 1 if this language is a RTL (right to left) language
define('_ADM_USE_RTL', '0');

define('_MODULES', 'Modules');
define('_SYSTEM', 'Systeem');
define('_IMPRESSCMS_NEWS', 'Nieuws');
define('_ABOUT', 'Het ImpressCMS project');
define('_IMPRESSCMS_HOME', 'Project pagina');
define('_IMPRESSCMS_COMMUNITY', 'Gemeenschap');
define('_IMPRESSCMS_ADDONS', 'Toevoegingen');
define('_IMPRESSCMS_WIKI', 'Wiki');
define('_IMPRESSCMS_BLOG', 'Blog');
define('_IMPRESSCMS_DONATE', 'Doneren!');
define('_IMPRESSCMS_Support', 'Ondersteun het project !');
define('_IMPRESSCMS_SOURCEFORGE', 'SourceForge Project pagina');
define('_IMPRESSCMS_ADMIN', 'Beheerpagina van');
/** The default separator used in XoopsTree::getNicePathFromId */
define('_BRDCRMB_SEP', '&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV', 'Beginpagina');
define('_CT_RELATEDS', 'Gerelateerde pagina\'s');
//Beveiligingsafbeelding (captcha)
define('_SECURITYIMAGE_GETCODE', 'Vul de beveiligingscode in');
define('_QUERIES', 'Queries');
define('_BLOCKS', 'Blokken');
define('_EXTRA', 'Extra');
define('_TIMERS', 'Timers');
define('_CACHED', 'Cached');
define('_REGENERATES', 'Hergenereren iedere %s seconde');
define('_TOTAL', 'Totaal :');
define('_ERR_NR', 'Fout nummer:');
define('_ERR_MSG', 'Fout bericht:');
define('_NOTICE', 'Opmerking');
define('_WARNING', 'Waarschuwing');
define('_STRICT', 'Strikt');
define('_ERROR', 'Fout');
define('_TOOKXLONG', ' duurde %s seconde om te laden.');
define('_BLOCK', 'Blok(ken)');
define('_WARNINGUPDATESYSTEM', 'Proficiat, u heeft zonet uw website met succes naar de laatste versie van ImpressCMS
geupgrade!<br />Om het upgrade process te voltooien dient u op onderstaande te klikken en de
systeem module te updaten.');

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT', 'http://www.impresscms.be'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE', 'ImpressCMS.be');
define('_ALLEFTCON', 'Geef de tekst in die aan links uitgelijnd moet worden.');
define('_ALCENTERCON', 'Geef de tekst in die gecentreerd moet worden.');
define('_ALRIGHTCON', 'Geef de tekst in die aan rechts uitgelijnd moet worden.');
define('_TRUST_PATH_HELP', "Waarschuwing: Het systeem kon het Trust path niet bereiken.<br />Het trust path is een map waar ImpressCMS en haar modules kwetsbare code en informatie opslaat ter beveiliging.<br />Het is aangeraden dat deze map zich buiten de web root bevind, wat het niet toegankelijk maakt voor enige browser.<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path'>Klik hier om meer te leren over het Trust path en hoe u het kunt aanmaken (Engelstalig).</a>");
define('_PROTECTOR_NOT_FOUND', "Waarschuwing: Het systeem is niet instaat om te bepalen of Protector is geïnstalleerd of actief is op uw website.<br />Wij adviseren u ten zeerste om Protector te installeren of te activeren en zo de beveiliging van uw website te vergroten.<br />Wij moeten GIJOE bedanken voor deze zeer goede module.<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Protector'>Klik hier om meer te leren over Protector.</a><br /><a target='_blank' href='http://xoops.peak.ne.jp/modules/mydownloads/singlefile.php?lid=105&cid=1'>Klik hier om de laatste versie te downloaden van Protector.</a>");

define('_MODABOUT_ABOUT', 'Over');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE', 'gregorian'); // this value is for the local java calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR', 'Kalender');
define('_RETRYPOST', 'Sorry, er heeft zich een time-out voorgedaan. Wilt u nogmaals posten ?'); // autologin hack GIJ

############# toegevoegd sinds 1.2 #############
define('_QSEARCH', 'Snel zoeken');
define('_PREV', 'Vorige');
define('_NEXT', 'Volgende');
define('_LCL_NUM0', '0');
define('_LCL_NUM1', '1');
define('_LCL_NUM2', '2');
define('_LCL_NUM3', '3');
define('_LCL_NUM4', '4');
define('_LCL_NUM5', '5');
define('_LCL_NUM6', '6');
define('_LCL_NUM7', '7');
define('_LCL_NUM8', '8');
define('_LCL_NUM9', '9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define('_USE_LOCAL_NUM', '0');
define('_ICMS_DBUPDATED', 'Database succesvol bijgewerkt !');
define('_MD_AM_DBUPDATED', _ICMS_DBUPDATED);

define('_TOGGLETINY', 'Toggle Editor');
define('_ENTERHTMLCODE', 'Voer de HTML codes in die u wilt toevoegen.');
define('_ENTERPHPCODE', 'Voer de PHP codes in die u wilt toevoegen.');
define('_ENTERCSSCODE', 'Voer de CSS codes in die u wilt toevoegen.');
define('_ENTERJSCODE', 'Voer de JavaScript codes in die u wilt toevoegen.');
define('_ENTERWIKICODE', 'Voer de wiki term in die u wilt toevoegen.');
define('_ENTERLANGCONTENT', 'Voer de text in die u wilt toevoegen in %s.');
define('_LANGNAME', 'Nederlands');
define('_ENTERYOUTUBEURL', 'Voer YouTube url in:');
define('_ENTERHEIGHT', 'Voer frame\'s hoogte in');
define('_ENTERWIDTH', 'Voer frame\'s breedte in');
define('_ENTERMEDIAURL', 'Voer media url in:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"
// insert double '\' before 't', 'r', 'n'
define('_TODAY', "\V\a\\n\d\a\a\g G:i");
define('_YESTERDAY', "\G\i\s\\t\e\\r\e\\n G:i");
define('_MONTHDAY', 'n/j G:i');
define('_YEARMONTHDAY', 'j/n/Y G:i');
define('_ELAPSE', '%s ago');
define('_VISIBLE', 'Zichtbaar');
define('_UP', 'Boven');
define('_DOWN', 'Onders');
define('_CONFIGURE', 'Instellen');

// added in 1.4.0
define('_OUTDATED_PHP', 'Uw huidige PHP versie (%s) wordt niet langer onderhouden. Gelieve up te graden voor veiligheidsredenen!');
