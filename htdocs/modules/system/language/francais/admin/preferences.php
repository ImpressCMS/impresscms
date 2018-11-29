<?php
// fichier:  .../modules/install/makedata.php
//              .../modules/system//include/update.php
//Gestion des préférences des modules systéme, paramétres généraux etc...
define("_AM_DBUPDATED","Base de donn&eacute;es mise &agrave; jour avec succ&egrave;s !");
define("_MD_AM_SITEPREF","Pr&eacute;f&eacute;rences du site");
define("_MD_AM_SITENAME","Nom du site");
define("_MD_AM_SLOGAN","Slogan pour votre site");
define("_MD_AM_ADMINML","Adresse Email de l'administrateur");
define("_MD_AM_LANGUAGE","Langue du site par d&eacute;faut");
define("_MD_AM_STARTPAGE","Module pour votre page d'accueil");
define("_MD_AM_NONE","Aucun");
define("_MD_CONTENTMAN","gestionnaire de contenu");
define("_MD_AM_SERVERTZ","Fuseau horaire du serveur");
define("_MD_AM_DEFAULTTZ","Fuseau horaire par d&eacute;faut");
define("_MD_AM_DTHEME","Th&egrave;me du site par d&eacute;faut");
define("_MD_AM_THEMESET","Jeu de th&egrave;mes");
define("_MD_AM_ANONNAME","Nom donner aux utilisateurs anonymes");
define("_MD_AM_MINPASS","Longueur minimum requis pour le mot de passe");
define("_MD_AM_NEWUNOTIFY","Notifier par mail lorsqu'un nouvel utilisateur s'est enregistr&eacute; ?");
define("_MD_AM_SELFDELETE","Autoriser les membres &agrave; supprimer leur compte ?");
define("_MD_AM_LOADINGIMG","Afficher l'image: Chargement...");
define("_MD_AM_USEGZIP","Utiliser la compression gzip ?");
define("_MD_AM_UNAMELVL","S&eacute;lectionner le niveau de restriction pour le filtrage des noms des membres");
define("_MD_AM_STRICT","Strict (uniquement alpha-num&eacute;rique)");
define("_MD_AM_MEDIUM","Moyen");
define("_MD_AM_LIGHT","Permissif (recommand&eacute; pour les caract&egrave;res multi-bits)");
define("_MD_AM_USERCOOKIE","Nom du cookie utilisateur.");
define("_MD_AM_USERCOOKIEDSC","Ce cookie contient uniquement un nom utilisateur et il est sauvegard&eacute; pour un an sur le PC de l'utilisateur (s'il le d&eacute;sire). Si un utilisateur garde ce cookie dans son PC, son nom de membre sera automatiquement ins&eacute;r&eacute; dans la boite de connexion.");
define("_MD_AM_USEMYSESS","Utiliser une session personnalis&eacute;e");
define("_MD_AM_USEMYSESSDSC","S&eacute;lectionnez Oui pour personnaliser la valeur de la session &ccedil;i dessous");
define("_MD_AM_SESSNAME","Nom de la session.");
define("_MD_AM_SESSNAMEDSC","Valide uniquement lorsque l'option 'Utiliser une session personnalis&eacute;e' est active");
define("_MD_AM_SESSEXPIRE","Expiration de la session");
define("_MD_AM_SESSEXPIREDSC","Dur&eacute;e maximum de la session en minutes, uniquement si 'Utiliser une session personnalis&eacute;e' est active.");
define("_MD_AM_BANNERS","Activer l'affichage des banni&egrave;res?");
define("_MD_AM_MYIP","Votre adresse IP");
define("_MD_AM_MYIPDSC","Cette IP ne sera pas compt&eacute;e pour l'affichage des banni&egrave;res");
define("_MD_AM_ALWDHTML","Autoris&eacute;es le code HTML dans tous les postes ?");
define("_MD_AM_INVLDMINPASS","ERREUR ! respectez la longueur minimum du mot de passe.");
define("_MD_AM_INVLDUCOOK","ERREUR ! respectez le nom du cookie utilisateur.");
define("_MD_AM_INVLDSCOOK","ERREUR ! respectez le nom du cookie de session.");
define("_MD_AM_INVLDSEXP","ERREUR ! respectez l'expiration de la session.");
define("_MD_AM_ADMNOTSET","L'Email de l'administrateur n'est pas saisi.");
define("_MD_AM_YES","Oui");
define("_MD_AM_NO","Non");
define("_MD_AM_DONTCHNG","Ne pas changer !");
define("_MD_AM_REMEMBER","Attention ! faire un chmod 666 sur ce fichier pour permettre au syst&egrave;me d'y &eacute;crire correctement.");
define("_MD_AM_IFUCANT","Si vous ne changez pas les permissions vous pouvez modifier ce fichier manuellement.");
define("_MD_AM_COMMODE","Affichage par d&eacute;faut des commentaires");
define("_MD_AM_COMORDER","Ordre d'affichage des commentaires");
define("_MD_AM_ALLOWHTML","Autoriser les balises HTML dans les commentaires utilisateurs ?");
define("_MD_AM_DEBUGMODE","Mode de mise au point (Debug)");
define("_MD_AM_DEBUGMODEDSC","Vous pouvez choisir entre plusieurs options de debuggage. Un site Web en ligne doit avoir ce mode sur inactif, car tout le monde pourra visualiser ces messages d'erreurs.");
define("_MD_AM_AVATARALLOW","Autoriser le t&eacute;l&eacute;chargement d'avatar personnel ?");
define('_MD_AM_AVATARMP','Envois minimum requis');
define('_MD_AM_AVATARMPDSC',"Nombre minimum de posts requis pour t&eacute;l&eacute;charger un avatar personnel");
define("_MD_AM_AVATARW","Largeur maximum de l'avatar en pixels: ");
define("_MD_AM_AVATARH","Hauteur maximum de l'avatar en pixels:");
define("_MD_AM_AVATARMAX","Poids maximum de l'avatar en octets:");
define("_MD_AM_AVATARCONF","Param&egrave;tres des avatars personnels");
define("_MD_AM_CHNGUTHEME","Changer tous les th&egrave;mes des utilisateurs");
define("_MD_AM_NOTIFYTO","Choisissez le groupe auquel l'Email de notification d'un nouveau membre sera envoy&eacute;");
define("_MD_AM_ALLOWTHEME","Autoriser les utilisateurs &agrave; s&eacute;lectionner un th&egrave;me ?");
define("_MD_AM_ALLOWIMAGE","Autoriser les utilisateurs &agrave; afficher des fichiers images dans leurs envois ?");
define("_MD_AM_USERACTV","Activation par l'utilisateur requis (recommand&eacute;)");
define("_MD_AM_AUTOACTV","Activation automatique");
define("_MD_AM_ADMINACTV","Activation par les administrateurs");
define("_MD_AM_REGINVITE","Inscription sur invitation");
define("_MD_AM_ACTVTYPE","S&eacute;lectionnez le type d'activation pour les membres nouvellement enregistr&eacute;s");
define("_MD_AM_ACTVGROUP","Choisissez le groupe auquel le mail d'activation doit &ecirc;tre envoy&eacute;");
define("_MD_AM_ACTVGROUPDSC","Valide uniquement lorsque l'option 'Activation par les administrateurs' est s&eacute;lectionn&eacute;e");
define('_MD_AM_USESSL', 'Utiliser le SSL pour se connecter ?');
define('_MD_AM_SSLPOST', 'Nom de la variable SSL');
define('_MD_AM_SSLPOSTDSC', 'Nom de la variable utilis&eacute;e pour la valeur de session en mode POST. Si vous ne savez pas quoi mettre, inventez un nom difficilement reconnaissable.');
define('_MD_AM_DEBUGMODE0','Inactif');
define('_MD_AM_DEBUGMODE1','Activer le mode debug en ligne');
define('_MD_AM_DEBUGMODE2','Activer le mode debug en popup');
define('_MD_AM_DEBUGMODE3','Activer le mode debug des templates Smarty');
define('_MD_AM_MINUNAME', 'Longueur minimum requis pour le nom d\'un membre');
define('_MD_AM_MAXUNAME', 'Longueur maximum requis pour le nom d\'un membre');
define('_MD_AM_GENERAL', 'Param&egrave;tres g&eacute;n&eacute;raux');
define('_MD_AM_USERSETTINGS', 'Param&egrave;tres des infos des utilisateurs');
define('_MD_AM_ALLWCHGMAIL', 'Autoriser les utilisateurs &agrave; changer leur adresse Email ?');
define('_MD_AM_ALLWCHGMAILDSC', '');
define('_MD_AM_IPBAN', 'IP Interdites');
define('_MD_AM_BADEMAILS', "Entrez les Emails qui ne doivent pas &ecirc;tre employ&eacute;s dans les profils utilisateurs");
define('_MD_AM_BADEMAILSDSC', 'Les s&eacute;parer par un <b>|</b>. Attention ! Ne jamais terminer par |');
define('_MD_AM_BADUNAMES', 'Noms et pseudo qui ne doivent pas &ecirc;tre employ&eacute;s par les utilisateurs.');
define('_MD_AM_BADUNAMESDSC', 'Les s&eacute;parer par un <b>|^</b>, Attention ! Ne jamais terminer par | ou |^');
define('_MD_AM_DOBADIPS', "Activer le bannissement d'IP?");
define('_MD_AM_DOBADIPSDSC', "Les utilisateurs des adresses IP indiqu&eacute;es seront bannis de votre site");
define('_MD_AM_BADIPS', 'Adresses IP qui seront bannies de ce site.<br />Les s&eacute;parer par un <b>|');
define('_MD_AM_BADIPSDSC', "^aaa.bbb.ccc bannira les visiteurs dont l'IP commence par aaa.bbb.ccc<br />aaa.bbb.ccc$ bannira les visiteurs dont l'IP se termine par aaa.bbb.ccc<br />aaa.bbb.ccc bannira les visiteurs dont l'IP contient aaa.bbb.ccc");
define('_MD_AM_PREFMAIN', 'Pr&eacute;f&eacute;rences du site');
define('_MD_AM_METAKEY', 'M&eacute;ta keywords');
define('_MD_AM_METAKEYDSC', 'Le champ m&eacute;ta keywords est une s&eacute;rie de mots-cl&eacute;s pour le r&eacute;f&eacute;rencement du site dans les moteurs de recherche Google par exemple. Tapez les mots-cl&eacute;s correspondant aux sujets du site s&eacute;par&eacute;s les par une virgule et un espace entre deux mots cl&eacute;s. (Ex. chien, chat, animaux, croquettes)');
define('_MD_AM_METARATING', 'M&eacute;ta rating');
define('_MD_AM_METARATINGDSC', "Le champ m&eacute;ta rating autorise l'acc&egrave;s a ce site aux visiteurs ayant l'&acirc;ge minimum requis ");
define('_MD_AM_METAOGEN', 'G&eacute;n&eacute;ral');
define('_MD_AM_METAO14YRS', '14 ans');
define('_MD_AM_METAOREST', 'Limit&eacute;');
define('_MD_AM_METAOMAT', 'Adulte');
define('_MD_AM_METAROBOTS', 'M&eacute;ta robots');
define('_MD_AM_METAROBOTSDSC', 'Le champ m&eacute;ta robots d&eacute;clare aux moteurs de recherche quel contenu indexer');
define('_MD_AM_INDEXFOLLOW', 'Indexer, suivre');
define('_MD_AM_NOINDEXFOLLOW', 'Ne pas indexer, suivre');
define('_MD_AM_INDEXNOFOLLOW', 'Indexer, ne pas suivre');
define('_MD_AM_NOINDEXNOFOLLOW', 'Ne pas indexer, ne pas suivre');
define('_MD_AM_METAAUTHOR', 'M&eacute;ta auteur');
define('_MD_AM_METAAUTHORDSC', "La balises auteur d&eacute;finit le nom de l'auteur des documents qui seront lus. Les formats de donn&eacute;es support&eacute;s incluent le nom, l'adresse e-mail du Webmestre, le nom de l'entreprise ou l'URL.");
define('_MD_AM_METACOPYR', 'M&eacute;ta copyright');
define('_MD_AM_METACOPYRDSC', "Le champ m&eacute;ta copyright ajoute les droits d'auteur souhaiter au site et aux documents du site.");
define('_MD_AM_METADESC', 'M&eacute;ta description');
define('_MD_AM_METADESCDSC', 'Le champ m&eacute;ta description c\'est une description assez courte du contenu g&eacute;n&eacute;rale du site.');
define('_MD_AM_METAFOOTER', 'Les champs m&eacute;tas et bas de page');
define('_MD_AM_FOOTER', 'Bas de page du site');
define('_MD_AM_FOOTERDSC', 'Textes, liens ou images qui s\'afficheront en bas de toutes les pages du site');
define('_MD_AM_CENSOR', 'Censure des mots ind&eacute;sirables');
define('_MD_AM_DOCENSOR', 'Activer la censure des mots ind&eacute;sirables ?');
define('_MD_AM_DOCENSORDSC', 'Mots qui doivent &ecirc;tre automatiquement censur&eacute;s sur le site. Cette option peut &ecirc;tre arr&ecirc;t&eacute;e pour accro&icirc;tre la vitesse du site.');
define('_MD_AM_CENSORWRD', 'Mots &agrave; censurer');
define('_MD_AM_CENSORWRDDSC', 'Mots qui seront censur&eacute;s dans les posts, commentaires et autres des utilisateurs.<br />S&eacute;parer les par un <b>|</b>. Ne rien mettre &agrave; la fin de la liste !');
define('_MD_AM_CENSORRPLC', 'Mots censur&eacute;s seront remplac&eacute;s par:');
define('_MD_AM_CENSORRPLCDSC', 'Les mots censur&eacute;s seront remplac&eacute;s par les caract&egrave;res entr&eacute;s dans cette zone de texte');

