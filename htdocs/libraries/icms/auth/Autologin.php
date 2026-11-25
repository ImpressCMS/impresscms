<?php
/**
 * Persistent login ("remember me") tokens
 *
 * The cookie holds "selector:validator". The selector is used to look up the
 * token row; only a SHA-256 hash of the validator is stored in the database, so
 * a database leak cannot be used to forge cookies. Tokens are rotated on every
 * use, revoked on logout and password change, and a valid selector presented
 * with a wrong validator (a sign the cookie was stolen and already used) revokes
 * every token of that user.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Auth
 */
class icms_auth_Autologin {

	/** Name of the remember-me cookie */
	const COOKIE_NAME = 'icms_autologin';

	/** Names of the cookies used before 2.0.4, only cleared nowadays */
	const LEGACY_COOKIES = array('autologin_uname', 'autologin_pass');

	/**
	 * Lifetime of a token in seconds
	 *
	 * @return int
	 */
	static public function lifetime() {
		return defined('ICMS_AUTOLOGIN_LIFETIME') ? (int) ICMS_AUTOLOGIN_LIFETIME : 604800;
	}

	/**
	 * Is there a remember-me cookie in the current request
	 *
	 * @return bool
	 */
	static public function hasCookie() {
		return !empty($_COOKIE[self::COOKIE_NAME]) && is_string($_COOKIE[self::COOKIE_NAME]);
	}

	/**
	 * Are there cookies of the old (insecure) autologin implementation
	 *
	 * @return bool
	 */
	static public function hasLegacyCookie() {
		foreach (self::LEGACY_COOKIES as $name) {
			if (isset($_COOKIE[$name])) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Create a new token for the user and send it as cookie
	 *
	 * @param icms_member_user_Object $user
	 * @return bool
	 */
	static public function issue($user) {
		$uid = (int) $user->getVar('uid');
		if ($uid <= 0) {
			return false;
		}
		if (random_int(1, 100) === 1) {
			self::gc();
		}

		$selector = bin2hex(random_bytes(12));
		$validator = bin2hex(random_bytes(32));
		$now = time();
		$expires = $now + self::lifetime();

		$db = icms::$xoopsDB;
		$sql = sprintf(
			'INSERT INTO %s (selector, validator_hash, uid, expires, created) VALUES (%s, %s, %d, %d, %d)',
			$db->prefix('autologin_tokens'),
			$db->quoteString($selector),
			$db->quoteString(hash('sha256', $validator)),
			$uid, $expires, $now
		);
		if (!$db->queryF($sql)) {
			return false;
		}
		icms_setCookieVar(self::COOKIE_NAME, $selector . ':' . $validator, $expires);
		return true;
	}

	/**
	 * Validate the cookie value without consuming the token
	 *
	 * @param string $cookie value of the remember-me cookie
	 * @return array|false array($user, $selector) when valid
	 */
	static public function validate($cookie) {
		if (!is_string($cookie) || !preg_match('/^([0-9a-f]{24}):([0-9a-f]{64})$/', $cookie, $matches)) {
			return false;
		}
		list(, $selector, $validator) = $matches;

		$row = self::fetch($selector);
		if (!$row) {
			return false;
		}
		if ((int) $row['expires'] < time()) {
			self::deleteSelector($selector);
			return false;
		}
		if (!hash_equals((string) $row['validator_hash'], hash('sha256', $validator))) {
			// selector is known but the validator is not: the cookie was copied and
			// already rotated by someone else. Kill all sessions of this user.
			self::revokeAll((int) $row['uid']);
			return false;
		}

		$user = icms::handler('icms_member')->getUser((int) $row['uid']);
		if (!is_object($user) || (int) $user->getVar('level') <= 0) {
			self::revokeAll((int) $row['uid']);
			return false;
		}
		return array($user, $selector);
	}

	/**
	 * Give a used token a new validator and expiry date
	 *
	 * The selector is kept on purpose: when a copied cookie was used by someone
	 * else, the next request of the real owner presents this selector with the
	 * old validator, which validate() treats as theft.
	 *
	 * @param icms_member_user_Object $user
	 * @param string $selector selector of the token that was used
	 * @return bool
	 */
	static public function rotate($user, $selector) {
		$validator = bin2hex(random_bytes(32));
		$expires = time() + self::lifetime();
		$db = icms::$xoopsDB;
		$sql = sprintf(
			'UPDATE %s SET validator_hash = %s, expires = %d WHERE selector = %s AND uid = %d',
			$db->prefix('autologin_tokens'),
			$db->quoteString(hash('sha256', $validator)),
			$expires,
			$db->quoteString($selector),
			(int) $user->getVar('uid')
		);
		if (!$db->queryF($sql)) {
			self::clearCookie();
			return false;
		}
		icms_setCookieVar(self::COOKIE_NAME, $selector . ':' . $validator, $expires);
		return true;
	}

	/**
	 * Revoke the token in the current request and clear the cookies
	 */
	static public function revokeCurrent() {
		if (self::hasCookie() && preg_match('/^([0-9a-f]{24}):/', $_COOKIE[self::COOKIE_NAME], $matches)) {
			self::deleteSelector($matches[1]);
		}
		self::clearCookie();
	}

	/**
	 * Revoke all tokens of a user (password change, account removal, theft)
	 *
	 * @param int $uid
	 * @return bool
	 */
	static public function revokeAll($uid) {
		$uid = (int) $uid;
		if ($uid <= 0) {
			return false;
		}
		$db = icms::$xoopsDB;
		return (bool) $db->queryF(sprintf('DELETE FROM %s WHERE uid = %d', $db->prefix('autologin_tokens'), $uid));
	}

	/**
	 * Remove the remember-me cookie and the cookies of the old implementation
	 */
	static public function clearCookie() {
		$past = time() - 3600;
		icms_setCookieVar(self::COOKIE_NAME, '', $past);
		unset($_COOKIE[self::COOKIE_NAME]);

		// the old implementation used a path derived from ICMS_URL
		$paths = array('/');
		$legacyPath = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH : parse_url(ICMS_URL, PHP_URL_PATH);
		if (!empty($legacyPath) && $legacyPath !== '/') {
			$paths[] = $legacyPath;
		}
		foreach (self::LEGACY_COOKIES as $name) {
			if (!isset($_COOKIE[$name])) {
				continue;
			}
			foreach ($paths as $path) {
				setcookie($name, '', array('expires' => $past, 'path' => $path));
			}
			unset($_COOKIE[$name]);
		}
	}

	/**
	 * Remove expired tokens
	 *
	 * @return bool
	 */
	static public function gc() {
		$db = icms::$xoopsDB;
		return (bool) $db->queryF(sprintf('DELETE FROM %s WHERE expires < %d', $db->prefix('autologin_tokens'), time()));
	}

	/**
	 * @param string $selector
	 * @return array|false
	 */
	static private function fetch($selector) {
		$db = icms::$xoopsDB;
		$result = $db->queryF(sprintf(
			'SELECT validator_hash, uid, expires FROM %s WHERE selector = %s',
			$db->prefix('autologin_tokens'), $db->quoteString($selector)
		));
		if (!$result) {
			return false;
		}
		$row = $db->fetchArray($result);
		return is_array($row) ? $row : false;
	}

	/**
	 * @param string $selector
	 * @return bool
	 */
	static private function deleteSelector($selector) {
		$db = icms::$xoopsDB;
		return (bool) $db->queryF(sprintf(
			'DELETE FROM %s WHERE selector = %s',
			$db->prefix('autologin_tokens'), $db->quoteString($selector)
		));
	}
}
