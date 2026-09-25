<?php
declare(strict_types=1);
/**
 * Manage memberships
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	GroupMembership
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id: Object.php 10326 2010-07-11 18:54:25Z malanciault $
 */
namespace Icms\Member\Group\Membership;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * membership of a user in a group
 *
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @category	ICMS
 * @package		Member
 * @subpackage	Group
 */
class Entity extends \Icms\Core\Entity {
	/**
	 * constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->initVar('linkid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('groupid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
	}
}
