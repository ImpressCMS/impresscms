<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by evco - webmaster Impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// -------------------------------------------------------------------------------- //
// $Id: blocksadmin.php 506 2006-05-26 23:10:37Z skalpa $
//%%%%%%	Admin Module Name  Blocks 	%%%%%
define("_AM_DBUPDATED","Database aggiornato con successo!");

//%%%%%%	blocks.php 	%%%%%
define("_AM_BADMIN","Amministrazione Blocchi");

# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_BPADMIN',"Amministrazione posizione blocchi");
define('_AM_BPCOD',"ID");
define('_AM_BPNAME',"Nome della posizione");
define('_AM_BPDESC',"Descrizione della posizione");
define('_AM_ADDPBLOCK',"Aggiungi una nuova posizione di blocco");
define('_AM_EDITPBLOCK',"Modifica la posizione del blocco"); 
define('_AM_PBNAME_DESC',"Nome della posizione di blocco, &egrave; con questo nome che avrai creato una ripetizione nel tema per la esposizione dei blocchi.<br/>Usa un nome con lettere minuscole, senza spazi e caratteri speiciali o accentati.");
define('_AM_BPMSG1',"Operazione di creazione ultimata con successo!");
define('_AM_BPMSG2',"Si sono verificati problemi nell'operazione.");
define('_AM_BPMSG3',"Sei sicuro di voler eliminare questa posizione di blocco?");
define('_AM_BPHELP','Per includere il nuovo blocco nel tema, mettere il codice sottostante nel posto dove si desidera che appaia.');

define("_AM_ADDBLOCK","Aggiungi un nuovo blocco");
define("_AM_LISTBLOCK","Elenca tutti i blocchi");
define("_AM_SIDE","Colonna");
define("_AM_BLKDESC","Descrizione");
define("_AM_TITLE","Titolo");
define("_AM_WEIGHT","Peso");
define("_AM_ACTION","Azione");
define("_AM_BLKTYPE","Posizione");
define("_AM_LEFT","Sinistra");
define("_AM_RIGHT","Destra");
define("_AM_CENTER","Centrale");
define("_AM_VISIBLE","Visibile");
define("_AM_POSCONTT","Posizione del contenuto aggiuntivo");
define("_AM_ABOVEORG","Sopra il contenuto originale");
define("_AM_AFTERORG","Sotto il contenuto originale");
define("_AM_EDIT","Modifica");
define("_AM_DELETE","Elimina");
define("_AM_SBLEFT","Blocco laterale - Sinistro");
define("_AM_SBRIGHT","Blocco laterale - Destro");
define("_AM_CBLEFT","Blocco centrale - Sinistro");
define("_AM_CBRIGHT","Blocco centrale - Destro");
define("_AM_CBCENTER","Blocco centrale - Centrale");
define("_AM_CBBOTTOMLEFT","Blocco centrale - Basso Sinistro");
define("_AM_CBBOTTOMRIGHT","Blocco centrale - Basso Destro");
define("_AM_CBBOTTOM","Blocco centrale - Basso");
define("_AM_CONTENT","Contenuto");
define("_AM_OPTIONS","Opzioni");
define("_AM_CTYPE","Linguaggio del contenuto");
define("_AM_HTML","HTML");
define("_AM_PHP","PHP");
define("_AM_AFWSMILE","Formattazione automatica (faccine abilitate)");
define("_AM_AFNOSMILE","Formattazione automatica (faccine disabilitate)");
define("_AM_SUBMIT","Invia");
define("_AM_CUSTOMHTML","Blocco personalizzato (HTML)");
define("_AM_CUSTOMPHP","Blocco personalizzato (PHP)");
define("_AM_CUSTOMSMILE","Blocco personalizzato (Formattazione automatica + faccine)");
define("_AM_CUSTOMNOSMILE","Blocco personalizzato (Formattazione automatica)");
define("_AM_DISPRIGHT","Mostra solo i blocchi laterali di destra");
define("_AM_SAVECHANGES","Salva modifiche");
define("_AM_EDITBLOCK","Modifica un blocco");
define("_AM_SYSTEMCANT","I blocchi di sistema non possono essere eliminati!");
define("_AM_MODULECANT","Questo blocco non pu&ograve; essere eliminato direttamente! Se vuoi disabilitare questo blocco, disattiva il relativo modulo.");
define("_AM_RUSUREDEL","Sei sicuro di voler eliminare il blocco <strong>%s</strong>?");
define("_AM_NAME","Descrizione");
define("_AM_USEFULTAGS","Tag utili:");
define("_AM_BLOCKTAG1","%s visualizzer&agrave; %s");
define('_AM_SVISIBLEIN', 'Mostra i blocchi visibili in %s');
define('_AM_TOPPAGE', 'Pagina iniziale');
define('_AM_VISIBLEIN', 'Visibile in');
define('_AM_ALLPAGES', 'Tutte le pagine');
define('_AM_TOPONLY', 'Solo la pagina iniziale');
define('_AM_ADVANCED', 'Impostazioni avanzate');
define('_AM_BCACHETIME', 'Tempo di cache');
define('_AM_BALIAS', 'Pseudonimo');
define('_AM_CLONE', 'Clona');  // Clona un blocco
define('_AM_CLONEBLK', 'Clona'); // Blocco clonato
define('_AM_CLONEBLOCK', 'Crea un clone del blocco');
define('_AM_NOTSELNG', "'%s' non &egrave; selezionato!"); // messaggio di errore
define('_AM_EDITTPL', 'Modifica Template');
define('_AM_MODULE', 'Modulo');
define('_AM_GROUP', 'Gruppo');
define('_AM_UNASSIGNED', 'Non assegnato');

