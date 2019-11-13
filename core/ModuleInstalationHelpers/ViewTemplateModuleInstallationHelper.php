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
		if ($templates !== false) {
			$logger->info(_MD_AM_TEMPLATES_ADDING);
			$dirname = $module->getVar('dirname');
			$handler = \icms::handler('icms_view_template_file');
			foreach ($templates as $tpl) {
				/**
				 * @var \icms_view_template_file_Object $tplfile
				 */
				$tplfile = $handler->create();
				$tpldata = &xoops_module_gettemplate($dirname, $tpl['file']);
				$tplfile->setVar('tpl_source', $tpldata, true);
				$tplfile->setVar('tpl_refid', $newmid);

				$tplfile->setVar('tpl_tplset', 'default');
				$tplfile->setVar('tpl_file', $tpl['file']);
				$tplfile->setVar('tpl_desc', $tpl['description'], true);
				$tplfile->setVar('tpl_module', $dirname);
				$tplfile->setVar('tpl_lastmodified', time());
				$tplfile->setVar('tpl_lastimported', 0);
				$tplfile->setVar('tpl_type', 'module');
				if (!$tplfile->store()) {
					$logger->error(
						sprintf('  ' . _MD_AM_TEMPLATE_INSERT_FAIL, $tpl['file'])
					);
				} else {
					$newtplid = $tplfile->getVar('tpl_id');
					$logger->info(
						sprintf('  ' . _MD_AM_TEMPLATE_INSERTED, $tpl['file'], $newtplid)
					);

					// generate compiled file
					if (!icms_view_Tpl::template_touch($newtplid)) {
						$logger->error(
							sprintf('  ' . _MD_AM_TEMPLATE_COMPILE_FAIL, $tpl['file'], $newtplid)
						);
					} else {
						$logger->info(
							sprintf('  ' . _MD_AM_TEMPLATE_COMPILED, $tpl['file'])
						);
					}
				}
				unset($tpldata);
			}
		}
		icms_view_Tpl::template_clear_module_cache($newmid);
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 0;
	}
}