<?php
/**
 * Wiki TextSanitizer plugin
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @since		1.2
 * @package		plugins
 * @version		$Id$
 */
define('WIKI_LINK',	'http://'._LANGCODE.'.wikipedia.org/wiki/%s'); // The link to wiki module
function textsanitizer_wiki(&$ts, $text)
{
	$patterns[] = "/\[\[([^\]]*)\]\]/esU";
	$replacements[] = "wikiLink( '\\1' )";
	return preg_replace($patterns, $replacements, $text);
}
function wikiLink($text)
{
	if ( empty($text) ) return $text;
	$ret = "<a href='".sprintf( WIKI_LINK, $text )."' target='_blank' title=''>".$text."</a>";
	return $ret;
}
?>