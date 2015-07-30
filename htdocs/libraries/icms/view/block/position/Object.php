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

		$this->initVar('id', self::DTYPE_INTEGER);
		$this->initVar('pname', self::DTYPE_STRING, '', true, 30);
		$this->initVar('title', self::DTYPE_STRING, '', true, 90);
		$this->initVar('description', self::DTYPE_STRING);
		$this->initVar('block_default', self::DTYPE_INTEGER, 0);
		$this->initVar('block_type', self::DTYPE_STRING, 'L', false, 1);
                
                parent::__construct($handler);
	}
}

