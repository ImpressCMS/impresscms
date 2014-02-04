<?php
/**
 * wrapper class for handling comments
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		Administration
 * @subpackage	Comments
 * @version		SVN $Id$
 */

/**
 * Handler for comments
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	Comments
 */
class mod_system_CommentsHandler extends icms_data_comment_Handler {

	/**
	 *
	 * Constructs the comment handler
	 *
	 * @param obj $db	database instance (@see icms_db_Factory::instance)
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		/* overriding the default table name
		 * @todo	complete refactoring and use standard table name
		 */
		$this->table = $this->db->prefix('xoopscomments');
	}
}
