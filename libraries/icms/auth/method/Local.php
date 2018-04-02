<?php
/**
 * Authentication class for local database
 * 
 * Authentication classes, local authentication class file
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org> 
 * @package     ICMS\Authentication\Method
 * @author	Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_auth_method_Local extends icms_auth_Object {
	/**
	 * Authentication Service constructor
	 * constructor
	 * @param object $dao reference to dao object
	 */
	public function __construct() {
		$this->_dao = icms::$xoopsDB;
		$this->auth_method = 'local';
	}

	/**
	 * Authenticate user
	 * @param string $uname
	 * @param string $pwd
	 * @return object {@link icms_member_user_Object} icms_member_user_Object object
	 */
	public function authenticate($uname, $pwd = NULL) {
		$member_handler = icms::handler('icms_member');
		$user = $member_handler->loginUser($uname, $pwd);
		icms::$session->enableRegenerateId = TRUE;
		icms::$session->sessionOpen();
		if ($user == FALSE) {
			icms::$session->destroy(session_id());
			$this->setErrors(1, _US_INCORRECTLOGIN);
		}
		return ($user);
	}
}
