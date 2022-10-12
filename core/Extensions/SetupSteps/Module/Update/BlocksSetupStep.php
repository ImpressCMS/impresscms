<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;

use Exception;
use icms;
use Imponeer\Database\Criteria\CriteriaItem;
use Imponeer\Database\Criteria\Enum\ComparisionOperator;
use Imponeer\Database\Criteria\Enum\Condition;
use ImpressCMS\Core\Database\DatabaseConnection;
use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\BlockSetupStep as InstallBlockSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Facades\Member;
use ImpressCMS\Core\Models\BlockHandler;
use ImpressCMS\Core\Models\GroupPerm;
use ImpressCMS\Core\Models\GroupPermHandler;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\TemplateFile;
use ImpressCMS\Core\Models\TemplateFileHandler;
use ImpressCMS\Core\View\Template;
use function icms_conv_nr2local;

class BlocksSetupStep extends InstallBlockSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $info, OutputDecorator $output, ...$params): bool
	{
		global $icmsConfig;

		$blocks = $info->getInfo('blocks');
		$output->info(_MD_AM_MOD_REBUILD_BLOCKS);
		$output->incrIndent();

		/**
		 * @var TemplateFileHandler $tplfile_handler
		 */
		$tplfile_handler = &icms::handler('icms_view_template_file');

		$db = $this->getDB();

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
						$content = $this->readTemplate($info->dirname, $block['template']);
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
								'mid' => $info->mid,
								'func_num' => $i,
								'show_func' => $block['show_func'],
								'func_file' => $block['file']
							]
						) as $fblock
					) {
						$fcount++;
						if ($this->updateBlockCfg($block['name'], $editfunc, $template, $fblock['bid'])) {
							$output->success(_MD_AM_BLOCK_UPDATED, $this->getTranslatedName($fblock['name']), icms_conv_nr2local($fblock['bid']));
							if ($template) {
								$tplfile = $tplfile_handler->find('default', 'block', $fblock['bid']);
								if (empty($tplfile)) {
									$tplfile_new = &$tplfile_handler->create();
									$tplfile_new->tpl_module = $info->dirname;
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
						} else {
							$output->error(_MD_AM_UPDATE_FAIL, $this->getTranslatedName($fblock['name']));
						}
					}

					if ($fcount === 0) {
						$newBlock = $this->createNewBlock($block, $info, $i, $template);

						if (!$newBlock->store()) {
							$output->error(_MD_AM_CREATE_FAIL, $this->getTranslatedName($block['name']));
						} else {
							$newbid = $newBlock->bid;

							if (icms::$user) {
								$groups = &icms::$user->getGroups();
							} else {
								$groups = [ICMS_GROUP_ADMIN];
							}
							$gperm_handler = icms::handler('icms_member_groupperm');
							foreach ($groups as $mygroup) {
								$bperm = &$gperm_handler->create();
								$bperm->gperm_groupid = $mygroup;
								$bperm->gperm_itemid = $newbid;
								$bperm->gperm_name = 'block_read';
								$bperm->gperm_modid = 1;
								if (!$gperm_handler->insert($bperm)) {
									$output->error(_MD_AM_BLOCK_ACCESS_FAIL . ' ' . $newbid, $mygroup);

							foreach ($this->getGroupsIdsForModule($info->mid) as $mygroup) {
								if ($this->createBlockReadPermission($mygroup, $newbid)) {
									$output->success(_MD_AM_BLOCK_ACCESS_ADDED, $newbid, $mygroup);

								} else {
									$output->error(_MD_AM_BLOCK_ACCESS_FAIL, $newbid, $mygroup);
								}
							}

							if ($template) {
								$newid = $this->addTemplate(
									$info->dirname,
									(int)$newbid,
									$content,
									$block['template'],
									$block['description']
								);
								if ($newid) {
									$output->success(_MD_AM_TEMPLATE_INSERTED, $block['template'], $newid);
									if ($icmsConfig['template_set'] === 'default') {
										if (!Template::template_touch($newid)) {
											$output->error(_MD_AM_TEMPLATE_RECOMPILE_FAIL, $block['template']);
										} else {
											$output->success(_MD_AM_TEMPLATE_RECOMPILED, $block['template']);
										}
									}
								} else {
									$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $block['template']);
								}
							}
							$output->success(_MD_AM_BLOCK_CREATED, $this->getTranslatedName($block['name']), $newbid);
							$this->saveModuleBlockLink($newbid);
						}
					}
				}
			}

			$block_arr = $newBlocksHandler->getByModule($info->mid);
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

	protected function createBlockReadPermission(int $userGroupId, int $blockId): bool
	{
		/**
		 * @var GroupPermHandler $handler
		 */
		$handler = icms::handler('icms_member_groupperm');

		/**
		 * @var GroupPerm $permission
		 */
		$permission = $handler->create();

		$permission->gperm_groupid = $userGroupId;
		$permission->gperm_itemid = $blockId;
		$permission->gperm_name = 'block_read';
		$permission->gperm_modid = 1;

		return $permission->store();
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 16;
	}

	protected function saveModuleBlockLink(int $blockId): void
	{
		$db = $this->getDB();

		$db->perform('INSERT INTO ' . $db->prefix('block_module_link')
			. ' (block_id, module_id, page_id) VALUES (:bid, :mid, :pid);', [
			'bid' => $blockId,
			'mid' => 0,
			'pid' => 1
		]);
	}

	protected function getDB(): DatabaseConnection
	{
		return $this->getContainer()->get('db');
	}

	protected function addTemplate(
		string $dirname,
		int    $blockId,
		string $content,
		string $templateFile,
		string $description
	): ?int
	{

		/**
		 * @var TemplateFileHandler $templateFileHandler
		 */
		$templateFileHandler = &icms::handler('icms_view_template_file');

		/**
		 * @var TemplateFile $template
		 */
		$template = $templateFileHandler->create();
		$template->tpl_module = $dirname;
		$template->tpl_refid = $blockId;
		$template->setVar('tpl_source', $content, true);
		$template->tpl_tplset = 'default';
		$template->setVar('tpl_file', $templateFile, true);
		$template->tpl_type = 'block';
		$template->tpl_lastimported = 0;
		$template->tpl_lastmodified = time();
		$template->setVar('tpl_desc', $description, true);

		if ($template->store()) {
			return $template->tpl_id;
		}

		return null;
	}

	protected function updateBlockCfg(string $blockName, string $editFunc, string $template, int $blockId): bool
	{
		$db = $this->getDB();

		try {
			$db->perform(
				'UPDATE ' . $db->prefix('newblocks') . '
									  SET name=:name, edit_func=:edit_func, content=:content, template=:template, last_modified=:last_modified
									  WHERE bid=:bid', [
				'name' => $this->getTranslatedName($blockName),
				'edit_func' => $editFunc,
				'content' => '',
				'template' => $template,
				'last_modified' => time(),
				'bid' => $blockId
			]);
		} catch (Exception $exception) {
			return false;
		}

		return true;
	}

	protected function getGroupsIdsForModule(int $moduleId): array
	{
		$db = $this->getDB();

		return $db->fetchCol(
			'SELECT DISTINCT gperm_groupid
							FROM ' . $db->prefix('group_permission') . '
							WHERE gperm_modid=:mid',
			[
				'mid' => $moduleId,
			]
		);
	}

}