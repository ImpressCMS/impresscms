<?php
// $Id: blocksadmin.php 8889 2009-06-19 14:38:08Z pesianstranger $
//%%%%%%	Admin Module Name  Blocks 	%%%%%
if(!defined('_AM_DBUPDATED')){if(!defined('_AM_DBUPDATED')){define("_AM_DBUPDATED","Database succesvol bijgewerkt!");}}

//%%%%%%	blocks.php 	%%%%%
define("_AM_BADMIN","Blokken beheer");

# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_BPADMIN',"Blokken positie beheer");

define("_AM_ADDBLOCK","Nieuwe blok toevoegen");
define("_AM_LISTBLOCK","Toon alle blokken");
define("_AM_SIDE","Positie");
define("_AM_BLKDESC","Blok omschrijving");
define("_AM_TITLE","Titel");
define("_AM_WEIGHT","Gewicht");
define("_AM_ACTION","Actie");
define("_AM_BLKTYPE","Positie");
define("_AM_LEFT","Links");
define("_AM_RIGHT","Rechts");
define("_AM_CENTER","Midden");
define("_AM_VISIBLE","Zichtbaar");
define("_AM_POSCONTT","Positie van de ingevoegde inhoud");
define("_AM_ABOVEORG","Boven de originele inhoud");
define("_AM_AFTERORG","Onder de originele inhoud");
define("_AM_EDIT","Bewerken");
define("_AM_DELETE","Verwijderen");
define("_AM_SBLEFT","Zijblok - links");
define("_AM_SBRIGHT","Zijblok - rechts");
define("_AM_CBLEFT","Middenblok - links");
define("_AM_CBRIGHT","Middenblok - rechts");
define("_AM_CBCENTER","Middenblok - midden");
define("_AM_CBBOTTOMLEFT","Middenblok - linksonder");
define("_AM_CBBOTTOMRIGHT","Middenblok - rechtsonder");
define("_AM_CBBOTTOM","Middenblok - onder");
define("_AM_CONTENT","Inhoud");
define("_AM_OPTIONS","Opties");
define("_AM_CTYPE","Type inhoud");
define("_AM_HTML","HTML");
define("_AM_PHP","PHP script");
define("_AM_AFWSMILE","Auto format (smilies ingeschakeld)");
define("_AM_AFNOSMILE","Auto format (smilies uitgeschakeld)");
define("_AM_SUBMIT","Toevoegen");
define("_AM_CUSTOMHTML","Standaard blok (HTML)");
define("_AM_CUSTOMPHP","Standaard blok (PHP)");
define("_AM_CUSTOMSMILE","Standaard blok (auto format + smilies)");
define("_AM_CUSTOMNOSMILE","Standaard blok (auto format)");
define("_AM_DISPRIGHT","Alleen rechterblokken tonen");
define("_AM_SAVECHANGES","Wijzigingen opslaan");
define("_AM_EDITBLOCK","Bewerk een blok");
define("_AM_SYSTEMCANT","Systeemblokken kunnen niet worden verwijderd!");
define("_AM_MODULECANT","Deactiveer eerst de module om het blok te kunnen verwijderen.");
define("_AM_RUSUREDEL","Weet u zeker dat het blok <b>%s</b> moet worden verwijderd?");
define("_AM_NAME","Naam");
define("_AM_USEFULTAGS","Handige tags:");
define("_AM_BLOCKTAG1","%s zal %s tonen");
define('_AM_SVISIBLEIN', 'Maak blokken zichtbaar in %s');
define('_AM_TOPPAGE', 'Beginpagina');
define('_AM_VISIBLEIN', 'Zichtbaar in');
define('_AM_ALLPAGES', "Alle pagina's");
define('_AM_TOPONLY', 'Alleen in de homepagina');
define('_AM_ADVANCED', 'Geavanceerde instellingen');
define('_AM_BCACHETIME', 'Cache altijddurend');
define('_AM_BALIAS', 'Alias naam');
define('_AM_CLONE', 'Klonen');  // kloon een blok
define('_AM_CLONEBLK', 'Gekloond'); // cloned block
define('_AM_CLONEBLOCK', 'Maak een gekloond blok aan');
define('_AM_NOTSELNG', "'%s' is niet geselecteerd!"); // foutmelding
define('_AM_EDITTPL', 'Bewerk de template');
define('_AM_MODULE', 'Module');
define('_AM_GROUP', 'Gebruikersgroep');
define('_AM_UNASSIGNED', 'Niet toegewezen');

define('_AM_CHANGESTS', 'Wijzig de zichtbaarheid van het blok');

######################## Added in 1.2 ###################################
define('_AM_BLOCKS_PERMGROUPS','Groups allowed to view this block');

/**
 * The next Language definitions are included since 2.0 of blockadmin module, because now is based on IPF.
 * TODO: Add the rest of the fields, are added only the ones wich are shown.
 */
// Texts

// Actions
define("_AM_SYSTEM_BLOCKSADMIN_CREATE", "Nieuw blok aanmaken");
define("_AM_SYSTEM_BLOCKSADMIN_EDIT", "Blok aanpassen");
define("_AM_SYSTEM_BLOCKSADMIN_MODIFIED", "Blok succesvol aangepast!");
define("_AM_SYSTEM_BLOCKSADMIN_CREATED", "Blok succesvol aangemaakt!");


// Fields
define("_CO_SYSTEM_BLOCKSADMIN_NAME", "Naam");
define("_CO_SYSTEM_BLOCKSADMIN_NAME_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE", "Titel");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_MID", "Module");
define("_CO_SYSTEM_BLOCKSADMIN_MID_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE", "Zichtbaar");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT", "Inhoud");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE", "Zijde");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT", "Gewicht");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE", "Blok type");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE", "Inhoud type");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS", "Opties");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME", "Blok Cache Tijd");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME_DSC", "");

define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS", "Blok zichtbaarheid rechten");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS_DSC", "Selecteer welke groepen toestemming hebben om dit blok te zien. Dit betekend dat een gebruiker behorend tot een van deze groepen het blok kan zien wanneer het geactiveerd is op de pagina.");

define("_AM_SBLEFT_ADMIN","Administratie zijde blok - Links");
define("_AM_SBRIGHT_ADMIN","Administratie zijde blok - Rechts");
define("_AM_CBLEFT_ADMIN","Administratie centrum blok - Links");
define("_AM_CBRIGHT_ADMIN","Administratie centrum blok - Rechts");
define("_AM_CBCENTER_ADMIN","Administratie centrum blok - Midden");
define("_AM_CBBOTTOMLEFT_ADMIN","Administratie centrum blok - Linksonder");
define("_AM_CBBOTTOMRIGHT_ADMIN","Administratie centrum blok - Rechtsonder");
define("_AM_CBBOTTOM_ADMIN","Administratie centrum blok - Onder");
?>