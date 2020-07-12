<?php
/**
 * CAPTCHA class
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through CaptchaElement::setConfig("mode", $mode), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since	XOOPS
 * @package	ICMS\Form\Elements\Captcha
 */
namespace ImpressCMS\Core\View\Form\Elements\Captcha;

use icms;

/**
 * Creates the captcha object
 *
 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package	ICMS\Form\Elements\Captcha
 */
class Image {

	public $active = true;
	/** potential values: image, text */
	public $mode = 'text';
	/** */
	public $config = array();
	/** Logging error messages */
	public $message = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		icms_loadLanguageFile('core', 'captcha');

		// Loading default preferences
		$this->config = @include __DIR__ . '/config.php';

		global $icmsConfigCaptcha;
		$this->setMode($icmsConfigCaptcha['captcha_mode']);
	}

	/**
	 * Set CAPTCHA mode
	 *
	 * For future possible modes, right now force to use text or image
	 *
	 * @param string	$mode	if no mode is set, just verify current mode
	 */
	public function setMode($mode = null) {
		if (!empty($mode) && in_array($mode, ['text', 'image'])) {
			$this->mode = $mode;

			if ($this->mode !== "image") {
				return;
			}
		}

		// Disable image mode
		if (!extension_loaded('gd')) {
			$this->mode = 'text';
		} else {
			$required_functions = array(
				'imagecreatetruecolor', 'imagecolorallocate', 'imagefilledrectangle',
				'imagejpeg', 'imagedestroy', 'imageftbbox'
			);
			foreach ($required_functions as $func) {
				if (!function_exists($func)) {
					$this->mode = 'text';
					break;
				}
			}
		}

	}

	/**
	 * Creates instance of CaptchaElement Object
	 * @return  object Reference to the CaptchaElement Object
	 */
	public static function &instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Sets the Captcha Config
	 * @param   string $name Config Name
	 * @param   string $val Config Value
	 * @return  bool  Always returns true if the setting of the config has succeeded
	 */
	public function setConfig($name, $val)
	{
		if ($name == "mode") {
			$this->setMode($val);
		} elseif (isset($this->$name)) {
			$this->$name = $val;
		} else {
			$this->config[$name] = $val;
		}
		return true;
	}

	/**
	 * Initializing the CAPTCHA class
	 * @param   string  $name			 name of the instance
	 * @param   string  $skipmember	   Skip the captcha because the user is member / logged in
	 * @param   string  $num_chars		comes from config, just initializes the variable
	 * @param   string  $fontsize_min	 comes from config, just initializes the variable
	 * @param   string  $fontsize_max	 comes from config, just initializes the variable
	 * @param   string  $background_type  comes from config, just initializes the variable
	 * @param   string  $background_num   comes from config, just initializes the variable
	 */
	public function init(
			$name = 'icmscaptcha', $skipmember = null, $num_chars = null,
			$fontsize_min = null, $fontsize_max = null, $background_type = null,
			$background_num = null) {
		global $icmsConfigCaptcha;
		// Loading RUN-TIME settings
		foreach (array_keys($this->config) as $key) {
			if (isset(${$key}) && ${$key} !== null) {
				$this->config[$key] = ${$key};
			}
		}
		$this->config["name"] = $name;

		// Skip CAPTCHA for group
		//$gperm_handler = \icms::handler('icms_member_groupperm');
		$groups = is_object(icms::$user)? icms::$user->getGroups():array(ICMS_GROUP_ANONYMOUS);
		if (array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember']) && is_object(icms::$user)) {
			$this->active = false;
		} elseif ($icmsConfigCaptcha['captcha_mode'] == 'none') {
			$this->active = false;
		}
	}

	/**
	 * Verify user submission
	 * @param bool $skipMember Skip Captcha because user is member / logged in
	 * @return bool
	 */
	public function verify($skipMember = null) {
		global $icmsConfig, $icmsConfigCaptcha;

		/**
		 * @var \Aura\Session\Session $session
		 */
		$session = icms::getInstance()->get('session');
		$captchaSection = $session->getSegment(__CLASS__);

		$sessionName = $captchaSection->get('name');
		$skipMember = $skipMember ?? $captchaSection->get('skip_member');
		$maxAttempts = (int)$captchaSection->get('max_attempts');

		$is_valid = false;

		$groups = is_object(icms::$user)? icms::$user->getGroups():array(ICMS_GROUP_ANONYMOUS);
		if (is_object(icms::$user) && array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember'])) {
			$is_valid = true;
		} elseif (!empty($maxAttempts) && $captchaSection->get('attempt_' . $sessionName) > $maxAttempts) {
			$this->message[] = ICMS_CAPTCHA_TOOMANYATTEMPTS;

			// Verify the code
		} elseif ($session_code = $captchaSection->get('session_code')) {
			$func = ($icmsConfigCaptcha['captcha_casesensitive'])? 'strcmp' : 'strcasecmp';
			$is_valid = !$func(trim(@$_POST[$sessionName]), $session_code);
		}

		if (!empty($maxAttempts)) {
			if (!$is_valid) {
				// Increase the attempt records on failure
				$captchaSection->set('attempt_' . $sessionName, $captchaSection->get('attempt_' . $sessionName) + 1);
				// Log the error message
				$this->message[] = ICMS_CAPTCHA_INVALID_CODE;

			} else {
				// reset attempt records on success
				$captchaSection->set('attempt_' . $sessionName, null);
			}
		}

		$this->destroyGarbage(true);

		return $is_valid;
	}

	/**
	 * Destory historical stuff
	 * @param bool	$clearSession	also clear session variables?
	 * @return bool True if destroying succeeded
	 */
	public function destroyGarbage($clearSession = false) {
		$class = ($this->mode == 'image') ? ImageMode::class : TextMode::class;
		$captcha_handler = new $class();
		if (method_exists($captcha_handler, 'destroyGarbage')) {
			$captcha_handler->loadConfig($this->config);
			$captcha_handler->destroyGarbage();
		}

		if ($clearSession) {
			/**
			 * @var \Aura\Session\Session $session
			 */
			$session = icms::getInstance()->get('session');
			$captchaSection = $session->getSegment(__CLASS__);

			$captchaSection->set('name', null);
			$captchaSection->set('skip_member', null);
			$captchaSection->set('session_code', null);
			$captchaSection->set('max_attempts', null);
		}

		return true;
	}

	/**
	 * Get Caption
	 * @return string    The Caption Constant
	 */
	public function getCaption()
	{
		return defined('ICMS_CAPTCHA_CAPTION') ? constant('ICMS_CAPTCHA_CAPTION') : '';
	}

	/**
	 * Set Message
	 * @return string    The message
	 */
	public function getMessage()
	{
		return implode('<br />', $this->message);
	}

	/**
	 * Render
	 * @return  string  the rendered form
	 */
	public function render() {
		global $icmsConfigCaptcha;
		$form = '';

		if (!$this->active || empty($this->config['name'])) {
			return $form;
		}

		/**
		 * @var \Aura\Session\Session $session
		 */
		$session = icms::getInstance()->get('session');
		$captchaSection = $session->getSegment(__CLASS__);

		$captchaSection->set('name', $this->config['name']);
		$captchaSection->set('skip_member', $icmsConfigCaptcha['captcha_skipmember']);
		$maxAttempts = $icmsConfigCaptcha['captcha_maxattempt'];
		$captchaSection->set('max_attempts', $maxAttempts);

		 if (!empty($maxAttempts)) {
			 $captchaSection->set('max_attempts_' . $captchaSection->get('name'), $maxAttempts);
		}


		// Fail on too many attempts
		if (!empty($maxAttempts) && $captchaSection->get('max_attempts_' . $captchaSection->get('name')) > $maxAttempts) {
			$form = ICMS_CAPTCHA_TOOMANYATTEMPTS;
			// Load the form element
		} else {
			$form = $this->loadForm();
		}

		return $form;
	}

	/**
	 * Load Form
	 * @return string	The Loaded Captcha Form
	 */
	public function loadForm() {
		$class = ($this->mode == 'image') ? ImageMode::class : TextMode::class;
		$captcha_handler = new $class();
		$captcha_handler->loadConfig($this->config);

		return $captcha_handler->render();
	}
}
