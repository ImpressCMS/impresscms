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
 * Manage modules
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    LICENSE.txt
 */

namespace ImpressCMS\Core\Models;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Database\Legacy\Updater\TableUpdater;
use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Facades\Member;
use ImpressCMS\Core\File\Filesystem;
use ImpressCMS\Core\View\Template;

/**
 * Module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of module class objects.
 *
 * @package    ICMS\Module
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @copyright    Copyright (c) 2000 XOOPS.org
 */
class ModuleHandler
	extends AbstractExtendedHandler
{

	/**
	 * Constructor
	 *
	 * @param object $db
	 * @param string $module
	 */
	public function __construct(&$db, $module = 'icms')
	{
		if (!$module) {
			$module = 'icms_member';
		}
		parent::__construct($db, 'module', 'mid', 'dirname', 'name', $module, 'modules', true);
	}

	/**
	 * Returns an array of all available modules, based on folders in the modules directory
	 *
	 * The getList method cannot be used for this, because uninstalled modules are not listed
	 * in the database
	 *
	 * @return    array    List of folder names in the modules directory
	 * @since    1.3
	 */
	static public function getAvailable()
	{
		$cleanList = array();
		$dirtyList = Filesystem::getDirList(ICMS_MODULES_PATH . '/');
		foreach ($dirtyList as $item) {
			if (file_exists(ICMS_MODULES_PATH . '/' . $item . '/icms_version.php')) {
				$cleanList[$item] = $item;
			} elseif (file_exists(ICMS_MODULES_PATH . '/' . $item . '/xoops_version.php')) {
				$cleanList[$item] = $item;
			}
		}
		return $cleanList;
	}

	/**
	 * Get a list of active modules, with the folder name as the key
	 *
	 * This method is necessary to be able to use a static method
	 *
	 * @return    string[]    List of active modules
	 * @since    1.3
	 */
	public static function getActive()
	{
		$module_handler = new self(icms::$xoopsDB);
		$criteria = new CriteriaItem('isactive', 1);
		return $module_handler->getList($criteria, true);
	}

	/**
	 * Checks if the current user can access the specified module
	 * @param Module $module
	 * @param bool $inAdmin
	 * @return bool
	 */
	public static function checkModuleAccess($module, $inAdmin = false)
	{
		if ($inAdmin && !icms::$user) {
			return false;
		}
		/* @var $perm_handler GroupPermHandler */
		$perm_handler = icms::handler('icms_member_groupperm');
		if ($inAdmin) {
			if (!$module) {
				// We are in /admin.php
				return icms::$user->isAdmin(-1);
			} else {
				return $perm_handler->checkRight('module_admin', $module->mid, icms::$user->getGroups());
			}
		} elseif ($module) {
			$groups = (icms::$user) ? icms::$user->getGroups() : ICMS_GROUP_ANONYMOUS;
			return $perm_handler->checkRight('module_read', $module->mid, $groups);
		}
		// We are in /something.php: let the page handle permissions
		return true;
	}

	public function beforeSave(Module &$module)
	{
		$module->setVar('last_update', time());
		return true;
	}

	/**
	 * Function and rendering for installation of a module
	 *
	 * @param string $dirname Module dirname
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return bool
	 */
	public function install(string $dirname, OutputDecorator $output): bool
	{
		$dirname = trim($dirname);

		if ($this->getCount(new CriteriaItem('dirname', $dirname)) > 0) {
			$output->fatal(_MD_AM_FAILINS, $dirname);
			$output->error(_MD_AM_ALEXISTS, $dirname);
			return false;
		}

		$module = &$this->create();
		$module->loadInfoAsVar($dirname);
		$module->registerClassPath();
		$module->setVar('weight', 1);

		$output->info(_MD_AM_INSTALLING . $module->getInfo('name'));
		$output->writeln(_VERSION . ': ' . icms_conv_nr2local($module->getInfo('version')));
		if (($module->getInfo('author') !== false) && trim($module->getInfo('author'))) {
			$output->writeln(_AUTHOR . ': ' . trim($module->getInfo('author')));
		}
		/**
		 * @var SetupStepInterface[] $steps
		 */
		$steps = (array)icms::getInstance()->get('setup_step.module.install');
		usort($steps, function (SetupStepInterface $stepA, SetupStepInterface $stepB) {
			return $stepA->getPriority() > $stepB->getPriority();
		});

		if (!$this->save($module)) {
			$output->error(_MD_AM_DATA_INSERT_FAIL, $module->name);
			$output->fatal(_MD_AM_FAILINS, $module->name);
			return false;
		}

		// execute module steps that needs to be runned before module installation
		foreach ($steps as $step) {
			if (!$step->execute($module, $output)) {
				$output->fatal(_MD_AM_FAILINS, $dirname);
				return false;
			}
		}
		$output->success(_MD_AM_OKINS, $module->name);
		return true;
	}

	/**
	 * Logic for uninstalling a module
	 *
	 * @param string $dirname Dirname of module to uninstall
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return    bool
	 */
	public function uninstall(string $dirname, OutputDecorator $output)
	{
		global $icmsConfig;

		$module = $this->getByDirname($dirname);
		if (!$module) {
			$output->fatal(
				_MD_AM_FAILUNINS, $module->name
			);
			return false;
		}

		$module->registerClassPath();
		Template::template_clear_module_cache($module->mid);
		if ($module->dirname == 'system') {
			$output->fatal(
				_MD_AM_FAILUNINS, $module->name
			);
			$output->writeln(_MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_SYSNO);
			return false;
		}

		if ($module->dirname === $icmsConfig['startpage']) {
			$output->fatal(
				_MD_AM_FAILUNINS, $module->name
			);
			$output->writeln(_MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO);
			return false;
		}

		/**
		 * @var Member $member_handler
		 */
		$member_handler = icms::handler('icms_member');
		$grps = $member_handler->getGroupList();
		foreach ($grps as $k => $v) {
			$stararr = explode('-', $icmsConfig['startpage'][$k]);
			if (count($stararr) > 0) {
				if ($module->mid == $stararr[0]) {
					$output->fatal(_MD_AM_FAILDEACT, $module->name);
					$output->writeln(
						_MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
					);
					return false;
				}
			}
		}
		if (in_array($module->dirname, $icmsConfig['startpage'], true)) {
			$output->fatal(
				_MD_AM_FAILDEACT, $module->name
			);
			$output->writeln(_MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO);
			return false;
		}

		/**
		 * @var SetupStepInterface[] $steps
		 */
		$steps = (array)icms::getInstance()->get('setup_step.module.uninstall');
		usort($steps, function (SetupStepInterface $stepA, SetupStepInterface $stepB) {
			return $stepA->getPriority() > $stepB->getPriority();
		});

		// execute module steps that needs to be runned before module uninstallation
		foreach ($steps as $step) {
			if ($step->getPriority() >= 0) {
				continue;
			}
			$step->execute($module, $output);
		}

		if (!$this->delete($module)) {
			$output->incrIndent();
			$output->error(_MD_AM_DELETE_FAIL, $module->name);
			$output->decrIndent();
			return false;
		}

		// execute module steps that needs to be runned after module uninstallation
		foreach ($steps as $step) {
			if ($step->getPriority() < 0) {
				continue;
			}
			$step->execute($module, $output);
		}

		$output->success(
			_MD_AM_OKUNINS, $module->name
		);

		return true;
	}

	/**
	 * Load a module by its dirname
	 *
	 * @param string $dirname
	 * @param bool $loadConfig set to TRUE in case you want to load the module config in addition
	 * @return    Module|false
	 * @todo Make caching work
	 */
	public function getByDirname($dirname, $loadConfig = false)
	{
		//if (!($module = $this->getFromCache('dirname', $dirname))) {
		$criteria = new CriteriaItem('dirname', trim($dirname));
		$criteria->setLimit(1);
		$objs = $this->getObjects($criteria);
		$module = $objs[0] ?? null;
		//}
		if ($module && $loadConfig) {
			$this->loadConfig($module);
		}
		return $module;
	}

	/**
	 * load config for a module before caching it
	 *
	 * @param Module $module
	 * @return    bool                TRUE
	 */
	private function loadConfig($module)
	{
		if ($module->config !== null) {
			return true;
		}
		icms_loadLanguageFile($module->getVar('dirname'), 'main');
		if ($module->getVar('hasconfig') == 1
			|| $module->getVar('hascomments') == 1
			|| $module->getVar('hasnotification') == 1
		) {
			$module->config = icms::$config->getConfigsByCat(0, $module->getVar('mid'));
		}
		return true;
	}

	/**
	 * Delete a module from the database
	 *
	 * @param Module &$module
	 * @param bool $force Force to delete?
	 *
	 * @return  bool
	 */
	public function delete(&$module, $force = false)
	{
		if (!parent::delete($module, $force)) {
			return false;
		}
		// delete admin permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_admin' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int)$module->mid
		);
		$this->db->query($sql);
		// delete read permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int)$module->mid
		);
		$this->db->query($sql);

		$sql = sprintf(
			"SELECT block_id FROM %s WHERE module_id = '%u'",
			$this->db->prefix('block_module_link'), (int)$module->mid
		);
		if ($result = $this->db->query($sql)) {
			$block_id_arr = array();
			while ($myrow = $this->db->fetchArray($result)) {
				array_push($block_id_arr, $myrow['block_id']);
			}
		}

		// loop through block_id_arr
		if (isset($block_id_arr)) {
			foreach ($block_id_arr as $i) {
				$sql = sprintf(
					"SELECT block_id FROM %s WHERE module_id != '%u' AND block_id = '%u'",
					$this->db->prefix('block_module_link'), (int)$module->mid, (int)$i
				);
				if ($result2 = $this->db->query($sql)) {
					if (0 < $this->db->getRowsNum($result2)) {
						// this block has other entries, so delete the entry for this module
						$sql = sprintf(
							"DELETE FROM %s WHERE (module_id = '%u') AND (block_id = '%u')",
							$this->db->prefix('block_module_link'), (int)$module->mid, (int)$i
						);
						$this->db->query($sql);
					} else {
						// this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
						$sql = sprintf(
							"UPDATE %s SET visible = '0' WHERE bid = '%u'",
							$this->db->prefix('newblocks'), (int)$i
						);
						$this->db->query($sql);
						$sql = sprintf(
							"UPDATE %s SET module_id = '-1' WHERE module_id = '%u'",
							$this->db->prefix('block_module_link'), (int)$module->mid
						);
						$this->db->query($sql);
					}
				}
			}
		}
		if (!empty($this->_cachedModule[$module->dirname])) {
			unset($this->_cachedModule[$module->dirname]);
		}
		if (!empty($this->_cachedModule_lookup[$module->mid])) {
			unset($this->_cachedModule_lookup[$module->mid]);
		}
		return true;
	}

	/**
	 * Logic for updating a module
	 *
	 * @param string $dirname Dirname of module to uninstall
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return    bool
	 */
	public function update($dirname, OutputDecorator $output)
	{
		$dirname = trim($dirname);
		/**
		 * @var ModuleHandler $module_handler
		 */
		$module_handler = icms::handler('icms_module');
		$module = $module_handler->getByDirname($dirname);
		if (!$module) {
			$output->fatal(_MD_AM_UPDATE_FAIL, $module->name);
			return false;
		}

		// Save current version for use in the update function
		$prev_version = $module->version;
		$prev_dbversion = $module->dbversion;

		Template::template_clear_module_cache($module->mid);
		// we dont want to change the module name set by admin
		$temp_name = $module->name;
		$module->loadInfoAsVar($dirname);
		$module->setVar('name', $temp_name);

		/*
		 * ensure to only update those fields that are currently available in the database
		 * this is required to allow structural updates for the module table
		 */
		$table = new TableUpdater('modules');
		foreach (array_keys($module->vars) as $k) {
			if (!$table->fieldExists($k)) {
				unset($module->vars[$k]);
			}
		}

		if (!$module->store()) {
			$output->fatal(_MD_AM_UPDATE_FAIL, $module->name);
			return false;
		}

		$output->success(_MD_AM_MOD_DATA_UPDATED);

		/**
		 * @var SetupStepInterface[] $steps
		 */
		$steps = (array)icms::getInstance()->get('setup_step.module.update');
		usort($steps, function (SetupStepInterface $stepA, SetupStepInterface $stepB) {
			return $stepA->getPriority() > $stepB->getPriority();
		});

		foreach ($steps as $step) {
			$step->execute($module, $output, $prev_version, $prev_dbversion);
		}

		$output->success(_MD_AM_OKUPD, $module->name);

		return true;
	}

	/**
	 * Logic for activating a module
	 *
	 * @param int $mid Module id
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return   bool
	 */
	public function activate($mid, OutputDecorator $output)
	{
		$module = $this->get($mid);
		Template::template_clear_module_cache($module->mid);
		$module->setVar('isactive', 1);
		if (!$module->store()) {
			$output->fatal(_MD_AM_FAILACT . ' ' . _MD_AM_ERRORSC);
			$output->msg(
				$module->getHtmlErrors()
			);
			return false;
		}
		$icms_block_handler = icms_getModuleHandler('blocks', 'system');
		$blocks = &$icms_block_handler->getByModule($module->mid);
		$bcount = count($blocks);
		for ($i = 0; $i < $bcount; $i++) {
			$blocks[$i]->setVar('isactive', 1);
			$blocks[$i]->store();
		}
		$output->success(_MD_AM_OKACT, $module->name);
		return true;
	}

	/**
	 * Load a module from the database
	 *
	 * @param int $id ID of the module
	 * @param bool $loadConfig set to TRUE in case you want to load the module config in addition
	 * @param bool $debug Debug enabled for object?
	 * @param bool|object $criteria Criteria for getting object if needed
	 *
	 * @return    Module|false
	 */
	public function &get($id, $loadConfig = false, $debug = false, $criteria = false)
	{
		$module = parent::get($id, true, $debug, $criteria);
		if ($loadConfig) {
			$this->loadConfig($module);
		}
		return $module;
	}

	/**
	 * Logic for deactivating a module
	 *
	 * @param int $mid Module id
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return bool
	 */
	public function deactivate($mid, OutputDecorator $output)
	{
		global $icmsConfig;

		$module = $this->get($mid);
		Template::template_clear_module_cache($mid);
		$module->setVar('isactive', 0);
		if ($module->dirname == "system") {
			$output->fatal(
				_MD_AM_FAILDEACT
				. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_SYSNO,
				$module->name
			);
			return false;
		} elseif ($module->dirname == $icmsConfig['startpage']) {
			$output->fatal(
				_MD_AM_FAILDEACT
				. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO,
				$module->name
			);
			return false;
		} else {
			$member_handler = icms::handler('icms_member');
			$grps = $member_handler->getGroupList();
			foreach ($grps as $k => $v) {
				$stararr = explode('-', $icmsConfig['startpage'][$k]);
				if (count($stararr) > 0) {
					if ($module->mid == $stararr[0]) {
						$output->fatal(
							_MD_AM_FAILDEACT
							. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO,
							$module->name
						);
						return false;
					}
				}
			}
			if (in_array($module->dirname, $icmsConfig['startpage'], true)) {
				$output->fatal(
					_MD_AM_FAILDEACT
					. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO,
					$module->name
				);
				return false;
			}
			if (!$module->store()) {
				$output->fatal(
					_MD_AM_FAILDEACT
					. ' ' . _MD_AM_ERRORSC,
					$module->name
				);
				$output->msg(
					$module->getHtmlErrors()
				);
				return false;
			}

			$icms_block_handler = icms_getModuleHandler('blocks', 'system');
			$blocks = &$icms_block_handler->getByModule($module->mid);
			$bcount = count($blocks);
			for ($i = 0; $i < $bcount; $i++) {
				$blocks[$i]->setVar('isactive', false);
				$icms_block_handler->insert($blocks[$i]);
			}
			$output->success(_MD_AM_OKDEACT, $module->name);
			return true;
		}
	}

	/**
	 * Logic for changing the weight (order) and name of modules
	 *
	 * @param int $mid Unique ID for the module to change
	 * @param int $weight Integer value of the weight to be applied to the module
	 * @param string $name Name to be applied to the module
	 * @param OutputDecorator $output Output where to write messages
	 *
	 * @return bool
	 */
	public function change($mid, $weight, $name, OutputDecorator $output)
	{
		$module = $this->get($mid);
		$module->setVar('weight', $weight);
		$module->setVar('name', $name);
		if (!$module->store()) {
			$output->fatal(
				_MD_AM_FAILORDER
				. ' ' . _MD_AM_ERRORSC,
				DataFilter::stripSlashesGPC($name)
			);
			$output->msg(
				$module->getHtmlErrors()
			);
			return false;
		}
		$output->success(_MD_AM_OKORDER, DataFilter::stripSlashesGPC($name));
		return true;
	}

	/**
	 *
	 * @param string $dirname Directory name of the module
	 * @param string $template Name of the template file
	 * @param boolean $block Are you trying to retrieve the template for a block?
	 */
	public function getTemplate($dirname, $template, $block = false)
	{

	}

	/**
	 * Get all menu items of all activated modules
	 *
	 * @return array
	 */
	public function getAdminMenuItems()
	{
		$criteria = new CriteriaCompo();
		$criteria->add(new CriteriaItem('hasadmin', 1));
		$criteria->add(new CriteriaItem('isactive', 1));
		$criteria->setOrder('ASC');
		$criteria->setSort('name');
		$modules = $this->getObjects($criteria);
		$modules_menu = [];
		foreach ($modules as $module) {
			$modules_menu[] = $module->getAdminMenuItems();
		}
		return $modules_menu;
	}
}
