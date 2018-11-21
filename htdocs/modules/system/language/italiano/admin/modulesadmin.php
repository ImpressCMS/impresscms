<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// $Id: modulesadmin.php 2 2005-11-02 18:23:29Z skalpa $
//%%%%%%	File Name  modulesadmin.php 	%%%%%
define("_MD_AM_MODADMIN","Amministrazione Moduli");
define("_MD_AM_MODULE","Modulo");
define("_MD_AM_VERSION","Versione");
define("_MD_AM_LASTUP","Ultimo Aggiornamento");
define("_MD_AM_DEACTIVATED","Disattivato");
define("_MD_AM_ACTION","Azione");
define("_MD_AM_DEACTIVATE","Disattiva");
define("_MD_AM_ACTIVATE","Attiva");
define("_MD_AM_UPDATE","Aggiorna");
define("_MD_AM_DUPEN","Query doppie nelle tabelle dei moduli!");
define("_MD_AM_DEACTED","Il modulo selezionato &egrave; stato disattivato con successo! Adesso, se vuoi, puoi disinstallare il modulo.");
define("_MD_AM_ACTED","Il modulo selezionato &egrave; stato attivato con successo!");
define("_MD_AM_UPDTED","Il modulo selezionato &egrave; stato aggiornato con successo!");
define("_MD_AM_SYSNO","Il modulo Sistema non pu&ograve; essere disattivato.");
define("_MD_AM_STRTNO","Questo modulo &egrave; impostato come modulo di default nella pagina iniziale. Per favore modifica il modulo iniziale secondo le tue esigenze.");

// added in RC2
define("_MD_AM_PCMFM","Per favore conferma:");

// added in RC3
define("_MD_AM_ORDER","Ordine");
define("_MD_AM_ORDER0","(0 = nascondi)");
define("_MD_AM_ACTIVE","Attivo");
define("_MD_AM_INACTIVE","Non attivo");
define("_MD_AM_NOTINSTALLED","Non installato");
define("_MD_AM_NOCHANGE","Nessuna modifica");
define("_MD_AM_INSTALL","Installa");
define("_MD_AM_UNINSTALL","Disinstalla");
define("_MD_AM_SUBMIT","Invia");
define("_MD_AM_CANCEL","Annulla");
define("_MD_AM_DBUPDATE","Database aggiornato con successo!");
define("_MD_AM_BTOMADMIN","Torna alla pagina 'Amministrazione Moduli'");

// %s represents module name
define("_MD_AM_FAILINS","Non &egrave; possibile installare %s.");
define("_MD_AM_FAILACT","Non &egrave; possibile attivare %s.");
define("_MD_AM_FAILDEACT","Non &egrave; possibile disattivare %s.");
define("_MD_AM_FAILUPD","Non &egrave; possibile aggiornare %s.");
define("_MD_AM_FAILUNINS","Non &egrave; possibile disinstallare %s.");
define("_MD_AM_FAILORDER","Non &egrave; possibile riordinare %s.");
define("_MD_AM_FAILWRITE","Non &egrave; possibile scrivere il file del menu principale.");
define("_MD_AM_ALEXISTS","Il modulo %s esiste gi&agrave;.");
define("_MD_AM_ERRORSC", "Errori:");
define("_MD_AM_OKINS","Il modulo %s &egrave; stato installato con successo!");
define("_MD_AM_OKACT","Il modulo %s &egrave; stato attivato con successo!");
define("_MD_AM_OKDEACT","Il modulo %s &egrave; stato disattivato con successo!");
define("_MD_AM_OKUPD","Il modulo %s &egrave; stato aggiornato con successo!");
define("_MD_AM_OKUNINS","Il modulo %s &egrave; stato disinstallato con successo!");
define("_MD_AM_OKORDER","Il modulo %s &egrave; stato modificato con successo!");

define('_MD_AM_RUSUREINS', 'Premi il pulsante qui sotto per installare questo modulo');
define('_MD_AM_RUSUREUPD', 'Premi il pulsante qui sotto per aggiornare questo modulo');
define('_MD_AM_RUSUREUNINS', 'Sei certo di voler disinstallare questo modulo?');
define('_MD_AM_LISTUPBLKS', 'I seguenti blocchi verranno aggiornati.<br />Seleziona i blocchi il cui contenuto (opzioni e template) pu&ograve; essere sovrascritto.<br />');
define('_MD_AM_NEWBLKS', 'Nuovi blocchi');
define('_MD_AM_DEPREBLKS', 'Blocchi deprecati');

