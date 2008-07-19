<?php
/**
* Config file ofthe System module
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by marcan <marcan@impresscms.org>
* @version		$Id: admin.php 1683 2008-04-19 13:50:00Z malanciault $
*/

$modversion['name'] = _MI_SYSTEM_NAME;
$modversion['version'] = 1.03;
$modversion['description'] = _MI_SYSTEM_DESC;
$modversion['author'] = "";
$modversion['credits'] = "The ImpressCMS Project";
$modversion['help'] = "system.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/system_slogo.png";
$modversion['dirname'] = "system";
$modversion['iconsmall'] = "images/icon_small.png";
$modversion['iconbig'] = "images/system_big.png";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin.php";
$modversion['adminmenu'] = "menu.php";

$modversion['onUpdate'] = "include/update.php";

// Templates
$i=0;

$i++;
$modversion['templates'][$i]['file'] = 'system_imagemanager.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_imagemanager2.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_userinfo.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_userform.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_rss.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_redirect.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_comment.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_comments_flat.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_comments_thread.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_comments_nest.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_siteclosed.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_dummy.html';
$modversion['templates'][$i]['description'] = 'Dummy template file for holding non-template contents. This should not be edited.';

$i++;
$modversion['templates'][$i]['file'] = 'system_notification_list.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_notification_select.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'system_block_dummy.html';
$modversion['templates'][$i]['description'] = 'Dummy template for custom blocks or blocks without templates';

$i++;
$modversion['templates'][$i]['file'] = 'system_privpolicy.html';
$modversion['templates'][$i]['description'] = 'Template for displaying site Privacy Policy';

$i++;
$modversion['templates'][$i]['file'] = 'system_error.html';
$modversion['templates'][$i]['description'] = 'Template for handling HTTP errors';

$i++;
$modversion['templates'][$i]['file'] = 'system_content.html';
$modversion['templates'][$i]['description'] = 'Template of content pages';

$i++;
$modversion['templates'][$i]['file'] = 'system_openid.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'blocks/system_block_contentmenu_structure.html';
$modversion['templates'][$i]['description'] = 'Template of content pages';

$i++;
$modversion['templates'][$i]['file'] = 'admin/content/system_adm_contentmanager_index.html';
$modversion['templates'][$i]['description'] = 'Template of the admin of content manager';

$i++;
$modversion['templates'][$i]['file'] = 'admin/blockspadmin/system_adm_blockspadmin.html';
$modversion['templates'][$i]['description'] = 'Template of the admin of block position admin';

$i++;
$modversion['templates'][$i]['file'] = 'admin/pages/system_adm_pagemanager_index.html';
$modversion['templates'][$i]['description'] = 'Template of the admin of pages manager';

$i++;
$modversion['templates'][$i]['file'] = 'admin/blocksadmin/system_adm_blocksadmin.html';
$modversion['templates'][$i]['description'] = 'Template for the blocks admin';

$i++;
$modversion['templates'][$i]['file'] = 'admin/modulesadmin/system_adm_modulesadmin.html';
$modversion['templates'][$i]['description'] = 'Template for the modules admin';

$i++;
$modversion['templates'][$i]['file'] = 'system_common_form.html';
$modversion['templates'][$i]['description'] = 'Common form template';

$i++;
$modversion['templates'][$i]['file'] = 'system_persistabletable_display.html';
$modversion['templates'][$i]['description'] = 'Template of the IcmsPersistableTable list view';

$i++;
$modversion['templates'][$i]['file'] = 'admin/customtag/system_adm_customtag.html';
$modversion['templates'][$i]['description'] = 'Template of the Custom Tag admin';


