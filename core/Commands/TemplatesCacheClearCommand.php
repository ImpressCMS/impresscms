<?php


namespace ImpressCMS\Core\Commands;


use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command that clears compiled templates
 *
 * @package ImpressCMS\Core\Commands
 */
class TemplatesCacheClearCommand extends Command
{

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
		$fs = new Filesystem(
			new Local(ICMS_COMPILE_PATH)
		);
		foreach ($fs->listContents('', true) as $file) {
			if (($file['type'] !== 'file') || ($file['extension'] !== 'php')) {
				continue;
			}
			$fs->delete($file['path']);
		}
		$output->writeln('Templates cache cleared successfully');
	}
}