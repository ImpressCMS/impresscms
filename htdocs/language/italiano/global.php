<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// $Id: global.php 1029 2007-09-09 03:49:25Z phppp $
//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','Attendere prego ...');
define('_FETCHING','Caricamento ...');
define('_TAKINGBACK','Stai venendo riportato nel punto in cui eri ...');
define('_LOGOUT','Esci');
define('_SUBJECT','Oggetto');
define('_MESSAGEICON','Icona del messaggio');
define('_COMMENTS','Commenti');
define('_POSTANON','Invia in anonimato');
define('_DISABLESMILEY','Disabilita faccine');
define('_DISABLEHTML','Disabilita tag HTML');
define('_PREVIEW','Anteprima');
define('_GO','Vai!');
define('_NESTED','Annidati');
define('_NOCOMMENTS','Nessun commento');
define('_FLAT','Piatti');
define('_THREADED','Ad albero');
define('_OLDESTFIRST','I pi&ugrave; vecchi prima');
define('_NEWESTFIRST','I pi&ugrave; nuovi prima');
define('_MORE','altro...');
define('_IFNOTRELOAD','Se la pagina non dovesse caricarsi in automatico, clicca <a href="%s">qui</a>');
define('_WARNINSTALL2','ATTENZIONE: La cartella %s &egrave; ancora sul server. <br />Si prega di rimuoverla per motivi di sicurezza.');
define('_WARNINWRITEABLE','ATTENZIONE: Il file %s non &egrave; protetto in scrittura sul server. <br />Si prega di modificare i permessi di questo file, per motivi di sicurezza:<br /> su server Linux/Unix (CHMOD 444), su Windows (sola lettura)');
define('_WARNINNOTWRITEABLE','ATTENZIONE: Il file %s non &egrave; scrivibile dal server. <br />Si prega di cambiare i permessi di questo file per ragioni di funzionalit&agrave;.<br /> in Linux (777), in Win32 (scrivibile)');

// Error messages issued by XoopsObject::cleanVars()
define( "_XOBJ_ERR_REQUIRED", "%s &egrave; richiesta/o" );
define( "_XOBJ_ERR_SHORTERTHAN", "%s deve contenere meno di %d caratteri." );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define("_PROFILE","Profilo");
define("_POSTEDBY","Inviato da ");
define("_VISITWEBSITE","Visita il sito web");
define("_SENDPMTO","Invia un messaggio privato a %s");
define("_SENDEMAILTO","Invia un email a %s");
define("_ADD","Aggiungi");
define("_REPLY","Rispondi");
define("_DATE","Data");   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define("_MAIN","Principale");
define("_MANUAL","Manuale");
define("_INFO","Informazioni");
define("_CPHOME","Pannello di Controllo");
define("_YOURHOME","Pagina Home");

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define("_WHOSONLINE","Utenti online");
define('_GUESTS', 'Ospiti');
define('_MEMBERS', 'Iscritti');
define("_ONLINEPHRASE","<b>%s</b> utente(i) online<br />");
define("_ONLINEPHRASEX","<b>%s</b> utente(i) in <b>%s</b>");
define("_CLOSE","Chiudi");  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define("_QUOTEC","Citazione:");

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Spiacente non hai i permessi per accedere a questa sezione!");

//%%%%%		Common Phrases		%%%%%
define("_NO","No");
define("_YES","S&igrave;");
define("_EDIT","Modifica");
define("_DELETE","Elimina");
define("_SUBMIT","Invia");
define("_MODULENOEXIST","Il modulo scelto non esiste!");
define("_ALIGN","Allinea");
define("_LEFT","Sinistra");
define("_CENTER","Centro");
define("_RIGHT","Destra");
define("_FORM_ENTER", "Prego inserisci %s");
// %s represents file name
define("_MUSTWABLE","Il file %s non deve essere protetto in scrittura sul server!");
// Module info
define('_PREFERENCES', 'Preferenze');
define("_VERSION", "Versione");
define("_DESCRIPTION", "Descrizione");
define("_ERRORS", "Errori");
define("_NONE", "Nessuno");
define('_ON','il');
define('_READS','letture');
define('_SEARCH','Cerca');
define('_ALL', 'Tutto');
define('_TITLE', 'Titolo');
define('_OPTIONS', 'Opzioni');
define('_QUOTE', 'Citazione');
define('_HIDDENC', 'Contenuto nascosto:');
define('_HIDDENTEXT', 'Questo contenuto non &egrave; visibile agli utenti anonimi. Sei pregato di <a href="'.ICMS_URL.'/register.php" title="Registrazione a ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">iscriverti al sito</a> per poterlo vedere.');
define('_LIST', 'Elenca');
define('_LOGIN','Login utente');
define('_USERNAME','Nome utente: ');
define('_PASSWORD','Password: ');
define("_SELECT","Seleziona");
define("_IMAGE","Immagine");
define("_SEND","Invia");
define("_CANCEL","Annulla");
define("_ASCENDING","Ordine crescente");
define("_DESCENDING","Ordine decrescente");
define('_BACK', 'Indietro');
define('_NOTITLE', 'Nessun titolo');

