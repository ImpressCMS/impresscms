<?php
/**
* Block Positions manager for the Impress Persistable Framework
*
* Longer description about this page
*
* @copyright      http://www.impresscms.org/ The ImpressCMS Project
* @license         LICENSE.txt
* @package	core
* @since            1.0
* @version		$Id$
*/

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';

/**
 * IcmsBlockposition
 *
 */
class IcmsBlockposition extends IcmsPersistableObject {
	
	/**
	 * Constructor
	 *
	 * @param IcmsBlockpositionHandler $handler
	 */
	public function __construct(& $handler) {
		
		$this->IcmsPersistableObject($handler);
		
		$this->quickInitVar('id', XOBJ_DTYPE_INT);
		$this->quickInitVar('pname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('block_default', XOBJ_DTYPE_INT);
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		
	}
	
}

/**
 * IcmsBlockpositionHandler
 *
 */
class IcmsBlockpositionHandler extends IcmsPersistableObjectHandler {
	
	/**
	 * Constructor
	 *
	 * @param IcmsDatabase $db
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'blockposition', 'id', 'title', 'description', 'icms');
		$this->table = $this->db->prefix('block_positions');
	}
	
	public function insert(& $obj, $force = false, $checkObject = true, $debug=false){
		$obj->setVar('block_default', 0);
		$obj->setVar('block_type', 'L');
		return parent::insert( $obj, $force, $checkObject, $debug );
	}
	
}


?>