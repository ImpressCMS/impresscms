<?php
/**
 * Block Positions manager for the Impress Persistable Framework
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 * @since		1.0
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * icms_view_block_position_Object
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 */
class icms_view_block_position_Object extends icms_ipf_Object {

	/**
	 * Constructor
	 *
	 * @param icms_view_block_position_Handler $handler
	 */
	public function __construct(& $handler) {

		parent::__construct($handler);

		$this->quickInitVar('id', self::DTYPE_INTEGER);
		$this->quickInitVar('pname', self::DTYPE_DEP_TXTBOX, true);
		$this->quickInitVar('title', self::DTYPE_DEP_TXTBOX, true);
		$this->quickInitVar('description', self::DTYPE_STRING);
		$this->quickInitVar('block_default', self::DTYPE_INTEGER);
		$this->quickInitVar('block_type', self::DTYPE_DEP_TXTBOX);

	}
}

