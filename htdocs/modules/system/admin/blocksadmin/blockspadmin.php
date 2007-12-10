<?php
// $Id: blocksadmin.php 506 2006-05-26 23:10:37Z skalpa $
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
  $act = (isset($_GET['act']) && $_GET['act'] != '')?$_GET['act']:((isset($_POST['act']) && $_POST['act'] != '')?$_POST['act']:'list');
	if ($act == 'list'){
    xoops_cp_header();
		listPblocks();
    xoops_cp_footer();
	}elseif ($act == 'edit'){
	  $pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
    xoops_cp_header();
	  edit_pblock($pbid);
    xoops_cp_footer();
	}elseif ($act == 'delete'){
	  $pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
    xoops_cp_header();
	  xoops_confirm(array( 'pbid' => $pbid, 'act'=>'delete_ok'),'admin.php?fct=blocksadmin&op=adminpblocks', _AM_BPMSG3);
    xoops_cp_footer();
	}elseif ($act == 'delete_ok'){
    $pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
		del_pblock($pbid);
	}elseif ($act == 'save'){
	  save_pblock($_POST);
	}elseif ($act == 'edit_ok'){
	  save_pblock($_POST,true);
	}
} else {
    echo "Acess Denied";
}

function listPblocks(){
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
	$oldzones = XoopsBlock::getBlockPositions(true);
  echo "<h4 style='float:right; text-align:left;'><a href='admin.php?fct=blocksadmin'>"._AM_BADMIN."</a></h4>";
	echo '<div class="CPbigTitle" style="background-image: url('.XOOPS_URL.'/modules/system/admin/blocksadmin/images/blocksadmin_big.png)">'._AM_BPADMIN.'</div><br />';
	//echo "<h4 style='text-align:left;'>"._AM_BPADMIN."</h4>";

	echo '<p>'._AM_BPHELP.'</p><br /><br />';

	echo "<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
        <tr valign='middle'><th>"._AM_BPCOD."</th><th>"._AM_BPNAME."</th><th>"._AM_TITLE."</th><th width='30%'>"._AM_BPDESC."</th><th align='right' width='12%'>"._AM_ACTION."</th></tr>
        ";
	$class = 'odd';
	foreach ($oldzones as $k=>$v){
		$class = ($class == 'even')?'odd':'even';
		echo '<tr class="'.$class.'">';
		echo '<td align="center">'.$k.'</td>';
		echo '<td>'.$oldzones[$k]['pname'].'</td>';
		$tit = (defined($oldzones[$k]['title']))?constant($oldzones[$k]['title']):$oldzones[$k]['title'];
		echo '<td>'.$tit.'</td>';
		echo '<td>'.$oldzones[$k]['description'].'</td>';
		if (!$oldzones[$k]['block_default']){
			$opcoes = '<a href="admin.php?fct=blocksadmin&op=adminpblocks&act=edit&pbid='.$k.'">'._EDIT.'</a> | ';
			$opcoes .= '<a href="admin.php?fct=blocksadmin&op=adminpblocks&act=delete&pbid='.$k.'">'._DELETE.'</a>';
		}else{
			$opcoes = '';
		}
		echo '<td align="right">'.$opcoes.'</td>';
		echo '</tr>';
	}
	echo "<tr><td class='foot' align='center' colspan='7'>
        </td></tr></table>
        </form>
        <br /><br />";
	$pblock = array(
  	'form_title' => _AM_ADDPBLOCK,
	  'pname' => '',
	  'title' => '',
	  'description' => '',
	  'act' => 'save'
	  );
	include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/pblockform.php';
  $form->display();
}

function edit_pblock($pbid){
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
	$oldzones = XoopsBlock::getBlockPositions(true);

	echo '<a href="admin.php?fct=blocksadmin&op=adminpblocks">'. _AM_BPADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_EDITPBLOCK.'<br /><br />';

	$pblock = array(
  	'form_title' => _AM_EDITPBLOCK,
	  'pbid' => $pbid,
	  'pname' => $oldzones[$pbid]['pname'],
	  'title' => $oldzones[$pbid]['title'],
	  'description' => $oldzones[$pbid]['description'],
	  'act' => 'edit_ok'
	  );

	include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/pblockform.php';
  $form->display();
}

function save_pblock($dados,$edit=false){
	$db =& Database::getInstance();

	if (!$edit)
	  $sql = "INSERT INTO ".$db->prefix('block_positions')." (pname,title,description,block_default,block_type) VALUES ('".$dados['pname']."','".$dados['title']."','".$dados['description']."','0','L')";
	else
	  $sql = "UPDATE ".$db->prefix('block_positions')." SET pname='".$dados['pname']."', title='".$dados['title']."', description='".$dados['description']."', block_default='0', block_type='L' WHERE id='".intval($dados['pbid'])."'";

	if ($db->queryF($sql)){
	  redirect_header('admin.php?fct=blocksadmin&op=adminpblocks',1,_AM_BPMSG1);
	  exit();
	}else{
	  redirect_header('admin.php?fct=blocksadmin&op=adminpblocks',1,_AM_BPMSG2);
	  exit();
	}  
}

function del_pblock($pbid){
	$db =& Database::getInstance();

	$sql = "DELETE FROM ".$db->prefix('block_positions')." WHERE id='".intval($pbid)."'";

	if ($db->queryF($sql)){
	  redirect_header('admin.php?fct=blocksadmin&op=adminpblocks',1,_AM_BPMSG1);
	  exit();
	}else{
	  redirect_header('admin.php?fct=blocksadmin&op=adminpblocks',1,_AM_BPMSG2);
	  exit();
	}  
}

?>