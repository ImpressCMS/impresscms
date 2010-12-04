<?php
/**
* Shows all tree structures within ImpressCMS (including breadcrumbs)
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

/**
 * A tree structures with {@link XoopsObject}s as nodes
 *
 * @package		kernel
 * @subpackage	core
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsObjectTree {
	
	private $_deprecated;
	
	public function __construct(& $objectArr, $myId, $parentId, $rootId = null) {
		$tree =  new icms_ipf_Tree($objectArr, $myId, $parentId, $rootId);
		$tree->_deprecated = icms_core_Debug::setDeprecated();
		return $tree;
	}
}
?>