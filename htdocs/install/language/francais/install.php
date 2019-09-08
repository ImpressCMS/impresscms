<?php

define( "SHOW_HIDE_HELP", "Afficher/masquer l'aide" );
define ("ALTERNATE_LANGUAGE_MSG","T&eacute;l&eacute;chargez une langue suppl&eacute;mentaire du site ImpressCMS");
define ("ALTERNATE_LANGUAGE_LNK_MSG", "Choisissez une autre langue");
define ("ALTERNATE_LANGUAGE_LNK_URL", "https://sourceforge.net/projects/impresscms/files/ImpressCMS%20Languages/");
// Vérifier la page de configuration
define( "SERVER_API", "Server API" );
define( "PHP_EXTENSION", "%s extension" );
define( "CHAR_ENCODING", "Encodage des caract&egrave;res" );
define( "XML_PARSING", "l'analyse XML" );
define( "OPEN_ID", "OpenID" );
define( "REQUIREMENTS", "Exigences" );
define( "_PHP_VERSION", "version PHP" );
define( "RECOMMENDED_SETTINGS", "Configuration recommand&eacute;e" );
define( "RECOMMENDED_EXTENSIONS", "Extensions recommand&eacute;e" );
define( "SETTING_NAME", "Nom de la configuration" );
define( "RECOMMENDED", "Recommand&eacute;" );
define( "CURRENT", "Actif" );
define( "RECOMMENDED_EXTENSIONS_MSG", "Ces extensions ne sont pas obligatoire pour une utilisation normale, mais peuvent le devenir pour tirer avantage de certaines fonctionnalit&eacute;s (comme le multi-langues ou le support RSS). Il est donc recommand&eacute; qu'ils soient install&eacute;s." );
define( "NONE", "Aucun" );
define( "SUCCESS", "Succ&egrave;s" );
define( "WARNING", "Avertissement" );
define( "FAILED", "&Eacute;chec" );

// Titres (principaux et les pages)
define( "XOOPS_INSTALL_WIZARD", " Assistance d'installation %s" );
define( "INSTALL_STEP", "&Eacute;tape" );
define( "INSTALL_H3_STEPS", "&Eacute;tapes" );
define( "INSTALL_OUTOF", " sur " );
define( "INSTALL_COPYRIGHT", "Copyright &copy; 2007-" . date('Y', time()) . " <a href=\"http://www.impresscms.org\">Le projet ImpressCMS</a>" );

define( "LANGUAGE_SELECTION", "S&eacute;lection de la langue" );
define( "LANGUAGE_SELECTION_TITLE", "Choisissez la langue de l'assistance");	
define( "INTRODUCTION", "Introduction" );
define( "INTRODUCTION_TITLE", "Bienvenue dans l'assistance d'installation" );	
define( "CONFIGURATION_CHECK", "Configuration du serveur" );
define( "CONFIGURATION_CHECK_TITLE", "V&eacute;rification de la configuration de votre serveur" );
define( "PATHS_SETTINGS", "Configuration des chemins" );
define( "PATHS_SETTINGS_TITLE", "Configuration des chemins" );
define( "DATABASE_CONNECTION", "Connection a la base de donn&eacute;es" );
define( "DATABASE_CONNECTION_TITLE", "Connection a la base de donn&eacute;es" );
define( "DATABASE_CONFIG", "Base de donn&eacute;es" );
define( "DATABASE_CONFIG_TITLE", "Configuration de la base de donn&eacute;es" );
define( "CONFIG_SAVE", "Sauvegarde de la configuration" );
define( "CONFIG_SAVE_TITLE", "Sauvegarde des configuration de votre site" );
define( "TABLES_CREATION", "Cr&eacute;ation des tables" );
define( "TABLES_CREATION_TITLE", "Cr&eacute;ation des tables" );
define( "INITIAL_SETTINGS", "Configuration initiale" );
define( "INITIAL_SETTINGS_TITLE", "Veuillez entrer votre configuration initiale" );
define( "DATA_INSERTION", "Insertion de donn&eacute;es" );
define( "DATA_INSERTION_TITLE", "Sauvegarde de votre configuration dans la base de donn&eacute;es" );
define( "WELCOME", "Bienvenue" );
define( "NO_PHP5", "No PHP 5" );
define( "WELCOME_TITLE", "Installation du site compl&eacute;te" );		// L0
define( "MODULES_INSTALL", "Installation des scripts" );
define( "MODULES_INSTALL_TITLE", "Installation des scripts " );
define( "NO_PHP5_TITLE", "No PHP 5" );
define( "NO_PHP5_CONTENT","PHP 5 is required for ImpressCMS to function properly - your installation cannot continue. Please work with your hosting provider to upgrade your environment to PHP5 before attempting to install again. For more information, read <a href='http://community.impresscms.org/modules/smartsection/item.php?itemid=122' >ImpressCMS on PHP5 </a>.");
define( "SAFE_MODE", "Safe Mode On" );
define( "SAFE_MODE_TITLE", "Safe Mode On" );
define( "SAFE_MODE_CONTENT", "ImpressCMS has detected PHP is running in Safe Mode. Because of this, your installation cannot continue. Please work with your hosting provider to change your environment before attempting to install again." );

