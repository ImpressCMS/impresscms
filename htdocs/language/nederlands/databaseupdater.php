<?php

/**
* $Id: databaseupdater.php 8688 2009-05-02 18:25:07Z pesianstranger $
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
    die("ImpressCMS root path is niet gedefineerd");
}

define("_DATABASEUPDATER_IMPORT", "Importeer");
define("_DATABASEUPDATER_CURRENTVER", "Huidige versie: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Database Versie %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Data toegevoegd in tabel %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Fout bij toevoegen data in tabel %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Wijzigen van veld %s in tabel %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Fout bij wijzigen van veld %s in tabel %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Tabel %s is aangemaakt");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Fout bij aanmaken van tabel %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Veld %s is succesvol toegevoegd");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Fout bij toevoegen van veld %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Uw database is niet up-to-date. Wij verzoeken u uw database tabellen bij te werken!<br /><b>Opmerking : ImpressCMS adviseery u ten zeerste eerst een backup te maken van al uw database tabellen alvorens u dit bijwerk script uitvoert.</b><br />");
define("_DATABASEUPDATER_NOUPDATE", "Uw database is up-to-date. Er zijn geen bijwerkingen noodzakelijk.");
define("_DATABASEUPDATER_UPDATE_DB", "Bijwerken van de database");
define("_DATABASEUPDATER_UPDATE_ERR", "Fouten tijdens bijwerken naar versie %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Nu bijwerken!");
define("_DATABASEUPDATER_UPDATE_OK", "Succesvol bijgewerkt naar versie %s");
define("_DATABASEUPDATER_UPDATE_TO", "Bijwerken naar versie %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Bijwerken van database...");
define("_DATABASEUPDATER_MSG_DROPFIELD", "Veld %s succesvol verwijderd");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Data van tabel %s zijn succesvol bijgewerkt");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Er is een fout opgetreden tijdens het bijwerken van de data in tabel %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "De specifieke data van tabel %s zijn succesvol verwijderd");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Er is een fout opgetreden tijdens het verwijderen van spefieke data uit tabel %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Bijwerken van module dbversie niet mogelijk");
define("_DATABASEUPDATER_LATESTVER", "Laatste database versie : <span class='currentVer'>%s</span><br />");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Niet mogelijk om config %s in te voegen<br />");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "%s config succesvol ingevoegd<br />");
