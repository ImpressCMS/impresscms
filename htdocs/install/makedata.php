<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Inserts configuration data's
 *
 * This file is responsible for configuration data's while installing
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package        installer
 * @since       2.3.0
 * @author        Haruki Setoyama  <haruki@planewave.org>
 * @author        Kazumi Ono <webmaster@myweb.ne.jp>
 * @author        Skalpa Keo <skalpa@xoops.org>
 * @author        Taiwen Jiang <phppp@users.sourceforge.net>
 */

include_once './class/dbmanager.php';

// RMV
// TODO: Shouldn't we insert specific field names??  That way we can use
// the defaults specified in the database...!!!! (and don't have problem
// of missing fields in install file, when add new fields to database)

function make_groups(&$dbm)
{
	$gruops['XOOPS_GROUP_ADMIN'] = $dbm->insert('groups', [
		[
			'name' => _INSTALL_WEBMASTER,
			'description' => _INSTALL_WEBMASTERD,
			'group_type' => 'Admin'
		]
	]);
	$gruops['XOOPS_GROUP_USERS'] = $dbm->insert('groups', [
		[
			'name' => _INSTALL_REGUSERS,
			'description' => _INSTALL_REGUSERSD,
			'group_type' => 'User'
		]
	]);
	$gruops['XOOPS_GROUP_ANONYMOUS'] = $dbm->insert('groups', [
		[
			'name' => _INSTALL_ANONUSERS,
			'description' => _INSTALL_ANONUSERSD,
			'group_type' => 'Anonymous'
		]
	]);

	if (!$gruops['XOOPS_GROUP_ADMIN'] || !$gruops['XOOPS_GROUP_USERS'] || !$gruops['XOOPS_GROUP_ANONYMOUS']) {
		return false;
	}

	return $gruops;
}

