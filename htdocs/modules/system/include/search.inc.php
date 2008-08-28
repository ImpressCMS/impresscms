<?php
/**
* Function to allow search on content pages of the content manager and in Links on Symlinks Mnager
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		real_therplima <therplima@impresscms.org)
* @version		$Id: search.inc.php 429 2008-003-25 22:21:41Z real_therplima $
*/

function search_content($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB, $xoopsConfig, $xoopsUser;
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	
	$content_handler =& xoops_gethandler('content');
	$gperm_handler = & xoops_gethandler( 'groupperm' );

	$criteria = new CriteriaCompo(new Criteria('content_status', 1));
	if ( $userid != 0 ) {
		$criteria->add(new Criteria('content_uid', $userid));
	}

	$qarray_bkp = $queryarray;
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$crit = new CriteriaCompo(new Criteria('content_title', '%'.$queryarray[0].'%','LIKE'));
		$crit->add(new Criteria('content_menu', '%'.$queryarray[0].'%','LIKE'),'OR');
		$crit->add(new Criteria('content_body', '%'.$queryarray[0].'%','LIKE'),'OR');
		$criteria->add($crit);
		unset($queryarray[0]);
		unset($crit);
		foreach ($queryarray as $query){
			$crit = new CriteriaCompo(new Criteria('content_title', '%'.$query.'%','LIKE'));
			$crit->add(new Criteria('content_menu', '%'.$query.'%','LIKE'),'OR');
			$crit->add(new Criteria('content_body', '%'.$query.'%','LIKE'),'OR');
			$criteria->add($crit,$andor);
			unset($crit);
		}
	}
	$queryarray = $qarray_bkp;
	
	$contents = $content_handler->getObjects($criteria);
	
	$ret = array();
	
	if (is_array($contents) && count($contents) > 0) {
		$i = 0;
		asort($contents);
		foreach ($contents as $content) {
			if ($gperm_handler->checkRight('content_read', $content->getVar('content_id'), $groups)){
				$ret[$i]['image'] = "images/icon_small.png";
				$ret[$i]['link'] = XOOPS_URL.'/content.php?page='.$content_handler->makeLink($content);
				$ret[$i]['title'] = $content->getVar('content_title');
				$ret[$i]['uid'] = $content->getVar('content_uid');
				$ret[$i]['time'] = $content->getVar('content_created');
				if ($i == ($limit-1)) {
					return $ret;
				}
				$i++;
			}
		}
	}
	
	$symlinks_handler =& xoops_gethandler('page');

	$criteria = new CriteriaCompo(new Criteria('page_status', 1));
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$crit = new CriteriaCompo(new Criteria('page_title', '%'.$queryarray[0].'%','LIKE'));
		$criteria->add($crit);
		unset($queryarray[0]);
		unset($crit);
		foreach ($queryarray as $query){
			$crit = new CriteriaCompo(new Criteria('page_title', '%'.$query.'%','LIKE'));
			$criteria->add($crit,$andor);
			unset($crit);
		}
	}
	$criteria->add(new Criteria('page_url', '%*', 'NOT LIKE'));
	
	$symlinks = $symlinks_handler->getObjects($criteria);
	
	if (is_array($symlinks) && count($symlinks) > 0) {
		$i = 0;
		asort($symlinks);
		foreach ($symlinks as $symlink) {
			$ret[$i]['image'] = "images/icon_small.png";
			$ret[$i]['link'] = $symlink->getVar('page_url');
			$ret[$i]['title'] = $symlink->getVar('page_title');
			if ($i == ($limit-1)) {
				return $ret;
			}
			$i++;
		}
	} 

	return $ret;
}
?>
