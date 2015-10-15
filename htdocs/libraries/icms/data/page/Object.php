<?php
/**
 * Classes responsible for managing core page objects
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license	LICENSE.txt
 * @category	ICMS
 * @package	Page
 * @since	ImpressCMS 1.1
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS page class.
 *
 * @property INTEGER $page_id       Page ID
 * @property INTEGER $page_moduleid Module ID for what this page is it
 * @property STRING $page_title     Title of page
 * @property STRING $page_url       URL
 * @property INTEGER $page_status   Status
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

