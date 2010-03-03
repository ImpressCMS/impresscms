<?php
// $Id$
/**
* Administration of comments, versionfile
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

$modversion['name'] = _MD_AM_COMMENTS;
$modversion['version'] = "";
$modversion['description'] = "ImpressCMS Site Comment Manager";
$modversion['author'] = "";
$modversion['credits'] = "The ImpressCMS Project";
$modversion['help'] = "comments.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "comments.gif";

$modversion['hasAdmin'] = 1;
$modversion['adminpath'] = "admin.php?fct=comments";
$modversion['category'] = XOOPS_SYSTEM_COMMENT;
?>