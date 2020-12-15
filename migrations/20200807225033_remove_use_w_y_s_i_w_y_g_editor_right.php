<?php

use Phoenix\Migration\AbstractMigration;

class RemoveUseWYSIWYGEditorRight extends AbstractMigration
{
    protected function up(): void
    {
		$this->delete(
			$this->prefix('group_permission'),
			[
				'gperm_name' => 'use_wysiwygeditor'
			]
		);
    }

    protected function down(): void
    {
		$this->insert(
			$this->prefix('group_permission'),
			[
				'gperm_groupid' => 1,
				'gperm_itemid' => 1,
				'gperm_modid' => 1,
				'gperm_name' => 'use_wysiwygeditor'
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
