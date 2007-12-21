<?php
// $Id: authfactory.php 1029 2007-09-09 03:49:25Z phppp $
// authfactory.php - Authentification class factory
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
 * @description	Authentification class factory
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 * @copyright	copyright (c) 2000-2005 XOOPS.org
 */
class XoopsAuthFactory
{

	/**
	 * Get a reference to the only instance of authentication class
     * 
     * if the class has not been instantiated yet, this will also take 
     * care of that
	 * 
     * @static
     * @return      object  Reference to the only instance of authentication class
	 */
	function &getAuthConnection($uname)
	{
		static $auth_instance;		
		if (!isset($auth_instance)) {
			$config_handler =& xoops_gethandler('config');    
    		$authConfig =& $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);    		
			require_once XOOPS_ROOT_PATH.'/class/auth/auth.php';
			if (empty($authConfig['auth_method'])) { // If there is a config error, we use xoops
				$xoops_auth_method = 'db';
			} else {
			    $xoops_auth_method = $authConfig['auth_method'];
			}
			// Verify if uname allow to bypass LDAP auth 
			if (in_array($uname, $authConfig['ldap_users_bypass'])) $xoops_auth_method = 'xoops';
			$file = XOOPS_ROOT_PATH . '/class/auth/auth_' . $xoops_auth_method . '.php';			
			require_once $file;
			$class = 'XoopsAuth' . ucfirst($xoops_auth_method);
			switch ($xoops_auth_method) {
				case 'xoops' :
					$dao =& $GLOBALS['xoopsDB'];
					break;
				case 'ldap'  : 
					$dao = null;
					break;
				case 'ads'  : 
					$dao = null;
					break;

			}
			$auth_instance = new $class($dao);
		}
		return $auth_instance;
	}

}

?>