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
				// break the loop if missing block config
				if (!isset($block['file']) || !isset($block['show_func'])) {
					break;
				}
				$options = '';
				if (!empty($block['options'])) {
					$options = trim($block['options']);
				}
				$template = '';
				if ((isset($block['template']) && trim($block['template']) != '')) {
					$content = & xoops_module_gettemplate($dirname, $block['template'], true);
				}
				if (empty($content)) {
					$content = '';
				} else {
					$template = trim($block['template']);
				}
				/**
				 * @var icms_view_block_Object $newBlock
				 */
				$newBlock = $this->create();
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
				$newBlock->setVar('edit_func', isset($block['edit_func'])? trim($block['edit_func']):'');
				$newBlock->setVar('template',$template);
				$newBlock->setVar('bcachetime',0);
				$newBlock->setVar('last_modified',time());

				if (!$newBlock->store()) {
					$logger->error(
						sprintf('  ' . _MD_AM_BLOCKS_ADD_FAIL , $block['name'] )
					);
				} else {
					$newbid = $newBlock->id();
					$logger->info(
						sprintf(_MD_AM_BLOCK_ADDED, $block['name'], icms_conv_nr2local($newbid) )
					);
					$sql = 'INSERT INTO ' . $this->db->prefix('block_module_link')
						. ' (block_id, module_id, page_id) VALUES ('
						. (int) $newbid . ', 0, 1)';
					$this->db->query($sql);
					if ($template != '') {
						/**
						 * @var icms_view_template_file_Object $tplfile
						 */
						$tplfile = icms::handler('icms_view_template_file')->create();
						$tplfile->setVar('tpl_refid', $newbid);
						$tplfile->setVar('tpl_source', $content, true);
						$tplfile->setVar('tpl_tplset', 'default');
						$tplfile->setVar('tpl_file', $block['template']);
						$tplfile->setVar('tpl_module', $dirname);
						$tplfile->setVar('tpl_type', 'block');
						$tplfile->setVar('tpl_desc', isset($block['description'])?$block['description']:'', true);
						$tplfile->setVar('tpl_lastimported', 0);
						$tplfile->setVar('tpl_lastmodified', time());
						if (!$tplfile->store()) {
							$logger->error('  ' . _MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
						} else {
							$newtplid = $tplfile->getVar('tpl_id');
							$logger->info('  ' . _MD_AM_TEMPLATE_INSERTED,  $block['template'], icms_conv_nr2local($newtplid) );
							// generate compiled file
							if (!icms_view_Tpl::template_touch($newtplid)) {
								$logger->error('  ' . _MD_AM_TEMPLATE_COMPILE_FAIL, $block['template'],icms_conv_nr2local($newtplid)  );
							} else {
								$logger->info('  ' . _MD_AM_TEMPLATE_COMPILED , $block['template']  );
							}
						}
					}
				}
				unset($content);
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
}