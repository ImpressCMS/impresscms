<?php


namespace ImpressCMS\Core\Commands;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command that clears cache
 *
 * @package ImpressCMS\Core\Commands
 */
class CacheClearCommand extends Command implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this->setDescription('Clears cache')
			->setName('cache:clear');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/**
		 * @var CacheItemPoolInterface $cache
		 */
		$cache = $this->container->get('cache');
		$cache->clear();
		$output->writeln('Cached cleared successfully');
	}
}