define('_MD_AM_MODULESADMIN_SUPPORT', 'Sito di supporto modulo');
define('_MD_AM_MODULESADMIN_STATUS', 'Status');
define('_MD_AM_MODULESADMIN_MODULENAME', 'Nome modulo');
define('_MD_AM_MODULESADMIN_MODULETITLE', 'Titolo modulo');

######################## Added in 1.2 ###################################
define('_MD_AM_FAILINSTEMP','ERRORE: Non &egrave; possibile inserire il template <b>%s</b> nel database.');
define('_MD_AM_FAILUPDTEMP','ERRORE: Non &egrave; possibile aggiornare il template <b>%s</b>.');
define('_MD_AM_INSTEMP','Template <b>%s</b> aggiunto al database. (ID: <b>%s</b>)');
define('_MD_AM_FAILCOMPTEMP','ERRORE: la compilazione del template <b>%s</b> &egrave; fallita.');
define('_MD_AM_COMPTEMP','Il template <b>%s</b> &egrave; stato compilato.');
define('_MD_AM_FAILINSTEMPFILE','ERRORE: non &egrave; possibile inserire il template <b>%s</b> nel database.');
define('_MD_AM_INSTEMPFILE','Template <b>%s</b> aggiunto al database. (ID: <b>%s</b>)');
define('_MD_AM_FAILCOMPTEMPFILE','ERRORE: la compilazione del template <b>%s</b> &egrave; fallita.');
define('_MD_AM_COMPTEMPFILE','Il template <b>%s</b> &egrave; stato compilato.');
define('_MD_AM_RECOMPTEMPFILE','Il template <b>%s</b> &egrave; stato ricompilato.');
define('_MD_AM_NOTRECOMPTEMPFILE','ERRORE: non &egrave; possibile ricompilare il template <b>%s</b>.');
define('_MD_AM_TEMPINS','Il template <b>%s</b> &egrave; stato inserito nel database.');
define('_MD_AM_MOD_DATA_UPDATED',' I dati del modulo sono stati aggiornati.');
define('_MD_AM_MOD_UP_TEM','Templates in aggiornamento ...');
define('_MD_AM_MOD_REBUILD_BLOCKS','Ricostruzione blocchi ...');
define('_MD_AM_INSTALLED', 'Moduli installati');
define('_MD_AM_NONINSTALL', 'Moduli disinstallati');
define('_MD_AM_NOTDELTEMPFILE', 'ERRORE: non &egrave; possibile cancellare il vecchio template <b>%s</b>. Aggiornamento di questo file abortito.');
define('_MD_AM_COULDNOTUPDATE', 'ERRORE: non &egrave; possibile aggiornare <b>%s</b>.');
define('_MD_AM_BLOCKUPDATED', 'Blocco <b>%s</b> aggiornato. Block ID: <b>%s</b>.');
define('_MD_AM_BLOCKCREATED', 'Blocco <b>%s</b> creato. Block ID: <b>%s</b>.');
//update process
define('_MD_AM_IMAGESFOLDER_UPDATE_TITLE', 'La cartella Image Manager deve essere scrivibile');
define('_MD_AM_IMAGESFOLDER_UPDATE_TEXT', 'La nuova versione del manager image cambia la posizione delle tue immagini. Quest\'aggiornamento prover&agrave; a spostare le immagini nel posto giusto ma richiede che la cartella che le accoglier&agrave; abbia i permessi di scrittura. Sei pregato di impostare i corretti permessi prima di cliccare il bottone aggiorna. <br /><b>Cartella Image Manager</b>: %s');
define('_MD_AM_PLUGINSFOLDER_UPDATE_TITLE', 'Cartella Plugins/Preloads deve essere scrivibile');
define('_MD_AM_PLUGINSFOLDER_UPDATE_TEXT', 'La nuova versione di ImpressCMS cambia la posizione dei preloads. Quest\'aggiornamento prover&agrave; a spostare i tuoi preloads al giusto posto ma richiede che la cartella che li accoglier&agrave; abbia i giusti permessi di scrittura. Sei pregato di impostare i permessi per le cartelle prima di cliccare il bottone aggiorna.<br /><b>Cartella Plugins</b>: %s<br /><b>cartella Preloads</b>: %s');

