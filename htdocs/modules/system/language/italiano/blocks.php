<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// $Id: blocks.php 2 2005-11-02 18:23:29Z skalpa $
// Blocks
define("_MB_SYSTEM_ADMENU","Amministrazione");
define("_MB_SYSTEM_RNOW","Registrati ora!");
define("_MB_SYSTEM_LPASS","Hai perso la password?");
define("_MB_SYSTEM_SEARCH","Cerca");
define("_MB_SYSTEM_ADVS","Ricerca avanzata");
define("_MB_SYSTEM_VACNT","Mostra profilo");
define("_MB_SYSTEM_EACNT","Modifica profilo");
// RMV-NOTIFY
define("_MB_SYSTEM_NOTIF", "Notifiche");
define("_MB_SYSTEM_LOUT","Disconnetti");
define("_MB_SYSTEM_INBOX","Posta in arrivo");
define("_MB_SYSTEM_SUBMS","News inviate");
define("_MB_SYSTEM_WLNKS","Link in attesa");
define("_MB_SYSTEM_BLNK","Link interrotti");
define("_MB_SYSTEM_MLNKS","Link modificati");
define("_MB_SYSTEM_WDLS","Download in attesa");
define("_MB_SYSTEM_BFLS","Download corrotti");
define("_MB_SYSTEM_MFLS","Download modificati");
define("_MB_SYSTEM_HOME","Home"); // link alla homepage nel blocco 'menu principale'
define("_MB_SYSTEM_RECO","Raccomanda");
define("_MB_SYSTEM_PWWIDTH","Larghezza della finestra di popup");
define("_MB_SYSTEM_PWHEIGHT","Altezza della finestra di popup");
define("_MB_SYSTEM_LOGO","Logo nella cartella %s");  // %s nome della directory del logo
define("_MB_SYSTEM_COMPEND", "Commenti");

define("_MB_SYSTEM_SADMIN","Mostra il gruppo degli amministratori");
define("_MB_SYSTEM_SPMTO","Invia un messaggio privato a %s");
define("_MB_SYSTEM_SEMTO","Invia un email a %s");

define("_MB_SYSTEM_DISPLAY","Mostra %s utenti registrati");
define("_MB_SYSTEM_DISPLAYA","Mostra l'avatar degli utenti");
define("_MB_SYSTEM_NODISPGR","Non mostrare gli utenti che hanno il livello:");

define("_MB_SYSTEM_DISPLAYC","Mostra %s commenti");
define("_MB_SYSTEM_SECURE", "Login sicuro");

define("_MB_SYSTEM_NUMTHEME", "%s temi");
define("_MB_SYSTEM_THSHOW", "Mostra screenshot");
define("_MB_SYSTEM_THWIDTH", "Larghezza screenshot");
define('_MB_SYSTEM_REMEMBERME', 'Ricordami');
define("_MB_SYSTEM_PRIVPOLICY", "Informativa Privacy");

//Content Manager
define("_MB_SYSTEM_SHOWSUBS", "Mostra sotto-pagine?");
define("_MB_SYSTEM_SHOWNAV", "Mostra il menu di navigazione?");
define("_MB_SYSTEM_SHOWPINFO", "Mostra informazioni sull'autore e la pagina pubblicata?");
define("_MB_SYSTEM_SORT", "Ordina");
define("_MB_SYSTEM_ORDER", "Ordine");
define("_MB_SYSTEM_SELCOLOR", "Colore di sfondo dell'oggetto selezionato: ");
define("_MB_SYSTEM_PAGE", "Pagina da mostrare");
if (!defined('_CT_EDIT_CONTENT')){define('_CT_EDIT_CONTENT','Modifica contenuto');}
if (!defined('_CT_DELETE_CONTENT')){define('_CT_DELETE_CONTENT','Elimina contenuto');}
if (!defined('_CT_PUBLISHEDBY')){define('_CT_PUBLISHEDBY','Inviato da');}
if (!defined('_CT_READS')){define('_CT_READS','letture');}
if (!defined('_CT_ON')){define('_CT_ON','il');}

