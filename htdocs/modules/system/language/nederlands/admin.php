<?php
// $Id: admin.php 11023 2011-02-15 01:30:36Z skenow $
//%%%%%%	File Name  admin.php 	%%%%%
//define('_MD_AM_CONFIG', 'System Configuration');
define('_INVALID_ADMIN_FUNCTION', 'Ongeldige Admin Functie');
// Admin Module Names
define('_MD_AM_ADGS', 'Gebruiker groepen');
define('_MD_AM_BANS', 'Banners');
define('_MD_AM_BKAD', 'Blokken');
define('_MD_AM_MDAD', 'Modules');
define('_MD_AM_SMLS', 'Smilies');
define('_MD_AM_RANK', 'Gebruiker status');
define('_MD_AM_USER', 'Bewerk gebruiker');
define('_MD_AM_FINDUSER', 'Zoek gebruiker');
define('_MD_AM_PREF', 'Instellingen');
define('_MD_AM_VRSN', 'Versie checker');
define('_MD_AM_MLUS', 'E-mail gebruikers');
define('_MD_AM_IMAGES', 'Afbeeldingen manager');
define('_MD_AM_AVATARS', 'Pasfoto\'s');
define('_MD_AM_TPLSETS', 'Templates');
define('_MD_AM_COMMENTS', 'Reacties');
define('_MD_AM_BKPOSAD', 'Blok posities');
define('_MD_AM_PAGES', 'Symlink beheer');
define('_MD_AM_CUSTOMTAGS', 'Standaard tags');
// Group permission phrases
define('_MD_AM_PERMADDNG', 'Kon Gebruikers-groeprechten niet toevoegen (Permissie: [%s] Gebruikersgroep: [%s])');
define('_MD_AM_PERMADDOK', 'Gebruikers-groeprechten succesvol toegevoegd (Permissie: [%s] Gebruikersgroep: [%s])');
define('_MD_AM_PERMRESETNG', 'Het was niet mogelijk de gebruikers-groeprechten voor %s te resetten');
define('_MD_AM_PERMADDNGP', 'Alle moeder items moeten geselecteerd worden.');
// added in 1.2
if (!defined('_MD_AM_AUTOTASKS')) {define('_MD_AM_AUTOTASKS', 'Automatische Taken');
}
define('_MD_AM_ADSENSES', 'Adsenses');
define('_MD_AM_RATINGS', 'Beoordelingen');
define('_MD_AM_MIMETYPES', 'Mime Types');
// added in 1.3
define('_MD_AM_GROUPS_ADVERTISING', 'Advertising');
define('_MD_AM_GROUPS_CONTENT', 'Content');
define('_MD_AM_GROUPS_LAYOUT', 'Layout');
define('_MD_AM_GROUPS_MEDIA', 'Media');
define('_MD_AM_GROUPS_SITECONFIGURATION', 'Site Configuratie');
define('_MD_AM_GROUPS_SYSTEMTOOLS', 'Systeem Hulpmiddelen');
define('_MD_AM_GROUPS_USERSANDGROUPS', 'Gebruikers en Groepen');
define('_MD_AM_ADSENSES_DSC', 'Adsenses zijn tags die zelf kunnen gedefinieerd worden, en die overal op de website kunnen gebruikt worden.');
define('_MD_AM_AUTOTASKS_DSC', 'Auto Tasks laten toe om acties te plannen om automatisch door het systeem te laten uitgevoeren');
define('_MD_AM_AVATARS_DSC', 'Beheer de portretten die beschikbaar zijn voor de website gebruikers');
define('_MD_AM_BANS_DSC', 'Beheer advertentie campagnes en adverteerder rekeningen');
define('_MD_AM_BKPOSAD_DSC', 'Beheer en creëer blok posities die gebruikt worden door de thema\'s van de website');
define('_MD_AM_BKAD_DSC', 'Beheer en creëer blokken om te gebruiker doorheen de website');
define('_MD_AM_COMMENTS_DSC', 'Beheer commentaren gemaakt door website bezoekers');
define('_MD_AM_CUSTOMTAGS_DSC', 'Custom Tags are tags die je om het even waar op de website kan gebruiken');
define('_MD_AM_USER_DSC', 'Creëer, wijzig en verwijder gebruikers');
define('_MD_AM_FINDUSER_DSC', 'Zoek door geregistreerde gebruikers met filters');
define('_MD_AM_ADGS_DSC', 'Beheer toegangen, leden, zichtbaarheid en toegangsrechten van gebruikersgroepen');
define('_MD_AM_IMAGES_DSC', 'Creëer afbeeldingsgroepen en beheer de toegangen voor elke groep. Bewerk opgeladen afbeeldingen.');
define('_MD_AM_MLUS_DSC', 'Verstuur mail naar gebruikersgroepen, of filter ontvangers op basis van criteria');
define('_MD_AM_MIMETYPES_DSC', 'Beheer de toegelaten bestandsextensies voor opgeladen bestanden');
define('_MD_AM_MDAD_DSC', 'Beheer module menu rang, status, naam en update modules wanneer nodig');
define('_MD_AM_RATINGS_DSC', 'Door deze tool te gebruiken kan u een nieuwe rating methode aan uw modules toevoegen, en de resultaten beheren in deze sectie.');
define('_MD_AM_SMLS_DSC', 'Beheer de beschikbare smilies en definieer de code die erbij gaat.');
define('_MD_AM_PAGES_DSC', 'Symlinks laten toe om een unieke link te associeren met elke pagina op de website. Dat laat dan toe om blokken aan die specifieke pagina toe te wijzen, of om rechtstreeks te linken in de content module.');
define('_MD_AM_TPLSETS_DSC', 'Templates zijn sets van HTMLCSS bestanden die bepalen wat hoe de modules op het scherm verschijnen');
define('_MD_AM_RANK_DSC', 'Gebruikersrangen zijn afbeeldingen die het mogelijk maken om verschillende niveaus van gebruikers te tonen op uw website');
define('_MD_AM_VRSN_DSC', 'Gebruik dit hulpmiddel om op updates te controleren voor uw system');
define('_MD_AM_PREF_DSC', 'ImpressCMS site voorkeuren');