// Settings (labels and help text)
define( "XOOPS_ROOT_PATH_LABEL", "Chemin physique de la racine de votre site" ); // L55
define( "XOOPS_ROOT_PATH_HELP", "C'est est le chemin de la racine de votre site" ); // L59
define( "_INSTALL_TRUST_PATH_HELP", "C'est le chemin physique du dossier de s&eacute;curit&eacute; (trust path) de votre site. Le dossier de s&eacute;curit&eacute; est un dossier o&ugrave; certains scripts est sauvegard&eacute; (donn&eacute;es et fichiers sensibles). Il est recommand&eacute; que ce dossier soit &agrave; l'ext&eacute;rieur de la racine web (donc non accesible par un navigateur). Si le dossier n'existe pas, l'assistant d'installation tentera de la cr&eacute;&eacute;. Si c'est impossible, vous devrez le cr&eacute;er manuellement." ); // L59

define( "XOOPS_URL_LABEL", "Adresse du site (URL)" ); // L56
define( "XOOPS_URL_HELP", "Adresse principale de votre site" ); // L58

define( "LEGEND_CONNECTION", "Connection au serveur" );
define( "LEGEND_DATABASE", "Base de donn&eacute;es" ); // L51

define( "DB_HOST_LABEL", "Nom du serveur (Habituellement localhost ou un nom d'hôte fourni par votre hebergeur)" );	// L27
define( "DB_HOST_HELP",  "Nom du serveur de la base de donn&eacute;es. Si vous n'&ecirc;tes pas s&ucirc;r, <em>localhost</em> fonctionne dans la plupart des cas"); // L67
define( "DB_USER_LABEL", " Nom d'utilisateur (Soit root ou un identifiant fourni par votre hebergeur)" );	// L28
define( "DB_USER_HELP",  "Nom pour se connecter au serveur de la base de donn&eacute;es"); // L65
define( "DB_PASS_LABEL", "Mot de passe (mot de passe utiliser pour acceder a votre base de donnees.)");	// L52
define( "DB_PASS_HELP",  "Mot de passe pour se connecter au serveur de la base de donn&eacute;es"); // L68
define( "DB_NAME_LABEL", "Nom de la base de donnees" );	// L29
define( "DB_NAME_HELP",  "Nom de la base de donn&eacute;es &agrave; utilis&eacute;. L'assistance d'installation tentera de cr&eacute;er cette base de don&eacute;&eacute;es si elle n'existe pas."); // L64
define( "DB_CHARSET_LABEL", "Code ISO du site, nous vous recommandons vivement d'utiliser l'UTF-8 par defaut." );
define( "DB_CHARSET_HELP",  "MySQL inclut le code ISO qui vous permet de stocker des donn&eacute;es en utilisant une vari&eacute;t&eacute; de code ISO et d'effectuer des comparaisons.");
define( "DB_COLLATION_LABEL", "Code ISO specifique au pays (default recommander)" );
define( "DB_COLLATION_HELP",  "Code ISO sp&eacute;cifique au pays est une s&eacute;rie de r&egrave;gles suivant l'encodage des caract&egrave;res.");
define( "DB_PREFIX_LABEL", "Prefixe des tables" );	// L30
define( "DB_PREFIX_HELP",  "Ce prefixe sera ajout&eacute; &agrave; toutes tables cr&eacute;&eacute;es afin d'&eacute;viter les conflits de noms de tables dans la base de donn&eacute;es. Si vous n'&ecirc;tes pas sur, veuillez utiliser ce qui vous est propos&eacute; (pr&eacute;fix g&eacute;n&eacute;r&eacute;s al&eacute;atoirement)"); // L63
define( "DB_PCONNECT_LABEL", "Utiliser les connexions persistentes ?" );	// L54
define( "DB_PCONNECT_HELP",  "Par d&eacute;faut c'est 'NON'. Choisissez 'NON' si vous n'&ecirc;tes pas s&ucirc;r."); // L69
define( "DB_PCONNECT_HELPS", "Les connexions persistentes sont utiles sur des liens internet lents. En générale, ils ne sont pas nécessaire pour la plupart des installations."); // L69


