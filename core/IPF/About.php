<?php
/**
 * Information about a module
 *
 * @copyright		http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 */

namespace ImpressCMS\Core\IPF;

use icms;
use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\View\Template;

/**
 * About
 *
 * Simple class that lets you build an about page
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.0
 * @author	marcan <marcan@impresscms.org>
 * @todo	Properly set visibility of vars
 * @package	ICMS\IPF
 */
class About {

	/**
	 * Title
	 *
	 * @var string
	 */
	public $_lang_aboutTitle = '';

		/**
		 * Author info
		 *
		 * @var string
		 */
	public $_lang_author_info = '';

		/**
		 * Lead developer
		 *
		 * @var string
		 */
	public $_lang_developer_lead = '';

		/**
		 * Contributor(s)
		 *
		 * @var string
		 */
	public $_lang_developer_contributor = '';

		/**
		 * Developer's website
		 *
		 * @var string
		 */
	public $_lang_developer_website = '';

		/**
		 * Developer's email
		 *
		 * @var string
		 */
	public $_lang_developer_email = '';

		/**
		 *
		 * @var string
		 */
	public $_lang_developer_credits = '';

		/**
		 * Module info
		 *
		 * @var string
		 */
	public $_lang_module_info = '';

		/**
		 * Module status (alfa, beta...)
		 *
		 * @var string
		 */
	public $_lang_module_status = '';

		/**
		 * Release date
		 *
		 * @var string
		 */
	public $_lang_module_release_date = '';

		/**
		 * Demn
		 *
		 * @var string
		 */
	public $_lang_module_demo = '';

		/**
		 * Support address
		 *
		 * @var string
		 */
	public $_lang_module_support = '';

		/**
		 * Bug text
		 *
		 * @var string
		 */
	public $_lang_module_bug = '';

		/**
		 * Bug address
		 *
		 * @var string
		 */
	public $_lang_module_submit_bug = '';

		/**
		 * Feature(s)
		 *
		 * @var string
		 */
	public $_lang_module_feature = '';

		/**
		 * Submit feature link
		 *
		 * @var string
		 */
	public $_lang_module_submit_feature = '';

		/**
		 * Disclaimer
		 *
		 * @var string
		 */
	public $_lang_module_disclaimer = '';

		/**
		 * Author word
		 *
		 * @var string
		 */
	public $_lang_author_word = '';

		/**
		 * Version history
		 *
		 * @var string
		 */
	public $_lang_version_history = '';

		/**
		 * Credits
		 *
		 * @var string
		 */
	public $_lang_by = '';

		/**
		 * Template
		 *
		 * @var \ImpressCMS\Core\View\Template|null
		 */
	public $_tpl;

	/**
	 * Constructor
	 *
	 * Initiate the object, based on $icmsModule
	 *
	 * @param string $aboutTitle text used in the extreme right caption of the menu
	 * @return About
	 */
	public function __construct($aboutTitle = _MODABOUT_ABOUT) {
		global $icmsModule, $icmsConfig;

		icms_loadLanguageFile($icmsModule->getVar("dirname"), 'modinfo');
		icms_loadLanguageFile('core', 'moduleabout');

		$this->_aboutTitle = $aboutTitle;

		$this->_lang_developer_contributor = _MODABOUT_DEVELOPER_CONTRIBUTOR;
		$this->_lang_developer_website = _MODABOUT_DEVELOPER_WEBSITE;
		$this->_lang_developer_email = _MODABOUT_DEVELOPER_EMAIL;
		$this->_lang_developer_credits = _MODABOUT_DEVELOPER_CREDITS;
		$this->_lang_module_info = _MODABOUT_MODULE_INFO;
		$this->_lang_module_status = _MODABOUT_MODULE_STATUS;
		$this->_lang_module_release_date = _MODABOUT_MODULE_RELEASE_DATE;
		$this->_lang_module_demo = _MODABOUT_MODULE_DEMO;
		$this->_lang_module_support = _CO_ICMS_MODULE_SUPPORT;
		$this->_lang_module_bug = _MODABOUT_MODULE_BUG;
		$this->_lang_module_submit_bug = _MODABOUT_MODULE_SUBMIT_BUG;
		$this->_lang_module_feature = _MODABOUT_MODULE_FEATURE;
		$this->_lang_module_submit_feature = _CO_ICMS_MODULE_SUBMIT_FEATURE;
		$this->_lang_module_disclaimer = _MODABOUT_MODULE_DISCLAIMER;
		$this->_lang_author_word = _MODABOUT_AUTHOR_WORD;
		$this->_lang_version_history = _MODABOUT_VERSION_HISTORY;
	}

	/**
	 * Sanitize a value
	 *
	 * @param string $value to be sanitized
	 * @return string sanitized value
	 */
	public function sanitize($value) {
		return DataFilter::checkVar($value, 'html', 'input'); // using input
	}

