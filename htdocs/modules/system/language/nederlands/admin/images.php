<?php
// $Id: images.php 11445 2011-11-18 08:17:59Z sato-san $
//%%%%%% Image Manager %%%%%

define('_MD_IMGMAIN', 'Index Afbeeldingen Beheer');
define('_MD_ADDIMGCAT', 'Voeg een afbeeldingen categorie toe:');
define('_MD_EDITIMGCAT', 'Bewerk een afbeeldingen categorie:');
define('_MD_IMGCATNAME', 'Categorie Naam:');
define('_MD_IMGCATRGRP', 'Selecteer de gebruikersgroep(en) die gebruik mag/mogen maken van de afbeeldingen manager:<br /><br /><span style="font-weight: normal;">Dit is/zijn de groepen die toegang hebben tot de afbeeldingen manager voor het selecteren van afbeeldingen, maar niet over afbeeldingen uploadrechten beschikken. De Webmaster heeft automatisch toegang.</span>');
define('_MD_IMGCATWGRP', 'Selecteer de gebruikersgroep(en) die afbeeldingen mogen uploaden:<br /><br /><span style="font-weight: normal;">Het meest gebruikelijke is dat moderators en admin-groepen van deze toepassing gebruik mogen maken.</span>');
define('_MD_IMGCATWEIGHT', 'Volgorde in afbeeldingenmanager:');
define('_MD_IMGCATDISPLAY', 'Deze categorie tonen?');
define('_MD_IMGCATSTRTYPE', 'De afbeeldingen zijn ge-upload naar:');
define('_MD_STRTYOPENG', 'Dit kan achteraf niet meer worden gewijzigd!');
define('_MD_INDB', ' In de Database opslaan (als binary "blob" data. Niet aanbevolen)');
define('_MD_ASFILE', ' Als bestand opslaan (in de directorie "uploads". Aanbevolen.)<br />');
define('_MD_RUDELIMGCAT', 'Weet u zeker dat u deze categorie, inclusief alle afbeeldingen aanwezig in deze categorie, wilt verwijderen?');
define('_MD_RUDELIMG', 'Weet u zeker dat u deze afbeelding wilt verwijderen?');
define('_MD_FAILDEL', 'Het verwijderen van de afbeelding: %s uit de Database is mislukt.');
define('_MD_FAILDELCAT', 'Het verwijderen van de afbeeldingen categorie: %s uit de Database is mislukt.');
define('_MD_FAILUNLINK', 'Het verwijderen van de afbeelding: %s van de server (uploads-) directorie is mislukt.');
######################## Added in 1.2 ###################################
define('_MD_FAILADDCAT', 'Afbeeldingen categorie toevoegen mislukt');
define('_MD_FAILEDIT', 'Afbeelding bijwerken mislukt');
define('_MD_FAILEDITCAT', 'Categorie bijwerken mislukt');
define('_MD_IMGCATPARENT', 'Hoofd categorie:');
define('_MD_DELETEIMGCAT', 'Afbeeldingen categorie verwijderen');
define('_MD_ADDIMGCATBTN', 'Nieuwe categorie toevoegen');
define('_MD_ADDIMGBTN', 'Nieuwe afbeelding toevoegen');
define('_MD_IMAGESIN', 'Afbeeldingen in %s');
define('_MD_IMAGESTOT', '<b>Totaal aantal afbeeldingen:</b> %s');
define('_MD_IMAGECATID', 'ID');
define('_MD_IMAGECATNAME', 'Titel');
define('_MD_IMGCATFOLDERNAME', 'Map naam');
define('_MD_IMGCATFOLDERNAME_DESC', 'Gebruik geen spaties of speciale karakters!');
define('_MD_IMAGECATMSIZE', 'Max grootte');
define('_MD_IMAGECATMWIDTH', 'Max breedte');
define('_MD_IMAGECATMHEIGHT', 'Max hoogte');
define('_MD_IMAGECATDISP', 'Tonen');
define('_MD_IMAGECATSTYPE', 'Type opslaan');
define('_MD_IMAGECATATUORESIZE', 'Auto schalen');
define('_MD_IMAGECATWEIGHT', 'Gewicht');
define('_MD_IMAGECATOPTIONS', 'Opties');
define('_MD_IMAGECATQTDE', '# Afbeeldingen');
define('_IMAGEFILTERS', 'Selecteer een filter:');
define('_IMAGEAPPLYFILTERS', 'Filters toepassen in afbeelding');
define('_IMAGEFILTERSSAVE', 'Orginele afbeelding overschrijven?');
define('_IMGCROP', 'Bijsnijd gereedschap');
define('_IMGFILTER', 'Filter gereedschap');
define('_MD_IMAGECATSUBS', 'Sub-categoriën');
define('_WIDTH', 'Breedte');
define('_HEIGHT', 'Hoogte');
define('_DIMENSION', 'Dimensie');
define('_CROPTOOL', 'Bijsnijd inspector');
define('_IMGDETAILS', 'Afbeeldingsdetails');
define('_INSTRUCTIONS', 'Instructies');
define('_INSTRUCTIONS_DSC', 'Om het bij te snijden vlak te bepalen, sleep en verplaats de gestippelde rechthoek of voer de waarde direct in, in het formulier.');
define('_MD_IMAGE_EDITORTITLE', 'DHTML Afbeeldingen Editor');
define('_MD_IMAGE_VIEWSUBS', 'Bekijk sub-categoriën');
define('_MD_IMAGE_COPYOF', 'Kopie van ');
define('IMANAGER_FILE', 'Bestand');
define('IMANAGER_WIDTH', 'Breedte');
define('IMANAGER_HEIGHT', 'Hoogte');
define('IMANAGER_SIZE', 'Grootte');
define('IMANAGER_ORIGINAL', 'Originele afbeelding');
define('IMANAGER_EDITED', 'Aangepaste afbeelding');
define('IMANAGER_FOLDER_NOT_WRITABLE', 'Map is niet schrijfbaar door de server.');
// added in 1.3
define('IMANAGER_NOPERM', 'U hebt geen toegang tot dit gedeelte!');
