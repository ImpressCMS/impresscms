<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\ConfigSetupStep as InstallConfigSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Models\Module;

class ConfigSetupStep extends InstallConfigSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		// first delete all config entries
		$config_handler = icms::handler('icms_config');
		$configs = $config_handler->getConfigs(new CriteriaItem('conf_modid', $module->getVar('mid')));
		$confcount = count($configs);
		$config_delng = array();
		if ($confcount > 0) {
			$output->info(_MD_AM_CONFIGOPTION_DELETED);
			$output->incrIndent();
			for ($i = 0; $i < $confcount; $i++) {
				if (!$config_handler->deleteConfig($configs[$i])) {
					$output->error(_MD_AM_CONFIGOPTION_DELETE_FAIL, $configs[$i]->getVar('conf_id'));
					// save the name of config failed to delete for later use
					$config_delng[] = $configs[$i]->getVar('conf_name');
				} else {
					$config_old[$configs[$i]->getVar('conf_name')]['value'] = $configs[$i]->conf_value;
					$config_old[$configs[$i]->getVar('conf_name')]['formtype'] = $configs[$i]->getVar('conf_formtype');
					$config_old[$configs[$i]->getVar('conf_name')]['valuetype'] = $configs[$i]->getVar('conf_valuetype');
					$output->error(_MD_AM_CONFIGOPTION_DELETED, $configs[$i]->getVar('conf_id'));
				}
			}
			$output->decrIndent();
		}

		// now reinsert them with the new settings
		$configs = (array)$module->getInfo('config');
		if ($module->getVar('hascomments') != 0) {
			$this->includeCommentsConfig($configs);
		}

		if ($module->getVar('hasnotification') != 0) {
			// Main notification options
			include_once ICMS_INCLUDE_PATH . '/notification_constants.php';
			$options = array(
				'_NOT_CONFIG_DISABLE' => XOOPS_NOTIFICATION_DISABLE,
				'_NOT_CONFIG_ENABLEBLOCK' => XOOPS_NOTIFICATION_ENABLEBLOCK,
				'_NOT_CONFIG_ENABLEINLINE' => XOOPS_NOTIFICATION_ENABLEINLINE,
				'_NOT_CONFIG_ENABLEBOTH' => XOOPS_NOTIFICATION_ENABLEBOTH,
			);

			$configs[] = array(
				'name' => 'notification_enabled',
				'title' => '_NOT_CONFIG_ENABLE',
				'description' => '_NOT_CONFIG_ENABLEDSC',
				'formtype' => 'select',
				'valuetype' => 'int',
				'default' => XOOPS_NOTIFICATION_ENABLEBOTH,
				'options' => $options
			);
			// Event specific notification options
			// FIXME: for some reason the default doesn't come up properly
			//  initially is ok, but not when 'update' module..
			$options = array();
			$notification_handler = icms::handler('icms_data_notification');
			$categories = &$notification_handler->categoryInfo('', $module->getVar('mid'));
			foreach ($categories as $category) {
				$events = &$notification_handler->categoryEvents($category['name'], false, $module->getVar('mid'));
				foreach ($events as $event) {
					if (!empty($event['invisible'])) {
						continue;
					}
					$option_name = $category['title'] . ' : ' . $event['title'];
					$option_value = $category['name'] . '-' . $event['name'];
					$options[$option_name] = $option_value;
				}
			}
			$configs[] = array(
				'name' => 'notification_events',
				'title' => '_NOT_CONFIG_EVENTS',
				'description' => '_NOT_CONFIG_EVENTSDSC',
				'formtype' => 'select_multi',
				'valuetype' => 'array',
				'default' => array_values($options),
				'options' => $options
			);
		}

		if (!empty($configs)) {
			$output->info(_MD_AM_CONFIG_ADDING);
			$output->incrIndent();
			$config_handler = icms::handler('icms_config');
			$order = 0;
			foreach ($configs as $config) {
				// only insert ones that have been deleted previously with success
				if (!in_array($config['name'], $config_delng)) {
					$confobj = &$config_handler->createConfig();
					$confobj->setVar('conf_modid', $module->mid);
					$confobj->setVar('conf_catid', 0);
					$confobj->setVar('conf_name', $config['name']);
					$confobj->setVar('conf_title', $config['title'], true);
					$confobj->setVar('conf_desc', $config['description'], true);
					$confobj->setVar('conf_formtype', $config['formtype']);
					$confobj->setVar('conf_valuetype', $config['valuetype']);
					if (isset($config_old[$config['name']]['value'])
						&& $config_old[$config['name']]['formtype'] == $config['formtype']
						&& $config_old[$config['name']]['valuetype'] == $config['valuetype']
					) {
						// preserve the old value if any
						// form type and value type must be the same
						// need to deal with arrays, because getInfo('config') doesn't convert arrays
						if (is_array($config_old[$config['name']]['value'])) {
							$confobj->setVar('conf_value', serialize($config_old[$config['name']]['value']), true);
						} else {
							$confobj->setVar('conf_value', $config_old[$config['name']]['value'], true);
						}
					} else {
						$confobj->setConfValueForInput($config['default'], true);
					}
					$confobj->setVar('conf_order', $order);
					$confop_msgs = [];
					if (isset($config['options']) && is_array($config['options'])) {
						foreach ($config['options'] as $key => $value) {
							$confop = &$config_handler->createConfigOption();
							$confop->setVar('confop_name', $key, true);
							$confop->setVar('confop_value', $value, true);
							$confobj->setConfOptions($confop);
							$confop_msgs[] = [_MD_AM_CONFIGOPTION_ADDED, $key, $value];
							unset($confop);
						}
					}
					$order++;
					if (false !== $config_handler->insertConfig($confobj)) {
						$output->success(_MD_AM_CONFIG_ADDED, $config['name']);
						$output->incrIndent();
						foreach ($confop_msgs as $msg) {
							$output->msg($msg[0], $msg[1], $msg[2]);
						}
						$output->decrIndent();
					} else {
						$output->error(_MD_AM_CONFIG_ADD_FAIL, $config['name']);
					}
					unset($confobj);
				}
			}
			unset($configs);
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 18;
	}
}