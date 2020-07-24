<?php


namespace ImpressCMS\Core\Commands;

use ImpressCMS\Core\SetupSteps\OutputDecorator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command that uninstall module
 *
 * @package ImpressCMS\Core\Commands
 */
class ModuleUninstallCommand extends Command
{

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this
			->setName('module:uninstall')
			->setDescription('Uninstalls specific module')
			->addArgument('module', InputArgument::REQUIRED, 'Module dirname');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$modName = $input->getArgument('module');
		if ($modName === 'system') {
			$output->writeln('<error>Can\'t uninstall system module</error>');
			return 1;
		}

		\icms::getInstance()->registerCommonServiceVariables();

		\icms_loadLanguageFile('system', 'modules', true);

		/**
		 * @var \icms_module_Handler $module_handler
		 */
		$module_handler = \icms::handler('icms_module');
		if ($module_handler->uninstall(
			$modName,
			new OutputDecorator($output)
		)) {
			$output->writeln('Module uninstalled successfully');
		} else {
			$output->writeln('There were some problems uninstalling module');
		}

		return 0;
	}
}