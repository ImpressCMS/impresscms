<?php
// $Id: blocksadmin.php 506 2006-05-26 23:10:37Z skalpa $
//%%%%%%	Admin Module Name  Blocks 	%%%%%
define("_AM_DBUPDATED",_MD_AM_DBUPDATED);

//%%%%%%	blocks.php 	%%%%%
define("_AM_BADMIN","Blocks Administration");

# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_BPADMIN',"Blocks Positions Administration");
define('_AM_BPCOD',"ID");
define('_AM_BPNAME',"Name of Position");
define('_AM_BPDESC',"Description of Position");
define('_AM_ADDPBLOCK',"Add new Block Position");
define('_AM_EDITPBLOCK',"Edit Block Position");
define('_AM_PBNAME_DESC',"Name of Block Position, it is with this name that will have to be created the Loop in the theme for the exhibition of blocks.<br/>Use a name with small_caption letters, without spaces and special characters.");
define('_AM_BPMSG1',"Successfully carried through operation!");
define('_AM_BPMSG2',"Problems had occurred to carry through them the operations.");
define('_AM_BPMSG3',"It is certain that it desires to exclude this block position?<br /><b>Warning:</b> This operation cannot be insult!");
define('_AM_BPHELP','To include the new block positions on the theme, put the code bellow in the place where it desires that the blocks appear.
<div style="border: 1px dashed #AABBCC; padding:10px; width:86%;">
<{foreach from=$xoBlocks.<b>name_of_position</b> item=block}><br /><{include file="<b>path_to_theme_folder/file_to_show_blocks</b>"}><br /><{/foreach}>
</div>
');
#

define("_AM_ADDBLOCK","Add a new block");
define("_AM_LISTBLOCK","List all blocks");
define("_AM_SIDE","Side");
define("_AM_BLKDESC","Block Description");
define("_AM_TITLE","Title");
define("_AM_WEIGHT","Weight");
define("_AM_ACTION","Action");
define("_AM_BLKTYPE","Block Type");
define("_AM_LEFT","Left");
define("_AM_RIGHT","Right");
define("_AM_CENTER","Center");
define("_AM_VISIBLE","Visible");
define("_AM_POSCONTT","Position of the additional content");
define("_AM_ABOVEORG","Above the original content");
define("_AM_AFTERORG","After the original content");
define("_AM_EDIT","Edit");
define("_AM_DELETE","Delete");
define("_AM_SBLEFT","Side Block - Left");
define("_AM_SBRIGHT","Side Block - Right");
define("_AM_CBLEFT","Center Block - Left");
define("_AM_CBRIGHT","Center Block - Right");
define("_AM_CBCENTER","Center Block - Center");
define("_AM_CBBOTTOMLEFT","Center Block - Bottom left");
define("_AM_CBBOTTOMRIGHT","Center Block - Bottom right");
define("_AM_CBBOTTOM","Center Block - Bottom");
define("_AM_CONTENT","Content");
define("_AM_OPTIONS","Options");
define("_AM_CTYPE","Content Type");
define("_AM_HTML","HTML");
define("_AM_PHP","PHP Script");
define("_AM_AFWSMILE","Auto Format (smilies enabled)");
define("_AM_AFNOSMILE","Auto Format (smilies disabled)");
define("_AM_SUBMIT","Submit");
define("_AM_CUSTOMHTML","Custom Block (HTML)");
define("_AM_CUSTOMPHP","Custom Block (PHP)");
define("_AM_CUSTOMSMILE","Custom Block (Auto Format + smilies)");
define("_AM_CUSTOMNOSMILE","Custom Block (Auto Format)");
define("_AM_DISPRIGHT","Display only rightblocks");
define("_AM_SAVECHANGES","Save Changes");
define("_AM_EDITBLOCK","Edit a block");
define("_AM_SYSTEMCANT","System blocks cannot be deleted!");
define("_AM_MODULECANT","This block cannot be deleted directly! If you wish to disable this block, deactivate the module.");
define("_AM_RUSUREDEL","Are you sure you want to delete block <b>%s</b>?");
define("_AM_NAME","Name");
define("_AM_USEFULTAGS","Useful Tags:");
define("_AM_BLOCKTAG1","%s will print %s");
define('_AM_SVISIBLEIN', 'Show blocks visible in %s');
define('_AM_TOPPAGE', 'Top Page');
define('_AM_VISIBLEIN', 'Visible in');
define('_AM_ALLPAGES', 'All Pages');
define('_AM_TOPONLY', 'Top Page Only');
define('_AM_ADVANCED', 'Advanced Settings');
define('_AM_BCACHETIME', 'Cache lifetime');
define('_AM_BALIAS', 'Alias name');
define('_AM_CLONE', 'Clone');  // clone a block
define('_AM_CLONEBLK', 'Clone'); // cloned block
define('_AM_CLONEBLOCK', 'Create a clone block');
define('_AM_NOTSELNG', "'%s' is not selected!"); // error message
define('_AM_EDITTPL', 'Edit Template');
define('_AM_MODULE', 'Module');
define('_AM_GROUP', 'Group');
define('_AM_UNASSIGNED', 'Unassigned');
?>
