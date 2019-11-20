<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class BlockModuleInstallationHelper implements ModuleInstallationHelperInterface
{
	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$blocks = $module->getInfo('blocks');
		if ($blocks !== false) {
			$logger->info(_MD_AM_BLOCKS_ADDING);
			$dirname = $module->getVar('dirname');
			$newmid = $module->getVar('mid');
			$handler = \icms::handler('icms_view_block');
			foreach ($blocks as $blockkey => $block) {
				if (!isset($block['file']) || !isset($block['show_func'])) {
					continue;
				}
				$options = !empty($block['options']) ? trim($block['options']) : '';
				$template = '';
				if ((isset($block['template']) && trim($block['template']) != '')) {
					$content = $this->readTemplate($dirname, $block['template']);
				}
				if (empty($content)) {
					$content = '';
				} else {
					$template = trim($block['template']);
				}
				/**
				 * @var icms_view_block_Object $newBlock
				 */
				$newBlock = $handler->create();
				$newBlock->setVar('name', trim($block['name']));
				$newBlock->setVar('mid', $newmid);
				$newBlock->setVar('func_num', $blockkey);
				$newBlock->setVar('options', $options);
				$newBlock->setVar('name', $block['name']);
				$newBlock->setVar('title', $block['name']);
				$newBlock->setVar('content', '');
				$newBlock->setVar('side', 1);
				$newBlock->setVar('weight', 0);
				$newBlock->setVar('visible', 0);
				$newBlock->setVar('block_type', 'M');
				$newBlock->setVar('c_type', 'H');
				$newBlock->setVar('isactive', 1);
				$newBlock->setVar('dirname', $dirname);
				$newBlock->setVar('func_file', $block['file']);
				$newBlock->setVar('show_func', $block['show_func']);
				$newBlock->setVar('edit_func', isset($block['edit_func']) ? trim($block['edit_func']) : '');
				$newBlock->setVar('template', $template);
				$newBlock->setVar('bcachetime', 0);
				$newBlock->setVar('last_modified', time());

				if (!$newBlock->store()) {
					$logger->error(
						sprintf('  ' . _MD_AM_BLOCKS_ADD_FAIL, $block['name'])
					);
				} else {
					$newbid = $newBlock->id();
					$logger->info(
						sprintf(_MD_AM_BLOCK_ADDED, $block['name'], icms_conv_nr2local($newbid))
					);
					$sql = 'INSERT INTO ' . $this->db->prefix('block_module_link')
						. ' (block_id, module_id, page_id) VALUES ('
						. (int)$newbid . ', 0, 1)';
					$this->db->query($sql);
					if ($template != '') {
						/**
						 * @var icms_view_template_file_Object $tplfile
						 */
						$tplfile = \icms::handler('icms_view_template_file')->create();
						$tplfile->setVar('tpl_refid', $newbid);
						$tplfile->setVar('tpl_source', $content, true);
						$tplfile->setVar('tpl_tplset', 'default');
						$tplfile->setVar('tpl_file', $block['template']);
						$tplfile->setVar('tpl_module', $dirname);
						$tplfile->setVar('tpl_type', 'block');
						$tplfile->setVar('tpl_desc', isset($block['description']) ? $block['description'] : '', true);
						$tplfile->setVar('tpl_lastimported', 0);
						$tplfile->setVar('tpl_lastmodified', time());
						if (!$tplfile->store()) {
							$logger->error('  ' . _MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
						} else {
							$newtplid = $tplfile->getVar('tpl_id');
							$logger->info('  ' . _MD_AM_TEMPLATE_INSERTED, $block['template'], icms_conv_nr2local($newtplid));
							// generate compiled file
							if (!icms_view_Tpl::template_touch($newtplid)) {
								$logger->error('  ' . _MD_AM_TEMPLATE_COMPILE_FAIL, $block['template'], icms_conv_nr2local($newtplid));
							} else {
								$logger->info('  ' . _MD_AM_TEMPLATE_COMPILED, $block['template']);
							}
						}
					}
				}
				unset($content);
			}
		}
		$this->updateBlocksPermissions($module, $logger);

		return true;
	}

	/**
	 * Read template from file
	 *
	 * @param string $dirname Dirname from where to read
	 * @param string $filename Filename to read
	 *
	 * @return string
	 */
	protected function readTemplate(string $dirname, string $filename): string
	{
		$ret = '';
		$file = ICMS_MODULES_PATH . '/' . $dirname . '/templates/blocks/' . $filename;
		if (!file_exists($file)) {
			return $ret;
		}
		return str_replace(["\r\n", "\n"], ["\n", "\r\n"], file_get_contents($file));
	}

	/**
	 * Updates blocks permissions
	 *
	 * @param icms_module_Object $module Module to update
	 * @param LoggerInterface $logger Logger to print messages
	 */
	protected function updateBlocksPermissions(icms_module_Object $module, LoggerInterface $logger)
	{
		$groups = $module->getInfo('hasMain') ? [ICMS_GROUP_ADMIN, ICMS_GROUP_USERS, ICMS_GROUP_ANONYMOUS] : [ICMS_GROUP_ADMIN];
		$icms_block_handler = \icms::handler('icms_view_block');
		$newmid = $module->getVar('mid');
		$blocks = &$icms_block_handler->getByModule($newmid, false);
		$logger->info(_MD_AM_PERMS_ADDING);
		$gperm_handler = \icms::handler('icms_member_groupperm');
		foreach ($groups as $mygroup) {
			if ($gperm_handler->checkRight('module_admin', 0, $mygroup)) {
				$mperm = &$gperm_handler->create();
				$mperm->setVar('gperm_groupid', $mygroup);
				$mperm->setVar('gperm_itemid', $newmid);
				$mperm->setVar('gperm_name', 'module_admin');
				$mperm->setVar('gperm_modid', 1);
				if (!$gperm_handler->insert($mperm)) {
					$logger->error(
						sprintf('  ' . _MD_AM_ADMIN_PERM_ADD_FAIL, icms_conv_nr2local($mygroup))
					);
				} else {
					$logger->info(
						sprintf('  ' . _MD_AM_ADMIN_PERM_ADDED, icms_conv_nr2local($mygroup))
					);
				}
				unset($mperm);
			}
			$mperm = &$gperm_handler->create();
			$mperm->setVar('gperm_groupid', $mygroup);
			$mperm->setVar('gperm_itemid', $newmid);
			$mperm->setVar('gperm_name', 'module_read');
			$mperm->setVar('gperm_modid', 1);
			if (!$gperm_handler->insert($mperm)) {
				$logger->error(
					sprintf('  ' . _MD_AM_USER_PERM_ADD_FAIL, icms_conv_nr2local($mygroup))
				);
			} else {
				$logger->info(
					sprintf('  ' . _MD_AM_USER_PERM_ADDED, icms_conv_nr2local($mygroup))
				);
			}
			unset($mperm);
			foreach ($blocks as $blc) {
				$bperm = &$gperm_handler->create();
				$bperm->setVar('gperm_groupid', $mygroup);
				$bperm->setVar('gperm_itemid', $blc);
				$bperm->setVar('gperm_name', 'block_read');
				$bperm->setVar('gperm_modid', 1);
				if (!$gperm_handler->insert($bperm)) {
					$logger->error(
						sprintf('  ' . _MD_AM_BLOCK_ACCESS_FAIL, icms_conv_nr2local($blc), icms_conv_nr2local($mygroup))
					);
				} else {
					$logger->info(
						sprintf('  ' . _MD_AM_BLOCK_ACCESS_ADDED, icms_conv_nr2local($blc), icms_conv_nr2local($mygroup))
					);
				}
				unset($bperm);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 1;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$icms_block_handler = \icms::handler('icms_view_block');
		$block_arr = $icms_block_handler->getByModule($module->getVar('mid'));
		if (!is_array($block_arr)) {
			return true;
		}
		$bcount = count($block_arr);
		$logger->info(_MD_AM_BLOCKS_DELETE);
		$tplfile_handler = \icms::handler('icms_view_template_file');
		for ($i = 0; $i < $bcount; $i++) {
			if (!$icms_block_handler->delete($block_arr[$i])) {
				$logger->error(
					sprintf('  ' . _MD_AM_BLOCK_DELETE_FAIL,
						$block_arr[$i]->getVar('name'),
						icms_conv_nr2local($block_arr[$i]->getVar('bid'))
					)
				);
			} else {
				$logger->info(
					sprintf('  ' . _MD_AM_BLOCK_DELETED,
						$block_arr[$i]->getVar('name'),
						icms_conv_nr2local($block_arr[$i]->getVar('bid'))
					)
				);
			}
			if ($block_arr[$i]->getVar('template') != '') {
				$templates = $tplfile_handler->find(null, 'block', $block_arr[$i]->getVar('bid'));
				$btcount = count($templates);
				if ($btcount > 0) {
					for ($j = 0; $j < $btcount; $j++) {
						if (!$tplfile_handler->delete($templates[$j])) {
							$logger->error(
								sprintf('  ' . _MD_AM_BLOCK_TMPLT_DELETE_FAILED,
									$templates[$j]->getVar('tpl_file'),
									icms_conv_nr2local($templates[$j]->getVar('tpl_id'))
								)
							);
						} else {
							$logger->info(
								sprintf('  ' . _MD_AM_BLOCK_TMPLT_DELETED,
									$templates[$j]->getVar('tpl_file'),
									icms_conv_nr2local($templates[$j]->getVar('tpl_id'))
								)
							);
						}
					}
				}
				unset($templates);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return 2;
	}
}