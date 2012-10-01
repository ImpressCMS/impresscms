<?php
/**
 * Administration of comments, versionfile
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Comments
 * @version		SVN: $Id: icms_version.php 11610 2012-02-28 03:53:55Z skenow $
 */

$modversion = array(
	'name' => _MD_AM_COMMENTS,
	'version' => "",
	'description' => _MD_AM_COMMENTS_DSC,
	'author' => "",
	'credits' => "The ImpressCMS Project",
	'help' => "comments.html",
	'license' => "GPL see LICENSE",
	'official' => 1,
	'image' => "comments.gif",
	'hasAdmin' => 1,
	'adminpath' => "admin.php?fct=comments",
	'category' => XOOPS_SYSTEM_COMMENT,
	'group' => _MD_AM_GROUPS_CONTENT);