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
 * Authorization classes, factory class file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Auth
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: Factory.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * Authentification class factory
 * @copyright   http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since       XOOPS
 * @category	ICMS
 * @package     Auth
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
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
				// If there is a config error, we use xoops
				$auth_method = 'xoops';
			} else {
				$auth_method = $icmsConfigAuth['auth_method'];

				// However if auth_method is XOOPS, and openid login is activated and a user is trying to authenticate with his openid

				/*
				 * @todo we need to add this in the preference
				 */
				$config_to_enable_openid = true;

				if ($icmsConfigAuth['auth_method'] == 'xoops' && $config_to_enable_openid && (isset($_REQUEST['openid_identity']) || isset($_SESSION['openid_response']))) {
					$auth_method = 'openid';
				}
			}
			// Verify if uname allow to bypass LDAP auth
			if (in_array($uname, $icmsConfigAuth['ldap_users_bypass'])) $auth_method = 'xoops';
			/* with autoloading in ImpressCMS 1.3, requiring the file is not necessary */
			$class = 'icms_auth_' . ucfirst($auth_method);
			switch ($auth_method) {
				case 'xoops' :
					$dao =& icms::$xoopsDB;
					break;

				case 'ldap'  :
					$dao = null;
					break;

				case 'ads'  :
					$dao = null;
					break;

				default:
					break;
			}
			$auth_instance = new $class($dao);
			return $auth_instance;
		}
	}
}

