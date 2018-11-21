<?php
// $Id: images.php 8533 2009-04-11 10:08:35Z icmsunderdog $
//%%%%%% Image Manager %%%%%


define('_MD_IMGMAIN', 'Gestionnaire d\'images');
//Image Manager Main

define('_MD_ADDIMGCAT', 'Ajouter une cat&eacute;gorie d\'images:');
define('_MD_EDITIMGCAT', 'Modifier une cat&eacute;gorie d\'images:');
define('_MD_IMGCATNAME', 'Nom de la cat&eacute;gorie:');
define('_MD_IMGCATRGRP', 'S&eacute;lectionnez les groupes autoris&eacute;s &agrave; utiliser le gestionnaire d\'images :<br /><br /><span style="font-weight: normal;">Ces groupes sont autoris&eacute;s &agrave; utiliser le gestionnaire d\'images mais pas a en t&eacute;l&eacute;charger. Seul le Webmasteur a automatiquement acc&egrave;s.</span>');
define('_MD_IMGCATWGRP', 'S&eacute;lectionnez les groupes autoris&eacute;s &agrave; t&eacute;l&eacute;charger des images:<br /><br /><span style="font-weight: normal;">Normalement pour les groupes de mod&eacute;rateurs de forums et administrateurs du site.</span>');
define('_MD_IMGCATWEIGHT', 'l\'ordre d\'affichage dans le gestionnaire d\'images :');
define('_MD_IMGCATDISPLAY', 'Afficher cette cat&eacute;gorie ?');
define('_MD_IMGCATSTRTYPE', 'Configuration de l\'images t&eacute;l&eacute;charg&eacute;:');
define('_MD_STRTYOPENG', 'Attention ! ne pourra plus &ecirc;tre changer apr&egrave;s !');
define('_MD_INDB', ' Conserver dans la base de donn&eacute;es (sous forme binaire de "donn&eacute;es" )');
define('_MD_ASFILE', ' Conserver comme fichier g&eacute;n&eacute;ralement dans le r&eacute;pertoire uploads<br />');
define('_MD_RUDELIMGCAT', '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer cette cat&eacute;gorie et tous ses fichiers images ?');
define('_MD_RUDELIMG', '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer ces fichiers ?');
define('_MD_FAILDEL', '&Eacute;chec de la suppression de l\'image %s de la base de donn&eacute;es');
define('_MD_FAILDELCAT', '&Eacute;chec de la suppression de la cat&eacute;gorie d\'images %s de la base de donn&eacute;es');
define('_MD_FAILUNLINK', '&Eacute;chec de suppression de l\'image %s dans le r&eacute;pertoire du serveur');
######################## Added in 1.2 ###################################
define('_MD_FAILADDCAT', 'Echec de suppression de la catégorie');
define('_MD_FAILEDIT', 'Echec d\'édition de l\'image');
define('_MD_FAILEDITCAT', 'Echec d\'édition de la catégorie');
define('_MD_IMGCATPARENT', 'Catégorie Principale:');
define('_MD_DELETEIMGCAT', 'Supprimer catégorie d\'images');
define('_MD_ADDIMGCATBTN', 'Créer nouvelle catégorie');
define('_MD_ADDIMGBTN', 'Créer nouvel image');
define('_MD_IMAGESIN', 'Images dans %s');
define('_MD_IMAGESTOT', '<b>Nombre totale d\'images:</b> %s');
define('_MD_IMAGECATID', 'ID');
define('_MD_IMAGECATNAME', 'Titre');
define('_MD_IMGCATFOLDERNAME', 'Nom répertoire');
define('_MD_IMGCATFOLDERNAME_DESC', 'N\'utilisez pas d\'espaces ou charactères spéciaux!');
define('_MD_IMAGECATMSIZE', 'Taille max');
define('_MD_IMAGECATMWIDTH', 'Largeur max');
define('_MD_IMAGECATMHEIGHT', 'Hauteur max');
define('_MD_IMAGECATDISP', 'Afficher');
define('_MD_IMAGECATSTYPE', 'Type opslaan');
define('_MD_IMAGECATATUORESIZE', 'Auto schalen');
define('_MD_IMAGECATWEIGHT', 'Poids');
define('_MD_IMAGECATOPTIONS', 'Options');
define('_MD_IMAGECATQTDE', '# Images');
define('_IMAGEFILTERS', 'Selectionner un filtre');
define('_IMAGEAPPLYFILTERS', 'Appliquer filtres dans l\'image');
define('_IMAGEFILTERSSAVE', 'Remplaçer image d\'origine');
define('_IMGCROP', 'Outil de coupure');
define('_IMGFILTER', 'Outil de filtre');
define('_MD_IMAGECATSUBS', 'Sous-categories');
define('_WIDTH', 'Largeur');
define('_HEIGHT', 'Hauteur');
define('_DIMENSION', 'Dimensions');
define('_CROPTOOL', 'Inspecteur de coupure');
define('_IMGDETAILS', 'Détails de l\'image');
define('_INSTRUCTIONS', 'Instructions');
define('_INSTRUCTIONS_DSC', '????');
define('_MD_IMAGE_EDITORTITLE', 'DHTML Editeur d\'images');
define('_MD_IMAGE_VIEWSUBS', 'Afficher sous-catégories');
define('_MD_IMAGE_COPYOF', 'Copie de');
define('IMANAGER_FILE', 'Fichier');
define('IMANAGER_WIDTH', 'Largeur');
define('IMANAGER_HEIGHT', 'Hauteur');
define('IMANAGER_SIZE', 'Taille');
define('IMANAGER_ORIGINAL', 'Image Originale');
define('IMANAGER_EDITED', 'Image adaptée');
define('IMANAGER_FOLDER_NOT_WRITABLE', 'Ecriture par le serveur impossible dans le répertoire ');
?>