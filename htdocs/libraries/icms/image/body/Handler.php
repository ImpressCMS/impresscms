<?php

/**
 * Image body storing class
 *
 * @package	ICMS\Image\Body
 */
class icms_image_body_Handler extends \icms_ipf_Handler {

        /**
         * Constructor
         * 
         * @param \icms_db_IConnection $db              Database connection
         */
        public function __construct(&$db) {                
                parent::__construct($db, 'image_body', 'image_id', '', '', 'icms', 'imagebody');
        }
        
}