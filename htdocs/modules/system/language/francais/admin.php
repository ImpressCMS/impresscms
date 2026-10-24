<?php
// $Id: admin.php 9551 2009-11-14 14:43:53Z pesianstranger $
//%%%%%%	File Name  admin.php 	%%%%%
//define('_MD_AM_CONFIG', 'Systeem instellingen');
define('_INVALID_ADMIN_FUNCTION', 'Fonction administration non valide');
// Admin Module Names
define('_MD_AM_ADGS', 'Groupes');
define('_MD_AM_BKAD', 'Blocs');
define('_MD_AM_MDAD', 'Modules');
define('_MD_AM_SMLS', 'Emotic&ocirc;nes');
define('_MD_AM_RANK', 'Classement des membres');
define('_MD_AM_USER', 'Modifier un membre');
define('_MD_AM_FINDUSER', 'Rechercher un membre');
define('_MD_AM_PREF', 'Pr&eacute;f&eacute;rences');
define('_MD_AM_VRSN', 'Version');
define('_MD_AM_MLUS', 'Envoyer un mail');
define('_MD_AM_IMAGES', 'Gestion des images');
define('_MD_AM_AVATARS', 'Avatars');
define('_MD_AM_TPLSETS', 'Templates');
define('_MD_AM_COMMENTS', 'Commentaires');
define('_MD_AM_BKPOSAD', 'Positions des blocs');
define('_MD_AM_PAGES', 'Gestion des liens');
define('_MD_AM_CUSTOMTAGS', 'Gestion des Tags');
// Group permission phrases
define('_MD_AM_PERMADDNG', 'Impossible d\'ajouter la permission: %s au groupe: %s)');
define('_MD_AM_PERMADDOK', 'La permission: %s au groupe: %s a &eacute;t&eacute; ajout&eacute;e avec succ&egrave;s');
define('_MD_AM_PERMRESETNG', 'Impossible de r&eacute;-initialiser la permission de groupe pour le module %s');
define('_MD_AM_PERMADDNGP', 'Tous les &eacute;l&eacute;ments parents doivent &ecirc;tre s&eacute;lectionn&eacute;s.');
######################## Added in 1.2 ###################################
if(!defined('_MD_AM_AUTOTASKS')){define('_MD_AM_AUTOTASKS', 'Taches automatiques');
}
define('_MD_AM_ADSENSES', 'Adsenses');
define('_MD_AM_RATINGS', 'Ratings');
define('_MD_AM_MIMETYPES', 'Mime Types');

define("_MD_AM_ADGS_DSC", "G&eacute;rez les permissions, les membres, la visibilit&eacute; et les droits d'acc&egrave;s des groupes d'utilisateurs.");
define("_MD_AM_ADSENSES_DSC", "Les Adsenses sont des balises que vous pouvez d&eacute;finir et utiliser n'importe o&ugrave; sur votre site.");
define("_MD_AM_AUTOTASKS_DSC", "Les t&acirc;ches automatiques vous permettent de cr&eacute;er un calendrier d'actions que le syst&egrave;me ex&eacute;cutera automatiquement.");
define("_MD_AM_AVATARS_DSC", "G&eacute;rez les avatars disponibles pour les utilisateurs de votre site.");
define("_MD_AM_BKAD_DSC", "G&eacute;rez et cr&eacute;ez les blocs utilis&eacute;s sur l'ensemble de votre site.");
define("_MD_AM_BKPOSAD_DSC", "G&eacute;rez et cr&eacute;ez les positions de blocs utilis&eacute;es dans les th&egrave;mes de votre site.");
define("_MD_AM_COMMENTS_DSC", "G&eacute;rez les commentaires post&eacute;s par les utilisateurs sur votre site.");
define("_MD_AM_CUSTOMTAGS_DSC", "Les balises personnalis&eacute;es sont des balises que vous pouvez d&eacute;finir et utiliser n'importe o&ugrave; sur votre site.");
define("_MD_AM_FINDUSER_DSC", "Recherchez parmi les utilisateurs inscrits &agrave; l'aide de filtres.");
define("_MD_AM_IMAGES_DSC", "Cr&eacute;ez des groupes d'images et g&eacute;rez les permissions de chaque groupe. Recadrez et redimensionnez les photos t&eacute;l&eacute;charg&eacute;es.");
define("_MD_AM_MDAD_DSC", "G&eacute;rez la position, le statut et le nom des modules dans le menu, ou mettez-les &agrave; jour si n&eacute;cessaire.");
define("_MD_AM_MIMETYPES_DSC", "G&eacute;rez les extensions autoris&eacute;es pour les fichiers envoy&eacute;s sur votre site.");
define("_MD_AM_MLUS_DSC", "Envoyez un message &agrave; des groupes entiers d'utilisateurs ou filtrez les destinataires selon des crit&egrave;res.");
define("_MD_AM_PAGES_DSC", "Les symliens vous permettent de cr&eacute;er un lien unique bas&eacute; sur n'importe quelle page de votre site, utilisable pour des blocs propres &agrave; l'URL d'une page, ou pour cr&eacute;er un lien direct dans le contenu d'un module.");
define("_MD_AM_RANK_DSC", "Les rangs sont des images servant &agrave; diff&eacute;rencier les utilisateurs de diff&eacute;rents niveaux sur votre site !");
define("_MD_AM_RATINGS_DSC", "Cet outil vous permet d'ajouter une m&eacute;thode de notation &agrave; vos modules et de contr&ocirc;ler les r&eacute;sultats depuis cette section !");
define("_MD_AM_SMLS_DSC", "G&eacute;rez les smileys disponibles et d&eacute;finissez le code associ&eacute; &agrave; chacun.");
define("_MD_AM_TPLSETS_DSC", "Les gabarits sont des ensembles de fichiers html/css qui d&eacute;finissent la mise en page des modules.");
define("_MD_AM_USER_DSC", "Cr&eacute;ez, modifiez ou supprimez des utilisateurs inscrits.");
define("_MD_AM_VRSN_DSC", "Utilisez cet outil pour v&eacute;rifier les mises &agrave; jour de votre syst&egrave;me.");

?>
