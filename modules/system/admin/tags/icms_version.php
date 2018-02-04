<?php
/**
 * ImpressCMS Tags
 * Tags can be used for free-tagging content - they do not have a hierarchy
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	Tags
 * @since		2.0
 * @version		$Id: icms_version.php 11547 2012-01-29 21:43:11Z skenow $
 */

$modversion = array(
	'name' => _CO_SYSTEM_TAGS,
	'version' => "1.0",
	'description' => _CO_SYSTEM_TAGS_DSC,
	'author' => "Steve Kenow <skenow@impresscms.org",
	'credits' => "The ImpressCMS Projects",
	'help' => "",
	'license' => "GPL see LICENSE",
	'official' => 1,
	'image' => "tags.png",
	'hasAdmin' => 1,
	'adminpath' => "admin.php?fct=tags",
	'category' => _SYSTEM_TAGS,
	'group' => _MD_AM_GROUPS_CONTENT);