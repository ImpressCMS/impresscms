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
class icms_module_Handler
	extends icms_ipf_Handler
{

	/**
	 * Constructor
	 *
	 * @param object $db
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
		$dirtyList = $cleanList = array();
		$dirtyList = icms_core_Filesystem::getDirList(ICMS_MODULES_PATH . '/');
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
	 * @return    array    List of active modules
	 * @since    1.3
	 */
	static public function getActive()
	{
		$module_handler = new self(icms::$xoopsDB);
		$criteria = new icms_db_criteria_Item('isactive', 1);
		return $module_handler->getList($criteria, true);
	}

	/**
	 * Checks if the current user can access the specified module
	 * @param icms_module_Object $module
	 * @param bool $inAdmin
	 * @return bool
	 */
	static public function checkModuleAccess($module, $inAdmin = false)
	{
		if ($inAdmin && !icms::$user) {
			return false;
		}
		/* @var $perm_handler icms_member_groupperm_Handler */
		$perm_handler = icms::handler('icms_member_groupperm');
		if ($inAdmin) {
			if (!$module) {
				// We are in /admin.php
				return icms::$user->isAdmin(-1);
			} else {
				return $perm_handler->checkRight('module_admin', $module->getVar('mid'), icms::$user->getGroups());
			}
		} elseif ($module) {
			$groups = (icms::$user) ? icms::$user->getGroups() : ICMS_GROUP_ANONYMOUS;
			return $perm_handler->checkRight('module_read', $module->getVar('mid'), $groups);
		}
		// We are in /something.php: let the page handle permissions
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
	 * @return    object  {@link icms_module_Object} FALSE on fail
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
	 * load config for a module before caching it
	 *
	 * @param icms_module_Object $module
	 * @return    bool                TRUE
	 */
	private function loadConfig($module)
	{
		if ($module->config !== null) {
			return true;
		}
		icms_loadLanguageFile($module->getVar("dirname"), "main");
		if ($module->getVar("hasconfig") == 1
			|| $module->getVar("hascomments") == 1
			|| $module->getVar("hasnotification") == 1
		) {
			$module->config = icms::$config->getConfigsByCat(0, $module->getVar("mid"));
		}
		return true;
	}

	public function beforeSave(icms_module_Object &$module)
	{
		$module->setVar('last_update', time());
		return true;
	}

	/**
	 * Function and rendering for installation of a module
	 *
	 * @param string $dirname Module dirname
	 *
	 * @param \Psr\Log\LoggerInterface|null $logger Logger where to put action messages
	 * @return    string    Results of the installation process
	 */
	public function install($dirname, ?\Psr\Log\LoggerInterface $logger = null)
	{
		if ($logger === null) {
			$logger = new \Psr\Log\NullLogger();
		}
		$dirname = trim($dirname);
		$reservedTables = [
			'avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments',
			'config', 'configcategory', 'configoption', 'image', 'imagebody',
			'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups',
			'groups_users_link', 'group_permission', 'online',
			'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks',
			'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications',
		];
		if ($this->getCount(new icms_db_criteria_Item('dirname', $dirname)) == 0) {
			$module = &$this->create();
			$module->loadInfoAsVar($dirname);
			$module->registerClassPath();
			$module->setVar('weight', 1);
			$error = false;
			$sqlfile = &$module->getInfo('sqlfile');
			$logger->info(_MD_AM_INSTALLING . $module->getInfo('name'));
			$logger->debug(_VERSION . ': ' . icms_conv_nr2local($module->getInfo('version')));
			if (($module->getInfo('author') !== false) && trim($module->getInfo('author'))) {
				$logger->debug(_AUTHOR . ': ' . trim($module->getInfo('author')));
			}
			/**
			 * @var \ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface[] $helpers
			 */
			$helpers = (array)\icms::getInstance()->get('module_installation_helper');
			usort($helpers, function (\ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface $helperA, \ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface $helperB) {
				return $helperA->getModuleInstallStepPriority() > $helperB->getModuleInstallStepPriority();
			});

			// execute module steps that needs to be runned before module installation
			foreach ($helpers as $helper) {
				if ($helper->getModuleInstallStepPriority() >= 0) {
					continue;
				}
				$helper->executeModuleInstallStep($module, $logger);
			}

			if (($sqlfile !== false) && is_array($sqlfile)) {
				// handle instances when DB_TYPE includes 'pdo.'
				if (substr(env('DB_TYPE'), 0, 4) == 'pdo.') {
					$driver = substr(env('DB_TYPE'), 4);
				}
				$sql_file_path = ICMS_MODULES_PATH . '/' . $dirname . '/' . $sqlfile[$driver];
				if (!file_exists($sql_file_path)) {
					$logger->error(
						sprintf(_MD_AM_SQL_NOT_FOUND, $sql_file_path)
					);
					$error = true;
				} else {
					$logger->info(
						sprintf(_MD_AM_SQL_FOUND, $sql_file_path)
					);
					$sql_query = trim(
						file_get_contents($sql_file_path)
					);
					icms_db_legacy_mysql_Utility::splitSqlFile($pieces, $sql_query);
					$created_tables = array();
					foreach ($pieces as $piece) {
						// [0] contains the prefixed query
						// [4] contains unprefixed table name
						$prefixed_query = icms_db_legacy_mysql_Utility::prefixQuery($piece, $db->prefix());
						if (!$prefixed_query) {
							$logger->error(
								sprintf('"%s", %s', addslashes($piece), _MD_SQL_NOT_VALID)
							);
							$error = true;
							break;
						}
						// check if the table name is reserved
						if (!in_array($prefixed_query[4], $reservedTables, true)) {
							// not reserved, so try to create one
							if (!$db->query($prefixed_query[0])) {
								$logger->error($db->error());
								$error = true;
								break;
							} else {
								if (!in_array($prefixed_query[4], $created_tables, true)) {
									$logger->info(
										sprintf('  ' . _MD_AM_TABLE_CREATED,
											$db->prefix($prefixed_query[4])
										)
									);
									$created_tables[] = $prefixed_query[4];
								} else {
									$logger->info(
										sprintf('  ' . _MD_AM_DATA_INSERT_SUCCESS,
											$db->prefix($prefixed_query[4])
										)
									);
								}
							}
						} else {
							// the table name is reserved, so halt the installation
							$logger->emergency(
								sprintf(_MD_AM_RESERVED_TABLE, $prefixed_query[4])
							);
							$error = true;
							break;
						}
					}

					// if there was an error, delete the tables created so far, so the next installation will not fail
					if ($error === true) {
						foreach ($created_tables as $ct) {
							$db->query('DROP TABLE ' . $db->prefix($ct));
						}
					}
				}
			}

			// if no error, save the module info and blocks info associated with it
			if ($error === false) {
				if (!$this->save($module)) {
					$logger->error(
						sprintf(_MD_AM_DATA_INSERT_FAIL, $module->getVar('name'))
					);
					foreach ($created_tables as $ct) {
						$db->query("DROP TABLE " . $db->prefix($ct));
					}
					$logger->error(
						$logger->sprintf(_MD_AM_FAILINS, $module->getVar('name'))
					);
					return false;
				} else {
					$newmid = $module->getVar('mid');
					$logger->info(
						sprintf(_MD_AM_MOD_DATA_INSERT_SUCCESS, icms_conv_nr2local($newmid))
					);
				}

				// execute module steps that needs to be runned after module installation
				foreach ($helpers as $helper) {
					if ($helper->getModuleInstallStepPriority() < 0) {
						continue;
					}
					$helper->executeModuleInstallStep($module, $logger);
				}

				$logger->info(
					sprintf(_MD_AM_OKINS, $module->getVar('name'))
				);
				return true;
			} else {
				$logger->emergency(
					sprintf(_MD_AM_FAILINS, $dirname)
				);
				return false;
			}
		} else {
			$logger->emergency(
				sprintf(_MD_AM_FAILINS, $dirname)
			);
			$logger->error(
				sprintf(_MD_AM_ALEXISTS, $dirname)
			);
			return false;
		}
	}

	/**
	 * Logic for uninstalling a module
	 *
	 * @param unknown_type $dirname
	 * @return    string    Result messages for uninstallation
	 */
	public function uninstall($dirname, ?\Psr\Log\LoggerInterface $logger = null)
	{
		if ($logger === null) {
			$logger = new \Psr\Log\NullLogger();
		}
		global $icmsConfig;

		$reservedTables = [
			'avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config',
			'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset',
			'imgset_tplset_link', 'imgsetimg', 'groups', 'groups_users_link', 'group_permission',
			'online', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks',
			'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications'];

		$module = $this->getByDirname($dirname);
		$module->registerClassPath();
		icms_view_Tpl::template_clear_module_cache($module->getVar('mid'));
		if ($module->getVar('dirname') == 'system') {
			$logger->emergency(
				sprintf(_MD_AM_FAILUNINS, $module->getVar('name')) . ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_SYSNO
			);
			return false;
		} elseif ($module->getVar('dirname') === $icmsConfig['startpage']) {
			$logger->emergency(
				sprintf(_MD_AM_FAILUNINS, $module->getVar('name'))
				. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
			);
			return false;
		} else {
			/**
			 * @var icms_member_Handler $member_handler
			 */
			$member_handler = \icms::handler('icms_member');
			$grps = $member_handler->getGroupList();
			foreach ($grps as $k => $v) {
				$stararr = explode('-', $icmsConfig['startpage'][$k]);
				if (count($stararr) > 0) {
					if ($module->getVar('mid') == $stararr[0]) {
						$logger->emergency(
							sprintf(_MD_AM_FAILDEACT, $module->getVar('name')
							) . ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
						);
						return false;
					}
				}
			}
			if (in_array($module->getVar('dirname'), $icmsConfig['startpage'], true)) {
				$logger->emergency(
					sprintf(_MD_AM_FAILDEACT, $module->getVar('name'))
					. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
				);
				return false;
			}

			/**
			 * @var \ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface[] $helpers
			 */
			$helpers = (array)\icms::getInstance()->get('module_installation_helper');
			usort($helpers, function (\ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface $helperA, \ImpressCMS\Core\ModuleInstallationHelpers\ModuleInstallationHelperInterface $helperB) {
				return $helperA->getModuleUninstallStepPriority() > $helperB->getModuleUninstallStepPriority();
			});

			// execute module steps that needs to be runned before module uninstallation
			foreach ($helpers as $helper) {
				if ($helper->getModuleUninstallStepPriority() >= 0) {
					continue;
				}
				$helper->executeModuleUninstallStep($module, $logger);
			}

			if (!$this->delete($module)) {
				$logger->error(
					sprintf('  ' . _MD_AM_DELETE_FAIL, $module->getVar('name'))
				);
			} else {

				// delete tables used by this module
				$modtables = $module->getInfo('tables');
				$is_IPF = $module->getInfo('object_items');
				if ($modtables !== false && is_array($modtables)) {
					$logger->info(
						_MD_AM_MOD_TABLES_DELETE
					);
					foreach ($modtables as $table) {
						if ($is_IPF) {
							$table = str_replace(env('DB_PREFIX') . '_', '', $table);
						}
						$prefix_table = $db->prefix($table);
						// prevent deletion of reserved core tables!
						if (!in_array($table, $reservedTables)) {
							$sql = 'DROP TABLE ' . $prefix_table;
							if (!$db->query($sql)) {
								$logger->error(
									sprintf('  ' . _MD_AM_MOD_TABLE_DELETE_FAIL, $prefix_table)
								);
							} else {
								$logger->info(
									sprintf('  ' . _MD_AM_MOD_TABLE_DELETED,  $prefix_table )
								);
							}
						} else {
							$logger->error(
								sprintf('  ' . _MD_AM_MOD_TABLE_DELETE_NOTALLOWED, $prefix_table)
							);
						}
					}
				}

				// delete permissions if any
				$logger->info(_MD_AM_GROUPPERM_DELETE);
				$gperm_handler = icms::handler('icms_member_groupperm');
				if (!$gperm_handler->deleteByModule($module->getVar('mid'))) {
					$logger->error('  ' . _MD_AM_GROUPPERM_DELETE_FAIL);
				} else {
					$logger->info('  ' . _MD_AM_GROUPPERM_DELETED);
				}

				// delete urllinks
				$urllink_handler = icms::handler('icms_data_urllink');
				$urllink_handler->deleteAll(icms_buildCriteria(array("mid" => $module->getVar("mid"))));

				// delete files
				$file_handler = icms::handler('icms_data_file');
				$file_handler->deleteAll(icms_buildCriteria(array("mid" => $module->getVar("mid"))));

				// execute module steps that needs to be runned after module uninstallation
				foreach ($helpers as $helper) {
					if ($helper->getModuleUninstallStepPriority() < 0) {
						continue;
					}
					$helper->executeModuleUninstallStep($module, $logger);
				}

				$logger->info(
					sprintf(_MD_AM_OKUNINS, $module->getVar('name'))
				);
			}
		}
		return true;
	}

	/**
	 * Load a module by its dirname
	 *
	 * @param string $dirname
	 * @param bool $loadConfig set to TRUE in case you want to load the module config in addition
	 * @return    object  {@link icms_module_Object} FALSE on fail
	 * @todo Make caching work!
	 *
	 */
	public function getByDirname($dirname, $loadConfig = false)
	{
		//if (!($module = $this->getFromCache('dirname', $dirname))) {
		$criteria = new icms_db_criteria_Item('dirname', trim($dirname));
		$criteria->setLimit(1);
		$objs = $this->getObjects($criteria);
		if (isset($objs[0])) {
			$module = $objs[0];
		} else {
			$module = null;
		}
		//}
		if ($module && $loadConfig) {
			$this->loadConfig($module);
		}
		return $module;
	}

	/**
	 * Delete a module from the database
	 *
	 * @param icms_module_Object &$module
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
			$this->db->prefix('group_permission'), (int)$module->getVar('mid')
		);
		$this->db->query($sql);
		// delete read permissions assigned for this module
		$sql = sprintf(
			"DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = '%u'",
			$this->db->prefix('group_permission'), (int)$module->getVar('mid')
		);
		$this->db->query($sql);

		$sql = sprintf(
			"SELECT block_id FROM %s WHERE module_id = '%u'",
			$this->db->prefix('block_module_link'), (int)$module->getVar('mid')
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
					$this->db->prefix('block_module_link'), (int)$module->getVar('mid'), (int)$i
				);
				if ($result2 = $this->db->query($sql)) {
					if (0 < $this->db->getRowsNum($result2)) {
						// this block has other entries, so delete the entry for this module
						$sql = sprintf(
							"DELETE FROM %s WHERE (module_id = '%u') AND (block_id = '%u')",
							$this->db->prefix('block_module_link'), (int)$module->getVar('mid'), (int)$i
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
							$this->db->prefix('block_module_link'), (int)$module->getVar('mid')
						);
						$this->db->query($sql);
					}
				}
			}
		}
		if (!empty($this->_cachedModule[$module->getVar('dirname')])) {
			unset($this->_cachedModule[$module->getVar('dirname')]);
		}
		if (!empty($this->_cachedModule_lookup[$module->getVar('mid')])) {
			unset($this->_cachedModule_lookup[$module->getVar('mid')]);
		}
		return true;
	}

	/**
	 * Logic for updating a module
	 *
	 * @param str $dirname
	 * @return    str    Result messages from the module update
	 */
	public function update($dirname, ?\Psr\Log\LoggerInterface $logger = null)
	{
		if ($logger === null) {
			$logger = new \Psr\Log\NullLogger();
		}
	}

	/**
	 * Logic for activating a module
	 *
	 * @param int $mid
	 * @return    string    Result message for activating the module
	 */
	public function activate($mid)
	{

	}

	/**
	 * Logic for deactivating a module
	 *
	 * @param int $mid Module id
	 * @param \Psr\Log\LoggerInterface $logger Logger where to write messages
	 *
	 * @return bool
	 */
	public function deactivate($mid, \Psr\Log\LoggerInterface $logger)
	{
		global $icms_page_handler, $icms_block_handler, $icmsConfig;
		if (!isset($icms_page_handler)) {
			$icms_page_handler = icms_getModuleHandler('pages', 'system');
		}

		$module = $this->get($mid);
		icms_view_Tpl::template_clear_module_cache($mid);
		$module->setVar('isactive', 0);
		if ($module->getVar('dirname') == "system") {
			$logger->emergency(
				sprintf(_MD_AM_FAILDEACT,  $module->getVar('name') )
				. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_SYSNO
			);
			return false;
		} elseif ($module->getVar('dirname') == $icmsConfig['startpage']) {
			$logger->emergency(
				sprintf(_MD_AM_FAILDEACT, $module->getVar('name'))
				. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
			);
			return false;
		} else {
			$member_handler = icms::handler('icms_member');
			$grps = $member_handler->getGroupList();
			foreach ($grps as $k => $v) {
				$stararr = explode('-', $icmsConfig['startpage'][$k]);
				if (count($stararr) > 0) {
					if ($module->getVar('mid') == $stararr[0]) {
						$logger->emergency(
							sprintf(_MD_AM_FAILDEACT, $module->getVar('name'))
							. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
						);
						return false;
					}
				}
			}
			if (in_array($module->getVar('dirname'), $icmsConfig['startpage'], true)) {
				$logger->emergency(
					sprintf(_MD_AM_FAILDEACT,  $module->getVar('name') )
					. ' ' . _MD_AM_ERRORSC . PHP_EOL . ' - ' . _MD_AM_STRTNO
				);
				return false;
			}
			if (!$module->store()) {
				$logger->emergency(
					sprintf(_MD_AM_FAILDEACT, $module->getVar('name'))
					. ' ' . _MD_AM_ERRORSC
				);
				$logger->notice(
					$module->getHtmlErrors()
				);
				return false;
			}

			$icms_block_handler = icms_getModuleHandler('blocks', 'system');
			$blocks = & $icms_block_handler->getByModule($module->getVar('mid'));
			$bcount = count($blocks);
			for ($i = 0; $i < $bcount; $i++) {
				$blocks[$i]->setVar('isactive', false);
				$icms_block_handler->insert($blocks[$i]);
			}
			$logger->info(
				sprintf(_MD_AM_OKDEACT, $module->getVar('name') )
			);
			return true;
		}
	}

	/**
	 * Logic for changing the weight (order) and name of modules
	 *
	 * @param int $mid Unique ID for the module to change
	 * @param int $weight Integer value of the weight to be applied to the module
	 * @param str $name Name to be applied to the module
	 * @param \Psr\Log\LoggerInterface $logger Logger where to write messages
	 *
	 * @return bool
	 */
	public function change($mid, $weight, $name, \Psr\Log\LoggerInterface $logger)
	{
		$module =  $this->get($mid);
		$module->setVar('weight', $weight);
		$module->setVar('name', $name);
		if (!$module->store()) {
			$logger->emergency(
				sprintf(_MD_AM_FAILORDER,  icms_core_DataFilter::stripSlashesGPC($name))
				. ' ' . _MD_AM_ERRORSC
			);
			$logger->notice(
				$module->getHtmlErrors()
			);
			return false;
		}
		$logger->info(
			sprintf(_MD_AM_OKORDER,  icms_core_DataFilter::stripSlashesGPC($name))
		);
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
		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('hasadmin', 1));
		$criteria->add(new icms_db_criteria_Item('isactive', 1));
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
