<?php
/**
 * Core constants
 *
 * This file has all core errors and warning constants.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		languages
 * @since		1.2
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id
 */

define('_CORE_MEMORYUSAGE', 'Consommation mémoire');
define('_CORE_BYTES', 'octets');
define('_CORE_KILOBYTES', 'Kilo Octets');
define('_CORE_MEGABYTES', 'Mega Octets');
define('_CORE_GIGABYTES', 'Giga octets');
define('_CORE_KILOBYTES_SHORTEN', 'Ko');
define('_CORE_MEGABYTES_SHORTEN', 'Mo');
define('_CORE_GIGABYTES_SHORTEN', 'Go');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', 'Le gestionnaire n\'existe pas<br />Module : %s<br />Nom : %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'La classe <b>%s</b> n\'existe pas<br />Nom du gestionnaire : %s');
define('_CORE_NOMODULE', 'Aucun module n\'est chargé');
define('_CORE_PAGENOTDISPLAYED', 'Cette page ne peut pas être affichée en raison d\'une erreur interne.<br/><br/>Vous pouvez fournir les informations suivantes aux administrateurs de ce site pour les aider à résoudre le problème :<br /><br />Erreur : %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Validation du jeton');
define('_CORE_TOKENNOVALID', 'Aucun jeton valide trouvé dans la requête/session');
define('_CORE_TOKENINVALID', 'Aucun jeton valide trouvé dans la requête/session');
define('_CORE_TOKENISVALID', 'Jeton valide trouvé');
define('_CORE_TOKENEXPIRED', 'Jeton valide expiré');
define('_CORE_CLASSNOTINSTANIATED', 'Cette classe ne peut pas être instanciée !');

define('_CORE_DB_NOTRACE', 'notrace:extension mysql non chargée');
define('_CORE_DB_NOTALLOWEDINGET', 'Les mises à jour de la base de données ne sont pas autorisées lors du traitement d\'une requête GET');
define('_CORE_DB_NOTRACEDB', 'notrace:Impossible de se connecter à la base de données');
define('_CORE_DB_INVALIDEMAIL', 'Addresse email invalide');
define('_CORE_PASSLEVEL1','Trop court');
define('_CORE_PASSLEVEL2','Faible');
define('_CORE_PASSLEVEL3','Bon');
define('_CORE_PASSLEVEL4','Fort');
define('_CORE_UNAMEPASS_IDENTIC','Nom d\'utilisateur et mot de passe identiques.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' les fichiers ont été ajoutés');
define('_CORE_CHECKSUM_FILES_REMOVED',' les fichiers ont été supprimés');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' les fichiers ont été modifiés ou supprimés');
define('_CORE_CHECKSUM_CHECKFILE','Vérification par rapport au fichier ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' les fichiers ont eu leurs permissions modifiées');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'Le fichier contenant les sommes de contrôle est indisponible ou illisible. La validation ne peut pas être terminée');
define('_CORE_CHECKSUM_ADDING',' Ajouter');
define('_CORE_CHECKSUM_CHECKSUM',' Somme de contrôle');
define('_CORE_CHECKSUM_PERMISSIONS',' Permissions');

define('_CORE_DEPRECATED', 'Déprécié');
define('_CORE_DEPRECATED_REPLACEMENT', 'utiliser %s à la place');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
define('_CORE_DEPRECATED_MSG', '%s en %s, ligne %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Appelé par: ');
define('_CORE_REMOVE_IN_VERSION', 'Ce sera supprimé dans la version %s');
define('_CORE_DEBUG', 'Debug');
define('_CORE_DEVELOPER_DASHBOARD', 'Tableau de bord développeur');
