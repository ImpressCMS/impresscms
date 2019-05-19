<?php
/**
 * Hashtag TextSanitizer plugin
 *
 * @link		https://www.impresscms.org/modules/simplywiki/index.php?page=HashtagPlugin
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		plugins
 */
define('HASHTAG_LINK', ICMS_URL . '/search.php?query=%s&amp;action=results'); // The link to search results

/**
 * Finds the hashtag in the text
 *
 * @param	str	$text
 * @return	str	String with the pattern replaced by a link
 */
function textsanitizer_hashtags($text) {
	return preg_replace_callback("#([\s\R])\#(?|([\w\-]+)|\[([\w\s\-]+)\])#", function($matches) {
		return hashtag($matches[2], $matches[1]);
	}, $text);
}

/**
 * Generate a link to search results from hashtag
 *
 * @param	str	$text
 * @param	str	$prefix
 * @return	str	link to search results for hashtag
 */
function hashtag($text, $prefix) {
	if (empty($text)) {
		return $text;
	}
	icms_loadLanguageFile('core', 'search');
	$ret = $prefix . "<a href='" . sprintf(HASHTAG_LINK, urlencode($text)) . "' title='" . _SR_SEARCHRESULTS . ": " . $text . "'>#" . $text . "</a>";
	return $ret;
}

 /**
  * Generates the code to add a button to the DHTML editor
  *
  * @param unknown_type $ele_name
  * @return	arr
  */
function render_hashtags($ele_name) {
	global $xoTheme;
	$dirname = basename(__DIR__);
	if (isset($xoTheme)) {
		$xoTheme->addScript(ICMS_PLUGINS_URL . '/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodeHashtag(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTER_HASHTAG, ENT_QUOTES, _CHARSET) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_PLUGINS_URL . "/textsanitizer/" . $dirname . "/" . $dirname . ".png'
		alt='" . $dirname . "'
		title='" . ucfirst($dirname) . "' />&nbsp;";
	$javascript = 'plugins/textsanitizer/' . $dirname . '/' . $dirname . '.js';

	return array($code, $javascript);
}

/**
 * Pass css style to hashtags
 *
 * @return	str	css style to add to hashtags
 */
function style_hashtags() {
	$style_info = '';
	return $style_info;
}
