<?php

//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','Bitte warten');
define('_FETCHING','Lädt...');
define('_TAKINGBACK','Bring dich dorthin zurück, wo du warst');
define('_LOGOUT','Abmelden');
define('_SUBJECT','Betreff');
define('_MESSAGEICON','Nachrichten Symbol');
define('_COMMENTS','Kommentare');
define('_POSTANON','Anonym schreiben');
define('_DISABLESMILEY','Smiley deaktivieren');
define('_DISABLEHTML','HTML deaktivieren');
define('_PREVIEW','Vorschau');

define('_GO','Go!');
define('_NESTED','Verschachtelt');
define('_NOCOMMENTS','Keine Kommentare');
define('_FLAT','Flat');
define('_THREADED','Thread');
define('_OLDESTFIRST','Älteste zuerst');
define('_NEWESTFIRST','Neueste zuerst');
define('_MORE','mehr...');
define('_IFNOTRELOAD','Wenn die Seite nicht automatisch neu geladen wird, klicken Sie bitte <a href="%s">hier</a>');
define('_WARNINSTALL2','WARNUNG: Verzeichnis %s existiert auf Ihrem Server. <br />Bitte entfernen Sie dieses Verzeichnis aus Sicherheitsgründen.');
define('_WARNINWRITEABLE','WARNUNG: Datei %s ist beschreibbar durch den Server. <br />Bitte ändern Sie die Berechtigung dieser Datei aus Sicherheitsgründen.<br /> in Unix (444), in Win32 (schreibgeschützt)');
define('_WARNINNOTWRITEABLE','WARNUNG: Datei %s ist beschreibbar durch den Server. <br />Bitte ändern Sie die Berechtigung dieser Datei aus Sicherheitsgründen.<br /> in Unix (777), in Win32 (schreibgeschützt)');

// Error messages issued by icms_core_Object::cleanVars()
define( '_XOBJ_ERR_REQUIRED', '%s ist erforderlich' );
define( '_XOBJ_ERR_SHORTERTHAN', '%s muss kürzer als %d Zeichen sein.' );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE','Profil');
define('_POSTEDBY','Gepostet von');
define('_VISITWEBSITE','Webseite besuchen');
define('_SENDPMTO','Private Nachricht an %s senden');
define('_SENDEMAILTO','E-Mail an %s senden');
define('_ADD','Hinzufügen');
define('_REPLY','Antworten');
define('_DATE','Datum');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN','Hauptseite');
define('_MANUAL','Handbuch');
define('_INFO','Informationen');
define('_CPHOME','Administrator Systemsteuerung');
define('_YOURHOME','Startseite');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE','Wer ist online');
define('_GUESTS', 'Gäste');
define('_MEMBERS', 'Mitglieder');
define('_ONLINEPHRASE','<b>%s</b> Benutzer sind online');
define('_ONLINEPHRASEX','<b>%s</b> Benutzer durchsuchen <b>%s</b>');
define('_CLOSE','Schließen');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC','Zitat:');

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Sie haben keine Berechtigung auf diese Seite zuzugreifen.");

//%%%%%		Common Phrases		%%%%%
define("_NO","Nein");
define("_YES","Ja");
define("_EDIT","Bearbeiten");
define("_DELETE","Löschen");
define("_SUBMIT","Absenden");
define("_MODULENOEXIST","Ausgewähltes Modul existiert nicht!");
define("_ALIGN","Ausrichten");
define("_LEFT","Links");
define("_CENTER","Zentriert");
define("_RIGHT","Rechts");
define("_FORM_ENTER", "Bitte geben Sie %s ein");
// %s represents file name
define("_MUSTWABLE","Datei %s muss vom Server beschreibbar sein!");
// Module info
define('_PREFERENCES', 'Einstellungen');
define("_VERSION", "Version");
define("_DESCRIPTION", "Beschreibung");
define("_ERRORS", "Fehler");
define("_NONE", "Nichts");
define('_ON','an');
define('_READS','lesen');
define('_SEARCH','Suche');
define('_ALL', 'Alle');
define('_TITLE', 'Titel');
define('_OPTIONS', 'Optionen');
define('_QUOTE', 'Zitat');
define('_HIDDENC', 'Versteckter Inhalt:');
define('_HIDDENTEXT', 'This content is hidden for anonymous users, please <a href="'.ICMS_URL.'/register.php" title="Registration at ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">register</a> to be able to see it.');
define('_LIST', 'Liste');
define('_LOGIN','Benutzer Login');
define('_USERNAME','Benutzername: ');
define('_PASSWORD','Passwort: ');
define("_SELECT","Auswählen");
define("_IMAGE","Bild");
define("_SEND","Senden");
define("_CANCEL","Abbruch");
define("_ASCENDING","Aufsteigende Reihenfolge");
define("_DESCENDING","Absteigende Reihenfolge");
define('_BACK', 'Zurück');
define('_NOTITLE', 'Kein Titel');

