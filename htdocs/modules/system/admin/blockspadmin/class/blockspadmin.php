<?php
/**
* Blocks position admin classes
*
* @copyright      http://www.impresscms.org/ The ImpressCMS Project
* @license         LICENSE.txt
* @package	(use an appropriate package name)
* @since            1.2
* @author		UnderDog <underdog@impresscms.org>
* @version		$Id$
*/


require_once(ICMS_ROOT_PATH.'/kernel/blockposition.php');

class SystemBlockspadmin extends IcmsBlockposition {

	/**
	 * Constructor
	 *
	 * @param IcmsBlockpositionHandler $handler
	 */
	public function __construct(& $handler) {
		parent::__construct( $handler );
		
		$this->hideFieldFromForm('id');
		$this->hideFieldFromForm('block_default');
		$this->hideFieldFromForm('block_type');
	}


	public function getVar($key, $format = 's') {
		if ( $format == 's' && in_array( $key, array ( 'titlec' ) ) ) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}


	private function titlec(){
		$rtn = defined($this->getVar('title')) ? constant($this->getVar('title')) : $this->getVar('title');
		return $rtn;
	}


	/**
   * getDeleteItemLink
   * 
   * Overwrited Method
   *
   * @param string $onlyUrl
   * @param boolean $withimage
   * @param boolean $userSide
   * @return string
   */
  public function getEditItemLink($onlyUrl=false, $withimage=true, $userSide=false){ 
 	if($this->getVar('block_default') == 1)
 		return "";
  	return parent::getEditItemLink($onlyUrl, $withimage, $userSide);
  }


	/**
   * getDeleteItemLink
   * 
   * Overwrited Method
   *
   * @param string $onlyUrl
   * @param boolean $withimage
   * @param boolean $userSide
   * @return string
   */
  public function getDeleteItemLink($onlyUrl=false, $withimage=true, $userSide=false){ 
 	if($this->getVar('block_default') == 1)
 		return "";
  	return parent::getDeleteItemLink($onlyUrl, $withimage, $userSide);
  }

}


class SystemBlockspadminHandler extends IcmsBlockpositionHandler {
	
	/**
	 * Constructor
	 *
	 * @param IcmsDatabase $db
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'blockspadmin', 'id', 'title', 'description', 'system');
		$this->table = $this->db->prefix('block_positions');		
	}
}

?>