<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Block;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\TemplateFile;
use ImpressCMS\Core\View\Template;
use function icms_conv_nr2local;

class BlockSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$blocks = $module->getInfo('blocks');
		if ($blocks !== false) {
			$output->info(_MD_AM_BLOCKS_ADDING);
			$output->incrIndent();
			$dirname = $module->dirname;
			$newmid = $module->mid;
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
				 * @var Block $newBlock
				 */
				$newBlock = $handler->create();
				$newBlock->name = trim($block['name']);
				$newBlock->mid = $newmid;
				$newBlock->func_num = $blockkey;
				$newBlock->options = $options;
				$newBlock->name = $block['name'];
				$newBlock->title = $block['name'];
				$newBlock->content = '';
				$newBlock->side = 1;
				$newBlock->weight = 0;
				$newBlock->visible = 0;
				$newBlock->block_type = 'M';
				$newBlock->c_type = 'H';
				$newBlock->isactive = 1;
				$newBlock->dirname = $dirname;
				$newBlock->func_file = $block['file'];
				$newBlock->show_func = $block['show_func'];
				$newBlock->edit_func = isset($block['edit_func']) ? trim($block['edit_func']) : '';
				$newBlock->template = $template;
				$newBlock->bcachetime = 0;
				$newBlock->last_modified = time();

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
						 * @var TemplateFile $tplfile
						 */
						$tplfile = icms::handler('icms_view_template_file')->create();
						$tplfile->tpl_refid = $newbid;
						$tplfile->setVar('tpl_source', $content, true);
						$tplfile->tpl_tplset = 'default';
						$tplfile->tpl_file = $block['template'];
						$tplfile->tpl_module = $dirname;
						$tplfile->tpl_type = 'block';
						$tplfile->setVar('tpl_desc', isset($block['description']) ? $block['description'] : '', true);
						$tplfile->tpl_lastimported = 0;
						$tplfile->tpl_lastmodified = time();
						if (!$tplfile->store()) {
							$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
						} else {
							$newtplid = $tplfile->tpl_id;
							$output->success(_MD_AM_TEMPLATE_INSERTED, $block['template'], icms_conv_nr2local($newtplid));
							// generate compiled file
							if (!Template::template_touch($newtplid)) {
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
	 * @param Module $module Module to update
	 * @param OutputDecorator $output
	 */
	protected function updateBlocksPermissions(Module $module, OutputDecorator $output)
	{
		$groups = $module->getInfo('hasMain') ? [ICMS_GROUP_ADMIN, ICMS_GROUP_USERS, ICMS_GROUP_ANONYMOUS] : [ICMS_GROUP_ADMIN];
		$icms_block_handler = icms::handler('icms_view_block');
		$newmid = $module->mid;
		$blocks = &$icms_block_handler->getByModule($newmid, false);
		$output->info(_MD_AM_PERMS_ADDING);
		$output->incrIndent();
		$gperm_handler = icms::handler('icms_member_groupperm');
		foreach ($groups as $mygroup) {
			if ($gperm_handler->checkRight('module_admin', 0, $mygroup)) {
				$mperm = &$gperm_handler->create();
				$mperm->gperm_groupid = $mygroup;
				$mperm->gperm_itemid = $newmid;
				$mperm->gperm_name = 'module_admin';
				$mperm->gperm_modid = 1;
				if (!$mperm->store()) {
					$output->error(_MD_AM_ADMIN_PERM_ADD_FAIL, icms_conv_nr2local($mygroup));
				} else {
					$output->success(_MD_AM_ADMIN_PERM_ADDED, icms_conv_nr2local($mygroup));
				}
				unset($mperm);
			}
			$mperm = &$gperm_handler->create();
			$mperm->gperm_groupid = $mygroup;
			$mperm->gperm_itemid = $newmid;
			$mperm->gperm_name = 'module_read';
			$mperm->gperm_modid = 1;
			if (!$mperm->store()) {
				$output->error(_MD_AM_USER_PERM_ADD_FAIL, icms_conv_nr2local($mygroup));
			} else {
				$output->success(_MD_AM_USER_PERM_ADDED, icms_conv_nr2local($mygroup));
			}
			unset($mperm);
			foreach ($blocks as $blc) {
				$bperm = &$gperm_handler->create();
				$bperm->gperm_groupid = $mygroup;
				$bperm->gperm_itemid = $blc;
				$bperm->gperm_name = 'block_read';
				$bperm->gperm_modid = 1;
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