define('_AM_CHANGESTS', 'Cambia la visibilit&agrave; blocco');

######################## Added in 1.2 ###################################
define('_AM_BLOCKS_PERMGROUPS','Gruppi a cui &egrave; permesso di vedere questo blocco');

/**
 * The next Language definitions are included since 2.0 of blockadmin module, because now is based on IPF.
 * TODO: Add the rest of the fields, are added only the ones wich are shown.
 */
// Texts

// Actions
define("_AM_SYSTEM_BLOCKSADMIN_CREATE", "Crea un nuovo blocco");
define("_AM_SYSTEM_BLOCKSADMIN_EDIT", "Modifica un blocco");
define("_AM_SYSTEM_BLOCKSADMIN_MODIFIED", "Blocco modificato con successo!");
define("_AM_SYSTEM_BLOCKSADMIN_CREATED", "Blocco creato con successo!");


// Fields
define("_CO_SYSTEM_BLOCKSADMIN_NAME", "Nome");
define("_CO_SYSTEM_BLOCKSADMIN_NAME_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE", "Titolo");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_MID", "Modulo");
define("_CO_SYSTEM_BLOCKSADMIN_MID_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE", "Visibile");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT", "Contenuto");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE", "Laterale");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT", "Peso");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE", "Tipo blocco");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE", "Tipo contenuto");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS", "Opzioni");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME", "Tempo di cache del blocco");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME_DSC", "");

define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS", "Permessi visualizzazione blocco");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS_DSC", "Seleziona quale gruppo avr&agrave; permessi di visualizzazione per questo blocco. Questo significa che un utente appartenente a uno di questi gruppi potr&agrave; vedere il blocco quando sar&agrave; attivato nel sito.");

define("_AM_SBLEFT_ADMIN","Blocco laterale amm. - Sinistra");
define("_AM_SBRIGHT_ADMIN","Blocco laterale amm. - Destra");
define("_AM_CBLEFT_ADMIN","Blocco centrale amm. - Sinistra");
define("_AM_CBRIGHT_ADMIN","Blocco centrale amm. - Destra");
define("_AM_CBCENTER_ADMIN","Blocco centrale amm. - Centro");
define("_AM_CBBOTTOMLEFT_ADMIN","Blocco centrale amm. - Basso Sinistra");
define("_AM_CBBOTTOMRIGHT_ADMIN","Blocco centrale amm. - Basso Destra");
define("_AM_CBBOTTOM_ADMIN","Blocco centrale amm. - Basso");

