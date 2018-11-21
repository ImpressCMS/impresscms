<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by evoc - webmaster of ImpressCMS
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// $Id: preferences.php 1194 2007-12-19 10:01:40Z phppp $
//%%%%%%	Admin Module Name  AdminGroup 	%%%%%
// dont change
define('_AM_DBUPDATED',"Database aggiornato con successo!");

define('_MD_AM_SITEPREF','Preferenze');
define('_MD_AM_SITENAME','Nome del sito');
define('_MD_AM_SLOGAN','Slogan del sito');
define('_MD_AM_ADMINML','Indirizzo email dell\'amministratore');
define('_MD_AM_LANGUAGE','Lingua di base');
define('_MD_AM_STARTPAGE','Modulo o pagina per la tua pagina iniziale ');
define('_MD_AM_NONE','Nessuno');
define("_MD_CONTENTMAN","Manager pagine statiche");
define('_MD_AM_SERVERTZ','Fuso orario del server');
define('_MD_AM_DEFAULTTZ','Fuso orario per il sito');
define('_MD_AM_DTHEME','Tema di default');
define('_MD_AM_THEMESET','Set di template');
define('_MD_AM_ANONNAME','Nick per gli utenti anonimi');
define('_MD_AM_MINPASS','Lunghezza minima della password');
define('_MD_AM_NEWUNOTIFY','Desideri ricevere una messaggio di notifica via email quando un nuovo utente si registra?');
define('_MD_AM_SELFDELETE','Consenti agli utenti di eliminare il proprio account?');
define('_MD_AM_LOADINGIMG','Mostra l\'immagine di caricamento ...?');
define('_MD_AM_USEGZIP','Utilizzare la compressione gzip?');
define('_MD_AM_UNAMELVL','Seleziona il livello di restrizione per i nomi scelti dagli utenti');
define('_MD_AM_STRICT','Stretto (solo lettere e numeri)');
define('_MD_AM_MEDIUM','Medio');
define('_MD_AM_LIGHT','Leggero (raccomandato per lingue multi-byte)');
define('_MD_AM_USERCOOKIE','Valore del cookie degli utenti');
define('_MD_AM_USERCOOKIEDSC','Questo cookie contiene solo il nick dell\'utente ed &egrave; salvato nel pc del visitatore per un anno (se questi lo desidera). Se un utente ha questo cookie sul suo pc, il nome utente verr&agrave; automaticamente inserito nel blocco di login ad ogni suo accesso.');
define('_MD_AM_USEMYSESS','Utilizza sessioni personalizzate');
define('_MD_AM_USEMYSESSDSC','Seleziona \'S&igrave;\' per personalizzare i valori della sessione.');
define('_MD_AM_SESSNAME','Nome sessione');
define('_MD_AM_SESSNAMEDSC','Il nome personalizzato della sessione. <br />(Valido solo se \'Utilizza sessioni personalizzate\' &egrave; attivo)');
define('_MD_AM_SESSEXPIRE','Durata della sessione');
define('_MD_AM_SESSEXPIREDSC','Durata massima della sessione in minuti.<br />(Valido solo se \'Utilizza sessioni personalizzate\' &egrave; attivo). Funziona solo se si utilizza PHP4.2.0 o successive.)');
define('_MD_AM_BANNERS','Attiva visualizzazione dei banners?');
define('_MD_AM_MYIP','Il tuo indirizzo IP fisso (se ne hai uno)');
define('_MD_AM_MYIPDSC','Questo IP non verr&agrave; conteggiato per i clicks dei banners');
define('_MD_AM_ALWDHTML','I tag HTML sono consentiti in tutti i messaggi.');
define('_MD_AM_INVLDMINPASS','Valore per la lunghezza minima della password non valido.');
define('_MD_AM_INVLDUCOOK','Valore per il cookie utente non valido.');
define('_MD_AM_INVLDSCOOK','Valore per il cookie di sessione non valido.');
define('_MD_AM_INVLDSEXP','Valore di durata della sessioni non valido.');
define('_MD_AM_ADMNOTSET','L\'email dell\'amministratore non &egrave; stata impostata.');
define('_MD_AM_YES','S&igrave;');
define('_MD_AM_NO','No');
define('_MD_AM_DONTCHNG','Non modificare!');
define('_MD_AM_REMEMBER','Ricorda di impostare a CHMOD 666 (scrittura/lettura) questo file per consentire al sistema di scriverlo.');
define('_MD_AM_IFUCANT','Se non puoi modificare i permessi del file modifica il file a mano e poi ricaricalo sul server.');


define('_MD_AM_COMMODE','Modalit&agrave; di visualizzazione dei commenti');
define('_MD_AM_COMORDER','Ordine di visualizzazione dei commenti');
define('_MD_AM_ALLOWHTML','Consenti i tag HTML nei commenti degli utenti?');
define('_MD_AM_DEBUGMODE','Modalit&agrave; di debug');
define('_MD_AM_DEBUGMODEDSC','Ci sono varie possibilit&agrave; di visualizzare il debug, ma un sito online non dovrebbe avere nessuna di queste opzioni abilitate.');
define('_MD_AM_AVATARALLOW','Consenti il caricamento sul server di avatar personali?');
define('_MD_AM_AVATARMP','Numero minimo di messaggi necessari');
define('_MD_AM_AVATARMPDSC','Inserisci il numero minimo di messaggi necessari per consentire a un utente di caricare un avatar personalizzato sul server.');
define('_MD_AM_AVATARW','Larghezza massima dell\'avatar (pixel)');
define('_MD_AM_AVATARH','Altezza massima dell\'avatar (pixel)');
define('_MD_AM_AVATARMAX','Dimensione massima del file di avatar (byte)');
define('_MD_AM_AVATARCONF','Impostazioni personalizzate dell\'avatar');
define('_MD_AM_CHNGUTHEME','Cambia tutti i temi impostati dagli utenti');
define('_MD_AM_NOTIFYTO','Seleziona il gruppo al quale inviare un messaggio di notifica quando si registra un nuovo utente');
define('_MD_AM_ALLOWTHEME','Consenti agli utenti di selezionare il tema?');
define('_MD_AM_ALLOWIMAGE','Consenti agli utenti di mostrare immagini nei messaggi?');

define('_MD_AM_USERACTV','Richiedi l\'attivazione da parte dell\'utente (raccomandato)');
define('_MD_AM_AUTOACTV','Attiva automaticamente');
define('_MD_AM_ADMINACTV','Attivazione fornita dagli amministratori');
define("_MD_AM_REGINVITE","Registrazione su invito");
define('_MD_AM_ACTVTYPE','Seleziona la modalit&agrave; di attivazione per i nuovi utenti registrati');
define('_MD_AM_ACTVGROUP','Seleziona il gruppo al quale inviare un email di di attivazione');
define('_MD_AM_ACTVGROUPDSC','Valido solo quando si seleziona \'Attivazione fornita dagli amministratori\'.');
define('_MD_AM_USESSL', 'Utilizza il metodo SSL per il login?');
define('_MD_AM_SSLPOST', 'Nome variabile SSL');
define('_MD_AM_SSLPOSTDSC', 'Il nome della variabile usata per trasferire valori di sessione con il metodo POST. Se non sei sicuro, scegli un valore che sia difficile da indovinare.');
define('_MD_AM_DEBUGMODE0','Nessun debug');
define('_MD_AM_DEBUGMODE1','Debug (fondo pagina)');
define('_MD_AM_DEBUGMODE2','Debug (finestra popup)');
define('_MD_AM_DEBUGMODE3','Smarty Templates Debug');
define('_MD_AM_MINUNAME', 'Lunghezza minima del nick');
define('_MD_AM_MAXUNAME', 'Lunghezza massima del nick');
define('_MD_AM_GENERAL', 'Impostazioni Generali');
define('_MD_AM_USERSETTINGS', 'Impostazioni Utenti');
define('_MD_AM_ALLWCHGMAIL', 'Consenti agli utenti di modificare il loro indirizzo email?');
define('_MD_AM_ALLWCHGMAILDSC', '');
define('_MD_AM_IPBAN', 'IP Bloccati');
define('_MD_AM_BADEMAILS', 'Elenca gli indirizzi email che non vuoi vengano usati in un profilo utente.');
define('_MD_AM_BADEMAILSDSC', 'Separa ciascun indirizzo con <strong>|</strong>, case insensitive, regex abilitata.');
define('_MD_AM_BADUNAMES', 'Elenca i nomi che non vuoi vengano usati come nick');
define('_MD_AM_BADUNAMESDSC', 'Separa ciascun nome con <b>|</b>, case insensitive, regex abilitata.');
define('_MD_AM_DOBADIPS', 'Attiva il blocco (ban) di indirizzi IP?');
define('_MD_AM_DOBADIPSDSC', 'Gli utenti con l\'indirizzo IP specificato non riusciranno ad acccedere al sito.');
define('_MD_AM_BADIPS', 'Inserisci gli indirizzi IP da bloccare.<br />Separa ciascun valore con <b>|</b>, case insensitive, regex abilitata.');
define('_MD_AM_BADIPSDSC', '^aaa.bbb.ccc non consentir&agrave; l\'accesso a visitatori con indirizzo IP che cominci con aaa.bbb.ccc<br />aaa.bbb.ccc$ non consentir&agrave; l\'accesso a visitatori con indirizzo IP che finisca con aaa.bbb.ccc<br />aaa.bbb.ccc non consentir&agrave; l\'accesso a visitatori con indirizzo IP che contenga aaa.bbb.ccc');
define('_MD_AM_PREFMAIN', 'Preferenze');
define('_MD_AM_METAKEY', 'Parole chiave (metatag)');
define('_MD_AM_METAKEYDSC', 'Le parole chiave sono una serie di termini che sintetizzano il contenuto offerto dal tuo sito. Inserisci ogni parola separandola con una virgola o uno spazio. (Es. ImpressCMS, PHP, mySQL, portale italiano).');
define('_MD_AM_METARATING', 'Meta Rating');
define('_MD_AM_METARATINGDSC', 'Il metatag \'rating\' definisce il target d\'et&agrave; del tuo sito e il livello dei suoi contenuti.');
define('_MD_AM_METAOGEN', 'Generale');
define('_MD_AM_METAO14YRS', 'Fino a 14 anni');
define('_MD_AM_METAOREST', 'Soggetto a restrizioni');
define('_MD_AM_METAOMAT', 'Adulti');
define('_MD_AM_METAROBOTS', 'Meta Robots');
define('_MD_AM_METAROBOTSDSC', 'Il metatag \'robots\' dichiara ai motori di ricerca il modo in cui far indicizzare i contenuti allo \'spider\'.');
define('_MD_AM_INDEXFOLLOW', 'Indice, Seguire');
define('_MD_AM_NOINDEXFOLLOW', 'Nessun indice, Seguire');
define('_MD_AM_INDEXNOFOLLOW', 'Indice, Non seguire');
define('_MD_AM_NOINDEXNOFOLLOW', 'Nessun indice, Non seguire');
define('_MD_AM_METAAUTHOR', 'Meta Author');
define('_MD_AM_METAAUTHORDSC', 'Il metatag \'author\' definisce l\'autore del documento che si sta leggendo. Il valore pu&ograve; essere: il nome, l\'indirizzo email del webmaster, il nome dell\'azienda o l\'URL.');
define('_MD_AM_METACOPYR', 'Meta Copyright');
define('_MD_AM_METACOPYRDSC', 'Il metatag \'copyright\' definisce qualsiasi dichiarazione si voglia divulgare circa i diritti di propriet&ograve; del materiale pubblicato nel sito.');
define('_MD_AM_METADESC', 'Meta Description');
define('_MD_AM_METADESCDSC', 'Il metatag \'description\' &egrave; una descrizione generale del contenuto e degli argomenti del sito');
define('_MD_AM_METAFOOTER', 'Metatag e pi&egrave; di pagina');
define('_MD_AM_FOOTER', 'Pi&egrave; di pagina');
define('_MD_AM_FOOTERDSC', 'Assicurati di inserire il link completo iniziando da http://, altrimenti il link non funzioner&agrave; correttamente nella pagine dei moduli.');
define('_MD_AM_CENSOR', 'Impostazioni di censura delle parole');
define('_MD_AM_DOCENSOR', 'Attiva la censura di parole indesiderate?');
define('_MD_AM_DOCENSORDSC', 'Se questa opzione &egrave; abilitata i termini impostati di seguito saranno censurati. Questa opzione pu&ograve; essere disabilitata per aumentare la velocit&agrave; del sito.');
define('_MD_AM_CENSORWRD', 'Parole da censurare');
define('_MD_AM_CENSORWRDDSC', 'Inserisci le parole che dovrebbero essere censurate nei messaggi degli utenti.<br />Separa ogni termine con una pipe <strong>|</strong>, case insensitive.');
define('_MD_AM_CENSORRPLC', 'Le parole censurate saranno sostituite con');
define('_MD_AM_CENSORRPLCDSC', 'Le parole censurate saranno sostituite con i caratteri contenuti in questa casella');

