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
 * Logic and rendering for adminstration of modules
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Modules
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

/**
 * Logic and rendering for listing modules
 * @return NULL	Assigns content to the template
 */
function xoops_module_list() {
	global $icmsAdminTpl, $icmsConfig;

	$icmsAdminTpl->assign('lang_madmin', _MD_AM_MODADMIN);
	$icmsAdminTpl->assign('lang_module', _CO_ICMS_MODULE);
	$icmsAdminTpl->assign('lang_version', _MD_AM_VERSION);
	$icmsAdminTpl->assign('lang_modstatus', _MD_AM_MODULES_STATUS);
	$icmsAdminTpl->assign('lang_lastup', _MD_AM_LASTUP);
	$icmsAdminTpl->assign('lang_active', _MD_AM_ACTIVE);
	$icmsAdminTpl->assign('lang_order', _MD_AM_ORDER);
	$icmsAdminTpl->assign('lang_order0', _MD_AM_ORDER0);
	$icmsAdminTpl->assign('lang_action', _AM_ACTION);
	$icmsAdminTpl->assign('lang_modulename', _MD_AM_MODULES_MODULENAME);
	$icmsAdminTpl->assign('lang_moduletitle', _MD_AM_MODULES_MODULETITLE);
	$icmsAdminTpl->assign('lang_info', _INFO);
	$icmsAdminTpl->assign('lang_update', _MD_AM_UPDATE);
	$icmsAdminTpl->assign('lang_unistall', _MD_AM_UNINSTALL);
	$icmsAdminTpl->assign('lang_support', _MD_AM_MODULES_SUPPORT);
	$icmsAdminTpl->assign('lang_submit', _MD_AM_SUBMIT);
	$icmsAdminTpl->assign('lang_install', _MD_AM_INSTALL);
	$icmsAdminTpl->assign('lang_installed', _MD_AM_INSTALLED);
	$icmsAdminTpl->assign('lang_noninstall', _MD_AM_NONINSTALL);

	$module_handler = icms::handler('icms_module');
	$installed_mods = $module_handler->getObjects();
	$listed_mods = array();
	foreach ($installed_mods as $module) {
		$module->registerClassPath(false);
		$module->getInfo();
		$mod = array(
			'mid' => $module->getVar('mid'),
			'dirname' => $module->getVar('dirname'),
			'name' => $module->getInfo('name'),
			'title' => $module->getVar('name'),
			'image' => $module->getInfo('image'),
			'adminindex' => $module->getInfo('adminindex'),
			'hasadmin' => $module->getVar('hasadmin'),
			'hasmain' => $module->getVar('hasmain'),
			'isactive' => $module->getVar('isactive'),
			'version' => icms_conv_nr2local(round($module -> getVar('version') / 100, 2)),
			'status' => ($module->getInfo('status'))?$module->getInfo('status'):'&nbsp;',
			'last_update' => ($module->getVar('last_update') != 0)? formatTimestamp($module->getVar('last_update'), 'm'):'&nbsp;',
			'weight' => $module->getVar('weight'),
			'support_site_url' => $module->getInfo('support_site_url'),
		);
		$icmsAdminTpl->append('modules', $mod);
		$listed_mods[] = $module->getVar('dirname');
	}

	$dirlist = icms_module_Handler::getAvailable();
	$uninstalled = array_diff($dirlist, $listed_mods);
	foreach ($uninstalled as $file) {
		clearstatcache();
		$file = trim($file);
			$module = & $module_handler->create();
			if (!$module->loadInfo($file, false)) {
				continue;
			}
			$mod = array(
				'dirname' => $module->getInfo('dirname'),
				'name' => $module->getInfo('name'),
				'image' => $module->getInfo('image'),
				'version' => icms_conv_nr2local(round($module->getInfo('version'), 2)),
				'status' => $module->getInfo('status'),
			);
			$icmsAdminTpl->append('avmodules', $mod);
			unset($module);
	}

	return $icmsAdminTpl->fetch('db:admin/modules/system_adm_modules.html');
}

/**
 * Logic for activating a module
 *
 * @param	int	$mid
 * @return	string	Result message for activating the module
 */
