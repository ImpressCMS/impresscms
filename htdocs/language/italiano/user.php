<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
define("_US_NOTREGISTERED","Non sei iscritto? Clicca <a href=register.php>qui</a> per iscriverti.");
define("_US_LOSTPASSWORD","Hai dimenticato la password?");
define("_US_NOPROBLEM","Non ti preoccupare, inserisci l'indirizzo email che hai utilizzato durante l'iscrizione e riceverai al pi&ugrave; presto una nuova password.");
define("_US_YOUREMAIL","Il tuo indirizzo email: ");
define("_US_SENDPASSWORD","Invia password");
define("_US_LOGGEDOUT","Ti sei disconnesso");
define("_US_THANKYOUFORVISIT","La tua sessione &egrave; chiusa, ciao e a presto!");
define("_US_INCORRECTLOGIN","Login non riuscito");
define("_US_LOGGINGU","Ora sei un utente registrato, %s.");
define('_US_RESETPASSWORD','Ripristina la tua password');
define('_US_SUBRESETPASSWORD','Ripristina password');
define('_US_RESETPASSTITLE','La tua password &egrave; scaduta!');
define('_US_RESETPASSINFO','Sei pregato di completare il seguente modulo per ripristinare la tua password. Se la tua email, nome utente e attuale password coincidono con i dati contenuti nel database la tua password sar&agrave; cambiata istantaneamente e tu sarai in grado di fare subito il login!');
define('_US_PASSEXPIRED','La tua password &egrave; scaduta.<br />Sarai subito reindirizzato a un modulo dove sarai in grado di ripristinare la password.');
define('_US_SORRYUNAMENOTMATCHEMAIL','Il nome utente inserito non &egrave; associato a nessun indirizzo email memorizzato nel database!');
define('_US_PWDRESET','La tua password &egrave; stata ripristinata con successo!');
define('_US_SORRYINCORRECTPASS','Hai inserito la tua password in modo errato!');

// 2001-11-17 ADD
define("_US_NOACTTPADM","Questo utente &egrave; stato disattivato.<br> Per maggiori informazioni contatta l'amministratore di questo sito.");
define("_US_ACTKEYNOT","Chiave di attivazione errata!");
define("_US_ACONTACT","Il nome che hai scelto esiste gi&ugrave;!");
define("_US_ACTLOGIN","Il tuo account &egrave; stato attivato. Ora puoi fare il login ed accedere alle aree riservate del sito.");
define("_US_NOPERMISS","Spiacente, ma non hai il permesso di eseguire questa operazione!");
define("_US_SURETODEL","Sei sicuro di voler eliminare la tua iscrizione?");
define("_US_REMOVEINFO","Tutti i tuoi dati saranno eliminati.");
define("_US_BEENDELED","La tua iscrizione &egrave; stata cancellata.");
define('_US_REMEMBERME', 'Ricordami');

//%%%%%%		File Name register.php 		%%%%%
define("_US_USERREG","Iscrizione utente");
define("_US_EMAIL","Email");
define("_US_ALLOWVIEWEMAIL","Permetti agli altri utenti di vedere il tuo indirizzo email");
define("_US_WEBSITE","Sito Web");
define("_US_TIMEZONE","Fuso orario");
define("_US_AVATAR","Avatar");
define("_US_VERIFYPASS","Verifica password");
define("_US_SUBMIT","Invia");
define('_US_USERNAME','Nome utente');
define('_US_FINISH','Termina la iscrizione');
define('_US_REGISTERNG','Non puoi iscrivere un altro utente.');
define('_US_MAILOK','Consenti agli amministratori del sito e ai moderatori di inviarti occasionali newsletters?');
define('_US_DISCLAIMER','Condizioni di utilizzo del servizio');
define('_US_IAGREE','Accetto tutte le condizioni sopra riportate');
define('_US_UNEEDAGREE', 'Spiacente ma &egrave; necessario accettare le condizioni di utilizzo del servizio, per completare l\'iscrizione.');
define('_US_NOREGISTER','Spiacente ma al momento non &egrave; possibile accettare ulteriori nuove iscrizioni.');


// %s is username. This is a subject for email
define("_US_USERKEYFOR","Chiave di attivazione per l'utente %s");

