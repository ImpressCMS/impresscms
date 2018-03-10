<?php
/**
 * Image body
 *
 * @property int    $image_id       Image ID
 * @property string $image_body     Image body
 */
class icms_image_body_Object extends \icms_ipf_Object {

    /**
    * Constructor
    */
    public function __construct(&$handler, $data = array()) {
        $this->initVar('image_id', self::DTYPE_INTEGER, null, false);
	$this->initVar('image_body', self::DTYPE_STRING, null, true, array(
                    self::VARCFG_SOURCE_FORMATING => 'binary'
                ));

        parent::__construct($handler, $data);
    }

}
