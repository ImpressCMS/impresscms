<?php
/**
 * Administration of Symlinks
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Symlinks
 * @version		SVN: $Id$
 */

/**
 * Symlinks handler
 *
 * @package		System
 * @subpackage	Symlinks
 */
class mod_system_PagesHandler extends icms_data_page_Handler {

	/** */
	private $modules_name;

	/**
	 * Constructor
	 *
	 * @param $db
	 */
	public function __construct(& $db) {
		icms_ipf_Handler::__construct($db, 'pages', 'page_id', 'page_title', '' , 'system');
		$this->table = $db->prefix('icmspage');
	}

	/**
	 * Get an array of installed modules
	 *
	 * @param boolean $full
	 * @return	array
	 */
	public function getModulesArray($full = FALSE) {
		if (!count($this->modules_name)) {
			$icms_module_handler = icms::handler('icms_module');
			$installed_modules = $icms_module_handler->getObjects();
			foreach ($installed_modules as $module) {
				$this->modules_name[$module->getVar('mid')]['name'] = $module->getVar('name');
				$this->modules_name[$module->getVar('mid')]['dirname'] = $module->getVar('dirname');
			}
		}
		$rtn = $this->modules_name;

		if (!$full) {
			foreach ($this->modules_name as $key => $module) {
				$rtn[$key] = $module['name'];
			}
		}

		return $rtn;
	}

	/**
	 * Change the status of the symlink in the db
	 *
	 * @param $page_id
	 * @return	boolean	FALSE if failed, TRUE if successful
	 */
	public function changeStatus($page_id) {
		$page = $this->get($page_id);
		$page->setVar('page_status', !$page->getVar('page_status'));
		return $this->insert($page, TRUE);
	}
}
