<?php
/**
 * HTML Highlighter TextSanitizer plugin
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @since		1.2
 * @package		plugins
 * @subpackage	textsanitizer
 * @version		$Id$
 */

/**
 * Locates and replaces marked text with highlighted HTML text
 *
 * @param string $text the search terms
 */
function textsanitizer_syntaxhighlighthtml($text) {
	// Moved to preload: IcmsPreloadSyntaxHighlight
	return $text;
}

/**
 * Returns passed code snippet as HTML
 *
 * @param $source
 */
function textsanitizer_geshi_html_highlight($source) {
	// Deprecated: handled by preload. Keep a safe non-GeSHi fallback.
	$source = icms_core_DataFilter::undoHtmlSpecialChars($source);
	$code = htmlspecialchars($source, ENT_QUOTES, _CHARSET);
	return "<div class=\"icmsCodeHtml\"><pre><code>".$code."</code></pre></div>";
}

/**
 * Adds button and script to the editor
 *
 * @param $ele_name
 */
function render_syntaxhighlighthtml($ele_name) {
	global $xoTheme;
	$javascript='';
	$dirname = basename(dirname(__FILE__));
	if (isset($xoTheme)) {
		$xoTheme->addScript(
			ICMS_URL.'/plugins/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodeHTML(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTERHTMLCODE, ENT_QUOTES, _CHARSET) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_URL . "/plugins/textsanitizer/" . $dirname . "/html.png'
		alt='html'
		title='HTML' />&nbsp;";
	return array($code, $javascript);
}

/**
 *
 * Enter specific styling for this plugin
 */
function style_syntaxhighlighthtml() {
	$style_info = '';
	return $style_info;
}