define('_MD_AM_SEARCH', 'Param&egrave;tres des recherches');
define('_MD_AM_DOSEARCH', 'Activer la recherche globale ?');
define('_MD_AM_DOSEARCHDSC', "Autorise la recherche d'un &eacute;l&eacute;ments sur tout le site.");
define('_MD_AM_MINSEARCH', 'Longueur minimum des mots');
define('_MD_AM_MINSEARCHDSC', 'Longueur minimum des mots saisi par les utilisateurs pour effectuer une recherche');
define('_MD_AM_MODCONFIG', 'Options de configuration des modules');
define('_MD_AM_DSPDSCLMR', 'Activer les conditions nouveau compte ?');
define('_MD_AM_DSPDSCLMRDSC', "Affiche les conditions que doit accepter le nouvel utilisateur pour cr&eacute;er son compte.");
define('_MD_AM_REGDSCLMR', "Conditions d'ouverture d'un compte");
define('_MD_AM_REGDSCLMRDSC', "Tapez vos conditions:");
define('_MD_AM_ALLOWREG', "Autoriser l'enregistrement de nouveaux utilisateurs ?");
define('_MD_AM_ALLOWREGDSC', "Acceptez ou refusez l'enregistrement de nouveaux utilisateurs sur le site");
define('_MD_AM_THEMEFILE', 'Mise &agrave; jour du th&egrave;mes ?');
define('_MD_AM_THEMEFILEDSC', "Oui pour que les modifications r&eacute;cente dans les fichiers du th&egrave;mes soit pris en compte sur le site.<br />Attention ! remettre sur <b>non</b> lorsque les modifications ont &eacute;t&eacute; pris en compte (s&eacute;curit&eacute; et vitesse).");
define('_MD_AM_CLOSESITE', 'Arr&ecirc;ter le site ?');
define('_MD_AM_CLOSESITEDSC', "Arr&ecirc;t du site le temps d'effectuer des modifications. Seuls les groupes s&eacute;lectionn&eacute;s dessous pourront acc&eacute;der au site.");
define('_MD_AM_CLOSESITEOK', "Groupes autoris&eacute;s &agrave; acc&eacute;der au site lorsqu'il est arr&ecirc;t&eacute;");
define('_MD_AM_CLOSESITEOKDSC', "Le groupe webmasters &agrave; toujours acc&egrave;s au site m&ecirc;me en mode arr&ecirc;t.");
define('_MD_AM_CLOSESITETXT', "Raison de l'arr&ecirc;t du site");
define('_MD_AM_CLOSESITETXTDSC', 'Texte de la page en maintenance qui sera afficher aux publics quand le site est en mode arr&ecirc;t.');
define('_MD_AM_SITECACHE', 'Param&egrave;tres du cache du site');
define('_MD_AM_SITECACHEDSC', "Mise en cache du contenu du site pour un temps indiqu&eacute; afin d'augmenter la vitesse d'affichage. La mise en cache large du site ignorera le cache au niveau des modules, le cache au niveau des blocs et le cache au niveau du module des articles.");
define('_MD_AM_MODCACHE', ' Param&egrave;tres du cache des modules');
define('_MD_AM_MODCACHEDSC', 'Mettre en cache le contenu des modules pour un temps indiqu&eacute; afin d\'augmenter la vitesse d\'affichage. <br>Cette mise en cache ignorera le cache au niveau du module des articles.');
define('_MD_AM_NOMODULE', "Il n'y a pas de modules qui peuvent &ecirc;tre mis en cache.");
define('_MD_AM_DTPLSET', 'Jeu de templates par d&eacute;faut');
define('_MD_AM_SSLLINK', 'URL pour la page de la connexion SSL');
define("_MD_AM_MAILER","Param&egrave;tre Email");
define("_MD_AM_MAILER_MAIL","");
define("_MD_AM_MAILER_SENDMAIL","");
define("_MD_AM_MAILER_","");
define("_MD_AM_MAILFROM","adresse:");
define("_MD_AM_MAILFROMDESC","");
define("_MD_AM_MAILFROMNAME","nom:");
define("_MD_AM_MAILFROMNAMEDESC","");
define("_MD_AM_MAILFROMUID","Nom de l'exp&eacute;diteur:");
define("_MD_AM_MAILFROMUIDDESC","");
define("_MD_AM_MAILERMETHOD","Mode d'envoi d'un mail");
define("_MD_AM_MAILERMETHODDESC","Par d&eacute;faut c'est 'PHP mail', utiliser un autre mode d'envoi uniquement en cas de probl&egrave;mes.");
define("_MD_AM_SMTPHOST","H&ocirc;te(s) SMTP");
define("_MD_AM_SMTPHOSTDESC","Liste des serveurs SMTP pour essayer de se connecter.");
define("_MD_AM_SMTPUSER","Nom utilisateur SMTPAuth");
define("_MD_AM_SMTPUSERDESC","Nom utilisateur pour se connecter &agrave; l'h&ocirc;te STMP avec SMTPAuth.");
define("_MD_AM_SMTPPASS","Mot de passe SMTPAuth");
define("_MD_AM_SMTPPASSDESC","Mot de passe pour se connecter &agrave; l'h&ocirc;te STMP avec SMTPAuth.");
define("_MD_AM_SENDMAILPATH","Chemin pour l'envoi du mail");
define("_MD_AM_SENDMAILPATHDESC","Chemin du programe d'envoi des Emails sur le serveur. par d&eacute;fault (/usr/sbin/sendmail) ");
define("_MD_AM_THEMEOK","Th&egrave;mes s&eacute;lectionnables");
define("_MD_AM_THEMEOKDSC","Th&egrave;mes que les utilisateurs peuvent choisir comme th&egrave;me par d&eacute;faut dans le bloc th&egrave;mes");
define("_MD_AM_AUTH_CONFOPTION_XOOPS", "Base de donn&eacute;es du site");
define("_MD_AM_AUTH_CONFOPTION_LDAP", "Annuaire Standard LDAP");
define("_MD_AM_AUTH_CONFOPTION_AD", "Annuaire Active Microsoft &copy");
define("_MD_AM_AUTHENTICATION", "Param&egrave;tre d'authentification");
define("_MD_AM_AUTHMETHOD", "M&eacute;thode d'authentification");
define("_MD_AM_AUTHMETHODDESC", "Quelle m&eacute;thode &agrave; utiliser pour authentifier les utilisateurs");
define("_MD_AM_LDAP_MAIL_ATTR", "Code du mail");
define("_MD_AM_LDAP_MAIL_ATTR_DESC", "Code repr&eacute;sentant l'Email (en g&eacute;n&eacute;ral 'mail')");
define("_MD_AM_LDAP_NAME_ATTR", "Code du nom complet");
define("_MD_AM_LDAP_NAME_ATTR_DESC", "Code repr&eacute;sentant le nom complet de la personne (en g&eacute;n&eacute;ral 'cn')");
define("_MD_AM_LDAP_SURNAME_ATTR", "Code du pseudo");
define("_MD_AM_LDAP_SURNAME_ATTR_DESC", "Code repr&eacute;sentant le pseudo de la personne (en g&eacute;n&eacute;ral 'sn')");
define("_MD_AM_LDAP_GIVENNAME_ATTR","Code du pr&eacute;nom");
define("_MD_AM_LDAP_GIVENNAME_ATTR_DSC", "Code repr&eacute;sentant le pr&eacute;nom de la personne (en g&eacute;n&eacute;ral 'givenname')");
define("_MD_AM_LDAP_BASE_DN", "DN de base");
define("_MD_AM_LDAP_BASE_DN_DESC", "Nom du DN de base pour les utilisateurs (ou=users,dc=org)");
define("_MD_AM_LDAP_PORT","Port LDAP");
define("_MD_AM_LDAP_PORT_DESC","Port d'&eacute;coute de votre LDAP (par d&eacute;faut 389 )");
define("_MD_AM_LDAP_SERVER","Nom du serveur");
define("_MD_AM_LDAP_SERVER_DESC","Nom ou adresse IP du serveur LDAP");
define("_MD_AM_LDAP_MANAGER_DN", "DN de recherche");
define("_MD_AM_LDAP_MANAGER_DN_DESC", "DN de la personne autoris&eacute;e &agrave; faire des recherches (par exemple cn=manager,dc=org) ");
define("_MD_AM_LDAP_MANAGER_PASS", "Mot de passe de recherche");
define("_MD_AM_LDAP_MANAGER_PASS_DESC", "Mot de passe de la personne autoris&eacute;e &agrave; faire des recherches");
define("_MD_AM_LDAP_VERSION", "Version LDAP");
define("_MD_AM_LDAP_VERSION_DESC", "Version du protocole LDAP : 2 ou 3");
define("_MD_AM_LDAP_USERS_BYPASS", " Utilisateurs contournant l'authentication LDAP");
define("_MD_AM_LDAP_USERS_BYPASS_DESC", "Authentification directe dans base de donn&eacute;es du site.<br>Noms des utilisateurs doivent &ecirc;tre s&eacute;par&eacute;s par | ");
define("_MD_AM_LDAP_USETLS", " TLS connexion");
define("_MD_AM_LDAP_USETLS_DESC", "Utilisez un TLS (Transport Layer Security=transports de s&eacute;curit&eacute; de la couche) de connection. pour l'utilisation standard de TLS c'est le port 389<br />" .
								  " et dans LDAP doit &ecirc;tre r&eacute;gl&eacute; sur 3.");
								  
