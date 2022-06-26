<?php

namespace ImpressCMS\Core\Providers;

use ImpressCMS\Core\Commands\CacheClearCommand;
use ImpressCMS\Core\Commands\GenerateAppKeyCommand;
use ImpressCMS\Core\Commands\ModuleInstallCommand;
use ImpressCMS\Core\Commands\ModuleUninstallCommand;
use ImpressCMS\Core\Commands\ModuleUpdateCommand;
use ImpressCMS\Core\Commands\RoutesListCommand;
use ImpressCMS\Core\Commands\TemplatesCacheClearCommand;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides system console commands
 */
class CommandsProvider extends AbstractServiceProvider
{
	public const CONSOLE_COMMAND_TAG = "console.command";

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		self::CONSOLE_COMMAND_TAG,
	];

    /**
     * @inheritDoc
     */
    public function register()
    {
		$this->leagueContainer
			->add(GenerateAppKeyCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;
		$this->leagueContainer
			->add(CacheClearCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;

		$this->leagueContainer
			->add(RoutesListCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG);

		$this->leagueContainer
			->add(TemplatesCacheClearCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;
		$this->leagueContainer
			->add(ModuleInstallCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;
		$this->leagueContainer
			->add(ModuleUpdateCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;
		$this->leagueContainer
			->add(ModuleUninstallCommand::class)
			->addTag(self::CONSOLE_COMMAND_TAG)
		;
    }
}