/* Image manager */
define('_IMGMANAGER','Gestore immagini');
define('_NUMIMAGES', '%s immagini');
define('_ADDIMAGE','Aggiungi un\'immagine');
define('_IMAGENAME','Nome:');
define('_IMGMAXSIZE','Dimensione massima (bytes):');
define('_IMGMAXWIDTH','Larghezza massima (pixels):');
define('_IMGMAXHEIGHT','Altezza massima (pixels):');
define('_IMAGECAT','Categoria:');
define('_IMAGEFILE','Immagine:');
define('_IMGWEIGHT','Ordine di visualizzazione delle immagini:');
define('_IMGDISPLAY','Visualizzare l\'immagine?');
define('_IMAGEMIME','MIME (estensione del file):');
define('_FAILFETCHIMG', 'Impossibile eseguire l\'upload del file %s!');
define('_FAILSAVEIMG', 'Caricamento nel database dell\'immagine %s fallito!');
define('_NOCACHE', 'Nessuna cache');
define('_CLONE', 'Clona');
define('_INVISIBLE', 'Invisibile');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Inizia con");
define("_ENDSWITH", "Termina con");
define("_MATCHES", "Coincide con");
define("_CONTAINS", "Contiene");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Iscriviti");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","DIMENSIONE");  // font size
define("_FONT","CARATTERE");  // font family
define("_COLOR","COLORE");  // font color
define("_EXAMPLE","&nbsp;ESEMPIO");
define("_ENTERURL","Indirizzo del sito web che si desidera inserire: ");
define("_ENTERWEBTITLE","Nome/Titolo del sito web: ");
define("_ENTERIMGURL","Indirizzo web dell'immagine che si desidera inserire:");
define("_ENTERIMGPOS","Allineamento dell'immagine.");
define("_IMGPOSRORL","'r' destra, 'l' sinistra, o lasciare vuoto.");
define("_ERRORIMGPOS","ERRORE! Si prega di specificare l'allineamento dell'immagine.");
define("_ENTEREMAIL","Indirizzo email che si desidera inserire:");
define("_ENTERCODE","Codice che si desidera inserire:");
define("_ENTERQUOTE","Citazione che si desidera riportare:");
define("_ENTERHIDDEN","Inserisci il testo che vuoi che sia nascosto agli utenti anonimi.");
define("_ENTERTEXTBOX","Per favore, inserire del testo nella casella.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 secondo');
define('_SECONDS', '%s secondi');
define('_MINUTE', '1 minuto');
define('_MINUTES', '%s minuti');
define('_HOUR', '1 ora');
define('_HOURS', '%s ore');
define('_DAY', '1 giorno');
define('_DAYS', '%s giorni');
define('_WEEK', '1 settimana');
define('_MONTH', '1 mese');

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
define('_LANGCODE', 'it');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define("_MODULES","Moduli");
define("_SYSTEM","Sistema");
define("_IMPRESSCMS_NEWS","Notizie");
define("_ABOUT","Il progetto ImpressCMS");
define("_IMPRESSCMS_HOME","Home del progetto");
define("_IMPRESSCMS_COMMUNITY","Community");
define("_IMPRESSCMS_ADDONS","Addons");
define("_IMPRESSCMS_WIKI","Wiki");
define("_IMPRESSCMS_BLOG","Blog");
define("_IMPRESSCMS_DONATE","Dona!");
define("_IMPRESSCMS_Support","Sostieni il progetto!");
define("_IMPRESSCMS_SOURCEFORGE","SourceForge Project");
define('_IMPRESSCMS_ADMIN','Amministrazione di');
/** The default separator used in XoopsTree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','Home');
define('_CT_RELATEDS','Pagine correlate');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","Inserisci il codice di sicurezza");
define("_QUERIES", "Queries");
define("_BLOCKS", "Blocchi");
define("_EXTRA", "Extra");
define("_TIMERS", "Timers");
define("_CACHED", "Cached");
define("_REGENERATES", "Rigenera ogni %s secondi");
define("_TOTAL", "Totale:");
define("_ERR_NR", "Errore numero:");
define("_ERR_MSG", "Messaggio di errore:");
define("_NOTICE", "Avviso");
define("_WARNING", "Attenzione");
define("_STRICT", "Stretto");
define("_ERROR", "Errore");
define("_TOOKXLONG", " prende %s secondi per caricare.");
define("_BLOCK", "Blocco/blocchi)");
define("_WARNINGUPDATESYSTEM","Congratulazioni, hai appena aggiornato con successo il tuo sito all'ultima versione di ImpressCMS!<br />Per terminare la procedura di aggiornamento dovrai cliccare qui e aggiornare il tuo modulo di sistema.<br />Clicca qui per procedere all'aggiornamento.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT','http://www.impresscms.it'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','Sito di supporto italiano');
define("_ALLEFTCON","Inserisci il testo da allineare al lato sinistro.");
define("_ALCENTERCON","Inserisci il testo da allineare al centro.");
define("_ALRIGHTCON","Inserisci il testo da allineare al lato destro.");
define( "_TRUST_PATH_HELP", "Attenzione: il sistema non riesce a raggiungere la Trust Path.<br />La Trust Path &egrave; una cartella sicura dove ImpressCMS e i suoi moduli inseriscono alcuni dati e codici sensibili per mantenere un alto standard di sicurezza.<br />&Egrave; raccomandato che questa cartella sia fuori dalla cartella radice del sito, rendendola cos&igrave; inaccessibile da un browser. <br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path'>Clicca qui per leggere una guida (in inglese) che spiega di pi&ugrave; sulla Trust Path e come crearla.</a>" );
define( "_PROTECTOR_NOT_FOUND", "Attenzione: il sistema non &egrave; in grado di trovare se Protector &egrave; installato o attivo sul tuo sito.<br />Raccomandiamo caldamente che tu installi o attivi il modulo Protector per migliorare le condizioni di sicurezza del tuo sito.<br />Un ringraziamento a GIJOE per questo utilissimo strumento.<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Protector'>Clicca qui per avere maggiori informazioni su protector. (in inglese)</a><br /><a target='_blank' href='http://xoops.peak.ne.jp/modules/mydownloads/singlefile.php?lid=105&cid=1'>Clicca qui per scaricare l'ultima versione di Protector.</a>" );

define('_MODABOUT_ABOUT', 'Info');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local java calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','Calendario');
define('_RETRYPOST','Spiacente, il tempo a disposizione &egrave; terminato. Vorresti inviare nuovamente i dati?'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','Ricerca veloce');
define('_PREV','Prec');
define('_NEXT','Pros');
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
define("_ICMS_DBUPDATED","Database aggiornato con successo!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','Toggle Editor');
define("_ENTERHTMLCODE","Inserisci il codice HTML che vuoi aggiungere.");
define("_ENTERPHPCODE","Inserisci il codice PHP che vuoi aggiungere.");
define("_ENTERCSSCODE","Inserisci il codice CSS che vuoi aggiungere.");
define("_ENTERJSCODE","Inserisci il codice JavaScript che vuoi aggiungere.");
define("_ENTERWIKICODE","Inserisci il termine wiki che vuoi aggiungere.");
define("_ENTERLANGCONTENT","Inserisci il testo che vuoi aggiungere in %s.");
define('_LANGNAME', 'English');
define('_ENTERYOUTUBEURL', 'Inserisci URL YouTube:');
define('_ENTERHEIGHT', 'Inserisci altezza frame');
define('_ENTERWIDTH', 'Inserisci larghezza frame');
define('_ENTERMEDIAURL', 'Inserisci URL media:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"	
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "\O\g\g\i G:i");
define("_YESTERDAY", "\I\e\r\i G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "Y/n/j G:i");
define("_ELAPSE", "%s fa");
define('_VISIBLE', 'Visibile');
define('_UP', 'Su');
define('_DOWN', 'Giù');
define('_CONFIGURE', 'Configura');

// Added in 1.2.2
define('_CSSTIDY_VULN', 'WARNING: File %s già esistente sul tuo server. <br />Sei pregato di rimuovere questo file manualmente.');
define('_FILE_DELETED', 'File %s cancellato con successo');

// added in 1.3
define('_CHECKALL', 'Seleziona tutto');
define('_COPYRIGHT', 'Copyright');
define("_LONGDATESTRING", "F jS Y, h:iA");
define('_AUTHOR', 'Autore');
define("_CREDITS", "Crediti");
define("_LICENSE", "Licenza");
define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>');
define("_BLOCK_ID", "Block ID");
define('_IMPRESSCMS_PROJECT','Project Development');

// added in 1.3.5
define("_FILTERS","Filtri");
define("_FILTER","Filtro");
define("_FILTERS_MSG1","Input Filter: ");
define("_FILTERS_MSG2","Input Filter (HTMLPurifier): ");
define("_FILTERS_MSG3","Output Filter: ");
define("_FILTERS_MSG4","Output Filter (HTMLPurifier): ");

// added in 1.4.0
define("_OUTDATED_PHP", "La vostra versione di PHP (%s) non è piu mantenuto. Si prega di fare l'update per ragioni di securità!");