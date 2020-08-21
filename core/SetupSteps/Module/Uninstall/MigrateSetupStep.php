<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;


use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class MigrateSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$migrationsPath = ICMS_MODULES_PATH . '/' . $module->dirname . '/migrations/';
		if (!file_exists($migrationsPath)) {
			return true;
		}

		$symfonyConsoleApplication = new \Symfony\Component\Console\Application('icms-setup-action');
		$symfonyConsoleApplication->setAutoExit(false);
		$symfonyConsoleApplication->add(new \Phoenix\Command\RollbackCommand());
		$symfonyConsoleApplication->run(new \Symfony\Component\Console\Input\ArrayInput([
			'command' => 'rollback',
			'--dir' => ['module/' . $module->dirname],
			'--config_type' => 'php',
			'--all' => true,
			'--config' => ICMS_ROOT_PATH . '/phoenix.php',
		]), $output);

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 0;
	}
}