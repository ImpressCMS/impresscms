<?php
/**
 * Manage of modules
 *

 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Module
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path is not defined');

/**
 * A Module
 *
 * @category	ICMS
 * @package		Module
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 **/
class icms_module_Object extends icms_core_Object {
	/**
	 * Module configuration
	 * @var array
	 */
	public $config = NULL;
	/**
	 * @var string
	 */
	public $modinfo;
	/**
	 * AdminMenu of the module
	 *
	 * @var array
	 */
	public $adminmenu;
	/**
	 * Header menu on admin of the module
	 *
	 * @var array
	 */
	public $adminheadermenu;
	/**
	 * array for messages
	 *
	 * @var array
	 */
	public $messages;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		parent::initVar('mid', XOBJ_DTYPE_INT, null, false);
		parent::initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
		parent::initVar('version', XOBJ_DTYPE_INT, 100, false);
		parent::initVar('last_update', XOBJ_DTYPE_INT, null, false);
		parent::initVar('weight', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('isactive', XOBJ_DTYPE_INT, 1, false);
		parent::initVar('dirname', XOBJ_DTYPE_OTHER, null, true);
		parent::initVar('hasmain', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('hasadmin', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('hassearch', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('hasconfig', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('hascomments', XOBJ_DTYPE_INT, 0, false);
		// RMV-NOTIFY
		parent::initVar('hasnotification', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('dbversion', XOBJ_DTYPE_INT, 0, false);
		parent::initVar('modname', XOBJ_DTYPE_OTHER, null, true);
		parent::initVar('ipf', XOBJ_DTYPE_INT, 0, false);
	}

	/**
	 * Initializes the module/application.
	 * This method is called during execution of icms::launchModule() to allow the module
	 * to setup its global structures.
	 * @return void
	 **/
	public function launch() {
	}

	/**
	 * register class path with autoloader
	 * notice: this function may not be used for the system module
	 *
	 * @param	bool	$isactive	if TRUE, the class path is only registered if the module is active
	 *								if FALSE, the class path is only registered if the module is inactive
	 * @return void
	 */
	public function registerClassPath($isactive = NULL) {
		if (parent::getVar("dirname") == "system") return;
		$class_path = ICMS_ROOT_PATH . "/modules/" . parent::getVar("dirname") . "/class";

		// check if class path exists
		if (!is_dir($class_path)) return;

		// check if module is active (only if applicable)
		if ($isactive !== NULL && parent::getVar("isactive") != (int) $isactive) return;

		// register class path
		if (parent::getVar("ipf")) {
			$modname = (parent::getVar("modname") != "") ?
				parent::getVar("modname") : parent::getVar("dirname");
			icms_Autoloader::register($class_path, "mod_" . $modname);
		} else {
			icms_Autoloader::register($class_path);
		}
	}

	/**
	 * Load module info
	 *
	 * @param   string  $dirname    Directory Name
	 * @param   boolean $verbose
	 **/
	public function loadInfoAsVar($dirname, $verbose = true) {
		if (!isset($this->modinfo)) {self::loadInfo($dirname, $verbose);}
		parent::setVar('name', $this->modinfo['name'], true);
		parent::setVar('version', (int) (100 * ($this->modinfo['version'] + 0.001)), true);
		parent::setVar('dirname', $this->modinfo['dirname'], true);
		$hasmain = (isset($this->modinfo['hasMain']) && $this->modinfo['hasMain'] == 1) ? 1 : 0;
		$hasadmin = (isset($this->modinfo['hasAdmin']) && $this->modinfo['hasAdmin'] == 1) ? 1 : 0;
		$hassearch = (isset($this->modinfo['hasSearch']) && $this->modinfo['hasSearch'] == 1) ? 1 : 0;
		$hasconfig = ((isset($this->modinfo['config']) && is_array($this->modinfo['config'])) || !empty($this->modinfo['hasComments'])) ? 1 : 0;
		$hascomments = (isset($this->modinfo['hasComments']) && $this->modinfo['hasComments'] == 1) ? 1 : 0;
		// RMV-NOTIFY
		$hasnotification = (isset($this->modinfo['hasNotification']) && $this->modinfo['hasNotification'] == 1) ? 1 : 0;
		parent::setVar('hasmain', $hasmain);
		parent::setVar('hasadmin', $hasadmin);
		parent::setVar('hassearch', $hassearch);
		parent::setVar('hasconfig', $hasconfig);
		parent::setVar('hascomments', $hascomments);
		// RMV-NOTIFY
		parent::setVar('hasnotification', $hasnotification);
		parent::setVar('modname', isset($this->modinfo['modname']) ? $this->modinfo['modname'] : "", true);
		$ipf = (isset($this->modinfo['object_items']) && is_array($this->modinfo['object_items'])) ? 1 : 0;
		parent::setVar('ipf', $ipf);
	}

	/**
	 * Get module info
	 *
	 * @param   string  	$name
	 * @return  array|string	Array of module information.
	 * If {@link $name} is set, returns a single module information item as string.
	 **/
	public function &getInfo($name = null) {
		if (!isset($this->modinfo)) {self::loadInfo(parent::getVar('dirname'));}
		if (isset($name)) {
			if (isset($this->modinfo[$name])) {return $this->modinfo[$name];}
			$return = false;
			return $return;
		}
		return $this->modinfo;
	}

	/**
	 * Retreive the database version of this module
	 *
	 * @return int dbversion
	 **/
	public function getDBVersion() {
		$ret = parent::getVar('dbversion');
		return $ret;
	}

	/**
	 * Get a link to the modules main page
	 *
	 * @return	string $ret or FALSE on fail
	 */
	public function mainLink() {
		if (parent::getVar('hasmain') == 1) {
			$ret = '<a href="' . ICMS_URL . '/modules/' . parent::getVar('dirname') . '/">' . parent::getVar('name') . '</a>';
			return $ret;
		}
		return false;
	}

	/**
	 * Get links to the subpages
	 *
	 * @return	string $ret
	 */
	public function subLink() {
		$ret = array();
		if (self::getInfo('sub') && is_array(self::getInfo('sub'))) {
			foreach (self::getInfo('sub') as $submenu) {
				$ret[] = array('name' => $submenu['name'], 'url' => $submenu['url']);
			}
		}
		return $ret;
	}

	/**
	 * Load the admin menu for the module
	 */
	public function loadAdminMenu() {
		if (self::getInfo('adminmenu') && self::getInfo('adminmenu') != '' && file_exists(ICMS_ROOT_PATH . '/modules/' . parent::getVar('dirname') . '/' . self::getInfo('adminmenu'))) {
			include_once ICMS_ROOT_PATH . '/modules/' . parent::getVar('dirname') . '/' . self::getInfo('adminmenu');
			$this->adminmenu = & $adminmenu;
			if (isset($headermenu)) {$this->adminheadermenu = & $headermenu;}
		}
	}

	/**
	 * Get the admin menu for the module
	 *
	 * @return	string $this->adminmenu
	 */
	public function &getAdminMenu() {
		if (!isset($this->adminmenu)) {self::loadAdminMenu();}
		return $this->adminmenu;
	}

	/**
	 * Get the admin header menu for the module
	 *
	 * @return	string $this->adminmenu
	 */
	public function &getAdminHeaderMenu() {
		if (!isset($this->adminheadermenu)) {self::loadAdminMenu();}
		return $this->adminheadermenu;
	}

	/**
	 * Load the module info for this module
	 *
	 * @param   string  $dirname    Module directory
	 * @param   bool    $verbose    Give an error on fail?
	 * @return  bool   TRUE if success, FALSE if fail.
	 */
	public function loadInfo($dirname, $verbose = true) {
		global $icmsConfig;
		icms_loadLanguageFile($dirname, 'modinfo');
		if (file_exists(ICMS_ROOT_PATH . '/modules/' . $dirname . '/icms_version.php')) {
			include ICMS_ROOT_PATH . '/modules/' . $dirname . '/icms_version.php';
		} elseif (file_exists(ICMS_ROOT_PATH . '/modules/' . $dirname . '/xoops_version.php')) {
			include ICMS_ROOT_PATH . '/modules/' . $dirname . '/xoops_version.php';
		} else {
			if (false != $verbose) {echo "Module File for $dirname Not Found!";}
			return false;
		}
		$this->modinfo =& $modversion;
		return true;
	}

	/**
	 * Search contents within a module
	 *
	 * @param   string  $term
	 * @param   string  $andor  'AND' or 'OR'
	 * @param   integer $limit
	 * @param   integer $offset
	 * @param   integer $userid
	 * @return  mixed   Search result or False if fail.
	 **/
	public function search($term = '', $andor = 'AND', $limit = 0, $offset = 0, $userid = 0) {
		if (parent::getVar('hassearch') != 1) {return false;}
		$search = & self::getInfo('search');
		if (parent::getVar('hassearch') != 1 || !isset($search['file']) || !isset($search['func']) || $search['func'] == '' || $search['file'] == '') {
			return false;
		}
		if (file_exists(ICMS_ROOT_PATH . '/modules/' . parent::getVar('dirname') . '/' . $search['file'])) {
			include_once ICMS_ROOT_PATH . '/modules/' . parent::getVar('dirname') . '/' . $search['file'];
		} else {
			return false;
		}
		if (function_exists($search['func'])) {
			$func = $search['func'];
			return $func($term, $andor, $limit, $offset, $userid);
		}
		return false;
	}

	/**
	 * Displays the (good old) adminmenu
	 *
	 * @param int  $currentoption  The current option of the admin menu
	 * @param string  $breadcrumb  The breadcrumb trail
	 * @param bool  $submenus  Show the submenus!
	 * @param int  $currentsub  The current submenu
	 *
	 * @return datatype  description
	 */
	public function displayAdminMenu($currentoption = 0, $breadcrumb = '', $submenus = false, $currentsub = -1) {
		global $icmsModule, $icmsConfig;
		icms_loadLanguageFile($icmsModule->getVar('dirname'), 'modinfo');
		icms_loadLanguageFile($icmsModule->getVar('dirname'), 'admin');
		$tpl = new icms_view_Tpl();
		$tpl->assign(
			array(
				'headermenu' => $this->getAdminHeaderMenu(),
				'adminmenu' => $this->getAdminMenu(),
				'current' => $currentoption,
				'breadcrumb' => $breadcrumb,
				'headermenucount' => count($this->getAdminHeaderMenu()),
				'submenus' => $submenus,
				'currentsub' => $currentsub,
				'submenuscount' => count($submenus)
			)
		);
		$tpl->display(ICMS_ROOT_PATH . '/modules/system/templates/admin/system_adm_modulemenu.html');
	}

	/**#@+
	 * For backward compatibility only!
	 * @deprecated Use parent::getVar('mid') instead
	 * @todo		Remove in version 1.4
	 */
	public function mid() {
		icms_core_Debug::setDeprecated('getVar("mid")', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		return parent::getVar('mid');
	}
	/**
	 *
	 * @deprecated	Use getVar('dirname') instead
	 * @todo 		Remove in version 1.4
	 */
	public function dirname() {
		icms_core_Debug::setDeprecated('getVar("dirname")', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		return parent::getVar('dirname');
	}
	/**
	 *
	 * @deprecated	Use getVar('name') instead
	 * @todo		Remove in version 1.4
	 */
	public function name() {
		icms_core_Debug::setDeprecated('getVar("name")', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		return parent::getVar('name');
	}
	/**
	 *
	 * @deprecated	Use the handler method instead
	 * @todo		Remove in version 1.4
	 * @param unknown_type $dirname
	 */
	public function &getByDirName($dirname) {
		icms_core_Debug::setDeprecated('Module Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		$modhandler = icms::handler('icms_module');
		$inst = & $modhandler->getByDirname($dirname);
		return $inst;
	}

	/**
	 * Modules Message Function
	 *
	 * @since ImpressCMS 1.2
	 * @author Sina Asghari (aka stranger) <stranger@impresscms.org>
	 *
	 * @param string $msg	The Error Message
	 * @param string $title	The Error Message title
	 * @param	bool	$render	Whether to echo (render) or return the HTML string
	 *
	 * @todo Make this work with templates ;)
	 */
	function setMessage($msg, $title = '', $render = false) {
		$ret = '<div class="moduleMsg">';
		if ($title != '') {$ret .= '<h4>' . $title . '</h4>';}
		if (is_array($msg)) {
			foreach ($msg as $m) {$ret .= $m . '<br />';}
		} else {
			$ret .= $msg;
		}
		$ret .= '</div>';
		if ($render) {
			echo $ret;
		} else {
			return $ret;
		}
	}
}