define("_US_YOURREGISTERED","Ora sei un utente iscritto. Presto riceverai un email con i dati che hai inserito durante l'iscrizione, segui le istruzioni che riceverai per accedere al sito.");
define("_US_YOURREGMAILNG","Adesso sei iscritto come utente del sito. Purtroppo non &egrave; stato possibile inviarti l'email con il link per attivare la tua utenza a causa di un errore interno verificatosi sul nostro server di posta elettronica.<br /> Ti preghiamo di contattare il webmaster via email e segnalare il problema.");
define('_US_YOURREGISTERED2','Adesso sei un utente iscritto. Sei pregato di attendere l\'attivazione dell\'iscrizione da parte di un amministratore del sito. Riceverai un\'email di notifica una volta che il tuo account sar&agrave; stato attivato. Questa operazione potrebbe richiedere del tempo, cerca di essere paziente.');

// %s is your site name
define("_US_NEWUSERREGAT","Nuovo utente su %s");
// %s is a username
define("_US_HASJUSTREG","%s si &egrave; appena iscritto!");

define("_US_INVALIDMAIL","ERRORE: L'indirizzo email non &egrave; valido");
define("_US_EMAILNOSPACES","ERRORE: L'indirizzo email non pu&ograve; contenere spazi.");
define("_US_INVALIDNICKNAME","ERRORE: Nick non valido");
define('_US_NICKNAMETOOLONG','Nick troppo lungo. Il nick non deve superare i %s caratteri.');
define('_US_NICKNAMETOOSHORT','Nick troppo corto. Il nick deve contenere almeno %s caratteri.');
define('_US_NAMERESERVED','ERRORE: Questo &egrave; un nick riservato.');
define('_US_NICKNAMENOSPACES','Non possono esserci spazi nel nick.');
define('_US_NICKNAMETAKEN','ERRORE: Nick gi&agrave; utilizzato.');
define('_US_LOGINNAMETAKEN','ERRORE: Nome Utente gi&agrave; utilizzato.');
define('_US_EMAILTAKEN','ERRORE: Indirizzo email gi&agrave; utilizzato.');
define('_US_ENTERPWD','ERRORE: Devi inserire una password.');
define('_US_SORRYNOTFOUND','Spiacenti, ma non &egrave; stata trovata alcuna informazione sull\'utente.');


define('_US_USERINVITE', 'Iscrizione al sito su invito');
define('_US_INVITENONE','ERRORE: la registrazione al sito &egrave; solo su invito.');
define('_US_INVITEINVALID','ERRORE: Errato codice di invito.');
define('_US_INVITEEXPIRED','ERRORE: Questo codice di invito &egrave; gi&agrave; stato utilizzato o &egrave; scaduto.');

define('_US_INVITEBYMEMBER','Solo un utente registrato pu&ograve; invitare nuovi utenti. Sei pregato di richiedere un invito email da un utente esistente.');
define('_US_INVITEMAILERR','Non siamo in grado di inviare la mail contenente il link di registrazione a causa di un errore interno dovuto al nostro server. Siamo spiacenti per l\'inconveniente, ti preghiamo di provare ancora e, se il problema persiste, di contattare l\'amministratore del sito con una email notificando la situazione. <br />');
define('_US_INVITEDBERR','Non siamo in grado di procedere nella tua richiesta di registrazione a causa di un errore interno. Siamo spiacenti, ti preghiamo di provare ancora e se il problema persiste, di contattare l\'amministratore del sito con una email notificando la situazione. <br />');
define('_US_INVITESENT','Una email contenente il link di registrazione &egrave; stata inviata all\'account email indicato. Si prega di seguire le istruzioni contenute per registrare la tua iscrizione. Ci&ograve; impiegher&agrave; qualche minuto di pazienza nell\'attesa.');
// %s is your site name
define('_US_INVITEREGLINK','Invito alla iscrizione da parte di %s');


// %s is your site name
define("_US_NEWPWDREQ","Nuova password richiesta su %s");
define('_US_YOURACCOUNT', 'Il tuo account su %s');

define("_US_MAILPWDNG","mail_password: aggiornamento non riuscito. Contatta l'amministratore.");
define('_US_RESETPWDNG','reset_password: aggiornamento della password non riuscito. Contatta l\'amministratore');

define('_US_RESETPWDREQ','Richiesta di ripristino della password su %s');
define('_US_MAILRESETPWDNG','reset_password: aggiornamento dati utente non riuscito. Contatta l\'amministratore');
define('_US_NEWPASSWORD','Nuova password');
define('_US_YOURUSERNAME','Il tuo nome utente');
define('_US_CURRENTPASS','La tua attuale password');
define('_US_BADPWD','Password scadente, la password non pu&ograve; contenere il nome utente.');

