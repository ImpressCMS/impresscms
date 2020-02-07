<?php
/**
 * Installer main english strings declaration file.
 * @copyright	The ImpressCMS project http://www.impresscms.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author       Skalpa Keo <skalpa@xoops.org>
 * @author       Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
 * @since        1.0
 * @package 		installer
 */

define("SHOW_HIDE_HELP", "Afficher/masquer le texte d'aide");

define("ALTERNATE_LANGUAGE_MSG", "Téléchargez un pack de langue alternatif sur le site Web ImpressCMS");
define("ALTERNATE_LANGUAGE_LNK_MSG", "Sélectionnez une autre langue qui n'est pas listée ici.");
define("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");

// Configuration check page
define("SERVER_API", "API du serveur");
define("PHP_EXTENSION", "Extension %s");
define("CHAR_ENCODING", "Encodage des caractères");
define("XML_PARSING", "XML parsing");
define("REQUIREMENTS", "Besoins");
define("_PHP_VERSION", "Version PHP");
define("RECOMMENDED_SETTINGS", "Paramètres recommandés");
define("RECOMMENDED_EXTENSIONS", "Extensions recommandées");
define("SETTING_NAME", "Nom du paramètre");
define("RECOMMENDED", "Recommandé");
define("CURRENT", "Actuel");
define("RECOMMENDED_EXTENSIONS_MSG", "Ces extensions ne sont pas nécessaires pour une utilisation normale, mais peuvent être nécessaires pour exploiter certaines fonctionnalités spécifiques (comme le support du multilangage ou RSS). Il est donc recommandé de les installer." );
define("NONE", "Aucun");
define("SUCCESS", "Succès");
define("WARNING", "Attention");
define("FAILED", "Échoué");

