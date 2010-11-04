<?php
/**
 * Object for user rank
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @category	ICMS
 * @package		Data
 * @subpackage	Rank
 * @version		SVN: $Id$
 */
/**
 * Class for creating a user rank
 *
 * @category	ICMS
 * @package		Data
 * @subpackage	Rank
 */
class icms_data_rank_Object extends icms_core_Object {

	/**
	 * Constructor
	 **/
	public function __construct() {
		parent::__construct();
		$this->initVar('rank_id', XOBJ_DTYPE_INT, NULL, FALSE);
		$this->initVar('rank_title', XOBJ_DTYPE_TXTBOX, NULL, FALSE);
		$this->initVar('rank_min', XOBJ_DTYPE_INT, 0);
		$this->initVar('rank_max', XOBJ_DTYPE_INT, 0);
		$this->initVar('rank_special', XOBJ_DTYPE_INT, 0);
		$this->initVar('rank_image', XOBJ_DTYPE_TXTBOX, "");
	}
}
