<?php
/**
 * ImpressCMS Custom Tag features
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		libraries
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: customtag.php 10897 2010-12-19 18:17:29Z phoenyx $
 */
/**
 *
 * Event triggers for Custom Tags
 * @since	1.2
 *
 */
class IcmsPreloadCustomtag extends icms_preload_Item {
	/**
	 * Function to be triggered at the end of the core boot process
	 */
	function eventFinishCoreBoot() {
		icms_loadLanguageFile("system", "customtag", TRUE);
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
	function eventBeforePreviewTarea($array) {
		$array[0] = self::replaceTagDeterministic($array[0], 'customtag', 'icms_sanitizeCustomtags_callback');
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
	function eventBeforeDisplayTarea($array) {
		$array[0] = self::replaceTagDeterministic($array[0], 'customtag', 'icms_sanitizeCustomtags_callback');
	}

	/**
	 * Function to be triggered at the end of the output init process
	 *
	 * @return	void
	 */
	function eventStartOutputInit() {
		global $icmsTpl;
		$icms_customtag_handler = icms_getModuleHandler("customtag", "system");
		$icms_customTagsObj = $icms_customtag_handler->getCustomtagsByName();
		$customtags_array = array();
		if (is_object($icmsTpl)) {
			foreach ($icms_customTagsObj as $k => $v) {
				$customtags_array[$k] = $v->render();
			}
			$icmsTpl->assign("icmsCustomtags", $customtags_array);
		}
	}

		/**
		 * Deterministically replace [tag]...[/tag] occurrences by invoking a preg-style callback.
		 * Builds a $matches array like preg_replace_callback would: [0] full match, [1] inner.
		 *
		 * @param string   $text
		 * @param string   $tag      e.g. 'customtag'
		 * @param callable $callback e.g. 'icms_sanitizeCustomtags_callback'
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