define("_MD_AM_LDAP_LOGINLDAP_ATTR","Code utilis&eacute; pour rechercher un utilisateur");
define("_MD_AM_LDAP_LOGINLDAP_ATTR_D","Quand l'utilisation du nom de connexion dans l'option DN est plac&eacute;e &agrave; oui, il doit correspondre &agrave; celui du site");
define("_MD_AM_LDAP_LOGINNAME_ASDN", "Nom de login pr&eacute;sent dans le DN");
define("_MD_AM_LDAP_LOGINNAME_ASDN_D", "nom de connexion est utilis&eacute;e dans le LDAP DN (ex: uid=<loginname>,dc=org)<br>L'entr&eacute;e est directement lue sur le serveur LDAP sans recherche");

define("_MD_AM_LDAP_FILTER_PERSON", "Filtre de recherche");
define("_MD_AM_LDAP_FILTER_PERSON_DESC", "Filtre sp&eacute;cial pour la recherche de personne. @@loginname@@ est remplac&eacute; par le nom de login<br> Laisser en blanc par d&eacute;faut !" .
		"<br />Ex : (&(objectclass=person)(samaccountname=@@loginname@@)) pour AD" .
		"<br />Ex : (&(objectclass=inetOrgPerson)(uid=@@loginname@@)) pour LDAP");

