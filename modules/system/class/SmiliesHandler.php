<?php
/**
 * wrapper class for handling smilies
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Handler for modules
 *
 * There is nothing here, for now. There is no smilie class - it is all procedural
 *
 * @todo	Convert to OOP
 * @package     ImpressCMS\Modules\System\Class\Smiles
 */
class mod_system_SmiliesHandler extends \ImpressCMS\Core\IPF\Handler {

	/**
	 * Construct the handler
	 * @@param  obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db, 'smilies', 'id', 'emotion', 'emotion', 'system');
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('smiles');
	}
}
