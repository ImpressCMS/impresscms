<?php

namespace ImpressCMS\Core\Commands;

use ImpressCMS\Core\Models\ModuleHandler;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command that updates module
 *
 * @package ImpressCMS\Core\Commands
 */
class ModuleUpdateCommand extends Command
{

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this
			->setName('module:update')
			->setDescription('Updates specific module')
			->addArgument('module', InputArgument::REQUIRED, 'Module dirname');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		\icms::getInstance()->registerCommonServiceVariables();

		$moduleName = $input->getArgument('module');

		\icms_loadLanguageFile('system', 'modules', true);
		\icms_loadLanguageFile($moduleName, 'common', false);
		\icms_loadLanguageFile($moduleName, 'modinfo', false);

		/**
		 * @var ModuleHandler $module_handler
		 */
		$module_handler = \icms::handler('icms_module');
		if ($module_handler->update(
			$moduleName,
			new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($output)
		)) {
			$output->writeln('Module updated successfully');
		} else {
			$output->writeln('There were some problems updating module');
		}

		return 0;
	}
}
