<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// $Id: notification.php 1029 2007-09-09 03:49:25Z phppp $

// RMV-NOTIFY

// Text for various templates...

define ('_NOT_NOTIFICATIONOPTIONS', 'Opzioni di notifica');
define ('_NOT_UPDATENOW', 'Aggiornare adesso!');
define ('_NOT_UPDATEOPTIONS', 'Aggiornare le opzioni di notifica');

define ('_NOT_CLEAR', 'Pulisci');
define ('_NOT_CHECKALL', 'Seleziona tutto');
define ('_NOT_MODULE', 'Modulo');
define ('_NOT_CATEGORY', 'Categoria');
define ('_NOT_ITEMID', 'ID');
define ('_NOT_ITEMNAME', 'Nome');
define ('_NOT_EVENT', 'Evento');
define ('_NOT_EVENTS', 'Eventi');
define ('_NOT_ACTIVENOTIFICATIONS', 'Notifiche attive');
define ('_NOT_NAMENOTAVAILABLE', 'Nome non disponibile');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define ('_NOT_ITEMNAMENOTAVAILABLE', 'Elemento Nome non disponibile');
define ('_NOT_ITEMTYPENOTAVAILABLE', 'Elemento Tipo non disponibile');
define ('_NOT_ITEMURLNOTAVAILABLE', 'Elemento URL non disponibile');
define ('_NOT_DELETINGNOTIFICATIONS', 'Eliminazione delle notifiche in corso ...');
define ('_NOT_DELETESUCCESS', 'Notifiche eliminate con successo!');
define ('_NOT_UPDATEOK', 'Opzioni di notifica aggiornate');
define ('_NOT_NOTIFICATIONMETHODIS', 'Metodo di notifica');
define ('_NOT_EMAIL', 'Email');
define ('_NOT_PM', 'messaggio privato');
define ('_NOT_DISABLE', 'disabilitato');
define ('_NOT_CHANGE', 'Modifica');

define ('_NOT_NOACCESS', 'Non hai i permessi necessari per accedere a questa pagina.');

// Text for module config options

define ('_NOT_ENABLE', 'Abilita');
define ('_NOT_NOTIFICATION', 'Notifica');

define ('_NOT_CONFIG_ENABLED', 'Abilitazione notifiche');
define ('_NOT_CONFIG_ENABLEDDSC', 'Questo sistema consente a un utente di ricevere un avviso al verificarsi di alcuni eventi. Scegliendo "S&igrave;", si attiva quest\'opzione.');

define ('_NOT_CONFIG_EVENTS', 'Abilitazione eventi specifici');
define ('_NOT_CONFIG_EVENTSDSC', 'Selezionare per quali eventi gli utenti possono abilitare le notifiche.');

define ('_NOT_CONFIG_ENABLE', 'Abilitazione notifiche');
define ('_NOT_CONFIG_ENABLEDSC', 'Questo sistema consente ad un utente di ricevere un avviso al verificarsi di alcuni eventi. Scegliere se all\'utente la possibilit&agrave; di iscrizione alla notifica deve venire visualizzata in un blocco, all\'interno del modulo, o in entrambi. Per le notifiche nel blocco, l\'opzione di notifica deve essere abilitata per quel modulo.');
define ('_NOT_CONFIG_DISABLE', 'Disabilita notifica');
define ('_NOT_CONFIG_ENABLEBLOCK', 'Abilitare le notifiche nel blocco');
define ('_NOT_CONFIG_ENABLEINLINE', 'Abilitare le notifiche nel modulo');
define ('_NOT_CONFIG_ENABLEBOTH', 'Abilitare le notifiche (entrambi gli stili)');

// For notification about comment events

define ('_NOT_COMMENT_NOTIFY', 'Commento aggiunto');
define ('_NOT_COMMENT_NOTIFYCAP', 'Messaggio di notifica per ogni nuovo commento inviato.');
define ('_NOT_COMMENT_NOTIFYDSC', 'Inviare un messaggio ogni volta che un nuovo commento viene aggiunto a questo elemento.');
define ('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica: Commento aggiunto a {X_ITEM_TYPE}');

define ('_NOT_COMMENTSUBMIT_NOTIFY', 'Commento inviato');
define ('_NOT_COMMENTSUBMIT_NOTIFYCAP', 'Messaggio di notifica per ogni nuovo commento inviato (in attesa di approvazione).');
define ('_NOT_COMMENTSUBMIT_NOTIFYDSC', 'Inviare un messaggio di notifica ogni volta che un nuovo commento viene inviato (ed &egrave; attesa di approvazione) per questo elemento.');
define ('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica: Commento inviato a {X_ITEM_TYPE}');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define ('_NOT_BOOKMARK_NOTIFY', 'Segnalibro');
define ('_NOT_BOOKMARK_NOTIFYCAP', 'Segnalibro per questo elemento (nessuna notifica).');
define ('_NOT_BOOKMARK_NOTIFYDSC', 'Tieni traccia di questo elemento, senza ricevere eventi di notifica.');

// For user profile
// FIXME: These should be reworded a little...

define ('_NOT_NOTIFYMETHOD', 'Modalit&agrave; di notifica: se, per esempio, si vuole tenere d\'occhio un forum, in che modo si desidera ricevere gli avvisi di eventuali aggiornamenti?');
define ('_NOT_METHOD_EMAIL', 'Email (utilizza l\'indirizzo email del mio profilo)');
define ('_NOT_METHOD_PM', 'Messaggio privato');
define ('_NOT_METHOD_DISABLE', 'Temporaneamente disabilitato');

define ('_NOT_NOTIFYMODE', 'Metodo di notifica base');
define ('_NOT_MODE_SENDALWAYS', 'Un messaggio per ogni notifica scelta');
define ('_NOT_MODE_SENDONCE', 'Un solo messaggio per tutte le notifiche');
define ('_NOT_MODE_SENDONCEPERLOGIN', 'Un solo messaggio per tutte le notifiche e disabilitazione fino a nuovo login');

define ('_NOT_NOTHINGTODELETE', 'Nessun elemento da eliminare.');

