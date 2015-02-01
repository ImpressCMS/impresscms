<?php
/**
 * Creates Zipfiles
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @version	$Id: class.zipfile.php 12278 2013-08-31 22:12:36Z fiammy $
 * @deprecated	Use icms_file_ZipFileHandler instead
 * @todo Remove in version 1.4
*/
class zipfile extends icms_file_ZipFileHandler {
	private $_deprecated;
	public function __construct() {
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_file_ZipFileHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
