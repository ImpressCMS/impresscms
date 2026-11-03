<?php
/**
 * Backwards compatible access to the captcha
 *
 * @deprecated since 2.0.4, use icms_captcha_Manager instead
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_form_elements_captcha_Object {

	private static ?self $instance = null;

	private string $message = '';

	public static function &instance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param ?bool $skipMember ignored, the captcha_skipmember preference decides
	 */
	public function verify($skipMember = null): bool {
		$result = icms_captcha_Manager::instance()->verify();
		$this->message = $result->getMessage();

		return $result->isValid();
	}

	public function getMessage(): string {
		return $this->message;
	}
}
