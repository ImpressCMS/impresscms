<?php
/**
 * Entry point for captcha and other verification systems
 *
 * Usage
 *
 * Form creation:
 *   $form->addElement(new \Icms\Form\Elements\Captcha($caption, $name));
 *
 * Verification:
 *   $result = \Icms\Captcha\Manager::instance()->verify();
 *   if (!$result->isValid()) {
 *       echo $result->getMessage();
 *   }
 *
 * Extending:
 *   \Icms\Captcha\Manager::instance()->registerProvider(new MyProvider());
 *   then set the captcha_mode preference to the name returned by MyProvider::getName().
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

namespace Icms\Captcha;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

icms_loadLanguageFile('core', 'captcha');

class Manager {

	private const SESSION_KEY = 'icms_captcha';
	private const FALLBACK_PROVIDER = 'text';
	private const HONEYPOT_PROVIDER = 'honeypot';

	private static ?self $instance = null;

	/** @var array<string, \Icms\Captcha\Provider> */
	private array $providers = array();

	private function __construct() {
		$this->registerProvider(new \Icms\Captcha\Provider\Text());
		$this->registerProvider(new \Icms\Captcha\Provider\Image());
		$this->registerProvider(new \Icms\Captcha\Provider\Recaptcha());
		$this->registerProvider(new \Icms\Captcha\Provider\Turnstile());
		$this->registerProvider(new \Icms\Captcha\Provider\Honeypot());
	}

	public static function instance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Read a value of the captcha preferences
	 */
	public static function config(string $key, mixed $default = null): mixed {
		global $icmsConfigCaptcha;

		return $icmsConfigCaptcha[$key] ?? $default;
	}

	/**
	 * Per request storage in the session shared by all providers
	 *
	 * @return array<string, mixed>
	 */
	public static function &session(): array {
		if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
			$_SESSION[self::SESSION_KEY] = array();
		}

		return $_SESSION[self::SESSION_KEY];
	}

	public function registerProvider(\Icms\Captcha\Provider $provider): void {
		$this->providers[$provider->getName()] = $provider;
	}

	/**
	 * @return string[] names of all registered providers
	 */
	public function getProviderNames(): array {
		return array_keys($this->providers);
	}

	/**
	 * Members of a group listed in the captcha_skipmember preference are not challenged
	 */
	public function isSkipped(): bool {
		if (!is_object(\icms::$user)) {
			return false;
		}

		$skipGroups = (array) self::config('captcha_skipmember', array());

		return count(array_intersect(\icms::$user->getGroups(), $skipGroups)) > 0;
	}

	/**
	 * @return \Icms\Captcha\Provider[] providers that challenge the current visitor
	 */
	public function getActiveProviders(): array {
		$mode = (string) self::config('captcha_mode', 'none');
		if ($mode === 'none' || $this->isSkipped()) {
			return array();
		}

		$active = array();
		if ($mode !== self::HONEYPOT_PROVIDER) {
			$active[] = $this->resolve($mode);
		}

		if ($mode === self::HONEYPOT_PROVIDER || self::config('captcha_honeypot', 0)) {
			$active[] = $this->providers[self::HONEYPOT_PROVIDER];
		}

		return $active;
	}

	public function isActive(): bool {
		return count($this->getActiveProviders()) > 0;
	}

	public function render(string $name = 'icmscaptcha'): string {
		$providers = $this->getActiveProviders();
		if (empty($providers)) {
			return '';
		}

		$session =& self::session();
		$session['name'] = $name;

		if ($this->isLockedOut($name)) {
			return ICMS_CAPTCHA_TOOMANYATTEMPTS;
		}

		$html = '';
		foreach ($providers as $provider) {
			$html .= $provider->render($name);
		}

		return $html;
	}

	/**
	 * @param ?string $name name of the form field, the last rendered one when omitted
	 */
	public function verify(?string $name = null): \Icms\Captcha\Result {
		$providers = $this->getActiveProviders();
		if (empty($providers)) {
			return \Icms\Captcha\Result::valid();
		}

		$session =& self::session();
		$name ??= (string) ($session['name'] ?? 'icmscaptcha');

		if ($this->isLockedOut($name)) {
			return \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_TOOMANYATTEMPTS);
		}

		$messages = array();
		foreach ($providers as $provider) {
			$result = $provider->verify($_POST, $name);
			if (!$result->isValid()) {
				$messages = array_merge($messages, $result->getMessages());
			}
		}

		if (!empty($messages)) {
			$session['attempts'][$name] = (int) ($session['attempts'][$name] ?? 0) + 1;

			return \Icms\Captcha\Result::invalid(...array_unique($messages));
		}

		unset($session['attempts'][$name]);

		return \Icms\Captcha\Result::valid();
	}

	private function isLockedOut(string $name): bool {
		$maxAttempts = (int) self::config('captcha_maxattempt', 0);
		if ($maxAttempts <= 0) {
			return false;
		}

		$session =& self::session();

		return (int) ($session['attempts'][$name] ?? 0) >= $maxAttempts;
	}

	/**
	 * Falls back to the built-in text captcha when the chosen provider cannot run,
	 * so a missing API key never leaves a form unprotected
	 */
	private function resolve(string $mode): \Icms\Captcha\Provider {
		$provider = $this->providers[$mode] ?? null;
		if ($provider !== null && $provider->isAvailable()) {
			return $provider;
		}

		return $this->providers[self::FALLBACK_PROVIDER];
	}
}
