<?php


// RMV-NOTIFY

// Text for various templates...

define ('_NOT_NOTIFICATIONOPTIONS', 'Optionen');
define ('_NOT_UPDATENOW', 'Jetzt aktualisieren');
define ('_NOT_UPDATEOPTIONS', 'Benachrichtigungsoptionen aktualisieren');

define ('_NOT_CLEAR', 'Leeren');
define ('_NOT_CHECKALL', 'Alle auswählen');
define ('_NOT_MODULE', 'Module');
define ('_NOT_CATEGORY', 'Kategorie');
define ('_NOT_ITEMID', 'ID');
define ('_NOT_ITEMNAME', 'Name');
define ('_NOT_EVENT', 'Veranstaltung');
define ('_NOT_EVENTS', 'Veranstaltungen');
define ('_NOT_ACTIVENOTIFICATIONS', 'Benachrichtigungen und Lesezeichen');
define ('_NOT_NAMENOTAVAILABLE', 'Name ist nicht vorhanden');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define ('_NOT_ITEMNAMENOTAVAILABLE', 'Artikelname nicht vorhanden');
define ('_NOT_ITEMTYPENOTAVAILABLE', 'Artikellyp nicht verfügbar');
define ('_NOT_ITEMURLNOTAVAILABLE', 'Artikel-URL nicht verfügbar');
define ('_NOT_DELETINGNOTIFICATIONS', 'Lösche Benachrichtigungen');
define ('_NOT_DELETESUCCESS', 'Benachrichtigung(en) erfolgreich gelöscht.');
define ('_NOT_UPDATEOK', 'Benachrichtigungsoptionen aktualisiert');
define ('_NOT_NOTIFICATIONMETHODIS', 'Benachrichtigungsmethode ist');
define ('_NOT_EMAIL', 'E-Mail');
define ('_NOT_PM', 'private Nachricht');
define ('_NOT_DISABLE', 'deaktiviert');
define ('_NOT_CHANGE', 'Ändern');

define ('_NOT_NOACCESS', 'Sie haben keine Berechtigung auf diese Seite zuzugreifen.');

// Text for module config options

define ('_NOT_NOTIFICATION', 'Benachrichtigung');

define ('_NOT_CONFIG_ENABLED', 'Benachrichtigung aktivieren');
define ('_NOT_CONFIG_ENABLEDDSC', 'Dieses Modul erlaubt den Benutzern, benachrichtigt zu werden, wenn bestimmte Ereignisse auftreten. Wählen Sie "Ja", um diese Funktion zu aktivieren.');

define ('_NOT_CONFIG_EVENTS', 'Spezielle Ereignisse aktivieren');
define ('_NOT_CONFIG_EVENTSDSC', 'Wählen Sie aus, welche Benachrichtigungs-Ereignisse Ihre Benutzer abonnieren können.');

define ('_NOT_CONFIG_ENABLE', 'Benachrichtigung aktivieren');
define ('_NOT_CONFIG_ENABLEDSC', 'Dieses Modul erlaubt es Benutzern benachrichtigt zu werden, wenn bestimmte Ereignisse auftreten. Wählen Sie, ob Benutzer mit Benachrichtigungsoptionen in einem Block (Block-Stil), innerhalb des Moduls (Inline-Stil) oder beides angezeigt werden sollen. Für Benachrichtigungen im Blockstil muss der Block Benachrichtigungsoptionen für dieses Modul aktiviert sein.');
define ('_NOT_CONFIG_DISABLE', 'Benachrichtigung deaktivieren');
define ('_NOT_CONFIG_ENABLEBLOCK', 'Nur Blockstil aktivieren');
define ('_NOT_CONFIG_ENABLEINLINE', 'Nur Inline-Stil aktivieren');
define ('_NOT_CONFIG_ENABLEBOTH', 'Benachrichtigung aktivieren (beide Stile)');

// For notification about comment events

define ('_NOT_COMMENT_NOTIFY', 'Kommentar hinzugefügt');
define ('_NOT_COMMENT_NOTIFYCAP', 'Benachrichtigen Sie mich, wenn ein neuer Kommentar für dieses Element gepostet wird.');
define ('_NOT_COMMENT_NOTIFYDSC', 'Erhalten Sie eine Benachrichtigung, wenn ein neuer Kommentar für dieses Element veröffentlicht (oder genehmigt) wird.');
define ('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Auto-Benachrichtigung: Kommentar zu {X_ITEM_TYPE} hinzugefügt');

define ('_NOT_COMMENTSUBMIT_NOTIFY', 'Kommentar übermittelt');
define ('_NOT_COMMENTSUBMIT_NOTIFYCAP', 'Benachrichtigen Sie mich, wenn ein neuer Kommentar für dieses Element eingereicht wird (warte auf Genehmigung).');
define ('_NOT_COMMENTSUBMIT_NOTIFYDSC', 'Benachrichtigen Sie mich, wenn ein neuer Kommentar für dieses Element eingereicht wird (warte auf Genehmigung).');
define ('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Auto-Benachrichtigung: Kommentar zu {X_ITEM_TYPE} hinzugefügt');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define ('_NOT_BOOKMARK_NOTIFY', 'Lesezeichen');
define ('_NOT_BOOKMARK_NOTIFYCAP', 'Lesezeichen für diesen Eintrag (keine Benachrichtigung).');
define ('_NOT_BOOKMARK_NOTIFYDSC', 'Behalten Sie den Überblick über dieses Element, ohne dass Sie irgendwelche Benachrichtigungen erhalten.');

// For user profile
// FIXME: These should be reworded a little...

define ('_NOT_NOTIFYMETHOD', 'Benachrichtigungsmethode: Wie möchten Sie Benachrichtigungen über Updates erhalten, wenn Sie z.B. ein Forum überwachen?');
define ('_NOT_METHOD_EMAIL', 'E-Mail (Benutze die Adresse in meinem Profil)');
define ('_NOT_METHOD_PM', 'Private Nachrichten');
define ('_NOT_METHOD_DISABLE', 'Vorübergehend deaktivieren');

define ('_NOT_NOTIFYMODE', 'Standard-Benachrichtigungsmodus');
define ('_NOT_MODE_SENDALWAYS', 'Benachrichtigen Sie mich über alle ausgewählten Updates');
define ('_NOT_MODE_SENDONCE', 'Nur einmal benachrichtigen');
define ('_NOT_MODE_SENDONCEPERLOGIN', 'Benachrichtigen Sie mich einmal und deaktivieren Sie mich, bis ich mich erneut anmelden kann');

define ('_NOT_NOTHINGTODELETE', 'Es ist nichts zu löschen.');

// Added in 1.3.1
define("_NOT_RUSUREDEL", "Sind Sie sicher, dass Sie diese Benachrichtigungen löschen möchten?");