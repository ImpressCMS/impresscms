<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Importieren");
define("_DATABASEUPDATER_CURRENTVER", "Aktuelle Version: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Datenbank Version %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "In Tabelle %s hinzugefügte Daten");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Fehler beim Hinzufügen von Daten in Tabelle %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Feld %s in Tabelle %s wird geändert");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Fehler beim Ändern des Feldes %s in der Tabelle %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Tabelle %s erstellt");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Fehler beim Erstellen der Tabelle %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Feld %s erfolgreich hinzugefügt");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Fehler beim Hinzufügen des Feldes %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Ihre Datenbank ist veraltet. Bitte aktualisieren Sie Ihre Datenbanktabellen!<br /><b>Hinweis : Das ImpressCMS empfiehlt Ihnen dringend, alle Datenbanktabellen zu sichern, bevor Sie dieses Upgrade-Skript ausführen.</b>");
define("_DATABASEUPDATER_NOUPDATE", "Ihre Datenbank ist aktuell. Es sind keine Updates notwendig.");
define("_DATABASEUPDATER_UPDATE_DB", "Datenbank wird aktualisiert");
define("_DATABASEUPDATER_UPDATE_ERR", "Fehler beim Aktualisieren auf Version %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Jetzt aktualisieren!");
define("_DATABASEUPDATER_UPDATE_OK", "Erfolgreich auf Version %s aktualisiert");
define("_DATABASEUPDATER_UPDATE_TO", "Fehler beim Aktualisieren auf Version %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Datenbank wird aktualisiert...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Datensätze der Tabelle %s wurden erfolgreich aktualisiert");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Fehler beim Aktualisieren der Datensätze in Tabelle %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Datensätze der Tabelle %s wurden erfolgreich aktualisiert");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Fehler beim Aktualisieren der Datensätze in Tabelle %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Modul dbversion konnte nicht aktualisiert werden");
define("_DATABASEUPDATER_LATESTVER", "Neueste Datenbankversion : <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Kann Konfiguration %s nicht einfügen");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "%s Konfiguration erfolgreich eingefügt");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'Beim Löschen der angegebenen Felder %1$s aus Tabelle %2$s ist ein Fehler aufgetreten');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'Feld %1$s wurde erfolgreich aus Tabelle %2$s gelöscht');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Datenbanktabelle %s erfolgreich gelöscht");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Fehler beim Hinzufügen von Daten in Tabelle %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Abfrage erfolgreich: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Anfrage fehlgeschlagen: %s");
