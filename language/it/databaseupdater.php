<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Importazione");
define("_DATABASEUPDATER_CURRENTVER", "Versione attuale: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Versione database %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Dati aggiunti alla tabella %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Errore aggiungendo alla tabella %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Canbiamento campo  %s nella tabella %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Errore cambiando campo %s nella tabella %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Tabella %s creata");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Errore creando tabella %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Campo aggiunto con successo %s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Errore aggiungendo campo %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Il tuo database &egrave; da aggiornare. Sei pregato di aggiornare al pi&ugrave; presto!<br /><b>Nota: Raccomandiamo di fare un backup del database prima di iniziare la procedura.</b><br />");
define("_DATABASEUPDATER_NOUPDATE", "Il tuo database &egrave; aggiornato. Non &egrave; necessario alcun aggiornamento.");
define("_DATABASEUPDATER_UPDATE_DB", "Database in corso di aggiornamento");
define("_DATABASEUPDATER_UPDATE_ERR", "Errori aggiornando alla versione %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Aggiorna ora!");
define("_DATABASEUPDATER_UPDATE_OK", "Aggiornato con successo alla versione %s");
define("_DATABASEUPDATER_UPDATE_TO", "Aggiornando alla versione %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Aggiornamento del database in corso ...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Dati contenuti nella tabella %s sono stati aggiornati con successo");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Si &egrave; verificato un errore aggiornando i dati della tabella %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "I dati specificati della tabella %s sono stati cancellati con successo");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Si &egrave; verificato un errore cancellando i dati nella tabella %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Impossibile aggiornare la versione database del modulo");
define("_DATABASEUPDATER_LATESTVER", "Ultima versione database: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Impossibile inserire la configurazione %s");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "La configurazione %s inserita con successo");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'Si è verificato un errore i campi specificati %1$s dalla tavola %2$s');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'Eliminato con successo il campo %1$s dalla tavola %2$s');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Tabella %s del database eliminata con successo");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Errore nel eliminare la tabella %s del database");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Interrogazione riuscita: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Interrogazione fallita: %s");
