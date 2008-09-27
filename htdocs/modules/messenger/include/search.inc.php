<?php
// $Id: search.inc.php,v 1.5 2004/08/03 16:48:57 hthouzard Exp $
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
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

function mp_search($queryarray, $andor, $limit, $offset, $userid){

$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : false;	
	
	global $xoopsDB, $xoopsUser;
	
	if ($action == 'results') {
	
	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('messenger');
    $modid= $module->getVar('mid');
    $searchparam='';

	$gperm_handler =& xoops_gethandler('groupperm');
	if (is_object($xoopsUser)) {
	    $groups = $xoopsUser->getGroups();
	} else {
		$groups = XOOPS_GROUP_ANONYMOUS;
	}

	$sql = "SELECT msg_id, subject, from_userid, to_userid, msg_time, msg_text FROM ".$xoopsDB->prefix("priv_msgs")." WHERE (to_userid = ".$xoopsUser->getVar('uid').") ";
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((subject LIKE '%$queryarray[0]%' OR msg_text LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(subject LIKE '%$queryarray[$i]%' OR msg_text LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
		// keywords highlighting
	}

	$sql .= "ORDER BY msg_time DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
			$ret[$i]['image'] = "images/lus.png";
			$ret[$i]['link'] = "viewbox.php?op=view&searchmsg=".$myrow['msg_id'];
			$ret[$i]['title'] = $myrow['subject'];
			$ret[$i]['time'] = $myrow['msg_time'];
			$ret[$i]['uid'] = $myrow['from_userid'];
			$i++;
	} 

	return $ret;
}}
?>