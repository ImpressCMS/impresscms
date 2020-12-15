<?php


namespace ImpressCMS\Core\Commands;


use Defuse\Crypto\Key;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to generate app_key
 *
 * @package ImpressCMS\Core\Commands
 */
class GenerateAppKeyCommand extends Command
{

	/**
	 * @inheritDoc
	 */
	protected function configure()
	{
		$this->setDescription('Generates new APP_KEY and prints to console')
			->setName('generate:app:key');
	}

	/**
	 * @inheritDoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln(Key::createNewRandomKey()->saveToAsciiSafeString());

		return 0;
	}

}