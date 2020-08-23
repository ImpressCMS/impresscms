<?php
namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\Database\DatabaseConnectionInterface;

/**
 * Image body storing class
 *
 * @package	ICMS\Image\Body
 */
class ImageBodyHandler extends AbstractExtendedHandler {

		/**
		 * Constructor
		 *
		 * @param DatabaseConnectionInterface $db              Database connection
		 */
		public function __construct(&$db) {
				parent::__construct($db, 'image_body', 'image_id', '', '', 'icms', 'imagebody');
		}

}