define("_MD_AM_LDAP_DOMAIN_NAME", "Nom de domaine");
define("_MD_AM_LDAP_DOMAIN_NAME_DESC", "Nom de domaine Windows. Pour ADS et serveur NT");

define("_MD_AM_LDAP_PROVIS", "Cr&eacute;ation automatique du compte");
define("_MD_AM_LDAP_PROVIS_DESC", "Cr&eacute;er le compte automatiquement dans la base de donn&eacute;es si l'utilisateur existe pas");

define("_MD_AM_LDAP_PROVIS_GROUP", "Affectation par d&eacute;faut");
define("_MD_AM_LDAP_PROVIS_GROUP_DSC", "Groupes auquels l'utilisateur cr&eacute;er sera affect&eacute; par d&eacute;fault");

define("_MD_AM_LDAP_FIELD_MAPPING_ATTR", "Serveur d'authentification mapping");
define("_MD_AM_LDAP_FIELD_MAPPING_DESC", "Correspondance entre les champ de la base de donn&eacute;es et le syst&egrave;me d'authentification LDAP." .
		"<br />par exemple: email=mail" .
		"<br />S&eacute;parez les avec un |" .
		"<br /><br />!! Pour le webmaster uniquement!!");

define("_MD_AM_LDAP_PROVIS_UPD", "maintenez l'approvisionnement de compte");
define("_MD_AM_LDAP_PROVIS_UPD_DESC", "Le compte d'utilisateur du site est toujours synchronis&eacute; avec le Serveur d'Authentification");

