<?php
//traduction CPascalWeb

define("_PLEASEWAIT","Merci de patienter");
define("_FETCHING","Chargement...");
define("_TAKINGBACK","Retour l&agrave; o&ugrave; vous &eacute;tiez...");
define("_LOGOUT","D&eacute;connexion");
define("_SUBJECT","Sujet");
define("_MESSAGEICON","Ic&ocirc;ne de message");
define("_COMMENTS","Commentaires");
define("_POSTANON","Poster en anonyme");
define("_DISABLESMILEY","D&eacute;sactiver les &eacute;motic&ocirc;nes");
define("_DISABLEHTML","D&eacute;sactiver le code html");
define("_PREVIEW","Pr&eacute;visualiser");

define("_GO","Go!");
define("_NESTED","Embo&icirc;t&eacute;");
define("_NOCOMMENTS","Pas de commentaires");
define("_FLAT","A plat");
define("_THREADED","Par conversation");
define("_OLDESTFIRST","Les + anciens en premier");
define("_NEWESTFIRST","Les + r&eacute;cents en premier");
define("_MORE","plus...");
define("_IFNOTRELOAD","Si la page ne se recharge pas automatiquement, merci de cliquer <a href=%s>ici</a>");
define("_WARNINSTALL2","ATTENTION !  Le r&eacute;pertoire %s est pr&eacute;sent sur votre serveur. <br />Merci de supprimer ce r&eacute;pertoire pour des raisons de s&eacute;curit&eacute;.");
define("_WARNINWRITEABLE","ATTENTION ! Le fichier %s est ouvert en &eacute;criture sur le serveur. <br />Merci de changer les permissions de ce fichier pour des raisons de s&eacute;curit&eacute;.<br /> sous Unix (444), sous Win32 (lecture seule)");
define("_WARNINNOTWRITEABLE","ATTENTION ! Le fichier %s n'est pas ouvert en &eacute;criture sur le serveur. <br />Merci de changer la permission de ce fichier des raisons de fonctionnalit&eacute;.<br /> sous Unix (777), sous Win32 (&eacute;criture)");

// Erreur messages de XoopsObject::cleanVars()
define( "_XOBJ_ERR_REQUIRED", "%s est n&eacute;cessaire" );
define( "_XOBJ_ERR_SHORTERTHAN", "%s doit &ecirc;tre inf&eacute;rieure &agrave; %d caract&egrave;res." );

// Fichier themeuserpost.php
define("_PROFILE","Profil");
define("_POSTEDBY","Post&eacute; par");
define("_VISITWEBSITE","Visiter le site Web");
define("_SENDPMTO","Envoyer un message priv&eacute; &agrave; %s");
define("_SENDEMAILTO","Envoyer un Email &agrave; %s");
define("_ADD","Ajouter");
define("_REPLY","R&eacute;pondre");
define("_DATE","Date");

//fichier admin_functions.php  
define("_MAIN","Principal");
define("_MANUAL","Manuel");
define("_INFO","Info");
define("_CPHOME","Administration");
define("_YOURHOME","Accueil");

//Fichier misc.php ( pop up qui est en ligne ?)      
define("_WHOSONLINE","Actuellement en ligne");
define('_GUESTS', 'Invit&eacute;s');
define('_MEMBERS', 'Membres');
define("_ONLINEPHRASE","<b>%s</b> visiteurs en ligne");
define("_ONLINEPHRASEX","dont <b>%s</b> sur <b>%s</b>");
define("_CLOSE","Fermer");

//Fichier module.textsanitizer.php  
define("_QUOTEC","Citation:");

//Fichier admin.php 
define("_NOPERM","D&eacute;sol&eacute;, vous n'avez pas les droits pour acc&eacute;der &agrave; cette zone.");

