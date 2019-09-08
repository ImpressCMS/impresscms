<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Italian translation by Marco Ragogna alias blueangel (m.ragogna@xoopsit.net)     //
// webmaster of XOOPS Official Italian Support Site (http://www.xoopsit.net)
// $Id: admin.php,v 1.8 2003/09/18 00:09:51 okazu Exp $
//%%%%%%	File Name  admin.php 	%%%%%
//define('_MD_AM_DBUPDATED','Database aggiornato con successo!');
define('_INVALID_ADMIN_FUNCTION', 'Funzione amministrativa non valida');

define('_MD_AM_CONFIG', 'Configurazione del sistema');

// Admin Module Names
define('_MD_AM_ADGS', 'Gruppi');
define('_MD_AM_BANS', 'Banners');
define('_MD_AM_BKAD', 'Blocchi');
define('_MD_AM_MDAD', 'Moduli');
define('_MD_AM_SMLS', 'Faccine');
define('_MD_AM_RANK', 'Livelli utenti');
define('_MD_AM_USER', 'Modifica utenti');
define('_MD_AM_FINDUSER', 'Trova utenti');
define('_MD_AM_PREF', 'Preferenze');
define('_MD_AM_VRSN', 'Versione');
define('_MD_AM_MLUS', 'Mail agli utenti');
define('_MD_AM_IMAGES', 'Immagini');
define('_MD_AM_AVATARS', 'Avatars');
define('_MD_AM_TPLSETS', 'Templates');
define('_MD_AM_COMMENTS', 'Commenti');
define('_MD_AM_CONTENT', 'Gestione contenuti');
define('_MD_AM_BKPOSAD', 'Posizione blocchi');
define('_MD_AM_PAGES', 'Symlink Manager');
define('_MD_AM_CUSTOMTAGS', 'Tags personalizzati');

 // Group permission phrases
define('_MD_AM_PERMADDNG', 'Impossibile aggiungere il permesso a un gruppo (Permesso: %s Gruppo: %s)!');
define('_MD_AM_PERMADDOK', 'Permesso al gruppo aggiunto con successo (Permesso: %s Gruppo: %s)!');
define('_MD_AM_PERMRESETNG', 'Impossibile azzerare i permessi del gruppo per il modulo %s!');
define('_MD_AM_PERMADDNGP', 'Tutti gli elementi genitore devono essere selezionati!');
######################## Added in 1.2 ###################################
if (!defined('_MD_AM_AUTOTASKS')) {
    define('_MD_AM_AUTOTASKS', 'Funzioni automatiche');
}
define('_MD_AM_ADSENSES', 'Adsenses');
define('_MD_AM_RATINGS', 'Votazioni');
define('_MD_AM_MIMETYPES', 'Mime Types');
// added in 1.3
define('_MD_AM_GROUPS_ADVERTISING', 'Pubblicità');
define('_MD_AM_GROUPS_CONTENT', 'Contenuti');
define('_MD_AM_GROUPS_LAYOUT', 'Layout');
define('_MD_AM_GROUPS_MEDIA', 'Media');
define('_MD_AM_GROUPS_SITECONFIGURATION', 'Configurazione sito');
define('_MD_AM_GROUPS_SYSTEMTOOLS', 'Strumenti di sistema');
define('_MD_AM_GROUPS_USERSANDGROUPS', 'Utenti e gruppi');
define('_MD_AM_ADSENSES_DSC', 'Adsenses sono tags che puoi definire e usare ovunque nel sito.');
define('_MD_AM_AUTOTASKS_DSC', 'Le funzioni automatiche permettono di creare una scaletta di azioni che il sistema eseguirà automaticamente.');
define('_MD_AM_AVATARS_DSC', 'Amministra gli avatars disponibili per gli utenti del tuo sito.');
define('_MD_AM_BANS_DSC', 'Amministra le campagne pubblicitarie e le utenze degli inserzionisti.');
define('_MD_AM_BKPOSAD_DSC', 'Amministra e crea posizioni per i blocchi che sono utilizzate nel tuo sito web.');
define('_MD_AM_BKAD_DSC', 'Amministra e crea blocchi che sono utlizzati nel tuo sito web.');
define('_MD_AM_COMMENTS_DSC', 'Amministra i commenti degli utenti sul tuo sito web.');
define('_MD_AM_CUSTOMTAGS_DSC', 'I Tags personalizzati sono tags che puoi definire e utilizzare ovunque nel tuo sito web.');
define('_MD_AM_USER_DSC', 'Crea, modifica o cancella utenti registrati.');
define('_MD_AM_FINDUSER_DSC', 'Cerca utenti registrati con l\'aiuto di filtri.');
define('_MD_AM_ADGS_DSC', 'Amministra permessi, membri, visibilità e diritti di accesso di gruppi di utenti.');
define('_MD_AM_IMAGES_DSC', 'Crea gruppi di immagini e amministra i permessi di ogni gruppo. Ritaglia e ridimensiona le foto caricate.');
define('_MD_AM_MLUS_DSC', 'Invia mail agli utenti di un intero gruppo - o filtra i destinatari in base ai criteri selezionati.');
define('_MD_AM_MIMETYPES_DSC', 'Amministra le estensioni permesse per i files caricati sul tuo sito web.');
define('_MD_AM_MDAD_DSC', 'Amministra il peso nel menu moduli, lo status, il nome o aggiorna i moduli se necessario.');
define('_MD_AM_RATINGS_DSC', 'Usando questo strumento, puoi aggiungere un nuovo metodo di classificazione ai tuoi moduli e controllare il risultato attraverso questa sezione');
define('_MD_AM_SMLS_DSC', 'Amministra le faccine disponibili e definisci il codice associato con ciascuno.');
define('_MD_AM_PAGES_DSC', 'Symlink ti permette di creare un link univoco basato su una pagina del tuo sito web, che potrebbe essere utilizzato per blocchi specifici a un url di pagina o per linkare direttamente nel contenuto di un modulo.');
define('_MD_AM_TPLSETS_DSC', 'I templates sono insiemi di files html/css che interpretano la impaginazione dei moduli.');
define('_MD_AM_RANK_DSC', 'I livelli utente sono immagini usate per rendere differenti i livelli fra gli utenti del vostro sito!');
define('_MD_AM_VRSN_DSC', 'Usa questo strumento per controllare gli aggiornamenti del tuo sistema.');
define('_MD_AM_PREF_DSC', 'Preferenze sito ImpressCMS');
