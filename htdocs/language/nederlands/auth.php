<?php
// $Id: auth.php 8548 2009-04-11 10:34:21Z icmsunderdog $
//%%%%%%		File Name auth.php 		%%%%%

define('_AUTH_MSG_AUTH_METHOD', 'gebruik %s authentificatie methode');
define('_AUTH_LDAP_EXTENSION_NOT_LOAD', 'PHP LDAP extensie niet geladen (controleer uw PHP configuratie bestand: php.ini)');
define('_AUTH_LDAP_SERVER_NOT_FOUND', 'Kan geen verbinding met de server tot stand brengen');
define('_AUTH_LDAP_USER_NOT_FOUND', 'Lid %s is niet gevonden in de bestanden op server (%s) in %s');
define('_AUTH_LDAP_CANT_READ_ENTRY', 'Kan ingaven %s niet lezen');
define('_AUTH_LDAP_XOOPS_USER_NOTFOUND', 'Er is geen corresponderende gebruikersinformatie gevonden in de ImpressCMS databank voor verbinding:
 %s <br />" .
		"Controleer alstublieft uw gebruikersgegevens of stel in op automatisch verkrijgen.');
define('_AUTH_LDAP_START_TLS_FAILED', 'Openen van een TLS verbinding is mislukt');
?>