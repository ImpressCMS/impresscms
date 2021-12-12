<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Block;
use ImpressCMS\Core\Models\BlockHandler;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\TemplateFile;
use ImpressCMS\Core\View\Template;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Symfony\Contracts\Translation\TranslatorInterface;
use function icms_conv_nr2local;

class BlockSetupStep implements SetupStepInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

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

			foreach ($blocks as $blockkey => $block) {
				if (!isset($block['file'], $block['show_func'])) {
					continue;
				}

				$template = '';
				if ((isset($block['template']) && trim($block['template']))) {
					$content = $this->readTemplate($dirname, $block['template']);
				}
				if (empty($content)) {
					$content = '';
				} else {
					$template = trim($block['template']);
				}

				$newBlock = $this->createNewBlock($block, $module, $blockkey, $template);

				if (!$newBlock->store()) {
					$output->error(_MD_AM_BLOCKS_ADD_FAIL, $this->getTranslatedName($block['name']));
				} else {
					$newbid = $newBlock->id();
					$output->success(_MD_AM_BLOCK_ADDED, $this->getTranslatedName($block['name']), icms_conv_nr2local($newbid));
					$sql = sprintf(
						'INSERT INTO `%s` (block_id, module_id, page_id) VALUES(%d, %d, %d);',
						$module->handler->db->prefix('block_module_link'),
						$newbid,
						0,
						1
					);
					$module->handler->db->query($sql);
					if ($template) {
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
						$tplfile->setVar(
							'tpl_desc',
							$block['description'] ?? '',
							true
						);
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
	protected function updateBlocksPermissions(Module $module, OutputDecorator $output): void
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
	 * Gets translated named
	 *
	 * @param string $name String or constant to translate
	 *
	 * @return string
	 */
	protected function getTranslatedName(string $name): string
	{
		/**
		 * @var TranslatorInterface $translator
		 */
		$translator = $this->getContainer()->get('translator');

		return $translator->trans($name, [], 'modinfo');
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 1;
	}

	protected function createNewBlock(array $blockInfo, Module $module, $key, string $template)
	{
		static $blockPositions = null;

		/**
		 * @var BlockHandler $blockHandler
		 */
		$blockHandler = icms::handler('icms_view_block');

		if ($blockPositions === null) {
			$blockPositions = array_flip(
				$blockHandler->getBlockPositions()
			);
		}

		$side = 1;
		if (isset($blockInfo['position'], $blockPositions[$blockInfo['position']])) {
			$side = $blockPositions[$blockInfo['position']];
		}

		$options = !empty($block['options']) ? trim($block['options']) : '';

		/**
		 * @var Block $newBlock
		 */
		$newBlock = $blockHandler->create();
		$newBlock->mid = $module->mid;
		$newBlock->func_num = $key;
		$newBlock->options = $options;
		$newBlock->name = $this->getTranslatedName($blockInfo['name']);
		$newBlock->title = $this->getTranslatedName($blockInfo['name']);
		$newBlock->content = '';
		$newBlock->side = $side;
		$newBlock->weight = $blockInfo['weight'] ?? 0;
		$newBlock->visible = isset($blockInfo['visible']) && $blockInfo['visible'];
		$newBlock->block_type = ($module->dirname === 'system') ? Block::BLOCK_TYPE_SYSTEM : Block::BLOCK_TYPE_MODULE;
		$newBlock->c_type = Block::CONTENT_TYPE_HTML;
		$newBlock->isactive = 1;
		$newBlock->dirname = $module->dirname;
		$newBlock->func_file = $blockInfo['file'];
		$newBlock->show_func = $blockInfo['show_func'];
		$newBlock->edit_func = isset($blockInfo['edit_func']) ? trim($blockInfo['edit_func']) : '';
		$newBlock->template = $template;
		$newBlock->bcachetime = 0;
		$newBlock->last_modified = time();

		return $newBlock;
	}
}