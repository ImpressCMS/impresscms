<?php


namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use icms_view_Tpl;
use Psr\Log\LoggerInterface;

class ViewTemplateModuleInstallationHelper implements ModuleInstallationHelperInterface
{
	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$newmid = $module->getVar('mid');
		$templates = $module->getInfo('templates');
		if ($templates === false) {
			return true;
		}
		$logger->info(_MD_AM_TEMPLATES_ADDING);
		$dirname = $module->getVar('dirname');
		$handler = \icms::handler('icms_view_template_file');
		foreach ($templates as $tpl) {
			/**
			 * @var \icms_view_template_file_Object $tplfile
			 */
			$tplfile = $handler->create();
			$tpldata = $this->readTemplate($dirname, $tpl['file']);
			$tplfile->setVar('tpl_source', $tpldata, true);
			$tplfile->setVar('tpl_refid', $newmid);

			$tplfile->setVar('tpl_tplset', 'default');
			$tplfile->setVar('tpl_file', $tpl['file']);
			$tplfile->setVar('tpl_desc', $tpl['description'], true);
			$tplfile->setVar('tpl_module', $dirname);
			$tplfile->setVar('tpl_lastmodified', time());
			$tplfile->setVar('tpl_lastimported', 0);
			$tplfile->setVar('tpl_type', 'module');
			if ($tplfile->store()) {
				$newtplid = $tplfile->getVar('tpl_id');
				$logger->info(
					sprintf('  ' . _MD_AM_TEMPLATE_INSERTED, $tpl['file'], $newtplid)
				);

				if (icms_view_Tpl::template_touch($newtplid)) {
					$logger->info(
						sprintf('  ' . _MD_AM_TEMPLATE_COMPILED, $tpl['file'])
					);
				} else {
					$logger->error(
						sprintf('  ' . _MD_AM_TEMPLATE_COMPILE_FAIL, $tpl['file'], $newtplid)
					);
				}
			} else {
				$logger->error(
					sprintf('  ' . _MD_AM_TEMPLATE_INSERT_FAIL, $tpl['file'])
				);
			}
		}

		icms_view_Tpl::template_clear_module_cache($newmid);

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
		$file = ICMS_MODULES_PATH . '/' . $dirname . '/templates/' . $filename;
		if (!file_exists($file)) {
			return $ret;
		}
		return str_replace(["\r\n", "\n"], ["\n", "\r\n"], file_get_contents($file));
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$tplfile_handler = \icms::handler('icms_view_template_file');
		$templates = $tplfile_handler->find(null, 'module', $module->getVar('mid'));
		$tcount = count($templates);
		if ($tcount === 0) {
			return true;
		}

		$logger->info(_MD_AM_TEMPLATES_DELETE);
		for ($i = 0; $i < $tcount; $i++) {
			if (!$tplfile_handler->delete($templates[$i])) {
				$logger->error(
					sprintf(
						'  ' . _MD_AM_TEMPLATE_DELETE_FAIL,
						$templates[$i]->getVar('tpl_file'),
						icms_conv_nr2local($templates[$i]->getVar('tpl_id'))
					)
				);
			} else {
				$logger->info(
					sprintf('  ' . _MD_AM_TEMPLATE_DELETED,
						icms_conv_nr2local($templates[$i]->getVar('tpl_file')),
						icms_conv_nr2local($templates[$i]->getVar('tpl_id'))
					)
				);
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return 1;
	}
}