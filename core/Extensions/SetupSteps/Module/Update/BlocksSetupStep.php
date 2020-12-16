<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;


use Exception;
use icms;
use ImpressCMS\Core\Database\DatabaseConnection;
use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\BlockSetupStep as InstallBlockSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Models\Block;
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
		$db = icms::getInstance()->get('db');

		/**
		 * @var BlockHandler $newBlocksHandler
		 */
		$newBlocksHandler = icms::handler('icms_view_block');

		if ($blocks !== false) {
			$showfuncs = array();
			$funcfiles = array();
			foreach ($blocks as $i => $block) {
				if (isset($block['show_func']) && $block['show_func'] != '' && isset($block['file']) && $block['file'] != '') {
					$editfunc = isset($block['edit_func']) ? $block['edit_func'] : '';
					$showfuncs[] = $block['show_func'];
					$funcfiles[] = $block['file'];
					$template = $content = '';
					if ((isset($block['template']) && trim($block['template']) != '')) {
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
								'UPDATE ' . $db->prefix(newblocks) . '
									  SET name=:name, edit_func=:edit_func, content=:content, template=:template, last_modified=:last_modified
									  WHERE bid=:bid', [
								'name' => $block['name'],
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
							$output->error(_MD_AM_UPDATE_FAIL, $fblock['name']);
						} else {
							$output->success(_MD_AM_BLOCK_UPDATED, $fblock['name'], icms_conv_nr2local($fblock['bid']));
							if ($template != '') {
								$tplfile = $tplfile_handler->find('default', 'block', $fblock['bid']);
								if (count($tplfile) == 0) {
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
									if ($icmsConfig['template_set'] == 'default') {
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

					if ($fcount == 0) {
						/**
						 * @var Block $newBlock
						 */
						$newBlock = $newBlocksHandler->create();
						$newBlock->mid = $module->mid;
						$newBlock->func_num = $i;
						$newBlock->options = $options;
						$newBlock->name = $block['name'];
						$newBlock->title = $block['name'];
						$newBlock->content = '';
						$newBlock->side = 1;
						$newBlock->weight = 0;
						$newBlock->visible = 0;
						$newBlock->block_type = Block::BLOCK_TYPE_MODULE;
						$newBlock->c_type = Block::CONTENT_TYPE_HTML;
						$newBlock->isactive = 1;
						$newBlock->dirname = $module->dirname;
						$newBlock->func_file = $block['file'];
						$newBlock->show_func = $block['show_func'];
						$newBlock->edit_func = $editfunc;
						$newBlock->template = $template;
						$newBlock->bcachetime = 0;
						$newBlock->last_modified = time();

						if (!$newBlock->store()) {
							$output->error(_MD_AM_CREATE_FAIL, $block['name']);
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

							if ($template != '') {
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
									if ($icmsConfig['template_set'] == 'default') {
										if (!Template::template_touch($newid)) {
											$output->error(_MD_AM_TEMPLATE_RECOMPILE_FAIL, $block['template']);
										} else {
											$output->success(_MD_AM_TEMPLATE_RECOMPILED, $block['template']);
										}
									}
								}
							}
							$output->success(_MD_AM_BLOCK_CREATED, $block['name'], $newbid);
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
				if (!in_array($block->show_func, $showfuncs) || !in_array($block->func_file, $funcfiles)) {
					if (!$newBlocksHandler->delete($block)) {
						$output->error(_MD_AM_BLOCK_DELETE_FAIL, $block->name, $block->bid);
					} else {
						$output->success(_MD_AM_BLOCK_DELETED, $block->name, $block->bid);
						if ($block->template != '') {
							$tplfiles = &$tplfile_handler->find(null, 'block', $block->bid);
							if (is_array($tplfiles)) {
								$btcount = count($tplfiles);
								for ($k = 0; $k < $btcount; $k++) {
									if (!$tplfile_handler->delete($tplfiles[$k])) {
										$output->error(_MD_AM_BLOCK_TMPLT_DELETE_FAILED, $tplfiles[$k]->tpl_file, $tplfiles[$k]->tpl_id);
									} else {
										$output->success(_MD_AM_BLOCK_TMPLT_DELETED, $tplfiles[$k]->tpl_file, $tplfiles[$k]->tpl_id);
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