	/**
	 * Render the whole About page of a module
	 *
	 */
	public function render() {
		global $icmsModule, $icmsConfig;

		icms_cp_header();

		$module_handler = icms::handler('icms_module');
		$versioninfo = & $module_handler->get($icmsModule->getVar('mid'));

		$icmsModule->displayAdminMenu(-1, $this->_aboutTitle . " " . $versioninfo->getInfo('name'));

		$this->_tpl = new Template();

		$this->_tpl->assign('module_url', ICMS_URL . "/modules/" . $icmsModule->getVar('dirname') . "/");
		$this->_tpl->assign('module_image', $versioninfo->getInfo('image'));
		$this->_tpl->assign('module_name', $versioninfo->getInfo('name'));
		$this->_tpl->assign('module_version', $versioninfo->getInfo('version'));
		$this->_tpl->assign('module_status_version', $versioninfo->getInfo('status_version'));

		// Left headings...
		if ($versioninfo->getInfo('author_realname') != '') {
			$author_name = $versioninfo->getInfo('author') . " (" . $versioninfo->getInfo('author_realname') . ")";
		} else {
			$author_name = $versioninfo->getInfo('author');
		}
		$this->_tpl->assign('module_author_name', $author_name);

		$this->_tpl->assign('module_license', $versioninfo->getInfo('license'));

		$this->_tpl->assign('module_credits', $versioninfo->getInfo('credits'));

		// Developers Information
		$this->_tpl->assign('module_developer_lead', $versioninfo->getInfo('developer_lead'));
		$this->_tpl->assign('module_developer_contributor', $versioninfo->getInfo('developer_contributor'));
		$this->_tpl->assign('module_developer_website_url', $versioninfo->getInfo('developer_website_url'));
		$this->_tpl->assign('module_developer_website_name', $versioninfo->getInfo('developer_website_name'));
		$this->_tpl->assign('module_developer_email', $versioninfo->getInfo('developer_email'));

		$people = $versioninfo->getInfo('people');
		if ($people) {

			$this->_tpl->assign('module_people_developers', isset($people['developers'])
				? array_map(array($this, 'sanitize'), $people['developers'])
				: false
			);
			$this->_tpl->assign('module_people_testers', isset($people['testers'])
				? array_map(array($this, 'sanitize'), $people['testers'])
				: false
			);
			$this->_tpl->assign('module_people_translators', isset($people['translators'])
				? array_map(array($this, 'sanitize'), $people['translators'])
				: false
			);
			$this->_tpl->assign('module_people_documenters', isset($people['documenters'])
				? array_map(array($this, 'sanitize'), $people['documenters'])
				: false
			);
			$this->_tpl->assign('module_people_other', isset($people['other'])
				? array_map(array($this, 'sanitize'), $people['other'])
				: false
			);
		}
		//$this->_tpl->assign('module_developers', $versioninfo->getInfo('developer_email'));

		// Module Development information
		$this->_tpl->assign('module_date', $versioninfo->getInfo('date'));
		$this->_tpl->assign('module_status', $versioninfo->getInfo('status'));
		$this->_tpl->assign('module_demo_site_url', $versioninfo->getInfo('demo_site_url'));
		$this->_tpl->assign('module_demo_site_name', $versioninfo->getInfo('demo_site_name'));
		$this->_tpl->assign('module_support_site_url', $versioninfo->getInfo('support_site_url'));
		$this->_tpl->assign('module_support_site_name', $versioninfo->getInfo('support_site_name'));
		$this->_tpl->assign('module_submit_bug', $versioninfo->getInfo('submit_bug'));
		$this->_tpl->assign('module_submit_feature', $versioninfo->getInfo('submit_feature'));

		// Manual
		$manual = $versioninfo->getInfo('manual');
		if ($manual) {
			$this->_tpl->assign('module_manual', isset($manual['wiki'])? array_map(array($this, 'sanitize'), $manual['wiki']):false);
		}

		// Warning
		$this->_tpl->assign('module_warning', DataFilter::checkVar($versioninfo->getInfo('warning'), 'html', 'input'));

		// Author's note
		$this->_tpl->assign('module_author_word', $versioninfo->getInfo('author_word'));

		// For changelog thanks to 3Dev
		//global $icmsModule;
		$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/changelog.txt';
		if (is_file($filename)) {

			$filesize = filesize($filename);
			$handle = fopen($filename, 'r');
			$this->_tpl->assign('module_version_history', DataFilter::checkVar(fread($handle, $filesize), 'text', 'output'));
			fclose($handle);
		}

		$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/docs/changelog.txt';
		if (is_file($filename)) {

			$filesize = filesize($filename);
			$handle = fopen($filename, 'r');
			$this->_tpl->assign('module_version_history', DataFilter::checkVar(fread($handle, $filesize), 'text', 'output'));
			fclose($handle);
		}

		// For license thanks to 3Dev
		if (file_exists(ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/docs/license.txt')) {
			$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/docs/license.txt';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/docs/' . $icmsConfig['language'] . '_license.txt')) {
			$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/docs/' . $icmsConfig['language'] . '_license.txt';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license.txt')) {
			$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license.txt';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license/' . $icmsConfig['language'] . '_license.txt')) {
			$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license/' . $icmsConfig['language'] . '_license.txt';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license.txt')) {
			$filename = ICMS_ROOT_PATH . '/modules/' . $icmsModule->getVar('dirname') . '/license.txt';
		}
		if (is_file($filename)) {
			$filesize = filesize($filename);
			$handle = fopen($filename, 'r');
			$this->_tpl->assign('module_license_txt', DataFilter::checkVar(fread($handle, $filesize), 'text', 'output'));
			fclose($handle);
		}

		$this->_tpl->display('db:admin/system_adm_moduleabout.html');

		icms_cp_footer();
	}
}
