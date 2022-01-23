<?php

use ImpressCMS\Core\Database\AbstractDatabaseMigration;

class AddEncryptCookiesSettingsOption extends AbstractDatabaseMigration
{
	protected function up(): void
	{
		$this->insert(
			$this->prefix('config'),
			[
				[
					'conf_modid' => '0',
					'conf_catid' => '1',
					'conf_name' => 'encrypt_cookies',
					'conf_title' => '_MD_AM_ENCRYPT_COOKIES',
					'conf_value' => '0',
					'conf_desc' => '_MD_AM_ENCRYPT_COOKIESDSC',
					'conf_formtype' => 'yesno',
					'conf_valuetype' => 'int',
					'conf_order' => '27'
				]
			]
		);
	}

	protected function down(): void
	{
		$this->delete(
			$this->prefix('config'),
			[
				'conf_modid' => 0,
				'conf_catid' => 1,
				'conf_name' => 'encrypt_cookies'
			]
		);
	}
}
