<?php
/**
 * Authorization classes, OpenID protocol class file
 *
 * This class handles the authentication of a user with its openid. If the the authenticate openid
 * is not found in the users database, the user will be able to create his account on this site or
 * associate its openid with is already registered account. This process is also taking into
 * consideration $icmsConfigPersonaUser['activation_type'].
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Authorization
 * @since		1.1
 * @author		malanciault <marcan@impresscms.org)
 * @credits		Sakimura <http://www.sakimura.org/> Evan Prodromou <http://evan.prodromou.name/>
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 */

class XoopsAuthOpenid extends icms_auth_Openid {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_auth_Openid', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>