//niveau de sécurité du mot de passe
define("_MD_AM_PASSLEVEL","Niveau de s&eacute;curit&eacute; minimum");
define("_MD_AM_PASSLEVEL_DESC","D&eacute;finir quel niveau de s&eacute;curit&eacute; pour le mot de passe de l'utilisateur. Il est recommand&eacute; de ne pas fixer trop faible ou trop fort, &ecirc;tre raisonnable. ");
define("_MD_AM_PASSLEVEL1","peut s&ucirc;r");
define("_MD_AM_PASSLEVEL2","Faible");
define("_MD_AM_PASSLEVEL3","Raisonnable");
define("_MD_AM_PASSLEVEL4","Fort");
define("_MD_AM_PASSLEVEL5","&eacute;lev&eacute;");
define("_MD_AM_PASSLEVEL6","Non classer");

define("_MD_AM_RANKW","Largeur maximum de l'image du classement en pixel");
define("_MD_AM_RANKH","Hauteur maximum de l'image du classement en pixel");
define("_MD_AM_RANKMAX","Poids maximum de l'image du classement en octets");

define("_MD_AM_MULTILANGUAGE","Gestion des langues");
define("_MD_AM_ML_ENABLE","Activer la fonction Multilangue");
define("_MD_AM_ML_ENABLEDSC","D&eacute;fini &agrave; Oui afin de permettre la lecture du site dans toutes les langues pr&eacute;sente dans les dossiers language.");
define("_MD_AM_ML_TAGS","Code Tags des langues");
define("_MD_AM_ML_TAGSDSC","Tapez les codes Tags des langues &agrave; utilis&eacute;s sur ce site.<br /> Par exemple:<br />fran&ccedil;ais=<b>fr</b>ench son tag sera donc <b>fr</b><br />Anglais=<b>en</b>glish son tag sera donc <b>en</b><br />s&eacute;par&eacute;s les par une virgule: <b>fr,en</b>.");
define("_MD_AM_ML_NAMES","Noms des langues");
define("_MD_AM_ML_NAMESDSC","Tapez les noms des langues &agrave; utiliser sur ce site, s&eacute;par&eacute;s les par une virgule");
define("_MD_AM_ML_CAPTIONS","Titres (ALT) des Langues");
define("_MD_AM_ML_CAPTIONSDSC","Entrez les titres (ALT) pour ces langues");
define("_MD_AM_ML_CHARSET","Charsets");
define("_MD_AM_ML_CHARSETDSC","Codes charsets de ces langues");

define("_MD_AM_REMEMBERME","Activer la fonction 'Se souvenir de moi' dans l'identification du visiteur (login).");
define("_MD_AM_REMEMBERMEDSC","La fonction 'Se souvenir de moi' peut repr&eacute;senter un probl&egrave;me de s&eacute;curit&eacute;. Utilisez le &agrave; vos risque et p&eacute;ril.");

define("_MD_AM_PRIVDPOLICY","Activer la fonction 'Politique de confidentialit&eacute;' ?");
define("_MD_AM_PRIVDPOLICYDSC","Le texte 'Politique de confidentialit&eacute;' devra &ecirc;tre adapt&eacute;e &agrave; votre site & il s'affiche lorsqu'un utilisateur s'enregistre &agrave; votre site.");
define("_MD_AM_PRIVPOLICY","Entrez votre 'Politique de confidentialit&eacute;'");
define("_MD_AM_PRIVPOLICYDSC","");

