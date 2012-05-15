<?php
/**
 * Wrapper for administering groups
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		Administration
 * @subpackage	Groups
 * @version		$Id$
 */

/**
 * Handles group administration
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	Groups
 */
class mod_system_GroupsHandler extends icms_member_group_Handler {

	/**
	 * Constructs the handler class for groups
	 *
	 * @param  obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('groups');
	}
}