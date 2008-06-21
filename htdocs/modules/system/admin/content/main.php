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
* @version		$Id: main.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_CT_ACCESS_DENIED);
} else {
	if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);
	if (!empty($_GET)) foreach ($_GET as $k => $v) ${$k} = StopXSS($v);
    $op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'list');
    $content_id = (isset($_GET['content_id']))?intval($_GET['content_id']):((isset($_POST['content_id']))?intval($_POST['content_id']):0);
    $content_supid = (isset($_GET['content_supid']))?intval($_GET['content_supid']):((isset($_POST['content_supid']))?intval($_POST['content_supid']):0);
    $limit = (isset($_GET['limit']))?intval($_GET['limit']):((isset($_POST['limit']))?intval($_POST['limit']):15);
    $start = (isset($_GET['start']))?intval($_GET['start']):((isset($_POST['start']))?intval($_POST['start']):0);
    $editor = (isset($_GET['editor']))?$_GET['editor']:null;
    switch ($op){
    	case 'list':
    		xoops_cp_header();
    		echo contmanager_index($content_supid,$start);
    		xoops_cp_footer();
    		break;
    	case 'savelist':
    		contmanager_savelist($content_supid);
    		break;
    	case 'addcontent':
    		contmanager_addcontent();
    		break;
    	case 'editcontent':
    		xoops_cp_header();
    		echo contentform($content_id);
    		xoops_cp_footer();
    		break;
    	case 'editcontentok':
    		contmanager_editcontent($content_id);
    		break;
    	case 'clonecontent':
    		xoops_cp_header();
    		echo contentform($content_id,true);
    		xoops_cp_footer();
    		break;
    	case 'clonecontentok':
    		contmanager_clonecontent($content_id);
    		break;
    	case 'delcontent':
    		xoops_cp_header();
    		xoops_confirm(array('op' => 'delcontentok', 'content_id' => $content_id, 'fct' => 'content'), 'admin.php', _MD_RUDELCONTENT);
    		xoops_cp_footer();
    		break;
    	case 'delcontentok':
    		contmanager_delcontent($content_id);
    		break;
    	case 'changestatus':
    		contmanager_changestatus($content_id);
    		break;
    }
}

