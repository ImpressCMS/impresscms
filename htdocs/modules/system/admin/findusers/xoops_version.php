<?php
/**
 * Administration of finding users, versionfile
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	Administration
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

$modversion = array( 'name' => _MD_AM_FINDUSER,
	'version' => "",
	'description' => "Find Users",
	'author' => "Kazumi Ono<br />( http://www.myweb.ne.jp/ )",
	'credits' => "The XOOPS Project",
	'help' => "findusers.html",
	'license' => "GPL see LICENSE",
	'official' => 1,
	'image' => "users.gif",

	'hasAdmin' => 1,
	'adminpath' => "admin.php?fct=findusers",
	'category' => XOOPS_SYSTEM_FINDU,
	'group' => 'Users and Groups');
