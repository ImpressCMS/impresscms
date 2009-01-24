<?php
/**
 * Hidden Content TextSanitizer plugin
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @since		1.2
 * @package		plugins
 * @version		$Id$
 */
function textsanitizer_hiddencontent(&$ts, $text)
{
        	$patterns[] = "/\[hide](.*)\[\/hide\]/sU";
			if($_SESSION['xoopsUserId'])
			{$replacements[] = _HIDDENC.'<div class="icmsHidden">\\1</div>';}
			else{$replacements[] = _HIDDENC.'<div class="icmsHidden">'._HIDDENTEXT.'</div>';}
			$replacements[] = '<div class="icmsHidden">\\1</div>';
	return preg_replace($patterns, $replacements, $text);
}
function javascript_hiddencontent($ele_name)
{
        $code = "<img onclick='javascript:icmsCodeHidden(\"".$ele_name."\", \"".htmlspecialchars(_ENTERHIDDEN, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"' src='".ICMS_URL."/images/hide.gif' alt='hide' />&nbsp;";
        $javascript = <<<EOH
				function icmsCodeHidden(id,enterHiddenPhrase){
    				if (enterHiddenPhrase == null) {
    				        enterHiddenPhrase = "Enter The Text To Be Hidden:";
    				}
					var text = prompt(enterHiddenPhrase, "");
					var domobj = xoopsGetElementById(id);
					if ( text != null && text != "" ) {
						var pos = text.indexOf(unescape('%00'));
						if(0 < pos){
							text = text.substr(0,pos);
						}
					    var result = "[hide]" + text + "[/hide]";
					    xoopsInsertText(domobj, result);
					}
					
					domobj.focus();
					}
EOH;

        return array($code, $javascript);
}
function stlye_hiddencontent(){
echo'<style type="text/css">
</style>';
}
?>