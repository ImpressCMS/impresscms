<?php
/**
 * CSS Highlighter TextSanitizer plugin
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
 * Pattern replacements for snippets of css stylesheets
 *
 * @param string $text the search terms
 */
function textsanitizer_syntaxhighlightcss($text) {
	// Moved to preload: IcmsPreloadSyntaxHighlight
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	return $text;
}

/**
 * Highlights the passed source code as css
 *
 * @param $source
 */
function textsanitizer_geshi_css_highlight($source) {
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	$source = icms_core_DataFilter::undoHtmlSpecialChars($source);
	$code = htmlspecialchars($source, ENT_QUOTES, _CHARSET);
	return "<div class=\"icmsCodeCss\"><code>" . $code . "</code></div>";
}

/**
 *
 * Adds javascript and icon to editor
 * @param $ele_name
 */
function render_syntaxhighlightcss($ele_name) {
	global $xoTheme;
	$javascript='';
	$dirname = basename(__DIR__);
	if (isset($xoTheme)) {
		$xoTheme->addScript(
			ICMS_URL.'/plugins/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodeCSS(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTERCSSCODE, ENT_QUOTES, _CHARSET) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_URL . "/plugins/textsanitizer/" . $dirname . "/css.png'
		alt='css'
		title='css' />&nbsp;";
	return array($code, $javascript);
}

/**
 *
 * Enter specific styling for this plugin
 */
function style_syntaxhighlightcss() {
	$style_info = '';
	return $style_info;
}
