<?php
/**
 * ImpressCMS Custom Tag features
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		libraries
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: adsense.php 11316 2011-08-10 02:34:28Z skenow $
 */

/**
 *
 * Preload items and events for AdSense
 * @since 1.2
 *
 */
class IcmsPreloadAdsense extends icms_preload_Item {
	/**
	 * Function to be triggered at the end of the core boot process
	 */
	function eventFinishCoreBoot() {
		icms_loadLanguageFile('system', 'adsense', TRUE);
	}

	/**
	 * Function to be triggered when entering in icms_core_Textsanitizer::displayTarea() function
	 *
	 * The $array var is structured like this:
	 * $array[0] = $text
	 * $array[1] = $html
	 * $array[2] = $smiley
	 * $array[3] = $xcode
	 * $array[4] = $image
	 * $array[5] = $br
	 *
	 * @param array array containing parameters passed by icms_core_Textsanitizer::displayTarea()
	 *
	 * @return	void
	 */
	public function eventAfterPreviewTarea($array) {
		$array[0] = self::replaceTagDeterministic($array[0], 'adsense', 'icms_sanitizeAdsenses_callback');
	}

	/**
	 * Function to be triggered when entering in icms_core_Textsanitizer::displayTarea() function
	 *
	 * The $array var is structured like this:
	 * $array[0] = $text
	 * $array[1] = $html
	 * $array[2] = $smiley
	 * $array[3] = $xcode
	 * $array[4] = $image
	 * $array[5] = $br
	 *
	 * @param array array containing parameters passed by icms_core_Textsanitizer::displayTarea()
	 *
	 * @return	void
	 */
	public function eventAfterDisplayTarea($array) {
		$array[0] = self::replaceTagDeterministic($array[0], 'adsense', 'icms_sanitizeAdsenses_callback');
	}

	/**
	 * Function to be triggered at the end of the output init process
	 *
	 * @return	void
	 */
	public function eventStartOutputInit() {
		global $icmsTpl;
		$icms_adsense_handler = icms_getModuleHandler("adsense", "system");
		$icms_adsensesObj = $icms_adsense_handler->getAdsensesByTag();
		$adsenses_array = array();
		if (is_object($icmsTpl)) {
			foreach ($icms_adsensesObj as $k => $v) {
				$adsenses_array[$k] = $v->render();
			}
			$icmsTpl->assign('icmsAdsenses', $adsenses_array);
		}
	}

	/**
	 * Deterministically replace [tag]...[/tag] occurrences by invoking a preg-style callback.
	 * Builds a $matches array like preg_replace_callback would: [0] full match, [1] inner.
	 *
	 * @param string   $text
	 * @param string   $tag      e.g. 'adsense'
	 * @param callable $callback e.g. 'icms_sanitizeAdsenses_callback'
	 * @return string
	 */
	private static function replaceTagDeterministic(string $text, string $tag, $callback): string
	{
		$open = '[' . $tag . ']';
		$close = '[/' . $tag . ']';
		$pos = 0;
		while (($start = strpos($text, $open, $pos)) !== false) {
			$innerStart = $start + strlen($open);
			$end = strpos($text, $close, $innerStart);
			if ($end === false) {
				break;
			}
			$inner = substr($text, $innerStart, $end - $innerStart);
			$full = substr($text, $start, ($end + strlen($close)) - $start);
			$replacement = call_user_func($callback, array($full, $inner));
			$text = substr($text, 0, $start) . $replacement . substr($text, $end + strlen($close));
			$pos = $start + strlen($replacement);
		}
		return $text;
	}
}

