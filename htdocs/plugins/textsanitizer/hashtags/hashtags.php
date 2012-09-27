<?php
/**
 * Hashtag TextSanitizer plugin
 *
 * @link		http://wiki.impresscms.org/modules/wiki/index.php?page=HashtagPlugin
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		plugins
 * @version		$Id: hashtags.php 11691 2012-04-14 21:15:44Z skenow $
 */
define('HASHTAG_LINK',	ICMS_URL . '/search.php?query=%s&amp;action=results'); // The link to search results

/**
 * Finds the hashtag in the text
 *
 * @param	obj	$ts
 * @param	str	$text
 * @return	str	String with the pattern replaced by a link
 */
function textsanitizer_hashtags(&$ts, $text) {
	$patterns[] = "#([\s\R])\#(?|([\w\-]+)|\[([\w\s\-]+)\])#e";
	$replacements[] = "hashtag('\\2', '\\1')";
	return preg_replace($patterns, $replacements, $text);
}

/**
 * Generate a link to search results from hashtag
 *
 * @param	str	$text
 * @param	str	$prefix
 * @return	str	link to search results for hashtag
 */
function hashtag($text, $prefix) {
	if (empty($text)) return $text;
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
	$dirname = basename(dirname(__FILE__));
	if (isset($xoTheme)) {
		$xoTheme->addScript(ICMS_PLUGINS_URL . '/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodeHashtag(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTER_HASHTAG, ENT_QUOTES) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_PLUGINS_URL . "/textsanitizer/" . $dirname . "/" . $dirname . ".png'
		alt='" . $dirname . "'
		title='" . ucfirst($dirname) . "' />&nbsp;";
	$javascript = 'plugins/textsanitizer/'. $dirname . '/' . $dirname . '.js';

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
