<?php
/**
* Installer main english strings declaration file.
* @copyright	The ImpressCMS project http://www.impresscms.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU public license
* @author       Skalpa Keo <skalpa@xoops.org>
* @author       Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
* @since        1.0
* @version		$Id: install.php 607 2006-07-03 00:23:48Z skalpa $
* @package 		installer
*/

define( "SHOW_HIDE_HELP", "Mostra/nascondi il testo di aiuto" );

define("ALTERNATE_LANGUAGE_MSG","Trovare una lingua alternativa sul sito ImpressCMS.");
define("ALTERNATE_LANGUAGE_LNK_MSG", "Selezionare una lingua che non si trova nella lista.");
define("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");
// Configuration check page
define( "SERVER_API", "API Server" );
define( "PHP_EXTENSION", "Estensioni %s" );
define( "CHAR_ENCODING", "Codifica carattere" );
define( "XML_PARSING", "XML parsing" );
define( "OPEN_ID", "OpenID" );
define( "REQUIREMENTS", "Requisiti" );
define( "_PHP_VERSION", "Versione PHP" );
define( "RECOMMENDED_SETTINGS", "Impostazioni raccomandate" );
define( "RECOMMENDED_EXTENSIONS", "Estensioni raccomandate" );
define( "SETTING_NAME", "Nomi impostazione" );
define( "RECOMMENDED", "Raccomandate" );
define( "CURRENT", "Attuale" );
define( "RECOMMENDED_EXTENSIONS_MSG", "Queste estensioni non sono richieste per l'uso normale, ma possono essere necessarie per sfruttare alcune specifiche funzioni (come il multilingua e il supporto RSS). Tuttavia &#232; raccomandato di averle installate." );

define( "NONE", "Non presente" );
define( "SUCCESS", "OK" );
define( "WARNING", "Avviso" );
define( "FAILED", "Fallito" );



// Titles (main and pages)
define( "XOOPS_INSTALL_WIZARD", " %s - Wizard per l'installazione" );
define( "INSTALL_STEP", "Livello" );
define( "INSTALL_H3_STEPS", "Livelli" );
define( "INSTALL_OUTOF", " di " );
define( "INSTALL_COPYRIGHT", "Copyright &copy; 2007 <a href=\"http://www.impresscms.org\">The ImpressCMS Project</a>" );

define( "LANGUAGE_SELECTION", "Selezione lingua" );
define( "LANGUAGE_SELECTION_TITLE", "Scegli la tua lingua");		// L128
define( "INTRODUCTION", "Introduzione" );
define( "INTRODUCTION_TITLE", "Benvenuto nell'assistente di installazione di ImpressCMS" );		// L0
define( "CONFIGURATION_CHECK", "Controllo configurazione" );
define( "CONFIGURATION_CHECK_TITLE", "Controllo della configurazione del tuo server" );
define( "PATHS_SETTINGS", "Impostazioni percorso" );
define( "PATHS_SETTINGS_TITLE", "Impostazioni percorso" );
define( "DATABASE_CONNECTION", "Connessione Database" );
define( "DATABASE_CONNECTION_TITLE", "Connessione Database" );
define( "DATABASE_CONFIG", "Configurazione Database" );
define( "DATABASE_CONFIG_TITLE", "Configurazione Database" );
define( "CONFIG_SAVE", "Salva configurazione" );
define( "CONFIG_SAVE_TITLE", "Salvataggio configurazione del sistema in corso" );
define( "TABLES_CREATION", "Creazione delle tabelle del database" );
define( "TABLES_CREATION_TITLE", "Creazione tabelle" );
define( "INITIAL_SETTINGS", "Impostazioni iniziali" );
define( "INITIAL_SETTINGS_TITLE", "Sei pregato di inserire le impostazioni iniziali" );
define( "DATA_INSERTION", "Inserimento dati" );
define( "DATA_INSERTION_TITLE", "Salvataggio delle tue impostazioni in corso" );
define( "WELCOME", "Benvenuto/a" );
define( "NO_PHP5", "PHP 5 non disponibile" );
define( "WELCOME_TITLE", "Installazione di ImpressCMS completata" );		// L0
define( "MODULES_INSTALL", "Installazione moduli" );
define( "MODULES_INSTALL_TITLE", "Installazione di moduli " );
define( "NO_PHP5_TITLE", "PHP 5 non disponibile" );
define( "NO_PHP5_CONTENT","PHP 5 &egrave; indispensabile per il buon funzionamento di ImpressCMS - La tua insallazione non pu&ograve; continuare. Sei pregato di chiedere al tuo amministratore del serveer di aggiornare l'ambiente a PHP5 prima di provare a installare ancora. Per maggiori informazioni puoi leggere, in inglese, <a href='http://community.impresscms.org/modules/smartsection/item.php?itemid=122' >ImpressCMS su PHP5 </a>.");
define( "SAFE_MODE", "Safe Mode On" );
define( "SAFE_MODE_TITLE", "Safe Mode On" );
define( "SAFE_MODE_CONTENT", "ImpressCMS ha trovato che PHP sta usando l'impostazione Safe Mode. A causa di ci&ograve; la tua installazione non pu&ograve; continuare. Sei pregato di chiedere al tuo hosting di cambiare questa impostazione a OFF prima di riprovare a installare." );

