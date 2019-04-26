<?php
/**
 * UrlLink Handler
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Data\UrlLink
 * @since	1.3
 * @author	Phoenyx
 */

class icms_data_urllink_Handler extends icms_ipf_Handler {
	/**
	 * constrcutor
	 *
	 * @param object $db database connection
	 */
	public function __construct(&$db) {
		parent::__construct($db, "data_urllink", "urllinkid", "caption", "desc", "icms");
	}
}