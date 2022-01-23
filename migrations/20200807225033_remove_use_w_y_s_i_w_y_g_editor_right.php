<?php

use ImpressCMS\Core\Database\AbstractDatabaseMigration;

class RemoveUseWYSIWYGEditorRight extends AbstractDatabaseMigration
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
}
