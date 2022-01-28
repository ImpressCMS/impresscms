<?php

namespace ImpressCMS\Core\Providers;

use League\Container\Container;
use League\Container\Definition\DefinitionInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Providers all system setup steps
 */
class SetupStepsProvider extends AbstractServiceProvider
{
	public const SETUP_STEP_MODULE_INSTALL = "setup_step.module.install";
	public const SETUP_STEP_MODULE_UPDATE = "setup_step.module.update";
	public const SETUP_STEP_MODULE_UNINSTALL = "setup_step.module.uninstall";

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		self::SETUP_STEP_MODULE_INSTALL,
		self::SETUP_STEP_MODULE_UPDATE,
		self::SETUP_STEP_MODULE_UNINSTALL,
	];

	/**
	 * @inheritDoc
	 */
	public function register()
	{
		$this->addModuleInstallSteps();
		$this->addModuleUpdateSteps();
		$this->addModuleUninstallSteps();
	}

	protected function addModuleInstallSteps(): void
	{
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\AutotasksSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\MigrateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\BlockSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
			->addMethodCall('setLeagueContainer',['container'])
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\ConfigSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\ScriptSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\TablesSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\ViewTemplateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\CopyAssetsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
			->addMethodCall('setLeagueContainer',['container'])
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Install\CacheClearSetupStep::class)
			->addArgument('cache')
			->addTag(self::SETUP_STEP_MODULE_INSTALL)
		;
	}

	protected function addModuleUpdateSteps(): void
	{
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\AutotasksSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\BlocksSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
			->addMethodCall('setLeagueContainer',['container'])
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\MigrateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\ConfigSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\ScriptSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\ViewTemplateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\CopyAssetsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
			->addMethodCall('setLeagueContainer',['container'])
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Update\CacheClearSetupStep::class)
			->addArgument('cache')
			->addTag(self::SETUP_STEP_MODULE_UPDATE)
		;
	}

	protected function addModuleUninstallSteps(): void
	{
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\AutotasksSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\BlockSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\MigrateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\CommentsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\ConfigSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\DataPageSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\FilesSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\GroupPermissionsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\NotificationsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\ScriptSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\TablesSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\UrlLinksSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\CopyAssetsSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
			->addMethodCall('setLeagueContainer',['container'])
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\ViewTemplateSetupStep::class)
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
		$this->leagueContainer
			->add(\ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall\CacheClearSetupStep::class)
			->addArgument('cache')
			->addTag(self::SETUP_STEP_MODULE_UNINSTALL)
		;
	}
}