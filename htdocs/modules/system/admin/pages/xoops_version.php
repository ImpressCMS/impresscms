<?php
/**
* Administration of content pages, versionfile
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		Administration
* @since		1.1
* @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version		$Id$
*/

$modversion = array( 'name' => _MD_AM_PAGES,
	'version' => "1",
	'description' => "ImpressCMS Content Manager",
	'author' => "Rodrigo Pereira Lima aka TheRplima",
	'credits' => "The ImpressCMS Project",
	'help' => "pages.html",
	'license' => "GPL see LICENSE",
	'official' => 1,
	'image' => "pages.gif",
	'hasAdmin' => 1,
	'adminpath' => "admin.php?fct=pages",
	'category' => XOOPS_SYSTEM_PAGES,
	'group' => 'Content');
