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
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS page class.
 *
 * @since	ImpressCMS 1.2
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 */
class icms_page_Object extends icms_ipf_Object {

	public function __construct( & $handler) {

		parent::__construct( $handler );

		$this->quickInitVar('page_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('page_moduleid', XOBJ_DTYPE_INT, true);
       	$this->quickInitVar('page_title', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_url', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_status', XOBJ_DTYPE_INT, true);
	}
}