define("_MD_AM_WELCOMEMSG","Envoyer un message de bienvenue aux nouveaux utilisateur enregistr&eacute;");
define("_MD_AM_WELCOMEMSGDSC","Envoyer un message de bienvenue aux nouveaux utilisateurs lorsqu'il active leur compte. Le texte de ce message peut &ecirc;tre configur&eacute; dans l'option suivante.");
define("_MD_AM_WELCOMEMSG_CONTENT","Texte du message de bienvenue");
define("_MD_AM_WELCOMEMSG_CONTENTDSC","Vous pouvez modifier le message qui est envoy&eacute; au nouvel utilisateur.<br /><br /><b>Info:</b> vous pouvez inserrez les codes suivant dans le texte: <br /><br />- {UNAME} = Pseudo de l'utilisateur <br />- {X_UEMAIL} = Email de l'utilisateur<br />- {X_ADMINMAIL} = Email de l'administrateur<br />- {X_SITENAME} = Nom du site<br />- {X_SITEURL} = URL du site");

define("_MD_AM_SEARCH_USERDATE","Afficher l'utilisateur et la date dans les r&eacute;sultats de recherche");
define("_MD_AM_SEARCH_USERDATEDSC","");
define("_MD_AM_SEARCH_NO_RES_MOD","Voir les modules avec aucun rapport dans les r&eacute;sultats de recherche");
define("_MD_AM_SEARCH_NO_RES_MODDSC","");
define("_MD_AM_SEARCH_PER_PAGE","Nombre de r&eacute;sultats par page de recherche");
define("_MD_AM_SEARCH_PER_PAGEDSC","");

define("_MD_AM_EXT_DATE","Utiliser l'extention date locale ?");
define("_MD_AM_EXT_DATEDSC","l'extention de date c'est la possibilit&eacute; pour les pays d'utiliser un autre calendrier que le calendrier gr&eacute;gorien.<br /> Attention ! en activant cette option, le script d'extention de calendrier devra &ecirc;tre installer sur le site.<br />Pour plus d'infos merci de visiter <a href='http://wiki.impresscms.org/index.php?title=Extended_date_function'>Fonction d'extention de date</a>.");

define("_MD_AM_EDITOR_DEFAULT","&Eacute;diteur de texte par d&eacute;faut");
define("_MD_AM_EDITOR_DEFAULT_DESC","S&eacute;lectionnez l'&eacute;diteur de texte par d&eacute;faut pour tout le site.");

define("_MD_AM_EDITOR_ENABLED_LIST","&Eacute;diteurs de texte autoris&eacute;");
define("_MD_AM_EDITOR_ENABLED_LIST_DESC","S&eacute;lectionnez les &eacute;diteurs de texte que peuvent choisir les utilisteurs dans les modules (fonctionne seulement si le module a une configuration pour s&eacute;lectionner l'&eacute;diteur.)");

define("_MD_AM_ML_AUTOSELECT_ENABLED","Affichage du site dans la m&ecirc;me langue du navigateur du visiteur");

define("_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE","Autoriser les anonymes &agrave; voir les profils d'utilisateurs.");

define("_MD_AM_ENC_TYPE","Changer le Cryptage du mot de passe (par d&eacute;faut c'est SHA256)");
define("_MD_AM_ENC_TYPEDSC","Change l'Algorithme utilis&eacute; pour crypter les mots de passe des utilisateurs. <br /><font color=red>ATTENTION ! changer l'Algorithme utilis&eacute; rendra tout les mots de passe invalide ! les utilisateurs enregistr&eacute; devront r&eacute;initialis&eacute; leurs mots de passe et voir m&ecirc;me d'en changer.</font>");
define("_MD_AM_ENC_MD5","MD5 (non recommand&eacute;)");
define("_MD_AM_ENC_SHA256","SHA 256 (recommand&eacute;)");
define("_MD_AM_ENC_SHA384","SHA 384");
define("_MD_AM_ENC_SHA512","SHA 512");
define("_MD_AM_ENC_RIPEMD128","RIPEMD 128");
define("_MD_AM_ENC_RIPEMD160","RIPEMD 160");
define("_MD_AM_ENC_WHIRLPOOL","WHIRLPOOL");
define("_MD_AM_ENC_HAVAL1284","HAVAL 128,4");
define("_MD_AM_ENC_HAVAL1604","HAVAL 160,4");
define("_MD_AM_ENC_HAVAL1924","HAVAL 192,4");
define("_MD_AM_ENC_HAVAL2244","HAVAL 224,4");
define("_MD_AM_ENC_HAVAL2564","HAVAL 256,4");
define("_MD_AM_ENC_HAVAL1285","HAVAL 128,5");
define("_MD_AM_ENC_HAVAL1605","HAVAL 160,5");
define("_MD_AM_ENC_HAVAL1925","HAVAL 192,5");
define("_MD_AM_ENC_HAVAL2245","HAVAL 224,5");
define("_MD_AM_ENC_HAVAL2565","HAVAL 256,5");

