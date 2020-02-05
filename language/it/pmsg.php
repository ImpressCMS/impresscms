<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","I tuoi messaggi privati sono stati eliminati con successo!");
define("_PM_PRIVATEMESSAGE","Messaggi privati");
define("_PM_INBOX","In arrivo"); // could use _US_INBOX
define("_PM_FROM","Da");
define("_PM_YOUDONTHAVE","Non hai nessun messaggio privato.");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Inviato il: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Messaggio precedente");
define("_PM_NEXT","Messaggio successivo");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Spiacenti, non sei un utente registrato.");
define("_PM_REGISTERNOW","Registrati adesso!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","L'utente selezionato non esiste.");
define("_PM_PLZTRYAGAIN","Controlla il nome e prova ancora.");
define("_PM_MESSAGEPOSTED","Il tuo messaggio privato &egrave; stato inviato con successo!");
define("_PM_CLICKHERE","Cliccando qui, &egrave; possibile visualizzare i propri messaggi privati.");
define("_PM_ORCLOSEWINDOW","Oppure clicca qui per chiudere questa finestra.");
define("_PM_USERWROTE","%s ha scritto:");
define("_PM_TO","A: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Messaggio: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Pulisci");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Non letto");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Si prega di registrarsi, se si desidera inviare messaggi privati agli utenti registrati su questo sito!");

define("_PM_ONLINE", "Online");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Notifica per ricezione di messaggio privato");
