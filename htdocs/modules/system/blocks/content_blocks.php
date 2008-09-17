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
* @version		$Id: content_blocks.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

function b_content_menu_show($options) {
    global $xoopsUser;

    $block = array();

    $gperm_handler = & xoops_gethandler( 'groupperm' );
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $content_handler =& xoops_gethandler('content');
    
    $block['showsubs'] = $options[2];
    $block['selcolor'] = $options[3];
    $block['menu'] = getPages($options[2],$options[0],$options[1],$options[4]);

    return $block;
}

function b_content_menu_edit($options){
    $chk = "";
	$form = _MB_SYSTEM_SORT.': <select name="options[0]">';
    if ( $options[0] == 'content_weight' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="content_weight"'.$chk.'>weight</option>';
    if ( $options[0] == 'content_title' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="content_title"'.$chk.'>title</option>';
	$form .= '</select><br />';
    $chk = "";
	$form .= _MB_SYSTEM_ORDER.': <select name="options[1]">';
    if ( $options[1] == 'ASC' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="ASC"'.$chk.'>ASC</option>';
    if ( $options[1] == 'DESC' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="DESC"'.$chk.'>DESC</option>';
	$form .= '</select><br />';
    $chk = "";
    $form .= _MB_SYSTEM_SHOWSUBS."&nbsp;";
    if ( $options[2] == 1 ) {
        $chk = " checked='checked'";
    }
    $form .= "<input type='radio' name='options[2]' value='1'".$chk." />&nbsp;"._YES;
    $chk = "";
    if ( $options[2] == 0 ) {
        $chk = ' checked="checked"';
    }
    $form .= '&nbsp;<input type="radio" name="options[2]" value="0"'.$chk.' />'._NO;
    $form .= '<br />'._MB_SYSTEM_SELCOLOR.'&nbsp;<input type="text" name="options[3]" value="'.$options[3].'" />';
    $form .= '<br />'._MB_SYSTEM_CONTID.'&nbsp;<input type="text" name="options[4]" value="'.$options[4].'" />';
    
    return $form;
}


function b_content_show($options) {
    global $xoopsUser;
    $myts =& MyTextSanitizer::getInstance();
    $block = array();

    $gperm_handler = & xoops_gethandler( 'groupperm' );
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $content_handler =& xoops_gethandler('content');
    $criteria = new CriteriaCompo(new Criteria('content_status', 1));
    $criteria->add(new Criteria('content_id', $options[0]));
    $impress_content = $content_handler->getObjects($criteria);
    $impress_content = $impress_content[0];

    if (!is_object($impress_content)){
    	return false;
    }
    $content_id = $impress_content->getVar('content_id');

    $viewperm  = $gperm_handler->checkRight('content_read', $content_id, $groups);         // $viewperm is true if user has permition to see this page
    $adminperm = $gperm_handler->checkRight('content_admin', $content_id, $uid);        // $adminperm is true if user has permition to admin this page

    if (!$viewperm){
    	return false;
    }
    
    $block["content_title"] = $impress_content->getVar('content_title');
    $block["isAdmin"] = $adminperm;
    $opts = '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=content&op=editcontent&content_id='.$impress_content->getVar('content_id').'"><img src="'.XOOPS_URL.'/modules/system/admin/content/images/edit_big.png" title="'._CT_EDIT_CONTENT.'" alt="'._CT_EDIT_CONTENT.'" /></a>';
    $opts .= '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=content&op=delcontent&content_id='.$impress_content->getVar('content_id').'"><img src="'.XOOPS_URL.'/modules/system/admin/content/images/delete_big.png" title="'._CT_DELETE_CONTENT.'" alt="'._CT_DELETE_CONTENT.'" /></a>';
    $block["content_admlinks"] = $opts;
    $member_handler =& xoops_gethandler('member');
    $autor =& $member_handler->getUser($impress_content->getVar('content_uid'));
    $block["show_pinfo"] = $options[3];
    $block["content_tinfo"] = sprintf(_CT_PUBLISHEDBY.' <a href="'.XOOPS_URL.'/userinfo.php?uid=%u">%s</a> '._CT_ON.' %s (%u '._CT_READS.')',$autor->getVar('uid'),$autor->getVar('uname'),formatTimestamp($impress_content->getVar('content_created'),"s"),$impress_content->getReads());
    $block["content_body"] = $myts->previewTarea($impress_content->getVar('content_body', "n"),1,1,1,1,0);
    $block["content_css"] = $impress_content->getVar('content_css');
    
    if ($options[1]){
    	$criteria = new Criteria('content_supid', $content_id);
    	$subs = $content_handler->getCount($criteria);
    	$subarr = array();
    	if ($subs > 0){
    		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
    		$criteria->add(new Criteria('content_supid', $content_id));
    		$subs = $content_handler->getObjects($criteria);
    		foreach ($subs as $sub){
    			$content_subs = array();
    			$content_subs['titulo'] = $sub->getVar("content_title");
    			$content_subs['teaser'] = icms_substr(icms_cleanTags($sub->getVar("content_body",'n')),0,225);
    			$seo = urlencode(str_replace(" ", "_",$sub->getVar('content_menu')));
    			$content_subs['link'] = XOOPS_URL.'/content.php?page='.$seo;
    			$subarr[] = $content_subs;
    		}
    		$block['content_subs'] = $subarr;
    		$block['showSubs'] = 1;
    		$block['subs_label'] = _CT_RELATEDS;
    	}
    }else{
    	$block['showSubs'] = 0;
    }
    $block['showNav'] = $options[2];
    $block['nav'] = showNav($content_id);

    return $block;
}

function b_content_edit($options){
    $content_handler =& xoops_gethandler('content');
    $impress_content = $content_handler->getContentList();

    $opts = '';
    foreach ($impress_content as $k=>$content){
    	$chk = "";
    	if ($options[0] == $k){
    		$chk = " selected='selected'";
    	}
    	$opts .= '<option value="'.$k.'"'.$chk.'>'.$content.'</option>';
    }
    $form = _MB_SYSTEM_PAGE.': <select name="options[0]">'.$opts.'</select>';
    $form .= '<br />'. _MB_SYSTEM_SHOWSUBS."&nbsp;";
    if ( $options[1] == 1 ) {
        $chk = " checked='checked'";
    }
    $form .= "<input type='radio' name='options[1]' value='1'".$chk." />&nbsp;"._YES;
    $chk = "";
    if ( $options[1] == 0 ) {
        $chk = ' checked="checked"';
    }
    $form .= '&nbsp;<input type="radio" name="options[1]" value="0"'.$chk.' />'._NO;
    $form .= '<br />'. _MB_SYSTEM_SHOWNAV."&nbsp;";
    if ( $options[2] == 1 ) {
        $chk = " checked='checked'";
    }
    $form .= "<input type='radio' name='options[2]' value='1'".$chk." />&nbsp;"._YES;
    $chk = "";
    if ( $options[2] == 0 ) {
        $chk = ' checked="checked"';
    }
    $form .= '&nbsp;<input type="radio" name="options[2]" value="0"'.$chk.' />'._NO;
    $form .= '<br />'. _MB_SYSTEM_SHOWPINFO."&nbsp;";
    if ( $options[3] == 1 ) {
        $chk = " checked='checked'";
    }
    $form .= "<input type='radio' name='options[3]' value='1'".$chk." />&nbsp;"._YES;
    $chk = "";
    if ( $options[3] == 0 ) {
        $chk = ' checked="checked"';
    }
    $form .= '&nbsp;<input type="radio" name="options[3]" value="0"'.$chk.' />'._NO;
    
    return $form;
}

function b_content_relmenu_show($options) {
    global $xoopsUser;

    $block = array();

    $gperm_handler = & xoops_gethandler( 'groupperm' );
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $content_handler =& xoops_gethandler('content');
    $content_id = isset($_GET['page'])?$_GET['page']:0;
    
    $block['showsubs'] = $options[2];
    $menu = getPages($options[2],$options[0],$options[1],$content_id,1);
    $block['menu'] = isset($menu[0]['subs'])?$menu[0]['subs']:array();
    
    return $block;
}

function b_content_relmenu_edit($options){
    $chk = "";
	$form = _MB_SYSTEM_SORT.': <select name="options[0]">';
    if ( $options[0] == 'content_weight' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="content_weight"'.$chk.'>weight</option>';
    if ( $options[0] == 'content_title' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="content_title"'.$chk.'>title</option>';
	$form .= '</select><br />';
    $chk = "";
	$form .= _MB_SYSTEM_ORDER.': <select name="options[1]">';
    if ( $options[1] == 'ASC' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="ASC"'.$chk.'>ASC</option>';
    if ( $options[1] == 'DESC' ) {
        $chk = " checked='checked'";
    }
    $form .= '<option value="DESC"'.$chk.'>DESC</option>';
	$form .= '</select><br />';
    $chk = "";
    $form .= _MB_SYSTEM_SHOWSUBS."&nbsp;";
    if ( $options[2] == 1 ) {
        $chk = " checked='checked'";
    }
    $form .= "<input type='radio' name='options[2]' value='1'".$chk." />&nbsp;"._YES;
    $chk = "";
    if ( $options[2] == 0 ) {
        $chk = ' checked="checked"';
    }
    $form .= '&nbsp;<input type="radio" name="options[2]" value="0"'.$chk.' />'._NO;

    return $form;
}

function getPages($showsubs = true, $sort='content_weight', $order='ASC', $content_id = 0, $relateds = 0 ) {
    global $xoopsUser;

    $gperm_handler = & xoops_gethandler( 'groupperm' );
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $content_handler =& xoops_gethandler('content');

    $criteria = new CriteriaCompo(new Criteria('content_status', 1));
    if (!$relateds){
    	$criteria->add(new Criteria('content_supid', $content_id));
    }else{
    	$criteria->add(new Criteria('content_menu', $content_id,'LIKE'));
    	$criteria->add(new Criteria('content_id', $content_id),'OR');
    }
    $crit = new CriteriaCompo(new Criteria('content_visibility', 1));
    $crit->add(new Criteria('content_visibility', 3),'OR');
    $criteria->add($crit);
    $criteria->setSort($sort);
    $criteria->setOrder($order);
    $impress_content = $content_handler->getObjects($criteria);
    
    $i = 0;
    $pages = array();
    foreach ($impress_content as $content){
    	if ($gperm_handler->checkRight('content_read', $content->getVar('content_id'), $groups)){
    		$pages[$i]['title'] = $content->getVar('content_title');
    		$pages[$i]['menu'] = $content_handler->makeLink($content);
    		if ($showsubs){
    			$subs = getPages($showsubs, $sort, $order, $content->getVar('content_id'));
    			if (count($subs) > 0){
    				$pages[$i]['hassubs'] = 1;
    				$pages[$i]['subs'] = $subs;
    			}else{
    				$pages[$i]['hassubs'] = 0;
    			}
    		}
    		$i++;
    	}
    }

    return $pages;
}

function b_content_tagmenu_show($options) {
    global $xoopsUser;

    $block = array();

    $content_handler =& xoops_gethandler('content');
    $tags = $content_handler->getTags();
    arsort($tags);
    $block['tags'] = $tags;

    return $block;
}
?>