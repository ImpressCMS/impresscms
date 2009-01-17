<?php
// $Id: blocksadmin.php 1029 2007-09-09 03:49:25Z phppp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}
// check if the user is authorised
if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
    include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

    # Adding dynamic block area/position system - TheRpLima - 2007-10-21
    $oldzones = XoopsBlock::getBlockPositions(true);
    #
    $editor = (isset($_GET['editor'])) ? StopXSS($_GET['editor']):null;
    function list_blocks(){
        global $xoopsUser, $xoopsConfig, $icmsAdminTpl, $oldzones,$editor;
        include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
        //OpenTable();
        $selmod = isset($_GET['selmod']) ? preg_replace('/[^0-9-]/','',$_GET['selmod']) : '0-1';
        $selvis = isset($_GET['selvis']) ? intval($_GET['selvis']) : 2;
        $selgrp = isset($_GET['selgrp']) ? intval($_GET['selgrp']) : XOOPS_GROUP_USERS;
              
        $form = "<select size=\"1\" name=\"selmod\" onchange=\"location='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;selvis=$selvis&amp;selgrp=$selgrp&amp;selmod='+this.options[this.selectedIndex].value\">";
        $module_handler =& xoops_gethandler('module');
        $page_handler =& xoops_gethandler('page');
        $form .= $page_handler->getPageSelOptions($selmod);
        $form .= '</select>&nbsp;<input type="hidden" name="fct" value="blocksadmin" />';
        $icmsAdminTpl->assign('selmod',sprintf(_AM_SVISIBLEIN, $form));

        $member_handler =& xoops_gethandler('member');
        $group_list =& $member_handler->getGroupList();
        $group_list[0] = '#'._AM_UNASSIGNED; // fix for displaying blocks unassigned to any group
        
        $sgrp = new XoopsFormSelect(_AM_GROUP, "selgrp", $selgrp);
        $sgrp->addOptionArray($group_list);
        $sgrp->setExtra('onchange="location=\''.XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin&amp;selvis='.$selvis.'&amp;selmod='.$selmod.'&amp;selgrp=\'+this.options[this.selectedIndex].value";');
        $icmsAdminTpl->assign('selgrp',$sgrp->render());
        
        $svis = new XoopsFormSelect(_AM_VISIBLE, "selvis", $selvis);
        $svis->addOptionArray(array(_NO,_YES,_ALL));
        $svis->setExtra('onchange="location=\''.XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin&amp;selgrp='.$selgrp.'&amp;selmod='.$selmod.'&amp;selvis=\'+this.options[this.selectedIndex].value";');
        $icmsAdminTpl->assign('selvis',$svis->render());
        
        if ($selvis == 2) $selvis = null;
        if ($selgrp == 0) {// get blocks that are not assigned to any groups            
            $block_arr =& XoopsBlock::getNonGroupedBlocks($selmod, false, $selvis, 'b.side,b.weight,b.bid');
        } else {
            $block_arr =& XoopsBlock::getAllByGroupModule($selgrp, $selmod, false, $selvis, 'b.side,b.weight,b.bid');
        }
        $block_count = count($block_arr);

        $module_list2 =& $module_handler->getList();
        // for custom blocks
        $module_list2[0] = '&nbsp;';

        $block_configs = get_block_configs() ;

        foreach (array_keys($block_arr) as $i) {
        	$side = new XoopsFormSelect('', "side[$i]", $block_arr[$i]->getVar("side"),3);
            foreach (array_keys($oldzones) as $j){
            	$side->addOption($j,(defined($oldzones[$j]['title']))?constant($oldzones[$j]['title']):$oldzones[$j]['title']);
            }
            $weight = new XoopsFormText('', "weight[$i]", 2, 5, $block_arr[$i]->getVar("weight"));
        	$block = array();
        	$block['bid'] = $block_arr[$i]->getVar("bid");
        	$block['name'] = $block_arr[$i]->getVar("name");
        	$block['title'] = $block_arr[$i]->getVar("title");
        	$block['module'] = $module_list2[$block_arr[$i]->getVar('mid')];
        	$block['weightf'] = $weight->render();
        	$block['weight'] = $block_arr[$i]->getVar("weight");
        	$block['block_type'] = $block_arr[$i]->getVar("block_type");
        	$block['sidef'] = $side->render();
        	$block['side'] = $block_arr[$i]->getVar("side");
        	$block['visible'] = $block_arr[$i]->getVar("visible");
        	$icmsAdminTpl->append('blocks',$block);
        }
        
        $icmsAdminTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());
        
        $block = array('form_title' => _AM_ADDBLOCK, 'side' => 0, 'weight' => 0, 'visible' => 1, 'title' => '', 'content' => '', 'modules' => array('0-1'), 'is_custom' => true, 'ctype' => 'H', 'cachetime' => 0, 'op' => 'save', 'edit_form' => false);
        include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
        $icmsAdminTpl->assign('addform',$form->render());
        
        $icmsAdminTpl->assign('lang_bpadmin',_AM_BPADMIN);
        $icmsAdminTpl->assign('lang_badmin',_AM_BADMIN);
        $icmsAdminTpl->assign('lang_addnew',_AM_ADDBLOCK);
        $icmsAdminTpl->assign('lang_submit',_SUBMIT);
        $icmsAdminTpl->assign('lang_go',_GO);
        $icmsAdminTpl->assign('lang_svisiblein',_AM_SVISIBLEIN);
        $icmsAdminTpl->assign('lang_group',_AM_GROUP);
        $icmsAdminTpl->assign('lang_visible',_AM_VISIBLE);
        $icmsAdminTpl->assign('lang_name',_AM_BLKDESC);
        $icmsAdminTpl->assign('lang_title',_AM_TITLE);
        $icmsAdminTpl->assign('lang_module',_AM_MODULE);
        $icmsAdminTpl->assign('lang_side',_AM_SIDE);
        $icmsAdminTpl->assign('lang_weight',_AM_WEIGHT);
        $icmsAdminTpl->assign('lang_visible',_AM_VISIBLE);
        $icmsAdminTpl->assign('lang_action',_AM_ACTION);
        $icmsAdminTpl->assign('lang_delete',_DELETE);
        $icmsAdminTpl->assign('lang_edit',_EDIT);
        $icmsAdminTpl->assign('lang_clone',_CLONE);
        $icmsAdminTpl->assign('lang_changests',_AM_CHANGESTS);
                
        $icmsAdminTpl->assign('addformsts',(!is_null($editor))?'block':'none');

        return $icmsAdminTpl->fetch('db:admin/blocksadmin/system_adm_blocksadmin.html');
    }

    function save_block($bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bmodule, $bcachetime)
    {
        global $xoopsUser;
        if (empty($bmodule)) {
            xoops_cp_header();
            xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
            xoops_cp_footer();
            exit();
        }
        $myblock = new XoopsBlock();
        $myblock->setVar('side', $bside);
        $myblock->setVar('weight', $bweight);
        $myblock->setVar('visible', $bvisible);
        $myblock->setVar('weight', $bweight);
        $myblock->setVar('title', $btitle);
        $myblock->setVar('content', $bcontent);
        $myblock->setVar('c_type', $bctype);
        $myblock->setVar('block_type', 'C');
        $myblock->setVar('bcachetime', $bcachetime);
        switch ($bctype) {
        case 'H':
            $name = _AM_CUSTOMHTML;
            break;
        case 'P':
            $name = _AM_CUSTOMPHP;
            break;
        case 'S':
            $name = _AM_CUSTOMSMILE;
            break;
        default:
            $name = _AM_CUSTOMNOSMILE;
            break;
        }
        $myblock->setVar('name', $name);
        $newid = $myblock->store();
        if (!$newid) {
            xoops_cp_header();
            $myblock->getHtmlErrors();
            xoops_cp_footer();
            exit();
        }
        $db =& Database::getInstance();
        foreach ($bmodule as $bmid) {
            $page = explode('-', $bmid);
            $mid = $page[0];
            $pageid = $page[1];
            $sql = "INSERT INTO ".$db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('".intval($newid)."', '".intval($mid)."', '".intval($pageid)."')";
            $db->query($sql);
        }
		$groups = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
        $count = count($groups);
        for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_name, gperm_modid) VALUES ('".$groups[$i]."', '".intval($newid)."', 'block_read', '1')";
            $db->query($sql);
        }
        redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
        exit();
    }

    function edit_block($bid)
    {
        $myblock = new XoopsBlock($bid);
        $db =& Database::getInstance();
        $sql = "SELECT module_id,page_id FROM ".$db->prefix('block_module_link')." WHERE block_id='".intval($bid)."'";
        $result = $db->query($sql);
        $modules = array();
        while ($row = $db->fetchArray($result)) {
            $modules[] = intval($row['module_id']).'-'.intval($row['page_id']);
        }
        $is_custom = ($myblock->getVar('block_type') == 'C' || $myblock->getVar('block_type') == 'E') ? true : false;
        $block = array('form_title' => _AM_EDITBLOCK, 'name' => $myblock->getVar('name'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'title' => $myblock->getVar('title', 'E'), 'content' => $myblock->getVar('content', 'E'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'update', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'));
        echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_EDITBLOCK.'<br /><br />';
        include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
        $form->display();
    }

    function update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options=array())
    {
        global $xoopsConfig;
        if (empty($bmodule)) {
            xoops_cp_header();
            xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
            xoops_cp_footer();
            exit();
        }
        $myblock = new XoopsBlock($bid);
        $myblock->setVar('side', $bside);
        $myblock->setVar('weight', $bweight);
        $myblock->setVar('visible', $bvisible);
        $myblock->setVar('title', $btitle);
        $myblock->setVar('content', $bcontent);
        $myblock->setVar('bcachetime', $bcachetime);
        if (isset($options)) {
            $options_count = count($options);
            if ($options_count > 0) {
                //Convert array values to comma-separated
                for ( $i = 0; $i < $options_count; $i++ ) {
                    if (is_array($options[$i])) {
                        $options[$i] = implode(',', $options[$i]);
                    }
                }
                $options = implode('|', $options);
                $myblock->setVar('options', $options);
            }
        }
        if ($myblock->getVar('block_type') == 'C') {
            switch ($bctype) {
            case 'H':
                $name = _AM_CUSTOMHTML;
                break;
            case 'P':
                $name = _AM_CUSTOMPHP;
                break;
            case 'S':
                $name = _AM_CUSTOMSMILE;
                break;
            default:
                $name = _AM_CUSTOMNOSMILE;
                break;
            }
            $myblock->setVar('name', $name);
            $myblock->setVar('c_type', $bctype);
        } else {
            $myblock->setVar('c_type', 'H');
        }
        $msg = _AM_DBUPDATED;
        if ($myblock->store() != false) {
            $db =& Database::getInstance();
            $sql = sprintf("DELETE FROM %s WHERE block_id = '%u'", $db->prefix('block_module_link'), intval($bid));
            $db->query($sql);
            foreach ($bmodule as $bmid) {
            	$page = explode('-', $bmid);
            	$mid = $page[0];
            	$pageid = $page[1];
            	$sql = "INSERT INTO ".$db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('".intval($bid)."', '".intval($mid)."', '".intval($pageid)."')";
            	$db->query($sql);
            }
            include_once XOOPS_ROOT_PATH.'/class/template.php';
            $xoopsTpl = new XoopsTpl();
            $xoopsTpl->xoops_setCaching(2);
            if ($myblock->getVar('template') != '') {
                if ($xoopsTpl->is_cached('db:'.$myblock->getVar('template'), 'blk_'.$myblock->getVar('bid'))) {
                    if (!$xoopsTpl->clear_cache('db:'.$myblock->getVar('template'), 'blk_'.$myblock->getVar('bid'))) {
                        $msg = 'Unable to clear cache for block ID '.$bid;
                    }
                }
            } else {
                if ($xoopsTpl->is_cached('db:system_dummy.html', 'blk_'.$bid)) {
                    if (!$xoopsTpl->clear_cache('db:system_dummy.html', 'blk_'.$bid)) {
                        $msg = 'Unable to clear cache for block ID '.$bid;
                    }
                }
            }
        } else {
            $msg = 'Failed update of block. ID:'.$bid;
        }
        redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,$msg);
    }

    function delete_block($bid)
    {
        $myblock = new XoopsBlock($bid);
        if ( $myblock->getVar('block_type') == 'S' ) {
            $message = _AM_SYSTEMCANT;
            redirect_header('admin.php?fct=blocksadmin',4,$message);
            exit();
        } elseif ($myblock->getVar('block_type') == 'M') {
            // Fix for duplicated blocks created in 2.0.9 module update
            // A module block can be deleted if there is more than 1 that
            // has the same func_num/show_func which is mostly likely
            // be the one that was duplicated in 2.0.9
            if (1 >= $count = XoopsBlock::countSimilarBlocks($myblock->getVar('mid'), $myblock->getVar('func_num'), $myblock->getVar('show_func'))) {
                $message = _AM_MODULECANT;
                redirect_header('admin.php?fct=blocksadmin',4,$message);
            }
        }
        xoops_confirm(array('fct' => 'blocksadmin', 'op' => 'delete_ok', 'bid' => $myblock->getVar('bid')), 'admin.php', sprintf(_AM_RUSUREDEL,$myblock->getVar('title')));
    }

    function delete_block_ok($bid)
    {
        $myblock = new XoopsBlock($bid);
        $myblock->delete();
        if ($myblock->getVar('template') != '') {
            $tplfile_handler =& xoops_gethandler('tplfile');
            $btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
            if (count($btemplate) > 0) {
                $tplfile_handler->delete($btemplate[0]);
            }
        }
        redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
        exit();
    }

    function order_block($bid, $weight, $side)
    {
        $myblock = new XoopsBlock($bid);
        $myblock->setVar('weight', $weight);
        $myblock->setVar('side', $side);
        $myblock->store();
    }
    
    function changests_block($bid,$sts)
    {
        $myblock = new XoopsBlock($bid);
        if ($sts == 0){
        	$vis = 1;
        }else{
        	$vis = 0;
        }
        $myblock->setVar('visible', $vis);
        $myblock->store();
    }

    function clone_block($bid)
    {
        global $xoopsConfig;
        xoops_cp_header();
        $myblock = new XoopsBlock($bid);
        $db =& Database::getInstance();
        $sql = "SELECT module_id,page_id FROM ".$db->prefix('block_module_link')." WHERE block_id='".intval($bid)."'";
        $result = $db->query($sql);
        $modules = $bcustomp = array();
        while ($row = $db->fetchArray($result)) {
            $modules[] = intval($row['module_id']).'-'.intval($row['page_id']);
        }
        $is_custom = ($myblock->getVar('block_type') == 'C' || $myblock->getVar('block_type') == 'E') ? true : false;
        $block = array('form_title' => _AM_CLONEBLOCK, 'name' => $myblock->getVar('name'), 'title' => 'clone_'.$myblock->getVar('title'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'content' => $myblock->getVar('content', 'N'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'clone_ok', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'));
        echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_CLONEBLOCK.'<br /><br />';
        include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
        $form->display();
        xoops_cp_footer();
        exit();
    }

    function clone_block_ok($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bcachetime, $bmodule, $options)
    {
        global $xoopsUser;
        $block = new XoopsBlock($bid);
        $clone =& $block->xoopsClone();
        if (empty($bmodule)) {
            xoops_cp_header();
            xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
            xoops_cp_footer();
            exit();
        }
        $clone->setVar('side', $bside);
        $clone->setVar('weight', $bweight);
        $clone->setVar('visible', $bvisible);
        $clone->setVar('content', $bcontent);
        $clone->setVar('title', $btitle);
        $clone->setVar('bcachetime', $bcachetime);
        if ( isset($options) && (count($options) > 0) ) {
            $options = implode('|', $options);
            $clone->setVar('options', $options);
        }
        $clone->setVar('bid', 0);
        if ($block->getVar('block_type') == 'C' || $block->getVar('block_type') == 'E') {
            $clone->setVar('block_type', 'E');
        } else {
            $clone->setVar('block_type', 'D');
        }
        $newid = $clone->store();
        if (!$newid) {
            xoops_cp_header();
            $clone->getHtmlErrors();
            xoops_cp_footer();
            exit();
        }

        $db =& Database::getInstance();
        foreach ($bmodule as $bmid) {
            $page = explode('-', $bmid);
            $mid = $page[0];
            $pageid = $page[1];
            $sql = "INSERT INTO ".$db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('".intval($newid)."', '".intval($mid)."', '".intval($pageid)."')";
            $db->query($sql);
        }
        $groups =& $xoopsUser->getGroups();
        $count = count($groups);
        for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES ('".$groups[$i]."', '".intval($newid)."', '1', 'block_read')";
            $db->query($sql);
        }
        redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
    }
    
    function get_block_configs()
    {
    	$error_reporting_level = error_reporting( 0 ) ;
    	include '../xoops_version.php' ;
    	error_reporting( $error_reporting_level ) ;
    	if( empty( $modversion['blocks'] ) ) return array() ;
    	else return $modversion['blocks'] ;
    }

} else {
    echo "Access Denied";
}
?>
