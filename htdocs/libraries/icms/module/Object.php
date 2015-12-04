<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Manage of modules
 *

 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path is not defined');

/**
 * A Module
 *
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\Module
 * 
 * @property int    $mid               Module ID
 * @property string $name              Name displayed for users
 * @property int    $version           Version
 * @property int    $last_update       Last update
 * @property int    $weight            Weigth used for sorting modules on lists
 * @property int    $isactive          Is activated?
 * @property string $dirname           Directory name
 * @property int    $hasmain           Has main page?
 * @property int    $hasadmin          Has admin?
 * @property int    $hassearch         Has search?
 * @property int    $hasconfig         Has config?
 * @property int    $hascomments       Has comments?
 * @property int    $hasnotification   Has notifications?
 * @property int    $dbversion         Database version
 * @property string $modname           Internal name
 * @property int    $ipf               Is this module IPF based?
 */
class icms_module_Object 
    extends icms_ipf_Object {
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
	public function __construct(&$handler, $data = array()) {		
		$this->initVar('mid', self::DTYPE_INTEGER, null, false);
		$this->initVar('name', self::DTYPE_STRING, null, true, 150);
		$this->initVar('version', self::DTYPE_INTEGER, 100, false);
		$this->initVar('last_update', self::DTYPE_INTEGER, null, false);
		$this->initVar('weight', self::DTYPE_INTEGER, 0, false);
		$this->initVar('isactive', self::DTYPE_INTEGER, 1, false);
		$this->initVar('dirname', self::DTYPE_STRING, null, true, 25);
		$this->initVar('hasmain', self::DTYPE_INTEGER, 0, false);
		$this->initVar('hasadmin', self::DTYPE_INTEGER, 0, false);
		$this->initVar('hassearch', self::DTYPE_INTEGER, 0, false);
		$this->initVar('hasconfig', self::DTYPE_INTEGER, 0, false);
		$this->initVar('hascomments', self::DTYPE_INTEGER, 0, false);
		// RMV-NOTIFY
		$this->initVar('hasnotification', self::DTYPE_INTEGER, 0, false);
		$this->initVar('dbversion', self::DTYPE_INTEGER, 0, false);
		$this->initVar('modname', self::DTYPE_STRING, null, true, 25);
		$this->initVar('ipf', self::DTYPE_INTEGER, 0, false);
                
        parent::__construct($handler, $data);
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
		//if ($this->getVar("dirname") == "system") return;
		$class_path = ICMS_MODULES_PATH . "/" . $this->getVar("dirname") . "/class";

		// check if class path exists
		if (!is_dir($class_path)) return;

		// check if module is active (only if applicable)
		if ($isactive !== NULL && $this->getVar("isactive") != (int) $isactive) return;

		// register class path
		if ($this->getVar("ipf")) {
			$modname = ($this->getVar("modname") != "") ?
				$this->getVar("modname") : $this->getVar("dirname");
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
		if (!isset($this->modinfo)) {$this->loadInfo($dirname, $verbose);}
		$this->setVar('name', $this->modinfo['name'], true);
		$this->setVar('version', (int) (100 * ($this->modinfo['version'] + 0.001)), true);
		$this->setVar('dirname', $this->modinfo['dirname'], true);
		$hasmain = (isset($this->modinfo['hasMain']) && $this->modinfo['hasMain'] == 1) ? 1 : 0;
		$hasadmin = (isset($this->modinfo['hasAdmin']) && $this->modinfo['hasAdmin'] == 1) ? 1 : 0;
		$hassearch = (isset($this->modinfo['hasSearch']) && $this->modinfo['hasSearch'] == 1) ? 1 : 0;
		$hasconfig = ((isset($this->modinfo['config']) && is_array($this->modinfo['config'])) || !empty($this->modinfo['hasComments'])) ? 1 : 0;
		$hascomments = (isset($this->modinfo['hasComments']) && $this->modinfo['hasComments'] == 1) ? 1 : 0;
		// RMV-NOTIFY
		$hasnotification = (isset($this->modinfo['hasNotification']) && $this->modinfo['hasNotification'] == 1) ? 1 : 0;
		$this->setVar('hasmain', $hasmain);
		$this->setVar('hasadmin', $hasadmin);
		$this->setVar('hassearch', $hassearch);
		$this->setVar('hasconfig', $hasconfig);
		$this->setVar('hascomments', $hascomments);
		// RMV-NOTIFY
		$this->setVar('hasnotification', $hasnotification);
		$this->setVar('modname', isset($this->modinfo['modname']) ? $this->modinfo['modname'] : "", true);
		$ipf = (isset($this->modinfo['object_items']) && is_array($this->modinfo['object_items'])) ? 1 : 0;
		$this->setVar('ipf', $ipf);
	}

	/**
	 * Get module info
	 *
	 * @param   string  	$name
	 * @return  array|string	Array of module information.
	 * If {@link $name} is set, returns a single module information item as string.
	 **/
	public function &getInfo($name = null) {
		if (!isset($this->modinfo)) {$this->loadInfo($this->getVar('dirname'));}
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
		$ret = $this->getVar('dbversion');
		return $ret;
	}

	/**
	 * Get a link to the modules main page
	 *
	 * @return	string $ret or FALSE on fail
	 */
	public function mainLink() {
		if ($this->getVar('hasmain') == 1) {
			$ret = '<a href="' . ICMS_URL . '/modules/' . $this->getVar('dirname') . '/">' . $this->getVar('name') . '</a>';
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
		if ($this->getInfo('sub') && is_array($this->getInfo('sub'))) {
			foreach ($this->getInfo('sub') as $submenu) {
				$ret[] = array('name' => $submenu['name'], 'url' => $submenu['url']);
			}
		}
		return $ret;
	}

	/**
	 * Load the admin menu for the module
	 */
	public function loadAdminMenu() {
		if ($this->getInfo('adminmenu')
			&& $this->getInfo('adminmenu') != ''
			&& file_exists(ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/' . $this->getInfo('adminmenu'))
		) {
			include_once ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/' . $this->getInfo('adminmenu');
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
		if (!isset($this->adminmenu)) {$this->loadAdminMenu();}
		return $this->adminmenu;
	}

	/**
	 * Get the admin header menu for the module
	 *
	 * @return	string $this->adminmenu
	 */
	public function &getAdminHeaderMenu() {
		if (!isset($this->adminheadermenu)) {$this->loadAdminMenu();}
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
		if ($this->getVar('hassearch') != 1) {return false;}
		$search = & $this->getInfo('search');
		if ($this->getVar('hassearch') != 1 || !isset($search['file']) || !isset($search['func']) || $search['func'] == '' || $search['file'] == '') {
			return false;
		}
		if (file_exists(ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/' . $search['file'])) {
			include_once ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/' . $search['file'];
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
		$tpl->display('db:admin/system_adm_modulemenu.html');
	}
        
    /**
     * Get admin menu items for current module
     * 
     * @return array
     */
    public function getAdminMenuItems() {
        $inf = & $this->getInfo();
        $url = ICMS_MODULES_URL . DIRECTORY_SEPARATOR . $this->dirname . DIRECTORY_SEPARATOR;
        $rtn = [
            'link' => $url . (isset($inf['adminindex']) ? $inf['adminindex'] : ''),
            'title' => $this->getVar('name'),
            'dir' => $this->dirname,
            'absolute' => 1,
            'subs' => []
        ];
        if (isset($inf['iconsmall']) && $inf['iconsmall'] != '') {
            $rtn['small'] = $url . $inf['iconsmall'];
        }
        if (isset($inf['iconbig']) && $inf['iconbig'] != '') {
            $rtn['iconbig'] = $url . $inf['iconbig'];
        }
        $this->loadAdminMenu();
        if (is_array($this->adminmenu) && count($this->adminmenu) > 0) {
            foreach ($this->adminmenu as $item) {
                $item['link'] = $url . $item['link'];
                $rtn['subs'][] = $item;
            }
        }
        if ($this->hasconfig || $this->hascomments) {
            $rtn['subs'][] = [
                'title' => _PREFERENCES,
                'link' => ICMS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $this->mid
            ];
        }
        $rtn['hassubs'] = (count($rtn['subs']) > 0) ? 1 : 0;
        if ($rtn['hassubs'] == 0) {
            unset($rtn['subs']);
        }
        return $rtn;
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
	public function setMessage($msg, $title = '', $render = false) {
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
