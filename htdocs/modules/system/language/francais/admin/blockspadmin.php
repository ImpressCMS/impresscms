<?php
// fichier:  .../modules/system/admin/blockspadmin/main.php
//Gestion de la position des blocs
define("_AM_DBUPDATED","Base de donn&eacute;es mise &agrave; jour avec succ&egrave;s!");
define('_AM_BPADMIN',"Gestion de la position des blocs");
define('_AM_BPCOD',"ID");
define('_AM_BPNAME',"nom du positionnement");
define('_AM_BPDESC',"Description de la position");
define('_AM_ADDPBLOCK',"Ajoutez une nouvelle position de bloc");
define('_AM_EDITPBLOCK',"&Eacute;ditez la position du bloc");
define('_AM_PBNAME_DESC',"Nom de la nouvelle position du bloc, Attention ! c'est avec ce nom que devra &ecirc;tre cr&eacute;&eacute; le code &agrave; insser&eacute; dans le th&egrave;me.<br/>Utilisez un nom en <b>minuscule,</b> <b>sans espaces,</b> <b>sans accent,</b> et <b>sans caract&egrave;res sp&eacute;ciaux.</b>");
define('_AM_BPMSG1',"Op&eacute;ration r&eacute;alis&eacute; avec succ&egrave;s!");
define('_AM_BPMSG2',"Probl&egrave;mes ! des erreurs se sont produites pendant l'op&eacute;rations.");
define('_AM_BPMSG3',"&Ecirc;tes-vous s&ucirc;r de vouloir supprimer la position de ce bloc ?");
define('_AM_BPHELP','Pour afficher la nouvelle position du bloc sur le th&egrave;me, copier puis coller le code exemple &ccedil;i-dessous ou vous le souhaitez dans votre th&egrave;me. Ensuite Modifiez <b>nom_du_positionnement</b> et <b>le_chemin_du_dossier_du_th&egrave;me/fichier pour afficher les blocs</b>
<center><div style="border: 1px dashed #AABBCC; padding:10px; background: #FFF6BF; text-align: left; width:86%;">
<{foreach from=$xoBlocks.<b>nom_du_positionnement</b> item=block}><br /><{include file="<b>le_chemin_du_dossier_du_th&egrave;me/fichier pour afficher les blocs</b>"}><br /><{/foreach}>
</div></center>
');
define("_AM_TITLE","Titre du bloc");
define("_AM_NAME","Nom");
define("_AM_ACTION","<center>Action</center>");
define("_AM_EDIT","Modifier");
define("_AM_DELETE","Effacer");
define("_AM_SBLEFT","Bloc de cot&eacute; - gauche");
define("_AM_SBRIGHT","Bloc de cot&eacute; - droite");
define("_AM_CBLEFT","Bloc central - gauche");
define("_AM_CBRIGHT","Bloc central - droite");
define("_AM_CBCENTER","Bloc central - centr&eacute;");
define("_AM_CBBOTTOMLEFT","Bloc central - bas gauche");
define("_AM_CBBOTTOMRIGHT","Bloc central - bas droite");
define("_AM_CBBOTTOM","Bloc central - bas");
define("_AM_SUBMIT","Valider");

define('_AM_SYSTEM_BLOCKSPADMIN_CORE', 'Core position: it cannot be edited or deleted');

define('_AM_SYSTEM_BLOCKSPADMIN_TITLE',"Block Positions Administration");
define('_AM_SYSTEM_BLOCKSPADMIN_CREATED', "New Block Position Created");
define('_AM_SYSTEM_BLOCKSPADMIN_MODIFIED', "Block Position Modified");
define('_AM_SYSTEM_BLOCKSPADMIN_CREATE',"Add New Block Position");
define('_AM_SYSTEM_BLOCKSPADMIN_EDIT',"Edit Block Position");
define('_AM_SYSTEM_BLOCKSPADMIN_INFO','To include the new block positions on the theme, put the code bellow in the place where it desires that the blocks appear.
<div style="border: 1px dashed #AABBCC; padding:10px; width:86%;">
<{foreach from=$xoBlocks.<b>name_of_position</b> item=block}><br /><{include file="<b>path_to_theme_folder/file_to_show_blocks.html</b>"}><br /><{/foreach}>
</div>
');
define("_CO_SYSTEM_BLOCKSPADMIN_ID", "Id");
define("_CO_SYSTEM_BLOCKSPADMIN_TITLE", "Title");
define("_CO_SYSTEM_BLOCKSPADMIN_PNAME", "Position Name");
define('_CO_SYSTEM_BLOCKSPADMIN_PNAME_DSC',"Name of Block Position, it is with this name that will have to be created the Loop in the theme for the exhibition of blocks.<br/>Use a name with small_caption letters, without spaces and special characters.");
define("_CO_SYSTEM_BLOCKSPADMIN_DESCRIPTION", "Description");
define("_AM_SBLEFT_ADMIN","Admin Side Block - Left");
define("_AM_SBRIGHT_ADMIN","Admin Side Block - Right");
define("_AM_CBLEFT_ADMIN","Admin Center Block - Left");
define("_AM_CBRIGHT_ADMIN","Admin Center Block - Right");
define("_AM_CBCENTER_ADMIN","Admin Center Block - Center");
define("_AM_CBBOTTOMLEFT_ADMIN","Admin Center Block - Bottom left");
define("_AM_CBBOTTOMRIGHT_ADMIN","Admin Center Block - Bottom right");
define("_AM_CBBOTTOM_ADMIN","Admin Center Block - Bottom");

?>
