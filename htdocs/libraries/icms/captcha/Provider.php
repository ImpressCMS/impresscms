<?php
/**
 * Contract for a captcha or verification provider
 *
 * Implement this interface and register the class with
 * icms_captcha_Manager::registerProvider() to make a new verification system
 * available for forms and entry validation.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

interface icms_captcha_Provider {

	/**
	 * Identifier used as value of the captcha_mode preference
	 */
	public function getName(): string;

	/**
	 * Whether the provider has everything it needs (keys, extensions) to run
	 */
	public function isAvailable(): bool;

	/**
	 * HTML to place in the form
	 *
	 * @param string $name name of the form field
	 */
	public function render(string $name): string;

	/**
	 * Verify the submitted request
	 *
	 * @param array<string, mixed> $request submitted data, normally $_POST
	 * @param string $name name of the form field that was rendered
	 */
	public function verify(array $request, string $name): icms_captcha_Result;
}