// Added and Changed in 1.3
define("_MD_AM_UPDATE_FAIL","Impossibile aggiornare %s.");
define('_MD_AM_FUNCT_EXEC','Funzione %s eseguita con successo.');
define('_MD_AM_FAIL_EXEC','Esecuzione di %s fallita.');
define('_MD_AM_INSTALLING','Installazione di corso di ');
define('_MD_AM_SQL_NOT_FOUND', 'File SQL non trovato in %s');
define('_MD_AM_SQL_FOUND', "File SQL trovato in %s . <br  /> Creazione tavola in corso ...");
define('_MD_SQL_NOT_VALID', ' non è un SQL valido!');
define('_MD_AM_TABLE_CREATED', 	'Tavola %s creata.');
define('_MD_AM_DATA_INSERT_SUCCESS', 'Dati inseriti nella tavola %s.');
define('_MD_AM_RESERVED_TABLE', '%s è una tavola riservata!');
define('_MD_AM_DATA_INSERT_FAIL', 'Non è possibile inserire %s nel database.');
define('_MD_AM_CREATE_FAIL', 'ERRORE: non è possibile creare %s');

define('_MD_AM_MOD_DATA_INSERT_SUCCESS', 'Dati del modulo inseriti con successo. ID modulo: %s');

define('_MD_AM_BLOCK_UPDATED', 'Blocco %s aggiornato. ID blocco: %s.');
define('_MD_AM_BLOCK_CREATED', 'Blocco %s creato. ID blocco: %s.');

define('_MD_AM_BLOCKS_ADDING', 'Blocchi in creazione ...');
define('_MD_AM_BLOCKS_ADD_FAIL', 'ERRORE: non è stato possibile aggiungere blocchi %1$s al database! Errore di database: %2$s');
define('_MD_AM_BLOCK_ADDED', 'Blocco %1$s aggiunto. ID blocco: %2$s');
define('_MD_AM_BLOCKS_DELETE', 'Blocco in cancellazione ...');
define('_MD_AM_BLOCK_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare il blocco %1$s. ID blocco: %2$s');
define('_MD_AM_BLOCK_DELETED', 'Blocco %1$s cancellato. ID Blocco: %2$s');
define('_MD_AM_BLOCK_TMPLT_DELETE_FAILED', 'ERRORE: non è stato possibile cancellare il template blocco %1$s  dal database. Template ID: %2$s');
define('_MD_AM_BLOCK_TMPLT_DELETED', 'Template blocco %1$s  cancellato dal database. ID template: %2$s');
define('_MD_AM_BLOCK_ACCESS_FAIL', 'ERRORE: non è stato possibile aggiungere un diritto di accesso al blocco. ID blocco: %1$s  ID gruppo: %2$s');
define('_MD_AM_BLOCK_ACCESS_ADDED', 'Aggiunto diritto di accesso del blocco. ID blocco: %1$s, ID gruppo: %2$s');

define('_MD_AM_CONFIG_ADDING', 'Aggiunta dati di configurazione del modulo in corso ...');
define('_MD_AM_CONFIGOPTION_ADDED', 'Opzione di configurazione aggiunta. Nome: %1$s Valore: %2$s');
define('_MD_AM_CONFIG_ADDED', 'Configurazione %s  aggiunta al database.');
define('_MD_AM_CONFIG_ADD_FAIL', 'ERRORE: non è stato possibile inserire la configurazione %s al database.');

define('_MD_AM_PERMS_ADDING', 'Impostazioni dei diritti di gruppo in corso ...');
define('_MD_AM_ADMIN_PERM_ADD_FAIL', 'ERRORE: Non è stato possibile aggiungere diritti di accesso amministrativi per il gruppo ID %s');
define('_MD_AM_ADMIN_PERM_ADDED', 'Sono stati aggiunti diritti di accesso amministrativi per il gruppo ID %s');
define('_MD_AM_USER_PERM_ADD_FAIL', 'ERRORE: Non è stato possibile aggiungere diritti di accesso utente per il gruppo ID ID: %s');
define('_MD_AM_USER_PERM_ADDED', 'Sono stati aggiunti diritti di accesso utente per il gruppo ID: %s');

