<?php
/**
 * Richfile Handler
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Data\File
 * @since	1.3
 * @author	Phoenyx
 */

class icms_data_file_Handler extends icms_ipf_Handler {
	/**
	 * constrcutor
	 *
	 * @param object $db database connection
	 */
	public function __construct(&$db) {
		parent::__construct($db, "data_file", "fileid", "caption", "desc", "icms");
	}

	/*
	 * afterDelete event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is deleted
	 *
	 * @param icms_data_file_Object $obj object
	 * @return bool TRUE
	 */
	protected function afterDelete(&$obj) {
		$imgUrl = $obj->getVar("url");
		if (strstr($imgUrl, ICMS_URL) !== false) {
			$imgPath = str_replace(ICMS_URL, ICMS_ROOT_PATH, $imgUrl);
			if (is_file($imgPath)) {
				unlink($imgPath);
			}
		}
		return true;
	}
}