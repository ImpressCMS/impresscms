<?php

namespace ImpressCMS\Core\SetupSteps\Module\Update;

use icms;
use icms_module_Object;
use icms_view_Tpl;
use ImpressCMS\Core\SetupSteps\Module\Install\ViewTemplateSetupStep as InstallViewTemplateSetupStep;
use ImpressCMS\Core\SetupSteps\OutputDecorator;

class ViewTemplateSetupStep extends InstallViewTemplateSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		global $icmsConfig;

		$newmid = $module->mid;
		$tplfile_handler = &icms::handler('icms_view_template_file');
		$deltpl = $tplfile_handler->find('default', 'module', $newmid);
		$delng = array();
		if (is_array($deltpl)) {
			$xoopsDelTpl = new icms_view_Tpl();
			// clear cache files
			$xoopsDelTpl->clear_cache(null, 'mod_' . $module->dirname);
			// delete template file entry in db
			$dcount = count($deltpl);
			for ($i = 0; $i < $dcount; $i++) {
				if (!$tplfile_handler->delete($deltpl[$i])) {
					$delng[] = $deltpl[$i]->getVar('tpl_file');
				}
			}
		}

		$templates = $module->getInfo('templates');
		if ($templates !== false) {
			$output->info(_MD_AM_MOD_UP_TEM);
			$output->incrIndent();
			foreach ($templates as $tpl) {
				$tpl['file'] = trim($tpl['file']);
				if (!in_array($tpl['file'], $delng)) {
					$tpldata = $this->readTemplate($module->dirname, $tpl['file']);
					$tplfile = &$tplfile_handler->create();
					$tplfile->tpl_refid = $newmid;
					$tplfile->tpl_lastimported = 0;
					$tplfile->tpl_lastmodified = time();
					if (preg_match("/\.css$/i", $tpl['file'])) {
						$tplfile->tpl_type = 'css';
					} else {
						$tplfile->tpl_type = 'module';
					}
					$tplfile->setVar('tpl_source', $tpldata, true);
					$tplfile->tpl_module = $module->dirname;
					$tplfile->tpl_tplset = 'default';
					$tplfile->setVar('tpl_file', $tpl['file'], true);
					$tplfile->setVar('tpl_desc', $tpl['description'], true);
					if (!$tplfile_handler->insert($tplfile)) {
						$output->error(_MD_AM_TEMPLATE_INSERT_FAIL, $tpl['file']);
					} else {
						$newid = $tplfile->getVar('tpl_id');
						$output->success(_MD_AM_TEMPLATE_INSERTED, $tpl['file'], $newid);
						if ($icmsConfig['template_set'] == 'default') {
							if (!icms_view_Tpl::template_touch($newid)) {
								$output->error(_MD_AM_TEMPLATE_RECOMPILE_FAIL, $tpl['file']);
							} else {
								$output->success(_MD_AM_TEMPLATE_RECOMPILED, $tpl['file']);
							}
						}
					}
					unset($tpldata);
				} else {
					$output->error(_MD_AM_TEMPLATE_DELETE_FAIL);
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
		return 10;
	}
}