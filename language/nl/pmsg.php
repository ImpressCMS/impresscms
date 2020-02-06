<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Bericht(en) is(zijn) verwijderd");
define("_PM_PRIVATEMESSAGE","Privéberichten");
define("_PM_INBOX","Postvak in"); // could use _US_INBOX
define("_PM_FROM","Van");
define("_PM_YOUDONTHAVE","Er zijn geen privéberichten");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Verzonden: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Vorig bericht");
define("_PM_NEXT","Volgend bericht");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Sorry! U bent geen geregistreerde gebruiker.");
define("_PM_REGISTERNOW","Registreer u nu!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","De geselecteerde gebruiker komt niet voor in onze database.");
define("_PM_PLZTRYAGAIN","Controleer de gebruikersnaam en probeer het opnieuw.");
define("_PM_MESSAGEPOSTED","Bericht is geplaatst");
define("_PM_CLICKHERE","Klik hier om uw privéberichten (pm) te bekijken");
define("_PM_ORCLOSEWINDOW","Klik hier om het scherm te sluiten.");
define("_PM_USERWROTE","%s schreef:");
define("_PM_TO","Aan: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Bericht: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Wissen");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Niet gelezen");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Registreer of log in alvorens een bericht te verzenden!");

define("_PM_ONLINE", "Online");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Privébericht notificatie");
