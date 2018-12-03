<?php
// $Id: mailusers.php 19775 2010-07-11 18:54:25Z malanciault $
//%%%%%%	Admin Module Name  MailUsers	%%%%%
if (!defined('_AM_DBUPDATED')) {define('_AM_DBUPDATED', 'Base de donn&eacute;es mise &agrave; jour avec succ&egrave;s!');
}

//%%%%%%	mailusers.php 	%%%%%
define('_AM_SENDTOUSERS', 'Envoyer un message aux utilisateurs dont:');
define('_AM_SENDTOUSERS2', 'Envoyer &agrave;:');
define('_AM_GROUPIS', 'Le Groupe est (option)');
define('_AM_TIMEFORMAT', '(Format dd-mm-yyyy, option)');
define('_AM_LASTLOGMIN', 'La derni&egrave;re connexion est apr&egrave;s');
define('_AM_LASTLOGMAX', 'La derni&egrave;re connexion est avant');
define('_AM_REGDMIN', 'L\'inscription est apr&egrave;s');
define('_AM_REGDMAX', 'L\'inscription est avant');
define('_AM_IDLEMORE', 'La derni&egrave;re connexion est de plus de X jours (option)');
define('_AM_IDLELESS', 'La derni&egrave;re connexion est de moins de X jours (option)');
define('_AM_MAILOK', 'Envoyer un message uniquement aux membres qui acceptent les messages de notification (optionnel)');
define('_AM_INACTIVE', 'Envoyer un message aux membres inactifs uniquement (option)');
define('_AMIFCHECKD', 'Si c\'est coch&eacute;, tout ce qui est ci-dessus ainsi que la messagerie priv&eacute;e seront ignor&eacute;s');
define('_AM_MAILFNAME', 'Nom de l\'exp&eacute;diteur (e-mail uniquement)');
define('_AM_MAILFMAIL', 'E-mail de l\'exp&eacute;diteur (e-mail uniquement)');
define('_AM_MAILSUBJECT', 'Sujet');
define('_AM_MAILBODY', 'Corps du message');
define('_AM_MAILTAGS', 'Balises utiles :');
define('_AM_MAILTAGS1', '{X_UID} imprimera l\'ID utilisateur');
define('_AM_MAILTAGS2', '{X_UNAME} imprimera le nom de l\'utilisateur');
define('_AM_MAILTAGS3', '{X_UEMAIL} imprimera l\'e-mail de l\'utilisateur');
define('_AM_MAILTAGS4', '{X_UACTLINK} imprimera le lien d\'activation de l\'utilisateur');
define('_AM_SENDTO', 'Envoyer &agrave;');
define('_AM_EMAIL', 'E-mail');
define('_AM_PM', 'Message priv&eacute;');
define('_AM_SENDMTOUSERS', 'Envoyer le message aux utilisateurs');
define('_AM_SENT', 'Envoy&eacute; aux utilisateurs');
define('_AM_SENTNUM', '%s - %s (total: %s utilisateurs)');
define('_AM_SENDNEXT', 'Suivant');
define('_AM_NOUSERMATCH', 'Pas d\'utilisateur correspondant');
define('_AM_SENDCOMP', 'Envoi du message termin&eacute;.');
?>