<?php


namespace ImpressCMS\Core\SetupSteps\Module\Install;


use icms;
use icms_module_Object;
use icms_view_block_Object;
use icms_view_template_file_Object;
use icms_view_Tpl;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use function icms_conv_nr2local;

class BlockSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$blocks = $module->getInfo('blocks');
		if ($blocks !== false) {
			$output->info(_MD_AM_BLOCKS_ADDING);
			$output->incrIndent();
			$dirname = $module->getVar('dirname');
			$newmid = $module->getVar('mid');
			$handler = icms::handler('icms_view_block');
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
					$output->error(_MD_AM_BLOCKS_ADD_FAIL, $block['name']);
				} else {
					$newbid = $newBlock->id();
					$output->success(_MD_AM_BLOCK_ADDED, $block['name'], icms_conv_nr2local($newbid));
					$sql = sprintf(
						'INSERT INTO `%s` (block_id, module_id, page_id) VALUES(%d, %d, %d);',
						$module->handler->db->prefix('block_module_link'),
						$newbid,
						0,
						1
					);
					$module->handler->db->query($sql);
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
						$tplfile->setVar('tpl_desc', isset($block['description']) ? $block['description'] : '', true);
						$tplfile->setVar('tpl_lastimported', 0);
						$tplfile->setVar('tpl_lastmodified', time());
						if (!$tplfile->store()) {
							$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
						} else {
							$newtplid = $tplfile->getVar('tpl_id');
							$output->success(_MD_AM_TEMPLATE_INSERTED, $block['template'], icms_conv_nr2local($newtplid));
							// generate compiled file
							if (!icms_view_Tpl::template_touch($newtplid)) {
								$output->error(_MD_AM_TEMPLATE_COMPILE_FAIL, $block['template'], icms_conv_nr2local($newtplid));
							} else {
								$output->success(_MD_AM_TEMPLATE_COMPILED, $block['template']);
							}
						}
					}
				}
				unset($content);
			}
		}
		$output->resetIndent();
		$this->updateBlocksPermissions($module, $output);
		$output->resetIndent();

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
	 * @param OutputDecorator $output
	 */
	protected function updateBlocksPermissions(icms_module_Object $module, OutputDecorator $output)
	{
		$groups = $module->getInfo('hasMain') ? [ICMS_GROUP_ADMIN, ICMS_GROUP_USERS, ICMS_GROUP_ANONYMOUS] : [ICMS_GROUP_ADMIN];
		$icms_block_handler = icms::handler('icms_view_block');
		$newmid = $module->getVar('mid');
		$blocks = &$icms_block_handler->getByModule($newmid, false);
		$output->info(_MD_AM_PERMS_ADDING);
		$output->incrIndent();
		$gperm_handler = icms::handler('icms_member_groupperm');
		foreach ($groups as $mygroup) {
			if ($gperm_handler->checkRight('module_admin', 0, $mygroup)) {
				$mperm = &$gperm_handler->create();
				$mperm->setVar('gperm_groupid', $mygroup);
				$mperm->setVar('gperm_itemid', $newmid);
				$mperm->setVar('gperm_name', 'module_admin');
				$mperm->setVar('gperm_modid', 1);
				if (!$mperm->store()) {
					$output->error(_MD_AM_ADMIN_PERM_ADD_FAIL, icms_conv_nr2local($mygroup));
				} else {
					$output->success(_MD_AM_ADMIN_PERM_ADDED, icms_conv_nr2local($mygroup));
				}
				unset($mperm);
			}
			$mperm = &$gperm_handler->create();
			$mperm->setVar('gperm_groupid', $mygroup);
			$mperm->setVar('gperm_itemid', $newmid);
			$mperm->setVar('gperm_name', 'module_read');
			$mperm->setVar('gperm_modid', 1);
			if (!$mperm->store()) {
				$output->error(_MD_AM_USER_PERM_ADD_FAIL, icms_conv_nr2local($mygroup));
			} else {
				$output->success(_MD_AM_USER_PERM_ADDED, icms_conv_nr2local($mygroup));
			}
			unset($mperm);
			foreach ($blocks as $blc) {
				$bperm = &$gperm_handler->create();
				$bperm->setVar('gperm_groupid', $mygroup);
				$bperm->setVar('gperm_itemid', $blc);
				$bperm->setVar('gperm_name', 'block_read');
				$bperm->setVar('gperm_modid', 1);
				if (!$bperm->store()) {
					$output->error(_MD_AM_BLOCK_ACCESS_FAIL, icms_conv_nr2local($blc), icms_conv_nr2local($mygroup));
				} else {
					$output->success(_MD_AM_BLOCK_ACCESS_ADDED, icms_conv_nr2local($blc), icms_conv_nr2local($mygroup));
				}
				unset($bperm);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 1;
	}
}