define( "DB_SALT_LABEL", "La Clef cryptee de mot de passe" );	// L98
define( "DB_SALT_HELP",  "Cette clef crypt&eacute;e sera ajout&eacute;e aux mots de passe dans la fonction icms_encryptPass(), et est utilis&eacute; pour s'assur&eacute; de cr&eacute;er un mot de passe totalement unique. Ne Pas changer cette clef une fois que votre site est cr&eacute;er, sinon cela rendra TOUT les mots de passe invalide. Si vous ne savez pas, garder la clef par d&eacute;faut"); // L97

define( "LEGEND_ADMIN_ACCOUNT", "Compte de l'administrateur du site" );
define( "ADMIN_LOGIN_LABEL", "Nom de l'administrateur du site" ); // L37
define( "ADMIN_EMAIL_LABEL", "E-mail de l'administrateur du site" ); // L38
define( "ADMIN_PASS_LABEL", "Mot de passe" ); // L39
define( "ADMIN_CONFIRMPASS_LABEL", "Confirmer le mot de passe" ); // L74
define( "ADMIN_SALT_LABEL", "La Clef de Sel de mot de passe" ); // L99

// Buttons
define( "BUTTON_PREVIOUS", "Pr&eacute;c&eacute;dent" ); // L42
define( "BUTTON_NEXT", "Suivant" ); // L47
define( "BUTTON_FINISH", "Terminer" );
define( "BUTTON_REFRESH", "Refraichir" );
define( "BUTTON_SHOW_SITE", "Aller &agrave; au site" );

// Messages
define( "XOOPS_FOUND", "%s trouv&eacute;" );
define( "CHECKING_PERMISSIONS", "V&eacute;rification des permissions des fichiers et dossiers..." ); // L82
define( "IS_NOT_WRITABLE", "%s n'est pas ouvert en &eacute;criture." ); // L83
define( "IS_WRITABLE", "%s est ouvert en &eacute;criture." ); // L84
define( "ALL_PERM_OK", "Toutes les permissions sont correctement configur&eacute;es." );

define( "READY_CREATE_TABLES", "Aucune tables du site n'a &eacute;t&eacute; d&eacute;tect&eacute;.<br />L'assistance d'installation est maintenant pr&ecirc;t &agrave; cr&eacute;er les tables syst&egrave;mes du site.<br />Cliquez sur <em>Suivant</em> pour continuer." );
define( "XOOPS_TABLES_FOUND", "Les tables syst&egrave;mes du site existent d&eacute;j&agrave; dans votre base de donn&eacute;es.<br />Cliquez sur <em>Suivant</em> pour aller &agrave; l'&eacute;tape suivante." ); // L131
define( "READY_INSERT_DATA", "L'assistance d'installation est maintenant pr&ecirc;t &agrave; ins&eacute;rer les donn&eacute;es initiales dans votre base de donn&eacute;es." );
define( "READY_SAVE_MAINFILE", "L'assistance d'installation  est maintenant pr&ecirc;t &agrave; sauvegarder la configuration sp&eacute;cifi&eacute;e dans le fichier <em>mainfile.php</em>.<br />Cliquez sur <em>Suivant</em> pour continuer." );
define( "DATA_ALREADY_INSERTED", "Les donn&eacute;es du site sont d&eacute;j&agrave; sauveagrd&eacute;es dans votre base de donn&eacute;es.<br />Cliquez sur <em>Suivant</em> pour continuer." );


// %s is database name
define( "DATABASE_CREATED", "Base de donn&eacute;es %s cr&eacute;&eacute;e!" ); // L43
// %s is table name
define( "TABLE_NOT_CREATED", "Impossible de cr&eacute;er la table %s" ); // L118
define( "TABLE_CREATED", "Table %s cr&eacute;&eacute;e." ); // L45
define( "ROWS_INSERTED", "%d enregistrements ins&eacute;r&eacute;s dans la table %s." ); // L119
define( "ROWS_FAILED", "&Eacute;chec &agrave; l'insertion de %d enregistrements dans la table %s." ); // L120
define( "TABLE_ALTERED", "Table %s mise &agrave; jour."); // L133
define( "TABLE_NOT_ALTERED", "&Eacute;chec lors de la mise &agrave; jour de la table%s."); // L134
define( "TABLE_DROPPED", "Table %s supprim&eacute;e."); // L163
define( "TABLE_NOT_DROPPED", "&Eacute;chec lors de la suppresion de la table %s."); // L164