/*
 * Added in 1.2
 */

// openid
define("_MB_SYSTEM_OPENID_LOGIN", "Login con il tuo OpenID");
define("_MB_SYSTEM_OPENID_URL", "Il tuo OpenID URL:");
define("_MB_SYSTEM_OPENID_NORMAL_LOGIN", "Torna al login normale");
define("_MB_SYSTEM_TOTAL_USERS", "Totale Utenti");
define("_MB_SYSTEM_ACT_USERS", "Utenti attivi");
define("_MB_SYSTEM_INACT_USERS", "Utenti non attivi");
define("_MB_SYSTEM_DISPLAYTOT","Mostrare informazioni su tutti i membri?");
// waiting content
define("_MB_SYSTEM_NOWAITING_DISPLAY","Sempre mostrato");
define("_MB_SYSTEM_SQL_CACHE","SQL cache");
// Social bookmarking
define("_MB_SYSTEM_SOCIAL_PROVIDER_SELECT", "Seleziona il provider di social network da mostrare");
define("_MB_SYSTEM_SOCIAL_PROVIDER_BOOKMARK", "preferiti su: ");
define("_MB_SYSTEM_SOCIAL_PROVIDER_0", "Twitter");
define("_MB_SYSTEM_SOCIAL_PROVIDER_1", "Facebook");
define("_MB_SYSTEM_SOCIAL_PROVIDER_2", "MySpace");
define("_MB_SYSTEM_SOCIAL_PROVIDER_3", "Del.icio.us");
define("_MB_SYSTEM_SOCIAL_PROVIDER_4", "Ask");
define("_MB_SYSTEM_SOCIAL_PROVIDER_5", "Mr. Wong");
define("_MB_SYSTEM_SOCIAL_PROVIDER_6", "Webnews");
define("_MB_SYSTEM_SOCIAL_PROVIDER_7", "Icio");
define("_MB_SYSTEM_SOCIAL_PROVIDER_8", "Oneview");
define("_MB_SYSTEM_SOCIAL_PROVIDER_9", "newsider");
define("_MB_SYSTEM_SOCIAL_PROVIDER_10", "Folkd");
define("_MB_SYSTEM_SOCIAL_PROVIDER_11", "Yigg");
define("_MB_SYSTEM_SOCIAL_PROVIDER_12", "Linkarena");
define("_MB_SYSTEM_SOCIAL_PROVIDER_13", "Digg");
define("_MB_SYSTEM_SOCIAL_PROVIDER_14", "Reddit");
define("_MB_SYSTEM_SOCIAL_PROVIDER_15", "Simpy");
define("_MB_SYSTEM_SOCIAL_PROVIDER_16", "StumbleUpon");
define("_MB_SYSTEM_SOCIAL_PROVIDER_17", "Slashdot");
define("_MB_SYSTEM_SOCIAL_PROVIDER_18", "Yahoo");
define("_MB_SYSTEM_SOCIAL_PROVIDER_19", "Spurl");
define("_MB_SYSTEM_SOCIAL_PROVIDER_20", "Google");
define("_MB_SYSTEM_SOCIAL_PROVIDER_21", "Blinklist");
define("_MB_SYSTEM_SOCIAL_PROVIDER_22", "Blogmarks");
define("_MB_SYSTEM_SOCIAL_PROVIDER_23", "Diigo");
define("_MB_SYSTEM_SOCIAL_PROVIDER_24", "Technorati");
define("_MB_SYSTEM_SOCIAL_PROVIDER_25", "Newsvine");
define("_MB_SYSTEM_SOCIAL_PROVIDER_26", "Blinkbits");
define("_MB_SYSTEM_SOCIAL_PROVIDER_27", "Netvouz");
define("_MB_SYSTEM_SOCIAL_PROVIDER_28", "Propeller");
define("_MB_SYSTEM_SOCIAL_PROVIDER_29", "Buzz");
define("_MB_SYSTEM_SOCIAL_PROVIDER_30", "Sphinn");
define("_MB_SYSTEM_SOCIAL_PROVIDER_31", "Jumptags");
