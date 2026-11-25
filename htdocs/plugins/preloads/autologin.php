<?php
/**
 * Automatic login with a remember-me cookie
 *
 * The token handling itself lives in icms_auth_Autologin.
 */
class icms_AutologinEventHandler {

	static public function setup() {
		icms_Event::attach('icms_core_Session', 'sessionStart', array(__CLASS__, 'onSessionStart'));
		icms_Event::attach('icms_core_Session', 'sessionClose', array(__CLASS__, 'onSessionClose'));
	}

	static public function onSessionStart() {
		if (!empty($_SESSION['xoopsUserId'])) {
			return;
		}
		if (icms_auth_Autologin::hasCookie()) {
			self::sessionAutologin($_COOKIE[icms_auth_Autologin::COOKIE_NAME]);
		} elseif (icms_auth_Autologin::hasLegacyCookie()) {
			// cookies of the old implementation are no longer accepted
			icms_auth_Autologin::clearCookie();
		}
	}

	static public function onSessionClose() {
		icms_auth_Autologin::revokeCurrent();
	}

	/**
	 * Log the user in with the remember-me cookie
	 *
	 * @param string $cookie value of the remember-me cookie
	 */
	static public function sessionAutologin($cookie) {
		$result = icms_auth_Autologin::validate($cookie);
		if ($result === false) {
			icms_auth_Autologin::clearCookie();
			return;
		}
		list($user, $selector) = $result;

		// anti-CSRF: never act on a request that was not confirmed by the user
		// (the token is not consumed yet, the login happens on session_confirm.php)
		if (!empty($_POST)) {
			$_SESSION['AUTOLOGIN_POST'] = $_POST;
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
			redirect_header(ICMS_URL . '/session_confirm.php', 0, '&nbsp;');
		} elseif (!empty($_SERVER['QUERY_STRING']) && substr($_SERVER['SCRIPT_NAME'], -19) != 'session_confirm.php') {
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
			redirect_header(ICMS_URL . '/session_confirm.php', 0, '&nbsp;');
		}

		// prevent session fixation: new session id and a clean session
		$keep = array();
		foreach (array('AUTOLOGIN_POST', 'AUTOLOGIN_REQUEST_URI') as $key) {
			if (isset($_SESSION[$key])) {
				$keep[$key] = $_SESSION[$key];
			}
		}
		session_regenerate_id(true);
		$_SESSION = $keep;

		$user->setVar('last_login', time());
		icms::handler('icms_member')->insertUser($user, true);

		$_SESSION['xoopsUserId'] = $user->getVar('uid');
		$_SESSION['xoopsUserGroups'] = $user->getGroups();
		$_SESSION['xoopsUserLastLogin'] = $user->getVar('last_login');

		global $icmsConfig;
		$user_theme = $user->getVar('theme');
		if (in_array($user_theme, $icmsConfig['theme_set_allowed'])) {
			$_SESSION['xoopsUserTheme'] = $user_theme;
		}
		$_SESSION['UserLanguage'] = $user->getVar('language');

		// one-time use: replace the token by a fresh one
		icms_auth_Autologin::rotate($user, $selector);
	}

}

icms_AutologinEventHandler::setup();
