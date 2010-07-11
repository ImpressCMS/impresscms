<?php
// $Id$
// auth_ads.php - Authentification class for Active Directory
/**
 * Authorization classes, Active Directory class file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	Authorization
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

/**
 * Authentification class for Active Directory
 *
 * @package     kernel
 * @subpackage  auth
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsAuthAds extends icms_auth_Ads {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_auth_Ads', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>