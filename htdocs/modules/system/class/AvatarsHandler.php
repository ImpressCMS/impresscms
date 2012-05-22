<?php
/**
 * wrapper class for handling the avatars
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		Administration
 * @subpackage	Avatars
 * @version		SVN $Id$
 */

/**
 * Handler for avatars
 * @category	ICMS
 * @package		Administration
 * @subpackage	Avatars
 */
class mod_system_AvatarsHandler extends icms_data_avatar_Handler {

	/**
	 *
	 * Constructs the avatar handler
	 *
	 * @param obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('avatar');
	}
}
