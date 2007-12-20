<?php
// $Id: auth_provisionning.php 1029 2007-09-09 03:49:25Z phppp $
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
/**
 * @package     kernel
 * @subpackage  auth
 * @description	Authentification provisionning class. This class is responsible to
 * provide synchronisation method to Xoops User Database
 *
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class AuthProvisionning {

	var $_auth_instance;

	function &getInstance(&$auth_instance)
	{
		static $provis_instance;
		if (!isset($provis_instance)) {
			$provis_instance = new AuthProvisionning($auth_instance);
		}
		return $provis_instance;
	}

    /**
	 * Authentication Service constructor
	 */
    function AuthProvisionning (&$auth_instance) {
        $this->_auth_instance = &$auth_instance;
        $config_handler =& xoops_gethandler('config');
        $config =& $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);
        foreach ($config as $key => $val) {
            $this->$key = $val;
        }
        $config_gen =& $config_handler->getConfigsByCat(XOOPS_CONF);
        $this->default_TZ = $config_gen['default_TZ'];
        $this->theme_set = $config_gen['theme_set'];
        $this->com_mode = $config_gen['com_mode'];
        $this->com_order = $config_gen['com_order'];
    }

    /**
	 *  Return a Xoops User Object
	 *
	 * @return XoopsUser or false
	 */
	function getXoopsUser($uname) {
		$member_handler =& xoops_gethandler('member');
		$criteria = new Criteria('uname', $uname);
		$getuser = $member_handler->getUsers($criteria);
		if (count($getuser) == 1)
			return $getuser[0];
		else return false;
	}

    /**
	 *  Launch the synchronisation process
	 *
	 * @return bool
	 */
	function sync($datas, $uname, $pwd = null) {
		$xoopsUser = $this->getXoopsUser($uname);
		if (!$xoopsUser) { // Xoops User Database not exists
			if ($this->ldap_provisionning) {
				$xoopsUser = $this->add($datas, $uname, $pwd);
			} else $this->_auth_instance->setErrors(0, sprintf(_AUTH_LDAP_XOOPS_USER_NOTFOUND, $uname));
		} else { // Xoops User Database exists
			if ($this->ldap_provisionning && $this->ldap_provisionning_upd) {
				$xoopsUser = $this->change($xoopsUser, $datas, $uname, $pwd);
			}
		}
		return $xoopsUser;
	}

    /**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */
	function add($datas, $uname, $pwd = null) {
		$ret = false;
		$member_handler =& xoops_gethandler('member');
        // Create XOOPS Database User
		$newuser = $member_handler->createUser();
        $newuser->setVar('uname', $uname);
        $newuser->setVar('pass', md5(stripslashes($pwd)));
        //$newuser->setVar('name', utf8_decode($datas[$this->ldap_givenname_attr][0]) . ' ' . utf8_decode($datas[$this->ldap_surname_attr][0]));
        //$newuser->setVar('email', $datas[$this->ldap_mail_attr][0]);
        $newuser->setVar('rank', 0);
        $newuser->setVar('level', 1);
        $newuser->setVar('timezone_offset', $this->default_TZ);
        $newuser->setVar('theme', 	$this->theme_set);
        $newuser->setVar('umode', 	$this->com_mode);
        $newuser->setVar('uorder', 	$this->com_order);
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$newuser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
        }
        if ($member_handler->insertUser($newuser)) {
        	foreach ($this->ldap_provisionning_group as $groupid)
        		$member_handler->addUserToGroup($groupid, $newuser->getVar('uid'));
        	$newuser->unsetNew();
        	return $newuser;
        } else {
			redirect_header(XOOPS_URL.'/user.php', 5, $newuser->getHtmlErrors());
			exit();
		}
    	return $ret;
	}

    /**
	 *  Modify user information
	 *
	 * @return bool
	 */
	function change(&$xoopsUser, $datas, $uname, $pwd = null) {
		$ret = false;
		$member_handler =& xoops_gethandler('member');
		$xoopsUser->setVar('pass', md5(stripslashes($pwd)));
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
        }
		if ($member_handler->insertUser($xoopsUser)) {
        	return $xoopsUser;
        } else {
			redirect_header(XOOPS_URL.'/user.php', 5, $xoopsUser->getHtmlErrors());
			exit();
		}
    	return $ret;
	}

    /**
	 *  Modify a user
	 *
	 * @return bool
	 */
	function delete() {
	}

    /**
	 *  Suspend a user
	 *
	 * @return bool
	 */
	function suspend() {
	}

    /**
	 *  Restore a user
	 *
	 * @return bool
	 */
	function restore() {
	}

    /**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */
	function resetpwd() {
	}


}

?>