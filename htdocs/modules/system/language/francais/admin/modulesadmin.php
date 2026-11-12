<?php
// fichier:  .../modules/system/admin/modulesadmin/modulesadmin.php
//              .../modules/system/admin/modulesadmin/main.php
//Administration gestion et installation des modules
// Attention ! ce Code  %s  est  le nom du module
define("_MD_AM_MODADMIN","Administration des modules");
define("_MD_AM_MODULE","Module");
define("_MD_AM_VERSION","Version");
define("_MD_AM_LASTUP","Derni&egrave;re mise &agrave; jour");
define("_MD_AM_DEACTIVATED","D&eacute;sactiv&eacute;");
define("_MD_AM_ACTION","Action");
define("_MD_AM_DEACTIVATE","D&eacute;sactiver");
define("_MD_AM_ACTIVATE","Activer");
define("_MD_AM_UPDATE","Mise &agrave; jour");
define("_MD_AM_DUPEN","Entr&eacute;e dupliqu&eacute;e dans la table modules !");
define("_MD_AM_DEACTED","Le module s&eacute;lectionn&eacute; a &eacute;t&eacute; d&eacute;sactiv&eacute;. Vous pouvez maintenant le d&eacute;sinstaller sans risque.");
define("_MD_AM_ACTED","Le module s&eacute;lectionn&eacute; a &eacute;t&eacute; activ&eacute; !");
define("_MD_AM_UPDTED","Le module s&eacute;lectionn&eacute; a &eacute;t&eacute; mis &agrave; jour !");
define("_MD_AM_SYSNO","Le module Syst&egrave;me ne peut &ecirc;tre d&eacute;sactiv&eacute;.");
define("_MD_AM_STRTNO","Ce module est d&eacute;fini comme votre page d'accueil par d&eacute;faut. Merci de changer tout d'abord le module d'accueil dans les pr&eacute;f&eacute;rences.");
define("_MD_AM_PCMFM","Merci de confirmer :");
define("_MD_AM_ORDER","Ordre");
define("_MD_AM_ORDER0","(0 = cach&eacute;)");
define("_MD_AM_ACTIVE","Actif");
define("_MD_AM_INACTIVE","Inactif");
define("_MD_AM_NOTINSTALLED","Non install&eacute;");
define("_MD_AM_NOCHANGE","Pas de changement");
define("_MD_AM_INSTALL","Installer");
define("_MD_AM_UNINSTALL","D&eacute;sinstaller");
define("_MD_AM_SUBMIT","Valider");
define("_MD_AM_CANCEL","Annuler");
define("_MD_AM_DBUPDATE","Base de donn&eacute;es mise &agrave; jour avec succ&agrave;s !");
define("_MD_AM_BTOMADMIN","Retour &agrave; la page d'administration des modules");
define("_MD_AM_FAILINS","Impossible d'installer %s.");
define("_MD_AM_FAILACT","Impossible d'activer %s.");
define("_MD_AM_FAILDEACT","Impossible de d&eacute;sactiver %s.");
define("_MD_AM_FAILUPD","Impossible de mettre &agrave; jour %s.");
define("_MD_AM_FAILUNINS","Impossible de d&eacute;sinstaller %s.");
define("_MD_AM_FAILORDER","Impossible de r&eacute;-ordonner %s.");
define("_MD_AM_FAILWRITE","Impossible d'&eacute;crire dans le menu principal.");
define("_MD_AM_ALEXISTS","Le module %s existe d&eacute;j&agrave;.");
define("_MD_AM_ERRORSC", "Erreur(s):");
define("_MD_AM_OKINS","Le module %s a &eacute;t&eacute; install&eacute; avec succ&egrave;s.");
define("_MD_AM_OKACT","Le module %s a &eacute;t&eacute; activ&eacute; avec succ&egrave;s.");
define("_MD_AM_OKDEACT","Le module %s a &eacute;t&eacute; d&eacute;sactiv&eacute; avec succ&egrave;s.");
define("_MD_AM_OKUPD","Le module %s a &eacute;t&eacute; mis &agrave; jour avec succ&egrave;s.");
define("_MD_AM_OKUNINS","Le module %s a &eacute;t&eacute; d&eacute;sinstall&eacute; avec succ&egrave;s.");
define("_MD_AM_OKORDER","Le module %s a &eacute;t&eacute; r&eacute;-ordonn&eacute; avec succ&egrave;s.");
define('_MD_AM_RUSUREINS', 'Pressez le bouton ci-dessous pour installer ce module');
define('_MD_AM_RUSUREUPD', 'Pressez le bouton ci-dessous pour mettre &agrave; jour ce module');
define('_MD_AM_RUSUREUNINS', '&Ecirc;tes-vous s&ucirc;r de vouloir d&eacute;sinstaller ce module ?');
define('_MD_AM_LISTUPBLKS', 'Les blocs suivants vont &ecirc;tre mis &agrave; jour.<br />S&eacute;lectionnez les blocs dont le contenu (template et options) peuvent &ecirc;tre &eacute;cras&eacute;s.<br />');
define('_MD_AM_NEWBLKS', 'Nouveaux blocs');
define('_MD_AM_DEPREBLKS', 'Blocs rejet&eacute;s');
define('_MD_AM_MODULESADMIN_SUPPORT', 'Site de support du module');
define('_MD_AM_MODULESADMIN_STATUS', 'Status');
define('_MD_AM_MODULESADMIN_MODULENAME', 'Nom du module');
define('_MD_AM_MODULESADMIN_MODULETITLE', 'Titre du module');

