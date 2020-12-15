<?php

use Phoenix\Migration\AbstractMigration;

class RemoveUseCustomRedirectionConfigOption extends AbstractMigration
{
    protected function up(): void
    {
    	$this->delete(
			$this->prefix('config'),
			[
				'conf_name' => 'use_custom_redirection'
			]
		);
    	$this->delete(
    		$this->prefix('tplfile'),
			[
				'tpl_file' => 'system_redirect.html',
				'tpl_tplset' => 'default'
			]
		);
    }

    protected function down(): void
    {
    	$this->insert(
			$this->prefix('config'),
			[
				'conf_modid' => 0,
				'conf_name' => 'use_custom_redirection',
				'conf_catid' => 10,
				'conf_title' => '_MD_AM_CUSTOMRED',
				'conf_value' => 1,
				'conf_desc' => '_MD_AM_CUSTOMREDDSC',
				'conf_formtype' => 'yesno',
				'conf_valuetype' => 'int',
				'conf_order' => 8
			]
		);
		$this->insert(
			$this->prefix('tplfile'),
			[
				'tpl_refid' => 1,
				'tpl_module' => 'system',
				'tpl_tplset' => 'default',
				'tpl_file' => 'system_redirect.html',
				'tpl_desc' => '',
				'tpl_lastmodified' => time(),
				'tpl_lastimported' => 0,
				'tpl_type' => 'module',
			]
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
		return \icms::getInstance()->get('db-connection-1')->prefix($table);
	}
}
