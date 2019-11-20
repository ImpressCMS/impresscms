<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class ConfigModuleInstallationHelper implements ModuleInstallationHelperInterface
{

	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$configs = (array)$module->getInfo('config');
		if ($module->getVar('hascomments') != 0) {
			include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
			$configs[] = [
				'name' => 'com_rule',
				'title' => '_CM_COMRULES',
				'description' => '',
				'formtype' => 'select',
				'valuetype' => 'int',
				'default' => 1,
				'options' => [
					'_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE,
					'_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL,
					'_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER,
					'_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN
				]
			];
			$configs[] = [
				'name' => 'com_anonpost',
				'title' => '_CM_COMANONPOST',
				'description' => '',
				'formtype' => 'yesno',
				'valuetype' => 'int',
				'default' => 0,
			];
		}

		if ($module->getVar('hasnotification') != 0) {
			include_once ICMS_INCLUDE_PATH . '/notification_constants.php';
			$options = [
				'_NOT_CONFIG_DISABLE' => XOOPS_NOTIFICATION_DISABLE,
				'_NOT_CONFIG_ENABLEBLOCK' => XOOPS_NOTIFICATION_ENABLEBLOCK,
				'_NOT_CONFIG_ENABLEINLINE' => XOOPS_NOTIFICATION_ENABLEINLINE,
				'_NOT_CONFIG_ENABLEBOTH' => XOOPS_NOTIFICATION_ENABLEBOTH,
			];
			$configs[] = [
				'name' => 'notification_enabled',
				'title' => '_NOT_CONFIG_ENABLE',
				'description' => '_NOT_CONFIG_ENABLEDSC',
				'formtype' => 'select',
				'valuetype' => 'int',
				'default' => XOOPS_NOTIFICATION_ENABLEBOTH,
				'options' => $options,
			];
			// Event-specific notification options
			$options = [];
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
			$configs[] = [
				'name' => 'notification_events',
				'title' => '_NOT_CONFIG_EVENTS',
				'description' => '_NOT_CONFIG_EVENTSDSC',
				'formtype' => 'select_multi',
				'valuetype' => 'array',
				'default' => array_values($options),
				'options' => $options
			];
		}

		if ($configs !== false) {
			$logger->info(_MD_AM_CONFIG_ADDING);
			/**
			 * @var \icms_config_Handler $config_handler
			 */
			$config_handler = icms::handler('icms_config');
			$order = 0;
			foreach ($configs as $config) {
				/**
				 * @var \icms_config_item_Object $confobj
				 */
				$confobj = &$config_handler->createConfig();
				$confobj->setVar('conf_modid', $module->getVar('mid'));
				$confobj->setVar('conf_catid', 0);
				$confobj->setVar('conf_name', $config['name']);
				$confobj->setVar('conf_title', $config['title'], true);
				$confobj->setVar('conf_desc', $config['description'], true);
				$confobj->setVar('conf_formtype', $config['formtype']);
				$confobj->setVar('conf_valuetype', $config['valuetype']);
				$confobj->setConfValueForInput($config['default'], true);
				$confobj->setVar('conf_order', $order);
				$confop_msgs = [];
				if (isset($config['options']) && is_array($config['options'])) {
					foreach ($config['options'] as $key => $value) {
						$confop = &$config_handler->createConfigOption();
						$confop->setVar('confop_name', $key, true);
						$confop->setVar('confop_value', $value, true);
						$confobj->setConfOptions($confop);
						$confop_msgs[] = sprintf('    ' . _MD_AM_CONFIGOPTION_ADDED, $key, $value);
						unset($confop);
					}
				}
				$order++;
				if ($config_handler->insertConfig($confobj) !== false) {
					$logger->info(
						sprintf('  ' . _MD_AM_CONFIG_ADDED . implode(PHP_EOL, $confop_msgs), $config['name'])
					);
				} else {
					$logger->error(
						sprintf('  ' . _MD_AM_CONFIG_ADD_FAIL . implode(PHP_EOL, $confop_msgs), $config['name'])
					);
				}
				unset($confobj);
			}
			unset($configs);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 1;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		if ($module->getVar('hasconfig') == 0 && $module->getVar('hascomments') == 0) {
			return true;
		}
		$config_handler = \icms::handler('icms_config');
		$configs = $config_handler->getConfigs(new icms_db_criteria_Item('conf_modid', $module->getVar('mid')));
		$confcount = count($configs);
		if ($confcount > 0) {
			return true;
		}
		$logger->info(_MD_AM_CONFIGOPTIONS_DELETE);
		for ($i = 0; $i < $confcount; $i++) {
			if (!$config_handler->deleteConfig($configs[$i])) {
				$logger->error(
					sprintf(
						'  ' . _MD_AM_CONFIGOPTION_DELETE_FAIL,
						icms_conv_nr2local($configs[$i]->getVar('conf_id'))
					)
				);
			} else {
				$logger->info(
					sprintf(
						'  ' . _MD_AM_CONFIGOPTION_DELETED,
						icms_conv_nr2local($configs[$i]->getVar('conf_id'))
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
		return 5;
	}
}