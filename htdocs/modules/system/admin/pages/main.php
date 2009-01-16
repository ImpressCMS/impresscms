<?php
/**
* Links Manager
*
* System tool that allow create and manage custom links to show blocks or define as start page
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version		$Id: main.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_CT_ACCESS_DENIED);
} else {
	if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);
	if (!empty($_GET)) foreach ($_GET as $k => $v) ${$k} = StopXSS($v);
    $op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'list');
    $page_id = (isset($_GET['page_id']))?intval($_GET['page_id']):((isset($_POST['page_id']))?intval($_POST['page_id']):0);
    $limit = (isset($_GET['limit']))?intval($_GET['limit']):((isset($_POST['limit']))?intval($_POST['limit']):15);
    $start = (isset($_GET['start']))?intval($_GET['start']):((isset($_POST['start']))?intval($_POST['start']):0);
    $redir = (isset($_GET['redir']))?$_GET['redir']:((isset($_POST['redir']))?$_POST['redir']:null);
    
    switch ($op){
    	case 'list':
    		xoops_cp_header();
    		echo pages_index($start);
    		xoops_cp_footer();
    		break;
    	case 'addpage':
    		pages_addpage();
    		break;
    	case 'editpage':
    		xoops_cp_header();
    		echo pageform($page_id);
    		xoops_cp_footer();
    		break;
    	case 'editpageok':
    		pages_editpage($page_id);
    		break;
    	case 'delpage':
    		pages_confirmdelpage($page_id,$redir);
    		break;
    	case 'delpageok':
    		pages_delpage($page_id,$redir);
    		break;
    	case 'changestatus':
    		pages_changestatus($page_id,$redir);
    		break;
    }
}

function pages_index($start=0){
	global $icmsAdminTpl,$xoopsUser,$xoopsConfig,$limit;

	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	
    $query = isset($_POST['query']) ? StopXSS($_POST['query']) : null;
	$icmsAdminTpl->assign('query',$query);
	
	$icmsAdminTpl->assign('lang_page_manager',_MD_PAGEMAN);
	$icmsAdminTpl->assign('lang_submit',_SUBMIT);
	$icmsAdminTpl->assign('lang_cancel',_CANCEL);
	$icmsAdminTpl->assign('lang_search',_SEARCH);
	$icmsAdminTpl->assign('lang_notfound',_MD_NOTFOUND);
	$icmsAdminTpl->assign('lang_page_id',_MD_ID);
	$icmsAdminTpl->assign('lang_page_module',_MD_MODULE);
	$icmsAdminTpl->assign('lang_page_title',_MD_TITLE);
	$icmsAdminTpl->assign('lang_page_url',_MD_URL);
	$icmsAdminTpl->assign('lang_page_status',_MD_STATUS);
	$icmsAdminTpl->assign('lang_page_options',_MD_OPTIONS);
	$icmsAdminTpl->assign('lang_page_addpage',_MD_ADDPAGE);
	$icmsAdminTpl->assign('lang_page_changests',_MD_PAGECHANGESTS);
	$icmsAdminTpl->assign('lang_page_prevpage',_MD_PREVIEWPAGE);
	$icmsAdminTpl->assign('lang_page_editpage',_MD_EDITPAGE);
	$icmsAdminTpl->assign('lang_page_delpage',_MD_DELETEPAGE);
	
	$page_handler =& xoops_gethandler('page');
	$module_handler =& xoops_gethandler('module');

	$criteria = new CriteriaCompo();
	if (!is_null($query)){
		$crit = new CriteriaCompo(new Criteria('page_title', $query.'%','LIKE'));
		$criteria->add($crit);		
	}
	$pagecount = $page_handler->getCount($criteria);
	$icmsAdminTpl->assign('pagecount',$pagecount);
	$criteria->setStart($start);
	$criteria->setLimit($limit);
	$pages = $page_handler->getObjects($criteria);
	
	if ($pagecount > 0){
		if ($pagecount > $limit) {
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($pagecount, $limit, $start, 'start', 'fct=pages&amp;op=list');
			$icmsAdminTpl->assign('pag','<div style="float:'._GLOBAL_LEFT.'; padding-top:2px;" align="center">'.$nav->renderNav().'</div>');
		}else{
			$icmsAdminTpl->assign('pag','');
		}
	}else{
		$icmsAdminTpl->assign('pag','');
	}
	
	foreach ($pages as $page){
		$pag = array();
		$pag['page_id'] = $page->getVar('page_id');
		$pag['pages_id'] = icms_conv_nr2local($page->getVar('page_id'));
		$pag['page_moduleid'] = $page->getVar('page_moduleid');
		$mod = $module_handler->get($page->getVar('page_moduleid'));
		$pag['module'] = $mod->getVar('name');
		$pag['page_title'] = $page->getVar('page_title');
		$pag['page_url'] = $page->getVar('page_url');
		if (substr($page->getVar('page_url'),-1) == '*'){
			$pag['page_vurl'] = 0;
		}else{
			if (substr($page->getVar('page_url'),0,7) == 'http://'){
				$pag['page_vurl'] = $page->getVar('page_url');
			}else{
				$pag['page_vurl'] = XOOPS_URL.'/'.$page->getVar('page_url');
			}
		}
		$pag['page_status'] = $page->getVar('page_status');
		$icmsAdminTpl->append('pages',$pag);
	}
	$icmsAdminTpl->assign('addpageform',pageform());
	
	return $icmsAdminTpl->fetch('db:admin/pages/system_adm_pagemanager_index.html');
}

function pages_addpage() {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=pages', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}

	$page_handler =& xoops_gethandler('page');
	$page = $page_handler->create();
	if (!isset($_POST['page_moduleid']) || $_POST['page_moduleid'] == 0){
		$_POST['page_moduleid'] = 1;
	}
	$page->setVars($_POST);

	if (!$page_handler->insert($page)){
		$msg = _MD_FAILADD;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	redirect_header('admin.php?fct=pages&op=list',2,$msg);
}

function pages_editpage($page_id) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=pages', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$page_handler =& xoops_gethandler('page');
	$page = $page_handler->get($page_id);
	if (!isset($_POST['page_moduleid']) || $_POST['page_moduleid'] == 0){
		$_POST['page_moduleid'] = 1;
	}
	$page->setVars($_POST);

	if (!$page_handler->insert($page)){
		$msg = _MD_FAILEDIT;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	redirect_header('admin.php?fct=pages&op=list',2,$msg);
}

function pages_delpage($page_id,$redir=null) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=pages',1, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	if ($page_id <= 0) {
		redirect_header('admin.php?fct=pages',1);
	}
	$page_handler = xoops_gethandler('page');
	$page =& $page_handler->get($page_id);
	if (!is_object($page)) {
		redirect_header('admin.php?fct=pages',1);
	}

	if (!$page_handler->delete($page)) {
		xoops_cp_header();
		xoops_error(sprintf(_MD_FAILDEL, $page->getVar('page_id')));
		xoops_cp_footer();
		exit();
	}

	redirect_header((!is_null($redir))?base64_decode($redir):'admin.php?fct=pages',2,_MD_AM_DBUPDATED);
}

function pages_confirmdelpage($page_id,$redir=null){
	global $xoopsConfig;
	
	$page_handler = xoops_gethandler('page');
	$page = $page_handler->get($page_id);
	
	if ($xoopsConfig['startpage'] == $page->getVar('page_moduleid').'-'.$page->getVar('page_id')){ //Selected page is the start page of the site
		redirect_header((!is_null($redir))?base64_decode($redir).'&canceled=1':'admin.php?fct=pages&op=list',5,_MD_DELSTARTPAGE);
	}else{
		xoops_cp_header();
		$arr = array();
		$arr['op'] = 'delpageok';
		$arr['page_id'] = $page_id;
		$arr['fct'] = 'pages';
		if (!is_null($redir)){
			$arr['redir'] = $redir;
		}
		xoops_confirm($arr, 'admin.php', _MD_RUDELPAGE);
		xoops_cp_footer();
	}
}

function pages_changestatus($page_id,$redir=null) {
	global $xoopsConfig;
	
	$page_handler = xoops_gethandler('page');
	$page = $page_handler->get($page_id);
	if (empty($redir)){
		$sts = !$page->getVar('page_status');
	}else{
		$sts = 0;
	}
	$page->setVar('page_status',$sts);

	$module_handler = xoops_gethandler('module');
	$mod = $module_handler->get($page->getVar('page_moduleid'));
	
	if (!$mod->getVar('isactive')){
		redirect_header((!is_null($redir))?base64_decode($redir).'&canceled=1':'admin.php?fct=pages&op=list',3,_MD_MODDEACTIVE);
	}
	
	if ($xoopsConfig['startpage'] == $page->getVar('page_moduleid').'-'.$page->getVar('page_id')){ //Selected page is the start page of the site
		redirect_header((!is_null($redir))?base64_decode($redir).'&canceled=1':'admin.php?fct=pages&op=list',5,_MD_DELSTARTPAGE);
	}
	
	if (!$page_handler->insert($page)){
		$msg = _MD_FAILEDIT;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	redirect_header((!is_null($redir))?base64_decode($redir):'admin.php?fct=pages&op=list',2,$msg);
}

function pageform($id=null){
	global $xoopsUser,$xoopsConfig;
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	
	if (isset($id)){
		$ftitle = _MD_EDITPAGE;
		$page_handler = xoops_gethandler('page');
        $page =& $page_handler->get($id);
        $moduleid = $page->getVar('page_moduleid');
        $title = $page->getVar('page_title');
        $url = $page->getVar('page_url');        
		$status = $page->getVar('page_status');
	}else{
		$ftitle = _MD_ADDPAGE;
		$title = '';
		$url = '';
		$moduleid = '';
		$status = 1;
	}

	$form = new XoopsThemeForm($ftitle, 'page_form', 'admin.php', "post", true);

	$page_handler = xoops_gethandler('page');
	
	$mid = new XoopsFormSelect(_MD_PAGE_MODULE, 'page_moduleid', $moduleid);
	$mid->customValidationCode[] = 'var value = document.getElementById(\'page_moduleid\').value; if (value == 0){alert("'._MD_PAGE_MODULE_ERR.'"); return false;}';
	$module_handler =& xoops_gethandler('module');
	$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
	$criteria->add(new Criteria('isactive', 1));
	$moduleslist = $module_handler->getList($criteria);
	$module = $module_handler->get(1);
	$list = array('0'=>'--------------------------',$module->getVar('mid')=>$module->getVar('name'));
	$moduleslist = $list+$moduleslist;
	$mid->addOptionArray($moduleslist);
	$form->addElement($mid,true);
	
	$form->addElement(new XoopsFormText(_MD_PAGE_TITLE, 'page_title', 50, 255,$title), true);
	$furl = new XoopsFormText(_MD_PAGE_URL, 'page_url', 50, 255,$url);
	$furl->setDescription(_MD_PAGE_URL_DESC);
	$form->addElement($furl, true);
	$form->addElement(new XoopsFormRadioYN(_MD_PAGE_DISPLAY, 'page_status', intval($status), _YES, _NO));

	$tray = new XoopsFormElementTray('' ,'');
	$tray->addElement(new XoopsFormButton('', 'page_button', _SUBMIT, 'submit'));

	$btn = new XoopsFormButton('', 'reset', _CANCEL, 'button');
	if (isset($id)){
		$btn->setExtra('onclick="document.location.href=\'admin.php?fct=pages&op=list\'"');
	}else{
		$btn->setExtra('onclick="document.getElementById(\'addpageform\').style.display = \'none\'; return false;"');
	}
	$tray->addElement($btn);
	$form->addElement($tray);
	
	$form->addElement(new XoopsFormHidden('fct', 'pages'));
	if (isset($id)){
		$form->addElement(new XoopsFormHidden('op', 'editpageok'));
		$form->addElement(new XoopsFormHidden('page_id', $id));
	}else{
		$form->addElement(new XoopsFormHidden('op', 'addpage'));
	}

	return $form->render();
}
?>