define('_MD_AM_AUTOTASK_FAIL', 'ERRORE: Non è stato possibile inserire la funzione automatica al database. Name: %s');
define('_MD_AM_AUTOTASK_ADDED', 'È stata aggiunta una funzione alla lista. Nome funzione: %s');
define('_MD_AM_AUTOTASK_UPDATE', 'Funzioni automatiche in aggiornamento ...');
define('_MD_AM_AUTOTASKS_DELETE', 'Funzioni automatiche in cancellazione ...');

define('_MD_AM_SYMLINKS_DELETE', 'Links in cancellazione dal Symlink Manager...');
define('_MD_AM_SYMLINK_DELETE_FAIL', 'ERRORE: Non è stato possibile cancellare il link %1$s dal database. Link ID: %2$s');
define('_MD_AM_SYMLINK_DELETED', 'Link %1$s cancellato dal database. Link ID: %2$s');

define('_MD_AM_DELETE_FAIL', 'ERRORE: Non è stato possibile cancellare %s');


define('_MD_AM_TEMPLATE_INSERT_FAIL','ERRORE: non è stato possibile inserire il template %s al database.');
define('_MD_AM_TEMPLATE_UPDATE_FAIL','ERRORE: non è stato possibile aggiornare il template %s.');
define('_MD_AM_TEMPLATE_INSERTED','Template %s aggiunto al database. (ID: %s)');
define('_MD_AM_TEMPLATE_COMPILE_FAIL','ERRORE: impossibile compilare il template %s.');
define('_MD_AM_TEMPLATE_COMPILED','Template %s compilato.');
define('_MD_AM_TEMPLATE_RECOMPILED','Template %s ricompilato.');
define('_MD_AM_TEMPLATE_RECOMPILE_FAIL','ERRORE: non è stato possibile ricompilare il template %s.');

define('_MD_AM_TEMPLATES_ADDING', 'Templates in corso di aggiunta ...');
define('_MD_AM_TEMPLATES_DELETE', 'Cancellazione dei templates in corso ...');
define('_MD_AM_TEMPLATE_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare il template %1$s dal database. Template ID: %2$s');
define('_MD_AM_TEMPLATE_DELETED', 'Template %1$s  cancellato dal database. Template ID: %2$s');
define('_MD_AM_TEMPLATE_UPDATED', 'Template %s aggiornato.');

define('_MD_AM_MOD_TABLES_DELETE', 'Tavole del modulo in corso di cancellazione ...');
define('_MD_AM_MOD_TABLE_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare la tavola %s');
define('_MD_AM_MOD_TABLE_DELETED', 'Tavola %s eliminata.');
define('_MD_AM_MOD_TABLE_DELETE_NOTALLOWED', 'ERRORE: Non è permesso eliminare la tavola %s!');

define('_MD_AM_COMMENTS_DELETE', 'Commenti in corso di cancellazione ...');
define('_MD_AM_COMMENT_DELETE_FAIL', 'ERRORE: non è possibile cancellare i commenti');
define('_MD_AM_COMMENT_DELETED', 'Commenti cancellati');

define('_MD_AM_NOTIFICATIONS_DELETE', 'Notifiche in corso di cancellazione ...');
define('_MD_AM_NOTIFICATION_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare le notifiche');
define('_MD_AM_NOTIFICATION_DELETED', 'Notifiche cancellate');

define('_MD_AM_GROUPPERM_DELETE', 'Permessi del gruppo in corso di cancellazione ...');
define('_MD_AM_GROUPPERM_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare i permessi del gruppo');
define('_MD_AM_GROUPPERM_DELETED', 'Permessi del gruppo cancellati');

define('_MD_AM_CONFIGOPTIONS_DELETE', 'Opzioni di configurazione del modulo in corso di cancellazione ...');
define('_MD_AM_CONFIGOPTION_DELETE_FAIL', 'ERRORE: non è stato possibile cancellare i dati di configurazione dal database. Configurazioni ID: %s');
define('_MD_AM_CONFIGOPTION_DELETED', 'I dati di configurazione sono stati cancellati dal database. Config ID: %s');