// Titles (main and pages)
define("XOOPS_INSTALL_WIZARD", " %s - Assistant d'installation");
define("INSTALL_STEP", "Étape");
define("INSTALL_H3_STEPS", "Étapes");
define("INSTALL_OUTOF", " hors de ");
define("INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"https://www.impresscms.org\" target=\"_blank\">The ImpressCMS Project</a>");

define("LANGUAGE_SELECTION", "Sélection de la langue");
define("LANGUAGE_SELECTION_TITLE", "Sélectionnez votre langue"); // L128
define("INTRODUCTION", "Introduction");
define("INTRODUCTION_TITLE", "Bienvenue dans l'assistant d'installation ImpressCMS"); // L0
define("CONFIGURATION_CHECK", "Vérification de la configuration");
define("CONFIGURATION_CHECK_TITLE", "Vérification de la configuration de votre serveur");
define("PATHS_SETTINGS", "Paramétrages des chemins");
define("PATHS_SETTINGS_TITLE", "Paramétrages des chemins");
define("DATABASE_CONNECTION", "Connexion à la base de données");
define("DATABASE_CONNECTION_TITLE", "Connexion à la base de données");
define("DATABASE_CONFIG", "Configuration de la base de données");
define("DATABASE_CONFIG_TITLE", "Configuration de la base de données");
define("CONFIG_SAVE", "Enregistrer la configuration");
define("CONFIG_SAVE_TITLE", "Enregistrement de la configuration du système");
define("TABLES_CREATION", "Création des tables");
define("TABLES_CREATION_TITLE", "Création des tables dans la base de données");
define("INITIAL_SETTINGS", "Paramètres initiaux");
define("INITIAL_SETTINGS_TITLE", "Veuillez entrer vos paramètres initiaux");
define("DATA_INSERTION", "Insertion de données");
define("DATA_INSERTION_TITLE", "Enregistrement de vos paramètres dans la base de données");
define("WELCOME", "Bienvenue,");
define("NO_PHP5", "Pas de PHP 5");
define("WELCOME_TITLE", "Installation de ImpressCMS complété"); // L0
define("MODULES_INSTALL", "Installation de modules");
define("MODULES_INSTALL_TITLE", "Installation de modules ");
define("NO_PHP5_TITLE", "Pas de PHP 5");
define("NO_PHP5_CONTENT", "Un minimum de PHP 5.6.0 est requis pour que ImpressCMS fonctionne correctement - votre installation ne peut pas continuer. Veuillez travailler avec votre hébergeur pour mettre à jour votre environnement vers une version de PHP plus récente que 5.6.0 (7.2 + est recommandé) avant d'essayer de réinstaller. Pour plus d'informations, lisez <a href='https://www.impresscms.org/modules/news/article.php?article_id=122' >ImpressCMS sur PHP5 </a>.");
define("SAFE_MODE", "Safe Mode activé");
define("SAFE_MODE_TITLE", "Safe Mode activé");
define("SAFE_MODE_CONTENT", "ImpressCMS a détecté que PHP fonctionne en 'safe mode'. Par conséquent, votre installation ne peut pas continuer. Veuillez travailler avec votre hébergeur pour modifier votre environnement avant d'essayer de réinstaller.");

// Settings (labels and help text)
define("XOOPS_ROOT_PATH_LABEL", "Chemin physique racine de documents ImpressCMS"); // L55
define("XOOPS_ROOT_PATH_HELP", "Ceci est le chemin physique du dossier de documents ImpressCMS, la racine web même de votre application ImpressCMS"); // L59

define("XOOPS_URL_LABEL", "Emplacement du site web (URL)"); // L56
define("XOOPS_URL_HELP", "URL principale qui sera utilisée pour accéder à votre installation ImpressCMS"); // L58

define("LEGEND_CONNECTION", "Connexion serveur");
define("LEGEND_DATABASE", "Base de données"); // L51

define("DB_HOST_LABEL", "Nom d'hôte du serveur"); // L27
define("DB_HOST_HELP", "Nom d'hôte du serveur de base de données. Si vous n'êtes pas sûr, <em>localhost</em> fonctionne dans la plupart des cas"); // L67
define("DB_USER_LABEL", "Nom d'utilisateur"); // L28
define("DB_USER_HELP", "Nom du compte utilisateur qui sera utilisé pour se connecter au serveur de base de données"); // L65
define("DB_PASS_LABEL", "Mot de passe"); // L52
define("DB_PASS_HELP", "Password of your database user account"); // L68
define("DB_NAME_LABEL", "Database name"); // L29
define("DB_NAME_HELP", "The name of database on the host. The installer will attempt to create a database if one does not exist"); // L64
define("DB_CHARSET_LABEL", "Database character set, we STRONGLY recommend you to use UTF-8 as default.");
define("DB_CHARSET_HELP", "MySQL includes character set support that enables you to store data using a variety of character sets and perform comparisons according to a variety of collations.");
define("DB_COLLATION_LABEL", "Database collation");
define("DB_COLLATION_HELP", "A collation is a set of rules for comparing characters in a character set.");
define("DB_PREFIX_LABEL", "Table prefix"); // L30
define("DB_PREFIX_HELP", "This prefix will be added to all new tables created to avoid name conflicts in the database. If you are unsure, just keep the default"); // L63
define("DB_PCONNECT_LABEL", "Use persistent connection"); // L54

define("DB_SALT_LABEL", "Password Salt Key"); // L98
define("DB_SALT_HELP", "This salt key will be appended to passwords in function icms_encryptPass(), and is used to create a totally unique secure password. Do Not change this key once your site is live, doing so will render ALL passwords invalid. If you are unsure, just keep the default"); // L97

define("LEGEND_ADMIN_ACCOUNT", "Administrator account");
define("ADMIN_LOGIN_LABEL", "Admin login"); // L37
define("ADMIN_EMAIL_LABEL", "Admin e-mail"); // L38
define("ADMIN_PASS_LABEL", "Admin password"); // L39
define("ADMIN_CONFIRMPASS_LABEL", "Confirm password"); // L74
define("ADMIN_SALT_LABEL", "Password Salt Key"); // L99

// Buttons
define("BUTTON_PREVIOUS", "<< Pr&eacute;c&eacute;dent"); // L42
define("BUTTON_NEXT", "Proch"); // L47
define("BUTTON_FINISH", "Terminer");
define("BUTTON_REFRESH", "Actualiser");
define("BUTTON_SHOW_SITE", "Show my site");

// Messages
define("XOOPS_FOUND", "%s found");
define("CHECKING_PERMISSIONS", "Checking file and directory permissions..."); // L82
define("IS_NOT_WRITABLE", "%s is NOT writable."); // L83
define("IS_WRITABLE", "%s is writable."); // L84
define("ALL_PERM_OK", "All Permissions are correct.");

define("READY_CREATE_TABLES", "No ImpressCMS tables were detected.<br />The installer is now ready to create the ImpressCMS system tables.<br />Press <em>next</em> to proceed.");
define("XOOPS_TABLES_FOUND", "The ImpressCMS system tables already exists in your database.<br />Press <em>next</em> to go to the next step."); // L131
define("READY_INSERT_DATA", "The installer is now ready to insert initial data into your database.");
define("READY_SAVE_MAINFILE", "The installer is now ready to save the specified settings to <em>.env</em>.<br />Press <em>next</em> to proceed.");
define("DATA_ALREADY_INSERTED", "ImpressCMS data is stored in your database already. No further data will be stored by this action.<br />Press <em>next</em> to go to the next step.");

// %s is database name
define("DATABASE_CREATED", "Database %s created!"); // L43
// %s is table name
define("TABLE_NOT_CREATED", "Unable to create table %s"); // L118
define("TABLE_CREATED", "Table %s cr&eacute;er."); // L45
define("ROWS_INSERTED", "%d entries inserted to table %s."); // L119
define("ROWS_FAILED", "Failed inserting %d entries to table %s."); // L120
define("TABLE_ALTERED", "Table %s updated."); // L133
define("TABLE_NOT_ALTERED", "Failed updating table %s."); // L134
define("TABLE_DROPPED", "Table %s dropped."); // L163
define("TABLE_NOT_DROPPED", "Failed deleting table %s."); // L164

// Error messages
define("ERR_COULD_NOT_ACCESS", "Could not access the specified folder. Please verify that it exists and is readable by the server.");
define("ERR_NO_XOOPS_FOUND", "No ImpressCMS installation could be found in the specified folder.");
define("ERR_INVALID_EMAIL", "Addresse email invalide"); // L73
define("ERR_REQUIRED", "Merci de saisir toutes les donn&eacute;es requises."); // L41
define("ERR_PASSWORD_MATCH", "Les deux mots de passe ne correspondent pas");
define("ERR_NEED_WRITE_ACCESS", "Le serveur doit avoir un accès en écriture aux fichiers et dossiers suivants<br />(i.e. <em>chmod 777 directory_name</em> sur un serveur UNIX/LINUX)"); // L72
define("ERR_NO_DATABASE", "Impossible de créer la base de données. Contactez l'administrateur du serveur pour plus de détails."); // L31
define("ERR_NO_DBCONNECTION", "Impossible de se connecter au serveur de base de données."); // L106
define("ERR_WRITING_CONSTANT", "Échec d'écriture de la constante %s."); // L122
define('ERR_WRITE_ENV_DATA', 'Erreur lors de l\'écriture des données .env');
define("ERR_INVALID_DBCHARSET", "Le jeu de caractères '%s' n'est pas supporté.");
define("ERR_INVALID_DBCOLLATION", "Le collationnement '%s' n'est pas supporté.");
define("ERR_CHARSET_NOT_SET", "Le jeu de caractères par défaut n'est pas défini pour la base de données ImpressCMS.");

//
define("_INSTALL_SELECT_MODS_INTRO", 'Dans la liste ci-dessous, veuillez sélectionner les modules que vous souhaitez installer sur ce site. <br /><br />
Tous les modules installés sont accessibles par défaut par le groupe Administrateurs et le groupe Utilisateurs Enregistrés. <br /><br />
Si vous avez besoin de définir les permissions pour les utilisateurs anonymes, veuillez le faire dans le panneau d\'administration après avoir terminé cet installateur. <br /><br />
Pour plus d\'informations concernant l\'administration du groupe, veuillez visiter le <a href="https://www.impresscms.org/modules/simplywiki/index.php?page=Permissions" rel="external">wiki</a>.');

define("_INSTALL_SELECT_MODULES", 'Sélectionner les modules à installer');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Sélectionnez les modules visibles aux visiteurs');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Le module %s n'a pas pu être installé.");
define("_INSTALL_ERRORS", 'Erreurs');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Le module %s a déjà été installé");
define("_INSTALL_FAILED_TO_EXECUTE", "Failed to execute ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Executed correctly");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Module %s has been installed succesfully.");
define("_INSTALL_MOD_INSTALL_FAILED", "The wizard could not install module %s.");
define("_INSTALL_INSTALLING", "Installing %s module");

define("_INSTALL_WEB_LOCATIONS", "Web location");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Web location");

define("_INSTALL_COULD_NOT_INSERT", "The wizard was unable to install module %s the database.");
define("_INSTALL_CHARSET", "utf-8");

define("_INSTALL_PHYSICAL_PATH", "Physical path");

define("_MD_AM_MULTLOGINMSG_TXT", 'Impossible de vous connecter au site!! <br />
        <p align="left" style="color:red;">
        Causes possibles:<br />
         - Vous ');
define("_INSTALL_LOCAL_SITE", 'http://www.impresscms.org/'); //Link to local support site
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_ADM_USE_RTL", "0"); // turn this to 1 if your language is right to left

######################## Added in 1.2 ###################################
define("ADMIN_DISPLAY_LABEL", "Admin Display Name"); // L37
define('_CORE_PASSLEVEL1', 'Trop court');
define('_CORE_PASSLEVEL2', 'Faible');
define('_CORE_PASSLEVEL3', 'Bon');
define('_CORE_PASSLEVEL4', 'Fort');
define('DB_PCONNECT_HELP', "Persistent connections are useful with slower internet connections. They are not generally required for most installations. Default is 'NO'. Choose 'NO' if you are unsure"); // L69
define("DB_PCONNECT_HELPS", "Persistent connections are useful with slower internet connections. They are not generally required for most installations."); // L69

// Added in 1.3
define("FILE_PERMISSIONS", "File Permissions");
