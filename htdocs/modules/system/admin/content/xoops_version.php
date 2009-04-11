<?php
/**
* Content Manager
*
* System tool that allow create and manage content pages
* Some parts of this tool was based on mastop publish and smartcontent modules
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		Administration
* @since		1.1
* @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version		$Id$
*/

$modversion['name'] = _MD_AM_CONTENT;
$modversion['version'] = "";
$modversion['description'] = "ImpressCMS Content Manager";
$modversion['author'] = "";
$modversion['credits'] = "The ImpressCMS Project";
$modversion['help'] = "content.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "content.gif";

$modversion['hasAdmin'] = 1;
$modversion['adminpath'] = "admin.php?fct=content";
$modversion['category'] = XOOPS_SYSTEM_CONTENT;

?>