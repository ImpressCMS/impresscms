<?php
namespace ImpressCMS\Core\Models;

/**
 * Image body
 *
 * @property int    $image_id       Image ID
 * @property string $image_body     Image body
 */
class ImageBody extends AbstractExtendedModel {

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