function contmanager_index($content_supid,$start=0){
	global $icmsAdminTpl,$xoopsUser,$xoopsConfig,$limit,$editor;

	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	
    $query = isset($_POST['query']) ? StopXSS($_POST['query']) : null;
	$icmsAdminTpl->assign('query',$query);
	
	$icmsAdminTpl->assign('lang_cont_manager',_MD_CONTENTMAN);
	$icmsAdminTpl->assign('lang_submit',_SUBMIT);
	$icmsAdminTpl->assign('lang_cancel',_CANCEL);
	$icmsAdminTpl->assign('lang_search',_SEARCH);
	$icmsAdminTpl->assign('lang_notfound',_MD_NOTFOUND);
	$icmsAdminTpl->assign('lang_cont_id',_MD_ID);
	$icmsAdminTpl->assign('lang_cont_title',_MD_TITLE);
	$icmsAdminTpl->assign('lang_cont_menu',_MD_MENU);
	$icmsAdminTpl->assign('lang_cont_visibility',_MD_VISIBILITY);
	$icmsAdminTpl->assign('lang_cont_subs',_MD_SUBS);
	$icmsAdminTpl->assign('lang_cont_weight',_MD_WEIGHT);
	$icmsAdminTpl->assign('lang_cont_status',_MD_STATUS);
	$icmsAdminTpl->assign('lang_cont_options',_MD_OPTIONS);
	$icmsAdminTpl->assign('lang_cont_addcont',_MD_ADDCONTENT);
	$icmsAdminTpl->assign('lang_cont_changests',_MD_CONTENTCHANGESTS);
	$icmsAdminTpl->assign('lang_cont_prevcont',_MD_PREVIEWCONTENT);
	$icmsAdminTpl->assign('lang_cont_clonecont',_MD_CLONECONTENT);
	$icmsAdminTpl->assign('lang_cont_editcont',_MD_EDITCONTENT);
	$icmsAdminTpl->assign('lang_cont_delcont',_MD_DELETECONTENT);
	
	$content_handler =& xoops_gethandler('content');
	$gperm_handler = & xoops_gethandler( 'groupperm' );

	$criteria = new CriteriaCompo(new Criteria('content_supid', $content_supid));
	if (!is_null($query)){
		$crit = new CriteriaCompo(new Criteria('content_title', $query.'%','LIKE'));
		$crit->add(new Criteria('content_menu', $query.'%','LIKE'),'OR');
		$crit->add(new Criteria('content_body', '%'.$query.'%','LIKE'),'OR');
		$criteria->add($crit);		
	}
	$pagecount = $content_handler->getCount($criteria);
	$icmsAdminTpl->assign('pagecount',$pagecount);
	$criteria->setStart($start);
	$criteria->setLimit($limit);
	$contents = $content_handler->getObjects($criteria);
	
	$conts = array();
	foreach ($contents as $content){
		if ($gperm_handler->checkRight('content_read', $content->getVar('content_id'), $groups)){
			$conts[] = $content;
		}
	}
	
	if ($pagecount != count($conts)){
		$pagecount = count($conts);
		$contents = $conts;
	}
	
	if ($pagecount > 0){
		if ($pagecount > $limit) {
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($pagecount, $limit, $start, 'start', 'fct=content&amp;op=list&amp;content_supid='.$content_supid);
			$icmsAdminTpl->assign('pag','<div style="float:left; padding-top:2px;" align="center">'.$nav->renderNav().'</div>');
		}else{
			$icmsAdminTpl->assign('pag','');
		}
	}else{
		$icmsAdminTpl->assign('pag','');
	}
	
	foreach ($contents as $content){
		$cont = array();
		$cont['content_id'] = $content->getVar('content_id');
		$cont['content_supid'] = $content->getVar('content_supid');
		$cont['content_title'] = $content->getVar('content_title');
		$cont['content_menu'] = $content->getVar('content_menu');
		$cont['viewlink'] = $content_handler->makeLink($content);
		$criteria = new Criteria('content_supid', $content->getVar('content_id'));
		$subs = $content_handler->getCount($criteria);
		$cont['content_subs'] = $subs;
		$cont['content_weight'] = $content->getVar('content_weight');
		$cont['content_status'] = $content->getVar('content_status');
		$visibility_select = new XoopsFormSelect(_MD_CONTENT_VISIBILITY, "content_visibility".$content->getVar('content_id'),$content->getVar('content_visibility'));
		$visibility_select->addOptionArray(array(1=>_MD_CONTENT_VISIBILITY_1, 2=>_MD_CONTENT_VISIBILITY_2, 3=>_MD_CONTENT_VISIBILITY_3, 4=>_MD_CONTENT_VISIBILITY_4));
		$cont['visibility'] = $visibility_select->render();
		$icmsAdminTpl->append('contents',$cont);
	}
	$icmsAdminTpl->assign('content_supid',$content_supid);	
	$icmsAdminTpl->assign('TokenHTML',$GLOBALS['xoopsSecurity']->getTokenHTML());	
	$icmsAdminTpl->assign('adminNav',adminNav($content_supid));
	$icmsAdminTpl->assign('addcontform',contentform());
	
	$icmsAdminTpl->assign('addformsts',(!is_null($editor))?'block':'none');
	
	return $icmsAdminTpl->fetch('db:admin/content/system_adm_contentmanager_index.html');
}

function contmanager_savelist($content_supid) {
	$err = 0;
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=content', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$content_handler =& xoops_gethandler('content');
	$criteria = new Criteria('content_supid', $content_supid);
	$contents = $content_handler->getObjects($criteria);
	foreach ($contents as $cont){
		$content = $content_handler->get($cont->getVar('content_id'));
		if ( isset( $_POST['content_weight'.$cont->getVar('content_id')] ) ) {
			$content->setVar('content_weight', intval($_POST['content_weight'.$cont->getVar('content_id')]));
		}
		if ( isset( $_POST['content_visibility'.$cont->getVar('content_id')] ) ) {
			$content->setVar('content_visibility', intval($_POST['content_visibility'.$cont->getVar('content_id')]));
		}
		$content->setVar('content_updated',time());
		if ( !$content_handler->insert($content) ) {
			$err++;
		}
	}
	if ( $err > 0 ) {
		$msg = _MD_FAILEDIT;
	} else {
		$msg = _MD_AM_DBUPDATED;
	}
	redirect_header('admin.php?fct=content&op=list&content_supid='.$content_supid, 2, $msg);
}

