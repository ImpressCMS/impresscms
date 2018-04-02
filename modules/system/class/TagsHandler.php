<?php
/**
 * ImpressCMS Tag Handler
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

/* This may be loaded by other modules - and not just through the cpanel */
icms_loadLanguageFile('system', 'tags', TRUE);

/**
 * Handler for all system tags
 *
 * @package     ImpressCMS\Modules\System\Class\Tags
 */
class mod_system_TagsHandler extends icms_ipf_Handler {

	/**
	 * Construct the tag handler
	 * @param	$db	the database instance
	 */
	public function __construct(&$db) {
		parent::__construct($db, "tags", "id", "tag", "tag", "system");
	}
}
