<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Função de Busca no Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: busca.inc.php,v 1.1.1.1 2007/01/31 19:30:00 topet05 Exp $
### =============================================================

function search_content($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB, $xoopsConfig, $xoopsUser;
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	
	$content_handler =& xoops_gethandler('content');
	$gperm_handler = & xoops_gethandler( 'groupperm' );

	$criteria = new CriteriaCompo(new Criteria('content_status', 1));
	if ( $userid != 0 ) {
		$criteria->add(new Criteria('content_uid', $userid));
	}
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$crit = new CriteriaCompo(new Criteria('content_title', '%'.$queryarray[0].'%','LIKE'));
		$crit->add(new Criteria('content_menu', '%'.$queryarray[0].'%','LIKE'),'OR');
		$crit->add(new Criteria('content_body', '%'.$queryarray[0].'%','LIKE'),'OR');
		$criteria->add($crit);
		unset($queryarray[0]);
		foreach ($queryarray as $query){
			$crit = new CriteriaCompo(new Criteria('content_title', '%'.$queryarray[0].'%','LIKE'));
			$crit->add(new Criteria('content_menu', '%'.$queryarray[0].'%','LIKE'),'OR');
			$crit->add(new Criteria('content_body', '%'.$queryarray[0].'%','LIKE'),'OR');
			$criteria->add($crit,$andor);
		}
	}
	
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
	return $ret;
}
?>
