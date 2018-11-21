<?php
// $Id: groups.php 10326 2010-07-11 18:54:25Z malanciault $
//%%%%%%	Admin Module Name  AdminGroup 	%%%%%
if (!defined('_AM_DBUPDATED')) {define('_AM_DBUPDATED', 'Database is succesvol bijgewerkt!');
}

define('_AM_EDITADG', 'Groepen beheer');
define('_AM_MODIFY', 'Wijzigen');
define('_AM_DELETE', 'Verwijderen');
define('_AM_CREATENEWADG', 'Nieuwe groep aanmaken');
define('_AM_NAME', 'Naam');
define('_AM_DESCRIPTION', 'Omschrijving');
define('_AM_INDICATES', '* geeft verplicht veld aan');
define('_AM_SYSTEMRIGHTS', 'Systeembeheer rechten');
define('_AM_ACTIVERIGHTS', 'Modulebeheer rechten');
define('_AM_IFADMIN', 'Als systeembeheer rechten is aangevinkt, wordt het toegangsrecht voor de module altijd geactiveerd.');
define('_AM_ACCESSRIGHTS', 'Module toegangsrechten');
define('_AM_UPDATEADG', 'Gebruikersgroep bijwerken');
define('_AM_MODIFYADG', 'Gebruikersgroep bewerken');
define('_AM_DELETEADG', 'Gebruikersgroep verwijderen');
define('_AM_AREUSUREDEL', 'Weet u zeker dat u deze gebruikersgroep wilt verwijderen?');
define('_AM_YES', 'Ja');
define('_AM_NO', 'Nee');
define('_AM_EDITMEMBER', 'Beheer leden van deze gebruikersgroep');
define('_AM_MEMBERS', 'Leden');
define('_AM_NONMEMBERS', 'Niet-leden');
define('_AM_ADDBUTTON', ' toevoegen --> ');
define('_AM_DELBUTTON', '<-- verwijderen');
define('_AM_UNEED2ENTER', 'Verplichte velden moeten worden ingevuld!');
// Added in RC3
define('_AM_BLOCKRIGHTS', 'Toegangsrechten blokken');
define('_AM_FINDU4GROUP', 'zoek gebruikers voor deze groep');
define('_AM_GROUPSMAIN', 'Groepen beheer');
define('_AM_ADMINNO', 'Er dient minimaal een gebruiker in de webmasters groep te zitten.');
# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_SBLEFT', 'Zijblok - Links');
define('_AM_SBRIGHT', 'Zijblok - Rechts');
define('_AM_CBLEFT', 'Middenblok - Links');
define('_AM_CBRIGHT', 'Middenblok - Rechts');
define('_AM_CBCENTER', 'Middenblok - Midden');
define('_AM_CBBOTTOMLEFT', 'Middenblok - Linksonder');
define('_AM_CBBOTTOMRIGHT', 'Middenblok - Rechtsonder');
define('_AM_CBBOTTOM', 'Middenblok - Middenonder');
#

define('_AM_EDPERM', 'WYSIWYG editor kan worden gebruikt in de volgende modules');
define('_AM_DEBUG_PERM', 'De Debug Modus is ingeschakeld voor de volgende modules');
define('_AM_GROUPMANAGER_PERM', 'Groepen beheer rechten');
// Added Since 1.2
define('_MD_AM_ID', 'ID');
define('_AM_SBLEFT_ADMIN', 'Administratie zijde blok - Links');
define('_AM_SBRIGHT_ADMIN', 'Administratie zijde blok - Rechts');
define('_AM_CBLEFT_ADMIN', 'Administratie centrum blok - Links');
define('_AM_CBRIGHT_ADMIN', 'Administratie centrum blok - Rechts');
define('_AM_CBCENTER_ADMIN', 'Administratie centrum blok - Midden');
define('_AM_CBBOTTOMLEFT_ADMIN', 'Administratie centrum blok - Linksonder');
define('_AM_CBBOTTOMRIGHT_ADMIN', 'Administratie centrum blok - Rechtsonder');
define('_AM_CBBOTTOM_ADMIN', 'Administratie centrum blok - Onder');
?>