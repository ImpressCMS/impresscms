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

define('_CORE_MEMORYUSAGE', 'Uso de memoria');
define('_CORE_BYTES', 'bytes');
define('_CORE_KILOBYTES', 'Kilo Bytes');
define('_CORE_MEGABYTES', 'Mega Bytes');
define('_CORE_GIGABYTES', 'Bytes');
define('_CORE_KILOBYTES_SHORTEN', 'Kb');
define('_CORE_MEGABYTES_SHORTEN', 'Mb');
define('_CORE_GIGABYTES_SHORTEN', 'Gb');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', 'Handler no existe<br />Módulo: %s<br />Nombre: %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'Clase <b>%s</b> no existe<br />Nombre del Manejador: %s');
define('_CORE_NOMODULE', 'Ningún módulo ha sido cargado');
define('_CORE_PAGENOTDISPLAYED', 'Esta página no se puede mostrar debido a un error interno.<br/><br/>Puede proporcionar la siguiente información a los administradores de este sitio para ayudarles a resolver el problema:<br /><br />Error: %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Validación del token');
define('_CORE_TOKENNOVALID', 'No se encontró un token válido en la solicitud/sesión');
define('_CORE_TOKENINVALID', 'No se encontró un token válido en la solicitud/sesión');
define('_CORE_TOKENISVALID', 'Token válido encontrado');
define('_CORE_TOKENEXPIRED', 'Token válido caducó');
define('_CORE_CLASSNOTINSTANIATED', '¡Esta clase no puede ser instanciada!');

define('_CORE_DB_NOTRACE', 'notrace:la extensión ql no se ha cargado');
define('_CORE_DB_NOTALLOWEDINGET', 'Las actualizaciones de la base de datos no están permitidas durante el procesamiento de una solicitud GET');
define('_CORE_DB_NOTRACEDB', 'notrace:No se puede conectar a la base de datos');
define('_CORE_DB_INVALIDEMAIL', 'Invalid Email');
define('_CORE_PASSLEVEL1','Muy corto');
define('_CORE_PASSLEVEL2','Débil');
define('_CORE_PASSLEVEL3','Bueno');
define('_CORE_PASSLEVEL4','Fuerte');
define('_CORE_UNAMEPASS_IDENTIC','Nombre de usuario y contraseña idénticos.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' archivos han sido añadidos');
define('_CORE_CHECKSUM_FILES_REMOVED',' archivos han sido eliminados');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' archivos han sido alterados o eliminados');
define('_CORE_CHECKSUM_CHECKFILE','Comprobando contra el archivo ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' los archivos han cambiado sus permisos');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'El archivo que contiene las sumas de comprobación no está disponible o no es legible. La validación no se puede completar');
define('_CORE_CHECKSUM_ADDING',' Agregando');
define('_CORE_CHECKSUM_CHECKSUM',' Comprobación');
define('_CORE_CHECKSUM_PERMISSIONS',' Permisos');

define('_CORE_DEPRECATED', 'Desaprobado');
define('_CORE_DEPRECATED_REPLACEMENT', 'usar %s en su lugar');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Pila de llamadas: <br />');
define('_CORE_DEPRECATED_MSG', '%s en %s, línea %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Llamado por: ');
define('_CORE_REMOVE_IN_VERSION', 'Esto se eliminará en la versión %s');
define('_CORE_DEBUG', 'Debug');
define('_CORE_DEVELOPER_DASHBOARD', 'Panel de desarrolladores');
