<?php
/**
 * Blocks position admin classes
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Block Positions
 * @since		ImpressCMS 1.2
 * @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: blockspadmin.php 11009 2011-02-05 21:17:31Z skenow $
 */

/**
 * System Blockspadmin Class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Block Positions
 * @since 		ImpressCMS 1.2
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class SystemBlockspadmin extends icms_view_block_position_Object {

	/**
	 * Constructor
	 *
	 * @param icms_view_block_position_Handler $handler
	 */
	public function __construct(& $handler) {
		parent::__construct( $handler );

		$this->hideFieldFromForm('id');
		$this->hideFieldFromForm('block_default');
		$this->hideFieldFromForm('block_type');
	}

	/**
	 * Get Custom Title
	 *
	 * @return string
	 */
	public function getCustomTitle() {
		$rtn = defined($this->getVar('title')) ? constant($this->getVar('title')) : $this->getVar('title');
		return $rtn;
	}

	/**
	 * getEditItemLink
	 *
	 * Overwrited Method: a lock instead of a link for core positions
	 *
	 * @param string $onlyUrl
	 * @param boolean $withimage
	 * @param boolean $userSide
	 * @return string
	 */
	public function getEditItemLink($onlyUrl=false, $withimage=true, $userSide=false) {
		if ($this->getVar('block_default') == 1) {
			// core positions cannot be edited or deleted: a lock in their actions column
			return $onlyUrl ? "" : "<img src='" . ICMS_IMAGES_SET_URL . "/actions/lock.png' style='vertical-align: middle;' alt='"
				. _AM_SYSTEM_BLOCKSPADMIN_CORE . "' title='" . _AM_SYSTEM_BLOCKSPADMIN_CORE . "' />";
		}
		return parent::getEditItemLink($onlyUrl, $withimage, $userSide);
	}

	/**
	 * getDeleteItemLink
	 *
	 * Overwrited Method
	 *
	 * @param string $onlyUrl
	 * @param boolean $withimage
	 * @param boolean $userSide
	 * @return string
	 */
	public function getDeleteItemLink($onlyUrl=false, $withimage=true, $userSide=false) {
		if ($this->getVar('block_default') == 1) return "";
		return parent::getDeleteItemLink($onlyUrl, $withimage, $userSide);
	}

}

/**
 * System Blockspadmin Class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Block Positions
 * @since		ImpressCMS 1.2
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class SystemBlockspadminHandler extends icms_view_block_position_Handler {

	/**
	 * Constructor
	 *
	 * @param IcmsDatabase $db
	 */
	public function __construct(& $db) {
		icms_ipf_Handler::__construct($db, 'blockspadmin', 'id', 'title', 'description', 'system');
		$this->table = $this->db->prefix('block_positions');
	}
}

