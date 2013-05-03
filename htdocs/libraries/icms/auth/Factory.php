<?php
/**
 * Authentication classes, factory class file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Authentication
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: Factory.php 11731 2012-06-17 01:25:04Z skenow $
 */

/**
 * Authentication class factory
 *
 * @category	ICMS
 * @package     Authentication
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