define('_MD_AM_SEARCH', 'Impostazioni di Ricerca');
define('_MD_AM_DOSEARCH', 'Attiva la ricerca globale?');
define('_MD_AM_DOSEARCHDSC', 'Consenti di ricercare messaggi/elementi all\'interno del sito.');
define('_MD_AM_MINSEARCH', 'Lunghezza minima delle parole chiave');
define('_MD_AM_MINSEARCHDSC', 'Inserisci il numero minimo di caratteri che l\'utente deve inserire per effettuare una ricerca.');
define('_MD_AM_MODCONFIG', 'Impostazioni della configurazione dei moduli');
define('_MD_AM_DSPDSCLMR', 'Mostra le condizioni d\'uso?');
define('_MD_AM_DSPDSCLMRDSC', 'Seleziona \'S&igrave;\' per mostrare le condizioni d\'uso nella pagina di registrazione degli utenti');
define('_MD_AM_REGDSCLMR', 'Condizioni d\'uso per la registrazione');
define('_MD_AM_REGDSCLMRDSC', 'Inserisci un testo che descriva i termini e le condizioni d\'uso durante la registrazione');
define('_MD_AM_ALLOWREG', 'Consenti la registrazione di nuovi utenti?');
define('_MD_AM_ALLOWREGDSC', 'Seleziona \'S&igrave;\' per consentire la registrazione di nuovi utenti');
define('_MD_AM_THEMEFILE', 'Permettere le modifiche a templates/tema?');
define('_MD_AM_THEMEFILEDSC', 'Se questa opzione &egrave; abilitata il tema e/o i templates modificati saranno ricompilati in automatico ad ogni reload della pagina visualizzando eventuali modifiche.<br />Sconsigliato su siti in produzione.');
define('_MD_AM_CLOSESITE', 'Chiudere il sito?');
define('_MD_AM_CLOSESITEDSC', 'Seleziona \'S&igrave;\' per chiudere il sito e far in modo che solo gli utenti nei gruppi selezionati abbiano accesso al sito');
define('_MD_AM_CLOSESITEOK', 'Seleziona i gruppi a cui &egrave; consentito l\'accesso quando il sito &egrave; chiuso');
define('_MD_AM_CLOSESITEOKDSC', 'Gli utenti del gruppo Webmaster hanno sempre garantito l\'accesso al sito');
define('_MD_AM_CLOSESITETXT', 'Ragioni della chiusura del sito');
define('_MD_AM_CLOSESITETXTDSC', 'Il testo da visualizzare quando il sito &egrave; chiuso. <br />&Egrave; possibile utilizzare codice html.');
define('_MD_AM_SITECACHE', 'Tempo di cache del sito');
define('_MD_AM_SITECACHEDSC', 'Replica nella cache tutti i contenuti del sito per il tempo specificato per aumentare le prestazioni. Il tempo di cache del sito ha precedenza sui moduli, blocchi e oggetti che prevedono una cache.');
define('_MD_AM_MODCACHE', 'Tempo di cache dei moduli');
define('_MD_AM_MODCACHEDSC', 'Replica nella cache tutti i contenuti del modulo per il tempo specificato per aumentare le prestazioni. Il tempo di cache dei moduli ha precedenza sui moduli che prevedono una cache.');
define('_MD_AM_NOMODULE', 'Non esistono moduli che possano usufruire della cache.');
define('_MD_AM_DTPLSET', 'Template set di default');
define('_MD_AM_SSLLINK', 'Indirizzo dove si trova la pagina di login SSL');

// added for mailer
define('_MD_AM_MAILER','Impostazioni Mail');
define('_MD_AM_MAILER_MAIL','');
define('_MD_AM_MAILER_SENDMAIL','');
define('_MD_AM_MAILER_','');
define('_MD_AM_MAILFROM','Dall\'indirizzo');
define('_MD_AM_MAILFROMDESC','');
define('_MD_AM_MAILFROMNAME','Da nome');
define('_MD_AM_MAILFROMNAMEDESC','');
// RMV-NOTIFY
define('_MD_AM_MAILFROMUID','Da utente');
define('_MD_AM_MAILFROMUIDDESC','Quando il sistema invia un messaggio privato, quale utente deve risultare esserne il mittente?');
define('_MD_AM_MAILERMETHOD','Metodo di spedizione delle mail');
define('_MD_AM_MAILERMETHODDESC','Metodo utilizzato per spedire le mail. L\'impostazione di default &egrave; \'mail\', utilizzane altre solo se questa da problemi');
define('_MD_AM_SMTPHOST','Host SMTP');
define('_MD_AM_SMTPHOSTDESC','Elenco del server SMTP cui tentare di connettersi.');
define('_MD_AM_SMTPUSER','Nome utente SMTPAuth');
define('_MD_AM_SMTPUSERDESC','Nome utente per la connessione a un host SMTP con SMTPAuth.');
define('_MD_AM_SMTPPASS','Password SMTPAuth');
define('_MD_AM_SMTPPASSDESC','Password per la connessione a un host SMTP con SMTPAuth.');
define('_MD_AM_SENDMAILPATH','Percorso di sendmail');
define('_MD_AM_SENDMAILPATHDESC','Percorso del programma sendmail (o sostituto) sul server web.');
define('_MD_AM_THEMEOK','Temi selezionabili');
define('_MD_AM_THEMEOKDSC','I temi che gli utenti possono scegliere se viene visualizzato il blocco temi.');


// ImpressCMS Authentication constants
define('_MD_AM_AUTH_CONFOPTION_XOOPS', 'Database ImpressCMS');
define('_MD_AM_AUTH_CONFOPTION_LDAP', 'Standard Directory LDAP');
define('_MD_AM_AUTH_CONFOPTION_AD', 'Microsoft Active Directory &copy');
define('_MD_AM_AUTHENTICATION', 'Opzioni di autenticazione');
define('_MD_AM_AUTHMETHOD', 'Metodo di autenticazione');
define('_MD_AM_AUTHMETHODDESC', 'Il metodo che si intende utilizzare per l\'accesso degli utenti.');
define('_MD_AM_LDAP_MAIL_ATTR', 'LDAP - Campo Mail');
define('_MD_AM_LDAP_MAIL_ATTR_DESC','Nome dell\'attributo E-Mail nella vostra directory LDAP.');
define('_MD_AM_LDAP_NAME_ATTR','LDAP - Campo Common Name');
define('_MD_AM_LDAP_NAME_ATTR_DESC','Nome dell\'attributo Common Name nella vostra directory LDAP.');
define('_MD_AM_LDAP_SURNAME_ATTR','LDAP - Campo Surname');
define('_MD_AM_LDAP_SURNAME_ATTR_DESC','Nome dell\'attributo Surname nella vostra directory LDAP.');
define('_MD_AM_LDAP_GIVENNAME_ATTR','LDAP - Campo Given Name');
define('_MD_AM_LDAP_GIVENNAME_ATTR_DSC','Nome dell\'attributo Given Name nella vostra directory LDAP.');
define('_MD_AM_LDAP_BASE_DN', 'LDAP - DN Base');
define('_MD_AM_LDAP_BASE_DN_DESC', 'Il DN (Distinguished Name) base della vostra directory LDAP.');
define('_MD_AM_LDAP_PORT','LDAP - Numero di Porta');
define('_MD_AM_LDAP_PORT_DESC','Il numero di porta necessario per accedere alla directory del vostro server LDAP.');
define('_MD_AM_LDAP_SERVER','LDAP - Nome del server');
define('_MD_AM_LDAP_SERVER_DESC','Il nome della vostra directory sul server LDAP.');

define('_MD_AM_LDAP_MANAGER_DN', 'DN del gestore LDAP');
define('_MD_AM_LDAP_MANAGER_DN_DESC', 'DN autorizzato a fare ricerche (es. manager)');
define('_MD_AM_LDAP_MANAGER_PASS', 'Password del gestore LDAP');
define('_MD_AM_LDAP_MANAGER_PASS_DESC', 'La passowrd dell\'utente autorizzato a fare ricerche');
define('_MD_AM_LDAP_VERSION', 'LDAP - Versione protocollo');
define('_MD_AM_LDAP_VERSION_DESC', 'La versione di protocollo LDAP: 2 o 3');
define('_MD_AM_LDAP_USERS_BYPASS', ' Utenti ImpressCMS esclusi dall\'autenticazione LDAP');
define('_MD_AM_LDAP_USERS_BYPASS_DESC', 'Utenti cui &egrave; permesso di non effettuare il login LDAP.<br />Login diretto in ImpressCMS<br />Separare ogni nome utente con <strong>|</strong>');

define("_MD_AM_LDAP_USETLS", "Utilizza una connessione TLS");
define("_MD_AM_LDAP_USETLS_DESC", "Imposta una connessione TLS (Transport Layer Security).<BR />TLS utlizza di default la porta 389.<BR />" .
								  " La versione LDAP deve essere impostata sul valore '3'.");

