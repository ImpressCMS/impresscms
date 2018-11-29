<?php
// $Id: mailusers.php 10326 2010-07-11 18:54:25Z malanciault $
//%%%%%%	Admin Module Name  MailUsers	%%%%%
if (!defined('_AM_DBUPDATED')) {define('_AM_DBUPDATED', 'Database succesvol bijgewerkt!');
}

//%%%%%%	mailusers.php 	%%%%%
define('_AM_SENDTOUSERS', 'Verstuur het bericht naar de volgende gebruikers:');
define('_AM_SENDTOUSERS2', 'Verstuur naar:');
define('_AM_GROUPIS', 'Gebruikersgroep (optioneel)');
define('_AM_TIMEFORMAT', '(Opmaak jjjj-mm-dd, optioneel)');
define('_AM_LASTLOGMIN', 'Laatste keer ingelogd na');
define('_AM_LASTLOGMAX', 'Laatste keer ingelogd voor');
define('_AM_REGDMIN', 'Registratie datum na');
define('_AM_REGDMAX', 'Registratie datum voor');
define('_AM_IDLEMORE', 'Laatste keer ingelogd meer dan X dagen geleden (optioneel)');
define('_AM_IDLELESS', 'Laatste keer ingelogd minder dan X dagen geleden (optioneel)');
define('_AM_MAILOK', 'Verstuur bericht alleen naar gebruikers die notificatie berichten accepteren (optioneel)');
define('_AM_INACTIVE', 'Verstuur bericht alleen naar inactieve gebruikers (optioneel)');
define('_AMIFCHECKD', 'Wanneer aangevinkt, dan wordt al het voorgaande inclusief priveberichten genegeerd');
define('_AM_MAILFNAME', 'Van naam (aleen bij e-mail)');
define('_AM_MAILFMAIL', 'Van e-mail (alleen bij e-mail)');
define('_AM_MAILSUBJECT', 'Onderwerp');
define('_AM_MAILBODY', 'Tekstvak');
define('_AM_MAILTAGS', 'Handige tags:');
define('_AM_MAILTAGS1', '{X_UID} toont gebruikers id');
define('_AM_MAILTAGS2', '{X_UNAME} toont gebruikersnaam');
define('_AM_MAILTAGS3', '{X_UEMAIL} toont gebruikers e-mailadres');
define('_AM_MAILTAGS4', '{X_UACTLINK} toont gebruikers activatielink');
define('_AM_SENDTO', 'Verstuur naar');
define('_AM_EMAIL', 'E-mail');
define('_AM_PM', 'Privébericht');
define('_AM_SENDMTOUSERS', 'Verstuur bericht naar de gebruikers');
define('_AM_SENT', 'Verzonden naar de gebruikers');
define('_AM_SENTNUM', '%s - %s (totaal: %s gebruikers)');
define('_AM_SENDNEXT', 'Volgende');
define('_AM_NOUSERMATCH', 'Geen overeenkomstige gebruiker(s)');
define('_AM_SENDCOMP', 'Bericht is verzonden.');
?>