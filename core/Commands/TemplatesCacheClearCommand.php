<?php


namespace ImpressCMS\Core\Commands;


use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command that clears compiled templates
 *
 * @package ImpressCMS\Core\Commands
 */
class TemplatesCacheClearCommand extends Command implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this->setDescription('Clears compiled templates cache')
			->setName('templates:cache:clear');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/**
		 * @var Filesystem $fs
		 */
		$fs = $this->container->get('filesystem.compiled');
		foreach ($fs->listContents('', true) as $file) {
			if (($file['type'] !== 'file') || ($file['extension'] !== 'php')) {
				continue;
			}
			$fs->delete($file['path']);
		}
		$output->writeln('Templates cache cleared successfully');

		return 0;
	}
}