define('_MD_AM_LDAP_LOGINLDAP_ATTR','Attributo LDAP da utilizzare per cercare un utente');
define('_MD_AM_LDAP_LOGINLDAP_ATTR_D','Quando &egrave; abilitata l\'opzione "Utilizzare il nome utente per il DN" deve corrispondere al nome utente (uid) ImpressCMS');
define('_MD_AM_LDAP_LOGINNAME_ASDN', 'Utilizzare il nome utente per il DN');
define('_MD_AM_LDAP_LOGINNAME_ASDN_D', 'Il nome utente ImpressCMS verr&agrave; usato nel DN LDAP<br />(es : uid=<loginname>,dc=ImpressCMS,dc=org)<br />Il dato viene letto direttamente dal server LDAP, senza ricerca.');

define('_MD_AM_LDAP_FILTER_PERSON', 'Filtro per la ricerca utente usato da LDAP');
define('_MD_AM_LDAP_FILTER_PERSON_DESC', 'Filtro speciale LDAP per la ricerca di un utente. @@loginname@@ viene sostituito dall\'id di accesso dell\'utente<br /> LASCIARE IN BIANCO SE NON SI CONOSCE IL CODICE!' .
		'<br />Es : (&(objectclass=person)(samaccountname=@@loginname@@)) per AD' .
		'<br />Es : (&(objectclass=inetOrgPerson)(uid=@@loginname@@)) per LDAP');

define('_MD_AM_LDAP_DOMAIN_NAME', 'Nome del dominio');
define('_MD_AM_LDAP_DOMAIN_NAME_DESC', 'Nome dominio Windows (solo per server ADS e NT)');

define('_MD_AM_LDAP_PROVIS', 'Generazione automatica degli account ImpressCMS');
define('_MD_AM_LDAP_PROVIS_DESC', 'Crea un database utenti ImpressCMS se non ne esiste gi&agrave; uno');

define('_MD_AM_LDAP_PROVIS_GROUP', 'Gruppo di default');
define('_MD_AM_LDAP_PROVIS_GROUP_DSC', 'I nuovi utenti vengono inseriti in questi gruppi');

define("_MD_AM_LDAP_FIELD_MAPPING_ATTR", "Mappatura campi server ImpressCMS-Auth");
define("_MD_AM_LDAP_FIELD_MAPPING_DESC", "Definisci qui la corrispondenza tra il campo del database ImpressCMS e il campo del sistema di autenticazione LDAP." .
		"<br /><br />Formato: [Campo Databse ImpressCMS]=[Attributo sistema LDAP]" .
		"<br />per esempio : email=mail" .
		"<br />Separate ciascuna coppia di campi con <b>|</b>" .
		"<br /><br />!! Per utenti esperti !!");
		
define("_MD_AM_LDAP_PROVIS_UPD", "Tieni aggiornata la gestione dell'account ImpressCMS");
define("_MD_AM_LDAP_PROVIS_UPD_DESC", "L'account utente ImpressCMS verr&agrave; sempre sincronizzato con il server di autenticazione");

//lang constants for secure password
define("_MD_AM_PASSLEVEL","Livello minimo di sicurezza");
define("_MD_AM_PASSLEVEL_DESC","Definisci quale livello di sicurezza vuoi per la password degli utenti. È raccomandato non impostarlo troppo basso o troppo forte, sii ragionevole.");
define("_MD_AM_PASSLEVEL1","Spento(Insicuro)");
define("_MD_AM_PASSLEVEL2","Debole");
define("_MD_AM_PASSLEVEL3","Ragionevole");
define("_MD_AM_PASSLEVEL4","Forte");
define("_MD_AM_PASSLEVEL5","Sicuro");
define("_MD_AM_PASSLEVEL6","Senza classificazione");

define("_MD_AM_RANKW","Massima larghezza dell'immagine del rango (pixel)");
define("_MD_AM_RANKH","Massima altezza dell'immagine del rango (pixel)");
define("_MD_AM_RANKMAX","Massima dimensione dell'immagine del rango (byte)");

define("_MD_AM_MULTILANGUAGE","Impostazioni per il multilingua");
define("_MD_AM_ML_ENABLE","Attiva multilingua");
define("_MD_AM_ML_ENABLEDSC","Imposta s&igrave; per abilitare il multilingua nel sito.");
define("_MD_AM_ML_TAGS","Tags Multilingua");
define("_MD_AM_ML_TAGSDSC","Immetti i tags da utilizzare su questo sito, separati da una virgola. Esempio: en,fr,it (questi tag possono essere usati per definire l'inglese, il francese e l'italiano)");
define("_MD_AM_ML_NAMES","Nome lingue");
define("_MD_AM_ML_NAMESDSC","Inserisci il nome delle lingue da usare, separate da una virgola");
define("_MD_AM_ML_CAPTIONS","Intestazioni delle lingue");
define("_MD_AM_ML_CAPTIONSDSC","Inserisci le intestazioni che vorresti usare per le lingue");
define("_MD_AM_ML_CHARSET","Set di caratteri");
define("_MD_AM_ML_CHARSETDSC","Inserisci il set di caratteri (charset) di queste lingue");

define("_MD_AM_REMEMBERME","Attiva la funzione 'Ricordami' nel login.");
define("_MD_AM_REMEMBERMEDSC","La funzione 'Ricordami' pu&ograve; rappresentare problema di sicurezza. Usala a tuo rischio.");

define("_MD_AM_PRIVDPOLICY","Attiva la 'Informativa Privacy'");
define("_MD_AM_PRIVDPOLICYDSC","La 'Informativa Privacy' dovrebbe essere sempre attiva nel tuo sito ogni volta che permetti l'iscrizione e la registrazione di nuovi utenti raccogliendo dati personali. Una volta attivata apparir&agrave; un link nel menu principale. Il testo contenuto va adattato alle singole esigenze di ogni sito.");
define("_MD_AM_PRIVPOLICY","Inserisci la 'Informativa Privacy' del tuo sito.");
define("_MD_AM_PRIVPOLICYDSC","");

define("_MD_AM_WELCOMEMSG","Invia un messaggio di benvenuto ai nuovi utenti registrati");
define("_MD_AM_WELCOMEMSGDSC","Invia un messaggio di benvenuto ai nuovi utenti quando il loro account &egrave; attivato. Il contenuto del messaggio pu&ograve; essere configurato nella seguente opzione.");
define("_MD_AM_WELCOMEMSG_CONTENT","Testo del messaggio di benvenuto");
define("_MD_AM_WELCOMEMSG_CONTENTDSC","Tu puoi modificare il messaggio da inviare al nuovo utente. Nota che puoi usare i seguenti tags: <br /><br />- {UNAME} = Nome utente<br />- {X_UEMAIL} = email dell'utente<br />- {X_ADMINMAIL} = indirizzo email dell'amministratore<br />- {X_SITENAME} = nome del sito<br />- {X_SITEURL} = URL del sito");

define("_MD_AM_SEARCH_USERDATE","Mostra utente e dati nei risultati di ricerca");
define("_MD_AM_SEARCH_USERDATEDSC","");
define("_MD_AM_SEARCH_NO_RES_MOD","Mostra moduli che non contengono nessun risultato di ricerca");
define("_MD_AM_SEARCH_NO_RES_MODDSC","");
define("_MD_AM_SEARCH_PER_PAGE","Oggetti per pagina nei risultati di ricerca");
define("_MD_AM_SEARCH_PER_PAGEDSC","");

define("_MD_AM_EXT_DATE","Vuoi utilizzare una funzione di data locale estesa?");
define("_MD_AM_EXT_DATEDSC","Nota: attivando questa opzione, ImpressCMS utilizzer&agrave; uno script di calendario esteso <b>SOLO</b> se hai questo script attivo sul sito.<br />Sei pregato di visitare <a href='http://wiki.impresscms.org/index.php?title=Extended_date_function'>la funzione data estesa sul wiki</a> per maggiori informazioni.");

define("_MD_AM_EDITOR_DEFAULT","Editor di default");
define("_MD_AM_EDITOR_DEFAULT_DESC","Seleziona l'editor di default per tutto il sito.");

define("_MD_AM_EDITOR_ENABLED_LIST","Attiva editor");
define("_MD_AM_EDITOR_ENABLED_LIST_DESC","Seleziona fra gli editor preferibili dai moduli (Se il modulo ha una opzione per selezionare l'editor.)");

define("_MD_AM_ML_AUTOSELECT_ENABLED","Seleziona automaticamente la lingua a seconda della configurazione del browser");

define("_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE","Permetti agli utenti anonimi di vedere i profili degli utenti.");

define("_MD_AM_ENC_TYPE","Cambia la criptazione della password (default &egrave; SHA256)");
define("_MD_AM_ENC_TYPEDSC","Cambia l'algoritmo usato per criptare le password degli utenti.<br />Dopo averlo cambiato tutte le password saranno inutilizzabili e tutti gli utenti dovranno richiederne delle nuove al sistema.");
define("_MD_AM_ENC_MD5","MD5 (non raccomandato)");
define("_MD_AM_ENC_SHA256","SHA 256 (raccomandato)");
define("_MD_AM_ENC_SHA384","SHA 384");
define("_MD_AM_ENC_SHA512","SHA 512");
define("_MD_AM_ENC_RIPEMD128","RIPEMD 128");
define("_MD_AM_ENC_RIPEMD160","RIPEMD 160");
define("_MD_AM_ENC_WHIRLPOOL","WHIRLPOOL");
define("_MD_AM_ENC_HAVAL1284","HAVAL 128,4");
define("_MD_AM_ENC_HAVAL1604","HAVAL 160,4");
define("_MD_AM_ENC_HAVAL1924","HAVAL 192,4");
define("_MD_AM_ENC_HAVAL2244","HAVAL 224,4");
define("_MD_AM_ENC_HAVAL2564","HAVAL 256,4");
define("_MD_AM_ENC_HAVAL1285","HAVAL 128,5");
define("_MD_AM_ENC_HAVAL1605","HAVAL 160,5");
define("_MD_AM_ENC_HAVAL1925","HAVAL 192,5");
define("_MD_AM_ENC_HAVAL2245","HAVAL 224,5");
define("_MD_AM_ENC_HAVAL2565","HAVAL 256,5");

