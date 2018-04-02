<?php
/**
 * Classes responsible for managing core page objects
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license	LICENSE.txt
 * @since	ImpressCMS 1.1
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 */

/**
 * ImpressCMS page class.
 *
 * @property int    $page_id       Page ID
 * @property int    $page_moduleid Module ID for what this page is it
 * @property string $page_title    Title of page
 * @property string $page_url      URL
 * @property int    $page_status   Status
 *
 * @package	ICMS\Data\Page
 */
class icms_data_page_Object extends icms_ipf_Object {

	public function __construct( & $handler, $data = array()) {
            $this->initVar('page_id', self::DTYPE_INTEGER );
            $this->initVar('page_moduleid', self::DTYPE_INTEGER, 0, true, 8);
            $this->initVar('page_title', self::DTYPE_STRING, '', true, 255);
            $this->initVar('page_url', self::DTYPE_STRING, '', true, 255);
            $this->initVar('page_status', self::DTYPE_INTEGER, 0, true, 1);

            parent::__construct( $handler , $data);
	}

        /**
         * Gets page URL
         *
         * @return string
         */
        public function getURL() {
            return (substr($this->getVar('page_url'), 0, 7) == 'http://')
				? $this->getVar('page_url') : ICMS_URL . '/' . $this->getVar('page_url');
        }
}

