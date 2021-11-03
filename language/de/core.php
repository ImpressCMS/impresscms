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

define('_CORE_MEMORYUSAGE', 'Speichernutzung (PHP memory)');
define('_CORE_BYTES', 'Bytes');
define('_CORE_KILOBYTES', 'KByte');
define('_CORE_MEGABYTES', 'Mbytes');
define('_CORE_GIGABYTES', 'Giga Bytes');
define('_CORE_KILOBYTES_SHORTEN', 'KB');
define('_CORE_MEGABYTES_SHORTEN', 'MB');
define('_CORE_GIGABYTES_SHORTEN', 'GB');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', 'Handler existiert nicht<br />Modul: %s<br />Name: %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'Class <b>%s</b> existiert nicht<br />Handlername: %s');
define('_CORE_NOMODULE', 'Kein Modul ist geladen');
define('_CORE_PAGENOTDISPLAYED', 'Diese Seite kann aufgrund eines internen Fehlers nicht angezeigt werden.<br/><br/>Sie können den Administratoren dieser Seite folgende Informationen zur Verfügung stellen, um ihnen bei der Lösung des Problems zu helfen:<br /><br />Fehler: %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Token-Validierung');
define('_CORE_TOKENNOVALID', 'Kein gültiges Token in der Anfrage/Sitzung gefunden');
define('_CORE_TOKENINVALID', 'Kein gültiger Token in Anfrage/Sitzung gefunden');
define('_CORE_TOKENISVALID', 'Gültiges Token gefunden');
define('_CORE_TOKENEXPIRED', 'Gültiger Token abgelaufen');
define('_CORE_CLASSNOTINSTANIATED', 'Diese Klasse kann nicht instanziiert werden!');

define('_CORE_DB_NOTRACE', 'notrace:mysql extension not loaded');
define('_CORE_DB_NOTALLOWEDINGET', 'Database updates are not allowed during processing of a GET request');
define('_CORE_DB_NOTRACEDB', 'notrace:Unable to connect to database');
define('_CORE_DB_INVALIDEMAIL', 'Invalid Email');
define('_CORE_PASSLEVEL1','Too short');
define('_CORE_PASSLEVEL2','Weak');
define('_CORE_PASSLEVEL3','Good');
define('_CORE_PASSLEVEL4','Strong');
define('_CORE_UNAMEPASS_IDENTIC','Username and Password identical.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' files have been added');
define('_CORE_CHECKSUM_FILES_REMOVED',' files have been removed');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' files have been altered or removed');
define('_CORE_CHECKSUM_CHECKFILE','Checking against the file ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' files have had their permissions altered');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'The file containing the checksums is unavailable or unreadable. Validation cannot be completed');
define('_CORE_CHECKSUM_ADDING',' Adding');
define('_CORE_CHECKSUM_CHECKSUM',' Checksum');
define('_CORE_CHECKSUM_PERMISSIONS',' Permissions');

define('_CORE_DEPRECATED', 'Deprecated');
define('_CORE_DEPRECATED_REPLACEMENT', 'use %s instead');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
define('_CORE_DEPRECATED_MSG', '%s in %s, line %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Called by: ');
define('_CORE_REMOVE_IN_VERSION', 'This will be removed in version %s');
define('_CORE_DEBUG', 'Debug');
define('_CORE_DEVELOPER_DASHBOARD', 'Developer Dashboard');
