<?php
/**
 * ImpressCMS Backup Manager
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.4
 * @author		ImpressCMS Development Team
 * @version		$Id$
 */

$modversion = array(
	'name' => _MD_AM_BACKUP,
	'version' => "1.0",
	'description' => _MD_AM_BACKUP_DSC,
	'author' => "ImpressCMS Development Team",
	'credits' => "The ImpressCMS Projects",
	'help' => "",
	'license' => "GPL see LICENSE",
	'official' => 1,
	'image' => "backup.gif",
	'hasAdmin' => 1,
	'adminpath' => "admin.php?fct=backup",
	'category' => XOOPS_SYSTEM_BACKUP,
	'group' => _MD_AM_GROUPS_SYSTEMTOOLS
);
