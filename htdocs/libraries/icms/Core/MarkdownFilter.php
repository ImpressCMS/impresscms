<?php
declare(strict_types=1);
/**
 * Markdown filtering helpers.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	2.0
 */

namespace Icms\Core;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Util\HtmlFilter as CommonMarkHtmlFilter;

class MarkdownFilter extends \icms_core_DataFilter {
	/**
	 * Render Markdown source as safe HTML.
	 */
	public static function filterMarkdown(string $markdown): string {
		if ($markdown === '') {
			return '';
		}

		$rendered = self::getConverter()->convert($markdown)->getContent();

		return \icms_core_HTMLFilter::filterHTML($rendered);
	}

	/**
	 * Return a shared CommonMark converter instance.
	 */
	protected static function getConverter(): CommonMarkConverter {
		static $converter;

		if (!isset($converter)) {
			$converter = new CommonMarkConverter([
				'html_input' => CommonMarkHtmlFilter::ESCAPE,
				'allow_unsafe_links' => false,
			]);
		}

		return $converter;
	}
}

\class_alias(MarkdownFilter::class, 'icms_core_MarkdownFilter');