<?php
/**
 * Blocks position admin Handler class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Administration
 * @subpackage	Block Positions
 * @since		ImpressCMS 1.2
 * @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id$
 */
/**
 * System positions Handler Class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Block Positions
 * @since		ImpressCMS 1.2
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class mod_system_PositionsHandler extends icms_view_block_position_Handler {

	/**
	 * Constructor
	 *
	 * @param IcmsDatabase $db
	 */
	public function __construct(& $db) {
		icms_ipf_Handler::__construct($db, 'positions', 'id', 'title', 'description', 'system');
		$this->table = $this->db->prefix('block_positions');
	}
}
