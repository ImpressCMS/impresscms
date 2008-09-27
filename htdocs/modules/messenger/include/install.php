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

function xoops_module_install_messenger(&$module) {
  global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsModule;
  if( file_exists(XOOPS_ROOT_PATH."/modules/messenger/language/".$xoopsConfig['language']."/admin.php") ) {
	 include_once(XOOPS_ROOT_PATH."/modules/messenger/language/".$xoopsConfig['language']."/admin.php");
  } else {
	 include_once(XOOPS_ROOT_PATH."/modules/messenger/language/english/admin.php");
  }
  $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('modules')." SET weight = 0 WHERE mid = ".$module->getVar('mid')."");

  if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {

    if(!TableExists($xoopsDB->prefix('priv_msgs'))){
      $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/messenger/sql/mysqlmsg.sql");
    }
    $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (1, 0, '"._MP_BOX1."', NULL, 1)");
    $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (2, 0, '"._MP_BOX2."', NULL, 1)");
    $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix('priv_msgscat')."` (`cid`, `pid`, `title`, `uid`, `ver`) VALUES (3, 0, '"._MP_BOX3."', NULL, 1)");

    if (!FieldExists('reply_msg',$xoopsDB->prefix('priv_msgs'))) {
      $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD reply_msg TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER read_msg");
    }
    if (!FieldExists('anim_msg',$xoopsDB->prefix('priv_msgs'))) {
      $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD anim_msg VARCHAR(100)");
    }
    if (!FieldExists('cat_msg',$xoopsDB->prefix('priv_msgs'))) {
      $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD cat_msg MEDIUMINT(8) DEFAULT '1' NOT NULL");
    }
    if (!FieldExists('file_msg',$xoopsDB->prefix('priv_msgs'))) {
      $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD file_msg MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL");
    }
    if (!FieldExists('msg_pid',$xoopsDB->prefix('priv_msgs'))) {
      $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix('priv_msgs')."` ADD msg_pid MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL AFTER msg_id");
    }
	
	 if(TableExists($xoopsDB->prefix('user_profile'))){
    $result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('user_profile')." SET messenger_link=".$GLOBALS['xoopsDB']->quoteString(
    "<a href=\"javascript:openWithSelfMain('{X_URL}/pmlite.php?send2=1&to_userid={X_UID}', 'pmlite', 550, 450);\" title=\""._PM_MI_MESSAGE." {X_UNAME}\"><img src=\"{X_URL}/modules/messenger/images/pm.gif\" alt=\""._PM_MI_MESSAGE." {X_UNAME}\" /></a>"
    )." WHERE messenger_link=''");	
    }
  }
  return true;
}

function FieldExists($fieldname,$table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

function TableExists($tablename)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
	return($xoopsDB->getRowsNum($result) > 0);
}
?>