function xoops_module_activate($mid) {
	global $icms_block_handler, $icmsAdminTpl;
	$module_handler = icms::handler('icms_module');
	$module = & $module_handler->get($mid);
	icms_view_Tpl::template_clear_module_cache($module->getVar('mid'));
	$module->setVar('isactive', 1);
	if (!$module_handler->insert($module)) {
		$ret = "<p>" . sprintf(_MD_AM_FAILACT, "<strong>" . $module->getVar('name') . "</strong>") . "&nbsp;"
			. _MD_AM_ERRORSC . "<br />" . $module->getHtmlErrors();
		return $ret . "</p>";
	}
	$icms_block_handler = icms_getModuleHandler('blocks', 'system');
	$blocks = & $icms_block_handler->getByModule($module->getVar('mid'));
	$bcount = count($blocks);
	for ($i = 0; $i < $bcount; $i++) {
		$blocks[$i]->setVar('isactive', 1);
		$icms_block_handler->insert($blocks[$i]);
	}
	return "<p>" . sprintf(_MD_AM_OKACT, "<strong>" . $module->getVar('name') . "</strong>") . "</p>";
}

/**
 * Logic for updating a module
 *
 * @param 	str $dirname
 * @return	str	Result messages from the module update
 */
function icms_module_update($dirname) {
	global $icmsConfig, $icmsAdminTpl;

	$msgs = array();

	$dirname = trim($dirname);
	$module_handler = icms::handler('icms_module');
	$module = $module_handler->getByDirname($dirname);

	// Save current version for use in the update function
	$prev_version = $module->getVar('version');
	$prev_dbversion = $module->getVar('dbversion');

	icms_view_Tpl::template_clear_module_cache($module->getVar('mid'));
	// we dont want to change the module name set by admin
	$temp_name = $module->getVar('name');
	$module->loadInfoAsVar($dirname);
	$module->setVar('name', $temp_name);

	/*
	 * ensure to only update those fields that are currently available in the database
	 * this is required to allow structural updates for the module table
	 */
	$table = new icms_db_legacy_updater_Table("modules");
	foreach (array_keys($module->vars) as $k) {
		if (!$table->fieldExists($k)) {
			unset($module->vars[$k]);
		}
	}

	if (!$module_handler->insert($module)) {
		$msgs[] = sprintf('<p>' . _MD_AM_UPDATE_FAIL . '</p>', $module->getVar('name'));
	} else {
		$newmid = $module->getVar('mid');
		$msgs[] = _MD_AM_MOD_DATA_UPDATED;
		$tplfile_handler = & icms::handler('icms_view_template_file');
		$deltpl = $tplfile_handler->find('default', 'module', $module->getVar('mid'));
		$delng = array();
		if (is_array($deltpl)) {
			$xoopsDelTpl = new icms_view_Tpl();
			// clear cache files
			$xoopsDelTpl->clear_cache(null, 'mod_' . $dirname);
			// delete template file entry in db
			$dcount = count($deltpl);
			for ($i = 0; $i < $dcount; $i++) {
				if (!$tplfile_handler->delete($deltpl[$i])) {
					$delng[] = $deltpl[$i]->getVar('tpl_file');
				}
			}
		}

		$templates = $module->getInfo('templates');
		if ($templates !== false) {
			$msgs[] = _MD_AM_MOD_UP_TEM;
			foreach ($templates as $tpl) {
				$tpl['file'] = trim($tpl['file']);
				if (!in_array($tpl['file'], $delng)) {
					$tpldata = & xoops_module_gettemplate($dirname, $tpl['file']);
					$tplfile = & $tplfile_handler->create();
					$tplfile->setVar('tpl_refid', $newmid);
					$tplfile->setVar('tpl_lastimported', 0);
					$tplfile->setVar('tpl_lastmodified', time());
					if (preg_match("/\.css$/i", $tpl['file'])) {
						$tplfile->setVar('tpl_type', 'css');
					} else {
						$tplfile->setVar('tpl_type', 'module');
					}
					$tplfile->setVar('tpl_source', $tpldata, true);
					$tplfile->setVar('tpl_module', $dirname);
					$tplfile->setVar('tpl_tplset', 'default');
					$tplfile->setVar('tpl_file', $tpl['file'], true);
					$tplfile->setVar('tpl_desc', $tpl['description'], true);
					if (!$tplfile_handler->insert($tplfile)) {
						$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">'
						. _MD_AM_TEMPLATE_INSERT_FAIL . '</span>', '<strong>' . $tpl['file'] . '</strong>');
					} else {
						$newid = $tplfile->getVar('tpl_id');
						$msgs[] = sprintf('&nbsp;&nbsp;<span>' . _MD_AM_TEMPLATE_INSERTED . '</span>', '<strong>' . $tpl['file'] . '</strong>', '<strong>' . $newid . '</strong>');
						if ($icmsConfig['template_set'] == 'default') {
							if (!icms_view_Tpl::template_touch($newid)) {
								$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">'
								. _MD_AM_TEMPLATE_RECOMPILE_FAIL . '</span>', '<strong>' . $tpl['file'] . '</strong>');
							} else {
								$msgs[] = sprintf('&nbsp;&nbsp;<span>' . _MD_AM_TEMPLATE_RECOMPILED . '</span>', '<strong>' . $tpl['file'] . '</strong>');
							}
						}
					}
					unset($tpldata);
				} else {
					$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_TEMPLATE_DELETE_FAIL . '</span>', $tpl['file']);
				}
			}
		}
		$blocks = $module->getInfo('blocks');
		$msgs[] = _MD_AM_MOD_REBUILD_BLOCKS;
		if ($blocks !== false) {
			$count = count($blocks);
			$showfuncs = array();
			$funcfiles = array();
			foreach ($blocks as $i => $block) {
				if (isset($block['show_func']) && $block['show_func'] != '' && isset($block['file']) && $block['file'] != '') {
					$editfunc = isset($block['edit_func'])?$block['edit_func']:'';
					$showfuncs[] = $block['show_func'];
					$funcfiles[] = $block['file'];
					$template = $content = '';
					if ((isset($block['template']) && trim($block['template']) != '')) {
						$content = & xoops_module_gettemplate($dirname, $block['template'], true);
					}
					if (!$content) {
						$content = '';
					} else {
						$template = $block['template'];
					}
					$options = '';
					if (!empty($block['options'])) {
						$options = $block['options'];
					}
					$sql = "SELECT bid, name FROM " . icms::$xoopsDB->prefix('newblocks')
						. " WHERE mid='" . (int) $module->getVar('mid')
						. "' AND func_num='" . (int) $i
						. "' AND show_func='" . addslashes($block['show_func'])
						. "' AND func_file='" . addslashes($block['file']) . "'";
					$fresult = icms::$xoopsDB->query($sql);
					$fcount = 0;
					while ($fblock = icms::$xoopsDB->fetchArray($fresult)) {
						$fcount++;
						$sql = "UPDATE " . icms::$xoopsDB->prefix("newblocks")
							. " SET name='" . addslashes($block['name'])
							. "', edit_func='" . addslashes($editfunc)
							. "', content='', template='" . $template
							. "', last_modified=" . time()
							. " WHERE bid='" . (int) $fblock['bid'] . "'";
						$result = icms::$xoopsDB->query($sql);
						if (!$result) {
							$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_UPDATE_FAIL, $fblock['name']);
						} else {
							$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_BLOCK_UPDATED,
								'<strong>' . $fblock['name'] . '</strong>',
								'<strong>' . icms_conv_nr2local($fblock['bid']) . '</strong>');
							if ($template != '') {
								$tplfile = $tplfile_handler->find('default', 'block', $fblock['bid']);
								if (count($tplfile) == 0) {
									$tplfile_new = & $tplfile_handler->create();
									$tplfile_new->setVar('tpl_module', $dirname);
									$tplfile_new->setVar('tpl_refid', (int) $fblock['bid']);
									$tplfile_new->setVar('tpl_tplset', 'default');
									$tplfile_new->setVar('tpl_file', $block['template'], true);
									$tplfile_new->setVar('tpl_type', 'block');
								} else {
									$tplfile_new = $tplfile[0];
								}
								$tplfile_new->setVar('tpl_source', $content, true);
								$tplfile_new->setVar('tpl_desc', $block['description'], true);
								$tplfile_new->setVar('tpl_lastmodified', time());
								$tplfile_new->setVar('tpl_lastimported', 0);
								if (!$tplfile_handler->insert($tplfile_new)) {
									$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">'
										. _MD_AM_TEMPLATE_UPDATE_FAIL . '</span>', '<strong>' . $block['template'] . '</strong>');
								} else {
									$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_TEMPLATE_UPDATED, '<strong>' . $block['template'] . '</strong>');
									if ($icmsConfig['template_set'] == 'default') {
										if (!icms_view_Tpl::template_touch($tplfile_new->getVar('tpl_id'))) {
											$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">'
												. _MD_AM_TEMPLATE_RECOMPILE_FAIL . '</span>', '<strong>' . $block['template'] . '</strong>');
										} else {
											$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_TEMPLATE_RECOMPILED, '<strong>' . $block['template'] . '</strong>');
										}
									}
								}
							}
						}
					}

					if ($fcount == 0) {
						$newbid = icms::$xoopsDB->genId(icms::$xoopsDB->prefix('newblocks') . '_bid_seq');
						$block_name = addslashes($block['name']);
						/* @todo properly handle the block_type when updating the system module */
						$sql = "INSERT INTO " . icms::$xoopsDB->prefix("newblocks")
							. " (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) VALUES ('"
							. (int) $newbid . "', '" . (int) $module->getVar('mid') . "', '" . (int) $i . "', '" . addslashes($options) . "', '" . $block_name . "', '" . $block_name . "', '', '1', '0', '0', 'M', 'H', '1', '" . addslashes($dirname) . "', '" . addslashes($block['file']) . "', '" . addslashes($block['show_func']) . "', '" . addslashes($editfunc) . "', '" . $template . "', '0', '" . time() . "')";
						$result = icms::$xoopsDB->query($sql);
						if (!$result) {
							$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_CREATE_FAIL, $block['name']);
							echo $sql;
						} else {
							if (empty($newbid)) {
								$newbid = icms::$xoopsDB->getInsertId();
							}
							$groups = & icms::$user->getGroups();
							$gperm_handler = icms::handler('icms_member_groupperm');
							foreach ($groups as $mygroup) {
								$bperm = & $gperm_handler->create();
								$bperm->setVar('gperm_groupid', (int) $mygroup);
								$bperm->setVar('gperm_itemid', (int) $newbid);
								$bperm->setVar('gperm_name', 'block_read');
								$bperm->setVar('gperm_modid', 1);
								if (!$gperm_handler->insert($bperm)) {
									$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_BLOCK_ACCESS_FAIL . '</span>',
										'<strong>' . $newbid . '</strong>',
										'<strong>' . $mygroup . '</strong>');
								} else {
									$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_BLOCK_ACCESS_ADDED,
										'<strong>' . $newbid . '</strong>',
										'<strong>' . $mygroup . '</strong>');
								}
							}

							if ($template != '') {
								$tplfile = & $tplfile_handler->create();
								$tplfile->setVar('tpl_module', $dirname);
								$tplfile->setVar('tpl_refid', (int) $newbid);
								$tplfile->setVar('tpl_source', $content, true);
								$tplfile->setVar('tpl_tplset', 'default');
								$tplfile->setVar('tpl_file', $block['template'], true);
								$tplfile->setVar('tpl_type', 'block');
								$tplfile->setVar('tpl_lastimported', 0);
								$tplfile->setVar('tpl_lastmodified', time());
								$tplfile->setVar('tpl_desc', $block['description'], true);
								if (!$tplfile_handler->insert($tplfile)) {
									$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_TEMPLATE_INSERT_FAIL . '</span>',
										'<strong>' . $block['template'] . '</strong>');
								} else {
									$newid = $tplfile->getVar('tpl_id');
									$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_TEMPLATE_INSERTED,
										'<strong>' . $block['template'] . '</strong>', '<strong>' . $newid . '</strong>');
									if ($icmsConfig['template_set'] == 'default') {
										if (!icms_view_Tpl::template_touch($newid)) {
											$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_TEMPLATE_RECOMPILE_FAIL . '</span>',
												'<strong>' . $block['template'] . '</strong>');
										} else {
											$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_TEMPLATE_RECOMPILED, '<strong>' . $block['template'] . '</strong>');
										}
									}
								}
							}
							$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_BLOCK_CREATED,
								'<strong>' . $block['name'] . '</strong>',
								'<strong>' . $newbid . '</strong>');
							$sql = "INSERT INTO " . icms::$xoopsDB->prefix('block_module_link')
								. " (block_id, module_id, page_id) VALUES ('"
								. (int) $newbid . "', '0', '1')";
							icms::$xoopsDB->query($sql);
						}
					}
				}
			}

			$icms_block_handler = icms::handler('icms_view_block');
			$block_arr = $icms_block_handler->getByModule($module->getVar('mid'));
			foreach ($block_arr as $block) {
				if (!in_array($block->getVar('show_func'), $showfuncs) || !in_array($block->getVar('func_file'), $funcfiles)) {
					$sql = sprintf("DELETE FROM %s WHERE bid = '%u'", icms::$xoopsDB->prefix('newblocks'), (int) $block->getVar('bid'));
					if (!icms::$xoopsDB->query($sql)) {
						$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_BLOCK_DELETE_FAIL . '</span>',
							'<strong>' . $block->getVar('name') . '</strong>',
							'<strong>' . $block->getVar('bid') . '</strong>');
					} else {
						$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_BLOCK_DELETED,
							'<strong>' . $block->getVar('name') . '</strong>',
							'<strong>' . $block->getVar('bid') . '</strong>');
						if ($block->getVar('template') != '') {
							$tplfiles = & $tplfile_handler->find(null, 'block', $block->getVar('bid'));
							if (is_array($tplfiles)) {
								$btcount = count($tplfiles);
								for ($k = 0; $k < $btcount; $k++) {
									if (!$tplfile_handler->delete($tplfiles[$k])) {
										$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_BLOCK_TMPLT_DELETE_FAILED . '</span>',
											'<strong>' . $tplfiles[$k]->getVar('tpl_file') . '</strong>',
											'<strong>' . $tplfiles[$k]->getVar('tpl_id') . '</strong>');
									} else {
										$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_BLOCK_TMPLT_DELETED,
											'<strong>' . $tplfiles[$k]->getVar('tpl_file') . '</strong>',
											'<strong>' . $tplfiles[$k]->getVar('tpl_id') . '</strong>');
									}
								}
							}
						}
					}
				}
			}
		}

		// first delete all config entries
		$config_handler = icms::handler('icms_config');
		$configs = $config_handler->getConfigs(new icms_db_criteria_Item('conf_modid', $module->getVar('mid')));
		$confcount = count($configs);
		$config_delng = array();
		if ($confcount > 0) {
			$msgs[] = _MD_AM_CONFIGOPTION_DELETED;
			for ($i = 0; $i < $confcount; $i++) {
				if (!$config_handler->deleteConfig($configs[$i])) {
					$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_CONFIGOPTION_DELETE_FAIL . '</span>',
						'<strong>' . $configs[$i]->getVar('conf_id') . '</strong>');
					// save the name of config failed to delete for later use
					$config_delng[] = $configs[$i]->getVar('conf_name');
				} else {
					$config_old[$configs[$i]->getVar('conf_name')]['value'] = $configs[$i]->conf_value;
					$config_old[$configs[$i]->getVar('conf_name')]['formtype'] = $configs[$i]->getVar('conf_formtype');
					$config_old[$configs[$i]->getVar('conf_name')]['valuetype'] = $configs[$i]->getVar('conf_valuetype');
					$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_CONFIGOPTION_DELETED,
						'<strong>' . $configs[$i]->getVar('conf_id') . '</strong>');
				}
			}
		}

		// now reinsert them with the new settings
		$configs = $module->getInfo('config');
		if ($configs !== false) {
			if ($module->getVar('hascomments') != 0) {
				include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
				array_push($configs, array(
					'name' => 'com_rule',
					'title' => '_CM_COMRULES',
					'description' => '',
					'formtype' => 'select',
					'valuetype' => 'int',
					'default' => 1,
					'options' => array(
						'_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE,
						'_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL,
						'_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER,
						'_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN)
					)
				);
				array_push($configs, array(
					'name' => 'com_anonpost',
					'title' => '_CM_COMANONPOST',
					'description' => '',
					'formtype' => 'yesno',
					'valuetype' => 'int',
					'default' => 0
					)
				);
			}
		} else {
			if ($module->getVar('hascomments') != 0) {
				include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
				$configs[] = array(
					'name' => 'com_rule',
					'title' => '_CM_COMRULES',
					'description' => '',
					'formtype' => 'select',
					'valuetype' => 'int',
					'default' => 1,
					'options' => array(
						'_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE,
						'_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL,
						'_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER,
						'_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN
					)
				);
				$configs[] = array(
					'name' => 'com_anonpost',
					'title' => '_CM_COMANONPOST',
					'description' => '',
					'formtype' => 'yesno',
					'valuetype' => 'int',
					'default' => 0
				);
			}
		}

		if ($module->getVar('hasnotification') != 0) {
			if (empty($configs)) {
				$configs = array();
			}
			// Main notification options
			include_once ICMS_INCLUDE_PATH . '/notification_constants.php';
			$options = array(
				'_NOT_CONFIG_DISABLE' => XOOPS_NOTIFICATION_DISABLE,
				'_NOT_CONFIG_ENABLEBLOCK' => XOOPS_NOTIFICATION_ENABLEBLOCK,
				'_NOT_CONFIG_ENABLEINLINE' => XOOPS_NOTIFICATION_ENABLEINLINE,
				'_NOT_CONFIG_ENABLEBOTH' => XOOPS_NOTIFICATION_ENABLEBOTH,
			);

			$configs[] = array(
				'name' => 'notification_enabled',
				'title' => '_NOT_CONFIG_ENABLE',
				'description' => '_NOT_CONFIG_ENABLEDSC',
				'formtype' => 'select',
				'valuetype' => 'int',
				'default' => XOOPS_NOTIFICATION_ENABLEBOTH,
				'options'=>$options
			);
			// Event specific notification options
			// FIXME: for some reason the default doesn't come up properly
			//  initially is ok, but not when 'update' module..
			$options = array();
			$notification_handler = icms::handler('icms_data_notification');
			$categories = & $notification_handler->categoryInfo('', $module->getVar('mid'));
			foreach ($categories as $category) {
				$events = & $notification_handler->categoryEvents($category['name'], false, $module->getVar('mid'));
				foreach ($events as $event) {
					if (!empty($event['invisible'])) {
						continue;
					}
					$option_name = $category['title'] . ' : ' . $event['title'];
					$option_value = $category['name'] . '-' . $event['name'];
					$options[$option_name] = $option_value;
				}
			}
			$configs[] = array(
				'name' => 'notification_events',
				'title' => '_NOT_CONFIG_EVENTS',
				'description' => '_NOT_CONFIG_EVENTSDSC',
				'formtype' => 'select_multi',
				'valuetype' => 'array',
				'default' => array_values($options),
				'options' => $options
			);
		}

		if ($configs !== false) {
			$msgs[] = _MD_AM_CONFIG_ADDING;
			$config_handler = icms::handler('icms_config');
			$order = 0;
			foreach ($configs as $config) {
				// only insert ones that have been deleted previously with success
				if (!in_array($config['name'], $config_delng)) {
					$confobj = & $config_handler->createConfig();
					$confobj->setVar('conf_modid', (int) $newmid);
					$confobj->setVar('conf_catid', 0);
					$confobj->setVar('conf_name', $config['name']);
					$confobj->setVar('conf_title', $config['title'], true);
					$confobj->setVar('conf_desc', $config['description'], true);
					$confobj->setVar('conf_formtype', $config['formtype']);
					$confobj->setVar('conf_valuetype', $config['valuetype']);
					if (isset($config_old[$config['name']]['value'])
						&& $config_old[$config['name']]['formtype'] == $config['formtype']
						&& $config_old[$config['name']]['valuetype'] == $config['valuetype']
					) {
						// preserve the old value if any
						// form type and value type must be the same
						// need to deal with arrays, because getInfo('config') doesn't convert arrays
						if (is_array($config_old[$config['name']]['value'])) {
							$confobj->setVar('conf_value', serialize($config_old[$config['name']]['value']), true);
						} else {
							$confobj->setVar('conf_value', $config_old[$config['name']]['value'], true);
						}
					} else {
						$confobj->setConfValueForInput($config['default'], true);
					}
					$confobj->setVar('conf_order', $order);
					$confop_msgs = '';
					if (isset($config['options']) && is_array($config['options'])) {
						foreach ($config['options'] as $key => $value) {
							$confop = & $config_handler->createConfigOption();
							$confop->setVar('confop_name', $key, true);
							$confop->setVar('confop_value', $value, true);
							$confobj->setConfOptions($confop);
							$confop_msgs .= sprintf('<br />&nbsp;&nbsp;&nbsp;&nbsp;' . _MD_AM_CONFIGOPTION_ADDED,
								'<strong>' . $key . '</strong>',
								'<strong>' . $value . '</strong>');
							unset($confop);
						}
					}
					$order++;
					if (false !== $config_handler->insertConfig($confobj)) {
						$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_CONFIG_ADDED, '<strong>' . $config['name'] . '</strong>. ')
						. $confop_msgs;
					} else {
						$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_CONFIG_ADD_FAIL . '</span>',
						'<strong>' . $config['name'] . '</strong>. ');
					}
					unset($confobj);
				}
			}
			unset($configs);
		}

		// add module specific tasks to system autotasks list
		$atasks = $module->getInfo('autotasks');
		$atasks_handler = &icms_getModuleHandler('autotasks', 'system');
		if (isset($atasks) && is_array($atasks) && (count($atasks) > 0)) {
			$msgs[] = _MD_AM_AUTOTASK_UPDATE;
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item('sat_type', 'addon/' . $module->getInfo('dirname')));
			$items_atasks = $atasks_handler->getObjects($criteria, false);
			foreach ($items_atasks as $task) {
				$taskID = (int) $task->getVar('sat_addon_id');
				$atasks[$taskID]['enabled'] = $task->getVar('sat_enabled');
				$atasks[$taskID]['repeat'] = $task->getVar('sat_repeat');
				$atasks[$taskID]['interval'] = $task->getVar('sat_interval');
				$atasks[$taskID]['name'] = $task->getVar('sat_name');
			}
			$atasks_handler->deleteAll($criteria);
			if (is_array($atasks)) {
				foreach ($atasks as $taskID => $taskData) {
					if (!isset($taskData['code']) || trim($taskData['code']) == '') {
						continue;
					}
					$task = &$atasks_handler->create();
					if (isset($taskData['enabled'])) {
						$task->setVar('sat_enabled', $taskData['enabled']);
					}
					if (isset($taskData['repeat'])) {
						$task->setVar('sat_repeat', $taskData['repeat']);
					}
					if (isset($taskData['interval'])) {
						$task->setVar('sat_interval', $taskData['interval']);
					}
					if (isset($taskData['onfinish'])) {
						$task->setVar('sat_onfinish', $taskData['onfinish']);
					}
					$task->setVar('sat_name', $taskData['name']);
					$task->setVar('sat_code', $taskData['code']);
					$task->setVar('sat_type', 'addon/' . $module->getInfo('dirname'));
					$task->setVar('sat_addon_id', (int) $taskID);
					if (!($atasks_handler->insert($task))) {
						$msgs[] = sprintf('&nbsp;&nbsp;<span style="color:#ff0000;">' . _MD_AM_AUTOTASK_FAIL . '</span>',
							'<strong>' . $taskData['name'] . '</strong>');
					} else {
						$msgs[] = sprintf('&nbsp;&nbsp;' . _MD_AM_AUTOTASK_ADDED,
							'<strong>' . $taskData['name'] . '</strong>');
					}
				}
			}
			unset($atasks, $atasks_handler, $task, $taskData, $criteria, $items, $taskID);
		}

		// execute module specific update script if any
		$update_script = $module->getInfo('onUpdate');
		$ModName = ($module->getInfo('modname') != '')? trim($module->getInfo('modname')):$dirname;
		if (false !== $update_script && trim($update_script) != '') {
			include_once ICMS_MODULES_PATH . '/' . $dirname . '/' . trim($update_script);

			$is_IPF = $module->getInfo('object_items');
			if (!empty($is_IPF)) {
				$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();
				$icmsDatabaseUpdater->moduleUpgrade($module, true);
				array_merge($msgs, $icmsDatabaseUpdater->_messages);
			}

			if (function_exists('xoops_module_update_' . $ModName)) {
				$func = 'xoops_module_update_' . $ModName;
				if (!$func($module, $prev_version, $prev_dbversion)) {
					$msgs[] = sprintf(_MD_AM_FAIL_EXEC, '<strong>' . $func . '</strong>');
				} else {
					$msgs[] = $module->messages;
					$msgs[] = sprintf(_MD_AM_FUNCT_EXEC, '<strong>' . $func . '</strong>');
				}
			} elseif (function_exists('icms_module_update_' . $ModName)) {
				$func = 'icms_module_update_' . $ModName;
				if (!$func($module, $prev_version, $prev_dbversion)) {
					$msgs[] = sprintf(_MD_AM_FAIL_EXEC, '<strong>' . $func . '</strong>');
				} else {
					$msgs[] = $module->messages;
					$msgs[] = sprintf(_MD_AM_FUNCT_EXEC, '<strong>' . $func . '</strong>');
				}
			}
		}

		$msgs[] = '</code><p>' . sprintf(_MD_AM_OKUPD, '<strong>' . $module->getVar('name') . '</strong>') . '</p>';
	}
	$ret = '<code>' . implode('<br />', $msgs);
	return $ret;
}

