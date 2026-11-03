<?php
/**
 * Outcome of a captcha verification
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_captcha_Result {

	/** @var string[] */
	private array $messages;

	private function __construct(private bool $valid, array $messages = array()) {
		$this->messages = $messages;
	}

	public static function valid(): self {
		return new self(true);
	}

	public static function invalid(string ...$messages): self {
		return new self(false, $messages);
	}

	public function isValid(): bool {
		return $this->valid;
	}

	/**
	 * @return string[]
	 */
	public function getMessages(): array {
		return $this->messages;
	}

	public function getMessage(): string {
		return implode('<br />', $this->messages);
	}
}