// Frases Communs
define("_NO","Non");
define("_YES","Oui");
define("_EDIT","Modifier");
define("_DELETE","Effacer");
define("_SUBMIT","Envoyer");
define("_MODULENOEXIST","Le script s&eacute;lectionn&eacute; n'existe pas !");
define("_ALIGN","Alignement");
define("_LEFT","Gauche");
define("_CENTER","Centre");
define("_RIGHT","Droite");
define("_FORM_ENTER", "Merci d'entrer %s");
//ce code  %s  represente le nom du fichier
define("_MUSTWABLE","Le fichier %s doit &ecirc;tre accessible en &eacute;criture sur le serveur !");
// Infos module 
define('_PREFERENCES', 'Pr&eacute;f&eacute;rences');
define("_VERSION", "Version");
define("_DESCRIPTION", "Description");
define("_ERRORS", "Erreurs");
define("_NONE", "Aucun");
define('_ON','le');
define('_READS','lectures');
define('_SEARCH','Cherche');
define('_ALL', 'Tous');
define('_TITLE', 'Titre');
define('_OPTIONS', 'Options');
define('_QUOTE', 'Citation');
define('_HIDDENC', 'Contenu cach&eacute;:');
define('_HIDDENTEXT', 'Ce contenu est cach&eacute; pour les visiteurs anonymes, merci <a href="'.ICMS_URL.'/register.php" title="enregistrement &agrave; ' . htmlspecialchars ( $xoopsConfig ['sitename'], ENT_QUOTES ) . '">de l\'enregistrer</a> pour qu\'il soit visible par tous.');
define('_LIST', 'Liste');
define('_LOGIN','Connexion');
define('_USERNAME','Membre:&nbsp;');
define('_PASSWORD','Mot de passe:&nbsp;');
define("_SELECT","&Eacute;diteur de texte");
define("_IMAGE","Image");
define("_SEND","Valider");
define("_CANCEL","Annuler");
define("_ASCENDING","Ordre montant");
define("_DESCENDING","Ordre d&eacute;scendant");
define('_BACK', 'Retour');
define('_NOTITLE', 'Aucun titre');

// Gestionnaire d'images
define('_IMGMANAGER',"Gestionnaire d'images");
define('_NUMIMAGES', '%s images');
define('_ADDIMAGE','Ajouter un fichier image');
define('_IMAGENAME','Nom:');
define('_IMGMAXSIZE','Poids maxi autoris&eacute;e (ko):');
define('_IMGMAXWIDTH','Largeur maxi autoris&eacute;e (pixels):');
define('_IMGMAXHEIGHT','Hauteur maxi autoris&eacute;e (pixels):');
define('_IMAGECAT','Cat&eacute;gorie :');
define('_IMAGEFILE','Fichier image ');
define('_IMGWEIGHT',"Ordre d'affichage dans le gestionnaire d'images:");
define('_IMGDISPLAY','Afficher cette image ?');
define('_IMAGEMIME','Type MIME:');
define('_FAILFETCHIMG', "Impossible de t&eacute;l&eacute;charg&eacute; le fichier %s");
define('_FAILSAVEIMG', "Impossible de stocker l'image %s dans la base de donn&eacute;es");
define('_NOCACHE', 'Pas de Cache');
define('_CLONE', 'Cloner');
define('_INVISIBLE', 'Invisible');

//Fichier class/xoopsform/formmatchoption.php 
define("_STARTSWITH", "Commen&ccedil;ant par");
define("_ENDSWITH", "Finissant par");
define("_MATCHES", "Correspondant &agrave;");
define("_CONTAINS", "Contenant");

//Fichier commentform.php  
define("_REGISTER","Enregistrement");

//Fichier  xoopscodes.php
define("_SIZE","TAILLE"); 
define("_FONT","POLICE"); 
define("_COLOR","COULEUR"); 
define("_EXAMPLE","EXEMPLE");
define("_ENTERURL","Entrez l'URL du lien que vous voulez ajouter:");
define("_ENTERWEBTITLE","Entrez le titre du site web:");
define("_ENTERIMGURL","Entrez l'URL de l'image que vous voulez ajouter.");
define("_ENTERIMGPOS","Maintenant, entrez la position de l'image.");
define("_IMGPOSRORL","'R' ou 'r' pour droite, 'L' ou 'l' pour gauche, ou laisser vide.");
define("_ERRORIMGPOS","ERREUR ! Entrez la position de l'image.");
define("_ENTEREMAIL","Entrez l'adresse Email que vous voulez ajouter.");
define("_ENTERCODE","Entrez les codes que vous voulez ajouter.");
define("_ENTERQUOTE","Entrez le texte que vous voulez citer.");
define("_ENTERHIDDEN","Entrez le texte que vous voulez cach&eacute; pour les visiteurs anonymes.");
define("_ENTERTEXTBOX","Merci de saisir le texte dans la bo&icirc;te.");

//Format de l'heure et de la date
define('_SECOND', '1 seconde');
define('_SECONDS', '%s secondes');
define('_MINUTE', '1 minute');
define('_MINUTES', '%s minutes');
define('_HOUR', '1 heure');
define('_HOURS', '%s heures');
define('_DAY', '1 jour');
define('_DAYS', '%s jours');
define('_WEEK', '1 semaine');
define('_MONTH', '1 mois');

