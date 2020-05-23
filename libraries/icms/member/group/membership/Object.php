<?php
/**
 * Manage memberships
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	Kazumi Ono (aka onokazo)
 */

namespace ImpressCMS\Core\Member\Group\Membership;

/**
 * membership of a user in a group
 *
 * @author	Kazumi Ono <onokazu@xoops.org>
 * @package	ICMS\Member\Group\Membership
 *
 * @property int $linkid        Membership link ID
 * @property int $groupid       Group ID
 * @property int $uid           User ID
 */
class icms_member_group_membership_Object extends \ImpressCMS\Core\IPF\AbstractModel {
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
