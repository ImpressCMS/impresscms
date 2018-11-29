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

define('_CORE_MEMORYUSAGE', 'Utilizzo della memoria: %s');
define('_CORE_BYTES', 'bytes');
define('_CORE_KILOBYTES', 'Kilo Bytes');
define('_CORE_MEGABYTES', 'Mega Bytes');
define('_CORE_GIGABYTES', 'Giga Bytes');
define('_CORE_KILOBYTES_SHORTEN', 'Kb');
define('_CORE_MEGABYTES_SHORTEN', 'Mb');
define('_CORE_GIGABYTES_SHORTEN', 'Gb');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', 'Non esiste handler<br />Modulo: %s<br />Nome: %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'Class <b>%s</b> Non esiste<br />Handler Nome: %s');
define('_CORE_NOMODULE', 'Non si è caricato nessun modulo');
define('_CORE_PAGENOTDISPLAYED', 'Questa pagina non può essere visualizzata a causa di un errore interno.<br/><br/>Puoi fornire all\'amministratore del sito la seguente informazione utile a risolvere il problema:<br /><br />Errore: %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Convalida Token');
define('_CORE_TOKENNOVALID', 'Nessun token valido è stato trovato nella richiesta/sessione');
define('_CORE_TOKENINVALID', 'Nessun token valido è stato trovato nella richiesta/sessione');
define('_CORE_TOKENISVALID', 'Nessun token valido è stato trovato');
define('_CORE_TOKENEXPIRED', 'Token valido scaduto');
define('_CORE_CLASSNOTINSTANIATED', 'Questa classe non può essere instanziata!');
define('_CORE_OID_INSESSIONS', 'esiste già una openid_response in SESSION');
define('_CORE_OID_FETCHING', 'richiesta in atto dal OID server');
define('_CORE_OID_STATCANCEL', 'OOI Server status risposta: Auth_OpenID_CANCEL');
define('_CORE_OID_VERIFCANCEL', 'Verifica cancellata.');
define('_CORE_OID_SERVERFAILED', 'OOI Server status risposta: Auth_OpenID_FAILURE');
define('_CORE_OID_FAILED', 'Autenticazione OpenID fallita: ');
define('_CORE_OID_DUMPREQ', 'Outputing the REQUEST');
define('_CORE_OID_SUCESSFULLYIDENTIFIED', 'Sei stato verificato con successo con l\'identit&agrave; di %s (%s).');
define('_CORE_OID_SERVERSUCCESS', 'OOI Server response status is Auth_OpenID_SUCCESS');
define('_CORE_OID_DISPID', 'displayid: ');
define('_CORE_OID_OPENID', 'openid: ');
define('_CORE_OID_DUMPING', 'dumping sreg info');
define('_CORE_OID_CANONID', '  (XRI CanonicalID: %s) ');
define('_CORE_OID_STEPIS', 'Step is ');
define('_CORE_OID_CHECKINGID', 'Verifica in corso se abbiamo un utente con questo OpenID');
define('_CORE_OID_FOUNDSTEPIS', 'Trovato un utente, il prossimo passo &egrave; ');
define('_CORE_OID_NOTFOUNDSTEPIS', 'Nessun utente trovato con questo OpenID, il prossimo passo &egrave; ');
define('_CORE_DB_NOTRACE', 'notrace: estensione mysql non caricata');
define('_CORE_DB_NOTALLOWEDINGET', 'Gli aggiornamenti del database non sono consentiti durante una procedura di richiesta GET');
define('_CORE_DB_NOTRACEDB', 'notrace: connessione al database impossibile');
define('_CORE_DB_INVALIDEMAIL', 'Email non valida');
define('_CORE_PASSLEVEL1','Troppo breve');
define('_CORE_PASSLEVEL2','Debole');
define('_CORE_PASSLEVEL3','Buona');
define('_CORE_PASSLEVEL4','Forte');
define('_CORE_UNAMEPASS_IDENTIC','Il nome utente e la password sono identici.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' files sono stati aggiunti');
define('_CORE_CHECKSUM_FILES_REMOVED',' files sono stati rimossi');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' files sono stati modificati o rimossi');
define('_CORE_CHECKSUM_CHECKFILE','File in corso di controllo ');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' files hanno ricevuto modifiche nei permessi');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', 'Il file contenente il checksum non è disponibile o è illeggibile. La convalida non può essere completata');
define('_CORE_CHECKSUM_ADDING',' in corso di aggiunta');
define('_CORE_CHECKSUM_CHECKSUM',' Checksum');
define('_CORE_CHECKSUM_PERMISSIONS',' Permessi');

define('_CORE_DEPRECATED', 'Deprecato');
define('_CORE_DEPRECATED_REPLACEMENT', 'usa %s invece');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
define('_CORE_DEPRECATED_MSG', '%s in %s, linea %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Chiamato da: ');
define('_CORE_REMOVE_IN_VERSION', 'Questo sarà rimosso nella versione %s');
define('_CORE_DEBUG', 'Debug');
define('_CORE_DEVELOPER_DASHBOARD', 'Cruscotto sviluppatore');

define('_CORE_OID_URL_EXPECTED', 'Auspicata un URL OpenID.');
define("_CORE_OID_URL_INVALID", 'Errore di autenticazione: OpenID non valida.');
define("_CORE_OID_REDIRECT_FAILED", 'Potrebbe non ridirezionare al server: %s');
define("_CORE_OID_INPROGRESS", "Transazione OpenID in progresso");
