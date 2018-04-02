<?php
/**
 * wrapper for template management
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package     ImpressCMS\Modules\System\Class\TemplatesSets
 */

/**
 * Handler for managing images

 * @category	ICMS
 * @package		Administration
 * @subpackage	Templates
 */
class mod_system_TplsetsHandler extends icms_view_template_set_Handler {

	/**
	 * Construct the handler
	 * @@param  obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('tplset');
	}
}