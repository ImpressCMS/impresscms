<?php

class icms_AutologinEventHandler {

	static public function setup() {
		icms_Event::attach('icms_core_Session', 'sessionStart', array(__CLASS__, 'onSessionStart'));
		icms_Event::attach('icms_core_Session', 'sessionClose', array(__CLASS__, 'onSessionClose'));
	}
	static public function onSessionStart() {
		/**
		 * @var Aura\Session\Session $session
		 */
		$session = \icms::getInstance()->get('session');
		$userSegment = $session->getSegment(icms_member_user_Object::class);

		// Autologin if correct cookie present.
		if ($userSegment->get('userid') && isset($_COOKIE['autologin_uname']) && isset($_COOKIE['autologin_pass'])) {
			self::sessionAutologin($_COOKIE['autologin_uname'], $_COOKIE['autologin_pass']);
		}
	}

	static public function sessionAutologin($autologinName, $autologinPass) {
		/**
		 * @var Aura\Session\Session $session
		 */
		$session = \icms::getInstance()->get('session');
		$autologinSegment = $session->getSegment('autologin');

		if (!empty($_POST)) {
			$autologinSegment->set('post', $_POST);
			$autologinSegment->set('request_uri', $_SERVER['REQUEST_URI']);
			redirect_header(ICMS_URL . '/session_confirm.php', 0, '&nbsp;');
		} elseif (!empty($_SERVER['QUERY_STRING']) && substr($_SERVER['SCRIPT_NAME'], -19) != 'session_confirm.php') {
			$autologinSegment->set('request_uri', $_SERVER['REQUEST_URI']);
			redirect_header(ICMS_URL . '/session_confirm.php', 0, '&nbsp;');
		}

		$uname = icms_core_DataFilter::stripSlashesGPC($autologinName);
		$pass = icms_core_DataFilter::stripSlashesGPC($autologinPass);
		if (empty($uname) || is_numeric($pass)) {
			$user = false;
		} else {
			$uname4sql = addslashes($uname);
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('uname', $uname4sql));
			$user_handler = icms::handler('icms_member_user');
			$users = $user_handler->getObjects($criteria, false);
			if (empty($users) || count($users) != 1) {
				$user = false;
			} else {
				$user = $users[0];
				$old_limit = time() - (defined('ICMS_AUTOLOGIN_LIFETIME')? ICMS_AUTOLOGIN_LIFETIME : 604800);
				list($old_Ynj, $old_encpass) = explode(':', $pass);
				if (strtotime($old_Ynj) < $old_limit || md5($user->pass .
						ICMS_DB_PASS . ICMS_DB_PREFIX . $old_Ynj) != $old_encpass) {
					$user = false;
				}
			}
			unset($users);
		}
		$icms_cookie_path = defined('ICMS_COOKIE_PATH')? ICMS_COOKIE_PATH
			: preg_replace('?http://[^/]+(/.*)$?', "$1", ICMS_URL);
		if ($icms_cookie_path == ICMS_URL) {
			$icms_cookie_path = '/';
		}
		if (false != $user && $user->level > 0) {
			// update time of last login
			$user->setVar('last_login', time());
			if (!icms::handler('icms_member')->insertUser($user, true)) {
			}

			/**
			 * @var Aura\Session\Session $session
			 */
			$session = \icms::getInstance()->get('session');
			$userSegment = $session->getSegment(icms_member_user_Object::class);

			$userSegment->set('userid', $user->uid);
			$userSegment->set('groups', $user->getGroups());

			global $icmsConfig;
			$user_theme = $user->theme;
			$user_language = $user->language;
			if (in_array($user_theme, $icmsConfig['theme_set_allowed'])) {
				$userSegment->set('theme', $user_theme);
			}
			$userSegment->set('language', $user_language);

			// update autologin cookies
			// we need to secure cookie when using SSL
			$secure = substr(ICMS_URL, 0, 5) == 'https'?1:0;
			// 1 week default
			$expire = time()
					+ (defined('ICMS_AUTOLOGIN_LIFETIME')? ICMS_AUTOLOGIN_LIFETIME : 604800);
			setcookie('autologin_uname', $uname, $expire, $icms_cookie_path, '', $secure, 1);
			$Ynj = date('Y-n-j');
			setcookie(
				'autologin_pass', $Ynj . ':' . md5($user->pass . ICMS_DB_PASS . ICMS_DB_PREFIX . $Ynj),
				$expire, $icms_cookie_path, '', $secure, 1
			);
		} else {
			setcookie('autologin_uname', '', time() - 3600, $icms_cookie_path, '', 0, 0);
			setcookie('autologin_pass', '', time() - 3600, $icms_cookie_path, '', 0, 0);
		}
	}

	static public function onSessionClose()
	{
		// autologin hack GIJ (clear autologin cookies)
		$icms_cookie_path = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH
			: preg_replace('?http://[^/]+(/.*)$?', '$1', ICMS_URL);
		if ($icms_cookie_path == ICMS_URL) {
			$icms_cookie_path = '/';
		}
		setcookie('autologin_uname', '', time() - 3600, $icms_cookie_path, '', 0, 0);
		setcookie('autologin_pass', '', time() - 3600, $icms_cookie_path, '', 0, 0);
	}

}

icms_AutologinEventHandler::setup();