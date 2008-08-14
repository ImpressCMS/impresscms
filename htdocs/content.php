<?php
/**
* Content Manager
*
* System tool that allow create and manage content pages
* Some parts of this tool was based on mastop publish and smartcontent modules
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package	core
* @since	1.1
* @author	Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version	$Id: content.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

$xoopsOption['pagetype'] = 'content';
include 'mainfile.php';
include_once ICMS_ROOT_PATH.'/class/module.textsanitizer.php';
include_once ICMS_ROOT_PATH.'/modules/system/constants.php';

$im_contentConfig =& $config_handler->getConfigsByCat(IM_CONF_CONTENT);
$page = (isset($_GET['page']))?trim(StopXSS($_GET['page'])):((isset($_POST['page']))?trim(StopXSS($_POST['page'])):0);

$gperm_handler = & xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$uid = is_object($xoopsUser) ? intval($xoopsUser->getVar('uid')) : 0;
$content_handler =& xoops_gethandler('content');

$tag = (isset($_GET['tag']))?trim(StopXSS($_GET['tag'])):((isset($_POST['tag']))?trim(StopXSS($_POST['tag'])):null);
$start = (isset($_GET['start']))?intval($_GET['start']):((isset($_POST['start']))?intval($_POST['start']):0);
if (!$page){
	$path = (isset($_SERVER['PATH_INFO']) && substr($_SERVER['PATH_INFO'],0,1) == '/')?substr($_SERVER['PATH_INFO'],1,strlen($_SERVER['PATH_INFO'])):((isset($_SERVER['PATH_INFO']))?$_SERVER['PATH_INFO']:'');
	$path = trim(StopXSS($path));
	$params = explode('/',$path);
	if (count($params) > 0){
		if ($params[0] == 'page'){
			$page = (isset($params[1]))?$params[1]:0;
		}elseif ($params[0] == 'tag'){
			$tag = (isset($params[1]))?$params[1]:null;
			$start = (isset($params[2]))?$params[2]:0;
		}else{
			$page = $params[0];
		}
	}
}

if (!is_null($tag)){
	include ICMS_ROOT_PATH.'/header.php';
	echo list_by_tag($tag,$start);
	include ICMS_ROOT_PATH.'/footer.php';
	exit;
}

if(!$page)
{
	if($im_contentConfig['default_page'] != 0)
	{
		$criteria = new CriteriaCompo(new Criteria('content_id', $im_contentConfig['default_page']));
	}
	else
	{
		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
		$criteria->setSort('content_id');
		$criteria->setOrder('DESC');
	}
	$impress_content = $content_handler->getObjects($criteria);
	$impress_content = (isset($impress_content[0]))?$impress_content[0]:null;
}
else
{
	$page = (is_int($page)) ? intval($page) : str_replace("_"," ", $page);
	$criteria = new CriteriaCompo(new Criteria('content_status', 1));
	$criteria->add(new Criteria('content_menu', $page,'LIKE'));
	$criteria->add(new Criteria('content_id', $page),'OR');
	$impress_content = $content_handler->getObjects($criteria);
	$impress_content = (isset($impress_content[0]))?$impress_content[0]:null;
}
if(!is_object($impress_content)) {redirect_header('index.php', 2, _CT_SELECTNG);}
$content_id = $impress_content->getVar('content_id');
$viewperm  = $gperm_handler->checkRight('content_read', $content_id, $groups);	// $viewperm is true if user has permition to see this page
$adminperm = $gperm_handler->checkRight('content_admin', $content_id, $uid);	// $adminperm is true if user has permition to admin this page

if(!$viewperm) {redirect_header('index.php', 2, _NOPERM);}
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
$xoopsTpl->assign("content_tags", filter_bytags($impress_content->getVar('content_tags')));
$xoopsTpl->assign("lang_tags", _CT_TAGS);

if($im_contentConfig['show_subs'])
{
	$criteria = new Criteria('content_supid', $content_id);
	$subs = $content_handler->getCount($criteria);
	if($subs > 0)
	{
		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
		$criteria->add(new Criteria('content_supid', $content_id));
		$crit = new CriteriaCompo(new Criteria('content_visibility', 2));
		$crit->add(new Criteria('content_visibility', 3),'OR');
		$criteria->add($crit);
		$subs = $content_handler->getObjects($criteria);
		foreach($subs as $sub)
		{
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
}
else
{
	$xoopsTpl->assign('showSubs', 0);
}
$xoopsTpl->assign('showNav',$im_contentConfig['show_nav']);
$xoopsTpl->assign('nav', showNav($content_id));
$xoopsTpl->assign("xoops_pagetitle", $impress_content->getVar('content_title'));
//$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="'.ICMS_URL.'/modules/system/admin/content/style.css" />');
$xoTheme->addStylesheet(ICMS_URL.'/modules/system/admin/content/style.css');

if(!is_object($xoopsUser))
{
	$impress_content->setReads();
}
else
{
	if($xoopsUser->getVar('uid') != $autor->getVar('uid')) {$impress_content->setReads();}
}
$content_handler->insert($impress_content);
include ICMS_ROOT_PATH.'/footer.php';

function list_by_tag($tag,$start=0){
    global $im_contentConfig,$groups,$uid,$xoopsTpl,$xoTheme;
    
    $myts =& MyTextSanitizer::getInstance();
    
    $gperm_handler   =& xoops_gethandler('groupperm');
    $content_handler =& xoops_gethandler('content');
	
    $criteria = new CriteriaCompo(new Criteria('content_status', 1));
    $criteria->add(new Criteria('content_tags', '%'.$tag.'%','LIKE'));
    $pagecount = $content_handler->getCount($criteria);
    $criteria->setLimit($im_contentConfig['num_pages']);
    $criteria->setStart($start);
	$pages = $content_handler->getObjects($criteria);
	
	foreach ($pages as $page){
		if ($gperm_handler->checkRight('content_read', $page->getVar('content_id'), $groups)){
			$adminperm = $gperm_handler->checkRight('content_admin', $page->getVar('content_id'), $uid);
			$cont = array();
			$cont['title'] = $page->getVar('content_title');
			$cont['url'] = ICMS_URL.'/content.php?page='.$content_handler->makeLink($page);
			$cont['isAdmin'] = $adminperm;
			$options = '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=content&op=editcontent&content_id='.$page->getVar('content_id').'"><img src="'.ICMS_URL.'/modules/system/images/edit_big.png" title="'._CT_EDIT_CONTENT.'" alt="'._CT_EDIT_CONTENT.'" /></a>';
			$options .= '<a href="'.ICMS_URL.'/modules/system/admin.php?fct=content&op=delcontent&content_id='.$page->getVar('content_id').'"><img src="'.ICMS_URL.'/modules/system/images/delete_big.png" title="'._CT_DELETE_CONTENT.'" alt="'._CT_DELETE_CONTENT.'" /></a>';
			$cont['admlinks'] = $options;
			$member_handler =& xoops_gethandler('member');
			$autor =& $member_handler->getUser($page->getVar('content_uid'));
			$cont['tinfo'] = sprintf(_CT_PUBLISHEDBY.' <a href="'.ICMS_URL.'/userinfo.php?uid=%u">%s</a> '._CT_ON.' %s (%u '._CT_READS.')',$autor->getVar('uid'),$autor->getVar('uname'),formatTimestamp($page->getVar('content_created'),"s"),$page->getReads());
			if ($im_contentConfig['teaser_length'] > 0){
				$cont['body'] = icms_substr(icms_cleanTags($page->getVar("content_body",'n'),array()),0,$im_contentConfig['teaser_length']);
			}else{
				$cont['body'] = $myts->previewTarea($page->getVar('content_body', "n"),1,1,1,1,0);
			}
			$cont['tags'] = filter_bytags($page->getVar('content_tags'));
			$xoopsTpl->append('contents',$cont);
		}
	}
	
	if ($pagecount > 0){
		if ($pagecount > $im_contentConfig['num_pages']) {
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($pagecount, $im_contentConfig['num_pages'], $start, 'start','tag='.$tag);
			$xoopsTpl->assign('pag',$nav->renderNav());
		}else{
			$xoopsTpl->assign('pag','');
		}
	}else{
		$xoopsTpl->assign('pag','');
	}
	
    $xoopsTpl->assign("lang_tags", _CT_TAGS);
	$xoopsTpl->assign("show_pinfo",$im_contentConfig['show_pinfo']);
    
	$xoTheme->addStylesheet(ICMS_URL.'/modules/system/admin/content/style.css');
	
	return $xoopsTpl->fetch('db:system_content_list.html');
}

function filter_bytags($tags){
	
	if (!empty($tags)){
		$tags_arr = explode(',',$tags);
	}else{
		$tags_arr = array();
	}

	$ret = '';
	if (count($tags_arr) > 0){
		foreach ($tags_arr as $tag){
			$ret .= '<a href="'.ICMS_URL.'/content.php?tag='.$tag.'">'.$tag.'</a>, ';
		}
		$ret = substr($ret,0,strlen($ret)-2);
	}
	return $ret;
}
?>