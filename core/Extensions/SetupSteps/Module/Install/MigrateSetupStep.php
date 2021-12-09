<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;

use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use Phoenix\Command\MigrateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

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

		$symfonyConsoleApplication = new Application('icms-setup-action');
		$symfonyConsoleApplication->setAutoExit(false);
		$symfonyConsoleApplication->add(new MigrateCommand());
		$symfonyConsoleApplication->run(new ArrayInput([
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
		return 99;
	}
}