<?php

/**
 * @var db_manager $dbm
 */

$dbm->insert('ranks', [
	[
		'rank_id' => 1,
		'rank_title' => 'Just popping in',
		'rank_min' => 0,
		'rank_max' => 20,
		'rank_special' => 0,
		'rank_image' => 'rank3e632f95e81ca.gif'
	],
	[
		'rank_id' => 2,
		'rank_title' => 'Not too shy to talk',
		'rank_min' => 21,
		'rank_max' => 40,
		'rank_special' => 0,
		'rank_image' => 'rank3dbf8e94a6f72.gif'
	],
	[
		'rank_id' => 3,
		'rank_title' => 'Quite a regular',
		'rank_min' => 41,
		'rank_max' => 70,
		'rank_special' => 0,
		'rank_image' => 'rank3dbf8e9e7d88d.gif'
	],
	[
		'rank_id' => 4,
		'rank_title' => 'Just can not stay away',
		'rank_min' => 71,
		'rank_max' => 150,
		'rank_special' => 0,
		'rank_image' => 'rank3dbf8ea81e642.gif'
	],
	[
		'rank_id' => 5,
		'rank_title' => 'Home away from home',
		'rank_min' => 151,
		'rank_max' => 10000,
		'rank_special' => 0,
		'rank_image' => 'rank3dbf8eb1a72e7.gif'
	],
	[
		'rank_id' => 6,
		'rank_title' => 'Moderator',
		'rank_min' => 0,
		'rank_max' => 0,
		'rank_special' => 1,
		'rank_image' => 'rank3dbf8edf15093.gif'
	],
	[
		'rank_id' => 7,
		'rank_title' => 'Webmaster',
		'rank_min' => 0,
		'rank_max' => 0,
		'rank_special' => 1,
		'rank_image' => 'rank3dbf8ee8681cd.gif'
	]
]);

$dbm->insert('smiles', [
	[
		'id' => 1,
		'code' => ':-D',
		'smile_url' => 'smil3dbd4d4e4c4f2.gif',
		'emotion' => 'Very Happy',
		'display' => 1
	],
	[
		'id' => 2,
		'code' => ':-)',
		'smile_url' => 'smil3dbd4d6422f04.gif',
		'emotion' => 'Smile',
		'display' => 1
	],
	[
		'id' => 3,
		'code' => ':-(',
		'smile_url' => 'smil3dbd4d75edb5e.gif',
		'emotion' => 'Sad',
		'display' => 1
	],
	[
		'id' => 4,
		'code' => ':-o',
		'smile_url' => 'smil3dbd4d8676346.gif',
		'emotion' => 'Surprised',
		'display' => 1
	],
	[
		'id' => 5,
		'code' => ':-?',
		'smile_url' => 'smil3dbd4d99c6eaa.gif',
		'emotion' => 'Confused',
		'display' => 1
	],
	[
		'id' => 6,
		'code' => '8-)',
		'smile_url' => 'smil3dbd4daabd491.gif',
		'emotion' => 'Cool',
		'display' => 1
	],
	[
		'id' => 7,
		'code' => ':lol:',
		'smile_url' => 'smil3dbd4dbc14f3f.gif',
		'emotion' => 'Laughing',
		'display' => 1
	],
	[
		'id' => 8,
		'code' => ':-x',
		'smile_url' => 'smil3dbd4dcd7b9f4.gif',
		'emotion' => 'Mad',
		'display' => 1
	],
	[
		'id' => 9,
		'code' => ':-P',
		'smile_url' => 'smil3dbd4ddd6835f.gif',
		'emotion' => 'Razz',
		'display' => 1
	],
	[
		'id' => 10,
		'code' => ':oops:',
		'smile_url' => 'smil3dbd4df1944ee.gif',
		'emotion' => 'Embarrassed',
		'display' => 0
	],
	[
		'id' => 11,
		'code' => ':cry:',
		'smile_url' => 'smil3dbd4e02c5440.gif',
		'emotion' => 'Crying (very sad)',
		'display' => 0
	],
	[
		'id' => 12,
		'code' => ':evil:',
		'smile_url' => 'smil3dbd4e1748cc9.gif',
		'emotion' => 'Evil or Very Mad',
		'display' => 0
	],
	[
		'id' => 13,
		'code' => ':roll:',
		'smile_url' => 'smil3dbd4e29bbcc7.gif',
		'emotion' => 'Rolling Eyes',
		'display' => 0
	],
	[
		'id' => 14,
		'code' => ';-)',
		'smile_url' => 'smil3dbd4e398ff7b.gif',
		'emotion' => 'Wink',
		'display' => 0
	],
	[
		'id' => 15,
		'code' => ':pint:',
		'smile_url' => 'smil3dbd4e4c2e742.gif',
		'emotion' => 'Another pint of beer',
		'display' => 0
	],
	[
		'id' => 16,
		'code' => ':hammer:',
		'smile_url' => 'smil3dbd4e5e7563a.gif',
		'emotion' => 'ToolTimes at work',
		'display' => 0
	],
	[
		'id' => 17,
		'code' => ':idea:',
		'smile_url' => 'smil3dbd4e7853679.gif',
		'emotion' => 'I have an idea',
		'display' => 0
	],
]);

