<?php
include("admin_header.php");
require_once( 'mygrouppermform.php' ) ;
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';



if( ! empty( $_POST['submit'] ) ) {
	include( "mygroupperm.php" ) ;
	redirect_header( XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/groupperm_global.php" , 1 , _MP_GPERMUPDATED );
}

    xoops_cp_header();
	 mp_adminmenu(8, _MP_ADMENU8);

	mp_collapsableBar('toptable', 'toptableicon');
	echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _MP_PERM . "</h4>";
echo "<div id='toptable'>";
echo "<br /><br />";
$permtoset= isset($_POST['permtoset']) ? intval($_POST['permtoset']) : 1;
$selected=array('','','');
$selected[$permtoset-1]=' selected';
echo "<form method='post' name='fselperm' action='groupperm_global.php'><table border=0><tr><td><select name='permtoset' onChange='javascript: document.fselperm.submit()'><option value='1'".$selected[0].">"._MP_GLOBAL."</option><option value='2'".$selected[1].">"._MP_GROUPES."</option><option value='3'".$selected[2].">"._MP_ACCES."</option></select></td><td><input type='submit' name='go'></tr></table></form>";
$module_id = $xoopsModule->getVar('mid');

switch($permtoset)
{
	case 1:
		$perm_name = "mp_view";
		$perm_desc = _MP_GLOBAL_DESC;
		break;
	case 2:
		$perm_name = "mp_groupe";
		$perm_desc = _MP_GROUPE_DESC;
		break;
	case 3:
		$perm_name = "module_read";
		$perm_desc = _MP_ACCES_DESC;
		break;
}



if ($selected[0]) {
	$global_perms_array = array(
        GPERM_MESS => _MP_CONF_MESS ,
		GPERM_OEIL => _MP_CONF_OEIL,
		GPERM_EXP => _MP_CONF_EXP,
		GPERM_UP => _MP_CONF_UP
		 );
	}
	
	
if ($selected[1]) {	
$gperm_handler =& xoops_gethandler('groupperm');
$member_handler =& xoops_gethandler('member');
$global_perms_array = $member_handler->getGroupList(new Criteria('groupid', XOOPS_GROUP_ANONYMOUS, '!='));
}


if ($selected[2]) {	
	$module_id = 1;
	$global_perms_array = array(
		$xoopsModule->getVar('mid') => _MP_CONF_ACCES	
		 );
	}

$permform = new MyXoopsGroupPermForm('', $module_id, $perm_name, $perm_desc);

 //MyXoopsGroupPermForm($title, $modid, $permname, $permdesc)	

foreach( $global_perms_array as $perm_id => $perm_name ) {
		$permform->addItem( $perm_id , $perm_name ) ;
	}
	
echo $permform->render();
echo "</div>\n" ;
xoops_cp_footer() ;

?>