// Settings (labels and help text)
define( "XOOPS_ROOT_PATH_LABEL", "Percorso fisico della cartella documenti ImpressCMS" ); // L55
define( "XOOPS_ROOT_PATH_HELP", "Questo &#232; il percorso fisico della cartella documenti dove si trova ImpressCMS, la radice del server web dell'applicazione ImpressCMS" ); // L59
define( "_INSTALL_TRUST_PATH_HELP", "Questa &#232; la posizione fisica del percorso sicuro (trust path) di ImpressCMS. Il percorso sicuro &#232; una cartella dove ImpressCMS e i suoi moduli posizioneranno dati sensibili ed informazioni per una maggiore sicurezza. &#200; raccomandato che questa cartella si trovi al di fuori dalla cartella pubblica del server web, in modo che non sia accessibile dal browser.  Se la cartella non esiste, ImpressCMS prover&agrave; a crearla. Se non fosse possibile, ci sar&agrave; bisogno che la si crei appositamente o via FTP o dal pannello di amministrazione del tuo hosting e gli si attribuisca i  permessi di lettura e scrittura (chmod 777).<br /><br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path'>Clicca qui</a> per saperne di pi&ugrave; sul percorso sicuro (Trust path)." ); // L59

define( "XOOPS_URL_LABEL", "Indirizzo del sito Web (URL)" ); // L56
define( "XOOPS_URL_HELP", "Principale indirizzo (URL) che sar&agrave; utilizzato per accedere al vostro ImpressCMS" ); // L58

define( "LEGEND_CONNECTION", "Connessione al server" );
define( "LEGEND_DATABASE", "Database" ); // L51

define( "DB_HOST_LABEL", "Nome host del server" );	// L27
define( "DB_HOST_HELP",  "Nome host o numero IP del server database. Se non siete sicuri, <em>localhost</em> funziona in molti casi"); // L67
define( "DB_USER_LABEL", "Nome Utente" );	// L28
define( "DB_USER_HELP",  "Nome dell'utente che sar&agrave; utilizzato per la connessione al server del database"); // L65
define( "DB_PASS_LABEL", "Password" );	// L52
define( "DB_PASS_HELP",  "Password della tua utenza database"); // L68
define( "DB_NAME_LABEL", "Nome database" );	// L29
define( "DB_NAME_HELP",  "Il nome del database sull'host. Se non esiste questo installatore prover&agrave; a creare uno"); // L64
define( "DB_CHARSET_LABEL", "Set di caratteri del Database, si raccomanda CALDAMENTE di usare UTF-8 come default." );
define( "DB_CHARSET_HELP",  "MySQL include un supporto al set di carattere che ti permette di immagazzinare dati usando una variet&agrave; di sets di caratteri e mettendo in atto comparazioni in accordo con una variet&agrave; di collazioni.");
define( "DB_COLLATION_LABEL", "Database collation" );
define( "DB_COLLATION_HELP",  "Una collazione &egrave; un set di regole per la comparazione di caratteri in un set di caratteri.");
define( "DB_PREFIX_LABEL", "Prefisso tabella" );	// L30
define( "DB_PREFIX_HELP",  "Questo prefisso sar&agrave; aggiunto a tutte le nuove tabelle create nel database per evitare un conflitto di nomi. Se non sei sicuro, non cambiare nulla."); // L63
define( "DB_PCONNECT_LABEL", "Usa connessioni persistenti" );	// L54