// Error messages
define( "ERR_COULD_NOT_ACCESS", "Impossible d'acc&eacute;der au dossier sp&eacute;cifi&eacute;. Veuillez v&eacute;rifier qu'il existe et qu'il est ouvert en &eacute;criture sur le serveur." );
define( "ERR_NO_XOOPS_FOUND", "Aucune installation du site trouv&eacute;e dans le dossier sp&eacute;cifi&eacute;." );
define( "ERR_INVALID_EMAIL", "Courriel invalide" ); // L73
define( "ERR_REQUIRED", "Veuillez entrer toutes les informations requises." ); // L41
define( "ERR_PASSWORD_MATCH", "Les deux mots de passe ne concordent pas" );
define( "ERR_NEED_WRITE_ACCESS", "Le serveur doit avoir acc&egrave;s en &eacute;criture aux dossiers et fichiers suivants<br />(i.e. <em>chmod 777 nom_dossier</em> sur un syst&egrave;me UNIX/LINUX)" ); // L72
define( "ERR_NO_DATABASE", "Impossible de cr&eacute;er la base de donn&eacute;es. Veuillez contacter votre h&eacute;bergeur pour plus de d&eacute;tails." ); // L31
define( "ERR_NO_DBCONNECTION", "Impossible de se connecter à la base de donn&eacute;es." ); // L106
define( "ERR_WRITING_CONSTANT", "&Eacute;chec &agrave; l'&eacute;criture de la constante %s." ); // L122

define( "ERR_COPY_MAINFILE", "Impossible de copier le fichier de distribution dans mainfile.php" );
define( "ERR_WRITE_MAINFILE", "Impossible d'&eacute;crire dans mainfile.php. V&eacute;rifiez les permissions et essayez &agrave; nouveau.");
define( "ERR_READ_MAINFILE", "Impossible d'ouvrir mainfile.php en lecture." );

define( "ERR_WRITE_SDATA", "Impossible d'&eacute;crire dans sdata.php. V&eacute;rifiez les permissions et essayez &agrave; nouveau.");
define( "ERR_READ_SDATA", "Impossible d'ouvrir sdata.php en lecture" );
define( "ERR_INVALID_DBCHARSET", "Le charset '%s' est non support&eacute;." );
define( "ERR_INVALID_DBCOLLATION", "La collation '%s' est non support&eacute;." );
define( "ERR_CHARSET_NOT_SET", "les codes ISO ne sont pas réglée pour la base de données." );


