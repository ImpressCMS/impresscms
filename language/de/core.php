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

define('_CORE_DB_NOTRACE', 'notrace:mysql Erweiterung nicht geladen');
define('_CORE_DB_NOTALLOWEDINGET', 'Datenbank-Aktualisierungen sind während der Bearbeitung einer GET-Anfrage nicht erlaubt');
define('_CORE_DB_NOTRACEDB', 'notrace:Keine Verbindung zur Datenbank möglich');
define('_CORE_DB_INVALIDEMAIL', 'Ungültige E-Mail');
define('_CORE_PASSLEVEL1','Zu kurz');
define('_CORE_PASSLEVEL2','Schwach');
define('_CORE_PASSLEVEL3','Gut');
define('_CORE_PASSLEVEL4','Stark');
define('_CORE_UNAMEPASS_IDENTIC','Benutzername und Passwort identisch.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' Benutzer wurden hinzugefügt');
define('_CORE_CHECKSUM_FILES_REMOVED',' Dateien wurden entfernt');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' Dateien wurden geändert oder entfernt');
define('_CORE_CHECKSUM_CHECKFILE','Überprüfung der Datei ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' Von den Dateien wurden ihre Berechtigungen geändert');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'Die Datei mit den Prüfsummen ist nicht verfügbar oder nicht lesbar. Validierung kann nicht abgeschlossen werden');
define('_CORE_CHECKSUM_ADDING',' Hinzufügen');
define('_CORE_CHECKSUM_CHECKSUM',' Prüfsumme');
define('_CORE_CHECKSUM_PERMISSIONS',' Berechtigungen');

define('_CORE_DEPRECATED', 'Veraltet');
define('_CORE_DEPRECATED_REPLACEMENT', 'stattdessen %s verwenden');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Anrufstapel <br />');
define('_CORE_DEPRECATED_MSG', '%s in %s, Zeile %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Aufgerufen von: ');
define('_CORE_REMOVE_IN_VERSION', 'Dies wird in Version %s entfernt');
define('_CORE_DEBUG', 'Debug Modus');
define('_CORE_DEVELOPER_DASHBOARD', 'Entwickler Dashboard');
