<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Importer");
define("_DATABASEUPDATER_CURRENTVER", "Version actuelle: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Version de la base de donn&eacute;es %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Donn&eacute;es ajout&eacute;es dans les tables %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Erreur lors de l'ajout des donn&eacute;es dans la table %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Changement de Champ %s dans la table %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Erreur des Champs %s dans la table %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Table %s cr&eacute;er");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Erreur lors de la cr&eacute;ation de la table %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Champ %s ajout&eacute; avec succ&egrave;s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Erreur lors de l'ajout du Champ %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Votre base de donn&eacute;es n'est pas &agrave; jour. Merci de mettre &agrave; jour votre base de donn&eacute;es !<br /><b>Infos: Il est fortement recommand&eacute; de sauvegarder votre base de donn&eacute;es avant de lancer cette mise &agrave; jour.</b><br />");
define("_DATABASEUPDATER_NOUPDATE", "Votre base de donn&eacute;es est mise &agrave; jour. Aucune mises &agrave; jour est n&eacute;cessaires.");
define("_DATABASEUPDATER_UPDATE_DB", "Mise &agrave; jour de la bases de donn&eacute;es");
define("_DATABASEUPDATER_UPDATE_ERR", "Erreurs de la mise &agrave; jour vers la version %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Mettre &agrave; jour maintenant!");
define("_DATABASEUPDATER_UPDATE_OK", "Mis &agrave; jour vers la version %s r&eacute;alis&eacute; avec succ&egrave;s");
define("_DATABASEUPDATER_UPDATE_TO", "Mise &agrave; jour vers la version %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Mise &agrave; jour de la base de donn&eacute;es...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Les tables %s ont &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Une erreur s'est produite en mettant &agrave; jour dans la table %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Les tables %s ont &eacute;t&eacute; supprim&eacute;s avec succ&egrave;s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Une erreur s'est produite en supprimant les donn&eacute;es indiqu&eacute;s dans la table %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Impossible de mettre ");
define("_DATABASEUPDATER_LATESTVER", "Derni");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Impossible d'ins");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "\"Configuration %s ajout");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'Une erreur s\'est produite en supprimant les champs %1$s indiqu&eacute; s dans la table %2$s');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'Mis &agrave; jour du champ %s r&eacute;alis&eacute; avec succ&egrave;s');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "La table de la base de données %s a été supprimée avec succès");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Erreur lors de la suppression de la table de la base de données %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Requête réussie : %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Échec de la requête : %s");
