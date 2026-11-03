<?php
/**
 * Hides an email address behind the captcha
 *
 * The address is encrypted into a token that is placed in a link. The page
 * behind the link (mailhide.php) shows the address once the visitor passed
 * the captcha chosen in the captcha_mode preference.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

icms_loadLanguageFile('core', 'captcha');

class icms_captcha_Mailhide {

	private const CIPHER = 'aes-256-gcm';
	private const IV_LENGTH = 12;
	private const TAG_LENGTH = 16;

	public static function isAvailable(): bool {
		return function_exists('openssl_encrypt') && in_array(self::CIPHER, openssl_get_cipher_methods(), true);
	}

	public static function encode(string $email): string {
		$iv = random_bytes(self::IV_LENGTH);
		$encrypted = openssl_encrypt($email, self::CIPHER, self::key(), OPENSSL_RAW_DATA, $iv, $tag, '', self::TAG_LENGTH);

		return rtrim(strtr(base64_encode($iv . $tag . $encrypted), '+/', '-_'), '=');
	}

	public static function decode(string $token): ?string {
		$raw = base64_decode(strtr($token, '-_', '+/'), true);
		if ($raw === false || strlen($raw) <= self::IV_LENGTH + self::TAG_LENGTH) {
			return null;
		}

		$iv = substr($raw, 0, self::IV_LENGTH);
		$tag = substr($raw, self::IV_LENGTH, self::TAG_LENGTH);
		$encrypted = substr($raw, self::IV_LENGTH + self::TAG_LENGTH);
		$email = openssl_decrypt($encrypted, self::CIPHER, self::key(), OPENSSL_RAW_DATA, $iv, $tag);

		return $email === false ? null : $email;
	}

	public static function url(string $email): string {
		return ICMS_URL . '/mailhide.php?c=' . self::encode($email);
	}

	/**
	 * Link that replaces the address, showing only its first characters
	 */
	public static function link(string $email): string {
		[$local, $domain] = explode('@', $email, 2) + array('', '');
		$visible = htmlspecialchars(substr($local, 0, 2) . '...@' . $domain, ENT_QUOTES);
		$url = htmlspecialchars(self::url($email), ENT_QUOTES);
		$title = htmlspecialchars(ICMS_CAPTCHA_MAILHIDE_REVEAL, ENT_QUOTES);

		return "<a class='email_protect' href='{$url}' rel='nofollow' title='{$title}'>{$visible}</a>";
	}

	private static function key(): string {
		return hash('sha256', 'icms_mailhide' . XOOPS_DB_SALT, true);
	}
}
