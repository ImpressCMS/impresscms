<?php
// $Id: blocksadmin.php 8889 2009-06-19 14:38:08Z pesianstranger $
//%%%%%%	Admin Module Name  Blocks 	%%%%%
if (!defined('_AM_DBUPDATED')) {
    if (!defined('_AM_DBUPDATED')) {
        define('_AM_DBUPDATED', 'Base de donn&eacute;es mise &agrave; jour avec succ&egrave;s!');
    }
}

//%%%%%%	blocks.php 	%%%%%
define('_AM_BADMIN', 'Administration des blocs');
# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_BPADMIN', 'Gestion de la position des blocs');
define('_AM_ADDBLOCK', 'Ajouter un nouveau bloc');
define('_AM_LISTBLOCK', 'Liste de tous les blocs');
define('_AM_SIDE', 'Cot&eacute;');
define('_AM_BLKDESC', 'Description du bloc');
define('_AM_TITLE', 'Titre');
define('_AM_WEIGHT', 'Poids');
define('_AM_ACTION', 'Action');
define('_AM_BLKTYPE', 'Position du bloc');
define('_AM_LEFT', 'Gauche');
define('_AM_RIGHT', 'Droite');
define('_AM_CENTER', 'Centre');
define('_AM_VISIBLE', 'Visible');
define('_AM_POSCONTT', 'Position du contenu additionnel');
define('_AM_ABOVEORG', 'Au dessus du contenu original');
define('_AM_AFTERORG', 'Au dessous du contenu original');
define('_AM_EDIT', 'Editer');
define('_AM_DELETE', 'Effacer');
define('_AM_SBLEFT', 'Bloc de cot&eacute; - gauche');
define('_AM_SBRIGHT', 'Bloc de cot&eacute; - droite');
define('_AM_CBLEFT', 'Bloc central - gauche');
define('_AM_CBRIGHT', 'Bloc central - droite');
define('_AM_CBCENTER', 'Bloc central - centr&eacute;');
define('_AM_CBBOTTOMLEFT', 'Bloc central - bas gauche');
define('_AM_CBBOTTOMRIGHT', 'Bloc central - bas droit');
define('_AM_CBBOTTOM', 'Bloc central - bas');
define('_AM_CONTENT', 'Contenu');
define('_AM_OPTIONS', 'Options');
define('_AM_CTYPE', 'Type de contenu');
define('_AM_HTML', 'HTML');
define('_AM_PHP', 'Script PHP');
define('_AM_AFWSMILE', 'Format automatique(&eacute;motic&ocirc;nes activ&eacute;s)');
define('_AM_AFNOSMILE', 'Format automatique (&eacute;motic&ocirc;nes d&eacute;sactiv&eacute;s)');
define('_AM_SUBMIT', 'Valider');
define('_AM_CUSTOMHTML', 'Bloc personnalis&eacute; (HTML)');
define('_AM_CUSTOMPHP', 'Bloc personnalis&eacute; (PHP)');
define('_AM_CUSTOMSMILE', 'Bloc personnalis&eacute; (Format Auto + &eacute;motic&ocirc;nes)');
define('_AM_CUSTOMNOSMILE', 'Bloc personnalis&eacute; (Format Auto)');
define('_AM_DISPRIGHT', 'Afficher uniquement les blocs de droite');
define('_AM_SAVECHANGES', 'Sauver les changements');
define('_AM_EDITBLOCK', 'Editer un bloc');
define('_AM_SYSTEMCANT', 'Le bloc syst&egrave;eme ne peut &ecirc;tre supprim&eacute; !');
define('_AM_MODULECANT', 'Ce bloc ne peut &ecirc;tre supprim&eacute; directement ! Si vous voulez d&eacute;sactiver ce bloc, d&eacute;sactivez le module.');
define('_AM_RUSUREDEL', 'Etes-vous s&ucirc;r de vouloir supprimer le bloc <b>%s</b>?');
define('_AM_NAME', 'Nom');
define('_AM_USEFULTAGS', 'Balises utiles :');
define('_AM_BLOCKTAG1', '%s afficheras %s');
define('_AM_SVISIBLEIN', 'Affiches les blocs visibles dans %s');
define('_AM_TOPPAGE', 'Top page');
define('_AM_VISIBLEIN', 'Visible dans');
define('_AM_ALLPAGES', 'Toutes les pages');
define('_AM_TOPONLY', 'Top Page uniquement');
define('_AM_ADVANCED', 'Param&egrave;tres avanc&eacute;s');
define('_AM_BCACHETIME', 'Temps de cache');
define('_AM_BALIAS', 'Nom de l\'alias');
define('_AM_CLONE', 'Cloner');
// kloon een blok
define('_AM_CLONEBLK', 'Clon&eacute;');
// cloned block
define('_AM_CLONEBLOCK', 'Cr&eacute;er un bloc clone');
define('_AM_NOTSELNG', '\'%s\' n\'est pas s&eacute;lectionn&eacute; !');
// foutmelding
define('_AM_EDITTPL', 'Editer le template');
define('_AM_MODULE', 'Module');
define('_AM_GROUP', 'Groupe');
define('_AM_UNASSIGNED', 'Non affect&eacute;');
define('_AM_CHANGESTS', 'Changer la visibilit&eacute; de bloc');
######################## Added in 1.2 ###################################
define('_AM_BLOCKS_PERMGROUPS', 'Groupes permis de visualiser ce bloc');
/**
 * The next Language definitions are included since 2.0 of blockadmin module, because now is based on IPF.
 * TODO: Add the rest of the fields, are added only the ones wich are shown.
 */