// Blocks
$i=0;

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME2;
$modversion['blocks'][$i]['description'] = "Shows user block";
$modversion['blocks'][$i]['show_func'] = "b_system_user_show";
$modversion['blocks'][$i]['template'] = 'system_block_user.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME3;
$modversion['blocks'][$i]['description'] = "Shows login form";
$modversion['blocks'][$i]['show_func'] = "b_system_login_show";
$modversion['blocks'][$i]['template'] = 'system_block_login.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME4;
$modversion['blocks'][$i]['description'] = "Shows search form block";
$modversion['blocks'][$i]['show_func'] = "b_system_search_show";
$modversion['blocks'][$i]['template'] = 'system_block_search.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME5;
$modversion['blocks'][$i]['description'] = "Shows contents waiting for approval";
$modversion['blocks'][$i]['show_func'] = "b_system_waiting_show";
$modversion['blocks'][$i]['template'] = 'system_block_waiting.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME6;
$modversion['blocks'][$i]['description'] = "Shows the main navigation menu of the site";
$modversion['blocks'][$i]['show_func'] = "b_system_main_show";
$modversion['blocks'][$i]['template'] = 'system_block_mainmenu.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME7;
$modversion['blocks'][$i]['description'] = "Shows basic info about the site and a link to Recommend Us pop up window";
$modversion['blocks'][$i]['show_func'] = "b_system_info_show";
$modversion['blocks'][$i]['edit_func'] = "b_system_info_edit";
$modversion['blocks'][$i]['options'] = "320|190|s_poweredby.gif|1";
$modversion['blocks'][$i]['template'] = 'system_block_siteinfo.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME8;
$modversion['blocks'][$i]['description'] = "Displays users/guests currently online";
$modversion['blocks'][$i]['show_func'] = "b_system_online_show";
$modversion['blocks'][$i]['template'] = 'system_block_online.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME9;
$modversion['blocks'][$i]['description'] = "Top posters";
$modversion['blocks'][$i]['show_func'] = "b_system_topposters_show";
$modversion['blocks'][$i]['options'] = "10|1";
$modversion['blocks'][$i]['edit_func'] = "b_system_topposters_edit";
$modversion['blocks'][$i]['template'] = 'system_block_topusers.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME10;
$modversion['blocks'][$i]['description'] = "Shows most recent users";
$modversion['blocks'][$i]['show_func'] = "b_system_newmembers_show";
$modversion['blocks'][$i]['options'] = "10|1";
$modversion['blocks'][$i]['edit_func'] = "b_system_newmembers_edit";
$modversion['blocks'][$i]['template'] = 'system_block_newusers.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME11;
$modversion['blocks'][$i]['description'] = "Shows most recent comments";
$modversion['blocks'][$i]['show_func'] = "b_system_comments_show";
$modversion['blocks'][$i]['options'] = "10";
$modversion['blocks'][$i]['edit_func'] = "b_system_comments_edit";
$modversion['blocks'][$i]['template'] = 'system_block_comments.html';

$i++;
// RMV-NOTIFY:
// Adding a block...
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME12;
$modversion['blocks'][$i]['description'] = "Shows notification options";
$modversion['blocks'][$i]['show_func'] = "b_system_notification_show";
$modversion['blocks'][$i]['template'] = 'system_block_notification.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME13;
$modversion['blocks'][$i]['description'] = "Shows theme selection box";
$modversion['blocks'][$i]['show_func'] = "b_system_themes_show";
$modversion['blocks'][$i]['options'] = "0|80";
$modversion['blocks'][$i]['edit_func'] = "b_system_themes_edit";
$modversion['blocks'][$i]['template'] = 'system_block_themes.html';

$i++;
$modversion['blocks'][$i]['file'] = "system_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME14;
$modversion['blocks'][$i]['description'] = "Displays image links to change the site language";
$modversion['blocks'][$i]['show_func'] = "b_system_multilanguage_show";
$modversion['blocks'][$i]['template'] = 'system_block_multilanguage.html';

$i++;
$modversion['blocks'][$i]['file'] = "social_bookmarks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME18;
$modversion['blocks'][$i]['description'] = "Displays image links to bookmark pages in sharing websites";
$modversion['blocks'][$i]['show_func'] = "b_social_bookmarks";
$modversion['blocks'][$i]['template'] = 'system_block_socialbookmark.html';
//Content Manager

$i++;
$modversion['blocks'][$i]['file'] = "content_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME15;
$modversion['blocks'][$i]['description'] = "Show content page";
$modversion['blocks'][$i]['show_func'] = "b_content_show";
$modversion['blocks'][$i]['options'] = "1|1|1|1";
$modversion['blocks'][$i]['edit_func'] = "b_content_edit";
$modversion['blocks'][$i]['template'] = 'system_block_content.html';

$i++;
$modversion['blocks'][$i]['file'] = "content_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME16;
$modversion['blocks'][$i]['description'] = "Menu of content pages and categories";
$modversion['blocks'][$i]['show_func'] = "b_content_menu_show";
$modversion['blocks'][$i]['options'] = "content_weight|ASC|1|#F2E2A0";
$modversion['blocks'][$i]['edit_func'] = "b_content_menu_edit";
$modversion['blocks'][$i]['template'] = 'system_block_contentmenu.html';

$i++;
$modversion['blocks'][$i]['file'] = "content_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_SYSTEM_BNAME17;
$modversion['blocks'][$i]['description'] = "Menu of content pages and categories";
$modversion['blocks'][$i]['show_func'] = "b_content_relmenu_show";
$modversion['blocks'][$i]['options'] = "content_weight|ASC|1";
$modversion['blocks'][$i]['edit_func'] = "b_content_relmenu_edit";
$modversion['blocks'][$i]['template'] = 'system_block_contentmenu.html';

// Menu
$modversion['hasMain'] = 0;
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "search_content";
?>
