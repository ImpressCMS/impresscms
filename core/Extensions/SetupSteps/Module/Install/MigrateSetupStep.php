<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;

use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

class MigrateSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$migrationsPath = ICMS_MODULES_PATH . '/' . $module->dirname . '/migrations/';
		if (!file_exists($migrationsPath)) {
			return true;
		}

		$symfonyConsoleApplication = new \Symfony\Component\Console\Application('icms-setup-action');
		$symfonyConsoleApplication->setAutoExit(false);
		$symfonyConsoleApplication->add(new \Phoenix\Command\MigrateCommand());
		$symfonyConsoleApplication->run(new \Symfony\Component\Console\Input\ArrayInput([
			'command' => 'migrate',
			'--dir' => ['module/' . $module->dirname],
			'--config_type' => 'php',
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