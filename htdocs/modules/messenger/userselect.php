<?php
// $Id: userselect.php 184 2006-01-22 22:34:51Z skalpa $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

// Where to locate the file? Member search should be restricted
// Limitation: Only work with javascript enabled 
include "header.php";
require_once ("include/functions.php");
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
   xoops_header();

if ($xoopsUser) { 

$start = (isset($_GET['start']))?$_GET['start']:0;
$_REQUEST['group'] = empty($_REQUEST['group'])?0:$_REQUEST['group'];
$_REQUEST['rank'] = empty($_REQUEST['rank'])?0:$_REQUEST['rank'];
$_REQUEST['searchText'] = isset($_REQUEST['searchText'])?trim($_REQUEST['searchText']):"";
$limit = 200;
$size = isset($_REQUEST['multiple']) && $_REQUEST['multiple'] ? 20 : 1;
$valid_subjects = array("uname"=> _MP_SEARCH_UNAME, "name"=>_MP_SEARCH_NAME, "email"=>_MP_SEARCH_EMAIL);

$name_parent = $_REQUEST["target"];
$name_current = 'users';
echo $js_adduser='
	<script type="text/javascript">
	var multiple='.intval($_REQUEST['multiple']).';
	function addusers(){
        var sel_current = xoopsGetElementById("'.$name_current.'");
        var sel_str="";
        var num = 0;
		for (var i = 0; i < sel_current.options.length; i++) {
			if (sel_current.options[i].selected) {
				var len=sel_current.options[i].text.length+sel_current.options[i].value.length;
				sel_str +=len+":"+sel_current.options[i].value+":"+sel_current.options[i].text;
				num ++;
			}
		}
		if(num==0) {
			if(multiple==0){ 
				window.close();
			}
			return false;
		}
		sel_str = num+":"+sel_str;
        window.opener.addusers(sel_str);
		if(multiple==0){ 
			window.close();
			window.opener.focus();
		}
        return true;
	}
	</script>
';

$member_handler = &xoops_gethandler('member');


if(empty($_REQUEST["action"])){
	$form_sel = new XoopsThemeForm(_MP_USEARCH, "selectusers", xoops_getenv('PHP_SELF'));
	
	$sel_box = new XoopsFormSelect(_MP_SEARCHBY, 'subject', empty($_REQUEST['subject'])?NULL:$_REQUEST['subject']);
	$sel_box->addOptionArray($valid_subjects);
	$form_sel->addElement($sel_box);
	
	$searchtext = new XoopsFormText(_MP_SEARCH_TEXT, 'searchText', 60, 255, empty($_REQUEST['searchText'])?NULL:$_REQUEST['searchText']);
	$searchtext->setDescription(_MP_SEARCH_TEXT_DESC);
	$form_sel->addElement($searchtext);
	
	$close_button = new XoopsFormButton('', '', _CLOSE, 'button');
	$close_button->setExtra('onclick="window.close()"') ;
	
	$button_tray = new XoopsFormElementTray("");
	$button_tray->addElement(new XoopsFormButton('', 'search', _SEARCH, 'submit'));
	$button_tray->addElement($close_button);
	
	$form_sel->addElement(new XoopsFormHidden('action', $_REQUEST["action"]));
	$form_sel->addElement(new XoopsFormHidden('target', $_REQUEST["target"]));
	$form_sel->addElement(new XoopsFormHidden('multiple', $_REQUEST["multiple"]));
	$form_sel->addElement($button_tray);
	$form_sel->display();   
	     
}

if($_REQUEST["action"] == '1' ||!empty($_REQUEST["search"])){
    $form_user = new XoopsThemeForm(_MP_SEARCH_SELECTUSER, "selectusers", xoops_getenv('PHP_SELF'));

    $myts =& MyTextSanitizer::getInstance();
    $criteria = new CriteriaCompo();
   
	if(!empty($_REQUEST['search'])){
	    $text = empty($_REQUEST['searchText'])?"%":$myts->addSlashes(trim($_REQUEST['searchText']));
	    $subject = in_array($_REQUEST['subject'], array_keys($valid_subjects))?trim($_REQUEST['subject']):"uname";
	    $crit = new Criteria($subject, $text, 'LIKE');
		$criteria->add($crit);
	    $sort = $subject;
	    $nav_extra =
	    	"action=".$_REQUEST["action"]."&amp;target=".$_REQUEST["target"]."&amp;multiple=".$_REQUEST["multiple"].
	    	"&amp;searchText=".$_REQUEST['searchText']."&amp;search=1&amp;subject=".$_REQUEST['subject'];
    }else{
	    $crit = null;
	    $sort = "uname";
	    $nav_extra =
    		"action=".$_REQUEST["action"]."&amp;target=".$_REQUEST["target"]."&amp;multiple=".$_REQUEST["multiple"].
	    	"&amp;group=".$_REQUEST['group']."&amp;rank=".$_REQUEST['rank'];
		if(!empty($_REQUEST["group"])){
			$uids = $member_handler->getUsersByGroup(intval($_REQUEST["group"]), false, $limit, $start);
		    $id_in = "(".implode(",", $uids).")";
	    	$crit = new Criteria("uid", $id_in, "IN");
			$criteria->add($crit);
    		$usercount = $member_handler->getUserCountByGroup(intval($_REQUEST["group"]));
		}elseif(!empty($_REQUEST["rank"])){
	    	$crit = new Criteria("rank", intval($_REQUEST["rank"]));
			$criteria->add($crit);
		}
    }
    
    $criteria->setSort($sort);
    $criteria->setLimit($limit);
    $criteria->setStart($start);
    $select_form = new XoopsFormSelect("", "users", array(), 1, false);		
    $select_form->addOptionArray($member_handler->getUserList($criteria));
    
    $user_select_tray = new XoopsFormElementTray(_MP_SEARCH_USERLIST, "<br />");
    $user_select_tray->addElement($select_form);
    $usercount = isset($usercount)? $usercount : $member_handler->getUserCount($crit);
    $nav = new XoopsPageNav($usercount, $limit, $start, "start", $nav_extra);
    $user_select_nav = new XoopsFormLabel(sprintf(_MP_SEARCH_COUNT, $usercount), $nav->renderNav(4));
    $user_select_tray->addElement($user_select_nav);
    
    $add_button = new XoopsFormButton('', '', _ADD, 'button');
	$add_button->setExtra('onclick="addusers();"') ;

    $close_button = new XoopsFormButton('', '', _CLOSE, 'button');
	$close_button->setExtra('onclick="window.close()"') ;

    $button_tray = new XoopsFormElementTray("");
    $button_tray->addElement($add_button);
    $button_tray->addElement(new XoopsFormButton('', '', _CANCEL, 'reset'));
    $button_tray->addElement($close_button);

    $form_user->addElement($user_select_tray);
    
    $form_user->addElement(new XoopsFormHidden('action', $_REQUEST["action"]));
    $form_user->addElement(new XoopsFormHidden('target', $_REQUEST["target"]));
    $form_user->addElement(new XoopsFormHidden('multiple', $_REQUEST["multiple"]));
    $form_user->addElement($button_tray);
    $form_user->display();        
}

    if($_REQUEST["action"] == '2'){
	$form_user = new XoopsThemeForm(_MP_SEARCH_SELECTUSER, "selectusers", xoops_getenv('PHP_SELF'));
    $cont_handler  =& xoops_gethandler('priv_msgscont');
    $ranks =& $cont_handler->getList($cont_handler);  
    $rank_select = new XoopsFormSelect(_MP_CTT, 'users', '');
    $rank_select->addOptionArray($ranks);
   $form_user->addElement($rank_select);	    
    
	$add_button = new XoopsFormButton('', '', _ADD, 'button');
	$add_button->setExtra('onclick="addusers();"') ;

    $close_button = new XoopsFormButton('', '', _CLOSE, 'button');
	$close_button->setExtra('onclick="window.close()"') ;

    $button_tray = new XoopsFormElementTray("");
    $button_tray->addElement($add_button);
    $button_tray->addElement(new XoopsFormButton('', '', _CANCEL, 'reset'));
    $button_tray->addElement($close_button);
		
    $form_user->addElement(new XoopsFormHidden('action', $_REQUEST["action"]));
    $form_user->addElement(new XoopsFormHidden('target', $_REQUEST["target"]));
    $form_user->addElement(new XoopsFormHidden('multiple', $_REQUEST["multiple"]));
    $form_user->addElement($button_tray);
    $form_user->display();        
}

} else {
    echo _PM_SORRY."<br /><br /><a href='".XOOPS_URL."/register.php'>"._PM_REGISTERNOW."</a>.";
}
    xoops_footer();
?>