//Content Manager
define("_MD_AM_CONTMANAGER","Manager pagine statiche");
define("_MD_AM_DEFAULT_CONTPAGE","Pagina di apertura");
define("_MD_AM_DEFAULT_CONTPAGEDSC","Seleziona la pagina di apertura che deve essere mostrata all'utente che entra nel manager pagine statiche. Lasciare in bianco se vuoi avere come pagina di apertura l'ultima pi&ugrave; recentemente creata.");
define("_MD_AM_CONT_SHOWNAV","Mostra il menu di navigazione sul lato utente?");
define("_MD_AM_CONT_SHOWNAVDSC","Seleziona s&igrave; per mostrare il menu di navigazione del manager di pagine statiche.");
define("_MD_AM_CONT_SHOWSUBS","Mostra le pagine correlate?");
define("_MD_AM_CONT_SHOWSUBSDSC","Seleziona s&igrave; per mostrare i links delle pagine correlate alle pagine statiche.");
define("_MD_AM_CONT_SHOWPINFO","Mostrare informazioni dell'autore e della pubblicazione?");
define("_MD_AM_CONT_SHOWPINFODSC","Selezionare s&igrave; per mostrare nella pagina informazioni circa l'autore e i dati di pubblicazione.");
define("_MD_AM_CONT_ACTSEO","Utilizza nell'url il titolo menu invece dell'id (seo)?");
define("_MD_AM_CONT_ACTSEODSC","Seleziona s&igrave; per il valore del titolo del menu invece che per l'id nell'url della pagina.");
//Captcha (Security image)
define('_MD_AM_USECAPTCHA', 'Vuoi utilizzare CAPTCHA nel modulo di registrazione?');
define('_MD_AM_USECAPTCHADSC', 'Selezionare s&igrave; per CAPTCHA (anti-spam/bot) nel modulo di iscrizione nuovo utente.');
define('_MD_AM_USECAPTCHAFORM', 'Vuoi utilizzare CAPTCHA per i commenti?');
define('_MD_AM_USECAPTCHAFORMDSC', 'Seleziona s&igrave; per aggiungere CAPTCHA (anti-spam) ai commenti per evitare spamming.');
define('_MD_AM_ALLWHTSIG', 'Vuoi permettere che nella firma siano usate immagini esterne e HTML?');
define('_MD_AM_ALLWHTSIGDSC', 'Se un utente che attacca il sito inserisce un\'immagine esterna usando [img], pu&ograve; conoscere IPs o User-Agents di ogni utente che visita il tuo sito.<br />Permettendo HTML puoi causare una vulnerabilit&agrave; dovuta alla possibilit&agrave; di inserire Script se utenti maliziosi cambiano la propria firma.');
define('_MD_AM_ALLWSHOWSIG', 'Vuoi permettere ai tuoi utenti di usare una firma nei propri profili/messaggi del tuo sito?');
define('_MD_AM_ALLWSHOWSIGDSC', 'Attivando questa opzione permetti che ogni utente possa avere una firma personale che si appone automaticamente (a scelta dell\'utente) dopo i propri posts.');
// < personalizações > fabio - Sat Apr 28 11:55:00 BRT 2007 11:55:00
define("_MD_AM_PERSON","Personalizzazione");
define("_MD_AM_GOOGLE_ANA","Google Analytics");
define("_MD_AM_GOOGLE_ANA_DESC","Scrivi l'id-code di Google Analytics, somigliante a questo: <small>_uacct = \"UA-<font color=#FF0000><b>xxxxxx-x</b></font>\"</small><br />OR<small><br />var pageTracker = _gat._getTracker( UA-\"<font color=#FF0000><b>xxxxxx-x</b></font>\");</small> (devi scrivere l'id-code in rosso)
.");
define("_MD_AM_LLOGOADM","Logo sinistro lato amministrativo");
define("_MD_AM_LLOGOADM_DESC"," Seleziona un'immagine da usare nell'angolo sinistro alto del pannello amministrativo. <br /><i>Per selezionare o inviare un'immagine deve essere presente almeno una categoria immagine in sistema > immagini</i> ");
define("_MD_AM_LLOGOADM_URL","Link immagine logo sinistro");
define("_MD_AM_LLOGOADM_ALT","Titolo immagine logo sinistro");
define("_MD_AM_RLOGOADM","Logo destro lato amministrativo");
define("_MD_AM_RLOGOADM_DESC"," Seleziona un'immagine da usare nell'angolo destro alto del pannello amministrativo. <br /><i>Per selezionare o inviare un'immagine deve essere presente almeno una categoria immagine in Sistema > Immagini</i>  ");
define("_MD_AM_RLOGOADM_URL","Link immagine logo destro");
define("_MD_AM_RLOGOADM_ALT","Titolo immagine logo sinistro");
define("_MD_AM_METAGOOGLE","Google Meta Tag");
define("_MD_AM_METAGOOGLE_DESC","Codice generato da Google per confermare il possesso di un sito e permettere l'utilizzo degli strumenti Google per i webmasters. Maggiori informazioni sono disponibili su http://www.google.com/webmasters");
define("_MD_AM_RSSLOCAL","Notizie lato amministrativo: URL RSS");
define("_MD_AM_RSSLOCAL_DESC","URL di una fonte RSS che pu&ograve; essere mostrata alla voce del menu The ImpressCMS Project > News.");
define("_MD_AM_FOOTADM","Pi&egrave; di pagina amministrativo");
define("_MD_AM_FOOTADM_DESC","Contenuti del pi&egrave; di pagina che sono visualizzati nelle pagine amministrative.");
define("_MD_AM_EMAILTTF","Font usato nel sistema di protezione degli indirizzi email");
define("_MD_AM_EMAILTTF_DESC","Seleziona quale font di carattere sar&agrave; utilizzato per generare la protezione dell'indirizzo email.<br /><i>Questa opzione si applica solo se 'Proteggere indirizzi email contro lo SPAM?' &egrave; impostato su Sì.</i>");
define("_MD_AM_EMAILLEN","Misura del carattere usato nella protezione degli indirizzi email");
define("_MD_AM_EMAILLEN_DESC","<i>Questa opzione si applica solo se 'Proteggere indirizzi email contro lo SPAM?' &egrave; impostato su Sì.</i>");
define("_MD_AM_EMAILCOLOR","Colore del carattere usato nella protezione degli indirizzi email");
define("_MD_AM_EMAILCOLOR_DESC","<i>Questa opzione si applica solo se 'Proteggere indirizzi email contro lo SPAM?' &egrave; impostato su Sì.</i>");
define("_MD_AM_EMAILSHADOW","Colore ombra usato nella protezione degli indirizzi email");
define("_MD_AM_EMAILSHADOW_DESC","Scegli un colore per l'ombra della protezione degli indirizi email. Lascia in bianco se non desideri usarne uno.<br /><i>Questa opzione si applica solo se 'Proteggere indirizzi email contro lo SPAM?' &egrave; impostato su Sì.</i>");
define("_MD_AM_SHADOWX","X offset dell'ombra usata nella protezione indirizzi email");
define("_MD_AM_SHADOWX_DESC","Metti un valore (in px) che rappresentano la sporgenza in orizzontale dell'ombra nella protezione degli indirizzi email.<br /><i>Questa opzione si applica solo se 'Colore ombra usato nella protezione degli indirizzi email' non &egrave; vuoto.</i>");
define("_MD_AM_SHADOWY","Y offset dell'ombra usata nella protezione indirizzi email");
define("_MD_AM_SHADOWY_DESC","Metti un valore (in px) che rappresenta la sporgenza in verticale dell'ombra nella protezione degli indirizzi email.<br /><i>Questa opzione si applica solo se 'Colore ombra usato nella protezione degli indirizzi email' non &egrave; vuoto.");
define("_MD_AM_EDITREMOVEBLOCK","Link a Modifica e Rimuovi blocchi dal lato utente?");
define("_MD_AM_EDITREMOVEBLOCKDSC","Attivando questa opzione saranno mostrata una icona nel titolo dei blocchi che permette un accesso diretto alla rimozione o modifica.");

define("_MD_AM_EMAILPROTECT","Proteggere indirizzi email contro lo SPAM?");
define("_MD_AM_EMAILPROTECTDSC","Attivando questa opzione si assicura una costante protezione dagli SPAMbots di un indirizzo email mostrato sul sito.");
define("_MD_AM_MULTLOGINPREVENT","Previeni login multipli dello stesso nome utente?");
define("_MD_AM_MULTLOGINPREVENTDSC","Con questa opzione abilitata non &egrave; possibile che uno stesso nome utente possa essere utilizzato da pi&ugrave; di un utente alla volta durante tutto il tempo della sessione.");
define("_MD_AM_MULTLOGINMSG","Multilogin: messaggio di re-indirizzamento");
define("_MD_AM_MULTLOGINMSG_DESC","Messaggio che verr&agrave; mostrato a un utente che prover&agrave; a fare il login con un nome utente gi&agrave; loggato. <br><i>Questa opzione si applica solo se 'Previeni login multipli dello stesso nome utente?' &egrave; impostato su Si.</i>");
define("_MD_AM_GRAVATARALLOW","Consenti l'uso di GRAVATAR?");
define("_MD_AM_GRAVATARALWDSC","Mostra immagini collegate alle utenze di quei membri che sono iscritti a <a href='http://www.gravatar.com/' target='_blank'>Gravatar</a>, un servizio gratuito. Permettendolo sar&agrave; possibile agli utenti di usare l'avatar/gravatar linkato al proprio indirizzo email.");

define("_MD_AM_SHOW_ICMSMENU","Mostra il menu drop-down del Progetto ImpressCMS?");
define("_MD_AM_SHOW_ICMSMENU_DESC","Seleziona No per mostrare il menu drop-down e S&igrave; per mostrarlo.");

define("_MD_AM_SHORTURL","Troncare gli URL lunghi?");
define("_MD_AM_SHORTURLDSC","Impostare questa opzione su S&igrave; se si vuole che tutte le URL siano automaticamente troncate a un certo numero di caratteri. Le URL lunghe, in un post di forum ad esempio, possono spesso creare problemi di design al sito.");
define("_MD_AM_URLLEN","Massima lunghezza URL");
define("_MD_AM_URLLEN_DESC","Il numero massimo di caratteri di una URL. I caratteri extra saranno troncati automaticamente.<br /><i>Questa opzione sar&agrave; applicata solo se 'Troncare gli URL lunghi?' &egrave; impostata su Sì.</i>");
define("_MD_AM_PRECHARS","Numero di caratteri iniziali");
define("_MD_AM_PRECHARS_DESC","Quanti caratteri mostrare all'inizio di una URL?<br /><i>Questa opzione sar&agrave; applicata solo se 'Troncare gli URL lunghi?' &egrave; impostata su S&igrave;.</i>");
define("_MD_AM_LASTCHARS","Numero di caratteri finali");
define("_MD_AM_LASTCHARS_DESC","Quanti caratteri mostrare alla fine di una URL?<br /><i>Questa opzione sar&agrave; applicata solo se 'Troncare gli URL lunghi?' &egrave; impostata su S&igrave;.");
define("_MD_AM_SIGMAXLENGTH","Massimo numero di caratteri nella firma utenti?");
define("_MD_AM_SIGMAXLENGTHDSC","Puoi scegliere la lunghezza della firma dei tuoi utenti.<br /> Ogni carattere che supera questo valore sar&agrave; ignorato.<br /><i>Sii cauto perch&eacute; le firme lunghe possono creare problemi di design al sito.</i>");

define("_MD_AM_AUTHOPENID","Attiva autenticazione OpenID");
define("_MD_AM_AUTHOPENIDDSC","Seleziona Sì per abilitare l'autenticazione OpenID. Questa permette agli utenti di fare login nel sito usando la loro iscrizione OpenID. Per informazioni complete sull'integrazione OpenID in ImpressCMS visitare <a href='http://wiki.impresscms.org/index.php?title=ImpressCMS_OpenID'> il  wiki (in inglese)</a>.");
define("_MD_AM_USE_GOOGLE_ANA","Attivare Google Analytics?");
define("_MD_AM_USE_GOOGLE_ANA_DESC","");

######################## Added in 1.2 ###################################
define("_MD_AM_CAPTCHA","Impostazioni Captcha");
define("_MD_AM_CAPTCHA_MODE","Modalit&agrave; Captcha");
define("_MD_AM_CAPTCHA_MODEDSC","Si prega di selezionare un tipo di Captcha per il tuo sito web");
define("_MD_AM_CAPTCHA_SKIPMEMBER","Gruppi liberi da Captcha");
define("_MD_AM_CAPTCHA_SKIPMEMBERDSC","Seleziona i gruppi ai quali non &egrave; richiesto un captcha. Questi gruppi non vedranno mai il campo captcha.");
define("_MD_AM_CAPTCHA_CASESENS","Maiuscole e minuscole");
define("_MD_AM_CAPTCHA_CASESENSDSC","I caratteri nella modalit&agrave; immagine sono case-sensitive");
define("_MD_AM_CAPTCHA_MAXATTEMP","Tentativi massimi possibili");
define("_MD_AM_CAPTCHA_MAXATTEMPDSC","Massimo dei tentativi possibili per ogni sessione");
define("_MD_AM_CAPTCHA_NUMCHARS","Massimo caratteri?");
define("_MD_AM_CAPTCHA_NUMCHARSDSC","Numero massimo di caratteri da generare");
define("_MD_AM_CAPTCHA_FONTMIN","Dimensione carattere minima");
define("_MD_AM_CAPTCHA_FONTMINDSC","");
define("_MD_AM_CAPTCHA_FONTMAX","Dimensione carattere massima");
define("_MD_AM_CAPTCHA_FONTMAXDSC","");
define("_MD_AM_CAPTCHA_BGTYPE","Tipo sfondo");
define("_MD_AM_CAPTCHA_BGTYPEDSC","Sfondo nella modalit&agrave; immagine");
define("_MD_AM_CAPTCHA_BGNUM","Immagini di sfondo");
define("_MD_AM_CAPTCHA_BGNUMDSC","Numero di immagini di sfondo da generare");
define("_MD_AM_CAPTCHA_POLPNT","Punti poligono");
define("_MD_AM_CAPTCHA_POLPNTDSC","Numero di punti poligono da generare");
define("_MD_AM_BAR","Barra");
define("_MD_AM_CIRCLE","Cerchio");
define("_MD_AM_LINE","Linea");
define("_MD_AM_RECTANGLE","Rettangolo");
define("_MD_AM_ELLIPSE","Ellisse");
define("_MD_AM_POLYGON","Poligono");
define("_MD_AM_RANDOM","Casuale");
define("_MD_AM_CAPTCHA_IMG","Immagine");
define("_MD_AM_CAPTCHA_TXT","Testo");
define("_MD_AM_CAPTCHA_OFF","Disabilitato");
define("_MD_AM_CAPTCHA_SKIPCHAR","Ometti caratteri");
define("_MD_AM_CAPTCHA_SKIPCHARDSC","Questa opzione permette di omettere dei caratteri nel generare il Captcha");
define('_MD_AM_PAGISTYLE','Stile dei paginations links:');
define('_MD_AM_PAGISTYLE_DESC','Seleziona lo stile dei paginations links.');
define('_MD_AM_ALLWCHGUNAME', 'Permetti agli utenti di cambiare il nome per il sito?');
define('_MD_AM_ALLWCHGUNAMEDSC', '');
define("_MD_AM_JALALICAL","Usa calendario esteso con Jalali?");
define("_MD_AM_JALALICALDSC","Con questa impostazione &egrave; possibile disporre di un calendario esteso nei moduli.<br />Sii prudente, questo calendario non &egrave; compatibile con alcuni browsers.");
define("_MD_AM_NOMAILPROTECT","Nessuno");
define("_MD_AM_GDMAILPROTECT","Protezione GD");
define("_MD_AM_REMAILPROTECT","re-Captcha");
define("_MD_AM_RECPRVKEY","re-Captcha private api code");
define("_MD_AM_RECPRVKEY_DESC","");
define("_MD_AM_RECPUBKEY","re-Captcha public api code");
define("_MD_AM_RECPUBKEY_DESC","");
define("_MD_AM_CONT_NUMPAGES","Numero di pagine sulla lista in modalit&agrave; tag");
define("_MD_AM_CONT_NUMPAGESDSC","Definisci il numero di pagine per mostrare dal lato utente sulla lista in modalit&agrave; tag.");
define("_MD_AM_CONT_TEASERLENGTH","Lunghezza Teaser");
define("_MD_AM_CONT_TEASERLENGTHDSC","Numero di caratteri della pagina teaser nella lista in modalit&agrave; tag.<br />Imposta a 0 per nessun limite.");
define("_MD_AM_STARTPAGEDSC","Seleziona il modulo desiderato o la pagina iniziale per ogni gruppo.");
define("_MD_AM_DELUSRES","Rimuovi utenti inattivi");
define("_MD_AM_DELUSRESDSC","Questa opzione rimuover&agrave; utenti che sono registrati ma che non hanno attivato il loro account per X giorni. <br />Inserisci la quantit&agrave; di giorni.");
define("_MD_AM_PLUGINS","Plugins Manager");
define("_MD_AM_SELECTSPLUGINS","Seleziona i plugins di cui si consente l'utilizzo");
define("_MD_AM_SELECTSPLUGINS_DESC","Tramite questa opzione puoi scegliere quali plugins di filtraggio dei testi saranno utilizzati.");
define("_MD_AM_GESHI_DEFAULT","Seleziona plugin da utilizzare per geshi");
define("_MD_AM_GESHI_DEFAULT_DESC","GeSHi (Generic Syntax Hilighter) &egrave; un evidenziatore della sintassi per i tuoi codici.");
define("_MD_AM_SELECTSHIGHLIGHT","Seleziona il tipo di evidenziatore per i codici");
define("_MD_AM_SELECTSHIGHLIGHT_DESC","Tramite questa opzione puoi scegliere quale evidenziatore sar&agrave; utilizzato per i tuoi codici.");
define("_MD_AM_HIGHLIGHTER_GESHI","Evidenziatore GeSHi");
define("_MD_AM_HIGHLIGHTER_PHP","Evidenziatore PHP");
define("_MD_AM_HIGHLIGHTER_OFF","Disabilitato");
define('_MD_AM_DODEEPSEARCH', "Vuoi abilitare la ricerca 'approfondita'?");
define('_MD_AM_DODEEPSEARCHDSC', "Vuoi che la tua pagina iniziale di ricerca indichi quanti risultati sono stati trovati in ciascun modulo? Nota: attivare questa opzione pu&ograve; rallentare il processo di ricerca!");
define('_MD_AM_NUMINITSRCHRSLTS', "Numero di risultati di ricerca iniziali: (per le ricerche 'superficiali')");
define('_MD_AM_NUMINITSRCHRSLTSDSC', "'Le ricerche 'superficiali' sono fatte velocemente limitando i risultati riportati da ogni modulo nella pagina iniziale.");
define('_MD_AM_NUMMDLSRCHRESULTS', "Numero di risultati di ricerca per pagina:");
define('_MD_AM_NUMMDLSRCHRESULTSDSC', "Questo determina quanti risultati saranno mostrati per pagina nell'approfondimento su un particolare modulo.");
define('_MD_AM_ADMIN_DTHEME', 'Tema lato amministrativo');
define('_MD_AM_ADMIN_DTHEME_DESC', '');
define('_MD_AM_CUSTOMRED', 'Utilizza il metodo di reindirizzamento in Ajax?');
define('_MD_AM_CUSTOMREDDSC', '');
define('_MD_AM_DTHEMEDSC','Tema di default utilizzato nel tuo sito.');

// Added in 1.2

// HTML Purifier preferences
define("_MD_AM_PURIFIER","Impostazioni HTML Purifier");
define("_MD_AM_PURIFIER_ENABLE","Attiva HTML Purifier");
define("_MD_AM_PURIFIER_ENABLEDSC","Seleziona s&igrave; per attivare i filtri HTML Purifier, disattivandolo lasci il sito vulnerabile agli attacchi perch&eacute; si consente l'inserimento contenuti HTML");
//HTML section
define("_MD_AM_PURIFIER_HTML_TIDYLEVEL","HTML Tidy Level");
define("_MD_AM_PURIFIER_HTML_TIDYLEVELDSC","Livelli di purificazione del codice garantiti dal modulo Tidy.<br /><br />
Nessuno = il codice non viene sottoposto a nessuna correzione,<br />Leggero = corregge solo quegli elementi che sarebbero scartati altrimenti per la mancanza di supporto nel doctype,<br />
Medio = applica pratiche migliori,<br />Forte = trasforma tutti gli elementi e gli attributi deprecati portandoli agli standard compatibili equivalenti.");
define("_MD_AM_PURIFIER_NONE","Nessuno");
define("_MD_AM_PURIFIER_LIGHT","Leggero");
define("_MD_AM_PURIFIER_MEDIUM","Medio (raccomandato)");
define("_MD_AM_PURIFIER_HEAVY","Forte");
define("_MD_AM_PURIFIER_HTML_DEFID","ID definizione HTML");
define("_MD_AM_PURIFIER_HTML_DEFIDDSC","Imposta il nome ID default della configurazione del purifier (lascia com'&egrave; altro che non stai creando una configurazione personalizzata e sai cosa stai facendo)");
define("_MD_AM_PURIFIER_HTML_DEFREV","Numero revisione definizione HTML");
define("_MD_AM_PURIFIER_HTML_DEFREVDSC","Esempio: la revisione 3 &egrave; pi&ugrave; aggiornata della 2. Così, quando questo viene incrementato questo valore la gestione della cache è abbastanza intelligente da pulire eventuali revisioni più vecchie di tua definizione e anche svuotare la cache. <br /> Puoi lasciare questa opzione così com'è se non sai cosa stai facendo e modificare direttamente il Purifier.");
define("_MD_AM_PURIFIER_HTML_DOCTYPE","HTML DocType");
define("_MD_AM_PURIFIER_HTML_DOCTYPEDSC","Doctype da utilizzare durante il filtraggio. Tecnicamente parlando questo non &egrave; attualmente un doctype (in quanto non identifica una DTD corrispondente), ma stiamo usando questo nome per ragioni di semplicità. Quando non vuoto, questo avrà la precedenza su eventuali direttive precedenti, come HTML o XHTML (Strict).");
define("_MD_AM_PURIFIER_HTML_ALLOWELE","Elementi consentiti");
define("_MD_AM_PURIFIER_HTML_ALLOWELEDSC","Whitelist di elementi HTML che sono consentiti nei posts. Ogni elemento inserito qui non sar&agrave; filtrato nei post degli utenti. Devi autorizzare solo elementi HTML sicuri.");
define("_MD_AM_PURIFIER_HTML_ALLOWATTR","Attributi consentiti");
define("_MD_AM_PURIFIER_HTML_ALLOWATTRDSC","Whitelist di elementi HTML che sono consentiti nei posts. Ogni attributo inserito qui non sar&agrave; filtrato nei post degli utenti. Devi autorizzare solo attributi HTML sicuri.<br /><br />Formatta i tuoi attributi come segue:<br />elemento.attributo (esempio: div.class) permetter&agrave; di utilizzare l'attributo di classe con i div tags. Puoi anche utilizzare wildcards: *.class per esempio permetter&agrave; l'attributo di classe in tutti gli elementi permessi.");
define("_MD_AM_PURIFIER_HTML_FORBIDELE","Elementi proibiti");
define("_MD_AM_PURIFIER_HTML_FORBIDELEDSC","Questa &egrave; la logica inversa di HTML.ELEMENTI CONSENTITI, e avr&agrave; la precedenza su quella direttiva o su ogni altra direttiva.");
define("_MD_AM_PURIFIER_HTML_FORBIDATTR","Attributi proibiti");
define("_MD_AM_PURIFIER_HTML_FORBIDATTRDSC"," Mentre questa direttiva &egrave; simile a Attributi HTML consentiti, per forwards-compatibility con XML, questo attributo ha una differente sintassi.<br />Invece che tag.attr, usa tag@attr. Per non consentire l'attributo href in un tags, impostare questa direttiva a a@href.<br />Puoi anche non consentire un attributo globalmente con attr o *@attr (entrambe le sintassi sono esatte; l'ultima &egrave; &egrave; prevista per essere coerente con Attributi HTML consentit).<br /><br />Attenzione: questa direttiva complementa Elementi HTML proibiti, Forbidden Elements, di conseguenza verifica prima quella direttiva e pensa due volte prima di usare questa direttiva.");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTH","Lunghezza max immagine");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTHDSC","Questa direttiva controlla il numero massimo di pixel nell'attributo di larghezza e di lunghezza del tag img. Questo si fa per prevenire gli attacchi imagecrash, disattivare con 0 a vostro rischio. ");
define("_MD_AM_PURIFIER_HTML_SAFEEMBED","Attiva Safe Embed");
define("_MD_AM_PURIFIER_HTML_SAFEEMBEDDSC","Se autorizzare o vietare i tags embed nei documenti, con una serie di caratteristiche di sicurezza extra aggiunte per impedire l'esecuzione di script. Questo è simile a ciò che fanno siti come MySpace per i tag embed. Embed è un elemento proprietario e farà sì che il tuo sito web fermi la convalida. Devi attivare questa opzione con l'HTML Safe Object. Altamente sperimentale.");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECT","Attiva Safe Object");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECTDSC","Questa opzione chiede se autorizzare o vietare i tags embed nei documenti, con una serie di caratteristiche di sicurezza extra aggiunte per impedire l'esecuzione di script. Questo è simile a ciò che fanno siti come MySpace per i tag object. Devi anche attivare HTML Safe Embed per ottenere il massimo di interoperabilit&agrave; con Internet Explorer, anche se il tags embed farà sì che il tuo sito web fermi la convalida. Altamente sperimentale.");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATA","Relax DTD Name Attribute Parsing");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATADSC","La specifica W3C DTD definisce il nome attributo da CDATA, non ID, a causa di limitazioni del formato DTD. In alcuni documenti, questo comportamento rilassato è auspicato, sia che si tratti di specificare i nomi duplicati, o per specificare i nomi che sarebbero ID illegali (ad esempio, i nomi che iniziano con un numero.) Impostare questa direttiva di configurazione a s&igrave; per utilizzare le regole di parsing rilassato. ");
// URI Section
define("_MD_AM_PURIFIER_URI_DEFID","URI Definition ID");
define("_MD_AM_PURIFIER_URI_DEFIDDSC","Identificatore univoco per una definizione URI costruita su misura. Se si desidera aggiungere un URIFilters personalizzato è necessario specificare questo valore. (lasciare cos&igrave; come &egrave; se non si sa cosa si sta facendo!)");
define("_MD_AM_PURIFIER_URI_DEFREV","Definizione URI Revision Number");
define("_MD_AM_PURIFIER_URI_DEFREVDSC","Esempio: la revisione 3 &egrave; pi&ugrave; aggiornata della 2. Così, quando questo valore viene incrementato la gestione della cache è abbastanza intelligente da pulire eventuali revisioni più vecchie di tua definizione e anche svuotare la cache. <br /> Puoi lasciare questa opzione così com'è se non sai cosa stai facendo e modificare direttamente il Purifier.");
define("_MD_AM_PURIFIER_URI_DISABLE","Disattiva tutti gli URI nei posts utenti");
define("_MD_AM_PURIFIER_URI_DISABLEDSC","La disattivazione URI impedisce agli utenti di inviare eventuali links di sorta, non è consigliabile attivare questa opzione tranne che per scopi di test. <br /> Il default è 'No'");
define("_MD_AM_PURIFIER_URI_BLACKLIST","URI Blacklist");
define("_MD_AM_PURIFIER_URI_BLACKLISTDSC","Immettere i nomi di dominio che devono essere filtrati (eliminati) dai messaggi degli utenti.");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEME","Schemi URI ammessi");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEMEDSC","Whitelist che definisce gli schemi che a una URI &egrave; consentito di avere. Questo previene gli attacchi XSS provenienti dall'uso di pseudo-schemi come javascript o mocha.<br />Valori accettati (http, https, ftp, mailto, nntp, news)");
define("_MD_AM_PURIFIER_URI_HOST","URI Host Domain");
define("_MD_AM_PURIFIER_URI_HOSTDSC","Immetti URI Host. Lascia in bianco per disabilitare!");
define("_MD_AM_PURIFIER_URI_BASE","URI Base Domain");
define("_MD_AM_PURIFIER_URI_BASEDSC","Immetti URI Base. Lascia in bianco per disabilitare!");
define("_MD_AM_PURIFIER_URI_DISABLEEXT","Disattiva Links esterni");
define("_MD_AM_PURIFIER_URI_DISABLEEXTDSC","Disattiva links a siti web esterni. Questo &egrave; altamente efficace contro lo spam e contro  l'anti-pagerank-leech, ma ha un costo in termini che non &egrave; permesso nessun altro link o immagine al di fuori del tuo dominio.<br />URIs non linkate saranno conservate. Se desideri attivare links a sottodomini o usare URIs assolute, attiva URI Host per il tuo sito.");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRES","Disattiva risorse esterne");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRESDSC","Disattiva embedding a risorse esterne, prevenendo gli utenti dall'inserire oggetti come immagini da altri host. Ci&ograve; previene tracking sugli accessi (buono per gli email viewers), bandwidth leeching, cross-site request forging, goatse.cx posting, ed altre brutture, ma dà come risultato una perdita di funzionalità dell'utente finale (ad es. non pi&ugrave; possibile inviare direttamente una foto da loro pubblicato su Flickr). Usalo se non si dispone di un efficente team di moderazione dei contenuti. ");
define("_MD_AM_PURIFIER_URI_DISABLERES","Disattiva risorse");
define("_MD_AM_PURIFIER_URI_DISABLERESDSC","Disattiva risorse incorporate (embedded), essenzialmente significa nessuna immagine. Puoi per&ograve; linkarle. Vedi Disattiva risorse esterne per capire che pu&ograve; essere una buona idea.");
define("_MD_AM_PURIFIER_URI_MAKEABS","Crea URI assolute");
define("_MD_AM_PURIFIER_URI_MAKEABSDSC","Converte tutte le URIs in forme assolute. Ciò è utile quando il codice HTML filtrato presuppone un percorso specifico di base, ma sarà effettivamente letta in un contesto diverso (e la fissazione di un URI di base alternativo non è possibile). < br /> <br /> URI di base deve essere abilitato perch&eacute; questa direttiva funzioni. ");
// Filter Section
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESC","Evita caratteri pericolosi in StyleBlocks");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESCDSC","Se evitare o no caratteri pericolosi come <, > e & come \3C, \3E e \26, rispettivamente. Questo pu&ograve; essere sicuramente impostato a false se il contenuto di StyleBlocks sar&agrave; posto in un foglio di stile esterno, dove non c'&egrave; nessun rischio di essere interpretato come HTML.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPE","Immetti StyleBlocks Scope");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPEDSC","Se vuoi che gli utenti siano in grado di definire i fogli di stile esterni, ma solo consentire loro di specificare le dichiarazioni CSS per un nodo specifico e impedire loro di fare danni con gli altri elementi, allora usa questa direttiva. <br />Essa accetta qualsiasi selettore CSS valido e antepone ad ogni dichiarazione il CSS estratto dal documento.<br /><br />Per esempio, se questa direttiva è impostata a #user-content ed un utente utilizza il selettore a:hover, il selettore finale sarà #user-content a:hover.<br /><br />Pu&ograve; essere utilizzata la virgola; considera il seguente esempio #user-content, #user-content2, il selettore finale sar&agrave; #user-content a:hover, #user-content2 a:hover.");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBE","Consenti di includere Video YouTube");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBEDSC","Questa direttiva attiva l'incorporazione di video YouTube in HTML Purifier. Controlla <a href='http://htmlpurifier.org/docs/enduser-youtube.html'>questo</a> documento sull'incorporazione di video per maggiori informazioni su ci&ograve; che fa questo filtro.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLK","Estrarre i blocchi di stile?");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKDSC","&Egrave; richiesta l'installazione di CSSTidy Plugin.<br /><br />Questa direttiva attiva il filtro di estrazione di blocco di stile, il quale rimuove blocchi di stile dall'input HTML, li purifica con CSSTidy e li pone nello StyleBlocks context variable per futuri tuoi utilizzi, normalmente per essere posti in un foglio di stile esterno o in un blocco di stile nell'header del tuo documento.<br /><br />Avvertenza: è possibile per l'utente creare un attacco imagecrash usando questo css. Il conteggio delle misure è difficoltoso, non è semplice limitare il ventaglio di possibilità delle lunghezze CSS, (attualmente usando lunghezze relative con molti livelli annidiati si permette di raggiungere valori larghi senza tuttavia specificarli nel foglio di stile) e la flessibile natura dei selettori rende difficile disattivare selettivamente le lunghezze sui tags immagine (tuttavia HTML Purifier disabilita la lunghezza e altezza CSS nello style in linea). Ci sono probabilmente due effettive misure di conteggio: una esplicita larghezza e altezza impostate in automatico in tutte le immagini del tuo documento (sfortunatamente) o l'altra disattivando lunghezza e altezza (alquanto regionevole). La decisione di utilizzare o meno queste misure è lasciata al lettore.");
define("_MD_AM_PURIFIER_FILTER_CUSTOM","Seleziona filtri personalizzati");
define("_MD_AM_PURIFIER_FILTER_CUSTOMDSC","Seleziona filtri per Video personalizzati dalla lista");
// Core Section
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGS","Evita tags non validi");
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGSDSC","Quando attivo i tags non validi saranno scritti in fondo al documento come testo semplice. Oppure eliminati senza ulteriore avviso.");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARS","Evita caratteri Non ASCII");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARSDSC","Questa direttiva supera una deficenza nel Core. Codifica alla cieca convertendo tutti i caratteri non-ASCII in entità numerica decimale prima di convertirlo alla sua codifica nativa. Ciò significa che anche i caratteri che possono essere espressi in non-codifica UTF-8 ne saranno soggetti, che può essere un problema reale per le codifiche come Big5. Si ipotizza inoltre che il repertorio ASCII sia disponibile, anche se questo è il caso di quasi tutte le codifiche. In ogni caso, usare sempre UTF-8!");
define("_MD_AM_PURIFIER_CORE_HIDDENELE","Attiva elementi HTML nascosti");
define("_MD_AM_PURIFIER_CORE_HIDDENELEDSC","Questa direttiva è un array di ricerca di elementi che dovrebbero avere il loro contenuto rimosso quando non sono ammessi dalla definizione di HTML. Ad esempio, il contenuto di un tag script non è di norma indicato in un documento, quindi se un tag script deve essere rimosso anche il suo contenuto deve essere rimosso. Questo è l'opposto di a b tag, che definisce alcune modifiche di presentazione, ma non nasconde il suo contenuto.");
define("_MD_AM_PURIFIER_CORE_COLORKEYS","Parole chiave colore");
define("_MD_AM_PURIFIER_CORE_COLORKEYSDSC","Array di ricerca di nomi di colori a sei cifre, il numero esadecimale corrispondente al colore, preceduto da cancelletto. Usati durante l'analisi colori.");
define("_MD_AM_PURIFIER_CORE_REMINVIMG","Rimuovere immagini non valide");
define("_MD_AM_PURIFIER_CORE_REMINVIMGDSC","Questa direttiva consente una verifica preventiva di URI in tag img. Come la strategia di convalida attributo non è autorizzata a rimuovere elementi dal documento. Default = sì");
// AutoFormat Section
define("_MD_AM_PURIFIER_AUTO_AUTOPARA","Attiva auto formattazione paragrafo");
define("_MD_AM_PURIFIER_AUTO_AUTOPARADSC","La presente direttiva si attiva nell'auto-paragraphing, dove a capo doppi vengono convertiti in paragrafi dove ciò è possibile. <br /> Auto-paragraphing: <br /> <br /> * Si applica sempre per gli elementi inline o per il testo nel root node. <br /> * Si applica a elementi inline o testo, con a capo doppi in nodi che consentono tag di paragrafo, <br /> * Si applica a doppio a capo nei tag. <br /> </ br> Il tag p deve essere consentito perché la presente direttiva sia in vigore. Non utilizzare i tag br per fare  paragrafi, anche se è semanticamente corretto. <br /> Per evitare auto-paragraphing come un produttore di contenuti, evitare di utilizzare un doppio a capo, tranne che per specificare un nuovo paragrafo o in contesti in cui esso ha un significato speciale (lo spazio bianco di solito non ha significato se non in tag come pre, quindi questo non dovrebbe essere difficile.) Per prevenire il paragraphing di testo inline adiacente al blocco di elementi, usare il tag div (il comportamento è un po' differente fuori dal nodo radice.)");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURI","Attiva Link Display");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURIDSC","La presente direttiva trasforma la visualizzazione del testo della URI in <a> tag, e disabilita tali collegamenti. Ad esempio, <a href=\"http://example.com\"> esempio < / a> diventa esempio (http://example.com ).");
define("_MD_AM_PURIFIER_AUTO_LINKIFY","Attiva Auto Linkify");
define("_MD_AM_PURIFIER_AUTO_LINKIFYDSC","Con questa direttiva si attiva linkification, auto-linking http, ftp e https URLs. Il tags con l'attributo href deve essere consentito. ");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFY","Attiva Purifier Internal Linkify");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFYDSC","Auto-formattatore che converte le direttive di configurazione in sintassi %Namespace. La direttiva che unisce gli a tags con l'attributo href deve essere consentita. (Lasciare questo così com'è se non stai avendo problemi).");
define("_MD_AM_PURIFIER_AUTO_CUSTOM","Consentita personalizzazione di AutoFormatting");
define("_MD_AM_PURIFIER_AUTO_CUSTOMDSC","Questa direttiva può essere utilizzata per aggiungere auto-format injectors personalizzati. Specificare un array di nomi injector (classe name meno il prefisso) o concretizzare implementazioni. Injector class deve esistere. Visita <a href='www.htmlpurifier.org'>HTML Purifier Homepage</a> per maggiori informazioni.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTY","Rimuovi elementi vuoti");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYDSC","Quando abilitato HTML Purifier proverà a togliere elementi vuoti che non contribuiscono affatto alle informazioni semantiche del documento. I seguenti tipi di nodi saranno rimossi:<br /><br />
 * Tags con nessun attributo o nessun contenuto e che non sono elementi vuoti (rimuove \<a\>\</a\> ma non \<br /\>), e<br />
 * Tags con nessuno contenuto, eccetto che:<br />
   o l'elemento colgroup, <br />
   o Elementi con l'id o il nome attributo, quando quegli attributi sono permessi in quegli elementi.<br /><br />
