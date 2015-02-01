<?php
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
 * XOOPS authentification class
 * Authorization classes, xoops authorization class file
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Auth
 * @subpackage	Xoops
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: Xoops.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * Authentification class for Native XOOPS
 * @category	ICMS
 * @package		Auth
 * @subpackage	Xoops
 * @author		Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_auth_Xoops extends icms_auth_Object {
	/**
	 * Authentication Service constructor
	 * constructor
	 * @param object $dao reference to dao object
	 */
	public function __construct(&$dao) {
		$this->_dao = $dao;
		$this->auth_method = 'xoops';
	}

	/**
	 *  Authenticate user
	 * @param string $uname
	 * @param string $pwd
	 * @return object {@link icms_member_user_Object} icms_member_user_Object object
	 */
	public function authenticate($uname, $pwd = null) {
		$member_handler = icms::handler('icms_member');
		$user = $member_handler->loginUser($uname, $pwd);
		icms::$session->enableRegenerateId = true;
		icms::$session->sessionOpen();
		if ($user == false) {
			icms::$session->destroy(session_id());
			$this->setErrors(1, _US_INCORRECTLOGIN);
		}
		return ($user);
	}
}

