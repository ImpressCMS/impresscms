<?php
/**
 * PHP Highlighter TextSanitizer plugin
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
 * Locates and replaces text with code block highlighted as PHP
 *
 * @param object $ts textsanitizer instance
 * @param string $text the search terms
 */
function textsanitizer_syntaxhighlightphp($text) {
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	return $text;
}

/**
 * Parses the passed text into highlighted PHP code block
 *
 * @param $source
 */
function textsanitizer_geshi_php_highlight($source) {
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	$source = icms_core_DataFilter::undoHtmlSpecialChars($source);
	$code = htmlspecialchars($source, ENT_QUOTES, _CHARSET);
	return "<div class=\"icmsCodePhp\"><code>" . $code . "</code></div>";
}

/**
 * Adds button and script to the editor
 *
 * @param $ele_name
 */
function render_syntaxhighlightphp($ele_name) {
	global $xoTheme;
	$javascript='';
	$dirname = basename(__DIR__);
	if (isset($xoTheme)) {
		$xoTheme->addScript(
			ICMS_URL.'/plugins/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodePHP(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTERPHPCODE, ENT_QUOTES, _CHARSET) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_URL . "/plugins/textsanitizer/" . $dirname . "/php.png'
		alt='php'
		title='PHP' />&nbsp;";
	return array($code, $javascript);
}

/**
 *
 * Enter specific styling for this plugin
 */
function style_syntaxhighlightphp() {
	$style_info = '';
	return $style_info;
}
