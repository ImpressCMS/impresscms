<?php
/**
 * TextSanitizer extension
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id$
 * @package		Frameworks::textsanitizer
 */

	
function textsanitizer_syntaxhighlight(&$ts, &$source, $language )
{
//	$source = MyTextSanitizer::undoHtmlSpecialChars($source);
	$source = _textsanitizer_php_highlight($source);
	return $source;
}

function _textsanitizer_php_highlight($text)
{
	$text = trim($text);
	$addedtag_open = 0;
	if ( !strpos($text, "<?php") and (substr($text, 0, 5) != "<?php") ) {
		$text = "<?php\n" . $text;
		$addedtag_open = 1;
	}
	$addedtag_close = 0;
	if ( !strpos($text, "?>") ) {
		$text .= "?>";
		$addedtag_close = 1;
	}
	$oldlevel = error_reporting(0);
	$buffer = highlight_string($text, true); // Require PHP 4.20+
	error_reporting($oldlevel);
	$pos_open = $pos_close = 0;
	if ($addedtag_open) {
		$pos_open = strpos($buffer, '&lt;?php');
	}
	if ($addedtag_close) {
		$pos_close = strrpos($buffer, '?&gt;');
	}
	
	$str_open = ($addedtag_open) ? substr($buffer, 0, $pos_open) : "";
	$str_close = ($pos_close) ? substr($buffer, $pos_close + 5) : "";
	
	$length_open = ($addedtag_open) ? $pos_open + 8 : 0;
	$length_text = ($pos_close) ? $pos_close - $length_open : 0;
	$str_internal = ($length_text) ? substr($buffer, $length_open, $length_text) : substr($buffer, $length_open);
	
	$buffer = $str_open.$str_internal.$str_close;
	return $buffer;
}

?>