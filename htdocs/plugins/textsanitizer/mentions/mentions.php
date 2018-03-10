<?php
/**
 * Mentions TextSanitizer plugin
 *
 * @link		http://wiki.impresscms.org/modules/wiki/index.php?page=MentionsPlugin
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		plugins
 */
define('MENTIONS_LINK',	ICMS_URL . '/userinfo.php?uid=%u'); // The link to user profile

/**
 * Finds the mention in the text
 *
 * The mention can be of the form @username or @[User Name] and the username must be the display name of the user
 *
 * @param	str	$text
 * @return	str	String with the pattern replaced by a link
 */
function textsanitizer_mentions($text) {
	return preg_replace_callback("#([\s\R])@(?|([\w\-]+)|\[([\w\s\-]+)\])#", function ($matches) {
		return mentions($matches[2], $matches[1]);
	}, $text);
}

/**
 * Generate a link to user profile from mention
 *
 * @param	str	$text
 * @param	str	$prefix
 * @return	str	link to search results for mention
 */
function mentions($text, $prefix) {
	if (empty($text)) return $text;
	icms_loadLanguageFile("core", "user");
	$userHandler =& icms::handler('icms_member');
	$criteria = new icms_db_criteria_Item('uname', $text);
	$userId = $userHandler->getUsers($criteria);
	if (!$userId) return $prefix . "@" . $text;
	$ret = $prefix . "<a href='" . sprintf(MENTIONS_LINK, $userId[0]->getVar('uid')) . "' title='" . sprintf(_US_ALLABOUT, $text) ."'>@" . $text . "</a>";
	return $ret;
}

 /**
 * Generates the code to add a button to the DHTML editor
  *
 * @param unknown_type $ele_name
 * @return	arr
 */
function render_mentions($ele_name) {
	global $xoTheme;
	$dirname = basename(dirname(__FILE__));
	if (isset($xoTheme)) {
		$xoTheme->addScript(ICMS_PLUGINS_URL . '/textsanitizer/' . $dirname . '/' . $dirname . '.js',
			array('type' => 'text/javascript'));
	}
	$code = "<img
		onclick='javascript:icmsCodeMention(\"" . $ele_name . "\", \"" . htmlspecialchars(_ENTER_MENTION, ENT_QUOTES, _CHARSET) . "\");'
		onmouseover='style.cursor=\"pointer\"'
		src='" . ICMS_PLUGINS_URL . "/textsanitizer/" . $dirname . "/" . $dirname . ".png'
		alt='" . $dirname . "'
		title='" . ucfirst($dirname) . "' />&nbsp;";
	$javascript = 'plugins/textsanitizer/'. $dirname . '/' . $dirname . '.js';

	return array($code, $javascript);
}

/**
 * Pass css style to mentions
 *
 * @return	str	css style for mentions
 */
function style_mentions() {
	$style_info = '';
	return $style_info;
}
