<?php
/**
 * Navigation breadcrumbs
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	marcan <marcan@impresscms.org>
 */

namespace ImpressCMS\Core\View\Renderer;

use ImpressCMS\Core\View\Template;

/**
 * Breadcrumb
 *
 * Managing page breadcrumb
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	marcan <marcan@impresscms.org>
 * @package	ICMS\View
 */
class BreadcrumbRenderer {

	private $_tpl;
	private $items;

	/**
	 * Constructor
	 * @param array $items An array of items for the breadcrumb
	 */
	public function __construct($items) {
		$this->items = $items;
	}

	/**
	 * Adds the breadcrumb items to the template
	 *
	 * @param boolean $fetchOnly Whether to display the breadcrumbs, or not
	 *
	 * @return string|null
	 * @throws \SmartyException
	 */
	public function render($fetchOnly = false) {

		$this->_tpl = new Template();
		$this->_tpl->assign('icms_breadcrumb_items', $this->items);

		if ($fetchOnly) {
			return $this->_tpl->fetch('db:system_breadcrumb.html');
		} else {
			$this->_tpl->display('db:system_breadcrumb.html');
		}
	}
}