define( "DB_SALT_LABEL", "Password Salt Key" );	// L98
define( "DB_SALT_HELP",  "Questa salt key sar&agrave; apposta alla password nella funzione icms_encryptPass() e usata per crere un'unica password sicura. Non cambiare questa chiave se il tuo sito &egrave; gi&agrave; attivo, facendo cos&igrave; tutte le password gi&agrave; esistenti saranno cambiate e rese non valide. Se tu non sei sicuro lascia il dato di default."); // L97

define( "LEGEND_ADMIN_ACCOUNT", "Dati utenza amministratore" );
define( "ADMIN_LOGIN_LABEL", "Nome utente per il login amministratore" ); // L37
define( "ADMIN_EMAIL_LABEL", "E-mail amministratore" ); // L38
define( "ADMIN_PASS_LABEL", "Password amministratore" ); // L39
define( "ADMIN_CONFIRMPASS_LABEL", "Conferma password" ); // L74
define( "ADMIN_SALT_LABEL", "Password Salt Key" ); // L99

// Buttons
define( "BUTTON_PREVIOUS", "Indietro" ); // L42
define( "BUTTON_NEXT", "Avanti" ); // L47
define( "BUTTON_FINISH", "Finisci" );
define( "BUTTON_REFRESH", "Ricarica" );
define( "BUTTON_SHOW_SITE", "Entra nel tuo sito" );

// Messages
define( "XOOPS_FOUND", "%s trovato" );
define( "CHECKING_PERMISSIONS", "Sto controllando i permessi di file e cartelle" ); // L82
define( "IS_NOT_WRITABLE", "%s NON &#232; scrivibile." ); // L83
define( "IS_WRITABLE", "%s &#232; scrivibile." ); // L84
define( "ALL_PERM_OK", "Tutti i premessi sono corretti." );

define( "READY_CREATE_TABLES", "Nessuna tabella di ImpressCMS &#232; stata trovata.<br />Il wizard di installazione &#232; pronto per creare le tabelle di sistema di ImpressCMS.<br />Premi <em>Avanti</em> per procedere." );
define( "XOOPS_TABLES_FOUND", "Le tabelle di sistema di ImpressCMS gi&agrave; esistono nel tuo database.<br />Premi <em>Avanti</em> per procedere." ); // L131
define( "READY_INSERT_DATA", "Il wizard di installazione &#232; pronto per inserire i dati iniziali nel tuo database." );
define( "READY_SAVE_MAINFILE", "Il wizard di installazione &#232; ora pronto a salvare le impostazioni selezionate nel <em>mainfile.php</em>.<br />Premi <em>Avanti</em> per procedere." );
define( "DATA_ALREADY_INSERTED", "I dati ImpressCMS sono già inseriti nel database. Nessun ulteriore dato sarà inserito da questa azione.<br />Premi <em>Avanti</em> per andare al prossimo passo." );


// %s is database name
define( "DATABASE_CREATED", "Il database %s &#232; stato creato!" ); // L43
// %s is table name
define( "TABLE_NOT_CREATED", "Non &#232; stato possibile creare la tabella %s" ); // L118
define( "TABLE_CREATED", "Tabella %s creata." ); // L45
define( "ROWS_INSERTED", "%d righe inserite nella tabella %s." ); // L119
define( "ROWS_FAILED", "Fallito inserimento di %d righe nella tabella %s." ); // L120
define( "TABLE_ALTERED", "Tabella %s aggiornata."); // L133
define( "TABLE_NOT_ALTERED", "Fallito l'aggiornamento della tabella %s."); // L134
define( "TABLE_DROPPED", "Tabella %s eliminata."); // L163
define( "TABLE_NOT_DROPPED", "Fallita concellazione della tabella %s."); // L164

