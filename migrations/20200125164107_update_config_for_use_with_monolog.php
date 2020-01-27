<?php

use Phoenix\Migration\AbstractMigration;

class UpdateConfigForUseWithMonolog extends AbstractMigration
{
    protected function up(): void
    {
        $this->delete(
        	$this->prefix('configoption'),
			[
				'confop_name' => '_MD_AM_DEBUGMODE2'
			]
		);
		$this->delete(
			$this->prefix('configoption'),
			[
				'confop_name' => '_MD_AM_DEBUGMODE3'
			]
		);

		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => 1
			],
			[
				'conf_name' => 'debug_mode'
			],
			'conf_value > 1'
		);
    }

    protected function down(): void
    {
    	$conf_ids = $this->select('SELECT conf_id FROM `' . $this->prefix('config') . '` WHERE conf_name = \'debug_mode\' LIMIT 1');
		$conf_id = $conf_ids[0]['conf_id'];

		$this->insert(
			$this->prefix('configoption'),
			[
				[
					'confop_name' => '_MD_AM_DEBUGMODE2',
					'confop_value' => 2,
					'conf_id' => $conf_id
				],
				[
					'confop_name' => '_MD_AM_DEBUGMODE3',
					'confop_value' => 3,
					'conf_id' => $conf_id
				]
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
	private function prefix(string $table): string {
		return \icms::getInstance()->get('db-connection-1')->prefix($table);
	}
}