/* Image manager */
define('_IMGMANAGER','Bildmanager');
define('_NUMIMAGES', '%s Bilder');
define('_ADDIMAGE','Bilddatei hinzufügen');
define('_IMAGENAME','Name:');
define('_IMGMAXSIZE','Maximale erlaubte Größe (Bytes):');
define('_IMGMAXWIDTH','Maximale erlaubte Größe (Bytes):');
define('_IMGMAXHEIGHT','Maximale erlaubte Größe (Bytes):');
define('_IMAGECAT','Kategorie:');
define('_IMAGEFILE','Bilddatei:');
define('_IMGWEIGHT','Reihenfolge:');
define('_IMGDISPLAY','Dieses Bild anzeigen?');
define('_IMAGEMIME','MIME-Typ:');
define('_FAILFETCHIMG', 'Datei %s konnte nicht hochgeladen werden');
define('_FAILSAVEIMG', 'Fehler beim Speichern des Bildes %s in der Datenbank');
define('_NOCACHE', 'Kein Cache');
define('_CLONE', 'Kopieren');
define('_INVISIBLE', 'Unsichtbar');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Beginnt mit");
define("_ENDSWITH", "Endet mit");
define("_MATCHES", "Treffer");
define("_CONTAINS", "Beinhaltet");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Registrieren");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","SIZE");  // font size
define("_FONT","Schrift");  // font family
define("_COLOR","FARBE");  // font color
define("_EXAMPLE","Auswahl");
define("_ENTERURL","Geben Sie die URL des Links ein, den Sie hinzufügen möchten:");
define("_ENTERWEBTITLE","Geben Sie den Titel der Webseite ein:");
define("_ENTERIMGURL","Geben Sie die URL des Links ein, den Sie hinzufügen möchten.");
define("_ENTERIMGPOS","Geben Sie nun die Position des Bildes ein.");
define("_IMGPOSRORL","'R' oder 'r' für rechts, 'L' oder 'l' für links, 'C' oder 'c' für die Mitte oder lassen Sie es leer.");
define("_ERRORIMGPOS","Geben Sie nun die Position des Bildes ein.");
define("_ENTEREMAIL","Geben Sie die E-Mail-Adresse ein, die Sie hinzufügen möchten.");
define("_ENTERCODE","Geben Sie die URL des Links ein, den Sie hinzufügen möchten.");
define("_ENTERQUOTE","Geben Sie den Text ein, den Sie zitieren möchten.");
define("_ENTERHIDDEN","Geben Sie den Text ein, den Sie für anonyme Benutzer verstecken möchten.");
define("_ENTERTEXTBOX","Bitte geben Sie Text in das Textfeld ein.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 Sekunde');
define('_SECONDS', '%s Sekunden');
define('_MINUTE', '1Minute');
define('_MINUTES', '%s Minuten');
define('_HOUR', '1 Stunde');
define('_HOURS', '%s Stunden');
define('_DAY', '1 Tag');
define('_DAYS', '%s Tage');
define('_WEEK', '1 week');
define('_MONTH', '1 month');

define("_DATESTRING","Y/n/j G:i:s");
define("_MEDIUMDATESTRING","Y/n/j G:i");
define("_SHORTDATESTRING","Y/n/j");
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
define('_LANGCODE', 'en');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define('_MODULES','Modules');
define('_SYSTEM','System');
define('_IMPRESSCMS_NEWS','News');
define('_ABOUT','ImpressCMS Project');
define('_IMPRESSCMS_HOME','Project Home');
define('_IMPRESSCMS_COMMUNITY','Community');
define('_IMPRESSCMS_ADDONS','Addons');
define('_IMPRESSCMS_WIKI','Wiki');
define('_IMPRESSCMS_BLOG','Blog');
define('_IMPRESSCMS_DONATE','Donate!');
define("_IMPRESSCMS_Support","Support the project !");
define('_IMPRESSCMS_SOURCEFORGE','SourceForge Project');
define('_IMPRESSCMS_ADMIN','Administration of');
/** The default separator used in icms_view_Tree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','Home');
define('_CT_RELATEDS','Related pages');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","Enter the security code");
define("_WARNINGUPDATESYSTEM","Congratulations, you have just successfully upgraded your site to the latest version of ImpressCMS!<br />Therefor to finish the upgrade process you'll need to click here and update your system module.<br />Click here to process the upgrade.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT', 'https://www.impresscms.org'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','Local support site');
define("_ALLEFTCON","Enter the text to be aligned on the Left side.");
define("_ALCENTERCON","Enter the text to be aligned on the Center side.");
define("_ALRIGHTCON","Enter the text to be aligned on the Right side.");

define('_MODABOUT_ABOUT', 'About');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','Calendar');
define('_RETRYPOST','Sorry, a time-out occured. Would you like to post again ?'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','Quick Search');
define('_PREV','Prev');
define('_NEXT','Next');
define('_LCL_NUM0','0');
define('_LCL_NUM1','1');
define('_LCL_NUM2','2');
define('_LCL_NUM3','3');
define('_LCL_NUM4','4');
define('_LCL_NUM5','5');
define('_LCL_NUM6','6');
define('_LCL_NUM7','7');
define('_LCL_NUM8','8');
define('_LCL_NUM9','9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define("_USE_LOCAL_NUM","0");
define("_ICMS_DBUPDATED","Database Updated Successfully!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','Toggle Editor');
define("_ENTERHTMLCODE","Enter the HTML codes that you want to add.");
define("_ENTERPHPCODE","Enter the PHP codes that you want to add.");
define("_ENTERCSSCODE","Enter the CSS codes that you want to add.");
define("_ENTERJSCODE","Enter the JavaScript codes that you want to add.");
define("_ENTERWIKICODE","Enter the wiki term that you want to add.");
define("_ENTERLANGCONTENT","Enter the text that you want to be in %s.");
define('_LANGNAME', 'English');
define('_ENTERYOUTUBEURL', 'Enter YouTube url:');
define('_ENTERHEIGHT', 'Enter frame\'s height');
define('_ENTERWIDTH', 'Enter frame\'s width');
define('_ENTERMEDIAURL', 'Enter media url:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "	\\o\\d\\a\\y G:i");
define("_YESTERDAY", "\\Y\e\\s\\t\e\\r\\d\\a\\y G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "Y/n/j G:i");
define("_ELAPSE", "%s ago");
define('_VISIBLE', 'Visible');
define('_UP', 'Up');
define('_DOWN', 'Down');
define('_CONFIGURE', 'Configure');

// Added in 1.2.2
define('_FILE_DELETED', 'File %s was deleted successfully');

// added in 1.3
define('_CHECKALL', 'Check all');
define('_COPYRIGHT', 'Copyright');
define("_LONGDATESTRING", "F jS Y, h:iA");
define('_AUTHOR', 'Author');
define("_CREDITS", "Credits");
define("_LICENSE", "License");
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_BLOCK_ID", "Block ID");
define('_IMPRESSCMS_PROJECT','Project Development');

// added in 1.3.5
define("_FILTERS","Filters");
define("_FILTER","Filter");
define("_FILTERS_MSG1","Input Filter: ");
define("_FILTERS_MSG2","Input Filter (HTMLPurifier): ");
define("_FILTERS_MSG3","Output Filter: ");
define("_FILTERS_MSG4","Output Filter (HTMLPurifier): ");


// added in 2.0
define('_ENTER_MENTION', 'Enter the user name to mention:');
define( '_ENTER_HASHTAG', 'Enter the term(s) to tag:');
define('_NAME', 'Name');

define('_OR', 'or');