// %s is a username
define("_US_PWDMAILED","La nuova password per l'utente %s &egrave; stata inviata.");
define("_US_CONFMAIL","L'email di conferma per l'utente %s &egrave; stata inviata.");
define('_US_ACTVMAILNG', 'L\'invio dell\'email di notifica all\'utente %s &egrave; fallito!');
define('_US_ACTVMAILOK', 'Email di notifica per l\'utente %s inviata con successo!');

//%%%%%%		File Name userinfo.php 		%%%%%
define("_US_SELECTNG","Nessun utente selezionato! Per favore, torna indietro e riprova.");
define('_US_PM','PM');
define('_US_ICQ','ICQ');
define('_US_AIM','AIM');
define('_US_YIM','YIM');
define('_US_MSNM','MSN');
define("_US_LOCATION","Localit&agrave;");
define("_US_OCCUPATION","Occupazione");
define("_US_INTEREST","Interessi");
define("_US_SIGNATURE","Firma");
define("_US_EXTRAINFO","Ulteriori informazioni");
define('_US_EDITPROFILE','Modifica profilo');
define('_US_LOGOUT','Logout');
define("_US_INBOX","In arrivo");
define("_US_MEMBERSINCE","Iscritto il");
define("_US_RANK","Livello");
define("_US_POSTS","Messaggi");
define("_US_LASTLOGIN","Ultimo login");
define("_US_ALLABOUT","Profilo di %s");
define("_US_STATISTICS","Statistiche");
define('_US_MYINFO','I miei dati');
define('_US_BASICINFO','Dati di base');
define('_US_MOREABOUT','Altro su di me');
define('_US_SHOWALL','Mostra tutto');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Profilo');
define('_US_REALNAME','Nome');
define('_US_SHOWSIG','Aggiungi sempre la mia firma');
define('_US_CDISPLAYMODE','Visualizzazione dei commenti');
define('_US_CSORTORDER','Ordine di visualizzazione dei commenti');
define('_US_PASSWORD','Password');
define("_US_TYPEPASSTWICE","(scrivere la nuova password due volte nel caso si desideri cambiarla)");
define("_US_SAVECHANGES","Salva le modifiche");
define("_US_NOEDITRIGHT","Spiacenti, ma non hai i privilegi per modificare i dati di questo utente.");
define("_US_PASSNOTSAME","Le due password sono diverse. E' necessario che siano identiche!");
define("_US_PWDTOOSHORT","Spiacenti, ma la tua password deve essere lunga almeno <b>%s</b> caratteri.");
define('_US_PROFUPDATED','Il tuo profilo &egrave; stato aggiornato con successo!');
define('_US_USECOOKIE','Scrivi il mio nick in un cookie per il periodo di un anno');
define('_US_NO','No');
define('_US_DELACCOUNT','Elimina l\'account');
define('_US_MYAVATAR', 'Il mio avatar');
define('_US_UPLOADMYAVATAR', 'Trasferisci un avatar personale sul server');
define('_US_MAXPIXEL','Numero massimo di pixel');
define('_US_MAXIMGSZ','Dimensione massima dell\'immagine (in byte)');
define('_US_SELFILE','Scegli il file');
define('_US_OLDDELETED','Il tuo vecchio avatar sar&agrave; cancellato dal server!');
define('_US_CHOOSEAVT', 'Scegli dalla lista uno degli avatar disponibili.');
define('_US_SELECT_THEME', 'Tema grafico selezionato');
define('_US_SELECT_LANG', 'Lingua di Default');

define('_US_PRESSLOGIN', 'Premere il pulsante seguente per effettuare il login');

define('_US_ADMINNO', 'Gli utenti del gruppo dei Webmaster non possono essere eliminati');
define('_US_GROUPS', 'Gruppo/i dell\'utente');

define('_US_YOURREGISTRATION', 'La tua registrazione su %s');
define('_US_WELCOMEMSGFAILED', 'Errore nell\'invio della email di benvenuto.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Notifica all\'amministrazione di un nuovo utente fallita.');
define('_US_REGFORM_NOJAVASCRIPT', 'Per il login al sito &egrave; necessario che il tuo browser abbia i javascript abilitati.');
define('_US_REGFORM_WARNING', 'Per iscriverti come utente al sito &egrave; necessaria una password sicura. Prova a crearne una mescolando caratteri (maiuscoli e minuscoli), numeri e simboli, la pi&ugrave; complessa che sia ancora possibile da ricordare.');
define('_US_CHANGE_PASSWORD', 'Cambia Password?');
define('_US_POSTSNOTENOUGH','Spiacente, per inviare il tuo avatar personalizzato devi avere almeno <b>%s</b> posts.');
define('_US_UNCHOOSEAVT', 'Finch&eacute; non raggiungi questo numero di posts non potrai scegliere un avatar da questa lista sottostante.');

