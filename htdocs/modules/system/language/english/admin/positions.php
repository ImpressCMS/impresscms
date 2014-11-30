<?php
define('_MD_AM_BKPOSAD','Block Positions');
define('_MD_AM_BKPOSAD_DSC', 'Manage and create blocks positions that are used within the themes on your website.');

define("_AM_SBLEFT","Side Block - Left");
define("_AM_SBRIGHT","Side Block - Right");
define("_AM_CBLEFT","Center Block - Left");
define("_AM_CBRIGHT","Center Block - Right");
define("_AM_CBCENTER","Center Block - Center");
define("_AM_CBBOTTOMLEFT","Center Block - Bottom left");
define("_AM_CBBOTTOMRIGHT","Center Block - Bottom right");
define("_AM_CBBOTTOM","Center Block - Bottom");

######################## Added in 1.2 ###################################
define('_AM_SYSTEM_POSITIONS_TITLE',"Block Positions Administration");
define('_AM_SYSTEM_POSITIONS_CREATED', "New Block Position Created");
define('_AM_SYSTEM_POSITIONS_MODIFIED', "Block Position Modified");
define('_AM_SYSTEM_POSITIONS_CREATE',"Add New Block Position");
define('_AM_SYSTEM_POSITIONS_EDIT',"Edit Block Position");
define('_AM_SYSTEM_POSITIONS_INFO','To include the new block positions on the theme, put the code bellow in the place where it desires that the blocks appear.
<div style="border: 1px dashed #AABBCC; padding:10px; width:86%;">
<{foreach from=$xoBlocks.<b>name_of_position</b> item=block}><br /><{include file="<b>path_to_theme_folder/file_to_show_blocks.html</b>"}><br /><{/foreach}>
</div>
');

define("_CO_SYSTEM_POSITIONS_ID", "Id");
define("_CO_SYSTEM_POSITIONS_TITLE", "Title");
define("_CO_SYSTEM_POSITIONS_PNAME", "Position Name");
define('_CO_SYSTEM_POSITIONS_PNAME_DSC',"Name of Block Position, it is with this name that will have to be created the Loop in the theme for the exhibition of blocks.<br/>Use a name with small_caption letters, without spaces and special characters.");
define("_CO_SYSTEM_POSITIONS_DESCRIPTION", "Description");

define("_AM_SBLEFT_ADMIN","Admin Side Block - Left");
define("_AM_SBRIGHT_ADMIN","Admin Side Block - Right");
define("_AM_CBLEFT_ADMIN","Admin Center Block - Left");
define("_AM_CBRIGHT_ADMIN","Admin Center Block - Right");
define("_AM_CBCENTER_ADMIN","Admin Center Block - Center");
define("_AM_CBBOTTOMLEFT_ADMIN","Admin Center Block - Bottom left");
define("_AM_CBBOTTOMRIGHT_ADMIN","Admin Center Block - Bottom right");
define("_AM_CBBOTTOM_ADMIN","Admin Center Block - Bottom");
