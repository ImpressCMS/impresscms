<?php
// $Id$
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
// Author: Sudhaker Raj (http://sudhaker.com/)                               //
// Project: The ImpressCMS Project (http://www.impresscms.org/)              //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

function load_invite_code($code) {
	// validate if code is of valid length.
	if (empty($code) || strlen($code) != 8) {
		header('Location: invite.php');
		// redirect_header('invite.php', 0, _US_INVITENONE);
		exit();
	}
	$xoopsDB =& Database::getInstance();
	$sql = sprintf('SELECT invite_to, invite_date, register_id, extra_info FROM %s WHERE invite_code = %s AND register_id = 0', $xoopsDB->prefix('invites'), $xoopsDB->quoteString(addslashes($code)));
	$result = $xoopsDB->query($sql);
	list($invite_to, $invite_date, $register_id, $extra_info) = $xoopsDB->fetchRow($result);
	if (empty($invite_to)) {
		redirect_header('invite.php', 3, _US_INVITEINVALID);
		exit();
	}
	// discard if already registered or invite is more than 3 days old
	if (! empty($register_id) || intval($invite_date) < time() - 3 * 86400) {
		redirect_header('invite.php', 3, _US_INVITEEXPIRED);
		exit();
	}
	// load default email and actkey
	global $email, $actkey;
	$email = $invite_to;
	$actkey = $code;
	// load extra_info
	$extra_array = unserialize($extra_info);
	foreach ($extra_array as $ex_key => $ex_value) {
		$GLOBALS[$ex_key] = $ex_value;
	}
	// update view time
	$sql = sprintf('UPDATE '.$xoopsDB->prefix('invites').' SET view_date = %d WHERE invite_code = %s AND register_id = 0', time(), $xoopsDB->quoteString(addslashes($code)));
	$result = $xoopsDB->query($sql);
}

function check_invite_code($code) {
	// validate if code is of valid length.
	if (empty($code) || strlen($code) != 8) {
		return false;
	}
	$xoopsDB =& Database::getInstance();
	$sql = sprintf('SELECT invite_to, invite_date FROM %s WHERE invite_code = %s AND register_id = 0', $xoopsDB->prefix('invites'), $xoopsDB->quoteString(addslashes($code)));
	$result = $xoopsDB->query($sql);
	list($invite_to, $invite_date) = $xoopsDB->fetchRow($result);
	if (empty($invite_to) || !empty($register_id) || intval($invite_date) < time() - 3 * 86400) {
		return false;
	}
	return true;	
}

function update_invite_code($code, $new_id) {
	$xoopsDB =& Database::getInstance();
	// update register_id
	$sql = sprintf('UPDATE '.$xoopsDB->prefix('invites').' SET register_id = %d WHERE invite_code = %s AND register_id = 0', $new_id, $xoopsDB->quoteString(addslashes($code)));
	$result = $xoopsDB->query($sql);
	return true;	
}

?>