// openid
define('_US_OPENID_NOPERM', 'Nessun permesso.');
define('_US_OPENID_FORM_CAPTION', 'OpenID');
define('_US_OPENID_FORM_DSC', '');
define('_US_OPENID_EXISTING_USER', 'Utente esistente');
define('_US_OPENID_EXISTING_USER_LOGIN_BELOW', 'Se tu sei un utente gi&agrave; iscritto, fai il login inserendo qui sotto il tuo nome utente e password per associare la tua iscrizione con questo OpenID.');
define('_US_OPENID_NOM_MEMBER', 'Ancora nessun account?');
define('_US_OPENID_NON_MEMBER_DSC', 'Se non ha ancora nessun account per questo sito, sei pregato di inserire il nome utente che desideri utilizzare e creeremo una utenza associatata con il tuo OpenID.');
define('_US_OPENID_YOUR', 'Il tuo OpenID');
define('_US_OPENID_LINKED_AUTH_FAILED', 'Il nome utente e la password che hai inserito non corrisponde a nessun utente valido. Sei pregato di provare ancora.');
define('_US_OPENID_LINKED_AUTH_NOT_ACTIVATED', 'L\'utenza con la quale stai facendo login non &egrave; stata attivata. Sei pregato di attivare il tuo account e poi provare ancora.');
define('_US_OPENID_LINKED_AUTH_CANNOT_SAVE', 'Spiacenti, si &egrave; verificato un errore. Non &egrave; stato possibile aggiornare questo utente con l\'autenticazione OpenID.');
define('_US_OPENID_NEW_USER_UNAME_TOO_SHORT', 'Il nome utente che hai inserito &egrave; troppo breve. Sei pregato di provare ancora.');
define('_US_OPENID_NEW_USER_UNAME_EXISTS', 'Il nome utente che tu hai inserito &egrave; gi&agrave; in uso. Sei pregato di provare ancora.');
define('_US_OPENID_NEW_USER_CANNOT_INSERT', 'Spiacente, si &egrave; verificato un errore e non &egrave; stato possibile creare un nuovo utente. Sei pregato di provare ancora.');
define('_US_OPENID_NEW_USER_CANNOT_INSERT_INGROUP', 'Spiacente, si &egrave; verificato un errore. Non &egrave; possibile aggiungere il nuovo utente al giusto gruppo. Sei pregato di contattare l\'amministratore del sito.');
define('_US_OPENID_NEW_USER_AUTH_NOT_ACTIVATED', 'Il nuovo utente creato non &egrave; stato ancora attivato.');
define('_US_OPENID_NEW_USER_CREATED', 'Un nuovo utente &egrave; stato creato con il nome utente %s. Login automatico in corso ...');
define('_US_OPENID_LINKED_DONE', 'Il tuo OpenID &egrave; stato linkato con l\'utente %S. Login in corso ...');
define('_US_ALREADY_LOGED_IN', 'Tu sei un utente registrato e, siamo spiacenti per questo, non &egrave; possibile registrarti finch&eacute; sei loggato.');
define('_US_ALLOWVIEWEMAILOPENID','Permetto agli altri utenti di vedere il mio OpenID');
define('_US_SERVER_PROBLEM_OCCURRED','Si è verificato un problema nel controllo della lista degli spammer!');
define('_US_INVALIDIP','ERROR: A questo indirizzo IP non è consentito registrarsi');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Nome login");
define('_US_OLD_PASSWORD', "Vecchia password");
define('_US_NICKNAME','Nome visualizzato');
define('_US_MULTLOGIN', 'Non è possibile fare il login sul sito!<br />
        <p align="left" style="color:red;">
        Possibili cause:<br />
         - Sei già registrato nel sito.<br />
         - Qualcun altro è registrato nel sito usando il tuo nome utente e password.<br />
         - Hai lasciato il sito o chiuso la finestra del browser senza cliccare il bottone "Esci".<br />
        </p>
        Attendi alcuni minuti e prova ancora. Se il problema persiste contatta l\'amministratore del sito.');
define("_US_OPENID_LOGIN", "Login con il tuo OpenID");
define("_US_OPENID_URL", "Il tuo OpenID URL:");
define("_US_OPENID_NORMAL_LOGIN", "Usa il tuo normale login");

