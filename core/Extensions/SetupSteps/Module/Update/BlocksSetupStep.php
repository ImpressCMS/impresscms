<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;

use Exception;
use icms;
use ImpressCMS\Core\Database\DatabaseConnection;
use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\BlockSetupStep as InstallBlockSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Models\BlockHandler;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\TemplateFileHandler;
use ImpressCMS\Core\View\Template;
use function icms_conv_nr2local;

class BlocksSetupStep extends InstallBlockSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		global $icmsConfig;

		$blocks = $module->getInfo('blocks');
		$output->info(_MD_AM_MOD_REBUILD_BLOCKS);
		$output->incrIndent();

		/**
		 * @var TemplateFileHandler $tplfile_handler
		 */
		$tplfile_handler = &icms::handler('icms_view_template_file');

		/**
		 * @var DatabaseConnection $db
		 */
		$db = $this->getContainer()->get('db');

		/**
		 * @var BlockHandler $newBlocksHandler
		 */
		$newBlocksHandler = icms::handler('icms_view_block');

		if ($blocks !== false) {
			$showfuncs = [];
			$funcfiles = [];
			foreach ($blocks as $i => $block) {
				if (isset($block['show_func'], $block['file']) && $block['show_func'] && $block['file']) {
					$editfunc = $block['edit_func'] ?? '';
					$showfuncs[] = $block['show_func'];
					$funcfiles[] = $block['file'];
					$template = $content = '';
					if ((isset($block['template']) && trim($block['template']))) {
						$content = $this->readTemplate($module->dirname, $block['template']);
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
					$fcount = 0;
					foreach (
						$db->fetchAll(
							'SELECT bid, name
							FROM ' . $db->prefix('newblocks') . '
							WHERE mid=:mid AND func_num=:func_num AND show_func=:show_func AND func_file=:func_file',
							[
								'mid' => $module->mid,
								'func_num' => $i,
								'show_func' => $block['show_func'],
								'func_file' => $block['file']
							]
						) as $fblock
					) {
						$fcount++;
						try {
							$db->perform(
								'UPDATE ' . $db->prefix('newblocks') . '
									  SET name=:name, edit_func=:edit_func, content=:content, template=:template, last_modified=:last_modified
									  WHERE bid=:bid', [
								'name' => $this->getTranslatedName($block['name']),
								'edit_func' => $editfunc,
								'content' => '',
								'template' => $template,
								'last_modified' => time(),
								'bid' => $fblock['bid']
							]);
							$result = true;
						} catch (Exception $exception) {
							$result = false;
						}
						if (!$result) {
							$output->error(_MD_AM_UPDATE_FAIL, $this->getTranslatedName($fblock['name']));
						} else {
							$output->success(_MD_AM_BLOCK_UPDATED, $this->getTranslatedName($fblock['name']), icms_conv_nr2local($fblock['bid']));
							if ($template) {
								$tplfile = $tplfile_handler->find('default', 'block', $fblock['bid']);
								if (empty($tplfile)) {
									$tplfile_new = &$tplfile_handler->create();
									$tplfile_new->tpl_module = $module->dirname;
									$tplfile_new->tpl_refid = (int)$fblock['bid'];
									$tplfile_new->tpl_tplset = 'default';
									$tplfile_new->setVar('tpl_file', $block['template'], true);
									$tplfile_new->tpl_type = 'block';
								} else {
									$tplfile_new = $tplfile[0];
								}
								$tplfile_new->setVar('tpl_source', $content, true);
								$tplfile_new->setVar('tpl_desc', $block['description'], true);
								$tplfile_new->tpl_lastmodified = time();
								$tplfile_new->tpl_lastimported = 0;
								if (!$tplfile_handler->insert($tplfile_new)) {
									$output->error(_MD_AM_TEMPLATE_UPDATE_FAIL, $block['template']);
								} else {
									$output->success(_MD_AM_TEMPLATE_UPDATED, $block['template']);
									if ($icmsConfig['template_set'] === 'default') {
										if (!Template::template_touch($tplfile_new->tpl_id)) {
											$output->error(_MD_AM_TEMPLATE_RECOMPILE_FAIL, $block['template']);
										} else {
											$output->success(_MD_AM_TEMPLATE_RECOMPILED, $block['template']);
										}
									}
								}
							}
						}
					}

					if ($fcount === 0) {
						$newBlock = $this->createNewBlock($block, $module, $i, $template);

						if (!$newBlock->store()) {
							$output->error(_MD_AM_CREATE_FAIL, $this->getTranslatedName($block['name']));
						} else {
							$newbid = $newBlock->bid;
							$groups = &icms::$user->getGroups();
							$gperm_handler = icms::handler('icms_member_groupperm');
							foreach ($groups as $mygroup) {
								$bperm = &$gperm_handler->create();
								$bperm->gperm_groupid = $mygroup;
								$bperm->gperm_itemid = $newbid;
								$bperm->gperm_name = 'block_read';
								$bperm->gperm_modid = 1;
								if (!$gperm_handler->insert($bperm)) {
									$output->error(_MD_AM_BLOCK_ACCESS_FAIL . ' ' . $newbid, $mygroup);
								} else {
									$output->success(_MD_AM_BLOCK_ACCESS_ADDED . ' ' . $newbid, $mygroup);
								}
							}

							if ($template) {
								$tplfile = &$tplfile_handler->create();
								$tplfile->tpl_module = $module->dirname;
								$tplfile->tpl_refid = (int)$newbid;
								$tplfile->setVar('tpl_source', $content, true);
								$tplfile->tpl_tplset = 'default';
								$tplfile->setVar('tpl_file', $block['template'], true);
								$tplfile->tpl_type = 'block';
								$tplfile->tpl_lastimported = 0;
								$tplfile->tpl_lastmodified = time();
								$tplfile->setVar('tpl_desc', $block['description'], true);
								if (!$tplfile_handler->insert($tplfile)) {
									$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
								} else {
									$newid = $tplfile->tpl_id;
									$output->success(_MD_AM_TEMPLATE_INSERTED, $block['template'], $newid);
									if ($icmsConfig['template_set'] === 'default') {
										if (!Template::template_touch($newid)) {
											$output->error(_MD_AM_TEMPLATE_RECOMPILE_FAIL, $block['template']);
										} else {
											$output->success(_MD_AM_TEMPLATE_RECOMPILED, $block['template']);
										}
									}
								}
							}
							$output->success(_MD_AM_BLOCK_CREATED, $this->getTranslatedName($block['name']), $newbid);
							$db->perform('INSERT INTO ' . $db->prefix('block_module_link')
								. ' (block_id, module_id, page_id) VALUES (:bid, :mid, :pid);', [
								'bid' => $newbid,
								'mid' => 0,
								'pid' => 1
							]);
						}
					}
				}
			}

			$block_arr = $newBlocksHandler->getByModule($module->mid);
			foreach ($block_arr as $block) {
				if (!in_array($block->show_func, $showfuncs, false) || !in_array($block->func_file, $funcfiles, false)) {
					if (!$newBlocksHandler->delete($block)) {
						$output->error(_MD_AM_BLOCK_DELETE_FAIL, $block->name, $block->bid);
					} else {
						$output->success(_MD_AM_BLOCK_DELETED, $block->name, $block->bid);
						if ($block->template) {
							$tplfiles = $tplfile_handler->find(null, 'block', $block->bid);
							if (is_array($tplfiles)) {
								foreach ($tplfiles as $k => $kValue) {
									if (!$tplfile_handler->delete($tplfiles[$k])) {
										$output->error(_MD_AM_BLOCK_TMPLT_DELETE_FAILED, $kValue->tpl_file, $kValue->tpl_id);
									} else {
										$output->success(_MD_AM_BLOCK_TMPLT_DELETED, $kValue->tpl_file, $kValue->tpl_id);
									}
								}
							}
						}
					}
				}
			}
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 16;
	}
}