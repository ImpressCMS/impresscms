<?php
/**
*
*
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			Venom <webmaster@exode-fr.com>
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;
function xoops_module_update_messenger(&$module) {
  global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;
  if( file_exists(XOOPS_ROOT_PATH."/modules/messenger/language/".$xoopsConfig['language']."/admin.php") ) {
	 include(XOOPS_ROOT_PATH."/modules/messenger/language/".$xoopsConfig['language']."/admin.php");
  } else {
	 include(XOOPS_ROOT_PATH."/modules/messenger/language/english/admin.php");
  }
  $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('modules')." SET weight = 0 WHERE mid = ".$module->getVar('mid')."");
  if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
  if(!TableExists($xoopsDB->prefix('priv_msgs'))) {
    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlmsg.sql");
  }
  //mise ajour table message
  if (!FieldExists('msg_pid',$xoopsDB->prefix('priv_msgs'))) {
    $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD msg_pid MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL AFTER msg_id");
  }
  if (!FieldExists('reply_msg',$xoopsDB->prefix('priv_msgs'))) {
    $sq1 = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD reply_msg TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER read_msg");
  }
  if (!FieldExists('anim_msg',$xoopsDB->prefix('priv_msgs'))) {
    $sq1 = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD anim_msg VARCHAR(100) AFTER reply_msg");
  }
  if (!FieldExists('cat_msg',$xoopsDB->prefix('priv_msgs'))) {
    $sq1 = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD cat_msg MEDIUMINT( 8 ) UNSIGNED DEFAULT '1' NOT NULL AFTER anim_msg");
  }
  if (!FieldExists('file_msg',$xoopsDB->prefix('priv_msgs'))) {
    $sq1 = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD file_msg MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL AFTER cat_msg");
  }

  //mise a jour message
  if(TableExists($xoopsDB->prefix('priv_msgsave'))) {
    $sq2 = "SELECT * FROM ".$xoopsDB->prefix('priv_msgsave')."";
    $result2 = $xoopsDB->query($sq2);
    while ($row = $xoopsDB->fetchArray($result2)) {
      $sql = "INSERT INTO `".$xoopsDB->prefix("priv_msgs")."` (msg_id, msg_image,subject,from_userid,to_userid,msg_time,msg_text,read_msg,reply_msg,anim_msg,cat_msg,file_msg) VALUES('','".$row['msg_image']."','".$row['subject']."','".$row['sauv_userid']."','".$row['to_userid']."','".$row['msg_time']."','".$row['msg_text']."','".$row['read_msg']."','".$row['reply_msg']."','".$row['anim_msg']."', '3','') ";
      $result = $xoopsDB->queryF($sql);
    }
    $result = $xoopsDB->queryF("DROP TABLE `".$xoopsDB->prefix("priv_msgsave")."`");
  }
  //add table priv_msgscat
  if(!TableExists($xoopsDB->prefix('priv_msgscat'))) {
    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlcat.sql");
  }
  $sq1 = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (1, 0, '"._MP_BOX1."', NULL, 1)");
  $sq1 = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (2, 0, '"._MP_BOX2."', NULL, 1)");
  $sq1 = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (3, 0, '"._MP_BOX3."', NULL, 1)");
  
  //add and update priv_msgscont
  if(!TableExists($xoopsDB->prefix('priv_msgscont'))) {
    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlcont.sql");
  } else {
  if (!FieldExists('ct_name',$xoopsDB->prefix('priv_msgscont'))) {
    $sql = "ALTER TABLE `".$xoopsDB->prefix("priv_msgscont")."` ADD `ct_name` varchar(60) NOT NULL default '' AFTER `ct_contact`";		
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('ct_uname',$xoopsDB->prefix('priv_msgscont'))) {
    $sql = "ALTER TABLE `".$xoopsDB->prefix("priv_msgscont")."` ADD `ct_uname` varchar(25) NOT NULL default '' AFTER `ct_name`";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('ct_regdate',$xoopsDB->prefix('priv_msgscont'))) {
    $sql = "ALTER TABLE `".$xoopsDB->prefix("priv_msgscont")."` ADD `ct_regdate` int(10) NOT NULL default '0' AFTER `ct_uname`";	
    $result = $xoopsDB->queryF($sql);
  }
  $sq3 = "SELECT * FROM ".$xoopsDB->prefix('priv_msgscont')."";
  $result3=$xoopsDB->query($sq3);

  while($row=$xoopsDB->fetchArray($result3)){
    $poster = new XoopsUser($row['ct_contact']);
    $sql = "UPDATE `".$xoopsDB->prefix('priv_msgscont')."` SET ct_name='".$poster->getVar('name')."', ct_uname='".$poster->getVar('uname')."', ct_regdate='".$poster->getVar('user_regdate')."' WHERE ct_contact='".$row['ct_contact']."'";
    $result = $xoopsDB->queryF($sql);
  }
}
  ////add and update tables priv_msgsopt
  if(!TableExists($xoopsDB->prefix('priv_msgsopt'))){
    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlopt.sql");
  } else {
  if (!FieldExists('resend',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE `".$xoopsDB->prefix("priv_msgsopt")."` ADD `resend` tinyint(1) NOT NULL default '0'";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('limite',$xoopsDB->prefix('priv_msgsopt'))) {
    $sq1 = "ALTER TABLE `".$xoopsDB->prefix("priv_msgsopt")."` ADD `limite` tinyint(2) default NULL";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('home',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgsopt")." ADD `home` tinyint(2) DEFAULT '1' NOT NULL AFTER `limite`";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('sortname',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgsopt")." ADD `sortname` varchar(15) AFTER `home`";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('sortorder',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgsopt")." ADD `sortorder` varchar(15) AFTER `sortname`";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('vieworder',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgsopt")." ADD `vieworder` varchar(15) AFTER `sortorder`";
    $result = $xoopsDB->queryF($sql);
  }
  if (!FieldExists('formtype',$xoopsDB->prefix('priv_msgsopt'))) {
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgsopt")." ADD `formtype` tinyint(1) AFTER `vieworder`";
    $result = $xoopsDB->queryF($sql);
  }
}
  //add and update tables priv_msgsup
  if(!TableExists($xoopsDB->prefix('priv_msgsup'))){
    $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlup.sql");
    //update priv_msgsup
    $sq5 = "SELECT * FROM ".$xoopsDB->prefix('priv_msgs')."WHERE file_msg !='' ";
    $result5 = $xoopsDB->query($sq5);

    while ($row = $xoopsDB->fetchArray($result5)) {
      $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("priv_msgsup")."` (`msg_id`, `u_id`, `u_name`, `u_mimetype`, `u_file`, `u_file`, `u_weight`) VALUES ('', ".$row['msg_id'].", ".$row['file_msg'].", '', ".$row['file_msg'].", '')");
      $sql = "UPDATE ".$xoopsDB->prefix('priv_msgs')." SET file_msg=1 WHERE msg_id='".$row['msg_id']."'";
    }
    //update file_msg
    $sql = "ALTER TABLE ".$xoopsDB->prefix("priv_msgs")." CHANGE `file_msg` `file_msg` MEDIUMINT(8) NOT NULL DEFAULT '0'";
    $result = $xoopsDB->queryF($sql);
  }
}

xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
$tpllist=array('mp_box.html','mp_contbox.html','mp_filebox.html','mp_index.html','mp_msgbox.html','mp_optionbox.html','mp_subox.html','mp_viewbox.html','mp_block_cont.html','mp_block_new.html');
$xoopsTpl = new XoopsTpl();
foreach ($tpllist as $onetemplate) {
$xoopsTpl->clear_cache('db:'.$onetemplate);
}

  return true;
}

function FieldExists($fieldname,$table) {
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

function TableExists($tablename) {
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
	return($xoopsDB->getRowsNum($result) > 0);
}
?>