// Texts

// Actions
define('_AM_SYSTEM_BLOCKSADMIN_CREATE', 'Créer nouveau bloc');
define('_AM_SYSTEM_BLOCKSADMIN_EDIT', 'Adapter bloc');
define('_AM_SYSTEM_BLOCKSADMIN_MODIFIED', 'Bloc adapté!');
define('_AM_SYSTEM_BLOCKSADMIN_CREATED', 'Bloc créé');
// Fields
define('_CO_SYSTEM_BLOCKSADMIN_NAME', 'Nom');
define('_CO_SYSTEM_BLOCKSADMIN_NAME_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_TITLE', 'Titre');
define('_CO_SYSTEM_BLOCKSADMIN_TITLE_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_MID', 'Module');
define('_CO_SYSTEM_BLOCKSADMIN_MID_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_VISIBLE', 'Visible');
define('_CO_SYSTEM_BLOCKSADMIN_VISIBLE_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_CONTENT', 'Contenu');
define('_CO_SYSTEM_BLOCKSADMIN_CONTENT_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_SIDE', 'Coté');
define('_CO_SYSTEM_BLOCKSADMIN_SIDE_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_WEIGHT', 'Poids');
define('_CO_SYSTEM_BLOCKSADMIN_WEIGHT_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE', 'Type de bloc');
define('_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_C_TYPE', 'Type de contenu');
define('_CO_SYSTEM_BLOCKSADMIN_C_TYPE_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_OPTIONS', 'Options');
define('_CO_SYSTEM_BLOCKSADMIN_OPTIONS_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_BCACHETIME', 'Temps cache du bloc');
define('_CO_SYSTEM_BLOCKSADMIN_BCACHETIME_DSC', '');
define('_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS', 'Droit de visualisation du bloc');
define('_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS_DSC', 'Selectionner quels groupes ont la permission de visualiser ce bloc. Ceci implique qu\'un utilisateur contenu dans un de ces groupes pourra voir le bloc quand c\'est activé sur la page');
define('_AM_SBLEFT_ADMIN', 'Administration bloc lateral - gauche');
define('_AM_SBRIGHT_ADMIN', 'Administration bloc lateral - droit');
define('_AM_CBLEFT_ADMIN', 'Administration bloc central - gauche');
define('_AM_CBRIGHT_ADMIN', 'Administration bloc central - droit');
define('_AM_CBCENTER_ADMIN', 'Administration bloc central - centre');
define('_AM_CBBOTTOMLEFT_ADMIN', 'Administration bloc central - Gauche-dessous');
define('_AM_CBBOTTOMRIGHT_ADMIN', 'Administration bloc central - Droit-dessous');
define('_AM_CBBOTTOM_ADMIN', 'Administration bloc central - dessous');
