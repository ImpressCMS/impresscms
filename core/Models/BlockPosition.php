<?php
/**
 * Block Positions manager for the Impress Persistable Framework
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	1.0
 */

namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
 * Block position
 *
 * @package	ICMS\View\Block\Position
 *
 * @property int    $id            Block position id
 * @property string $pname         Name (used on codes)
 * @property string $title         Title (displayed for users)
 * @property string $description   Description
 * @property int    $block_default Is default?
 * @property string $block_type    Type
 */
class BlockPosition extends AbstractDatabaseModel {

	/**
	 * Constructor
	 *
	 * @param BlockPositionHandler $handler
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

