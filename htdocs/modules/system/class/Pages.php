<?php
/**
 * Administration of Symlinks
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Symlinks
 * @version		SVN: $Id: Pages.php 11585 2012-02-19 05:26:17Z skenow $
 */

/**
 * Symlinks object
 *
 * @package		System
 * @subpackage	Symlinks
 */
class mod_system_Pages extends icms_data_page_Object {

	/**
	 * Constructor
	 */
	public function __construct(& $handler) {
		parent::__construct($handler);

		$this->setControl('page_status', 'yesno');
		$this->setControl('page_moduleid', array(
			'itemHandler' => 'pages',
			'method' => 'getModulesArray',
			'module' => 'system'
			));
	}

	/**
	 * Custom button for updating the status of a symlink
	 * @return	string
	 */
	public function getCustomPageStatus() {
		if ($this->getVar('page_status') == 1) {
			$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=pages&amp;op=status&amp;page_id=' . $this->getVar('page_id')
				. '" title="' . _VISIBLE . '" ><img src="' . ICMS_IMAGES_SET_URL . '/actions/button_ok.png" alt="' . _VISIBLE . '"/></a>';
		} else {
			$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=pages&amp;op=status&amp;page_id=' . $this->getVar('page_id')
				. '" title="' . _VISIBLE . '" ><img src="' . ICMS_IMAGES_SET_URL . '/actions/button_cancel.png" alt="' . _VISIBLE . '"/></a>';
		}
		return $rtn;
	}

	/**
	 * Custom control to retrieve parent module for the symlink
	 * @return	array 	Parent module for the symlink
	 */
	public function getCustomPageModuleid() {
		$modules = $this->handler->getModulesArray();
		return $modules[$this->getVar('page_moduleid')];
	}

	/**
	 * Retrieve title of the symlink
	 * @return	string
	 */
	public function getAdminViewItemLink($onlyUrl = FALSE) {
		$rtn = $this->getVar('page_title');
		return $rtn;
	}

	/**
	 * Build a link to the page represented by the symlink, if available
	 * @return	string
	 */
	public function getViewItemLink($onlyUrl = FALSE, $withimage = TRUE, $userSide = FALSE) {
		$url = (substr($this->getVar('page_url', 'e'), 0, 7) == 'http://')
			? $this->getVar('page_url', 'e')
			: ICMS_URL . '/' . $this->getVar('page_url', 'e');
		$url = icms_core_DataFilter::checkVar($url, 'url', 'host');

		if (!$url) {
			$ret = '';
		} else {
			$ret = '<a href="' . $url . '" alt="' . _PREVIEW . '" title="' . _PREVIEW
				. '" rel="external"><img src="' . ICMS_IMAGES_SET_URL . '/actions/viewmag.png" /></a>';
		}

		return $ret;
	}
}