Si prega di essere molto attenti quando si utilizza questa funzionalità, mentre a volte non sembra che gli elementi vuoti contengano informazioni utili, essi possono alterare il layout di un dato documento in possesso di uno stile appropriato. Questa direttiva è particolarmente utile quando si stanno elaborando HTML generato da macchine, si prega di evitare l'uso su un normale utente HTML. <br /> <br />
Gli elementi che contengono solo gli spazi saranno trattati come vuoti. Spazi non separabili, tuttavia, non contano come spazi vuoti. Vedere 'Rimuovere gli spazi vuoti' per un comportamento alternativo.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSP","Rimuovere Non-Breaking Spaces (Nbsp)");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPDSC","Quando attivato, HTML Purifier tratterà ogni elemento che contenga solo spazi non separabili come spazi bianchi normali e vuoti e li rimuoverà se 'Rimuovi elementi vuoti' è attivato.<br /><br />
Vedi 'Non tener conto di rimozione di Nbsp vuoti' per una lista di elementi che non hanno questo comportamento a loro applicato.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPT","Non tener conto di rimozione di Nbsp vuoti");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPTDSC","Quando è attiva, la presente direttiva definisce quali sono gli elementi HTML che non dovrebbero essere rimossi se hanno in sè solo un non-breaking space.");
// Attribute Section
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGET","Obiettivi di cornice consentiti");
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGETDSC","Tabella di ricerca di tutti gli Obiettivi di cornice consentiti. Alcuni obiettivi comunemente usati includono _blank, _self, _parent e _top. I valori dovrebbero essere in minuscolo, così la convalida verrà fatta in case-sensitive, nonostante la raccomandazione del W3C. XHTML 1.0 Strict non consente di attribuire l'obiettivo in modo tale che direttiva non avrà alcun effetto in quel doctype. XHTML 1.1 non consente il modulo di destinazione predefinito, sarà necessario attivarlo manualmente (vedi la documentazione del modulo per maggiori dettagli).");
define("_MD_AM_PURIFIER_ATTR_ALLOWREL","Consentire Document Relationships");
define("_MD_AM_PURIFIER_ATTR_ALLOWRELDSC","Lista di documenti di relazione di inoltro consentiti  con l'attributo rel. Valori comuni potrebbero essere nofollow o print.<br /><br />Default = external, nofollow, external nofollow e lightbox.");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSES","Class Values consentite");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSESDSC","Lista di valori di classe nell'attributo di classe. Lascia vuoto per permettere a tutti i valori nell'attributo di classe.");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSES","Valori di classe proibiti");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSESDSC","Lista di valori di classe proibiti nell'attributo di classe. Lascialo vuoto per permettere a tutti i valori nell'attributo di classe.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMG","Immagine non valida per default");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGDSC","L'immagine di default è un tag immagine a cui puntare se non c'è un attributo src valido. In future versioni noi potremo permettere che il tag img sia rimosso completamente, ma per via dei problemi incontrati non è al momento possibile.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALT","Tag alt di default nelle immagini non valide");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALTDSC","Questo diventa il contenuto dell'attributo alt di una immagine non valida se l'utente non ne ha specificato uno in precedenza.  <br />Non ha alcun effetto quando l'immagine è valida ma non c'è nessun attributo alt presente.");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALT","Tag alt di default");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALTDSC","Questo diventa il contenuto del tag alt di una immagine se l'utente non ne ha specificato uno in precedenza. <br />Si applica a ogni immagine senza un valido attributo alt. È opposto a Tag alt di default nelle immagini non valide, che si applica solo a immagini non valide e sovrascrive in caso di immagini non valide.<br />Il comportamento di default con null è di usare il  basename del src tag per alt.");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATA","Usa NMTokens o specificazioni CDATA");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATADSC","Se null, la class autorileverà il doctype e, se incontra XHTML 1.1 o XHTML 2.0, userà la specifica restrittiva della classe NMTOKENS. Altrimenti userà la definizione CDATA più rilassata. Se true, la definizione CDATA più rilassata è imposta; se false, la definizione NMTOKENS è imposta. Per ottenere un comportamento di HTML Purifier precedente alla 4.0.0, impostare questo parametro a false. Alcune ragioni per l'auto-rilevazione: nelle precedenti versioni di HTML Purifier, è stato presunto che la forma di classe era NMTOKENS, come specificato dalla Modularizzazione XHTML (che rappresentano XHTML 1.1 e XHTML 2.0). Il DTD per HTML 4.01 e XHTML 1.0, tuttavia specifica la classe CDATA. HTML 5 definisce efficacemente come CDATA, ma con l' ulteriore vincolo che ogni nome deve essere univoco (questo non è esplicitamente indicato nelle specifiche precedenti ).");
define("_MD_AM_PURIFIER_ATTR_ENABLEID","Consentire attributo ID?");
define("_MD_AM_PURIFIER_ATTR_ENABLEIDDSC","Consente l'attributo ID in HTML. Questo è disabilitato di default dovuto al fatto che senza una corretta configurazione l'input dell'utente spezza la validazione di una pagina web specificando un ID che già si trova nel codice HTML. Se non ti dispiace buttare al vento la prudenza, attiva questa direttiva, ma ti consigliamo caldamente di considerare anche l'uso di una lista nera di ID (ID Blacklist).");
define("_MD_AM_PURIFIER_ATTR_IDPREFIX","Impostare il prefisso dell'attributo ID");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXDSC","Stringa che precederà l'ID. Se non hai idea di quale ID userai per le tue pagine ID, puoi optare semplicemente per aggiungere un prefisso a tutti gli attributi inseriti dagli utenti cosicchè questi saranno ancora utilizzabili, ma non entreranno in conflitto con l'ID core della pagina. Esempio: impostando la direttiva a 'user_' ne risulterà che se un utente inserisce 'foo' esso divenerà 'user_foo'. Sii sicuro di impostare a sì 'Consenti Attributo ID' prima di utilizzarlo.");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCAL","Consenti auto formattazioni personalizzate");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCALDSC","Il prefisso temporaneo per gli ID utilizzato in combinazione con l'attributo prefisso ID. Se avete necessità di consentire più contenuti utente su una pagina web, può essere necessario avere un prefisso separato che cambia con ogni iterazione. In questo modo, separando i contenuti visualizzati sulla stessa pagina inviati dagli utenti, non si sovrascrivono fra loro. I valori ideali sono identificatori univoci per il contenuto che rappresentano (vale a dire l'id della riga nel database). Assicurarsi di aggiungere un separatore (come un segno di sottolineatura) alla fine. Attenzione: questa direttiva non funzionerà a meno che l'attributo ID prefisso sia impostato su un valore non vuoto!");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLIST","Blacklist attributo ID");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLISTDSC","Array di IDs non consentite.");
// CSS Section
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANT","Consentire !important nei fogli di stile");
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANTDSC","Questo parametro determina se è possibile o meno utilizzare nel CSS utente il modificatore cascade !important. Se no, !important sarà eliminato.");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKY","Permetti stili CSS ingannevoli");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKYDSC","Questo parametro determina se permettere o meno delle proprietà e dei valori CSS \"ingannevoli\". Proprietà/valori CSS ingannevoli possono drasticamente modificare il layout della pagina ma non costituire direttamente un pericolo. Per esempio display:none; è considerata una proprietà ingannevole che sarà consentita solo se questo parametro sarà impostato a no.");
define("_MD_AM_PURIFIER_CSS_ALLOWPROP","Permetti proprietà CSS");
define("_MD_AM_PURIFIER_CSS_ALLOWPROPDSC","Se le impostazioni di HTML Purifier sono insoddisfacenti per le tue esigenze, è possibile sovrascriverle con il tuo elenco di tag da consentire. Si noti che questo metodo è sottrattivo: fa il suo lavoro togliendo il set di funzionalità ad HTML Purifier per cui non è possibile aggiungere un attributo che non è supportato in primo luogo da HTML Purifier. <br /> <br /> Avvertenza: se un'altra preferenza entra in conflitto con gli elementi qui impostati quella precedenza vincerà e non ne sarà tenuto conto.");
define("_MD_AM_PURIFIER_CSS_DEFREV","Revisione definizione CSS");
define("_MD_AM_PURIFIER_CSS_DEFREVDSC","Identificatore di revisione per la tua definizione personalizzata. Vedere revisione definizione HTML per i dettagli.");
define("_MD_AM_PURIFIER_CSS_MAXIMGLEN","Massima lunghezza CSS immagine");
define("_MD_AM_PURIFIER_CSS_MAXIMGLENDSC","Questo parametro imposta la lunghezza massima consentita del tags img, ovvero le propriet&agrave; di altezza e larghezza delle immagini. Sono ammesse solo unit&agrave; di misura assolute (in, pt, pc, mm, cm) e pixels (px). Questo &egrave; utile a prevenire un attacco di imagecrash, si disattiva con null a tuo rischio e pericolo. Questa direttiva &egrave; simile a Max lunghezza immagine HTML, ed entrambe devono essere modificate anche se ci sono sottili differenze fra i valori (il max CSS &egrave; un numero con una unit&agrave;).");
define("_MD_AM_PURIFIER_CSS_PROPRIETARY","Consenti CSS sicuri proprietari");
define("_MD_AM_PURIFIER_CSS_PROPRIETARYDSC","Se consentire o meno i CSS sicuri proprietari.");
// purifier config options
define("_MD_AM_PURIFIER_401T","HTML 4.01 Transitional");
define("_MD_AM_PURIFIER_401S","HTML 4.01 Strict");
define("_MD_AM_PURIFIER_X10T","XHTML 1.0 Transitional");
define("_MD_AM_PURIFIER_X10S","XHTML 1.0 Strict");
define("_MD_AM_PURIFIER_X11","XHTML 1.1");
define("_MD_AM_PURIFIER_WEGAME","WEGAME Movies");
define("_MD_AM_PURIFIER_VIMEO","Vimeo Movies");
define("_MD_AM_PURIFIER_LOCALMOVIE","Local Movies");
define("_MD_AM_PURIFIER_GOOGLEVID","Google Video");
define("_MD_AM_PURIFIER_LIVELEAK","LiveLeak Movies");

