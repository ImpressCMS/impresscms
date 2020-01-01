<?php

namespace ImpressCMS\Core\Providers;

use icms;
use icms_module_Handler as ModuleHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Module service provider
 */
class ModuleServiceProvider extends AbstractServiceProvider
{
	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'module'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('module', function () {
			$module_handler = icms::handler("icms_module");
			$modules = $module_handler->getObjects();
			foreach ($modules as $module) $module->registerClassPath(TRUE);

			$inAdmin = (defined('ICMS_IN_ADMIN') && (int)ICMS_IN_ADMIN);

			$module = NULL;
			if (preg_match('/modules\/([^\/]+)/', $_SERVER['REQUEST_URI'], $matches)) {
				$path = ICMS_MODULES_PATH . DIRECTORY_SEPARATOR . $matches[1];
				if (!file_exists($path)) {
					include_once ICMS_ROOT_PATH . '/header.php';
					echo "<h4>" . _MODULENOEXIST . "</h4>";
					include_once ICMS_ROOT_PATH . '/footer.php';
					exit();
				}
				if ($inAdmin || file_exists($path . '/xoops_version.php') || file_exists($path . '/icms_version.php')) {
					/* @var $module icms_module_Object */
					$module = icms::handler("icms_module")->getByDirname($matches[1], TRUE);
					if (!$inAdmin && (!$module || !$module->getVar('isactive'))) {
						include_once ICMS_ROOT_PATH . '/header.php';
						echo "<h4>" . _MODULENOEXIST . "</h4>";
						include_once ICMS_ROOT_PATH . '/footer.php';
						exit();
					}
				}
			} else {
				return null;
			}
			if (!ModuleHandler::checkModuleAccess($module, $inAdmin)) {
				redirect_header(ICMS_URL . "/user.php", 3, _NOPERM, FALSE);
			}

			$module->launch();
			return $module ? $module : NULL;
		});
	}
}