define("_DATESTRING","Y-m-d");
define("_MEDIUMDATESTRING","Y-m-d G:i");
define("_SHORTDATESTRING","Y-m-d");
/*
The following characters are recognized in the format string:
a - "am" or "pm"
A - "AM" or "PM"
d - jour du mois, 2 digits avec les z&eacute;ros devant; i.e. "01" to "31"
D - jour de la semaine, textual, 3 lettres; i.e. "Fri"
F - mois, textual, long; i.e. "January"
h - heure, format 12-heures; i.e. "01" to "12"
H - heure, format 24-heures; i.e. "00" to "23"
g - heure, format 12-heures sans les z&eacute;ros devant; i.e. "1" to "12"
G - heure, format 24-heures sans les z&eacute;ros devant; i.e. "0" to "23"
i - minutes; i.e. "00" &agrave; "59"
j - jour du mois sans les z&eacute;ros devant; i.e. "1" to "31"
l (lowercase 'L') - jour de la semaine, textual, long; i.e. "Friday"
L - booleen pour ann&eacute;e bissextile; i.e. "0" or "1"
m - mois; i.e. "01" to "12"
n - mois sans les z&eacute;ros devant; i.e. "1" to "12"
M - mois, textual, 3 letters; i.e. "Jan"
s - secondes; i.e. "00" to "59"
S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd"
t - nombre de jours dans le mois donn&eacute; ; i.e. "28" to "31"
T - Timezone setting of this machine; i.e. "MDT"
U - seconds since the epoch
w - jour de la semaine, numeric, i.e. "0" (Sunday) to "6" (Saturday)
Y - ann&eacute;e, 4 positions; i.e. "1999"
y - ann&eacute;e, 2 positions; i.e. "99"
z - jour de l'ann&eacute;e; i.e. "0" to "365"
Z - timezone offset en secondes (i.e. "-43200" to "43200")
*/


//Param�tres sp�cifiques  de la langue fran�aise
define('_CHARSET', 'utf-8');
define('_LANGCODE', 'fr');

// changez le 0 en 1, si cette langue est en multi-octets (exemple: la langue asiatique)
define("XOOPS_USE_MULTIBYTES", "0");
// changez le 0 en 1, si cette langue s'�crit de droite vers la gauche (exemple: la langue asiatique)
define("_ADM_USE_RTL","0");

