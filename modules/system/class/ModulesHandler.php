<?php
/**
 * wrapper class for handling the module objects
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @package     ImpressCMS\Modules\System\Class\Modules
 */

/**
 * Handler for modules
 * @category	ICMS
 * @package		Administration
 * @subpackage	Modules
 */
class mod_system_ModulesHandler extends icms_module_Handler {

	/**
	 * Constructs the handler for modules
	 *
	 * @param obj $db
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the table name
		 * @todo	complete refactoring and use standard database table name
		 */
		$this->table = $this->db->prefix('modules');
	}
}
