<?php
/**
 * TextSanitizer extension
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package		plugins
 * @since		1.2
 * @version		$Id$
 

	
function textsanitizer_syntaxhighlightcss(&$ts, &$source, $language )
{
	$source = MyTextSanitizer::undoHtmlSpecialChars($source);
	$source = _textsanitizer_geshi_css_highlight( $source, $language );
	return $source;
}


function _textsanitizer_geshi_css_highlight( $source, $language )
{
	if ( !@include_once ICMS_LIBRARIES_PATH . '/geshi/geshi.php' ) return false;

    // Create the new GeSHi object, passing relevant stuff
    $geshi = new GeSHi($source, 'css');
    // Enclose the code in a <div>
    $geshi->set_header_type(GESHI_HEADER_NONE);

	// Sets the proper encoding charset other than "ISO-8859-1"
    $geshi->set_encoding(_CHARSET);

	$geshi->set_link_target ( "_blank" );

    // Parse the code
    $code = $geshi->parse_code();

    return $code;
}*/
?>