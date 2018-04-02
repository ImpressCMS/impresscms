<?php
/**
 * wrapper for image management
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package     ImpressCMS\Modules\System\Class\Images
 */

/**
 * Handler for managing images

 * @category	ICMS
 * @package		Administration
 * @subpackage	Images
 */
class mod_system_ImagesHandler extends icms_image_Handler {

	/**
	 * Construct the handler
	 * @@param  obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('images');
	}
}