// Error messages
define( "ERR_COULD_NOT_ACCESS", "Non &#232; possibile accedere alla cartella specificata. Verificare prego che esista e che sia leggibile dal server." );
define( "ERR_NO_XOOPS_FOUND", "Non si &#232; potuta trovare nessuna installazione ImpressCMS nella cartella specificata." );
define( "ERR_INVALID_EMAIL", "Email non valida" ); // L73
define( "ERR_REQUIRED", "Sei pregato di inserire tutte le informazioni richieste." ); // L41
define( "ERR_PASSWORD_MATCH", "Le due passwords non sono uguali" );
define( "ERR_NEED_WRITE_ACCESS", "Il server deve avere accesso di scrittura sui seguenti files and cartelle <br />(ad es. <em>chmod 777 nome _cartella</em> su un UNIX/LINUX server)" ); // L72
define( "ERR_NO_DATABASE", "Non &#232; possibile creare un database. Contattare l'amministratore del server per chiarimenti." ); // L31
define( "ERR_NO_DBCONNECTION", "Non &#232; possibile connettersi al server database." ); // L106
define( "ERR_WRITING_CONSTANT", "Tentativo di impostare la costante <b>%s</b> fallito." ); // L122

define( "ERR_COPY_MAINFILE", "Non &#232; stato possibile copiare il file della distribuzione in mainfile.php" );
define( "ERR_WRITE_MAINFILE", "Non &#232; stato possibile scrivere in mainfile.php. Prego eseguire un controllo sui permessi di file e provare ancora.");
define( "ERR_READ_MAINFILE", "Non &#232; stato possibile aprire il mainfile.php per la lettura" );

define( "ERR_WRITE_SDATA", "Non &#232; stato possibile scrivere in sdata.php. Si prega di controllare i permessi del file e provare ancora.");
define( "ERR_READ_SDATA", "Non &#232; stato possibile aprire sdata.php in lettura" );
define( "ERR_INVALID_DBCHARSET", "Il set di carateri '%s' non &egrave; supportato." );
define( "ERR_INVALID_DBCOLLATION", "La collation '%s' non &egrave; supportata." );
define( "ERR_CHARSET_NOT_SET", "Set di caratteri di default non &egrave; adatto per il database ImpressCMS." );


//
define('_INSTALL_SELECT_MODS_INTRO', 'Nella lista sottostante si prega di selezionare i moduli che si desidera installare su questo sito.
Tutti i moduli installati saranno accessibili dal gruppo amministratori e dagli utenti registrati.
In caso vogliate dare ai visitatori anonimi del sito (utenti non registrati o utenti che non hanno ancora fatto il login) accesso a uno o pi&ugrave; di questi moduli installati si prega di abilitare il gruppo anonimi dal pannello amministrativo una volta completata questa installazione. <br /><br />Per maggiori informazioni riguardanti il gruppo amministrativo si prega di visitare il <a href="http://wiki.impresscms.org/index.php?title=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Seleziona i moduli da installare');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Seleziona i moduli visibili ai visitatori');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Il modulo %s non può essere installato.");
define("_INSTALL_ERRORS", 'Errori');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Il module %s &#232; stato già installato");
define("_INSTALL_FAILED_TO_EXECUTE", "Esecuzione fallita ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Eseguito correttamente");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Il modulo %s stato installato con successo.");
define("_INSTALL_MOD_INSTALL_FAILED", "Il wizard non riesce a installare il modulo %s.");
define("_INSTALL_NO_PLUS_MOD", "Nessun modulo &#232; stato selezionato per l'installazione. Si prega di continuare l'installazione cliccando Avanti.");
define("_INSTALL_INSTALLING", "Installazione del modulo %s");

