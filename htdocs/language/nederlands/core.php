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

define('_CORE_MEMORYUSAGE', 'Geheugen gebruik');
define('_CORE_BYTES', 'bytes');
define('_CORE_KILOBYTES', 'Kilo Bytes');
define('_CORE_MEGABYTES', 'Mega Bytes');
define('_CORE_GIGABYTES', 'Giga Bytes');
define('_CORE_KILOBYTES_SHORTEN', 'Kb');
define('_CORE_MEGABYTES_SHORTEN', 'Mb');
define('_CORE_GIGABYTES_SHORTEN', 'Gb');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', 'Handler bestaat niet<br />Module: %s<br />Naam: %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'Klasse <b>%s</b> bestaat niet<br />Handler Naam: %s');
define('_CORE_NOMODULE', 'Er is geen module geladen');
define('_CORE_PAGENOTDISPLAYED', 'Deze pagina kan niet worden weergegeven door een interne fout.<br/><br/>U kunt de volgende informatie verstrekken aan de beheerder van deze website om het probleem te helpen oplossen:<br /><br />Fout: %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Teken beoordelen');
define('_CORE_TOKENNOVALID', 'Geen geldig teken in het verzoek/sessie gevonden');
define('_CORE_TOKENINVALID', 'Geen geldig teken in het verzoek/sessie gevonden');
define('_CORE_TOKENISVALID', 'Geldig teken gevonden');
define('_CORE_TOKENEXPIRED', 'Geldig teken is verlopen');
define('_CORE_CLASSNOTINSTANIATED', 'Deze class kan niet worden verzocht!');
define('_CORE_OID_INSESSIONS', 'we hebben al een openid_response in SESSIE');
define('_CORE_OID_FETCHING', 'Verkrijg het antwoord van de OpenID server');
define('_CORE_OID_STATCANCEL', 'OOI Server antwoord status is Auth_OpenID_CANCEL');
define('_CORE_OID_VERIFCANCEL', 'Beoordeling afgebroken.');
define('_CORE_OID_SERVERFAILED', 'OOI antwoord status van de Server is Auth_OpenID_FAILURE');
define('_CORE_OID_FAILED', 'OpenID authentificatie mislukt: ');
define('_CORE_OID_DUMPREQ', 'Weergeven van de AANVRAAG');
define('_CORE_OID_SUCESSFULLYIDENTIFIED', 'U heeft zich met succes geïdentificeerd als %s (%s)
als u identiteit.');
define('_CORE_OID_SERVERSUCCESS', 'OpenID Server antwoord status is Auth_OpenID_SUCCESS');
define('_CORE_OID_DISPID', 'weergaveid: ');
define('_CORE_OID_OPENID', 'openid: ');
define('_CORE_OID_DUMPING', 'weergeven sreg info');
define('_CORE_OID_CANONID', '  (XRI CanonicalID: %s) ');
define('_CORE_OID_STEPIS', 'Stap is ');
define('_CORE_OID_CHECKINGID', 'Controleren of er een gebruiker is met deze OpenID');
define('_CORE_OID_FOUNDSTEPIS', 'Gebruiker gevonden, stap is nu ');
define('_CORE_OID_NOTFOUNDSTEPIS', 'Geen gebruiker gevonden voor deze OpenID, stap is nu ');
define('_CORE_DB_NOTRACE', 'notrace:mysql extensie niet geladen');
define('_CORE_DB_NOTALLOWEDINGET', 'Database updates zijn niet toegestaan gedurende de uitvoering van een GET verzoek');
define('_CORE_DB_NOTRACEDB', 'notrace: Niet mogelijk om te verbinden met database');
define('_CORE_DB_INVALIDEMAIL', 'Ongeldige e-mail');
define('_CORE_PASSLEVEL1','Te kort');
define('_CORE_PASSLEVEL2','Zwak');
define('_CORE_PASSLEVEL3','Goed');
define('_CORE_PASSLEVEL4','Sterk');
define('_CORE_UNAMEPASS_IDENTIC','Gebruikernaam en wachtwoord zijn identiek.');
?>