//
define("_INSTALL_SELECT_MODS_INTRO", "Dans la bo&icirc;te de s&eacute;lection du cot&eacute; gauche, choisissez les scripts que vous souhaitez installer sur le site.
Tous les scripts install&eacute;s seront accessibles pour les membres enregistr&eacute;s et les administrateurs du site.
Dans le cas o&ugrave; vous souhaiteriez donner acc&egrave;s &agrave; certains scripts aux visiteurs(utilisateurs non-enregistr&eacute;s ou enregistr&eacute;s mais pas connect&eacute;s)
indiquez lesquels en les s&eacute;lectionnant dans la bo&icirc;te de s&eacute;lection de droite.");

define("_INSTALL_SELECT_MODULES", 'Choisissez les scripts que vous souhaitez installer');
define("_INSTALL_SELECT_MODULES_ANON_VISIBLE", 'Choisissez les scripts que vous souhaitez rendre visible aux visiteurs');
define("_INSTALL_IMPOSSIBLE_MOD_INSTALL", "Le script %s n'a pu &ecirc;tre install&eacute;.");
define("_INSTALL_ERRORS", 'Erreurs');
define("_INSTALL_MOD_ALREADY_INSTALLED", "Le script %s est d&eacute;j&agrave; install&eacute;");
define("_INSTALL_FAILED_TO_EXECUTE", "Erreur &agrave; l'&eacute;x&eacute;cution ");
define("_INSTALL_EXECUTED_SUCCESSFULLY", "Correctement &eacute;x&eacute;cut&eacute;");

define("_INSTALL_MOD_INSTALL_SUCCESSFULLY", "Le script %s a &eacute;t&eacute; install&eacute; avec succ&egrave;s.");
define("_INSTALL_MOD_INSTALL_FAILED", "L'assistance n'a pu installer le script %s.");
define("_INSTALL_NO_PLUS_MOD", "Aucun script n'a &eacute;t&eacute; choisi pour l'installation. Cliquez sur Suivant pour continuer.");
define("_INSTALL_INSTALLING", "Installlation du script %s");

define("_INSTALL_TRUST_PATH", "Dossier de s&eacute;curit&eacute;");
define("_INSTALL_TRUST_PATH_LABEL", "Dossier de s&eacute;curit&eacute; physique du site");
define("_INSTALL_WEB_LOCATIONS", "Emplacements Web");
define("_INSTALL_WEB_LOCATIONS_LABEL", "Emplacements Web");

define("_INSTALL_TRUST_PATH_FOUND", "Dossier de s&eacute;curit&eacute; trouv&eacute;.");
define("_INSTALL_ERR_NO_TRUST_PATH_FOUND", "Dossier de s&eacute;curit&eacute; introuvable.");

define("_INSTALL_COULD_NOT_INSERT", "L'assistance n'a pu installer le script %s dans la base de donn&eacute;e.");
define("_INSTALL_CHARSET","utf-8");

define("_INSTALL_PHYSICAL_PATH","Chemin physique");

define("TRUST_PATH_VALIDATE","Une suggestion de nom pour le dossier de s&eacute;curit&eacute; a &eacute;t&eacute; cr&eacute;&eacute;e ci-haut. Si vous le souhaitez utiliser un autre nom," .
		"remplacez-le par un autre nom.<br /><br />Cliquez ensuite sur le bouton 'Cr&eacute;er le dossier de s&eacute;curit&eacute;'.");
define("TRUST_PATH_NEED_CREATED_MANUALLY","Si il est impossible de cr&eacute;er le dossier de s&eacute;curit&eacute;. Cr&eacute;ez-le manuellement et cliquez sur le bouton" .
		"actualiser ci-bas.");
define("BUTTON_CREATE_TUST_PATH","Cr&eacute;er le dossier de s&eacute;curit&eacute;");
define("TRUST_PATH_SUCCESSFULLY_CREATED", "Le dossier de s&eacute;curit&eacute; a &eacute;t&eacute; cr&eacute;&eacute; avec succ&egrave;s.");

// welcome custom blocks
define("WELCOME_WEBMASTER","Bienvenue Webmaster !");
define("WELCOME_ANONYMOUS","Bienvenue sur votre site internet !");
define("_MD_AM_MULTLOGINMSG_TXT",'Impossible de vous connectez avec ce nom d\'utilisateur et mot de passe sur le site!! <br />
        <p align="left" style="color:red;">
        Causes possibles:<br />
         - Vous êtes déjà inscrit sur le site.<br />
         - Quelqu\'un d\'autre est connecté sur le site en utilisant votre nom d\'utilisateur et mot de passe.<br />
         - Vous avez quitter le site ou fermez la fenêtre du navigateur sans cliquer sur le bouton de déconnexion.<br />
        </p>
        Effacez les traces de votre navigateur (historique, cookies, sessions etc... Si le problème persiste contactez le webmasteur du site.');
define("_MD_AM_RSSLOCALLINK_DESC",'http://community.impresscms.org/modules/smartsection/backend.php'); //Lien vers le RRS de soutien local site
define("_INSTALL_LOCAL_SITE",'http://www.impresscms.org/'); //Lien vers le site de support local
define("_LOCAOL_STNAME",'ImpressCMS'); //Lien vers le site de support local
define("_LOCAL_SLOCGAN",'Make a lasting impression'); 
define("_LOCAL_FOOTER",'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">ImpressCMS Projet</a>'); //Lien footer (pied de page) pour le soutien local site
define("_LOCAL_SENSORTXT",'#OOPS#'); 
define("_ADM_USE_RTL","0"); // tourner à 1 si votre langue est droite à gauche
define("_DEF_LANG_TAGS",'fr,en'); 
define("_DEF_LANG_NAMES",'french,english'); 
define("_LOCAL_LANG_NAMES",'Fran&ccedil;ais,English');
define("_EXT_DATE_FUNC","0"); // changement de 0 à 1, si cette langue a une fonction étendue date


