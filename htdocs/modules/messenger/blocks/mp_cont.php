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
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
include_once XOOPS_ROOT_PATH . '/modules/messenger/class/priv_msgscont.php';

function b_mp_cont_show($options) {

	global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $HTTP_SERVER_VARS;
	
	if (is_object($xoopsUser)) {
		$uid = $xoopsUser->getVar('uid');
		$uname = $xoopsUser->getVar('uname');
	} else {
		$uid = 0;
		$uname = '';
	}	
	$block = array();
 if (!is_object($xoopsUser)) {
 $block['lang_none'] = _MP_BL_YOUDONTHAVE;
 } else {
     
	$online = 0;
	$offline = 0;
	$user = '';
    $cont_handler  = & xoops_gethandler('priv_msgscont');
    $criteria = new CriteriaCompo(); 
    $criteria->add(new Criteria('ct_userid', $xoopsUser->getVar('uid'))); 
    $amount = $cont_handler->getCount($criteria); 	
    $criteria->setSort('ct_uname');
    $criteria->setOrder('desc');
    $pm_cont =& $cont_handler->getObjects($criteria);
foreach (array_keys($pm_cont) as $i) { 

$poster = new XoopsUser($pm_cont[$i]->getVar('ct_contact'));

/* Online poster */
      if ($poster->isOnline()) {	  	  
$user .= "<a href='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send2=1&to_userid=".$pm_cont[$i]->getVar('ct_contact')."\",\"pmlite\", 450, 380)'>".$poster->getVar('uname')."</a>, &nbsp;";
$online ++;
      } else {
$offline ++;
      }
	  
}
	
	$block['online_total'] = $amount;
	$block['online'] = $online;
	$block['offline'] = $offline;
	$block['user'] = $user;
	$block['lang_online'] = _MP_BLOCK_ONLINE;
	$block['lang_offline'] = _MP_BLOCK_OFFLINE;
	$block['lang_contact'] = _MP_BLOCK_CONTACT;
 }
	return $block;
}

function b_mp_cont_edit($options) {
	
	return $form;
}


?>