define("_MD_AM_UNABLECSSTIDY", "CSSTidy Plugin non &egrave; stato trovato. Sei pregato di copiare CSSTidy nella cartella plugins per essere sicuro della sua presenza.");

// Autotasks
if(!defined('_MD_AM_AUTOTASKS')){define('_MD_AM_AUTOTASKS', 'Auto Comandi');}
define("_MD_AM_AUTOTASKS_SYSTEM", "Processing system");
define("_MD_AM_AUTOTASKS_HELPER", "Applicazioni assistenti");
define("_MD_AM_AUTOTASKS_HELPER_PATH", "Percorso delle applicazioni assistenti");

define("_MD_AM_AUTOTASKS_SYSTEMDSC", "Quale sistema di comandi dovrebbe essere utilizzato per eseguire comandi?");
define("_MD_AM_AUTOTASKS_HELPERDSC", "Per ogni sistema oltre a 'internal', sei pregato di specificare un applicativo assistente. Tuttavia solo un applicativo sar&agrave; utilizzato, quindi sceglilo con cura.");
define("_MD_AM_AUTOTASKS_HELPER_PATHDSC", "Se il tuo applicativo assistente non &egrave; locato nel percorso default del sistema, devi specificare il percorso esatto.");
define("_MD_AM_AUTOTASKS_USER", "Utente di sistema");
define("_MD_AM_AUTOTASKS_USERDSC", "Utente di sistema da utilizzare per le esecuzioni di comandi.");

