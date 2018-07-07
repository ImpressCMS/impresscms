<?php
/**
 * Block Positions manager for the Impress Persistable Framework
 *
 * Longer description about this page
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	ICMS\View\Block\Position
 * @since	1.0
 */

/* This may be loaded by other modules - and not just through the cpanel */
icms_loadLanguageFile('system', 'positions', true);
/**
 * icms_view_block_position_Handler
 *
 * @package	ICMS\View\Block\Position
 */
class icms_view_block_position_Handler extends icms_ipf_Handler {

	/**
	 * Constructor
	 *
	 * @param IcmsDatabase $db
	 */
	public function __construct(& $db) {
		parent::__construct($db, 'blockposition', 'id', 'title', 'description', 'icms');
		$this->className = 'icms_view_block_position_Object';
		$this->table = $this->db->prefix('block_positions');
	}

	/**
	 * Inserts block position into the database
	 *
	 * @param object  $obj  the block position object
	 * @param bool  $force  force the insertion of the object into the database
	 * @param bool  $checkObject  Check the object before insertion
	 * @param bool  $debug  turn on debug mode?
	 *
	 * @return bool  the result of the insert action
	 */
	public function insert(& $obj, $force = false, $checkObject = true, $debug = false) {
		$obj->setVar('block_default', 0);
		$obj->setVar('block_type', 'L');
		return parent::insert($obj, $force, $checkObject, $debug);
	}
}