//Gestion de contenu/articles
define("_MD_AM_CONTMANAGER","gestionnaire de contenu");
define("_MD_AM_DEFAULT_CONTPAGE","Page index par d&eacute;faut");
define("_MD_AM_DEFAULT_CONTPAGEDSC","Page d'accueil par d&eacute;faut dans le module. si aucune pages est s&eacute;lectionn&eacute;es le module affichera par d&eacute;faut les pages r&eacute;cemment cr&eacute;er.");
define("_MD_AM_CONT_SHOWNAV","Afficher le menu de navigation ?");
define("_MD_AM_CONT_SHOWNAVDSC","Affiche le menu des articles dans un bloc de navigation sur le site.");
define("_MD_AM_CONT_SHOWSUBS","Afficher les pages apparent&eacute; ?");
define("_MD_AM_CONT_SHOWSUBSDSC","Affiche les liens des pages en rapport ou apparent&eacute; avec l'article.");
define("_MD_AM_CONT_SHOWPINFO","Afficher les informations ?");
define("_MD_AM_CONT_SHOWPINFODSC","Afficher le nom de l'auteur, la date de publication sur la page de l'article.");
define("_MD_AM_CONT_ACTSEO","Utilisez le titre de la page plut&ocirc;t que l'identifiant dans l'url ?");
define("_MD_AM_CONT_ACTSEODSC","Am&eacute;liore le seo donc le r&eacute;f&eacute;rencement, au lieu d'avoir une URL basic exemple: http//www.../../content.php?page=<b>item01255dsq</b>. Vous aurez une URL du style http//www.../../content.php?page=<b>cpascalwebbienvenue</b> .");
//Captcha - sécurité anti-spam avec image
define('_MD_AM_USECAPTCHA', 'Activ&eacute; la s&eacute;curit&eacute; anti-spam ?');
define('_MD_AM_USECAPTCHADSC', 'la s&eacute;curit&eacute; CAPTCHA (anti-spam) affiche un code al&eacute;atoire de chiffres et de lettres sur le formulaire avant la validation.');
define('_MD_AM_USECAPTCHAFORM', 'Activ&eacute; la s&eacute;curit&eacute; anti-spam ?');
define('_MD_AM_USECAPTCHAFORMDSC', 'la s&eacute;curit&eacute; CAPTCHA (anti-spam) affiche un code al&eacute;atoire de chiffres et de lettres aux commentaires avant validation, afin d\'&eacute;viter les abus et le spam.');
define('_MD_AM_ALLWHTSIG', 'Autoris&eacute; les images externes et le code HTML dans la signature ?');
define('_MD_AM_ALLWHTSIGDSC', 'Attention ! il y a des risques si activ&eacute; l\'autorisation d\'ins&eacute;r&eacute; des images et du code HTML dans la signature peut causer des vuln&eacute;rabilit&eacute;s si un utilisateur ins&egrave;re des codes malveillants.');
define('_MD_AM_ALLWSHOWSIG', 'Autoris&eacute; les utilisateurs d\'utiliser une signature dans leur profil/posts ?');
define('_MD_AM_ALLWSHOWSIGDSC', 'En activant cette option, les utilisateurs seront en mesure d\'utiliser une signature personnelle qui sera ajout&eacute;e &agrave; leurs posts.');
define("_MD_AM_PERSON","Personalisation");
define("_MD_AM_GOOGLE_ANA","Google Analytics");
define("_MD_AM_GOOGLE_ANA_DESC","Relevez votre id Google Analytics sur votre compte Google, puis inscrivez le ici<br />exemple: <small>_uacct = \"UA-<font color=#FF0000><b>xxxxxx-x</b></font>\"</small><br />ou<small><br />var pageTracker = _gat._getTracker( UA-\"<font color=#FF0000><b>xxxxxx-x</b></font>\");</small><br />(remplacez <font color=#FF0000><b>xxxxxx-x</b></font> par votre id).");
define("_MD_AM_LLOGOADM","logo admin de gauche");
define("_MD_AM_LLOGOADM_DESC","S&eacute;lectionner une image pour qu'elle apparaisse dans le header (haut de la page) de l'administration ou importer en une en cliquant sur ''Ajouter un fichier image''");
define("_MD_AM_LLOGOADM_URL","Lien du logo admin de gauche");
define("_MD_AM_LLOGOADM_ALT","Titre 'ALT' du logo admin de gauche");
define("_MD_AM_RLOGOADM","logo admin de droite");
define("_MD_AM_RLOGOADM_DESC","S&eacute;lectionner une image pour qu'elle apparaisse dans le header (haut de la page) de l'administration ou importer en une en cliquant sur ''Ajouter un fichier image''");
define("_MD_AM_RLOGOADM_URL","Lien du logo admin de droite");
define("_MD_AM_RLOGOADM_ALT","Titre 'ALT' du logo admin de droite");
define("_MD_AM_METAGOOGLE","Google Meta Tag");
define("_MD_AM_METAGOOGLE_DESC","Code g&eacute;n&eacute;r&eacute; par Google pour confirmer la propri&eacute;t&eacute; sur un site de sorte que vous pouvez consulter la page d'erreur statistiques. Pour d'autres informations http://www.google.com/webmasters");
define("_MD_AM_RSSLOCAL","Admin flux d'actualit&eacute;s URL");
define("_MD_AM_RSSLOCAL_DESC","URL d'un lien RSS (flux d'actualit&eacute;s) qui sera affich&eacute; pour Le projet ImpressCMS > News.");
define("_MD_AM_FOOTADM","Bas de pages de l'administration");
define("_MD_AM_FOOTADM_DESC","Textes, liens ou images qui s'afficheront en bas de toutes les pages de l'administration");
define("_MD_AM_EMAILTTF","Police utilis&eacute;e dans la protection anti spam");
define("_MD_AM_EMAILTTF_DESC","S&eacute;lectionnez la police qui sera utilis&eacute;e pour g&eacute;n&eacute;rer la protection de l'adresse Email. Si celle &ccedil;i est activ&eacute; dans l'option pr&eacute;c&eacute;dente.");
define("_MD_AM_EMAILLEN","Taille de la police utilis&eacute;e dans la protection anti spam");
define("_MD_AM_EMAILLEN_DESC","S&eacute;lectionnez la taille de la police qui sera utilis&eacute;e pour g&eacute;n&eacute;rer la protection de l'adresse Email. Si celle &ccedil;i est activ&eacute;.");
define("_MD_AM_EMAILCOLOR","La couleur de police utilis&eacute;e dans la protection anti spam");
define("_MD_AM_EMAILCOLOR_DESC","S&eacute;lectionnez la couleur de police qui sera utilis&eacute;e pour g&eacute;n&eacute;rer la protection de l'adresse Email. Si celle &ccedil;i est activ&eacute;.");
define("_MD_AM_EMAILSHADOW","Ombre de couleur utilis&eacute; dans la protection anti spam");
define("_MD_AM_EMAILSHADOW_DESC","Choisissez une couleur pour l'ombre de la protection de l'adresse Email. Laissez ce champ vide si vous ne souhaitez pas l'utiliser.");
define("_MD_AM_SHADOWX","X = d&eacute;calage horizontal de l'ombre dans la protection anti spam");
define("_MD_AM_SHADOWX_DESC","Entrez une valeur (en px) qui repr&eacute;sentera le d&eacute;calage horizontal de l'ombre dans la protection anti spam.");
define("_MD_AM_SHADOWY","Y = d&eacute;calage vertical de l'ombre dans la protection anti spam");
define("_MD_AM_SHADOWY_DESC","Entrez une valeur (en px) qui repr&eacute;sentera le d&eacute;calage vertical de l'ombre dans la protection anti spam.");
define("_MD_AM_EDITREMOVEBLOCK","Modifier et supprimer des blocs du c&ocirc;t&eacute; utilisateur ?");
define("_MD_AM_EDITREMOVEBLOCKDSC","Afficher des ic&ocirc;nes &agrave; c&ocirc;t&eacute; du titre des blocs donnant acc&egrave;s directement dans l'admin pour supprimer ou de modifier le bloc.");

