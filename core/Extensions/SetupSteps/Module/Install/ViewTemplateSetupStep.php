<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\TemplateFile;
use ImpressCMS\Core\View\Template;

class ViewTemplateSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$newmid = $module->mid;
		$templates = $module->getInfo('templates');
		if ($templates === false) {
			return true;
		}
		$output->info(_MD_AM_TEMPLATES_ADDING);
		$dirname = $module->dirname;
		$handler = icms::handler('icms_view_template_file');
		$output->incrIndent();
		foreach ($templates as $tpl) {
			/**
			 * @var TemplateFile $tplfile
			 */
			$tplfile = $handler->create();
			$tpldata = $this->readTemplate($dirname, $tpl['file']);
			$tplfile->setVar('tpl_source', $tpldata, true);
			$tplfile->tpl_refid = $newmid;

			$tplfile->tpl_tplset = 'default';
			$tplfile->tpl_file = $tpl['file'];
			$tplfile->setVar('tpl_desc', $tpl['description'], true);
			$tplfile->tpl_module = $dirname;
			$tplfile->tpl_lastmodified = time();
			$tplfile->tpl_lastimported = 0;
			$tplfile->tpl_type = 'module';
			if ($tplfile->store()) {
				$newtplid = $tplfile->tpl_id;
				$output->success(_MD_AM_TEMPLATE_INSERTED, $tpl['file'], $newtplid);

				if (Template::template_touch($newtplid)) {
					$output->success(_MD_AM_TEMPLATE_COMPILED, $tpl['file']);
				} else {
					$output->error(_MD_AM_TEMPLATE_COMPILE_FAIL, $tpl['file'], $newtplid);
				}
			} else {
				$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $tpl['file']);
			}
		}
		$output->resetIndent();

		Template::template_clear_module_cache($newmid);

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
		$file = ICMS_MODULES_PATH.'/'.$dirname.'/templates/'.$filename;
		if (!file_exists($file)) {
			return $ret;
		}
		return str_replace(["\r\n", "\n"], ["\n", "\r\n"], file_get_contents($file));
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 0;
	}
}