<?php
/**
 * Manage memberships
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupMembership
 * @author		Kazumi Ono (aka onokazo)
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * membership of a user in a group
 *
 * @author		Kazumi Ono <onokazu@xoops.org>
 * 
 * @property INTEGER $linkid        Membership link ID
 * @property INTEGER $groupid       Group ID
 * @property INTEGER $uid           User ID
 */
class icms_member_group_membership_Object extends icms_ipf_Object {
	/**
	 * constructor
	 */
	public function __construct(&$handler, $data = array()) {		
		$this->initVar('linkid', self::DTYPE_INTEGER, null, false);
		$this->initVar('groupid', self::DTYPE_INTEGER, null, false);
		$this->initVar('uid', self::DTYPE_INTEGER, null, false);
                
                parent::__construct($handler, $data);
	}
}
