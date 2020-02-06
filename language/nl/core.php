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
define('_CORE_KILOBYTES', 'Kilobytes');
define('_CORE_MEGABYTES', 'Megabytes');
define('_CORE_GIGABYTES', 'Gigabytes');
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

define('_CORE_DB_NOTRACE', 'notrace:mysql extensie niet geladen');
define('_CORE_DB_NOTALLOWEDINGET', 'Database updates zijn niet toegestaan gedurende de uitvoering van een GET verzoek');
define('_CORE_DB_NOTRACEDB', 'notrace: Niet mogelijk om te verbinden met database');
define('_CORE_DB_INVALIDEMAIL', 'Ongeldige e-mail');
define('_CORE_PASSLEVEL1','Te kort');
define('_CORE_PASSLEVEL2','Zwak');
define('_CORE_PASSLEVEL3','Goed');
define('_CORE_PASSLEVEL4','Sterk');
define('_CORE_UNAMEPASS_IDENTIC','Gebruikernaam en wachtwoord zijn identiek.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' bestanden zijn toegevoegd');
define('_CORE_CHECKSUM_FILES_REMOVED',' bestanden werden verwijderd');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' bestanden werden aangepast of verwijderd');
define('_CORE_CHECKSUM_CHECKFILE','Controleren op bestand ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' de rechten van de bestanden zijn gewijzigd');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'Het bestand met de checksums is niet beschikbaar of onleesbaar. De validatie kan niet worden voltooid');
define('_CORE_CHECKSUM_ADDING',' Toevoegen');
define('_CORE_CHECKSUM_CHECKSUM',' Checksum');
define('_CORE_CHECKSUM_PERMISSIONS',' Rechten');

define('_CORE_DEPRECATED', 'Verouderd');
define('_CORE_DEPRECATED_REPLACEMENT', 'gebruik %s in de plaats');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
define('_CORE_DEPRECATED_MSG', '%s in %s, regel %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Oproep door: ');
define('_CORE_REMOVE_IN_VERSION', 'Dit zal worden verwijderd in versie %s');
define('_CORE_DEBUG', 'Debug');
define('_CORE_DEVELOPER_DASHBOARD', 'Ontwikkelaar Dashboard');
