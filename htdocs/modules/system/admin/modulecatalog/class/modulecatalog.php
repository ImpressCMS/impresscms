<?php
/**
 * Modules available on GitHub
 *
 * The rows are a read-only copy of icms_module_Catalog, so the modules admin can show
 * them in an IPF table with sorting, filtering and paging.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Modules
 * @since		2.0
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

icms_loadLanguageFile('system', 'modulesadmin', TRUE);

/**
 * A module published on GitHub
 *
 * @package		System
 * @subpackage	Modules
 */
class SystemModulecatalog extends icms_ipf_Object {

	/**
	 * Constructor
	 *
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('catalogid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX, TRUE, _MD_AM_MODULE);
		$this->quickInitVar('full_name', XOBJ_DTYPE_TXTBOX, TRUE, _MD_AM_GITHUB_REPOSITORY);
		$this->quickInitVar('description', XOBJ_DTYPE_OTHER, FALSE, _MD_AM_GITHUB_DESCRIPTION);
		$this->quickInitVar('html_url', XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar('stars', XOBJ_DTYPE_INT, FALSE, _MD_AM_GITHUB_STARS, '', 0);
		$this->quickInitVar('latest_version', XOBJ_DTYPE_TXTBOX, FALSE, _MD_AM_GITHUB_LATEST);
		$this->quickInitVar('latest_url', XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar('published_at', XOBJ_DTYPE_TXTBOX, FALSE, _MD_AM_GITHUB_PUBLISHED);
	}

	/**
	 * Module name, linking to the repository
	 *
	 * @return	string
	 */
	public function getRepositoryLink() {
		return '<a href="' . htmlspecialchars($this->getVar('html_url', 'n'), ENT_QUOTES) . '" rel="external">'
			. htmlspecialchars($this->getVar('dirname', 'n'), ENT_QUOTES) . '</a><br /><small>'
			. htmlspecialchars($this->getVar('full_name', 'n'), ENT_QUOTES) . '</small>';
	}

	/**
	 * @return	string
	 */
	public function getDescriptionText() {
		return htmlspecialchars($this->getVar('description', 'n'), ENT_QUOTES);
	}

	/**
	 * Latest version, linking to its release notes
	 *
	 * @return	string
	 */
	public function getLatestLink() {
		if ($this->getVar('latest_version', 'n') == '') {
			return _MD_AM_GITHUB_NORELEASE;
		}
		return '<a href="' . htmlspecialchars($this->getVar('latest_url', 'n'), ENT_QUOTES) . '" rel="external">'
			. htmlspecialchars($this->getVar('latest_version', 'n'), ENT_QUOTES) . '</a>';
	}

	/**
	 * Release date, without the time
	 *
	 * @return	string
	 */
	public function getPublishedDate() {
		return htmlspecialchars(substr($this->getVar('published_at', 'n'), 0, 10), ENT_QUOTES);
	}

	/**
	 * Action to download and install the latest release
	 *
	 * @return	string
	 */
	public function getInstallLink() {
		if ($this->getVar('latest_version', 'n') == '') {
			return '';
		}
		return '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=modulesadmin&amp;op=github_update&amp;module='
			. urlencode($this->getVar('dirname', 'n')) . '"><img src="' . ICMS_MODULES_URL . '/system/images/install.png" alt="'
			. _MD_AM_GITHUB_INSTALL . '" title="' . _MD_AM_GITHUB_INSTALL . '" /></a>';
	}
}

/**
 * Handler for the modules available on GitHub
 *
 * @package		System
 * @subpackage	Modules
 */
class SystemModulecatalogHandler extends icms_ipf_Handler {

	/**
	 * Constructor
	 *
	 * @param object $db
	 */
	public function __construct($db) {
		parent::__construct($db, 'modulecatalog', 'catalogid', 'dirname', 'description', 'system');
	}

	/**
	 * Make the table match the catalog: add new repositories, update changed ones, remove those that left
	 *
	 * @param	array	$modules	icms_module_Catalog::getModules()
	 * @return	bool
	 */
	public function sync(array $modules) {
		$existing = array();
		foreach ($this->getObjects(NULL, FALSE) as $obj) {
			$existing[strtolower($obj->getVar('full_name', 'n'))] = $obj;
		}

		$ok = TRUE;
		foreach ($modules as $module) {
			$key = strtolower($module['full_name']);
			$obj = isset($existing[$key]) ? $existing[$key] : $this->create();
			unset($existing[$key]);

			$values = array(
				'dirname' => $module['dirname'],
				'full_name' => $module['full_name'],
				'description' => $module['description'],
				'html_url' => $module['html_url'],
				'stars' => (int) $module['stars'],
				'latest_version' => $module['latest'] ? $module['latest']['version'] : '',
				'latest_url' => $module['latest'] ? $module['latest']['html_url'] : '',
				'published_at' => $module['latest'] ? $module['latest']['published_at'] : ''
			);
			$changed = $obj->isNew();
			foreach ($values as $name => $value) {
				if ((string) $obj->getVar($name, 'n') !== (string) $value) {
					$obj->setVar($name, $value);
					$changed = TRUE;
				}
			}
			if ($changed && !$this->insert($obj, TRUE)) {
				$ok = FALSE;
			}
		}

		foreach ($existing as $obj) {
			if (!$this->delete($obj, TRUE)) {
				$ok = FALSE;
			}
		}
		return $ok;
	}
}
