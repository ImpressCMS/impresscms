<?php
/**
 * HTML Highlighter TextSanitizer plugin
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @since		1.2
 * @package		plugins
 * @version		$Id$
 */
function textsanitizer_syntaxhighlighthtml(&$ts, $text)
{
	$patterns[] = "/\[code_html](.*)\[\/code_html\]/esU";
	$replacements[] = "textsanitizer_geshi_html_highlight( '\\1' )";
	return preg_replace($patterns, $replacements, $text);
}
function textsanitizer_geshi_html_highlight( $source )
{
	if ( !@include_once ICMS_LIBRARIES_PATH . '/geshi/geshi.php' ) return false;
	$source = MyTextSanitizer::undoHtmlSpecialChars($source);

    // Create the new GeSHi object, passing relevant stuff
    $geshi = new GeSHi($source, 'html4strict');
    // Enclose the code in a <div>
    $geshi->set_header_type(GESHI_HEADER_NONE);

	// Sets the proper encoding charset other than "ISO-8859-1"
    $geshi->set_encoding(_CHARSET);

	$geshi->set_link_target ( "_blank" );

    // Parse the code
    $code = $geshi->parse_code();
	$code = "<div class=\"icmsCodeHtml\"><code><pre>".$code."</pre></code></div>";
    return $code;
}
function javascript_syntaxhighlighthtml($ele_name)
{
        $code = "<img onclick='javascript:icmsCodeHTML(\"".$ele_name."\", \"".htmlspecialchars(_ENTERHTMLCODE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"' src='".ICMS_URL."/plugins/textsanitizer/".basename(dirname(__FILE__))."/html.png' alt='html' />&nbsp;";
        $javascript = <<<EOH
				function icmsCodeHTML(id,enterHTMLPhrase){
    				if (enterHTMLPhrase == null) {
    				        enterHTMLPhrase = "Enter The Text To Be HTML Code:";
    				}
					var text = prompt(enterHTMLPhrase, "");
					var domobj = xoopsGetElementById(id);
					if ( text != null && text != "" ) {
						var pos = text.indexOf(unescape('%00'));
						if(0 < pos){
							text = text.substr(0,pos);
						}
					    var result = "[code_html]" + text + "[/code_html]";
					    xoopsInsertText(domobj, result);
					}
					
					domobj.focus();
					}
EOH;

        return array($code, $javascript);
}
function stlye_syntaxhighlighthtml(){
echo'<style type="text/css">
</style>';
}
?>