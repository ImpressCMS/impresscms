<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by evoc - webmaster of ImpressCMS
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// $Id: userrank.php 1145 2007-12-04 09:43:26Z phppp $
//%%%%%%	Admin Module Name  UserRank 	%%%%%
define("_AM_DBUPDATED",_MD_AM_DBUPDATED);
define("_AM_RANKSSETTINGS","Amministrazione Livelli Utente");
define("_AM_TITLE","Livello");
define("_AM_MINPOST","Numero minimo di messaggi");
define("_AM_MAXPOST","Numero massimo di messaggi");
define("_AM_IMAGE","Immagine");
define("_AM_SPERANK","Livello speciale");
define("_AM_ON","Attivo");
define("_AM_OFF","Non attivo");
define("_AM_EDIT","Modifica");
define("_AM_DEL","Elimina");
define("_AM_ADDNEWRANK","Aggiungi un nuovo livello");
define("_AM_RANKTITLE","Nome del livello");
define("_AM_SPECIAL","Speciale");
define("_AM_ADD","Aggiungi");
define("_AM_EDITRANK","Modifica livelli");
define("_AM_ACTIVE","Attiva");
define("_AM_SAVECHANGE","Salva modifiche");
define("_AM_WAYSYWTDTR","ATTENZIONE: Sei certo di voler eliminare questo livello?");
define("_AM_YES","S&igrave;");
define("_AM_NO","No");
define("_AM_VALIDUNDER","(Un'immagine valida nella cartella <b>%s</b>)");
define("_AM_SPECIALCAN","(Possono essere assegnati agli utenti dei livelli 'speciali' che non dipendono dal numero effettivo di messaggi inviati)");
define("_AM_ACTION","Azione");

define("_AM_RANKW","Larghezza max %s pixels");
define("_AM_RANKH","Altezza max %s pixels");
define("_AM_RANKMAX","Max dimensione file %s bytes");
// $Id: userrank.php 9536 2009-11-13 18:59:32Z pesianstranger $

######################## Added in 1.2 ###################################
define('_CO_ICMS_USERRANKS','Impostazioni Livelli Utenti');
define('_CO_ICMS_USERRANKS_DSC', 'Lista di livelli per gli utenti disponibile nel sistema.');
define('_CO_ICMS_USERRANK', 'Livelli utente');
define('_CO_ICMS_USERRANKS_CREATE', 'Aggiungi un livello utente');
define('_CO_ICMS_USERRANKS_CREATE_INFO', 'Riempi questo modulo per aggiungere un nuovo livello utente nel sistema.');
define('_CO_ICMS_USERRANKS_DELETE_CONFIRM', 'Vuoi veramente eliminare questo livello utente dal sistema?');
define('_CO_ICMS_USERRANKS_EDIT', 'Modifica un livello utente');
define('_CO_ICMS_USERRANKS_EDIT_INFO', 'Utilizza questo modulo per modificare le informazioni di questo livello utente.');
define('_CO_ICMS_USERRANK_INFO', 'Informazione livello utente');
define('_CO_ICMS_USERRANK_NOT_FOUND', 'Il livello utente selezionato non è stato trovato.');

define('_CO_ICMS_USERRANKS_CREATED', 'Il livello utente è stato aggiunto con successo.');
define('_CO_ICMS_USERRANKS_MODIFIED', 'Il livello utente è stato modificato con successo.');

define('_CO_ICMS_USERRANK_RANK_SPECIAL','Livelli speciali');
define('_CO_ICMS_USERRANK_RANK_SPECIAL_DSC','(I livelli speciali possono essere attribuiti a utenti senza tenere conto del numero di posts inviati)');
define('_CO_ICMS_USERRANK_RANK_TITLE','Titolo livello');
define('_CO_ICMS_USERRANK_RANK_TITLE_DSC','');
define('_CO_ICMS_USERRANK_RANK_MIN','Min. Posts');
define('_CO_ICMS_USERRANK_RANK_MIN_DSC', '');
define('_CO_ICMS_USERRANK_RANK_MAX','Max. Posts');
define('_CO_ICMS_USERRANK_RANK_MAX_DSC', '');
define('_CO_ICMS_USERRANK_RANK_IMAGE','Immagine');
define('_CO_ICMS_USERRANK_RANK_IMAGE_DSC', '');

define('_CO_ICMS_USERRANK_EXPLAIN_TITLE', 'Cosa sono i livelli utenti?');
define('_CO_ICMS_USERRANK_EXPLAIN', 'I livelli utenti sono immagini usate per differenziare nel sito gli utenti in livelli. Una documentazione completa può essere trovata qui: <a rel="external" href="http://wiki.impresscms.org/index.php?title=User_ranks">User ranks</a>.');