define('_MODULES','Modules');
define('_SYSTEM','Syst&egrave;me');
define('_IMPRESSCMS_NEWS','News');
define('_ABOUT','Le projet ImpressCMS');
define('_IMPRESSCMS_HOME','Projet accueil');
define('_IMPRESSCMS_COMMUNITY','Communaut&eacute;');
define('_IMPRESSCMS_ADDONS','Modules');
define('_IMPRESSCMS_WIKI','Wiki');
define('_IMPRESSCMS_BLOG','Blog');
define('_IMPRESSCMS_DONATE','Faites un don!');
define("_IMPRESSCMS_Support","Soutenir le projet !");
define('_IMPRESSCMS_SOURCEFORGE','SourceForge Projet');
define('_IMPRESSCMS_ADMIN','Administration de');
/** The default separator used in XoopsTree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//gestionnaire de contenu
define('_CT_NAV','Accueil');
define('_CT_RELATEDS','Pages apparent&eacute;es');
//Securit� image (captcha)
define("_SECURITYIMAGE_GETCODE","Entrez le code de s&eacute;curit&eacute;");
define("_QUERIES", "requ&ecirc;tes envoy&eacute;es");
define("_BLOCKS", "Blocs");
define("_EXTRA", "Extra");
define("_TIMERS", "Temps");
define("_CACHED", "Cache");
define("_REGENERATES", "R&eacute;g&eacute;n&egrave;re chaque %s seconds");
define("_TOTAL", "Total:");
define("_ERR_NR", "Num&eacute;ro d'erreur:");
define("_ERR_MSG", "Message d'erreur:");
define("_NOTICE", "Infos");
define("_WARNING", "Attention");
define("_STRICT", "Stricte");
define("_ERROR", "Erreur");
define("_TOOKXLONG", " a pris %s secondes pour charger.");
define("_BLOCK", "Blocs");
define("_WARNINGUPDATESYSTEM","F&eacute;licitations, votre site est a jour avec la derni&egrave;re version d'ImpressCMS ! <br />Il faut maintenant cliquer ici pour mettre &agrave; jour votre script syst&egrave;me.<br />mis &agrave; jour.");

//  montre le site local de soutien dans le menu admin du site.
define('_IMPRESSCMS_LOCAL_SUPPORT','http://www.impresscms.org'); 
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','site de support');
define("_ALLEFTCON","Entrer le texte qui doit &ecirc;tre align&eacute; sur le c�t&eacute; Gauche. ");
define("_ALCENTERCON","Entrer le texte qui doit &ecirc;tre align&eacute; au Centre. ");
define("_ALRIGHTCON","Entrer le texte qui doit &ecirc;tre align&eacute; sur le c�t&eacute; droite");
define( "_TRUST_PATH_HELP", "Attention: la connection avec le script protector &agrave; &eacute;chou&eacute; v&eacute;rifier le chemin du dossier de s&eacute;curit&eacute;: http://www/nom du dossier de s&eacute;curit&eacute;.<br />le dossier de s&eacute;curit&eacute; et un dossier dans lequel le site et ses scripts stock certains fichier de code et d'information pour plus de s&eacute;curit&eacute;.<br />Il est recommand&eacute; que ce dossier se situer en dehors de la racine Web, ce qui le rend pas accessible par un navigateur.<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path'>Cliquez ici pour en savoir plus sur le chemin d'affectation sp&eacute;ciale et la mani&egrave;re de le cr&eacute;er.</a>" );
define( "_PROTECTOR_NOT_FOUND", "Attention: Le syst&egrave;me n'est pas en mesure de trouver si le script protector est install&eacute; ou actives sur votre site.<br />Nous vous recommandons vivement d'installer ou d'activer Protector pour am&eacute;liorer la s&eacute;curit&eacute; de votre site.<br />Atention! ne pas oublier de modifier le fichier mainfile.php apr&eacute;s avoir installer le script Protector.<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Protector'>Cliquez ici pour en savoir plus sur Protector.</a><br /><a target='_blank' href='http://xoops.peak.ne.jp/modules/mydownloads/singlefile.php?lid=105&cid=1'>Cliquez ici pour t&eacute;l&eacute;charger la derni&egrave;re version du script protector.</a>" );

define('_MODABOUT_ABOUT', '&Agrave; propos de');
// Si vous avez des probl�mes avec cette police dans votre langue ou si il ne fonctionne pas, t�l�chargez tcpdf en suivant ce lien: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf et ajouter la police dans les biblioth�ques/tcpdf/polices de caract�res puis &eacute;crire le nom de la police ici. syst�me va alors charger cette police pour votre langue.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE','gregorian'); //cette valeur est pour le calendrier local Java utilis� dans ce syst�me, si vous n'�tes pas s�r , laissez cette valeur comme elle est !
define('_CALENDAR','Calendrier');
define('_RETRYPOST','D&eacute;sol&eacute;, un temps mort a eu lieu. Voulez-vous post&eacute; de nouveau ?'); 

############# added since 1.2 #############
define('_QSEARCH','Recherche Rapide');
define('_PREV','Prev');
define('_NEXT','Proch');
define('_LCL_NUM0','0');
define('_LCL_NUM1','1');
define('_LCL_NUM2','2');
define('_LCL_NUM3','3');
define('_LCL_NUM4','4');
define('_LCL_NUM5','5');
define('_LCL_NUM6','6');
define('_LCL_NUM7','7');
define('_LCL_NUM8','8');
define('_LCL_NUM9','9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define("_USE_LOCAL_NUM","0");
define("_ICMS_DBUPDATED","Base de données mise à jour correctement!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','Changer Editeur');
define("_ENTERHTMLCODE","Entrer les codes HTML que vous voulez rajouter.");
define("_ENTERPHPCODE","Entrer les codes PHP que vous voulez rajouter.");
define("_ENTERCSSCODE","Entrer les codes CSS que vous voulez rajouter.");
define("_ENTERJSCODE","Entrer le code JavaScript que vous voulez rajouter."); 
define("_ENTERWIKICODE","Entrer le terme wiki que vous voulez rajouter.");
define("_ENTERLANGCONTENT","Entrer le texte que vous voulez mettre en %s.");
define('_LANGNAME', 'Français');
define('_ENTERYOUTUBEURL', 'Entrer l\'addresse YouTube:');
define('_ENTERHEIGHT', 'Entrer la hauteur de la corniche');
define('_ENTERWIDTH', 'Entrer le largeur de la corniche');
define('_ENTERMEDIAURL', 'Entrer l\'url du media :');
// !!IMPORTANT!! inserer '\' avant chaque charactere réservé: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"	
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "\Au\j\o\u\\r\d\\'\h\u\i G:i");
define("_YESTERDAY", "\H\i\e\\r G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "Y/n/j G:i");
define("_ELAPSE", "il y a %s");
define('_VISIBLE', 'Visible');
define('_UP', 'Haut');
define('_DOWN', 'Bas');
define('_CONFIGURE', 'Paramétriser');?>