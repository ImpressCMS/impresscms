<?php
/**
 * PHP Highlighter TextSanitizer plugin
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @since		1.2
 * @package		plugins
 * @version		$Id$
 */
function textsanitizer_syntaxhighlightphp(&$ts, $text)
{
	if ( !@include_once ICMS_LIBRARIES_PATH . '/geshi/geshi.php' ) return false;
	$text = MyTextSanitizer::undoHtmlSpecialChars($text);
    // Create the new GeSHi object, passing relevant stuff
    $geshi = new GeSHi($text, 'php');
    // Enclose the code in a <div>
    $geshi->set_header_type(GESHI_HEADER_NONE);

	// Sets the proper encoding charset other than "ISO-8859-1"
    $geshi->set_encoding(_CHARSET);

	$geshi->set_link_target ( "_blank" );

    // Parse the code
    $code = $geshi->parse_code();
	$patterns[] = "/\[code_php](.*)\[\/code_php\]/sU";
	$replacements[] = "$code";
	return preg_replace($patterns, $replacements, $text);
}
?>