//source editedit
define("_MD_AM_SRCEDITOR_DEFAULT","Editor di default per il codice sorgente");
define("_MD_AM_SRCEDITOR_DEFAULT_DESC","Seleziona l'editor per le modifiche ai codici sorgente.");

// added in 1.2.1
define("_MD_AM_SMTPSECURE","Metodo sicurezza SMTP");
define("_MD_AM_SMTPSECUREDESC","Metodo di autenticazione utilizzato per SMTP. (default è ssl)");
define("_MD_AM_SMTPAUTHPORT","Porta SMTP");
define("_MD_AM_SMTPAUTHPORTDESC","La porta utilizzata da tuo server mail SMTP (default è 465)");

// added in 1.3
define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPAT","Abilita compatibilità Flash per IE");
define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPATDSC","Se true, HTML Purifier genererà un codice per la compatibilità con Internet Explorer per tutti gli oggetti. Questa opzione è molto raccomandata se abiliti HTML.SafeObject.");
define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRN","Permetti FullScreen negli oggetti Flash");
define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRNDSC","Se true, HTML Purifier permetterà di usare 'allowFullScreen' negli oggetti flash quando si usa HTML.SafeObject.");
define("_MD_AM_PURIFIER_CORE_NORMALNEWLINES","Normalizza Newlines");
define("_MD_AM_PURIFIER_CORE_NORMALNEWLINESDSC","Sceglie se normalizzare o meno le newlines al sistema operativo di default. Quando false, HTML Purifier tenterà di conservare i files con newline miste.");
define('_MD_AM_AUTHENTICATION_DSC', 'Gestisci le impostazioni di sicurezza correlate alla accessibilità. Queste impostazioni avranno effetto su come le utenze verranno amministrate.');
define('_MD_AM_AUTOTASKS_PREF_DSC', 'Preferenze per le funzioni automatiche.');
define('_MD_AM_CAPTCHA_DSC', 'Amministra le impostazioni usate da captcha nel tuo sito.');
define('_MD_AM_GENERAL_DSC', 'La pagina più importante per le informazioni basilari necessarie al sistema.');
define('_MD_AM_PURIFIER_DSC', 'HTMLPurifier è utilizzato per proteggere il tuo sito dagli attacchi più comuni.');
define('_MD_AM_MAILER_DSC', 'Configura come il tuo sito amministrerà le email.');
define('_MD_AM_METAFOOTER_DSC', 'Amministra le tue meta informazioni il piè di pagina del sito come pure le opzioni di crawler.');
define('_MD_AM_MULTILANGUAGE_DSC', 'Amministra le impostazioni multi-lingua del tuo sito.');
define('_MD_AM_PERSON_DSC', 'Personalizza il sistema con loghi personalizzati e altre impostazioni.');
define('_MD_AM_PLUGINS_DSC', 'Seleziona quali plugins sono usati e disponibili per essere usati nel tuo sito.');
define('_MD_AM_SEARCH_DSC', 'Gestisci come la funzione di ricerca opererà per i tuoi utenti.');
define('_MD_AM_USERSETTINGS_DSC', 'Gestisci come gli utenti si registrano al tuo sito, la lunghezza del nome utente e il formato password.');
define('_MD_AM_CENSOR_DSC', 'Gestisci le parole non permesse nel tuo sito.');
define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOM","Permetti filtri personalizzati");
define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOMDSC","Permetti filtri personalizzati?<br /><br />Se abilitata questa opzione permetterai l'utilizzo di filtri che sono contenuti in;<br />'libraries/htmlpurifier/standalone/HTMLPurifier/Filter'");

