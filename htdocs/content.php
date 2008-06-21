<?php
/**
* Content Manager
*
* System tool that allow create and manage content pages
* Some parts of this tool was based on mastop publish and smartcontent modules
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version		$Id: content.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

$xoopsOption['pagetype'] = 'content';
include 'mainfile.php';
include_once ICMS_ROOT_PATH.'/class/module.textsanitizer.php';
include_once ICMS_ROOT_PATH . '/modules/system/constants.php';

$im_contentConfig =& $config_handler->getConfigsByCat(IM_CONF_CONTENT);

$page = (isset($_GET['page']))?$_GET['page']:((isset($_POST['page']))?$_POST['page']:0);
$gperm_handler = & xoops_gethandler( 'groupperm' );
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
$content_handler =& xoops_gethandler('content');
if (!$page){
	if ($im_contentConfig['default_page'] != 0){
		$criteria = new CriteriaCompo(new Criteria('content_id', $im_contentConfig['default_page']));
	}else{
		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
		$criteria->setSort('content_id');
		$criteria->setOrder('DESC');
	}
	$impress_content = $content_handler->getObjects($criteria);
	$impress_content = $impress_content[0];
} else {
	$page = (is_int($page)) ? intval($page) : str_replace("_"," ", $page);
	$criteria = new CriteriaCompo(new Criteria('content_status', 1));
	$criteria->add(new Criteria('content_menu', $page,'LIKE'));
	$criteria->add(new Criteria('content_id', $page),'OR');
	$impress_content = $content_handler->getObjects($criteria);
	$impress_content = $impress_content[0];
}

if (!is_object($impress_content)){
	redirect_header('index.php', 2, _CT_SELECTNG);
}
$content_id = $impress_content->getVar('content_id');

$viewperm  = $gperm_handler->checkRight('content_read', $content_id, $groups);         // $viewperm is true if user has permition to see this page
$adminperm = $gperm_handler->checkRight('content_admin', $content_id, $uid);        // $adminperm is true if user has permition to admin this page

if (!$viewperm){
	redirect_header('index.php', 2, _NOPERM);
}

$myts =& MyTextSanitizer::getInstance();
$xoopsOption['template_main'] = 'system_content.html';

include ICMS_ROOT_PATH.'/header.php';
$xoopsTpl->assign("content_title", $impress_content->getVar('content_title'));
$xoopsTpl->assign("isAdmin", $adminperm);
$options = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=content&op=editcontent&content_id='.$impress_content->getVar('content_id').'"><img src="'.ICMS_URL.'/modules/system/images/edit_big.png" title="'._CT_EDIT_CONTENT.'" alt="'._CT_EDIT_CONTENT.'" /></a>';
$options .= '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=content&op=delcontent&content_id='.$impress_content->getVar('content_id').'"><img src="'.ICMS_URL.'/modules/system/images/delete_big.png" title="'._CT_DELETE_CONTENT.'" alt="'._CT_DELETE_CONTENT.'" /></a>';
$xoopsTpl->assign("content_admlinks", $options);
$member_handler =& xoops_gethandler('member');
$autor =& $member_handler->getUser($impress_content->getVar('content_uid')); 
$xoopsTpl->assign("show_pinfo",$im_contentConfig['show_pinfo']);
$xoopsTpl->assign("content_tinfo", sprintf(_CT_PUBLISHEDBY.' <a href="'.ICMS_URL.'/userinfo.php?uid=%u">%s</a> '._CT_ON.' %s (%u '._CT_READS.')',$autor->getVar('uid'),$autor->getVar('uname'),formatTimestamp($impress_content->getVar('content_created'),"s"),$impress_content->getReads()));
$xoopsTpl->assign("content_body", $myts->previewTarea($impress_content->getVar('content_body', "n"),1,1,1,1,0));
$xoopsTpl->assign("content_css", sanitizeContentCss($impress_content->getVar('content_css')));

if ($im_contentConfig['show_subs']){
	$criteria = new Criteria('content_supid', $content_id);
	$subs = $content_handler->getCount($criteria);
	if ($subs > 0){
		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
		$criteria->add(new Criteria('content_supid', $content_id));
		$crit = new CriteriaCompo(new Criteria('content_visibility', 2));
		$crit->add(new Criteria('content_visibility', 3),'OR');
		$criteria->add($crit);
		$subs = $content_handler->getObjects($criteria);
		foreach ($subs as $sub){
			$content_subs = array();
			$content_subs['titulo'] = $sub->getVar("content_title");
			$content_subs['teaser'] = icms_substr(icms_cleanTags($sub->getVar("content_body",'n'),array()),0,300);
			$seo = urlencode(str_replace(" ", "_",$sub->getVar('content_menu')));
			$content_subs['link'] = ICMS_URL.'/content.php?page='.$seo;
			$xoopsTpl->append("content_subs", $content_subs);
		}
		$xoopsTpl->assign('showSubs', 1);
		$xoopsTpl->assign('subs_label', _CT_RELATEDS);
	}
}else{
	$xoopsTpl->assign('showSubs', 0);
}
$xoopsTpl->assign('showNav',$im_contentConfig['show_nav']);
$xoopsTpl->assign('nav',showNav($content_id));
$xoopsTpl->assign("xoops_pagetitle", $impress_content->getVar('content_title'));
$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="'.ICMS_URL.'/modules/system/admin/content/style.css" />');

if (!is_object($xoopsUser)){
	$impress_content->setReads();
}else{
	if ($xoopsUser->getVar('uid') != $autor->getVar('uid')){
		$impress_content->setReads();
	}
}
$content_handler->insert($impress_content);
include ICMS_ROOT_PATH.'/footer.php';
?>