$dbm->insert('icmspage', [
	[
		'page_id' => 2,
		'page_moduleid' => 1,
		'page_title' => 'Admin Control Panel',
		'page_url' => 'admin.php',
		'page_status' => 1
	],
	[
		'page_id' => 3,
		'page_moduleid' => 1,
		'page_title' => 'Avatars',
		'page_url' => 'modules/system/admin.php?fct=avatars*',
		'page_status' => 1
	],
	[
		'page_id' => 4,
		'page_moduleid' => 1,
		'page_title' => 'Banners',
		'page_url' => 'modules/system/admin.php?fct=banners*',
		'page_status' => 1
	],
	[
		'page_id' => 5,
		'page_moduleid' => 1,
		'page_title' => 'Blocks Admin',
		'page_url' => 'modules/system/admin.php?fct=blocksadmin*',
		'page_status' => 1
	],
	[
		'page_id' => 6,
		'page_moduleid' => 1,
		'page_title' => 'Block Positions',
		'page_url' => 'modules/system/admin.php?fct=blockspadmin*',
		'page_status' => 1
	],
	[
		'page_id' => 7,
		'page_moduleid' => 1,
		'page_title' => 'Comments',
		'page_url' => 'modules/system/admin.php?fct=comments*',
		'page_status' => 1
	],
	[
		'page_id' => 9,
		'page_moduleid' => 1,
		'page_title' => 'Find Users',
		'page_url' => 'modules/system/admin.php?fct=findusers*',
		'page_status' => 1
	],
	[
		'page_id' => 10,
		'page_moduleid' => 1,
		'page_title' => 'Custom Tag',
		'page_url' => 'modules/system/admin.php?fct=customtag*',
		'page_status' => 1
	],
	[
		'page_id' => 11,
		'page_moduleid' => 1,
		'page_title' => 'Groups',
		'page_url' => 'modules/system/admin.php?fct=groups*',
		'page_status' => 1
	],
	[
		'page_id' => 12,
		'page_moduleid' => 1,
		'page_title' => 'Image Manager',
		'page_url' => 'modules/system/admin.php?fct=images*',
		'page_status' => 1
	],
	[
		'page_id' => 13,
		'page_moduleid' => 1,
		'page_title' => 'Mail Users',
		'page_url' => 'modules/system/admin.php?fct=mailusers*',
		'page_status' => 1
	],
	[
		'page_id' => 14,
		'page_moduleid' => 1,
		'page_title' => 'Modules Admin',
		'page_url' => 'modules/system/admin.php?fct=modulesadmin*',
		'page_status' => 1
	],
	[
		'page_id' => 15,
		'page_moduleid' => 1,
		'page_title' => 'Symlink Manager',
		'page_url' => 'modules/system/admin.php?fct=pages*',
		'page_status' => 1
	],
	[
		'page_id' => 16,
		'page_moduleid' => 1,
		'page_title' => 'Preferences',
		'page_url' => 'modules/system/admin.php?fct=preferences*',
		'page_status' => 1
	],
	[
		'page_id' => 17,
		'page_moduleid' => 1,
		'page_title' => 'Smilies',
		'page_url' => 'modules/system/admin.php?fct=smilies*',
		'page_status' => 1
	],
	[
		'page_id' => 18,
		'page_moduleid' => 1,
		'page_title' => 'Templates',
		'page_url' => 'modules/system/admin.php?fct=tplsets*',
		'page_status' => 1
	],
	[
		'page_id' => 19,
		'page_moduleid' => 1,
		'page_title' => 'User Ranks',
		'page_url' => 'modules/system/admin.php?fct=userrank*',
		'page_status' => 1
	],
	[
		'page_id' => 20,
		'page_moduleid' => 1,
		'page_title' => 'User Edit',
		'page_url' => 'modules/system/admin.php?fct=users*',
		'page_status' => 1
	],
	[
		'page_id' => 21,
		'page_moduleid' => 1,
		'page_title' => 'Version Checker',
		'page_url' => 'modules/system/admin.php?fct=version*',
		'page_status' => 1
	],
]);