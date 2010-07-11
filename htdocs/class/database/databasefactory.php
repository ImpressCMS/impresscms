<?php
/**
 * @package database
 * @subpackage  main
 * @since XOOPS
 * @version $Id$
 *
 * @author		The XOOPS Project Community <http://www.xoops.org>
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

/**
 * ImpressCMS Database Factory Class
 *
 * @package database
 * @subpackage  main
 *
 * @author      Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
class IcmsDatabaseFactory extends icms_database_Factory {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_database_Factory', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * XoopsDatabseFactory Class
 *
 * @package database
 * @subpackage  main
 * @since XOOPS
 *
 * @author		The XOOPS Project Community <http://www.xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @deprecated
 */
class XoopsDatabaseFactory extends IcmsDatabaseFactory { /* For backwards compatibility */ }

?>