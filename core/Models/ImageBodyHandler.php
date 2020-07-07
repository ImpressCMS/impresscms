<?php
namespace ImpressCMS\Core\Models;

/**
 * Image body storing class
 *
 * @package	ICMS\Image\Body
 */
class ImageBodyHandler extends \ImpressCMS\Core\IPF\Handler {

		/**
		 * Constructor
		 *
		 * @param \icms_db_IConnection $db              Database connection
		 */
		public function __construct(&$db) {
				parent::__construct($db, 'image_body', 'image_id', '', '', 'icms', 'imagebody');
		}

}