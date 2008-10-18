<?php
// $Id: main.php 2 2005-11-02 18:23:29Z skalpa $
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
include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
include XOOPS_ROOT_PATH."/modules/system/admin/blocksadmin/blocksadmin.php";

$allowedHTML = array('bcontent');

if(!empty($_POST)){ foreach($_POST as $k => $v){ if (!in_array($k,$allowedHTML)){${$k} = StopXSS($v);}else{${$k} = $v;}}}
if(!empty($_GET)){ foreach($_GET as $k => $v){ if (!in_array($k,$allowedHTML)){${$k} = StopXSS($v);}else{${$k} = $v;}}}

$op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'list');
if($op == 'edit' || $op == 'delete' || $op == 'delete_ok' || $op == 'clone' || $op == 'adminpblocks')
{
  $bid = (isset($_GET['bid']))?intval($_GET['bid']):((isset($_POST['bid']))?intval($_POST['bid']):0);
}

if (isset($previewblock)) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=blocksadmin", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    xoops_cp_header();
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->xoops_setCaching(0);
    if (isset($bid)) {
        $block['bid'] = $bid;
        $block['form_title'] = _AM_EDITBLOCK;
        $myblock = new XoopsBlock($bid);
        $block['name'] = $myblock->getVar('name');
    } else {
        if ($op == 'save') {
            $block['form_title'] = _AM_ADDBLOCK;
        } else {
            $block['form_title'] = _AM_CLONEBLOCK;
        }
        $myblock = new XoopsBlock();
        $myblock->setVar('block_type', 'C');
    }
    $myts =& MyTextSanitizer::getInstance();
    $myblock->setVar('title', $myts->stripSlashesGPC($btitle));
    $myblock->setVar('content', $myts->stripSlashesGPC($bcontent));
    $dummyhtml = '<html><head><meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" /><meta http-equiv="content-language" content="'._LANGCODE.'" /><title>'.$xoopsConfig['sitename'].'</title><link rel="stylesheet" type="text/css" media="all" href="'.getcss($xoopsConfig['theme_set']).'" /></head><body><table><tr><th>'.$myblock->getVar('title').'</th></tr><tr><td>'.$myblock->getContent('S', $bctype).'</td></tr></table></body></html>';

    $block['edit_form'] = false;
    $block['template'] = '';
    $block['op'] = $op;
    $block['side'] = $bside;
    $block['weight'] = $bweight;
    $block['visible'] = $bvisible;
    $block['title'] = $myblock->getVar('title', 'E');
    $block['content'] = $myblock->getVar('content', 'E');
    $block['modules'] =& $bmodule;
    $block['ctype'] = isset($bctype) ? $bctype : $myblock->getVar('c_type');
    $block['is_custom'] = true;
    $block['cachetime'] = intval($bcachetime);
    echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$block['form_title'].'<br /><br />';
    include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
    $form->display();
    xoops_cp_footer();
    echo '<script type="text/javascript">
    <!--//
    win = openWithSelfMain("", "popup", 250, 200, true);
    win.document.clear();
    ';
    $lines = preg_split("/(\r\n|\r|\n)( *)/", $dummyhtml);
    foreach ($lines as $line) {
        echo 'win.document.writeln("'.str_replace('"', '\"', $line).'");';
    }
    echo '
    win.focus();
    win.document.close();
    //-->
    </script>';
    exit();
}

# Adding dynamic block area/position system - TheRpLima - 2007-10-21
if ($op == 'adminpblocks') {
	  include "blockspadmin.php";
	  exit;
}
#

if ( $op == "list" ) {
    xoops_cp_header();
    list_blocks();
    xoops_cp_footer();
    exit();
}

if ( $op == "order" ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=blocksadmin", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    foreach (array_keys($bid) as $i) {
        if ( $oldweight[$i] != $weight[$i] || $oldvisible[$i] != $visible[$i] || $oldside[$i] != $side[$i] )
        order_block($bid[$i], $weight[$i], $visible[$i], $side[$i]);
    }
    redirect_header("admin.php?fct=blocksadmin",1,_AM_DBUPDATED);
}

if ( $op == "save" ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=blocksadmin", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    save_block($bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bmodule, $bcachetime);
    exit();
}

if ( $op == "update" ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=blocksadmin", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $bcachetime = isset($bcachetime) ? intval($bcachetime) : 0;
    $options = isset($options) ? $options : array();
    $bcontent = isset($bcontent) ? $bcontent : '';
    $bctype = isset($bctype) ? $bctype : '';
    update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options);
}


if ( $op == "delete_ok" ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header("admin.php?fct=blocksadmin", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    delete_block_ok($bid);
    exit();
}

if ( $op == "delete" ) {
    xoops_cp_header();
    delete_block($bid);
    xoops_cp_footer();
    exit();
}

if ( $op == "edit" ) {
    xoops_cp_header();
    edit_block($bid);
    xoops_cp_footer();
    exit();
}

# Activate the block clone function - TheRpLima - 2007-10-21
/*
if ($op == 'clone') {
    clone_block($bid);
}

if ($op == 'clone_ok') {
    clone_block_ok($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options);
}
*/
if ($op == 'clone') {
    clone_block($bid);
}

if ($op == 'clone_ok') {
    $bcachetime = isset($bcachetime) ? intval($bcachetime) : 0;
    $options = isset($options) ? $options : array();
    $bcontent = isset($bcontent) ? $bcontent : '';
    clone_block_ok($bid,$bside,$bweight,$bvisible,$btitle,$bcontent,$bcachetime,$bmodule,$options);
}
#
?>