define("_MD_AM_EMAILPROTECT","Prot&eacute;gez les adresses Email contre le SPAM ?");
define("_MD_AM_EMAILPROTECTDSC","L'activation de cette option permettra &agrave; chaque fois qu'une adresse mail est afficher sur le site, elle sera automatiquement prot&eacute;g&eacute;e contre les robots SPAM.");
define("_MD_AM_MULTLOGINPREVENT","Pr&eacute;venir contre les connexions multiples ?");
define("_MD_AM_MULTLOGINPREVENTDSC","Si un utilisateur est d&eacute;j&agrave; connect&eacute; sur le site, le m&ecirc;me nom d'utilisateur ne pourra pas se connecter une deuxi&egrave;me fois jusqu'&agrave; ce que la premi&egrave;re session soit ferm&eacute;e.");
define("_MD_AM_MULTLOGINMSG","Message de redirection contre la multi connexion:");
define("_MD_AM_MULTLOGINMSG_DESC","Message qui sera affich&eacute; &agrave; un utilisateur qui essaie de se connecter avec un nom d'utilisateur actuellement connecter sur le site.");
define("_MD_AM_GRAVATARALLOW","Autoriser les avatars ?");
define("_MD_AM_GRAVATARALWDSC","Les utilisateurs seront en mesure d'utiliser les avatars et de les li&eacute;s &agrave; leur adresse Email.");

define("_MD_AM_SHOW_ICMSMENU","Afficher le menu d&eacute;roulant ImpressCMS projet ?");
define("_MD_AM_SHOW_ICMSMENU_DESC","Affiche le menu d&eacute;roulant de ImpressCMS projet avec ses liens dans l'administration.");

define("_MD_AM_SHORTURL","Tronquer les URLs longues ?");
define("_MD_AM_SHORTURLDSC","Coupe automatiquement les URLs du site trop longues &agrave; un certain nombre de caract&egrave;res, dans les posts de forum par exemple qui souvent peuvent modifier le design du th&eacute;me du site.");
define("_MD_AM_URLLEN","Longueur maximale des URLs ?");
define("_MD_AM_URLLEN_DESC","Nombre maximum de caract&egrave;res d'une URL qui sera automatiquement tronqu&eacute;.");
define("_MD_AM_PRECHARS","Nombre de caract&egrave;res au d&eacute;part des URLs ?");
define("_MD_AM_PRECHARS_DESC","Nombre maximum de caract&egrave;res autoris&eacute; au d&eacute;but d'une URL");
define("_MD_AM_LASTCHARS","Nombre de caract&egrave;res &agrave; la fin des URLs ?");
define("_MD_AM_LASTCHARS_DESC","Nombre maximum de caract&egrave;res autoris&eacute; &agrave; la fin d'une URL");
define("_MD_AM_HIDDENCONTENT","Utilisez un Tags pour masquer certains contenus ?");
define("_MD_AM_HIDDENCONTENTDSC","Utiliser un tag pour cacher certains contenus de votre site aux utilisateurs anonymes.<br />Les utilisateurs anonymes devront s'inscrire pour pouvoir voir ce contenu.<br /><i>Si vous d&eacute;sactivez cette option, tout le contenu entre ce Tag s'affichera comme d'habitude</i>");
define("_MD_AM_SIGMAXLENGTH","Nombre maximum de caract&egrave;res dans les signatures ?");
define("_MD_AM_SIGMAXLENGTHDSC","Nombre maximum de caract&egrave;res dans les signatures des utilisateurs.<br /> Tous les caract&egrave;res en plus du nombre indiquer sera ignor&eacute;.<br /><i>Attention, des signatures trop longues peuvent souvent modifier le design du th&eacute;me du site.</i>");
/*
define("_MD_AM_AUTORESIZE","Auto resize larger avatars?");
define("_MD_AM_AUTORESIZE_DESC","If YES, if the avatar sent have the width or height greater than that set will be automatically resized (keeping the ratio of the image) and the upload will be allowed. The function accepts images JPG, PNG and GIF (including animated gif), but yet does not function satisfactorily for animated gifs with transparent background.");
*/
define("_MD_AM_AUTHOPENID","Activer l'authentication OpenID ?");
define("_MD_AM_AUTHOPENIDDSC","Cela permet aux utilisateurs de se connecter sur le site en utilisant leur compte OpenID. Pour obtenir des renseignements complets sur l'int&eacute;gration de OpenID dans ImpressCMS, merci de visiter <a href='http://wiki.impresscms.org/index.php?title=ImpressCMS_OpenID'>notre wiki</a>.");
define("_MD_AM_USE_GOOGLE_ANA"," Activer Google Analytics ?");
define("_MD_AM_USE_GOOGLE_ANA_DESC","");

?>