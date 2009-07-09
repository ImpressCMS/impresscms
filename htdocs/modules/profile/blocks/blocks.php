<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license         GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author          Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

if (!defined('ICMS_ROOT_PATH')){ exit(); }

function b_profile_friends_show($options) {
	global $icmsUser;

	if (!empty($icmsUser)){
		// Filter for fetch votes ishot and isnothot
		$criteria = new criteria('friend1_uid', $icmsUser->getVar('uid'));
		// Creating factories of pictures and votes
		$friends_factory = icms_getmodulehandler('friendship', basename(dirname(dirname( __FILE__ ))), 'profile');

		$block['friends'] = $friends_factory->getFriends($options[0], $criteria, 0);
	}

	$block['lang_allfriends'] = _MB_PROFILE_ALLFRIENDS;

	return $block;
}

function b_profile_friends_edit($options) {
	$form = _MB_PROFILE_NUMBER_FRIENDS.": <input type='text' value='".$options['0']."'id='options[]' name='options[]' />";

	return $form;
}

function b_profile_lastpictures_show($options) {
	// Creating factories of pictures and votes
	$pictures_factory = icms_getmodulehandler('images', basename(dirname(dirname( __FILE__ ))), 'profile');
	$block = $pictures_factory->getLastPicturesForBlock($options[0]);

	return $block;
}

function b_profile_lastpictures_edit($options) {
	$form = _MB_PROFILE_NUMBER_PICTURES.": <input type='text' value='".$options['0']."'id='options[]' name='options[]' />";

	return $form;
}
?>