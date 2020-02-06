<?php


// RMV-NOTIFY

// Text for various templates...

define ('_NOT_NOTIFICATIONOPTIONS', 'Notificatie Instellingen');
define ('_NOT_UPDATENOW', 'Nu updaten');
define ('_NOT_UPDATEOPTIONS', 'Update Notificatie instellingen');

define ('_NOT_CLEAR', 'Wis');
define ('_NOT_CHECKALL', 'Alles aanvinken');
define ('_NOT_MODULE', 'Module');
define ('_NOT_CATEGORY', 'Categorie');
define ('_NOT_ITEMID', 'ID');
define ('_NOT_ITEMNAME', 'Naam');
define ('_NOT_EVENT', 'Gebeurtenis');
define ('_NOT_EVENTS', 'Gebeurtenissen');
define ('_NOT_ACTIVENOTIFICATIONS', 'Actieve notificaties');
define ('_NOT_NAMENOTAVAILABLE', 'Naam niet beschikbaar');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define ('_NOT_ITEMNAMENOTAVAILABLE', 'Item naam niet beschikbaar');
define ('_NOT_ITEMTYPENOTAVAILABLE', 'Item type niet beschikbaar');
define ('_NOT_ITEMURLNOTAVAILABLE', 'Item URL niet beschikbaar');
define ('_NOT_DELETINGNOTIFICATIONS', 'Bezig met notificaties te Verwijderen');
define ('_NOT_DELETESUCCESS', 'Notificatie(s) succesvol verwijderd.');
define ('_NOT_UPDATEOK', 'Notificatieinstellingen zijn succesvol aangepast');
define ('_NOT_NOTIFICATIONMETHODIS', 'Notificatie methode is');
define ('_NOT_EMAIL', 'E-mail');
define ('_NOT_PM', 'Privébericht (PM = Personal Message)');
define ('_NOT_DISABLE', 'Uitschakelen');
define ('_NOT_CHANGE', 'Wijzig');

define ('_NOT_NOACCESS', 'U hebt geen rechten om deze pagina te openen.');

// Text for module config options

define ('_NOT_NOTIFICATION', 'Notificatie');

define ('_NOT_CONFIG_ENABLED', 'Notificatie inschakelen');
define ('_NOT_CONFIG_ENABLEDDSC', 'Deze module staat gebruikers toe geinformeerd te worden wanneer een bepaalde handeling plaatsvindt. Kies "ja" om deze functie in te schakelen.');

define ('_NOT_CONFIG_EVENTS', 'Schakel bepaalde evenementen in');
define ('_NOT_CONFIG_EVENTSDSC', 'Selecteer op welke evenementen gebruikers zich mogen inschrijven.');

define ('_NOT_CONFIG_ENABLE', 'Notificatie inschakelen');
define ('_NOT_CONFIG_ENABLEDSC', 'Deze module staat gebruikers toe geinformeerd te worden wanneer
 een bepaalde handeling plaatsvindt. Selecteer of het bericht moet worden getoond in een blok
 (blockstyle) of in de module (inline-style) of beiden. Voor block-style notificatie dient de notificatie instelling te zijn toegestaan.');
define ('_NOT_CONFIG_DISABLE', 'Notificatie uitschakelen');
define ('_NOT_CONFIG_ENABLEBLOCK', 'Schakel enkel blok-stijl in');
define ('_NOT_CONFIG_ENABLEINLINE', 'Schakel enkel in-line-stijl in');
define ('_NOT_CONFIG_ENABLEBOTH', 'Schakel notificatie (beide stijlen) in');

// For notification about comment events

define ('_NOT_COMMENT_NOTIFY', 'Reactie toegevoegd');
define ('_NOT_COMMENT_NOTIFYCAP', 'Stel me op de hoogte wanneer er een nieuwe reactie is toegevoegd.');
define ('_NOT_COMMENT_NOTIFYDSC', 'Ontvang telkens een bericht wanneer er een nieuwe reactie is gepost (of goedgekeurd) voor dit item.');
define ('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}] auto-melding: Commentaar Toegevoegd Bij {X_ITEM_TYPE} "{X_ITEM_TITLE}" [AUTO-NOTIFY]');

define ('_NOT_COMMENTSUBMIT_NOTIFY', 'Reactie Ingestuurd');
define ('_NOT_COMMENTSUBMIT_NOTIFYCAP', 'Stel me op de hoogte wanneer er een nieuwe reactie is ingestuurd (wachtend op toelating) voor dit item');
define ('_NOT_COMMENTSUBMIT_NOTIFYDSC', 'Ontvang telkens een bericht wanneer er een nieuwe reactie is ingestuurd (wachtend op toelating) voor dit item.');
define ('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}] auto-melding: Reactie Toegevoegd Bij {X_ITEM_TYPE} "{X_ITEM_TITLE}" [AUTO-NOTIFY]');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define ('_NOT_BOOKMARK_NOTIFY', 'Bookmark');
define ('_NOT_BOOKMARK_NOTIFYCAP', 'Bookmark dit item (zonder melding).');
define ('_NOT_BOOKMARK_NOTIFYDSC', 'Volg dit item zonder notificaties te ontvangen');

// For user profile
// FIXME: These should be reworded a little...

define ('_NOT_NOTIFYMETHOD', 'Notificatie Methode');
define ('_NOT_METHOD_EMAIL', 'E-mail (gebruik E-mailadres in mijn profiel)');
define ('_NOT_METHOD_PM', 'Privébericht');
define ('_NOT_METHOD_DISABLE', 'Tijdelijk Uitgeschakeld');

define ('_NOT_NOTIFYMODE', 'Standaard Notificatie Methode');
define ('_NOT_MODE_SENDALWAYS', 'Stel me op de hoogte van alle geselecteerde updates');
define ('_NOT_MODE_SENDONCE', 'Stuur me slechts eenmalig een melding');
define ('_NOT_MODE_SENDONCEPERLOGIN', 'Stel mij eenmalig op de hoogte totdat ik me opnieuw inlog');

define ('_NOT_NOTHINGTODELETE', 'Niets aanwezig om te verwijderen.');

// Added in 1.3.1
define("_NOT_RUSUREDEL", "Are you sure you want to delete these notifications?");