function contmanager_addcontent() {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=content', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}

	$err = array();

	$content_handler =& xoops_gethandler('content');
	$content = $content_handler->create();
	$content->setVars($_POST);
	$content->setVar('content_created',time());
	$content->setVar('content_updated',time());

	if (!$content_handler->insert($content)){
		$msg = _MD_FAILADD;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	$gperm_handler =& xoops_gethandler('groupperm');
	$groups = $_POST['grupos_perm'];
	$count = count($groups);
	for ($i = 0; $i < $count; $i++) {
		$gperm_handler->addRight('content_read', $content->getVar('content_id'), $groups[$i]);
	}
	$gperm_handler->addRight('content_admin', $content->getVar('content_id'), $content->getVar('content_uid'));
	$contentid_or_supid = 'content_id';
	$contentid_or_supid_value = $content->getVar('content_id');
	if ($content->getVar('content_supid') != 0) {
		$contentid_or_supid = 'content_supid';
		$contentid_or_supid_value = $content->getVar('content_supid');
	}
	redirect_header('admin.php?fct=content&op=list&'.$contentid_or_supid.'='.$contentid_or_supid_value,2,$msg);
}

function contmanager_editcontent($content_id) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=content', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}

	$err = array();

	$content_handler =& xoops_gethandler('content');
	$content = $content_handler->get($content_id);
	$content->setVars($_POST);
	$content->setVar('content_updated',time());

	if (!$content_handler->insert($content)){
		$msg = _MD_FAILEDIT;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	$gperm_handler =& xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo(new Criteria('gperm_name', 'content_read'));
	$criteria->add(new Criteria('gperm_itemid', $content_id));
	$gperm_handler->deleteAll($criteria);
	$criteria = new CriteriaCompo(new Criteria('gperm_name', 'content_admin'));
	$criteria->add(new Criteria('gperm_itemid', $content_id));
	$criteria->add(new Criteria('gperm_groupid', $content->getVar('content_uid')));
	$gperm_handler->deleteAll($criteria);
	if (isset($_POST['grupos_perm'])) {
		$groups = $_POST['grupos_perm'];
	} else {
		$groups = array();
	}
	$count = count($groups);
	for ($i = 0; $i < $count; $i++) {
		$gperm_handler->addRight('content_read', $content_id, $groups[$i]);
	}
	$gperm_handler->addRight('content_admin', $content->getVar('content_id'), $content->getVar('content_uid'));
	$contentid_or_supid = 'content_id';
	$contentid_or_supid_value = $content->getVar('content_id');
	if ($content->getVar('content_supid') != 0) {
		$contentid_or_supid = 'content_supid';
		$contentid_or_supid_value = $content->getVar('content_supid');
	}
	redirect_header('admin.php?fct=content&op=list&'.$contentid_or_supid.'='.$contentid_or_supid_value,2,$msg);
}

function contmanager_clonecontent() {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=content', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}

	$err = array();

	$content_handler =& xoops_gethandler('content');
	$content = $content_handler->create();
	$content->setVars($_POST);
	$content->setVar('content_created',time());
	$content->setVar('content_updated',time());

	if (!$content_handler->insert($content)){
		$msg = _MD_FAILCLONE;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	$gperm_handler =& xoops_gethandler('groupperm');
	$groups = $_POST['grupos_perm'];
	$count = count($groups);
	for ($i = 0; $i < $count; $i++) {
		$gperm_handler->addRight('content_read', $content->getVar('content_id'), $groups[$i]);
	}
	$gperm_handler->addRight('content_admin', $content->getVar('content_id'), $content->getVar('content_uid'));

	$contentid_or_supid = 'content_id';
	$contentid_or_supid_value = $content->getVar('content_id');
	if ($content->getVar('content_supid') != 0) {
		$contentid_or_supid = 'content_supid';
		$contentid_or_supid_value = $content->getVar('content_supid');
	}
	redirect_header('admin.php?fct=content&op=list&'.$contentid_or_supid.'='.$contentid_or_supid_value,2,$msg);
}

function contmanager_delcontent($content_id) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('admin.php?fct=content',1, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	if ($content_id <= 0) {
		redirect_header('admin.php?fct=content',1);
	}
	$content_handler = xoops_gethandler('content');
	$content =& $content_handler->get($content_id);
	if (!is_object($content)) {
		redirect_header('admin.php?fct=content',1);
	}

	$gperm_handler =& xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo(new Criteria('gperm_name', 'content_read'));
	$criteria->add(new Criteria('gperm_itemid', $content_id));
	$gperm_handler->deleteAll($criteria);

	$criteria = new CriteriaCompo(new Criteria('gperm_name', 'content_admin'));
	$criteria->add(new Criteria('gperm_itemid', $content_id));
	$criteria->add(new Criteria('gperm_groupid', $content->getVar('content_uid')));
	$gperm_handler->deleteAll($criteria);

	if (!$content_handler->delete($content)) {
		xoops_cp_header();
		xoops_error(sprintf(_MD_FAILDEL, $content->getVar('content_id')));
		xoops_cp_footer();
		exit();
	}

	redirect_header('admin.php?fct=content',2,_MD_AM_DBUPDATED);
}

function contmanager_changestatus($content_id) {
	$content_handler =& xoops_gethandler('content');
	$content = $content_handler->get($content_id);
	$content->setVar('content_status',!$content->getVar('content_status'));

	if (!$content_handler->insert($content)){
		$msg = _MD_FAILEDIT;
	}else{
		$msg = _MD_AM_DBUPDATED;
	}

	$contentid_or_supid = 'content_id';
	$contentid_or_supid_value = $content->getVar('content_id');
	if ($content->getVar('content_supid') != 0) {
		$contentid_or_supid = 'content_supid';
		$contentid_or_supid_value = $content->getVar('content_supid');
	}
	redirect_header('admin.php?fct=content&op=list&'.$contentid_or_supid.'='.$contentid_or_supid_value,2,$msg);
}

function contentform($id=null,$clone=false){
	global $xoopsUser,$xoopsConfig,$editor;
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$gperm_handler =& xoops_gethandler('groupperm');
	
	if (isset($id)){
		if ($clone){
			$ftitle = _MD_CLONECONTENT;
		}else{
		    $ftitle = _MD_EDITCONTENT;
		}
        $id = $id;
        $content_handler = xoops_gethandler('content');
        $content =& $content_handler->get($id);
        $title = $content->getVar('content_title');
        $menu = $content->getVar('content_menu');
		$body = $content->getVar('content_body','E');
		$css = $content->getVar('content_css','E');
		$weight = $content->getVar('content_weight');
		$status = $content->getVar('content_status');
		$visibility = $content->getVar('content_visibility');
		$content_supid = $content->getVar('content_supid');
		$content_uid = $content->getVar('content_uid');
		$grupos_ids = $gperm_handler->getGroupIds('content_read', $id);
	}else{
		if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
		$ftitle = _MD_ADDCONTENT;
		$title = '';
		$menu = '';
		$body = '';
		$css = file_get_contents(XOOPS_ROOT_PATH.'/modules/system/admin/content/style_rtl.css');
		$weight = 0;
		$status = 1;
		$visibility = 3;
		global $content_supid;
		$content_uid = $xoopsUser->getVar('uid');
		$grupos_ids = $xoopsUser->getGroups();
		if (!in_array(XOOPS_GROUP_ANONYMOUS, $grupos_ids)) {
			array_push($grupos_ids, XOOPS_GROUP_ANONYMOUS);
		}
	   } else {
		$ftitle = _MD_ADDCONTENT;
		$title = '';
		$menu = '';
		$body = '';
		$css = file_get_contents(XOOPS_ROOT_PATH.'/modules/system/admin/content/style.css');
		$weight = 0;
		$status = 1;
		$visibility = 3;
		global $content_supid;
		$content_uid = $xoopsUser->getVar('uid');
		$grupos_ids = $xoopsUser->getGroups();
		if (!in_array(XOOPS_GROUP_ANONYMOUS, $grupos_ids)) {
			array_push($grupos_ids, XOOPS_GROUP_ANONYMOUS);
		}
           }
	}

	$form = new XoopsThemeForm($ftitle, 'content_form', 'admin.php', "post", true);

	$content_handler = xoops_gethandler('content');
	
	$form->addElement(new XoopsFormSelectEditor($form,"editor",$editor));
	
	$parent = new XoopsFormSelect(_MD_CONTENT_PARENT, "content_supid", $content_supid);
	$parent->addOptionArray($content_handler->getContentList());
	$form->addElement($parent);
	
	$form->addElement(new XoopsFormSelectUser(_MD_CONTENT_AUTOR, "content_uid", false, $content_uid));
	
	$form->addElement(new XoopsFormText(_MD_CONTENT_TITLE, 'content_title', 50, 255,$title), true);
	$fmenu = new XoopsFormText(_MD_CONTENT_MENU, 'content_menu', 50, 100,$menu);
	$fmenu->setDescription(_MD_CONTENT_MENU_DSC);
	$form->addElement($fmenu, true);
	if (!is_null($editor)){
		$form->addElement(new XoopsFormDhtmlTextArea(_MD_CONTENT_BODY, 'content_body',$body,30,70,"xoopsHiddenText",array('editor'=>$editor)),true);
	}else{
		$form->addElement(new XoopsFormDhtmlTextArea(_MD_CONTENT_BODY, 'content_body',$body,30,70,"xoopsHiddenText",array('editor'=>$xoopsConfig['editor_default'])),true);
	}
	$fcss = new XoopsFormTextArea(_MD_CONTENT_CSS, 'content_css',$css,10);
	$fcss->setDescription(sprintf(_MD_CONTENT_CSS_DESC,XOOPS_URL.'/modules/system/language/'.$xoopsConfig['language'].'/admin/content_css_doc.html'));
	$form->addElement($fcss);
	$form->addElement(new XoopsFormText(_MD_CONTENT_WEIGHT, 'content_weight', 3, 4, $weight));
	$form->addElement(new XoopsFormRadioYN(_MD_CONTENT_DISPLAY, 'content_status', intval($status), _YES, _NO));

	$visibility_select = new XoopsFormSelect(_MD_CONTENT_VISIBILITY, "content_visibility",$visibility);
	$visibility_select->addOptionArray(array(1=>_MD_CONTENT_VISIBILITY_1, 2=>_MD_CONTENT_VISIBILITY_2, 3=>_MD_CONTENT_VISIBILITY_3, 4=>_MD_CONTENT_VISIBILITY_4));
	$form->addElement($visibility_select);

	$perm_grupos_select = new XoopsFormSelectGroup(_MD_CONTENT_PERMGROUPS, 'grupos_perm', true, $grupos_ids, 5, true);
	$form->addElement($perm_grupos_select);

	$tray = new XoopsFormElementTray('' ,'');
	$tray->addElement(new XoopsFormButton('', 'content_button', _SUBMIT, 'submit'));

	$btn = new XoopsFormButton('', 'reset', _CANCEL, 'button');
	if (isset($id)){
		$btn->setExtra('onclick="document.location.href=\'admin.php?fct=content&op=list&content_supid='.$content_supid.'\'"');
	}else{
		$btn->setExtra('onclick="document.getElementById(\'addcontform\').style.display = \'none\'; return false;"');
	}
	$tray->addElement($btn);
	$form->addElement($tray);
	
	$form->addElement(new XoopsFormHidden('fct', 'content'));
	if (isset($id)){
		if ($clone){
			$form->addElement(new XoopsFormHidden('op', 'clonecontentok'));
		}else{
			$form->addElement(new XoopsFormHidden('op', 'editcontentok'));
			$form->addElement(new XoopsFormHidden('content_id', $id));
		}
	}else{
		$form->addElement(new XoopsFormHidden('op', 'addcontent'));
	}

	return $form->render();
}

/**
 * Function to create a navigation menu in content pages.
 * This function was based on the function that do the same in mastop publish module
 * 
 * @param integer $id
 * @param string $separador
 * @param string $style
 * @return string
 */
function adminNav($id = null, $separador = "/", $style="style='font-weight:bold'"){
	$admin_url = XOOPS_URL."/modules/system/admin.php?fct=content";
	if ($id == false) {
		return false;
	}else{
		if ($id > 0) {
			$content_handler =& xoops_gethandler('content');
			$cont = $content_handler->get($id);
			if ($cont->getVar('content_id') > 0) {
				$ret = "<a href='".$admin_url."&content_supid=".$cont->getVar('content_id')."'>".$cont->getVar('content_title')."</a>";
				if ($cont->getVar('content_supid') == 0) {
					return "<a href='".$admin_url."&content_supid=0'>"._MD_CONTENT_NAVADM."</a> $separador ".$ret;
				}elseif ($cont->getVar('content_supid') > 0){
					$ret = adminNav($cont->getVar('content_supid'), $separador)." $separador ". $ret;
				}
			}
		}else{
			return false;
		}
	}
	return $ret;
}
?>