<?php
define('_AM_SBLEFT', 'Blocco laterale - Sinistra');
define('_AM_SBRIGHT', 'Blocco laterale - Destra');
define('_AM_CBLEFT', 'Blocco centrale - Sinistra');
define('_AM_CBRIGHT', 'Blocco centrale - Destra');
define('_AM_CBCENTER', 'Blocco centrale - Centro');
define('_AM_CBBOTTOMLEFT', 'Blocco centrale - Basso sinistra');
define('_AM_CBBOTTOMRIGHT', 'Blocco centrale - Basso destra');
define('_AM_CBBOTTOM', 'Blocco centrale - Basso');

######################## Added in 1.2 ###################################
define('_AM_SYSTEM_BLOCKSPADMIN_TITLE', 'Amministrazione posizioni blocco');
define('_AM_SYSTEM_BLOCKSPADMIN_CREATED', 'Creata nuova posizione di blocco');
define('_AM_SYSTEM_BLOCKSPADMIN_MODIFIED', 'Modificata posizione di blocco');
define('_AM_SYSTEM_BLOCKSPADMIN_CREATE', 'Aggiungi nuova posizione di blocco');
define('_AM_SYSTEM_BLOCKSPADMIN_EDIT', 'Modifica posizione di blocco');
define('_AM_SYSTEM_BLOCKSPADMIN_INFO', 'Per includere nuove posizioni di blocco nel tema, inserire il codice sottostante nella posizione desiderata sul tema, dove desideri che il blocco appaia.
<div style="border: 1px dashed #AABBCC; padding:10px; width:86%;">
<{foreach from=$xoBlocks.<b>name_of_position</b> item=block}><br /><{include file="<b>path_to_theme_folder/file_to_show_blocks.html</b>"}><br /><{/foreach}>
</div>
');

define('_CO_SYSTEM_BLOCKSPADMIN_ID', 'Id');
define('_CO_SYSTEM_BLOCKSPADMIN_TITLE', 'Titolo');
define('_CO_SYSTEM_BLOCKSPADMIN_PNAME', 'Nome posizione');
define('_CO_SYSTEM_BLOCKSPADMIN_PNAME_DSC', 'Nome della posizione del blocco, sar&agrave; con questo nome che sar&agrave; creato il Loop nel tema per mostrare il blocco.<br/>Usa un nome con lettere minuscole, senza spazi o caratteri speciali.');
define('_CO_SYSTEM_BLOCKSPADMIN_DESCRIPTION', 'Descrizioni');

define('_AM_SBLEFT_ADMIN', 'Blocco laterale amm. - Sinistra');
define('_AM_SBRIGHT_ADMIN', 'Blocco laterale amm. - Destra');
define('_AM_CBLEFT_ADMIN', 'Blocco centrale amm. - Sinistra');
define('_AM_CBRIGHT_ADMIN', 'Blocco centrale amm. - Destra');
define('_AM_CBCENTER_ADMIN', 'Blocco centrale amm. - Centro');
define('_AM_CBBOTTOMLEFT_ADMIN', 'Blocco centrale amm. - Basso Sinistra');
define('_AM_CBBOTTOMRIGHT_ADMIN', 'Blocco centrale amm. - Basso Destra');
define('_AM_CBBOTTOM_ADMIN', 'Blocco centrale amm. - Basso');
