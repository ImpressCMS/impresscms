<?php

namespace ImpressCMS\Core\Commands;

use icms;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\ModuleHandler;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Providers\SetupStepsProvider;
use ImpressCMS\Core\View\Theme\ThemeFactory;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function icms_loadLanguageFile;

/**
 * Command that uninstall theme
 *
 * @package ImpressCMS\Core\Commands
 */
class ThemeUninstallCommand extends Command implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this
			->setName('theme:uninstall')
			->setDescription('Uninstalls specific theme')
			->addArgument('theme', InputArgument::REQUIRED, 'Theme dirname');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		\icms::getInstance()->registerCommonServiceVariables();

		$themeInfo = ThemeFactory::getThemeInfo(
			$input->getArgument('theme')
		);

		if ($themeInfo === null) {
			$output->writeln('Theme not found');
			return 1;
		}

		$decoratedOutput = new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($output);

		$decoratedOutput->info('Uninstalling theme from ' . $themeInfo->path . '...');
		$decoratedOutput->writeln(_VERSION . ': ' . icms_conv_nr2local($themeInfo->version));

		/**
		 * @var SetupStepInterface[] $steps
		 */
		$steps = (array)$this->container->get(SetupStepsProvider::SETUP_STEP_THEME_UNINSTALL);
		usort($steps, function (SetupStepInterface $stepA, SetupStepInterface $stepB) {
			return $stepA->getPriority() > $stepB->getPriority();
		});

		foreach ($steps as $step) {
			if ($step instanceof ContainerAwareInterface) {
				$step->setContainer($this->container);
			}
			if (!$step->execute($themeInfo, $decoratedOutput)) {
				$output->fatal('Failed to uninstall', $themeInfo->path);
				break;
			}
		}

		return 0;
	}
}