// Online module updates from GitHub
define('_MD_AM_GITHUB_TITLE', 'Available on GitHub');
define('_MD_AM_GITHUB_LATEST', 'Latest release');
define('_MD_AM_GITHUB_NEWVERSION', 'Version %s available');
define('_MD_AM_GITHUB_REFRESH', 'Check for updates now');
define('_MD_AM_GITHUB_CHECKED', 'Last checked on GitHub: %s');
define('_MD_AM_GITHUB_NEVERCHECKED', 'Not checked on GitHub yet');
define('_MD_AM_GITHUB_DESCRIPTION', 'Description');
define('_MD_AM_GITHUB_NORELEASE', 'No release yet');
define('_MD_AM_GITHUB_REPOSITORY', 'Repository');
define('_MD_AM_GITHUB_STARS', 'Stars');
define('_MD_AM_GITHUB_PUBLISHED', 'Released');
define('_MD_AM_GITHUB_INSTALL', 'Install from GitHub');
define('_MD_AM_GITHUB_CONFIRM', 'Install %1$s %2$s from GitHub? The current files and tables are backed up first and put back if the update fails.');
define('_MD_AM_GITHUB_INSTALLED_VERSION', 'Version of the files now: %s');
define('_MD_AM_GITHUB_DONE', '%s was installed from GitHub.');
define('_MD_AM_GITHUB_FAILED', 'Installing %s from GitHub failed. Nothing was changed, or the backup was put back.');
define('_MD_AM_GITHUB_INSTALLNOW', 'The module files are in place. Install the module now to start using it.');
define('_MD_AM_BACKUPS', 'Backups');
define('_MD_AM_BACKUP_DATE', 'Date');
define('_MD_AM_BACKUP_STATUS', 'Result');
define('_MD_AM_BACKUP_ROLLBACK', 'Roll back');
define('_MD_AM_BACKUP_DELETE', 'Delete');
define('_MD_AM_BACKUP_NONE', 'No backups yet');
define('_MD_AM_BACKUP_CONFIRM_ROLLBACK', 'Put %1$s version %2$s back from the backup of %3$s? Changes to the module data since then are lost.');
define('_MD_AM_BACKUP_CONFIRM_DELETE', 'Delete the backup of %1$s from %2$s?');
define('_MD_AM_BACKUP_ROLLEDBACK', '%s was rolled back.');
define('_MD_AM_BACKUP_ROLLBACKFAIL', 'Rolling back %s failed.');
define('_MD_AM_BACKUP_DELETED', 'The backup was deleted.');
define('_MD_AM_BACKUP_STATUS_OK', 'Updated');
define('_MD_AM_BACKUP_STATUS_PENDING', 'Interrupted');
define('_MD_AM_BACKUP_STATUS_FAILED', 'Failed, backup put back');
define('_MD_AM_BACKUP_STATUS_ROLLEDBACK', 'Rolled back');
define('_MD_AM_REMOVE', 'Delete from the site');
define('_MD_AM_REMOVE_CONFIRM', 'Delete %1$s from the site? The folder %2$s and all backups of this module are deleted for good.');
define('_MD_AM_REMOVE_DONE', '%s was deleted from the site.');
define('_MD_AM_REMOVE_FAILED', 'Deleting %s failed.');
define('_MD_AM_REMOVE_INSTALLED', '%s is installed. Uninstall it first.');

// trusted owners of GitHub modules
define('_MD_AM_GITHUB_OWNERS', 'Only modules from these GitHub owners are shown: %s. <a href="%s">Change</a>');

?>
