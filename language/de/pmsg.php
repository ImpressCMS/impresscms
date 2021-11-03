<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Ihre Nachricht(en) wurden gelöscht");
define("_PM_PRIVATEMESSAGE","Private Nachrichten");
define("_PM_INBOX","Postfach"); // could use _US_INBOX
define("_PM_FROM","Von");
define("_PM_YOUDONTHAVE","Sie haben keine privaten Nachrichten");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Gesendet: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Vorherige Nachricht");
define("_PM_NEXT","Nächste Nachricht");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Entschuldigung! Sie sind kein registrierter Benutzer.");
define("_PM_REGISTERNOW","Registrieren Sie jetzt!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","Der gewählte Benutzer existiert nicht in der Datenbank.");
define("_PM_PLZTRYAGAIN","Bitte überprüfen Sie den Namen und versuchen Sie es erneut.");
define("_PM_MESSAGEPOSTED","Ihre Nachricht wurde veröffentlicht");
define("_PM_CLICKHERE","Sie können hier klicken, um Ihre privaten Nachrichten anzusehen");
define("_PM_ORCLOSEWINDOW","Oder klicken Sie hier, um dieses Fenster zu schließen.");
define("_PM_USERWROTE","%s schrieb:");
define("_PM_TO","An: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Nachricht: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Leeren");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Nicht gelesen");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Bitte registrieren Sie sich zuerst um private Nachrichten zu senden!");

define("_PM_ONLINE", "Online");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Private Nachricht Benachrichtigung");
