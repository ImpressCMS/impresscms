<?php
/**
* icmsbreadcrumb
*
*
*
* @copyright      http://www.impresscms.org/ The ImpressCMS Project
* @license         LICENSE.txt
* @package	breadcrumb
* @since            1.0
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * IcmsBreadcrumb
 *
 * Managing page breadcrumb
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id:icmspersistabletable.php 1948 2008-05-01 19:01:10Z malanciault $
 */
class IcmsBreadcrumb {

	private $_tpl;
	private $items;

	/**
	 * Constructor
	 */
	public function __construct($items) {
		$this->items = $items;
	}

	function render($fetchOnly=false)
	{

		$this->_tpl = new icms_view_Tpl();
		$this->_tpl->assign('icms_breadcrumb_items', $this->items);

		if ($fetchOnly) {
			return $this->_tpl->fetch( 'db:system_breadcrumb.html');
		} else {
			$this->_tpl->display('db:system_breadcrumb.html');
		}
	}
}
