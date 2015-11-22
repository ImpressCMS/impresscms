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
 * Authentication classes, factory class file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

/**
 * Authentification class factory
 * 
 * @copyright   http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since       XOOPS
 * @package     ICMS\Authentication
 * @author	Pierre-Eric MENUET	<pemphp@free.fr>
 */
class icms_auth_Factory {

	/**
	 * Get a reference to the only instance of authentication class
	 *
	 * if the class has not been instantiated yet, this will also take
	 * care of that
	 * @param   string $uname Username to get Authentication class for
	 * @static
	 * @return  object  Reference to the only instance of authentication class
	 */
	static public function &getAuthConnection($uname) {
		static $auth_instance;
		if (isset($auth_instance)) {
			return $auth_instance;
		} else {
			global $icmsConfigAuth;

			if (empty($icmsConfigAuth['auth_method'])) {
				// If there is a config error, we use local authentication
				$auth_method = 'local';
			} else {
				$auth_method = $icmsConfigAuth['auth_method'];

				// However if auth_method is Local, and Openid login is activated and a user is trying to authenticate with his openid

				/*
				 * @todo remove this from the factory class!
				 * this actually should NOT be part of the factory class
				 */

				if ($icmsConfigAuth['auth_method'] == 'local'
					&& $icmsConfigAuth['auth_openid']
					&& (isset($_REQUEST['openid_identity']) || isset($_SESSION['openid_response']))
				) {
					$auth_method = 'openid';
				}
			}

			$class = 'icms_auth_method_' . ucfirst($auth_method);
			$auth_instance = new $class();
			return $auth_instance;
		}
	}
}
