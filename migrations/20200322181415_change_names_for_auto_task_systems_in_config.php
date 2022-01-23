<?php

use ImpressCMS\Core\Database\AbstractDatabaseMigration;

class ChangeNamesForAutoTaskSystemsInConfig extends AbstractDatabaseMigration
{
	protected function up(): void
	{
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'IcmsAutoTasksInternal'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'internal',
			]
		);
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'IcmsAutoTasksAt'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'at'
			]
		);
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'IcmsAutoTasksCron'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'cron'
			]
		);
	}

	protected function down(): void
	{
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'internal'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'IcmsAutoTasksInternal'
			]
		);
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'at'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'IcmsAutoTasksAt'
			]
		);
		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 'cron'
			],
			[
				'conf_name' => 'autotasks_system',
				'conf_value' => 'IcmsAutoTasksCron'
			]
		);
	}
}
