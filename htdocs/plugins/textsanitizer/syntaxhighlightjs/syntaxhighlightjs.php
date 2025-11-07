<?php
/**
 * JavaScript Highlighter TextSanitizer plugin
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
 * Locates and replaces passed text with JS highlighted code block
 *
 * @param string $text the search terms
 */
function textsanitizer_syntaxhighlightjs($text) {
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	return $text;
}

/**
 * Parses passed code into highlighted JS
 *
 * @param $source
 */
function textsanitizer_geshi_js_highlight($source) {
	icms_core_Debug::setDeprecated('textsanitizer_syntaxhighlightcss is no longer in use, use the syntaxhighlighter preload IcmsPreloadSyntaxHighlight instead.');
	$source = icms_core_DataFilter::undoHtmlSpecialChars($source);
	$code = htmlspecialchars($source, ENT_QUOTES, _CHARSET);
	return "<div class=\"icmsCodeJs\"><code>" . $code . "</code></div>";
}

/**
 *
 *  Adds button and script to the editor
 * @param $ele_name
 */
function render_syntaxhighlightjs($ele_name) {
	global $xoTheme;
	$javascript='';
	$dirname = basename(dirname(__FILE__));
	if (isset($xoTheme)) {
		$xoTheme->addScript(
			ICMS_URL . '/plugins/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
        	onclick='javascript:icmsCodeJS(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTERJSCODE, ENT_QUOTES, _CHARSET) . "\");'
        	onmouseover='style.cursor=\"pointer\"'
        	src='" . ICMS_URL."/plugins/textsanitizer/" . $dirname . "/js.png'
        	alt='js'
        	title='Javascript' />&nbsp;";
	return array($code, $javascript);
}
/*function style_syntaxhighlightjs() {
 $style_info = '.icmsCodeJs { background-color: #FAFAFA; color: #444; font-size: .9em; line-height: 1.2em; text-align: justify; border: #c2cdd6 1px dashed;}';
 return $style_info;
 }*/
