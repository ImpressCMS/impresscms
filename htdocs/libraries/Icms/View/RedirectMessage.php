<?php
/**
 * Redirect messages shown with icms.notify
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		View
 * @subpackage	RedirectMessage
 * @since		2.1
 */

namespace Icms\View;

use Icms\Core\HTMLFilter;
use Icms\View\Theme\Entity as Theme;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * RedirectMessage
 *
 * Shows the message that redirect_header() stores in the session with icms.notify
 * (libraries/notify). It adds the notification script and stylesheet to the theme and an inline
 * call that shows the message once the page is loaded.
 *
 * The markup in the message is filtered with the site's HTML filter (HTMLPurifier). The filter
 * returns the text unchanged when the purifier is disabled and only appends its marker when it
 * ran, so without the marker the message is shown as plain text instead. The message is passed
 * to the script as JSON, so quotes or markup in it cannot break out of the script.
 */
class RedirectMessage {

	/**
	 * Comment that HTMLFilter::filterHTML() appends when the purifier ran
	 */
	private const PURIFIER_MARKER = '<!-- filtered with htmlpurifier -->';

	/**
	 * Adds the notification assets and the call that shows the message to the theme
	 *
	 * @param Theme $theme the theme the assets are added to
	 * @param string $message the message (may contain simple markup)
	 */
	public static function addToTheme(Theme $theme, string $message): void {
		$prepared = self::prepare($message);
		$labels = ['close' => defined('_CLOSE') ? _CLOSE : 'Close'];

		$theme->addStylesheet(ICMS_LIBRARIES_URL . '/notify/notify.css', ['media' => 'screen']);
		$theme->addScript(ICMS_LIBRARIES_URL . '/notify/notify.js', ['type' => 'text/javascript']);
		$theme->addScript('', ['type' => 'text/javascript'], self::buildScript($prepared['text'], $prepared['options'], $labels));
	}

	/**
	 * Filters the message and decides how it is shown
	 *
	 * Messages that contain the heading produced by Entity::getHtmlErrors() are shown as errors.
	 *
	 * @param string $message the message (may contain simple markup)
	 * @return array{text: string, options: array{type: string, html: bool}}
	 */
	public static function prepare(string $message): array {
		$filtered = HTMLFilter::filterHTML($message);
		$purified = strpos($filtered, self::PURIFIER_MARKER) !== false;

		return [
			'text' => $purified ? str_replace(self::PURIFIER_MARKER, '', $filtered) : self::toPlainText($message),
			'options' => [
				'type' => (strpos($message, '<h4>') !== false) ? 'error' : 'info',
				'html' => $purified,
			],
		];
	}

	/**
	 * Reduces a message to plain text: the content of script and style elements is dropped, other
	 * tags are removed and headings, paragraphs, list items and line breaks become new lines
	 *
	 * @param string $message the message (may contain markup)
	 * @return string
	 */
	public static function toPlainText(string $message): string {
		$text = preg_replace('#<(script|style)\b.*?</\1>#is', '', $message);
		$text = preg_replace('#<br\s*/?>|</?(?:h[1-6]|p|li)\b[^>]*>#i', "\n", $text);

		return trim(preg_replace("/\n{2,}/", "\n", strip_tags($text)));
	}

	/**
	 * Builds the inline script that shows the message when the page is loaded
	 *
	 * The JSON_HEX_* flags escape <, >, &, ' and " so nothing in the message or labels can close
	 * the script element or the string it is placed in.
	 *
	 * @param string $text the message
	 * @param array $options the icms.notify options
	 * @param array $labels the icms.notify.labels
	 * @return string
	 */
	public static function buildScript(string $text, array $options, array $labels): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

		return 'document.addEventListener("DOMContentLoaded", function () {'
			. ' icms.notify.labels = ' . json_encode($labels, $flags) . ';'
			. ' icms.notify(' . json_encode($text, $flags) . ', ' . json_encode($options, $flags) . ');'
			. ' });';
	}
}
