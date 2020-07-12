<?php
namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
 * Image body
 *
 * @property int    $image_id       Image ID
 * @property string $image_body     Image body
 */
class ImageBody extends AbstractDatabaseModel {

	/**
	 * Constructor
	 */
	public function __construct(&$handler, $data = []) {
		$this->initVar('image_id', self::DTYPE_INTEGER, null, false);
	$this->initVar('image_body', self::DTYPE_STRING, null, true, [
					self::VARCFG_SOURCE_FORMATING => 'binary'
	]);

		parent::__construct($handler, $data);
	}

}