define("_INSTALL_TRUST_PATH", "Percorso sicuro");
define("_INSTALL_TRUST_PATH_LABEL", "Percorso sicuro fisico di ImpressCMS");
define("_INSTALL_WEB_LOCATIONS", "Indirizzo Web");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Indirizzo Web");

define("_INSTALL_TRUST_PATH_FOUND", "Percorso sicuro trovato.");
define("_INSTALL_ERR_NO_TRUST_PATH_FOUND", "Percorso sicuro non trovato.");

define("_INSTALL_COULD_NOT_INSERT", "Il wizard di installazione non &#232; stato in grado di installare il database del modulo %s.");
define("_INSTALL_CHARSET","utf-8");

define("_INSTALL_PHYSICAL_PATH","Percorso fisico");

define("TRUST_PATH_VALIDATE","Qui sopra &egrave; stato creato per te un nome per la cartella del percorso sicuro (Trust path). Se desideri utilizzare un nome differente allora rimpiazza l'indirizzo con un nome, e/o una cartella, di tua scelta. <br /><br />Clicka poi sul bottone Crea Trust Path.");
define("TRUST_PATH_NEED_CREATED_MANUALLY","Non &#232; stato possibile creare la cartella di percorso sicuro. Si prega di crearla manualmente e clickare sul tasto Ricarica.");
define("BUTTON_CREATE_TUST_PATH","Crea percorso sicuro");
define("TRUST_PATH_SUCCESSFULLY_CREATED", "Il percorso sicuro &#232; stato creato con successo.");

// welcome custom blocks
define("WELCOME_WEBMASTER","Benvenuto Webmaster !");
define("WELCOME_ANONYMOUS","Benvenuto in un sito 'powered by ImpressCMS'!");
define("_MD_AM_MULTLOGINMSG_TXT",'Non &egrave; stato possibile fare login al sito!! <br />
        <p align="left" style="color:red;">
        Possibili cause:<br />
         - Sei gi&agrave; loggato al sito.<br />
         - Qualcun altro &egrave; loggato nel sito e usa il tuo nome utente e password.<br />
         - Hai lasciato il sito o hai chiuso la finestra del browser senza clickare il bottone del logout.<br />
        </p>
        Attendi qualche minuto e prova pi&ugrave; tardi. Se il problema continua a persistere contatta un amministratore del sito.');
define("_MD_AM_RSSLOCALLINK_DESC",'http://www.impresscms.it/modules/smartsection/backend.php'); //Link to the rrs of local support site
define("_INSTALL_LOCAL_SITE",'http://www.impresscms.it/'); //Link to local support site
define("_LOCAOL_STNAME",'ImpressCMS'); //Link to local support site
define("_LOCAL_SLOCGAN",'Make a lasting impression'); //Link to local support site
define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.it/\" rel=\"external\">The ImpressCMS Project</a>'); //footer Link to local support site
define("_LOCAL_SENSORTXT",'#OOPS#'); //Add local translation
define("_ADM_USE_RTL","0"); // turn this to 1 if your language is right to left
define("_DEF_LANG_TAGS",'en,fr'); //Add local translation
define("_DEF_LANG_NAMES",'english,french'); //Add local translation
define("_LOCAL_LANG_NAMES",'English,Français'); //Add local translation
define("_EXT_DATE_FUNC","0"); // change 0 to 1 if this language has an extended date function

######################## Added in 1.2 ###################################
define( "ADMIN_DISPLAY_LABEL", "Nome amministratore visualizzato" ); // L37
define('_CORE_PASSLEVEL1','Troppo breve');
define('_CORE_PASSLEVEL2','Debole');
define('_CORE_PASSLEVEL3','Buona');
define('_CORE_PASSLEVEL4','Forte');
define('DB_PCONNECT_HELP', "Le connessioni persistenti sono utili con connessioni internet lente. Non sono generalmente richieste per la maggior parte delle installazioni. Default è 'NO'. Scegli 'NO' se non sei sicuro"); // L69
define( "DB_PCONNECT_HELPS",  "Le connessioni persistenti sono utili con connessioni internet lente. Non sono generalmente richieste per la maggior parte delle installazioni."); // L69