function make_data(&$dbm, &$cm, $adminname, $adminlogin_name, $adminpass, $adminmail, $language, $gruops)
{

	if (file_exists($lang_data_file = __DIR__ . '/language/' . $language . '/makedata.php')) {
		include $lang_data_file;
	}

	// data for table 'groups_users_link'
	$dbm->insert('groups_users_link', [
		[
			'groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'uid' => 1
		],
		[
			'groupid' => $gruops['XOOPS_GROUP_USERS'],
			'uid' => 1
		]
	]);

	// data for table 'group_permission'
	$dbm->insert("group_permission", [
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'module_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'module_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_USERS'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'module_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ANONYMOUS'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'module_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 2,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 3,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 4,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 5,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 6,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 7,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 8,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 9,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 10,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 11,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 12,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 13,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 14,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 15,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 16,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 17,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 18,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 19,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 20,
			'gperm_modid' => 1,
			'gperm_name' => 'system_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'group_manager'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 2,
			'gperm_modid' => 1,
			'gperm_name' => 'group_manager'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 3,
			'gperm_modid' => 1,
			'gperm_name' => 'group_manager'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'content_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_USERS'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'content_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ANONYMOUS'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'content_read'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'content_admin'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'use_wysiwygeditor'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'imgcat_write'
		],
		[
			'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
			'gperm_itemid' => 1,
			'gperm_modid' => 1,
			'gperm_name' => 'imgcat_read'
		],
	]);

	//Image Category to admin Logos
	$dbm->insert("imagecategory", [
		[
			'imgcat_id' => 1,
			'imgcat_pid' => 0,
			'imgcat_name' => 'Logos',
			'imgcat_maxsize' => 358400,
			'imgcat_maxwidth' => 350,
			'imgcat_maxheight' => 80,
			'imgcat_display' => 1,
			'imgcat_weight' => 0,
			'imgcat_type' => 'C',
			'imgcat_storetype' => 'file',
			'imgcat_foldername' => 'logos'
		]
	]);

	//Default logo used in the admin
	$dbm->insert("image", [
		[
			'image_id' => 1,
			'image_name' => 'img482278e29e81c.png',
			'image_nicename' => 'ImpressCMS',
			'image_mimetype' => 'image/png',
			'image_created' => time(),
			'image_display' => 1,
			'image_weight' => 0,
			'imgcat_id' => 1
		]
	]);

	$dbm->insert('tplset', [
		[
			'tplset_id' => 1,
			'tplset_name' => 'default',
			'tplset_desc' => 'ImpressCMS Default Template Set',
			'tplset_credits' => '',
			'tplset_created' => time()
		]
	]);

	// system modules
	if (file_exists(ICMS_ROOT_PATH . '/modules/system/language/' . $language . '/modinfo.php')) {
		include ICMS_ROOT_PATH . '/modules/system/language/' . $language . '/modinfo.php';
	} else {
		include ICMS_ROOT_PATH . '/modules/system/language/english/modinfo.php';
		$language = 'english';
	}

	$modversion = array();
	include_once ICMS_ROOT_PATH . '/modules/system/icms_version.php';

	// RMV-NOTIFY (updated for extra column in table)
	/* do not alter the value for dbversion (the 3rd to last field) - all updates for
	 * this will be handled by the module update process
	 *
	 * moved this ahead of the inclusion of system/icms_version.php for a reason - not completely without consequence
	 * $modversion is not yet defined ~skenow
	 */
	$dbm->insert("modules", [
		[
			'mid' => 1,
			'name' => _MI_SYSTEM_NAME,
			'version' => $modversion['version'] * 100,
			'last_update' => time(),
			'weight' => 0,
			'isactive' => 1,
			'dirname' => 'system',
			'hasmain' => 0,
			'hasadmin' => 1,
			'hassearch' => 0,
			'hasconfig' => 0,
			'hascomments' => 0,
			'hasnotification' => 0,
			'dbversion' => 44,
			'modname' => 'system',
			'ipf' => 1
		]
	]);

	foreach ($modversion['templates'] as $tplfile) {
		if (file_exists(ICMS_ROOT_PATH . '/modules/system/templates/' . $tplfile['file'])) {
			$newtplid = $dbm->insert('tplfile', [
				[
					'tpl_refid' => 1,
					'tpl_module' => 'system',
					'tpl_tplset' => 'default',
					'tpl_file' => $tplfile['file'],
					'tpl_desc' => $tplfile['description'],
					'tpl_lastmodified' => time(),
					'tpl_lastimported' => time(),
					'tpl_type' => 'module'
				]
			]);
			$dbm->insert('tplsource', [
				[
					'tpl_id' => $newtplid,
					'tpl_source' => file_get_contents(ICMS_ROOT_PATH . '/modules/system/templates/' . $tplfile['file'])
				]
			]);
		}
	}

	foreach ($modversion['blocks'] as $func_num => $newblock) {
		if (file_exists(ICMS_ROOT_PATH . '/modules/system/templates/blocks/' . $newblock['template'])) {
			if (in_array($newblock['template'], array('system_block_user.html', 'system_block_login.html', 'system_block_mainmenu.html', 'system_block_socialbookmark.html', 'system_block_themes.html', 'system_block_search.html', 'system_admin_block_warnings.html', 'system_admin_block_cp.html', 'system_admin_block_modules.html', 'system_block_newusers.html', 'system_block_online.html', 'system_block_waiting.html', 'system_block_topusers.html'))) {
				$visible = 1;
			} else {
				$visible = 0;
			}
			if (in_array($newblock['template'], array('system_block_search.html'))) {
				$canvaspos = 2;
			} elseif (in_array($newblock['template'], array('system_block_socialbookmark.html'))) {
				$canvaspos = 7;
			} elseif (in_array($newblock['template'], array('system_admin_block_warnings.html'))) {
				$canvaspos = 12;
			} elseif (in_array($newblock['template'], array('system_admin_block_cp.html'))) {
				$canvaspos = 11;
			} elseif (in_array($newblock['template'], array('system_admin_block_modules.html'))) {
				$canvaspos = 13;
			} elseif (in_array($newblock['template'], array('system_block_online.html', 'system_block_waiting.html'))) {
				$canvaspos = 9;
			} elseif (in_array($newblock['template'], array('system_block_newusers.html', 'system_block_topusers.html'))) {
				$canvaspos = 10;
			} else {
				$canvaspos = 1;
			}
			$options = !isset($newblock['options']) ? '' : trim($newblock['options']);
			$edit_func = !isset($newblock['edit_func']) ? '' : trim($newblock['edit_func']);

			# Adding dynamic block area/position system - TheRpLima - 2007-10-21
			$newbid = $dbm->insert('newblocks', [
				[
					'mid' => 1,
					'func_num' => $func_num,
					'options' => $options,
					'name' => $newblock['name'],
					'title' => $newblock['name'],
					'content' => '',
					'side' => $canvaspos,
					'weight' => 0,
					'visible' => $visible,
					'block_type' => 'S',
					'c_type' => 'H',
					'isactive' => 1,
					'dirname' => 'system',
					'func_file' => $newblock['file'],
					'show_func' => $newblock['show_func'],
					'edit_func' => $edit_func,
					'template' => $newblock['template'],
					'bcachetime' => 0,
					'last_modified' => time()
				]
			]);

			$newtplid = $dbm->insert('tplfile', [
				[
					'tpl_refid' => $newbid,
					'tpl_module' => 'system',
					'tpl_tplset' => 'default',
					'tpl_file' => $newblock['template'],
					'tpl_desc' => $newblock['description'],
					'tpl_lastmodified' => time(),
					'tpl_lastimported' => time(),
					'tpl_type' => 'block'
				]
			]);
			$dbm->insert('tplsource', [
				[
					'tpl_id' => $newtplid,
					'tpl_source' => file_get_contents(ICMS_ROOT_PATH . '/modules/system/templates/blocks/' . $newblock['template'])
				]
			]);
			$dbm->insert("group_permission", [
				[
					'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
					'gperm_itemid' => $newbid,
					'gperm_modid' => 1,
					'gperm_name' => 'block_read'
				],
				[
					'gperm_groupid' => $gruops['XOOPS_GROUP_USERS'],
					'gperm_itemid' => $newbid,
					'gperm_modid' => 1,
					'gperm_name' => 'block_read'
				],
				[
					'gperm_groupid' => $gruops['XOOPS_GROUP_ANONYMOUS'],
					'gperm_itemid' => $newbid,
					'gperm_modid' => 1,
					'gperm_name' => 'block_read'
				],
			]);
		}
	}
	// adding welcome custom block visible for webmasters
	$welcome_webmaster_filename = 'language/' . $language . '/welcome_webmaster.tpl';
	if (!file_exists($welcome_webmaster_filename)) {
		$welcome_webmaster_filename = 'language/english/welcome_webmaster.tpl';
	}
	if (file_exists($welcome_webmaster_filename)) {
		$newbid = $dbm->insert('newblocks', [
			[
				'mid' => 0,
				'func_num' => 0,
				'options' => '',
				'name' => 'Custom Block (Auto Format + smilies)',
				'title' => WELCOME_WEBMASTER,
				'content' => file_get_contents('language/' . $language . '/welcome_webmaster.tpl'),
				'side' => 4,
				'weight' => 0,
				'visible' => 1,
				'block_type' => 'C',
				'c_type' => 'S',
				'isactive' => 1,
				'dirname' => '',
				'func_file' => '',
				'show_func' => '',
				'edit_func' => '',
				'template' => '',
				'bcachetime' => 0,
				'last_modified' => time()
			]
		]);
		$dbm->insert("group_permission", [
			[
				'gperm_groupid' => $gruops['XOOPS_GROUP_ADMIN'],
				'gperm_itemid' => $newbid,
				'gperm_modid' => 1,
				'gperm_name' => 'block_read'
			],
		]);
	}
	// adding welcome custom block visible for anonymous
	$welcome_anonymous_filename = 'language/' . $language . '/welcome_anonymous.tpl';
	if (!file_exists($welcome_anonymous_filename)) {
		$welcome_anonymous_filename = 'language/english/welcome_anonymous.tpl';
	}
	if (file_exists($welcome_anonymous_filename)) {
		$newbid = $dbm->insert('newblocks', [
			[
				'mid' => 0,
				'func_num' => 0,
				'options' => '',
				'name' => 'Custom Block (Auto Format + smilies)',
				'title' => WELCOME_ANONYMOUS,
				'content' => file_get_contents('language/' . $language . '/welcome_anonymous.tpl'),
				'side' => 4,
				'weight' => 0,
				'visible' => 1,
				'block_type' => 'C',
				'c_type' => 'S',
				'isactive' => 1,
				'dirname' => '',
				'func_file' => '',
				'show_func' => '',
				'edit_func' => '',
				'template' => '',
				'bcachetime' => 0,
				'last_modified' => time()
			]
		]);
		$dbm->insert("group_permission", [
			[
				'gperm_groupid' => $gruops['XOOPS_GROUP_ANONYMOUS'],
				'gperm_itemid' => $newbid,
				'gperm_modid' => 1,
				'gperm_name' => 'block_read'
			],
		]);
	}

	// data for table 'users'
	$pwd = new icms_core_Password();
	$temp = $pwd->encryptPass($adminpass);
	$regdate = time();
	// RMV-NOTIFY (updated for extra columns in user table)
	$dbm->insert('users', [
		[
			'uid' => 1,
			'name' => '',
			'uname' => $adminname,
			'email' => $adminmail,
			'url' => ICMS_URL,
			'user_avatar' => 'blank.gif',
			'user_regdate' => $regdate,
			'user_from' => '',
			'user_sig' => '',
			'user_viewemail' => 0,
			'actkey' => '',
			'pass' => $temp,
			'posts' => 0,
			'attachsig' => 0,
			'rank' => 7,
			'level' => 5,
			'theme' => 'iTheme',
			'timezone_offset' => '0.0',
			'last_login' => time(),
			'umode' => 'thread',
			'uorder' => 0,
			'notify_method' => 1,
			'notify_mode' => 0,
			'user_occ' => '',
			'bio' => '',
			'user_intrest' => '',
			'user_mailok' => 0,
			'language' => $language,
			'openid' => '',
			'salt' => '',
			'user_viewoid' => 0,
			'pass_expired' => 0,
			'enc_type' => 1,
			'login_name' => $adminlogin_name
		]
	]);

	// data for table 'block_module_link'
	$sql = 'SELECT bid, side, template FROM ' . $dbm->prefix('newblocks');
	$result = $dbm->query($sql);

	$links = [];
	while ($myrow = $dbm->fetchArray($result)) {
		# Adding dynamic block area/position system - TheRpLima - 2007-10-21
		if ($myrow['side'] == 1 or $myrow['side'] == 2 or $myrow['side'] == 7) {
			$links[] = [
				'block_id' => $myrow['bid'],
				'module_id' => 0,
				'page_id' => 0
			];
		} elseif (in_array($myrow['template'], array('system_admin_block_warnings.html', 'system_admin_block_cp.html', 'system_admin_block_modules.html', 'system_block_newusers.html', 'system_block_online.html', 'system_block_waiting.html', 'system_block_topusers.html'))) {
			$links[] = [
				'block_id' => $myrow['bid'],
				'module_id' => 1,
				'page_id' => 2
			];
		} else {
			$links[] = [
				'block_id' => $myrow['bid'],
				'module_id' => 0,
				'page_id' => 1
			];
		}
	}

	$dbm->insert("block_module_link", $links);

	// Data for table 'config'

	$i = 0; // sets auto increment for config values (incremented using $i++ after each value.)
	$ci = 1; // sets auto increment for configoption values (incremented using $ci++ after each value.)

	// Data for Config Category 1 (System Preferences)
	$c = 1; // sets config category id
	$p = 0; // sets auto increment for config position (the order in which the option is displayed in the form)
	$dbm->insert('config',
		[
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'sitename',
				'conf_title' => '_MD_AM_SITENAME',
				'conf_value' => _LOCAOL_STNAME,
				'conf_desc' => '_MD_AM_SITENAMEDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'slogan',
				'conf_title' => '_MD_AM_SLOGAN',
				'conf_value' => _LOCAL_SLOCGAN,
				'conf_desc' => '_MD_AM_SLOGANDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'adminmail',
				'conf_title' => '_MD_AM_ADMINML',
				'conf_value' => $adminmail,
				'conf_desc' => '_MD_AM_ADMINMLDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'language',
				'conf_title' => '_MD_AM_LANGUAGE',
				'conf_value' => $language,
				'conf_desc' => '_MD_AM_LANGUAGEDSC',
				'conf_formtype' => 'language',
				'conf_valuetype' => 'other',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'startpage',
				'conf_title' => '_MD_AM_STARTPAGE',
				'conf_value' => serialize([
					1 => '--',
					2 => '--',
					3 => '--',
				]),
				'conf_desc' => '_MD_AM_STARTPAGEDSC',
				'conf_formtype' => 'startpage',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'server_TZ',
				'conf_title' => '_MD_AM_SERVERTZ',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_SERVERTZDSC',
				'conf_formtype' => 'timezone',
				'conf_valuetype' => 'float',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'default_TZ',
				'conf_title' => '_MD_AM_DEFAULTTZ',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_DEFAULTTZDSC',
				'conf_formtype' => 'timezone',
				'conf_valuetype' => 'float',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'use_ext_date',
				'conf_title' => '_MD_AM_EXT_DATE',
				'conf_value' => _EXT_DATE_FUNC,
				'conf_desc' => '_MD_AM_EXT_DATEDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'theme_set',
				'conf_title' => '_MD_AM_DTHEME',
				'conf_value' => 'iTheme',
				'conf_desc' => '_MD_AM_DTHEMEDSC',
				'conf_formtype' => 'theme',
				'conf_valuetype' => 'other',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'theme_admin_set',
				'conf_title' => '_MD_AM_ADMIN_DTHEME',
				'conf_value' => 'iTheme',
				'conf_desc' => '_MD_AM_ADMIN_DTHEME_DESC',
				'conf_formtype' => 'theme_admin',
				'conf_valuetype' => 'other',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'theme_fromfile',
				'conf_title' => '_MD_AM_THEMEFILE',
				'conf_value' => 0,
				'conf_desc' => '_MD_AM_THEMEFILEDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'theme_set_allowed',
				'conf_title' => '_MD_AM_THEMEOK',
				'conf_value' => serialize([
					'iTheme'
				]),
				'conf_desc' => '_MD_AM_THEMEOKDSC',
				'conf_formtype' => 'theme_multi',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'template_set',
				'conf_title' => '_MD_AM_DTPLSET',
				'conf_value' => 'default',
				'conf_desc' => '_MD_AM_DTPLSETDSC',
				'conf_formtype' => 'tplset',
				'conf_valuetype' => 'other',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'editor_default',
				'conf_title' => '_MD_AM_EDITOR_DEFAULT',
				'conf_value' => 'dhtmltextarea',
				'conf_desc' => '_MD_AM_EDITOR_DEFAULT_DESC',
				'conf_formtype' => 'editor',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'editor_enabled_list',
				'conf_title' => '_MD_AM_EDITOR_ENABLED_LIST',
				'conf_value' => serialize([
					'dhtmltextarea',
					'CKeditor',
					'tinymce'
				]),
				'conf_desc' => '_MD_AM_EDITOR_ENABLED_LIST_DESC',
				'conf_formtype' => 'editor_multi',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'sourceeditor_default',
				'conf_title' => '_MD_AM_SRCEDITOR_DEFAULT',
				'conf_value' => 'editarea',
				'conf_desc' => '_MD_AM_SRCEDITOR_DEFAULT_DESC',
				'conf_formtype' => 'editor_source',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'anonymous',
				'conf_title' => '_MD_AM_ANONNAME',
				'conf_value' => _INSTALL_ANON,
				'conf_desc' => '_MD_AM_ANONNAMEDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'gzip_compression',
				'conf_title' => '_MD_AM_USEGZIP',
				'conf_value' => 0,
				'conf_desc' => '_MD_AM_USEGZIPDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'usercookie',
				'conf_title' => '_MD_AM_USERCOOKIE',
				'conf_value' => 'icms_user',
				'conf_desc' => '_MD_AM_USERCOOKIEDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'use_mysession',
				'conf_title' => '_MD_AM_USEMYSESS',
				'conf_value' => 0,
				'conf_desc' => '_MD_AM_USEMYSESSDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'session_name',
				'conf_title' => '_MD_AM_SESSNAME',
				'conf_value' => 'icms_session',
				'conf_desc' => '_MD_AM_SESSNAMEDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'session_expire',
				'conf_title' => '_MD_AM_SESSEXPIRE',
				'conf_value' => 15,
				'conf_desc' => '_MD_AM_SESSEXPIREDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
		]);

	// ----------
	$dbm->insert('config',
		[
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'closesite',
				'conf_title' => '_MD_AM_CLOSESITE',
				'conf_value' => 0,
				'conf_desc' => '_MD_AM_CLOSESITEDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'closesite_okgrp',
				'conf_title' => '_MD_AM_CLOSESITEOK',
				'conf_value' => serialize(['1']),
				'conf_desc' => '_MD_AM_CLOSESITEOKDSC',
				'conf_formtype' => 'group_multi',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'closesite_text',
				'conf_title' => '_MD_AM_CLOSESITETXT',
				'conf_value' => _INSTALL_L165,
				'conf_desc' => '_MD_AM_CLOSESITETXTDSC',
				'conf_formtype' => 'textsarea',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'my_ip',
				'conf_title' => '_MD_AM_MYIP',
				'conf_value' => '127.0.0.1',
				'conf_desc' => '_MD_AM_MYIPDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'com_mode',
				'conf_title' => '_MD_AM_COMMODE',
				'conf_value' => 'nest',
				'conf_desc' => '_MD_AM_COMMODEDSC',
				'conf_formtype' => 'select',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
		]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_NESTED',
			'confop_value' => 'nest',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_FLAT',
			'confop_value' => 'flat',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_THREADED',
			'confop_value' => 'thread',
			'conf_id' => $i
		],
	]);
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'com_order',
			'conf_title' => '_MD_AM_COMORDER',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_COMORDERDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_OLDESTFIRST',
			'confop_value' => 0,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_NEWESTFIRST',
			'confop_value' => 1,
			'conf_id' => $i
		]
	]);
	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'use_captchaf',
			'conf_title' => '_MD_AM_USECAPTCHAFORM',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_USECAPTCHAFORMDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'enable_badips',
			'conf_title' => '_MD_AM_DOBADIPS',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_DOBADIPSDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'bad_ips',
			'conf_title' => '_MD_AM_BADIPS',
			'conf_value' => serialize(array('127.0.0.1')),
			'conf_desc' => '_MD_AM_BADIPSDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'module_cache',
			'conf_title' => '_MD_AM_MODCACHE',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_MODCACHEDSC',
			'conf_formtype' => 'module_cache',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 2 (User Preferences)
	$c = 2; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'allow_register',
			'conf_title' => '_MD_AM_ALLOWREG',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_ALLOWREGDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'minpass',
			'conf_title' => '_MD_AM_MINPASS',
			'conf_value' => 5,
			'conf_desc' => '_MD_AM_MINPASSDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'pass_level',
			'conf_title' => '_MD_AM_PASSLEVEL',
			'conf_value' => 40,
			'conf_desc' => '_MD_AM_PASSLEVEL_DESC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PASSLEVEL1',
			'confop_value' => 20,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PASSLEVEL2',
			'confop_value' => 40,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PASSLEVEL3',
			'confop_value' => 60,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PASSLEVEL4',
			'confop_value' => 80,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PASSLEVEL5',
			'confop_value' => 95,
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'minuname',
			'conf_title' => '_MD_AM_MINUNAME',
			'conf_value' => 3,
			'conf_desc' => '_MD_AM_MINUNAMEDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'maxuname',
			'conf_title' => '_MD_AM_MAXUNAME',
			'conf_value' => 20,
			'conf_desc' => '_MD_AM_MAXUNAMEDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'delusers',
			'conf_title' => '_MD_AM_DELUSRES',
			'conf_value' => 30,
			'conf_desc' => '_MD_AM_DELUSRESDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'use_captcha',
			'conf_title' => '_MD_AM_USECAPTCHA',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_USECAPTCHADSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'welcome_msg',
			'conf_title' => '_MD_AM_WELCOMEMSG',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_WELCOMEMSGDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'welcome_msg_content',
			'conf_title' => '_MD_AM_WELCOMEMSG_CONTENT',
			'conf_value' => _WELCOME_MSG_CONTENT,
			'conf_desc' => '_MD_AM_WELCOMEMSG_CONTENTDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'allow_chgmail',
			'conf_title' => '_MD_AM_ALLWCHGMAIL',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_ALLWCHGMAILDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'allow_chguname',
			'conf_title' => '_MD_AM_ALLWCHGUNAME',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_ALLWCHGUNAMEDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'allwshow_sig',
			'conf_title' => '_MD_AM_ALLWSHOWSIG',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_ALLWSHOWSIGDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'allow_htsig',
			'conf_title' => '_MD_AM_ALLWHTSIG',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_ALLWHTSIGDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'sig_max_length',
			'conf_title' => '_MD_AM_SIGMAXLENGTH',
			'conf_value' => 255,
			'conf_desc' => '_MD_AM_SIGMAXLENGTHDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'new_user_notify',
			'conf_title' => '_MD_AM_NEWUNOTIFY',
			'conf_value' => 1,
			'conf_desc' => '_MD_AM_NEWUNOTIFYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'new_user_notify_group',
			'conf_title' => '_MD_AM_NOTIFYTO',
			'conf_value' => $gruops['XOOPS_GROUP_ADMIN'],
			'conf_desc' => '_MD_AM_NOTIFYTODSC',
			'conf_formtype' => 'group',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'activation_type',
			'conf_title' => '_MD_AM_ACTVTYPE',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_ACTVTYPEDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_USERACTV',
			'confop_value' => 0,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_AUTOACTV',
			'confop_value' => 1,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ADMINACTV',
			'confop_value' => 2,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_REGINVITE',
			'confop_value' => 3,
			'conf_id' => $i
		],
	]);
	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'activation_group',
			'conf_title' => '_MD_AM_ACTVGROUP',
			'conf_value' => $gruops['XOOPS_GROUP_ADMIN'],
			'conf_desc' => '_MD_AM_ACTVGROUPDSC',
			'conf_formtype' => 'group',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'uname_test_level',
			'conf_title' => '_MD_AM_UNAMELVL',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_UNAMELVLDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_STRICT',
			'confop_value' => 0,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_MEDIUM',
			'confop_value' => 1,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_LIGHT',
			'confop_value' => 2,
			'conf_id' => $i
		],
	]);
	// ----------
	$dbm->insert('config',
		[
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_allow_upload',
				'conf_title' => '_MD_AM_AVATARALLOW',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_AVATARALWDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_allow_gravatar',
				'conf_title' => '_MD_AM_GRAVATARALLOW',
				'conf_value' => '1',
				'conf_desc' => '_MD_AM_GRAVATARALWDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			/* the avatar resizer shall later be included
            [
                'conf_id' => ++$i,
                'conf_modid' => 0,
                'conf_catid' => $c,
                'conf_name' => 'avatar_auto_resize',
                'conf_title' => '_MD_AM_AUTORESIZE',
                'conf_value' => '0',
                'conf_desc' => '_MD_AM_AUTORESIZE_DESC',
                'conf_formtype' => 'yesno',
                'conf_valuetype' => 'int',
                'conf_order' => $p++
            ],
             */
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_minposts',
				'conf_title' => '_MD_AM_AVATARMP',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_AVATARMPDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_width',
				'conf_title' => '_MD_AM_AVATARW',
				'conf_value' => '80',
				'conf_desc' => '_MD_AM_AVATARWDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_height',
				'conf_title' => '_MD_AM_AVATARH',
				'conf_value' => '80',
				'conf_desc' => '_MD_AM_AVATARHDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'avatar_maxsize',
				'conf_title' => '_MD_AM_AVATARMAX',
				'conf_value' => '35000',
				'conf_desc' => '_MD_AM_AVATARMAXDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'self_delete',
				'conf_title' => '_MD_AM_SELFDELETE',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_SELFDELETEDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'rank_width',
				'conf_title' => '_MD_AM_RANKW',
				'conf_value' => '120',
				'conf_desc' => '_MD_AM_RANKWDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'rank_height',
				'conf_title' => '_MD_AM_RANKH',
				'conf_value' => '120',
				'conf_desc' => '_MD_AM_RANKHDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'rank_maxsize',
				'conf_title' => '_MD_AM_RANKMAX',
				'conf_value' => '35000',
				'conf_desc' => '_MD_AM_RANKMAXDSC',
				'conf_formtype' => 'textbox',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'bad_unames',
				'conf_title' => '_MD_AM_BADUNAMES',
				'conf_value' => serialize(array('webmaster', '^impresscms', '^admin')),
				'conf_desc' => '_MD_AM_BADUNAMESDSC',
				'conf_formtype' => 'textsarea',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'bad_emails',
				'conf_title' => '_MD_AM_BADEMAILS',
				'conf_value' => serialize(array('impresscms.org$')),
				'conf_desc' => '_MD_AM_BADEMAILSDSC',
				'conf_formtype' => 'textsarea',
				'conf_valuetype' => 'array',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'remember_me',
				'conf_title' => '_MD_AM_REMEMBERME',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_REMEMBERMEDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'reg_dispdsclmr',
				'conf_title' => '_MD_AM_DSPDSCLMR',
				'conf_value' => 1,
				'conf_desc' => '_MD_AM_DSPDSCLMRDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'reg_disclaimer',
				'conf_title' => '_MD_AM_REGDSCLMR',
				'conf_value' => _INSTALL_DISCLMR,
				'conf_desc' => '_MD_AM_REGDSCLMRDSC',
				'conf_formtype' => 'textsarea',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'priv_dpolicy',
				'conf_title' => '_MD_AM_PRIVDPOLICY',
				'conf_value' => 0,
				'conf_desc' => '_MD_AM_PRIVDPOLICYDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'priv_policy',
				'conf_title' => '_MD_AM_PRIVPOLICY',
				'conf_value' => _INSTALL_PRIVPOLICY,
				'conf_desc' => '_MD_AM_PRIVPOLICYDSC',
				'conf_formtype' => 'textsarea',
				'conf_valuetype' => 'text',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'allow_annon_view_prof',
				'conf_title' => '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE',
				'conf_value' => '0',
				'conf_desc' => '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			],
			[
				'conf_id' => ++$i,
				'conf_modid' => 0,
				'conf_catid' => $c,
				'conf_name' => 'enc_type',
				'conf_title' => '_MD_AM_ENC_TYPE',
				'conf_value' => '23',
				'conf_desc' => '_MD_AM_ENC_TYPEDSC',
				'conf_formtype' => 'select',
				'conf_valuetype' => 'int',
				'conf_order' => $p++
			]
		]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_MD5',
			'confop_value' => '20',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_SHA256',
			'confop_value' => '21',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_SHA384',
			'confop_value' => '22',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_SHA512',
			'confop_value' => '23',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_RIPEMD128',
			'confop_value' => '24',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_RIPEMD160',
			'confop_value' => '25',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_WHIRLPOOL',
			'confop_value' => '26',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1284',
			'confop_value' => '27',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1604',
			'confop_value' => '28',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1924',
			'confop_value' => '29',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL2244',
			'confop_value' => '30',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL2564',
			'confop_value' => '31',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1285',
			'confop_value' => '32',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1605',
			'confop_value' => '33',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL1925',
			'confop_value' => '34',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL2245',
			'confop_value' => '35',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ENC_HAVAL2565',
			'confop_value' => '36',
			'conf_id' => $i
		],
	]);
	// ----------

	// Data for Config Category 3 (Meta & Footer Preferences)
	$c = 3; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_keywords',
			'conf_title' => '_MD_AM_METAKEY',
			'conf_value' => 'community management system, CMS, content management, social networking, community, blog, support, modules, add-ons, themes',
			'conf_desc' => '_MD_AM_METAKEYDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_description',
			'conf_title' => '_MD_AM_METADESC',
			'conf_value' => 'ImpressCMS is a dynamic Object Oriented based open source portal script written in PHP.',
			'conf_desc' => '_MD_AM_METADESCDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_robots',
			'conf_title' => '_MD_AM_METAROBOTS',
			'conf_value' => 'index,follow',
			'conf_desc' => '_MD_AM_METAROBOTSDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		]
	]);

	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_INDEXFOLLOW',
			'confop_value' => 'index,follow',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_NOINDEXFOLLOW',
			'confop_value' => 'noindex,follow',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_INDEXNOFOLLOW',
			'confop_value' => 'index,nofollow',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_NOINDEXNOFOLLOW',
			'confop_value' => 'noindex,nofollow',
			'conf_id' => $i
		],
	]);
	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_rating',
			'conf_title' => '_MD_AM_METARATING',
			'conf_value' => 'general',
			'conf_desc' => '_MD_AM_METARATINGDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		]
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_METAOGEN',
			'confop_value' => 'general',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_METAO14YRS',
			'confop_value' => '14 years',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_METAOREST',
			'confop_value' => 'restricted',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_METAOMAT',
			'confop_value' => 'mature',
			'conf_id' => $i
		],
	]);
	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_author',
			'conf_title' => '_MD_AM_METAAUTHOR',
			'conf_value' => 'ImpressCMS',
			'conf_desc' => '_MD_AM_METAAUTHORDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'meta_copyright',
			'conf_title' => '_MD_AM_METACOPYR',
			'conf_value' => 'Copyright &copy; 2007-' . date('Y', time()),
			'conf_desc' => '_MD_AM_METACOPYRDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'google_meta',
			'conf_title' => '_MD_AM_METAGOOGLE',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_METAGOOGLE_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'footer',
			'conf_title' => '_MD_AM_FOOTER',
			'conf_value' =>  _LOCAL_FOOTER ,
			'conf_desc' => '_MD_AM_FOOTERDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'use_google_analytics',
			'conf_title' => '_MD_AM_USE_GOOGLE_ANA',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_USE_GOOGLE_ANA_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'google_analytics',
			'conf_title' => '_MD_AM_GOOGLE_ANA',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_GOOGLE_ANA_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'footadm',
			'conf_title' => '_MD_AM_FOOTADM',
			'conf_value' => _LOCAL_FOOTER,
			'conf_desc' => '_MD_AM_FOOTADM_DESC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 4 (Badword Preferences)
	$c = 4; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'censor_enable',
			'conf_title' => '_MD_AM_DOCENSOR',
			'conf_value' => 0,
			'conf_desc' => '_MD_AM_DOCENSORDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'censor_words',
			'conf_title' => '_MD_AM_CENSORWRD',
			'conf_value' => serialize([
				'fuck',
				'shit',
				'cunt',
				'wanker',
				'bastard'
			]),
			'conf_desc' => '_MD_AM_CENSORWRDDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'censor_replace',
			'conf_title' => '_MD_AM_CENSORRPLC',
			'conf_value' => _LOCAL_SENSORTXT,
			'conf_desc' => '_MD_AM_CENSORRPLCDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 5 (Search Preferences)
	$c = 5; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'enable_search',
			'conf_title' => '_MD_AM_DOSEARCH',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_DOSEARCHDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'enable_deep_search',
			'conf_title' => '_MD_AM_DODEEPSEARCH',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_DODEEPSEARCHDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'num_shallow_search',
			'conf_title' => '_MD_AM_NUMINITSRCHRSLTS',
			'conf_value' => '5',
			'conf_desc' => '_MD_AM_NUMINITSRCHRSLTSDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'keyword_min',
			'conf_title' => '_MD_AM_MINSEARCH',
			'conf_value' => '3',
			'conf_desc' => '_MD_AM_MINSEARCHDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'search_user_date',
			'conf_title' => '_MD_AM_SEARCH_USERDATE',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_SEARCH_USERDATE',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'search_no_res_mod',
			'conf_title' => '_MD_AM_SEARCH_NO_RES_MOD',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_SEARCH_NO_RES_MODDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'search_per_page',
			'conf_title' => '_MD_AM_SEARCH_PER_PAGE',
			'conf_value' => '20',
			'conf_desc' => '_MD_AM_SEARCH_PER_PAGEDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 6 (Mail Settings)
	$c = 6; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'from',
			'conf_title' => '_MD_AM_MAILFROM',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_MAILFROMDESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'fromname',
			'conf_title' => '_MD_AM_MAILFROMNAME',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_MAILFROMNAMEDESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		// RMV-NOTIFY... Need to specify which user is sender of notification PM
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'fromuid',
			'conf_title' => '_MD_AM_MAILFROMUID',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_MAILFROMUIDDESC',
			'conf_formtype' => 'user',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'mailmethod',
			'conf_title' => '_MD_AM_MAILERMETHOD',
			'conf_value' => 'mail',
			'conf_desc' => '_MD_AM_MAILERMETHODDESC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => 'PHP mail()',
			'confop_value' => 'mail',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'sendmail',
			'confop_value' => 'sendmail',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'SMTP',
			'confop_value' => 'smtp',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'SMTPAuth',
			'confop_value' => 'smtpauth',
			'conf_id' => $i
		],
	]);
	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'smtphost',
			'conf_title' => '_MD_AM_SMTPHOST',
			'conf_value' => serialize([
				0 => '',
			]),
			'conf_desc' => '_MD_AM_SMTPHOSTDESC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'smtpuser',
			'conf_title' => '_MD_AM_SMTPUSER',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_SMTPUSERDESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'smtppass',
			'conf_title' => '_MD_AM_SMTPPASS',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_SMTPPASSDESC',
			'conf_formtype' => 'password',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'smtpsecure',
			'conf_title' => '_MD_AM_SMTPSECURE',
			'conf_value' => 'ssl',
			'conf_desc' => '_MD_AM_SMTPSECUREDESC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => 'None',
			'confop_value' => '',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'SSL',
			'confop_value' => 'ssl',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'TLS',
			'confop_value' => 'tls',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'smtpauthport',
			'conf_title' => '_MD_AM_SMTPAUTHPORT',
			'conf_value' => '465',
			'conf_desc' => '_MD_AM_SMTPAUTHPORTDESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'sendmailpath',
			'conf_title' => '_MD_AM_SENDMAILPATH',
			'conf_value' => '/usr/sbin/sendmail',
			'conf_desc' => '_MD_AM_SENDMAILPATHDESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 7 (Authentication Settings)
	$c = 7; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'auth_method',
			'conf_title' => '_MD_AM_AUTHMETHOD',
			'conf_value' => 'local',
			'conf_desc' => '_MD_AM_AUTHMETHODDESC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_AUTH_CONFOPTION_XOOPS',
			'confop_value' => 'local',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_AUTH_CONFOPTION_LDAP',
			'confop_value' => 'ldap',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_AUTH_CONFOPTION_AD',
			'confop_value' => 'ads',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'auth_openid',
			'conf_title' => '_MD_AM_AUTHOPENID',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_AUTHOPENIDDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_port',
			'conf_title' => '_MD_AM_LDAP_PORT',
			'conf_value' => '389',
			'conf_desc' => '_MD_AM_LDAP_PORT',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_server',
			'conf_title' => '_MD_AM_LDAP_SERVER',
			'conf_value' => 'your directory server',
			'conf_desc' => '_MD_AM_LDAP_SERVER_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_base_dn',
			'conf_title' => '_MD_AM_LDAP_BASE_DN',
			'conf_value' => 'dc=icms,dc=org',
			'conf_desc' => '_MD_AM_LDAP_BASE_DN_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_manager_dn',
			'conf_title' => '_MD_AM_LDAP_MANAGER_DN',
			'conf_value' => 'manager_dn',
			'conf_desc' => '_MD_AM_LDAP_MANAGER_DN_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_manager_pass',
			'conf_title' => '_MD_AM_LDAP_MANAGER_PASS',
			'conf_value' => 'manager_pass',
			'conf_desc' => '_MD_AM_LDAP_MANAGER_PASS_DESC',
			'conf_formtype' => 'password',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_version',
			'conf_title' => '_MD_AM_LDAP_VERSION',
			'conf_value' => '3',
			'conf_desc' => '_MD_AM_LDAP_VERSION_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_users_bypass',
			'conf_title' => '_MD_AM_LDAP_USERS_BYPASS',
			'conf_value' => serialize([
				'admin'
			]),
			'conf_desc' => '_MD_AM_LDAP_USERS_BYPASS_DESC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_loginname_asdn',
			'conf_title' => '_MD_AM_LDAP_LOGINNAME_ASDN',
			'conf_value' => 'uid_asdn',
			'conf_desc' => '_MD_AM_LDAP_LOGINNAME_ASDN_D',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_loginldap_attr',
			'conf_title' => '_MD_AM_LDAP_LOGINLDAP_ATTR',
			'conf_value' => 'uid',
			'conf_desc' => '_MD_AM_LDAP_LOGINLDAP_ATTR_D',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_filter_person',
			'conf_title' => '_MD_AM_LDAP_FILTER_PERSON',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_LDAP_FILTER_PERSON_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_domain_name',
			'conf_title' => '_MD_AM_LDAP_DOMAIN_NAME',
			'conf_value' => 'mydomain',
			'conf_desc' => '_MD_AM_LDAP_DOMAIN_NAME_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_provisionning',
			'conf_title' => '_MD_AM_LDAP_PROVIS',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_LDAP_PROVIS_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_provisionning_group',
			'conf_title' => '_MD_AM_LDAP_PROVIS_GROUP',
			'conf_value' => serialize([
				0 => '2',
			]),
			'conf_desc' => '_MD_AM_LDAP_PROVIS_GROUP_DSC',
			'conf_formtype' => 'group_multi',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_mail_attr',
			'conf_title' => '_MD_AM_LDAP_MAIL_ATTR',
			'conf_value' => 'mail',
			'conf_desc' => '_MD_AM_LDAP_MAIL_ATTR_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_givenname_attr',
			'conf_title' => '_MD_AM_LDAP_GIVENNAME_ATTR',
			'conf_value' => 'givenname',
			'conf_desc' => '_MD_AM_LDAP_GIVENNAME_ATTR_DSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_surname_attr',
			'conf_title' => '_MD_AM_LDAP_SURNAME_ATTR',
			'conf_value' => 'sn',
			'conf_desc' => '_MD_AM_LDAP_SURNAME_ATTR_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_field_mapping',
			'conf_title' => '_MD_AM_LDAP_FIELD_MAPPING_ATTR',
			'conf_value' => 'email=mail|name=displayname',
			'conf_desc' => '_MD_AM_LDAP_FIELD_MAPPING_DESC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_provisionning_upd',
			'conf_title' => '_MD_AM_LDAP_PROVIS_UPD',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_LDAP_PROVIS_UPD_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ldap_use_TLS',
			'conf_title' => '_MD_AM_LDAP_USETLS',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_LDAP_USETLS_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 8 (Multi Language Settings)
	$c = 8; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_enable',
			'conf_title' => '_MD_AM_ML_ENABLE',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_ML_ENABLEDEC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_autoselect_enabled',
			'conf_title' => '_MD_AM_ML_AUTOSELECT_ENABLED',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_ML_AUTOSELECT_ENABLED_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_tags',
			'conf_title' => '_MD_AM_ML_TAGS',
			'conf_value' => _DEF_LANG_TAGS,
			'conf_desc' => '_MD_AM_ML_TAGSDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_names',
			'conf_title' => '_MD_AM_ML_NAMES',
			'conf_value' => _DEF_LANG_NAMES,
			'conf_desc' => '_MD_AM_ML_NAMESDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_captions',
			'conf_title' => '_MD_AM_ML_CAPTIONS',
			'conf_value' => _LOCAL_LANG_NAMES,
			'conf_desc' => '_MD_AM_ML_CAPTIONSDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'ml_charset',
			'conf_title' => '_MD_AM_ML_CHARSET',
			'conf_value' => 'UTF-8,UTF-8',
			'conf_desc' => '_MD_AM_ML_CHARSETDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 9 (Content Manager Settings)
	$c = 9; // sets config category id
	$p = 0;
	/* These have been deprecated in 1.2 and should not be inserted. They are part of the content module now */

	// Data for Config Category 10 (Personalization Settings)
	$c = 10; // sets config category id
	$p = 0;
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_left_logo',
			'conf_title' => '_MD_AM_LLOGOADM',
			'conf_value' => '/uploads/imagemanager/logos/img482278e29e81c.png',
			'conf_desc' => '_MD_AM_LLOGOADM_DESC',
			'conf_formtype' => 'select_image',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_left_logo_url',
			'conf_title' => '_MD_AM_LLOGOADM_URL',
			'conf_value' =>  ICMS_URL . '/',
			'conf_desc' => '_MD_AM_LLOGOADM_URL_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_left_logo_alt',
			'conf_title' => '_MD_AM_LLOGOADM_ALT',
			'conf_value' => 'ImpressCMS',
			'conf_desc' => '_MD_AM_LLOGOADM_ALT_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_right_logo',
			'conf_title' => '_MD_AM_RLOGOADM',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_RLOGOADM_DESC',
			'conf_formtype' => 'select_image',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_right_logo_url',
			'conf_title' => '_MD_AM_RLOGOADM_URL',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_RLOGOADM_URL_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'adm_right_logo_alt',
			'conf_title' => '_MD_AM_RLOGOADM_ALT',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_RLOGOADM_ALT_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'rss_local',
			'conf_title' => '_MD_AM_RSSLOCAL',
			'conf_value' =>  _MD_AM_RSSLOCALLINK_DESC ,
			'conf_desc' => '_MD_AM_RSSLOCAL_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'editre_block',
			'conf_title' => '_MD_AM_EDITREMOVEBLOCK',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_EDITREMOVEBLOCKDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'use_custom_redirection',
			'conf_title' => '_MD_AM_CUSTOMRED',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_CUSTOMREDDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'multi_login',
			'conf_title' => '_MD_AM_MULTLOGINPREVENT',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_MULTLOGINPREVENTDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'email_protect',
			'conf_title' => '_MD_AM_EMAILPROTECT',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_EMAILPROTECTDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_NOMAILPROTECT',
			'confop_value' => 0,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_GDMAILPROTECT',
			'confop_value' => 1,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_SCMAILPROTECT',
			'confop_value' => 2,
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'email_font',
			'conf_title' => '_MD_AM_EMAILTTF',
			'conf_value' => 'arial.ttf',
			'conf_desc' => '_MD_AM_EMAILTTF_DESC',
			'conf_formtype' => 'select_font',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'email_font_len',
			'conf_title' => '_MD_AM_EMAILLEN',
			'conf_value' => '10',
			'conf_desc' => '_MD_AM_EMAILLEN_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'email_cor',
			'conf_title' => '_MD_AM_EMAILCOLOR',
			'conf_value' => '#000000',
			'conf_desc' => '_MD_AM_EMAILCOLOR_DESC',
			'conf_formtype' => 'color',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'email_shadow',
			'conf_title' => '_MD_AM_EMAILSHADOW',
			'conf_value' => '#cccccc',
			'conf_desc' => '_MD_AM_EMAILSHADOW_DESC',
			'conf_formtype' => 'color',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'shadow_x',
			'conf_title' => '_MD_AM_SHADOWX',
			'conf_value' => '2',
			'conf_desc' => '_MD_AM_SHADOWX_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'shadow_y',
			'conf_title' => '_MD_AM_SHADOWY',
			'conf_value' => '2',
			'conf_desc' => '_MD_AM_SHADOWY_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'shorten_url',
			'conf_title' => '_MD_AM_SHORTURL',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_SHORTURLDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'max_url_long',
			'conf_title' => '_MD_AM_URLLEN',
			'conf_value' => '50',
			'conf_desc' => '_MD_AM_URLLEN_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'pre_chars_left',
			'conf_title' => '_MD_AM_PRECHARS',
			'conf_value' => '35',
			'conf_desc' => '_MD_AM_PRECHARS_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'last_chars_left',
			'conf_title' => '_MD_AM_LASTCHARS',
			'conf_value' => '10',
			'conf_desc' => '_MD_AM_LASTCHARS_DESC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'show_impresscms_menu',
			'conf_title' => '_MD_AM_SHOW_ICMSMENU',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_SHOW_ICMSMENU_DESC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'use_jsjalali',
			'conf_title' => '_MD_AM_JALALICAL',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_JALALICALDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'pagstyle',
			'conf_title' => '_MD_AM_PAGISTYLE',
			'conf_value' => 'default',
			'conf_desc' => '_MD_AM_PAGISTYLE_DESC',
			'conf_formtype' => 'select_paginati',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 11 (CAPTCHA Settings)
	$c = 11; // sets config category id
	$p = 0;
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_mode',
			'conf_title' => '_MD_AM_CAPTCHA_MODE',
			'conf_value' => 'image',
			'conf_desc' => '_MD_AM_CAPTCHA_MODEDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_CAPTCHA_OFF',
			'confop_value' => 'none',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_CAPTCHA_IMG',
			'confop_value' => 'image',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_CAPTCHA_TXT',
			'confop_value' => 'text',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_skipmember',
			'conf_title' => '_MD_AM_CAPTCHA_SKIPMEMBER',
			'conf_value' => serialize(array('2')),
			'conf_desc' => '_MD_AM_CAPTCHA_SKIPMEMBERDSC',
			'conf_formtype' => 'group_multi',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_casesensitive',
			'conf_title' => '_MD_AM_CAPTCHA_CASESENS',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_CAPTCHA_CASESENSDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_skip_characters',
			'conf_title' => '_MD_AM_CAPTCHA_SKIPCHAR',
			'conf_value' => serialize(array('o', '0', 'i', 'l', '1')),
			'conf_desc' => '_MD_AM_CAPTCHA_SKIPCHARDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_maxattempt',
			'conf_title' => '_MD_AM_CAPTCHA_MAXATTEMP',
			'conf_value' => '8',
			'conf_desc' => '_MD_AM_CAPTCHA_MAXATTEMPDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_num_chars',
			'conf_title' => '_MD_AM_CAPTCHA_NUMCHARS',
			'conf_value' => '4',
			'conf_desc' => '_MD_AM_CAPTCHA_NUMCHARSDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_fontsize_min',
			'conf_title' => '_MD_AM_CAPTCHA_FONTMIN',
			'conf_value' => '10',
			'conf_desc' => '_MD_AM_CAPTCHA_FONTMINDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_fontsize_max',
			'conf_title' => '_MD_AM_CAPTCHA_FONTMAX',
			'conf_value' => '12',
			'conf_desc' => '_MD_AM_CAPTCHA_FONTMAXDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_background_type',
			'conf_title' => '_MD_AM_CAPTCHA_BGTYPE',
			'conf_value' => '100',
			'conf_desc' => '_MD_AM_CAPTCHA_BGTYPEDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_BAR',
			'confop_value' => 0,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_CIRCLE',
			'confop_value' => 1,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_LINE',
			'confop_value' => 2,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_RECTANGLE',
			'confop_value' => 3,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_ELLIPSE',
			'confop_value' => 4,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_POLYGON',
			'confop_value' => 5,
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_RANDOM',
			'confop_value' => 100,
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_background_num',
			'conf_title' => '_MD_AM_CAPTCHA_BGNUM',
			'conf_value' => '50',
			'conf_desc' => '_MD_AM_CAPTCHA_BGNUMDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'captcha_polygon_point',
			'conf_title' => '_MD_AM_CAPTCHA_POLPNT',
			'conf_value' => '3',
			'conf_desc' => '_MD_AM_CAPTCHA_POLPNTDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 12 (Text Sanitizer Plugin Settings)
	$c = 12; // sets config category id
	$p = 0;
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'sanitizer_plugins',
			'conf_title' => '_MD_AM_SELECTSPLUGINS',
			'conf_value' => serialize(array("syntaxhighlightphp", "hiddencontent")),
			'conf_desc' => '_MD_AM_SELECTSPLUGINS_DESC',
			'conf_formtype' => 'select_plugin',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'code_sanitizer',
			'conf_title' => '_MD_AM_SELECTSHIGHLIGHT',
			'conf_value' => 'none',
			'conf_desc' => '_MD_AM_SELECTSHIGHLIGHT_DESC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_HIGHLIGHTER_OFF',
			'confop_value' => 'none',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_HIGHLIGHTER_PHP',
			'confop_value' => 'php',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_HIGHLIGHTER_GESHI',
			'confop_value' => 'geshi',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'geshi_default',
			'conf_title' => '_MD_AM_GESHI_DEFAULT',
			'conf_value' => 'php',
			'conf_desc' => '_MD_AM_GESHI_DEFAULT_DESC',
			'conf_formtype' => 'select_geshi',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 13 (AutoTasks)
	$c = 13;
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'autotasks_system',
			'conf_title' => '_MD_AM_AUTOTASKS_SYSTEM',
			'conf_value' => 'internal',
			'conf_desc' => '_MD_AM_AUTOTASKS_SYSTEMDSC',
			'conf_formtype' => 'autotasksystem',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'autotasks_helper',
			'conf_title' => '_MD_AM_AUTOTASKS_HELPER',
			'conf_value' => 'wget %url%',
			'conf_desc' => '_MD_AM_AUTOTASKS_HELPERDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => 'PHP-CGI',
			'confop_value' => 'php -f %path%',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'wget',
			'confop_value' => 'wget %url%',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => 'Lynx',
			'confop_value' => 'lynx --dump %url%',
			'conf_id' => $i
		],
	]);

	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'autotasks_helper_path',
			'conf_title' => '_MD_AM_AUTOTASKS_HELPER_PATH',
			'conf_value' => '/usr/bin/',
			'conf_desc' => '_MD_AM_AUTOTASKS_HELPER_PATHDSC',
			'conf_formtype' => 'text',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'autotasks_user',
			'conf_title' => '_MD_AM_AUTOTASKS_USER',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_AUTOTASKS_USERDSC',
			'conf_formtype' => 'text',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);

	// Data for Config Category 14 (HTMLPurifier Settings)

	$host_domain = imcms_get_base_domain(ICMS_URL);
	$host_base = imcms_get_url_domain(ICMS_URL);

	$c = 14; // sets config category id
	$p = 0; // reset position increment to 0 for new category id
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'enable_purifier',
			'conf_title' => '_MD_AM_PURIFIER_ENABLE',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_ENABLEDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_DefinitionID',
			'conf_title' => '_MD_AM_PURIFIER_URI_DEFID',
			'conf_value' => 'system',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DEFIDDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_DefinitionRev',
			'conf_title' => '_MD_AM_PURIFIER_URI_DEFREV',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DEFREVDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_Host',
			'conf_title' => '_MD_AM_PURIFIER_URI_HOST',
			'conf_value' => $host_domain,
			'conf_desc' => '_MD_AM_PURIFIER_URI_HOSTDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_Base',
			'conf_title' => '_MD_AM_PURIFIER_URI_BASE',
			'conf_value' => $host_base,
			'conf_desc' => '_MD_AM_PURIFIER_URI_BASEDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_Disable',
			'conf_title' => '_MD_AM_PURIFIER_URI_DISABLE',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DISABLEDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_DisableExternal',
			'conf_title' => '_MD_AM_PURIFIER_URI_DISABLEEXT',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DISABLEEXTDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_DisableExternalResources',
			'conf_title' => '_MD_AM_PURIFIER_URI_DISABLEEXTRES',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DISABLEEXTRESDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_DisableResources',
			'conf_title' => '_MD_AM_PURIFIER_URI_DISABLERES',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_URI_DISABLERESDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_MakeAbsolute',
			'conf_title' => '_MD_AM_PURIFIER_URI_MAKEABS',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_URI_MAKEABSDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_HostBlacklist',
			'conf_title' => '_MD_AM_PURIFIER_URI_BLACKLIST',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_URI_BLACKLISTDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_URI_AllowedSchemes',
			'conf_title' => '_MD_AM_PURIFIER_URI_ALLOWSCHEME',
			'conf_value' => serialize(array('http', 'https', 'mailto', 'ftp', 'nntp', 'news')),
			'conf_desc' => '_MD_AM_PURIFIER_URI_ALLOWSCHEMEDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_DefinitionID',
			'conf_title' => '_MD_AM_PURIFIER_HTML_DEFID',
			'conf_value' => 'system',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_DEFIDDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_DefinitionRev',
			'conf_title' => '_MD_AM_PURIFIER_HTML_DEFREV',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_DEFREVDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_Doctype',
			'conf_title' => '_MD_AM_PURIFIER_HTML_DOCTYPE',
			'conf_value' => 'XHTML 1.0 Transitional',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_DOCTYPEDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_401T',
			'confop_value' => 'HTML 4.01 Transitional',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_401S',
			'confop_value' => 'HTML 4.01 Strict',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_X10T',
			'confop_value' => 'XHTML 1.0 Transitional',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_X10S',
			'confop_value' => 'XHTML 1.0 Strict',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_X11',
			'confop_value' => 'XHTML 1.1',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_TidyLevel',
			'conf_title' => '_MD_AM_PURIFIER_HTML_TIDYLEVEL',
			'conf_value' => 'medium',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_TIDYLEVELDSC',
			'conf_formtype' => 'select',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
	]);
	// Insert data for Config Options in selection field. (must be placed before //$i++)
	$dbm->insert('configoption', [
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_NONE',
			'confop_value' => 'none',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_LIGHT',
			'confop_value' => 'light',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_MEDIUM',
			'confop_value' => 'medium',
			'conf_id' => $i
		],
		[
			'confop_id' => $ci++,
			'confop_name' => '_MD_AM_PURIFIER_HEAVY',
			'confop_value' => 'heavy',
			'conf_id' => $i
		],
	]);

	// ----------
	$dbm->insert('config', [
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_AllowedElements',
			'conf_title' => '_MD_AM_PURIFIER_HTML_ALLOWELE',
			'conf_value' => serialize([
				'a',
				'abbr',
				'acronym',
				'b',
				'blockquote',
				'br',
				'caption',
				'cite',
				'code',
				'dd',
				'del',
				'dfn',
				'div',
				'dl',
				'dt',
				'em',
				'font',
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'i',
				'img',
				'ins',
				'kbd',
				'li',
				'ol',
				'p',
				'pre',
				's',
				'span',
				'strike',
				'strong',
				'sub',
				'sup',
				'table',
				'tbody',
				'td',
				'tfoot',
				'th',
				'thead',
				'tr',
				'tt',
				'u',
				'ul',
				'var'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_HTML_ALLOWELEDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_AllowedAttributes',
			'conf_title' => '_MD_AM_PURIFIER_HTML_ALLOWATTR',
			'conf_value' => serialize([
				'a.class',
				'a.href',
				'a.id',
				'a.name',
				'a.rev',
				'a.style',
				'a.title',
				'a.target',
				'a.rel',
				'abbr.title',
				'acronym.title',
				'blockquote.cite',
				'div.align',
				'div.style',
				'div.class',
				'div.id',
				'font.size',
				'font.color',
				'h1.style',
				'h2.style',
				'h3.style',
				'h4.style',
				'h5.style',
				'h6.style',
				'img.src',
				'img.alt',
				'img.title',
				'img.class',
				'img.align',
				'img.style',
				'img.height',
				'img.width',
				'li.style',
				'ol.style',
				'p.style',
				'span.style',
				'span.class',
				'span.id',
				'table.class',
				'table.id',
				'table.border',
				'table.cellpadding',
				'table.cellspacing',
				'table.style',
				'table.width',
				'td.abbr',
				'td.align',
				'td.class',
				'td.id',
				'td.colspan',
				'td.rowspan',
				'td.style',
				'td.valign',
				'tr.align',
				'tr.class',
				'tr.id',
				'tr.style',
				'tr.valign',
				'th.abbr',
				'th.align',
				'th.class',
				'th.id',
				'th.colspan',
				'th.rowspan',
				'th.style',
				'th.valign',
				'ul.style'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_HTML_ALLOWATTRDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_ForbiddenElements',
			'conf_title' => '_MD_AM_PURIFIER_HTML_FORBIDELE',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_FORBIDELEDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_ForbiddenAttributes',
			'conf_title' => '_MD_AM_PURIFIER_HTML_FORBIDATTR',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_FORBIDATTRDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_MaxImgLength',
			'conf_title' => '_MD_AM_PURIFIER_HTML_MAXIMGLENGTH',
			'conf_value' => '1200',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_MAXIMGLENGTHDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_SafeEmbed',
			'conf_title' => '_MD_AM_PURIFIER_HTML_SAFEEMBED',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_SAFEEMBEDDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_SafeObject',
			'conf_title' => '_MD_AM_PURIFIER_HTML_SAFEOBJECT',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_SAFEOBJECTDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_HTML_AttrNameUseCDATA',
			'conf_title' => '_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATA',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATADSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Filter_ExtractStyleBlocks',
			'conf_title' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLK',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Filter_ExtractStyleBlocks_Escaping',
			'conf_title' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESC',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESCDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Filter_ExtractStyleBlocks_Scope',
			'conf_title' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPE',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPEDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Filter_YouTube',
			'conf_title' => '_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBE',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBEDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Core_EscapeNonASCIICharacters',
			'conf_title' => '_MD_AM_PURIFIER_CORE_ESCNONASCIICHARS',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CORE_ESCNONASCIICHARSDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Core_HiddenElements',
			'conf_title' => '_MD_AM_PURIFIER_CORE_HIDDENELE',
			'conf_value' => serialize([
				'script',
				'style'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_CORE_HIDDENELEDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Core_RemoveInvalidImg',
			'conf_title' => '_MD_AM_PURIFIER_CORE_REMINVIMG',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CORE_REMINVIMGDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_AutoParagraph',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_AUTOPARA',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_AUTOPARADSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_DisplayLinkURI',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_DISPLINKURI',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_DISPLINKURIDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_Linkify',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_LINKIFY',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_LINKIFYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_PurifierLinkify',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_PURILINKIFY',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_PURILINKIFYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_Custom',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_CUSTOM',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_CUSTOMDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_RemoveEmpty',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTY',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_RemoveEmptyNbsp',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSP',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_AutoFormat_RemoveEmptyNbspExceptions',
			'conf_title' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPT',
			'conf_value' => serialize([
				'td',
				'th'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPTDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_AllowedFrameTargets',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGET',
			'conf_value' => serialize([
				'_blank',
				'_parent',
				'_self',
				'_top'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGETDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_AllowedRel',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_ALLOWREL',
			'conf_value' => serialize([
				'external',
				'nofollow',
				'external nofollow',
				'lightbox'
			]),
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_ALLOWRELDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_AllowedClasses',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_ALLOWCLASSES',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_ALLOWCLASSESDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_ForbiddenClasses',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSES',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSESDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_DefaultInvalidImage',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_DEFINVIMG',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_DEFINVIMGDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_DefaultInvalidImageAlt',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_DEFINVIMGALT',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_DEFINVIMGALTDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_DefaultImageAlt',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_DEFIMGALT',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_DEFIMGALTDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_ClassUseCDATA',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_CLASSUSECDATA',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_CLASSUSECDATADSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_EnableID',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_ENABLEID',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_ENABLEIDDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_IDPrefix',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_IDPREFIX',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_IDPREFIXDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_IDPrefixLocal',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_IDPREFIXLOCAL',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_IDPREFIXLOCALDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_Attr_IDBlacklist',
			'conf_title' => '_MD_AM_PURIFIER_ATTR_IDBLACKLIST',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_ATTR_IDBLACKLISTDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_DefinitionRev',
			'conf_title' => '_MD_AM_PURIFIER_CSS_DEFREV',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_DEFREVDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_AllowImportant',
			'conf_title' => '_MD_AM_PURIFIER_CSS_ALLOWIMPORTANT',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_ALLOWIMPORTANTDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_AllowTricky',
			'conf_title' => '_MD_AM_PURIFIER_CSS_ALLOWTRICKY',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_ALLOWTRICKYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_AllowedProperties',
			'conf_title' => '_MD_AM_PURIFIER_CSS_ALLOWPROP',
			'conf_value' => '',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_ALLOWPROPDSC',
			'conf_formtype' => 'textsarea',
			'conf_valuetype' => 'array',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_MaxImgLength',
			'conf_title' => '_MD_AM_PURIFIER_CSS_MAXIMGLEN',
			'conf_value' => '1200px',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_MAXIMGLENDSC',
			'conf_formtype' => 'textbox',
			'conf_valuetype' => 'text',
			'conf_order' => $p++
		],
		[
			'conf_id' => ++$i,
			'conf_modid' => 0,
			'conf_catid' => $c,
			'conf_name' => 'purifier_CSS_Proprietary',
			'conf_title' => '_MD_AM_PURIFIER_CSS_PROPRIETARY',
			'conf_value' => '1',
			'conf_desc' => '_MD_AM_PURIFIER_CSS_PROPRIETARYDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => $p++
		],
	]);
	// <<<<< End of Purifier Category >>>>>

	$dbm->insert('system_autotasks', [
		[
			'sat_name' => 'Inactivating users',
			'sat_code' => 'autotask.php',
			'sat_repeat' => 0,
			'sat_interval' => 1440,
			'sat_onfinish' => 0,
			'sat_enabled' => 1,
			'sat_lastruntime' => time(),
			'sat_type' => 'addon/system',
			'sat_addon_id' => 0
		]
	]);


	// all these were converted from sql files

	$dbm->insert('block_positions', [
		[
			'id' => 1,
			'pname' => 'canvas_left',
			'title' => '_AM_SBLEFT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'L'
		],
		[
			'id' => 2,
			'pname' => 'canvas_right',
			'title' => '_AM_SBRIGHT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'L'
		],
		[
			'id' => 3,
			'pname' => 'page_topleft',
			'title' => '_AM_CBLEFT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 4,
			'pname' => 'page_topcenter',
			'title' => '_AM_CBCENTER',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 5,
			'pname' => 'page_topright',
			'title' => '_AM_CBRIGHT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 6,
			'pname' => 'page_bottomleft',
			'title' => '_AM_CBBOTTOMLEFT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 7,
			'pname' => 'page_bottomcenter',
			'title' => '_AM_CBBOTTOM',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 8,
			'pname' => 'page_bottomright',
			'title' => '_AM_CBBOTTOMRIGHT',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 9,
			'pname' => 'canvas_left_admin',
			'title' => '_AM_SBLEFT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'L'
		],
		[
			'id' => 10,
			'pname' => 'canvas_right_admin',
			'title' => '_AM_SBRIGHT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'L'
		],
		[
			'id' => 11,
			'pname' => 'page_topleft_admin',
			'title' => '_AM_CBLEFT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 12,
			'pname' => 'page_topcenter_admin',
			'title' => '_AM_CBCENTER_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 13,
			'pname' => 'page_topright_admin',
			'title' => '_AM_CBRIGHT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 14,
			'pname' => 'page_bottomleft_admin',
			'title' => '_AM_CBBOTTOMLEFT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 15,
			'pname' => 'page_bottomcenter_admin',
			'title' => '_AM_CBBOTTOM_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
		[
			'id' => 16,
			'pname' => 'page_bottomright_admin',
			'title' => '_AM_CBBOTTOMRIGHT_ADMIN',
			'description' => NULL,
			'block_default' => '1',
			'block_type' => 'C'
		],
	]);

	$dbm->insert('configcategory', [
		[
			'confcat_id' => 1,
			'confcat_name' => '_MD_AM_GENERAL',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 2,
			'confcat_name' => '_MD_AM_USERSETTINGS',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 3,
			'confcat_name' => '_MD_AM_METAFOOTER',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 4,
			'confcat_name' => '_MD_AM_CENSOR',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 5,
			'confcat_name' => '_MD_AM_SEARCH',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 6,
			'confcat_name' => '_MD_AM_MAILER',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 7,
			'confcat_name' => '_MD_AM_AUTHENTICATION',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 8,
			'confcat_name' => '_MD_AM_MULTILANGUAGE',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 10,
			'confcat_name' => '_MD_AM_PERSON',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 11,
			'confcat_name' => '_MD_AM_CAPTCHA',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 12,
			'confcat_name' => '_MD_AM_PLUGINS',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 13,
			'confcat_name' => '_MD_AM_AUTOTASKS',
			'confcat_order' => 0,
		],
		[
			'confcat_id' => 14,
			'confcat_name' => '_MD_AM_PURIFIER',
			'confcat_order' => 0,
		],
	]);

	$dbm->insert('imgset', [
		[
			'imgset_id' => 1,
			'imgset_name' => 'default',
			'imgset_refid' => 0
		]
	]);

	$dbm->insert('imgset_tplset_link', [
		[
			'imgset_id' => 1,
			'tplset_name' => 'default'
		]
	]);

	$dbm->insert('system_mimetype', [
		[
			'mimetypeid' => 1,
			'extension' => 'bin',
			'types' => 'application/octet-stream',
			'name' => 'Binary File/Linux Executable',
			'dirname' => '',
		],
		[
			'mimetypeid' => 2,
			'extension' => 'dms',
			'types' => 'application/octet-stream',
			'name' => 'Amiga DISKMASHER Compressed Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 3,
			'extension' => 'class',
			'types' => 'application/octet-stream',
			'name' => 'Java Bytecode',
			'dirname' => '',
		],
		[
			'mimetypeid' => 4,
			'extension' => 'so',
			'types' => 'application/octet-stream',
			'name' => 'UNIX Shared Library Function',
			'dirname' => '',
		],
		[
			'mimetypeid' => 5,
			'extension' => 'dll',
			'types' => 'application/octet-stream',
			'name' => 'Dynamic Link Library',
			'dirname' => '',
		],
		[
			'mimetypeid' => 6,
			'extension' => 'hqx',
			'types' => 'application/binhex application/mac-binhex application/mac-binhex40',
			'name' => 'Macintosh BinHex 4 Compressed Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 7,
			'extension' => 'cpt',
			'types' => 'application/mac-compactpro application/compact_pro',
			'name' => 'Compact Pro Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 8,
			'extension' => 'lha',
			'types' => 'application/lha application/x-lha application/octet-stream application/x-compress application/x-compressed application/maclha',
			'name' => 'Compressed Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 9,
			'extension' => 'lzh',
			'types' => 'application/lzh application/x-lzh application/x-lha application/x-compress application/x-compressed application/x-lzh-archive zz-application/zz-winassoc-lzh application/maclha application/octet-stream',
			'name' => 'Compressed Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 10,
			'extension' => 'sh',
			'types' => 'application/x-shar',
			'name' => 'UNIX shar Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 11,
			'extension' => 'shar',
			'types' => 'application/x-shar',
			'name' => 'UNIX shar Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 12,
			'extension' => 'tar',
			'types' => 'application/tar application/x-tar applicaton/x-gtar multipart/x-tar application/x-compress application/x-compressed',
			'name' => 'Tape Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 13,
			'extension' => 'gtar',
			'types' => 'application/x-gtar',
			'name' => 'GNU tar Compressed File Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 14,
			'extension' => 'ustar',
			'types' => 'application/x-ustar multipart/x-ustar',
			'name' => 'POSIX tar Compressed Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 15,
			'extension' => 'zip',
			'types' => 'application/zip application/x-zip application/x-zip-compressed application/octet-stream application/x-compress application/x-compressed multipart/x-zip',
			'name' => 'Compressed Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 16,
			'extension' => 'exe',
			'types' => 'application/exe application/x-exe application/dos-exe application/x-winexe application/msdos-windows application/x-msdos-program',
			'name' => 'Executable File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 17,
			'extension' => 'wmz',
			'types' => 'application/x-ms-wmz',
			'name' => 'Windows Media Compressed Skin File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 18,
			'extension' => 'wmd',
			'types' => 'application/x-ms-wmd',
			'name' => 'Windows Media Download File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 19,
			'extension' => 'doc',
			'types' => 'application/msword application/doc appl/text application/vnd.msword application/vnd.ms-word application/winword application/word application/x-msw6 application/x-msword',
			'name' => 'Word Document',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 20,
			'extension' => 'pdf',
			'types' => 'application/pdf application/acrobat application/x-pdf applications/vnd.pdf text/pdf',
			'name' => 'Acrobat Portable Document Format',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 21,
			'extension' => 'eps',
			'types' => 'application/eps application/postscript application/x-eps image/eps image/x-eps',
			'name' => 'Encapsulated PostScript',
			'dirname' => '',
		],
		[
			'mimetypeid' => 22,
			'extension' => 'ps',
			'types' => 'application/postscript application/ps application/x-postscript application/x-ps text/postscript',
			'name' => 'PostScript',
			'dirname' => '',
		],
		[
			'mimetypeid' => 23,
			'extension' => 'smi',
			'types' => 'application/smil',
			'name' => 'SMIL Multimedia',
			'dirname' => '',
		],
		[
			'mimetypeid' => 24,
			'extension' => 'smil',
			'types' => 'application/smil',
			'name' => 'Synchronized Multimedia Integration Language',
			'dirname' => '',
		],
		[
			'mimetypeid' => 25,
			'extension' => 'wmlc',
			'types' => 'application/vnd.wap.wmlc ',
			'name' => 'Compiled WML Document',
			'dirname' => '',
		],
		[
			'mimetypeid' => 26,
			'extension' => 'wmlsc',
			'types' => 'application/vnd.wap.wmlscriptc',
			'name' => 'Compiled WML Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 27,
			'extension' => 'vcd',
			'types' => 'application/x-cdlink',
			'name' => 'Virtual CD-ROM CD Image File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 28,
			'extension' => 'pgn',
			'types' => 'application/formstore',
			'name' => 'Picatinny Arsenal Electronic Formstore Form in TIFF Format',
			'dirname' => '',
		],
		[
			'mimetypeid' => 29,
			'extension' => 'cpio',
			'types' => 'application/x-cpio',
			'name' => 'UNIX CPIO Archive',
			'dirname' => '',
		],
		[
			'mimetypeid' => 30,
			'extension' => 'csh',
			'types' => 'application/x-csh',
			'name' => 'Csh Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 31,
			'extension' => 'dcr',
			'types' => 'application/x-director',
			'name' => 'Shockwave Movie',
			'dirname' => '',
		],
		[
			'mimetypeid' => 32,
			'extension' => 'dir',
			'types' => 'application/x-director',
			'name' => 'Macromedia Director Movie',
			'dirname' => '',
		],
		[
			'mimetypeid' => 33,
			'extension' => 'dxr',
			'types' => 'application/x-director application/vnd.dxr',
			'name' => 'Macromedia Director Protected Movie File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 34,
			'extension' => 'dvi',
			'types' => 'application/x-dvi',
			'name' => 'TeX Device Independent Document',
			'dirname' => '',
		],
		[
			'mimetypeid' => 35,
			'extension' => 'spl',
			'types' => 'application/x-futuresplash',
			'name' => 'Macromedia FutureSplash File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 36,
			'extension' => 'hdf',
			'types' => 'application/x-hdf',
			'name' => 'Hierarchical Data Format File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 37,
			'extension' => 'js',
			'types' => 'application/x-javascript text/javascript',
			'name' => 'JavaScript Source Code',
			'dirname' => '',
		],
		[
			'mimetypeid' => 38,
			'extension' => 'skp',
			'types' => 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan',
			'name' => 'SSEYO Koan Play File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 39,
			'extension' => 'skd',
			'types' => 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan',
			'name' => 'SSEYO Koan Design File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 40,
			'extension' => 'skt',
			'types' => 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan',
			'name' => 'SSEYO Koan Template File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 41,
			'extension' => 'skm',
			'types' => 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan',
			'name' => 'SSEYO Koan Mix File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 42,
			'extension' => 'latex',
			'types' => 'application/x-latex text/x-latex',
			'name' => 'LaTeX Source Document',
			'dirname' => '',
		],
		[
			'mimetypeid' => 43,
			'extension' => 'nc',
			'types' => 'application/x-netcdf text/x-cdf',
			'name' => 'Unidata netCDF Graphics',
			'dirname' => '',
		],
		[
			'mimetypeid' => 44,
			'extension' => 'cdf',
			'types' => 'application/cdf application/x-cdf application/netcdf application/x-netcdf text/cdf text/x-cdf',
			'name' => 'Channel Definition Format',
			'dirname' => '',
		],
		[
			'mimetypeid' => 45,
			'extension' => 'swf',
			'types' => 'application/x-shockwave-flash application/x-shockwave-flash2-preview application/futuresplash image/vnd.rn-realflash',
			'name' => 'Macromedia Flash Format File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 46,
			'extension' => 'sit',
			'types' => 'application/stuffit application/x-stuffit application/x-sit',
			'name' => 'StuffIt Compressed Archive File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 47,
			'extension' => 'tcl',
			'types' => 'application/x-tcl',
			'name' => 'TCL/TK Language Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 48,
			'extension' => 'tex',
			'types' => 'application/x-tex',
			'name' => 'LaTeX Source',
			'dirname' => '',
		],
		[
			'mimetypeid' => 49,
			'extension' => 'texinfo',
			'types' => 'application/x-texinfo',
			'name' => 'TeX',
			'dirname' => '',
		],
		[
			'mimetypeid' => 50,
			'extension' => 'texi',
			'types' => 'application/x-texinfo',
			'name' => 'TeX',
			'dirname' => '',
		],
		[
			'mimetypeid' => 51,
			'extension' => 't',
			'types' => 'application/x-troff',
			'name' => 'TAR Tape Archive Without Compression',
			'dirname' => '',
		],
		[
			'mimetypeid' => 52,
			'extension' => 'tr',
			'types' => 'application/x-troff',
			'name' => 'Unix Tape Archive = TAR without compression (tar)',
			'dirname' => '',
		],
		[
			'mimetypeid' => 53,
			'extension' => 'src',
			'types' => 'application/x-wais-source',
			'name' => 'Sourcecode',
			'dirname' => '',
		],
		[
			'mimetypeid' => 54,
			'extension' => 'xhtml',
			'types' => 'application/xhtml+xml',
			'name' => 'Extensible HyperText Markup Language File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 55,
			'extension' => 'xht',
			'types' => 'application/xhtml+xml',
			'name' => 'Extensible HyperText Markup Language File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 56,
			'extension' => 'au',
			'types' => 'audio/basic audio/x-basic audio/au audio/x-au audio/x-pn-au audio/rmf audio/x-rmf audio/x-ulaw audio/vnd.qcelp audio/x-gsm audio/snd',
			'name' => 'ULaw/AU Audio File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 57,
			'extension' => 'XM',
			'types' => 'audio/xm audio/x-xm audio/module-xm audio/mod audio/x-mod',
			'name' => 'Fast Tracker 2 Extended Module',
			'dirname' => '',
		],
		[
			'mimetypeid' => 58,
			'extension' => 'snd',
			'types' => 'audio/basic',
			'name' => 'Macintosh Sound Resource',
			'dirname' => '',
		],
		[
			'mimetypeid' => 59,
			'extension' => 'mid',
			'types' => 'audio/mid audio/m audio/midi audio/x-midi application/x-midi audio/soundtrack',
			'name' => 'Musical Instrument Digital Interface MIDI-sequention Sound',
			'dirname' => '',
		],
		[
			'mimetypeid' => 60,
			'extension' => 'midi',
			'types' => 'audio/mid audio/m audio/midi audio/x-midi application/x-midi',
			'name' => 'Musical Instrument Digital Interface MIDI-sequention Sound',
			'dirname' => '',
		],
		[
			'mimetypeid' => 61,
			'extension' => 'kar',
			'types' => 'audio/midi audio/x-midi audio/mid x-music/x-midi',
			'name' => 'Karaoke MIDI File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 62,
			'extension' => 'mpga',
			'types' => 'audio/mpeg audio/mp3 audio/mgp audio/m-mpeg audio/x-mp3 audio/x-mpeg audio/x-mpg video/mpeg',
			'name' => 'Mpeg-1 Layer3 Audio Stream',
			'dirname' => '',
		],
		[
			'mimetypeid' => 63,
			'extension' => 'mp2',
			'types' => 'video/mpeg audio/mpeg',
			'name' => 'MPEG Audio Stream, Layer II',
			'dirname' => '',
		],
		[
			'mimetypeid' => 64,
			'extension' => 'mp3',
			'types' => 'audio/mpeg audio/x-mpeg audio/mp3 audio/x-mp3 audio/mpeg3 audio/x-mpeg3 audio/mpg audio/x-mpg audio/x-mpegaudio',
			'name' => 'MPEG Audio Stream, Layer III',
			'dirname' => '',
		],
		[
			'mimetypeid' => 65,
			'extension' => 'aif',
			'types' => 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/vnd.qcelp',
			'name' => 'Audio Interchange File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 66,
			'extension' => 'aiff',
			'types' => 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/mid audio/x-midi audio/vnd.qcelp',
			'name' => 'Audio Interchange File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 67,
			'extension' => 'aifc',
			'types' => 'audio/aiff audio/x-aiff audio/x-aifc sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/mid audio/vnd.qcelp',
			'name' => 'Audio Interchange File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 68,
			'extension' => 'm3u',
			'types' => 'audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls',
			'name' => 'MP3 Playlist File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 69,
			'extension' => 'ram',
			'types' => 'audio/x-pn-realaudio audio/vnd.rn-realaudio audio/x-pm-realaudio-plugin audio/x-pn-realvideo audio/x-realaudio video/x-pn-realvideo text/plain',
			'name' => 'RealMedia Metafile',
			'dirname' => '',
		],
		[
			'mimetypeid' => 70,
			'extension' => 'rm',
			'types' => 'application/vnd.rn-realmedia audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin',
			'name' => 'RealMedia Streaming Media',
			'dirname' => '',
		],
		[
			'mimetypeid' => 71,
			'extension' => 'rpm',
			'types' => 'audio/x-pn-realaudio audio/x-pn-realaudio-plugin audio/x-pnrealaudio-plugin video/x-pn-realvideo-plugin audio/x-mpegurl application/octet-stream',
			'name' => 'RealMedia Player Plug-in',
			'dirname' => '',
		],
		[
			'mimetypeid' => 72,
			'extension' => 'ra',
			'types' => 'audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin video/x-pn-realvideo',
			'name' => 'RealMedia Streaming Media',
			'dirname' => '',
		],
		[
			'mimetypeid' => 73,
			'extension' => 'wav',
			'types' => 'audio/wav audio/x-wav audio/wave audio/x-pn-wav',
			'name' => 'Waveform Audio',
			'dirname' => '',
		],
		[
			'mimetypeid' => 74,
			'extension' => 'wax',
			'types' => ' audio/x-ms-wax',
			'name' => 'Windows Media Audio Redirector',
			'dirname' => '',
		],
		[
			'mimetypeid' => 75,
			'extension' => 'wma',
			'types' => 'audio/x-ms-wma video/x-ms-asf',
			'name' => 'Windows Media Audio File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 76,
			'extension' => 'bmp',
			'types' => 'image/bmp image/x-bmp image/x-bitmap image/x-xbitmap image/x-win-bitmap image/x-windows-bmp image/ms-bmp image/x-ms-bmp application/bmp application/x-bmp application/x-win-bitmap application/preview',
			'name' => 'Windows OS/2 Bitmap Graphics',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 77,
			'extension' => 'gif',
			'types' => 'image/gif image/x-xbitmap image/gi_',
			'name' => 'Graphic Interchange Format',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 78,
			'extension' => 'ief',
			'types' => 'image/ief',
			'name' => 'Image File - Bitmap graphics',
			'dirname' => '',
		],
		[
			'mimetypeid' => 79,
			'extension' => 'jpeg',
			'types' => 'image/jpeg image/jpg image/jpe_ image/pjpeg image/vnd.swiftview-jpeg',
			'name' => 'JPEG/JIFF Image',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 80,
			'extension' => 'jpg',
			'types' => 'image/jpeg image/jpg image/jp_ application/jpg application/x-jpg image/pjpeg image/pipeg image/vnd.swiftview-jpeg image/x-xbitmap',
			'name' => 'JPEG/JIFF Image',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 81,
			'extension' => 'jpe',
			'types' => 'image/jpeg',
			'name' => 'JPEG/JIFF Image',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 82,
			'extension' => 'png',
			'types' => 'image/png application/png application/x-png',
			'name' => 'Portable (Public) Network Graphic',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 83,
			'extension' => 'tiff',
			'types' => 'image/tiff',
			'name' => 'Tagged Image Format File',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 84,
			'extension' => 'tif',
			'types' => 'image/tif image/x-tif image/tiff image/x-tiff application/tif application/x-tif application/tiff application/x-tiff',
			'name' => 'Tagged Image Format File',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 85,
			'extension' => 'ico',
			'types' => 'image/ico image/x-icon application/ico application/x-ico application/x-win-bitmap image/x-win-bitmap application/octet-stream',
			'name' => 'Windows Icon',
			'dirname' => '',
		],
		[
			'mimetypeid' => 86,
			'extension' => 'wbmp',
			'types' => 'image/vnd.wap.wbmp',
			'name' => 'Wireless Bitmap File Format',
			'dirname' => '',
		],
		[
			'mimetypeid' => 87,
			'extension' => 'ras',
			'types' => 'application/ras application/x-ras image/ras',
			'name' => 'Sun Raster Graphic',
			'dirname' => '',
		],
		[
			'mimetypeid' => 88,
			'extension' => 'pnm',
			'types' => 'image/x-portable-anymap',
			'name' => 'PBM Portable Any Map Graphic Bitmap',
			'dirname' => '',
		],
		[
			'mimetypeid' => 89,
			'extension' => 'pbm',
			'types' => 'image/portable bitmap image/x-portable-bitmap image/pbm image/x-pbm',
			'name' => 'UNIX Portable Bitmap Graphic',
			'dirname' => '',
		],
		[
			'mimetypeid' => 90,
			'extension' => 'pgm',
			'types' => 'image/x-portable-graymap image/x-pgm',
			'name' => 'Portable Graymap Graphic',
			'dirname' => '',
		],
		[
			'mimetypeid' => 91,
			'extension' => 'ppm',
			'types' => 'image/x-portable-pixmap application/ppm application/x-ppm image/x-p image/x-ppm',
			'name' => 'PBM Portable Pixelmap Graphic',
			'dirname' => '',
		],
		[
			'mimetypeid' => 92,
			'extension' => 'rgb',
			'types' => 'image/rgb image/x-rgb',
			'name' => 'Silicon Graphics RGB Bitmap',
			'dirname' => '',
		],
		[
			'mimetypeid' => 93,
			'extension' => 'xbm',
			'types' => 'image/x-xpixmap image/x-xbitmap image/xpm image/x-xpm',
			'name' => 'X Bitmap Graphic',
			'dirname' => '',
		],
		[
			'mimetypeid' => 94,
			'extension' => 'xpm',
			'types' => 'image/x-xpixmap',
			'name' => 'BMC Software Patrol UNIX Icon File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 95,
			'extension' => 'xwd',
			'types' => 'image/x-xwindowdump image/xwd image/x-xwd application/xwd application/x-xwd',
			'name' => 'X Windows Dump',
			'dirname' => '',
		],
		[
			'mimetypeid' => 96,
			'extension' => 'igs',
			'types' => 'model/iges application/iges application/x-iges application/igs application/x-igs drawing/x-igs image/x-igs',
			'name' => 'Initial Graphics Exchange Specification Format',
			'dirname' => '',
		],
		[
			'mimetypeid' => 97,
			'extension' => 'css',
			'types' => 'application/css-stylesheet text/css',
			'name' => 'Hypertext Cascading Style Sheet',
			'dirname' => '',
		],
		[
			'mimetypeid' => 98,
			'extension' => 'html',
			'types' => 'text/html text/plain',
			'name' => 'Hypertext Markup Language',
			'dirname' => '',
		],
		[
			'mimetypeid' => 99,
			'extension' => 'htm',
			'types' => 'text/html',
			'name' => 'Hypertext Markup Language',
			'dirname' => '',
		],
		[
			'mimetypeid' => 100,
			'extension' => 'txt',
			'types' => 'text/plain application/txt browser/internal',
			'name' => 'Text File',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 101,
			'extension' => 'rtf',
			'types' => 'application/rtf application/x-rtf text/rtf text/richtext application/msword application/doc application/x-soffice',
			'name' => 'Rich Text Format File',
			'dirname' => 'system',
		],
		[
			'mimetypeid' => 102,
			'extension' => 'wml',
			'types' => 'text/vnd.wap.wml text/wml',
			'name' => 'Website META Language File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 103,
			'extension' => 'wmls',
			'types' => 'text/vnd.wap.wmlscript',
			'name' => 'WML Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 104,
			'extension' => 'etx',
			'types' => 'text/x-setext',
			'name' => 'SetText Structure Enhanced Text',
			'dirname' => '',
		],
		[
			'mimetypeid' => 105,
			'extension' => 'xml',
			'types' => 'text/xml application/xml application/x-xml',
			'name' => 'Extensible Markup Language File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 106,
			'extension' => 'xsl',
			'types' => 'text/xml',
			'name' => 'XML Stylesheet',
			'dirname' => '',
		],
		[
			'mimetypeid' => 107,
			'extension' => 'php',
			'types' => 'text/php application/x-httpd-php application/php magnus-internal/shellcgi application/x-php',
			'name' => 'PHP Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 108,
			'extension' => 'php3',
			'types' => 'text/php3 application/x-httpd-php',
			'name' => 'PHP Script',
			'dirname' => '',
		],
		[
			'mimetypeid' => 109,
			'extension' => 'mpeg',
			'types' => 'video/mpeg',
			'name' => 'MPEG Movie',
			'dirname' => '',
		],
		[
			'mimetypeid' => 110,
			'extension' => 'mpg',
			'types' => 'video/mpeg video/mpg video/x-mpg video/mpeg2 application/x-pn-mpg video/x-mpeg video/x-mpeg2a audio/mpeg audio/x-mpeg image/mpg',
			'name' => 'MPEG 1 System Stream',
			'dirname' => '',
		],
		[
			'mimetypeid' => 111,
			'extension' => 'mpe',
			'types' => 'video/mpeg',
			'name' => 'MPEG Movie Clip',
			'dirname' => '',
		],
		[
			'mimetypeid' => 112,
			'extension' => 'qt',
			'types' => 'video/quicktime audio/aiff audio/x-wav video/flc',
			'name' => 'QuickTime Movie',
			'dirname' => '',
		],
		[
			'mimetypeid' => 113,
			'extension' => 'mov',
			'types' => 'video/quicktime video/x-quicktime image/mov audio/aiff audio/x-midi audio/x-wav video/avi',
			'name' => 'QuickTime Video Clip',
			'dirname' => '',
		],
		[
			'mimetypeid' => 114,
			'extension' => 'avi',
			'types' => 'video/avi video/msvideo video/x-msvideo image/avi video/xmpg2 application/x-troff-msvideo audio/aiff audio/avi',
			'name' => 'Audio Video Interleave File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 115,
			'extension' => 'movie',
			'types' => 'video/sgi-movie video/x-sgi-movie',
			'name' => 'QuickTime Movie',
			'dirname' => '',
		],
		[
			'mimetypeid' => 116,
			'extension' => 'asf',
			'types' => 'audio/asf application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx',
			'name' => 'Advanced Streaming Format',
			'dirname' => '',
		],
		[
			'mimetypeid' => 117,
			'extension' => 'asx',
			'types' => 'video/asx application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx video/x-la-asf',
			'name' => 'Advanced Stream Redirector File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 118,
			'extension' => 'wmv',
			'types' => 'video/x-ms-wmv',
			'name' => 'Windows Media File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 119,
			'extension' => 'wvx',
			'types' => 'video/x-ms-wvx',
			'name' => 'Windows Media Redirector',
			'dirname' => '',
		],
		[
			'mimetypeid' => 120,
			'extension' => 'wm',
			'types' => 'video/x-ms-wm',
			'name' => 'Windows Media A/V File',
			'dirname' => '',
		],
		[
			'mimetypeid' => 121,
			'extension' => 'wmx',
			'types' => 'video/x-ms-wmx',
			'name' => 'Windows Media Player A/V Shortcut',
			'dirname' => '',
		],
		[
			'mimetypeid' => 122,
			'extension' => 'ice',
			'types' => 'x-conference-xcooltalk',
			'name' => 'Cooltalk Audio',
			'dirname' => '',
		],
		[
			'mimetypeid' => 123,
			'extension' => 'rar',
			'types' => 'application/octet-stream',
			'name' => 'WinRAR Compressed Archive',
			'dirname' => '',
		],
	]);

	$dbm->insert('group_permission', [
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 20,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 20,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 19,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 19,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 76,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 76,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 77,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 77,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 82,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 82,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 79,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 79,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 80,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 80,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 81,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 81,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 83,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 83,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 84,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 84,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 100,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 100,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 2,
			'gperm_itemid' => 101,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
		[
			'gperm_groupid' => 1,
			'gperm_itemid' => 101,
			'gperm_modid' => 1,
			'gperm_name' => 'use_extension',
		],
	]);

	return $gruops;
}