// added in 1.3.2
define("_MD_AM_PURIFIER_HTML_SAFEIFRAME","Enable Safe Iframes");
define("_MD_AM_PURIFIER_HTML_SAFEIFRAMEDSC","Whether or not to permit Iframes in documents, with a number of extra security features added to prevent script execution. You must add safe domains in Safe Iframes URLs before enabling!.");
define("_MD_AM_PURIFIER_URI_SAFEIFRAMEREGEXP","Safe Iframes URLs");
define("_MD_AM_PURIFIER_URI_SAFEIFRAMEREGEXPDSC","A list of URLs that you want to allow to show iframe content on your site. This will be matched against an iframe URI. This is a relatively inflexible scheme, but works well enough for the most common use-case of iframes: embedded video. <br />Letting the site owner explicitly allow sites keeps unknown sites from showing iframes on your site with content you cannot control.<br /><br />
    Here are some example values:<br /><br />

    http://www.youtube.com/embed/ - Allow YouTube videos<br />
    http://player.vimeo.com/video/ - Allow Vimeo videos<br />
    http://www.youtube.com/embed/|http://player.vimeo.com/video/ - Allow both<br /><br />HTML Safe Iframe must be enabled for this to work.");

// added in 1.3.3
define("_MD_AM_ENC_RIPEMD256","RIPEMD 256");
define("_MD_AM_ENC_RIPEMD320","RIPEMD 320");
define("_MD_AM_ENC_SNEFRU256","Snefru 256");
define("_MD_AM_ENC_GOST","Gost");