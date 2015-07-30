<?php
/**
 * Classes responsible for managing core page objects
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Page
 * @since		ImpressCMS 1.1
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 * @version		SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS page class.
 *
 * @since	ImpressCMS 1.2
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
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
}

