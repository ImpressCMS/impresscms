<?php

namespace ImpressCMS\Core\Data;

/**
 * UrlLink Handler
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Data\UrlLink
 * @since	1.3
 * @author	Phoenyx
 */

class UrlLinkHandler extends \ImpressCMS\Core\IPF\Handler {
	/**
	 * constrcutor
	 *
	 * @param object $db database connection
	 */
	public function __construct(&$db) {
		parent::__construct($db, "data_urllink", "urllinkid", "caption", "desc", "icms");
	}
}