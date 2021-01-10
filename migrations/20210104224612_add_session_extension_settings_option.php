<?php

use ImpressCMS\Core\Facades\Config;
use Phoenix\Migration\AbstractMigration;

class AddSessionExtensionSettingsOption extends AbstractMigration
{
    protected function up(): void
    {
    	$this->execute(
    		'UPDATE ' . $this->prefix('config') . ' SET conf_order = conf_order + 1 WHERE conf_order > 21'
		);
		$this->insert(
			$this->prefix('config'),
			[
				'conf_modid' => 0,
				'conf_catid' => Config::CATEGORY_MAIN,
				'conf_name' => 'session_autoextend',
				'conf_title' => '_MD_AM_SESSION_AUTOEXTEND',
				'conf_desc' => '_MD_AM_SESSION_AUTOEXTEND_DSC',
				'conf_value' => 1,
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'intval',
				'conf_order' => 22
			]
		);
    }

    protected function down(): void
    {
		$this->delete(
			$this->prefix('config'),
			[
				'conf_name' => 'session_autoextend',
				'conf_modid' => 0,
				'conf_catid' => Config::CATEGORY_MAIN,
			]
		);
		$this->execute(
			'UPDATE ' . $this->prefix('config') . ' SET conf_order = conf_order - 1 WHERE conf_order > 21'
		);
    }

	/**
	 * Prefix table
	 *
	 * @param string $table Table to prefix
	 *
	 * @return string
	 */
	private function prefix(string $table): string
	{
		return icms::getInstance()->get('db-connection-1')->prefix($table);
	}
}
