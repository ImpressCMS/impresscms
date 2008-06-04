<?php
/**
* Block Positions Manager
*
* System tool that allow create and manage positions/areas to disply the blocks
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
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
	
	if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);

	$op = (isset($_GET['op']) && $_GET['op'] != '')?$_GET['op']:((isset($_POST['op']) && $_POST['op'] != '')?$_POST['op']:'list');

	switch ($op){
		case 'list':
			xoops_cp_header();
			echo listPblocks();
			xoops_cp_footer();
			break;
		case 'edit':
			$pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
			xoops_cp_header();
			edit_pblock($pbid);
			xoops_cp_footer();
			break;
		case 'delete':
			$pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
			xoops_cp_header();
			xoops_confirm(array( 'pbid' => $pbid, 'op'=>'delete_ok'),'admin.php?fct=blockspadmin', _AM_BPMSG3);
			xoops_cp_footer();
			break;
		case 'delete_ok':
			$pbid = (isset($_GET['pbid']) && $_GET['pbid'] != '')?$_GET['pbid']:((isset($_POST['pbid']) && $_POST['pbid'] != '')?$_POST['pbid']:0);
			del_pblock($pbid);
			break;
		case 'save':
			save_pblock($_POST);
			break;
		case 'edit_ok':
			save_pblock($_POST,true);
			break;
	}
}

function listPblocks(){
	global $icmsAdminTpl;

	$icmsAdminTpl->assign('lang_bp_admin',_AM_BPADMIN);
	$icmsAdminTpl->assign('lang_bp_help',_AM_BPHELP);
	$icmsAdminTpl->assign('lang_bp_bpid',_AM_BPCOD);
	$icmsAdminTpl->assign('lang_bp_bpname',_AM_BPNAME);
	$icmsAdminTpl->assign('lang_bp_bptitle',_AM_TITLE);
	$icmsAdminTpl->assign('lang_bp_bpdesc',_AM_BPDESC);
	$icmsAdminTpl->assign('lang_bp_bpaction',_AM_ACTION);
	$icmsAdminTpl->assign('lang_bp_bpedit',_EDIT);
	$icmsAdminTpl->assign('lang_bp_bpdelete',_DELETE);
	$icmsAdminTpl->assign('lang_cont_addcont',_AM_ADDPBLOCK);


	$oldzones = XoopsBlock::getBlockPositions(true);
	foreach ($oldzones as $k=>$v){
		$oldzones[$k]['title'] = (defined($oldzones[$k]['title']))?constant($oldzones[$k]['title']):$oldzones[$k]['title'];
	}
	$icmsAdminTpl->assign('oldzones',$oldzones);

	$pblock = array(
	'form_title' => _AM_ADDPBLOCK,
	'pname' => '',
	'title' => '',
	'description' => '',
	'act' => 'save'
	);
	include XOOPS_ROOT_PATH.'/modules/system/admin/blockspadmin/pblockform.php';
	$icmsAdminTpl->assign('addbposform',$form->render());

	return $icmsAdminTpl->fetch('db:admin/blockspadmin/system_adm_blockspadmin.html');
}

function edit_pblock($pbid){
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
	$oldzones = XoopsBlock::getBlockPositions(true);

	echo '<a href="admin.php?fct=blockspadmin">'. _AM_BPADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_EDITPBLOCK.'<br /><br />';

	$pblock = array(
  	'form_title' => _AM_EDITPBLOCK,
	  'pbid' => $pbid,
	  'pname' => $oldzones[$pbid]['pname'],
	  'title' => $oldzones[$pbid]['title'],
	  'description' => $oldzones[$pbid]['description'],
	  'act' => 'edit_ok'
	  );

	include XOOPS_ROOT_PATH.'/modules/system/admin/blockspadmin/pblockform.php';
  $form->display();
}

function save_pblock($dados,$edit=false){
	$db =& Database::getInstance();

	if (!$edit)
	  $sql = "INSERT INTO ".$db->prefix('block_positions')." (pname,title,description,block_default,block_type) VALUES ('".$dados['pname']."','".$dados['title']."','".$dados['description']."','0','L')";
	else
	  $sql = "UPDATE ".$db->prefix('block_positions')." SET pname='".$dados['pname']."', title='".$dados['title']."', description='".$dados['description']."', block_default='0', block_type='L' WHERE id='".intval($dados['pbid'])."'";

	if ($db->queryF($sql)){
	  redirect_header('admin.php?fct=blockspadmin',1,_AM_BPMSG1);
	}else{
	  redirect_header('admin.php?fct=blockspadmin',1,_AM_BPMSG2);
	}
}

function del_pblock($pbid){
	$db =& Database::getInstance();

	$sql = "DELETE FROM ".$db->prefix('block_positions')." WHERE id='".intval($pbid)."'";

	if ($db->queryF($sql)){
	  redirect_header('admin.php?fct=blockspadmin',1,_AM_BPMSG1);
	}else{
	  redirect_header('admin.php?fct=blockspadmin',1,_AM_BPMSG2);
	}
}
?>