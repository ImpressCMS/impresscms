<?php
/**
 * wrpapper class for handling the module objects
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		Administration
 * @subpackage	Modules
 * @version		$Id$
 */

class mod_system_ModulesHandler extends icms_module_Handler {

	public function __construct(&$db) {
		parent::__construct($db);
		$this->table = $this->db->prefix('modules');
	}
}